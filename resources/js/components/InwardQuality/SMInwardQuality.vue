<template>
  <div>
    <!-- Content Wrapper. Contains page content -->
    <div class="row">
      <!-- left column -->
      <div class="col-md-12 mt-3">
        <!-- Search and Manage Inward Quality-->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">
              Search and Manage Inward Quality
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->

          <div class="card-body table-responsive">
            <div class="d-flex justify-content-between align-content-center mb-2">
              <div class="d-flex">
                <div>
                  <div class="d-flex align-items-center ml-4">
                    <label for="paginate" class="text-nowrap mr-2 mb-0 text-md">
                      Per Page
                    </label>
                    <select v-model="paginate" class="form-control form-control-sm">
                      <option value="10">10</option>
                      <option value="20">20</option>
                      <option value="30">30</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <input v-model="search" type="search" class="form-control " placeholder="Search By ..." />
              </div>
            </div>

            <div class="p-0">
              <table class="table table-hover table-bordered table-striped table-sm">
                <thead class="text-md">
                  <tr>
                    <th width="10%">
                      <a href="#" @click.prevent="updateSorting('inward_quality_id')">Sr. No.</a>
                      <span v-if="sort_field == 'inward_quality_id' ? 1 : 0">
                        <span v-if="sort_direction == 'asc' ? 1 : 0">&uarr;</span>
                        <span v-if="sort_direction == 'desc' ? 1 : 0">&darr;</span>
                      </span>
                    </th>
                    <th>
                      <a href="#" @click.prevent="updateSorting('quality_name')">Quality Name</a>
                      <span v-if="sort_field =='quality_name'? 1: 0">
                        <span v-if="sort_direction == 'asc'? 1: 0">&uarr;</span>
                        <span v-if="sort_direction == 'desc'? 1: 0">&darr;</span>
                      </span>
                    </th>
                    <th>
                      <a href="#" @click.prevent="updateSorting('inward_category_name')">Category</a>
                      <span v-if="sort_field =='inward_category_name'? 1: 0">
                        <span v-if="sort_direction == 'asc'? 1: 0">&uarr;</span>
                        <span v-if="sort_direction == 'desc'? 1: 0">&darr;</span>
                      </span>
                    </th>
                    <th width="15%">Action</th>
                  </tr>
                </thead>
                <tbody class="text-md">
                  <tr v-for="inwardquality in inwardqualities.data" v-bind:key="inwardquality.inward_quality_id">
                    <td>{{ inwardquality.inward_quality_id }}</td>
                    <td>{{ inwardquality.quality_name }}</td>
                    <td>{{ inwardquality.inward_category_name }}</td>

                    <td class="text-center">
                      <button class="btn btn-primary btn-sm text-md"
                        @click="editInwardQuality(inwardquality.inward_quality_id, inwardquality.inward_quality_category_id,inwardquality.inward_category_name,inwardquality.quality_name)">
                        <i class="fas fa-pen"></i>
                      </button>

                      <button class="btn btn-danger btn-sm text-md"
                        @click="deleteInwardQuality(inwardquality.inward_quality_id)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="row mt-4">
              <div class="col-sm-6 offset-5">
                <pagination :data="inwardqualities" @pagination-change-page="getAllInwardQualities"></pagination>
              </div>
            </div>
          </div>
        </div>

        <div v-if="inwardQualityId == -1 ? 0 : 1" class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit Inward Quality</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" @click="closeEditInwardQuality">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->

          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-2">
                <label for="qualitycategory" class="text-md col-form-label">Quality Category <span class="required-mark"
                    style="color: red;">*</span></label>
              </div>
              <div class="col-md-4">
                <model-select :options="qualityCategories" v-model="selectedQualityCategory"
                  placeholder="Select Quality Category">
                </model-select>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-2">
                <label for="qualityname" class="text-md col-form-label">Quality Name <span class="required-mark"
                    style="color: red;">*</span></label>
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" v-model="editQualityName" maxlength="50"
                  placeholder="Enter Quality Name...">
              </div>
            </div>
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" @click="updateInwardQuality" class="btn btn-primary text-md">Update</button>
          </div>
        </div>
      </div>
      <!--/.col (right) -->
    </div>
    <!-- /.row -->
  </div>
</template>

<script>
  import toastr from "toastr";
  import swal from "sweetalert2";
  import { ModelSelect } from 'vue-search-select'

  export default {
    name: "SMInwardQuality",
    components: {
      ModelSelect
    },
    data() {
      return {
        inwardqualities: {},
        paginate: "10",
        search: "",
        sort_direction: "asc",
        sort_field: "inward_quality_id",
        inwardQualityId: -1,
        editQualityName: "",
        qualityCategories: [],
        selectedQualityCategory: "",
        status: null,
        message: null,
        errors: null
      };
    },
    mounted() {
      this.getAllInwardQualities();
      this.loadQualityCategories();
    },
    watch: {
      paginate: function () {
        this.getAllInwardQualities();
      },
      search: function () {
        this.getAllInwardQualities();
      }
    },
    methods: {
      getAllInwardQualities: function (page = 1) {
        axios
          .get(
            "../api/inwardqualities?page=" +
            page +
            "&paginate=" +
            this.paginate +
            "&search=" +
            this.search +
            "&sortdirection=" +
            this.sort_direction +
            "&sortfield=" +
            this.sort_field
          )
          .then((response, err) => {
            if (err) {
            }
            this.inwardqualities = response.data;
          });
      },

      updateSorting: function (field) {
        if (this.sort_field == field) {
          this.sort_direction =
            this.sort_direction == "asc" ? "desc" : "asc";
        } else {
          this.sort_field = field;
        }
        this.getAllInwardQualities(this.inwardqualities.current_page);
      },

      editInwardQuality: function (inward_quality_id, inward_quality_category_id, inward_category_name, quality_name) {
        this.inwardQualityId = inward_quality_id;
        this.selectedQualityCategory = inward_quality_category_id;
        this.editQualityName = quality_name;
      },

      loadQualityCategories: function () {
        axios.get('../api/inwardqualitycategories').then((response) => {
          this.qualityCategories = response.data.qualityCategories.map(category => {
            return {
              value: category.qualityCategoryId,
              text: category.qualityCategoryName
            }
          });
        }).catch(err => {
          console.log(err);
          toastr["error"]("Something went Wrong");
        })
      },

      updateInwardQuality: function () {
        if ((this.selectedQualityCategory == '') || (this.editQualityName == '')) {
          toastr["error"]('All Fields are Required');
        } else {
          axios
            .put('../api/inwardquality/update/' + this.inwardQualityId, {
              editQualityName: this.editQualityName,
              editQualityCategoryId: this.selectedQualityCategory
            })
            .then((res) => {
              if (res.data.status == -1) {
                var errormsg = res.data.errors;

                try {
                  if (errormsg.editQualityName)
                    toastr["error"](errormsg.editQualityName);
                } catch (err) { }

                try {
                  if (errormsg.editQualityCategoryId)
                    toastr["error"](errormsg.editQualityCategoryId);
                } catch (err) { }

              } else if (res.data.status == 0) {
                toastr["warning"](res.data.message);
              } else if (res.data.status == 1) {
                swal.fire({
                  title: 'Success',
                  html: "<h5 style='color:#9C9794'>Inward Quality Data Updated Successfully!</h5>",
                  icon: 'success'
                }).then((result) => {
                  this.resetFields();
                  this.inwardQualityId = -1;
                  this.getAllInwardQualities(this.inwardqualities.current_page);
                });
              }
            }).catch((err) => {
              console.log(err.res.data.message);
              toastr["error"]('Something went Wrong.');
            });
        }
      },

      deleteInwardQuality: function (inward_quality_id) {
        axios
          .delete('../api/inwardquality/delete/' + inward_quality_id)
          .then((res) => {
            swal.fire({
              title: "Success",
              html:
                "<h5 style='color:#9C9794'>Inward Quality Deleted Successfully</h5>",
              icon: "success"
            }).then((result) => {
              this.getAllInwardQualities(this.inwardqualities.current_page);
            });
          })
          .catch((err) => {
            console.log(err.res.data.message);
            toastr["error"]('Something went Wrong.');
          });
      },

      resetFields: function () {
        this.selectedQualityCategory = {};
        this.editQualityName = '';
      },

      closeEditInwardQuality: function () {
        this.inwardQualityId = -1;
        this.resetFields();
      }
    }
  };
</script>