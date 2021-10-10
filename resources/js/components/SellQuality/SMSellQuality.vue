<template>
  <div>
    <!-- Content Wrapper. Contains page content -->
    <div class="row">
      <!-- left column -->
      <div class="col-md-12 mt-3">
        <!-- Search and Manage Sell Quality-->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">
              Search and Manage Sell Quality
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
                      <a href="#" @click.prevent="updateSorting('sell_quality_id')">Sr. No.</a>
                      <span v-if="sort_field == 'sell_quality_id' ? 1 : 0">
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
                      <a href="#" @click.prevent="updateSorting('sell_category_name')">Category</a>
                      <span v-if="sort_field =='sell_category_name'? 1: 0">
                        <span v-if="sort_direction == 'asc'? 1: 0">&uarr;</span>
                        <span v-if="sort_direction == 'desc'? 1: 0">&darr;</span>
                      </span>
                    </th>
                    <th width="15%">Action</th>
                  </tr>
                </thead>
                <tbody class="text-md">
                  <tr v-for="sellquality in sellqualities.data" v-bind:key="sellquality.sell_quality_id">
                    <td>{{ sellquality.sell_quality_id }}</td>
                    <td>{{ sellquality.quality_name }}</td>
                    <td>{{ sellquality.sell_category_name }}</td>

                    <td class="text-center">
                      <button class="btn btn-primary btn-sm text-md"
                        @click="editSellQuality(sellquality.sell_quality_id, sellquality.sell_quality_category_id,sellquality.sell_category_name,sellquality.quality_name)">
                        <i class="fas fa-pen"></i>
                      </button>

                      <button class="btn btn-danger btn-sm text-md"
                        @click="deleteSellQuality(sellquality.sell_quality_id)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="row mt-4">
              <div class="col-sm-6 offset-5">
                <pagination :data="sellqualities" @pagination-change-page="getAllSellQualities"></pagination>
              </div>
            </div>
          </div>
        </div>

        <div v-if="sellQualityId == -1 ? 0 : 1" class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit Sell Quality</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" @click="closeEditSellQuality">
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
            <button type="submit" @click="updateSellQuality" class="btn btn-primary text-md">Update</button>
            <button type="reset" @click="resetFields" class="btn btn-primary ml-3 text-md">Reset</button>
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
    name: "SMSellQuality",
    components: {
      ModelSelect
    },
    data() {
      return {
        sellqualities: {},
        paginate: "10",
        search: "",
        sort_direction: "asc",
        sort_field: "sell_quality_id",
        sellQualityId: -1,
        editQualityName: "",
        qualityCategories: [],
        selectedQualityCategory: "",
        status: null,
        message: null,
        errors: null
      };
    },
    mounted() {
      this.getAllSellQualities();
      this.loadQualityCategories();
    },
    watch: {
      paginate: function () {
        this.getAllSellQualities();
      },
      search: function () {
        this.getAllSellQualities();
      }
    },
    methods: {
      getAllSellQualities: function (page = 1) {
        axios
          .get(
            "../api/sellqualities?page=" +
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
            this.sellqualities = response.data;
          });
      },

      updateSorting: function (field) {
        if (this.sort_field == field) {
          this.sort_direction =
            this.sort_direction == "asc" ? "desc" : "asc";
        } else {
          this.sort_field = field;
        }
        this.getAllSellQualities(this.sellqualities.current_page);
      },

      editSellQuality: function (sell_quality_id, sell_quality_category_id, sell_category_name, quality_name) {
        this.sellQualityId = sell_quality_id;
        this.selectedQualityCategory = sell_quality_category_id;
        this.editQualityName = quality_name;
      },

      loadQualityCategories: function () {
        axios.get('../api/sellqualitycategories').then((response) => {
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

      updateSellQuality: function () {
        if ((this.selectedQualityCategory == '') || (this.editQualityName == '')) {
          toastr["error"]('All Fields are Required');
        } else {
          axios
            .put('../api/sellquality/update/' + this.sellQualityId, {
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
                  html: "<h5 style='color:#9C9794'>Sell Quality Data Updated Successfully!</h5>",
                  icon: 'success'
                }).then((result) => {
                  this.resetFields();
                  this.sellQualityId = -1;
                  this.getAllSellQualities(this.sellqualities.current_page);
                });
              }
            }).catch((err) => {
              console.log(err.res.data.message);
              toastr["error"]('Something went Wrong.');
            });
        }
      },

      deleteSellQuality: function (sell_quality_id) {
        axios
          .delete('../api/sellquality/delete/' + sell_quality_id)
          .then((res) => {
            swal.fire({
              title: "Success",
              html:
                "<h5 style='color:#9C9794'>Sell Quality Deleted Successfully</h5>",
              icon: "success"
            }).then((result) => {
              this.getAllSellQualities(this.sellqualities.current_page);
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

      closeEditSellQuality: function () {
        this.sellQualityId = -1;
      }
    }
  };
</script>