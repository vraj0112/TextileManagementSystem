<template>
<<<<<<< Updated upstream
    <div>
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            Search and Manage Broker
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>

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
                                <input v-model="search" type="search" class="form-control "
                                    placeholder="Search By ..." />
                            </div>
                        </div>

                        <div class="p-0">
                            <table class="table table-hover table-bordered table-striped table-sm">
                                <thead class="text-md">
                                    <tr>
                                        <th width="10%">
                                            <a href="#" @click.prevent="updateSorting('broker_id')">Sr. No.</a>
                                            <span v-if="sort_field == 'broker_id' ? 1 : 0">
                                                <span v-if="sort_direction == 'asc' ? 1 : 0">&uarr;</span>
                                                <span v-if="sort_direction == 'desc' ? 1 : 0">&darr;</span>
                                            </span>
                                        </th>
                                        <th>
                                            <a href="#" @click.prevent="updateSorting('borker_name')">Broker Name</a>
                                            <span v-if="sort_field =='broker_name'? 1: 0">
                                                <span v-if="sort_direction == 'asc'? 1: 0">&uarr;</span>
                                                <span v-if="sort_direction == 'desc'? 1: 0">&darr;</span>
                                            </span>
                                        </th>
                                        <th>
                                            <a href="#" @click.prevent="updateSorting('broker_contact_no')">Contact
                                                Number</a>
                                            <span v-if="sort_field =='broker_contact_no'? 1: 0">
                                                <span v-if="sort_direction == 'asc'? 1: 0">&uarr;</span>
                                                <span v-if="sort_direction == 'desc'? 1: 0">&darr;</span>
                                            </span>
                                        </th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-md">
                                    <tr v-for="broker in brokers.data" v-bind:key="broker.broker_id">
                                        <td>{{ broker.broker_id }}</td>
                                        <td>{{ broker.broker_name }}</td>
                                        <td>{{ broker.broker_contact_no }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm text-md"
                                                @click="editBroker(broker.broker_id, broker.broker_name, broker.broker_contact_no)">
                                                <i class="fas fa-pen"></i>
                                            </button>

                                            <button class="btn btn-danger btn-sm text-md ml-3"
                                                @click="deleteBroker(broker.broker_id)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-6 offset-5">
                                <pagination :data="brokers" @pagination-change-page="getAllBrokers"></pagination>
                            </div>
                        </div>
                    </div>
=======
  <div>
    <div class="row">
      <div class="col-md-12 mt-3">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Search and Manage Broker</h3>
            <div class="card-tools">
              <button
                type="button"
                class="btn btn-tool"
                data-card-widget="collapse"
              >
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>

          <div class="card-body table-responsive">
            <div
              class="d-flex justify-content-between align-content-center mb-2"
            >
              <div class="d-flex">
                <div>
                  <div class="d-flex align-items-center ml-4">
                    <label for="paginate" class="text-nowrap mr-2 mb-0 text-md">
                      Per Page
                    </label>
                    <select
                      v-model="paginate"
                      class="form-control form-control-sm"
                    >
                      <option value="10">10</option>
                      <option value="20">20</option>
                      <option value="30">30</option>
                    </select>
                  </div>
>>>>>>> Stashed changes
                </div>
              </div>
              <div class="col-md-4">
                <input
                  v-model="search"
                  type="search"
                  class="form-control"
                  placeholder="Search By ..."
                />
              </div>
            </div>

            <div class="p-0">
              <table
                class="table table-hover table-bordered table-striped table-sm"
              >
                <thead class="text-md">
                  <tr>
                    <th width="10%">
                      <a href="#" @click.prevent="updateSorting('broker_id')"
                        >Sr. No.</a
                      >
                      <span v-if="sort_field == 'broker_id' ? 1 : 0">
                        <span v-if="sort_direction == 'asc' ? 1 : 0"
                          >&uarr;</span
                        >
                        <span v-if="sort_direction == 'desc' ? 1 : 0"
                          >&darr;</span
                        >
                      </span>
                    </th>
                    <th>
                      <a href="#" @click.prevent="updateSorting('borker_name')"
                        >Broker Name</a
                      >
                      <span v-if="sort_field == 'broker_name' ? 1 : 0">
                        <span v-if="sort_direction == 'asc' ? 1 : 0"
                          >&uarr;</span
                        >
                        <span v-if="sort_direction == 'desc' ? 1 : 0"
                          >&darr;</span
                        >
                      </span>
                    </th>
                    <th>
                      <a
                        href="#"
                        @click.prevent="updateSorting('broker_contact_no')"
                        >Contact Number</a
                      >
                      <span v-if="sort_field == 'broker_contact_no' ? 1 : 0">
                        <span v-if="sort_direction == 'asc' ? 1 : 0"
                          >&uarr;</span
                        >
                        <span v-if="sort_direction == 'desc' ? 1 : 0"
                          >&darr;</span
                        >
                      </span>
                    </th>
                    <th width="15%">Action</th>
                  </tr>
                </thead>
                <tbody class="text-md">
                  <tr
                    v-for="broker in brokers.data"
                    v-bind:key="broker.broker_id"
                  >
                    <td>{{ broker.broker_id }}</td>
                    <td>{{ broker.broker_name }}</td>
                    <td>{{ broker.broker_contact_no }}</td>
                    <td class="text-center">
                      <button
                        class="btn btn-primary btn-sm text-md"
                        @click="
                          editBroker(
                            broker.broker_id,
                            broker.broker_name,
                            broker.broker_contact_no
                          )
                        "
                      >
                        <i class="fas fa-pen"></i>
                      </button>

                      <button
                        class="btn btn-danger btn-sm text-md"
                        @click="deleteBroker(broker.broker_id)"
                      >
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="row mt-4">
              <div class="col-sm-6 offset-5">
                <pagination
                  :data="brokers"
                  @pagination-change-page="getAllBrokers"
                ></pagination>
              </div>
            </div>
          </div>
        </div>

        <div v-if="brokerId == -1 ? 0 : 1" class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit Broker</h3>
            <div class="card-tools">
              <button
                type="button"
                class="btn btn-tool"
                data-card-widget="collapse"
              >
                <i class="fas fa-minus"></i>
              </button>
              <button
                type="button"
                class="btn btn-tool"
                @click="closeEditBroker"
              >
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>

          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-2">
                <label for="brokerName" class="text-md col-form-label"
                  >Broker Name
                  <span class="required-mark" style="color: red">*</span></label
                >
              </div>

              <div class="col-md-3">
                <input
                  type="text"
                  class="form-control"
                  v-model="editBrokerName"
                  maxlength="70"
                  placeholder="Enter Broker Name..."
                />
              </div>

              <div class="col-md-2 ml-auto">
                <label for="contactNo" class="text-md col-form-label"
                  >Contact Number
                  <span class="required-mark" style="color: red">*</span></label
                >
              </div>

              <div class="col-md-3 mr-5">
                <input
                  type="text"
                  class="form-control"
                  v-model="editContactNo"
                  maxlength="10"
                  placeholder="Enter Broker Contact No..."
                />
              </div>
            </div>
          </div>

          <div class="card-footer">
            <button
              type="submit"
              @click="updateBroker"
              class="btn btn-primary text-md"
            >
              Update
            </button>
            <button
              type="reset"
              @click="resetFields"
              class="btn btn-primary ml-3 text-md"
            >
              Reset
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import toastr from "toastr";
import swal from "sweetalert2";

export default {
  name: "SMBroker",
  data() {
    return {
      brokers: [],
      paginate: "10",
      search: "",
      sort_direction: "asc",
      sort_field: "broker_id",
      brokerId: -1,
      editBrokerName: "",
      editContactNo: "",
      status: null,
      message: null,
      errors: null,
    };
  },
  mounted() {
    this.getAllBrokers();
  },
  watch: {
    paginate: function () {
      this.getAllBrokers();
    },
    search: function () {
      this.getAllBrokers();
    },
  },
  methods: {
    getAllBrokers: function (page = 1) {
      axios
        .get(
          "../api/brokers?page=" +
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
          this.brokers = response.data;
        });
    },

    updateSorting: function (field) {
      if (this.sort_field == field) {
        this.sort_direction = this.sort_direction == "asc" ? "desc" : "asc";
      } else {
        this.sort_field = field;
      }
      this.getAllBrokers(this.brokers.current_page);
    },

    editBroker: function (broker_id, broker_name, broker_contact_no) {
      this.brokerId = broker_id;
      this.editBrokerName = broker_name;
      this.editContactNo = broker_contact_no;
    },

    updateBroker: function () {
      if (this.editBrokerName == "" || this.editContactNo == "") {
        toastr["error"]("All Fileds are Required");
      } else {
        axios
          .put("../api/broker/update/" + this.brokerId, {
            editBrokerName: this.editBrokerName,
            editContactNo: this.editContactNo,
          })
          .then((res) => {
            if (res.data.status == -1) {
              var errormsg = res.data.errors;

              try {
                if (errormsg.editBrokerName)
                  toastr["error"](errormsg.editBrokerName);
              } catch (err) {}

              try {
                if (errormsg.editContactNo)
                  toastr["error"](errormsg.editContactNo);
              } catch (err) {}
            } else if (res.data.status == 0) {
              toastr["warning"](res.data.message);
            } else if (res.data.status == 1) {
              swal
                .fire({
                  title: "Success",
                  html: "<h5 style='color:#9C9794'>Broker Details Updated Successfully!</h5>",
                  icon: "success",
                })
                .then((result) => {
                  this.resetFields();
                  this.brokerId = -1;
                  this.getAllBrokers(this.brokers.current_page);
                });
            }
          })
          .catch((err) => {
            console.log(err.res.data.message);
            toastr["error"]("Something went Wrong.");
          });
      }
    },

    deleteBroker: function (broker_id) {
      axios
        .delete("../api/broker/delete/" + broker_id)
        .then((res) => {
          swal
            .fire({
              title: "Success",
              html: "<h5 style='color:#9C9794'>Broker Details Deleted Successfully</h5>",
              icon: "success",
            })
            .then((result) => {
              this.getAllBrokers(this.brokers.current_page);
            });
        })
        .catch((err) => {
          console.log(err.res.data.message);
          toastr["error"]("Something went Wrong.");
        });
    },

    resetFields: function () {
      this.editBrokerName = "";
      this.editContactNo = "";
    },

    closeEditBroker: function () {
      this.brokerId = -1;
    },
  },
};
</script>