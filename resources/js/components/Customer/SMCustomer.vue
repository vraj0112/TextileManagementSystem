<template>
  <div>
    <div class="row">
      <!-- left column -->
      <div class="col-md-12 mt-3">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Search and Manage Customer</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->

          <div class="card-body table-responsive">
            <div
              class="d-flex justify-content-between align-content-center mb-2"
            >
              <div class="d-flex">
                <div>
                  <div class="d-flex align-items-center ml-4">
                    <label for="paginate" class="text-nowrap mr-2 mb-0">
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
                    <th>
                      <a href="#" @click.prevent="updateSorting('customer_id')"
                        >Sr. No.</a
                      >
                      <span v-if="sort_field == 'customer_id' ? 1 : 0">
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
                        @click.prevent="updateSorting('customer_company_name')"
                        >Company Name</a
                      >
                      <span
                        v-if="sort_field == 'customer_company_name' ? 1 : 0"
                      >
                        <span v-if="sort_direction == 'asc' ? 1 : 0"
                          >&uarr;</span
                        >
                        <span v-if="sort_direction == 'desc' ? 1 : 0"
                          >&darr;</span
                        >
                      </span>
                    </th>
                    <th>Contact No.
                    </th>
                    <th>Email
                    </th>
                    <th>GST No.
                    </th>
                    <th>GST Code
                    </th>
                    <th>Address
                    </th>
                    <th width="10%">Action</th>
                  </tr>
                </thead>
                <tbody class="text-md">
                  <tr
                    v-for="customer in customers.data"
                    v-bind:key="customer.customer_id"
                  >
                    <td>{{ customer.customer_id }}</td>
                    <td>{{ customer.customer_company_name }}</td>
                    <td>{{ customer.customer_contact_no }}</td>
                    <td>{{ customer.customer_email }}</td>
                    <td>{{ customer.customer_gst_no }}</td>
                    <td>{{ customer.customer_gst_code }}</td>
                    <td>{{ customer.customer_address }}</td>

                    <td class="text-center">
                      <button
                        class="btn btn-primary btn-sm text-md"
                        @click="
                          editCustomerBtn(
                            customer.customer_id,
                            customer.customer_company_name,
                            customer.customer_contact_no,
                            customer.customer_email,
                            customer.customer_gst_no,
                            customer.customer_gst_code,
                            customer.customer_address
                          )
                        "
                      >
                        <i class="fas fa-pen"></i>
                      </button>

                      <button
                        class="btn btn-danger btn-sm text-md"
                        @click="deleteCustomer(customer.customer_id)"
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
                  :data="customers"
                  @pagination-change-page="getAllCustomers"
                ></pagination>
              </div>
            </div>
          </div>
        </div>

        <div v-if="customerId == -1 ? 0 : 1" class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Update Customer</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" @click="closeEditCustomer">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div
              class="form-group"
              style="
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
              "
            >
              <label for="companyName" class="text-md col-md-2">Company Name <span class="required-mark" style="color: red;">*</span></label>
              <input
                type="text"
                class="form-control col-md-5"
                v-model="companyName"
                placeholder="Enter Company Name..."
              />
              <div class="col-md-1"></div>
              <label for="companyContact" class="text-md col-md-2"
                >Contact Number <span class="required-mark" style="color: red;">*</span></label
              >
              <input
                type="tel"
                class="form-control col-md-2"
                v-model="companyContact"
                placeholder="Enter Contact Number..."
              />
            </div>
            <div
              class="form-group"
              style="
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
              "
            >
              <label for="companyAddress" class="text-md col-md-2"
                >Company Address <span class="required-mark" style="color: red;">*</span></label
              >
              <textarea
                class="form-control col-md-10"
                v-model="companyAddress"
                placeholder="Enter Company Address..."
              ></textarea>
            </div>
            <div
              class="form-group"
              style="
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
              "
            >
              <label for="emailAddress" class="text-md col-md-2">Email Address</label>
              <input
                type="email"
                class="form-control col-md-2"
                v-model="emailAddress"
                placeholder="Enter Email Address..."
              />
              <div class="col-md-1"></div>
              <label for="gstNumber" class="text-md col-md-1">GST No.<span class="required-mark" style="color: red;">*</span></label>
              <input
                type="text"
                class="form-control col-md-2"
                v-model="gstNumber"
                placeholder="Enter GST Number..."
              />
              <div class="col-md-1"></div>
              <label for="gstCode" class="text-md col-md-1">GST Code<span class="required-mark" style="color: red;">*</span></label>
              <input
                type="text"
                class="form-control col-md-2"
                v-model="gstCode"
                placeholder="Enter GST Code..."
              />
            </div>
          </div>
          <div class="card-footer">
            <button class="btn btn-primary" @click="UpdateCustomer">
              Update
            </button>
            <button class="btn btn-primary" @click="ResetUpdateCustomer">
              Reset
            </button>
          </div>
        </div>
      </div>
      <!--/.col (right) -->
    </div>
  </div>
</template>

<script>
import toastr from "toastr";
import swal from "sweetalert2";

export default {
  name: "SMCustomer",
  data() {
    return {
      customers: {},
      paginate: "10",
      search: "",
      sort_direction: "desc",
      sort_field: "customer_id",
      customerId: -1,
      companyName: "",
      companyContact: "",
      companyAddress: "",
      emailAddress: "",
      gstNumber: "",
      gstCode: "",
    };
  },
  mounted() {
    this.getAllCustomers();
  },
  watch: {
    paginate: function () {
      this.getAllCustomers();
    },
    search: function () {
      this.getAllCustomers();
    },
  },
  methods: {
    getAllCustomers: function (page = 1) {
      axios
        .get(
          "/api/customers?page=" +
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
          this.customers = response.data;
        });
    },

    updateSorting: function (field) {
      if (this.sort_field == field) {
        this.sort_direction = this.sort_direction == "asc" ? "desc" : "asc";
      } else {
        this.sort_field = field;
      }
      this.getAllCustomers(this.customers.current_page);
    },

    editCustomerBtn: function (
      customer_id,
      customer_company_name,
      customer_contact_no,
      customer_email,
      customer_gst_no,
      customer_gst_code,
      customer_address
    ) {
      toastr.info("Please scroll down the page!");
      this.customerId = customer_id;
      this.companyName = customer_company_name;
      this.companyContact = customer_contact_no;
      this.companyAddress = customer_address;
      this.emailAddress = customer_email;
      this.gstNumber = customer_gst_no;
      this.gstCode = customer_gst_code;
    },

    validateCompanyName: function () {
      if (this.companyName == "") {
        toastr.info("Please Enter Company Name!");
        return false;
      } else if (this.companyName.length > 50) {
        toastr.warning("Company Name must be less than 50 characters!");
        return false;
      } else {
        return true;
      }
    },

    validateCompanyContact: function () {
      if (this.companyContact == "") {
        toastr.info("Please Enter Contact Number!");
        return false;
      } else if (this.companyContact.length != 10) {
        toastr.warning("Contact Number must be equal to 10 characters!");
        return false;
      } else {
        return true;
      }
    },

    validateCompanyAddress: function () {
      if (this.companyAddress == "") {
        toastr.info("Please Enter Company Address!");
        return false;
      } else if (this.companyAddress.length > 255) {
        toastr.warning("Company Address must be less than 255 characters!");
        return false;
      } else {
        return true;
      }
    },

    validateEmailAddress: function () {
      if (this.emailAddress.length > 255) {
        toastr.warning("Email Address must be less than 255 characters!");
        return false;
      } 
      
      if (this.emailAddress != "") {
        if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.emailAddress))) 
        {
          toastr.warning("Please enter a valid email address!");
          return false;
        }
      }
      return true;
    },

    validateGSTNumber: function () {
      if (this.gstNumber == "") {
        toastr.info("Please Enter GST Number!");
        return false;
      } else if (this.gstNumber.length != 15) {
        toastr.warning("GST Number must be equal to 15 characters!");
        return false;
      } else {
        return true;
      }
    },

    validateGSTCode: function () {
      if (this.gstCode == "") {
        toastr.info("Please Enter GST Code!");
        return false;
      } else if (this.gstCode.length != 2) {
        toastr.warning("GST Code must be equal to 2 characters!");
        return false;
      } else if (this.gstCode != this.gstNumber.substring(0, 2)) {
        toastr.warning(
          "GST Code must be equal to first 2 characters of GST Number!"
        );
      } else {
        return true;
      }
    },

    UpdateCustomer: function () {
      if (
        this.validateCompanyName() &&
        this.validateCompanyContact() &&
        this.validateCompanyAddress() &&
        this.validateEmailAddress() &&
        this.validateGSTNumber() &&
        this.validateGSTCode()
      ) {
        axios
          .put("/api/customer/update/" + this.customerId, {
            companyName: this.companyName,
            companyContact: this.companyContact,
            companyAddress: this.companyAddress,
            emailAddress: this.emailAddress,
            gstNumber: this.gstNumber,
            gstCode: this.gstCode,
          })
          .then((res) => {
            if (res.data.status == 1) {
              swal
                .fire({
                  title: "Success",
                  html: "<h5 style='color:#9C9794'>Customer Updated Successfully</h5>",
                  icon: "success",
                })
                .then((result) => {
                  this.customerId = -1;
                  this.ResetUpdateCustomer();
                  this.$emit("refreshCustomersTable");
                  this.getAllCustomers(this.customers.current_page);
                });
            } else if (res.data.status == 0) {
              toastr.info(res.data.message);
            } else {
              toastr.error(res.data.message);
            }
          })
          .catch((err) => {
            toastr.error(res.error.message);
            console.log(res.error.message);
          });
      }
    },

    ResetUpdateCustomer: function () {
      this.companyName = "";
      this.companyContact = "";
      this.companyAddress = "";
      this.emailAddress = "";
      this.gstNumber = "";
      this.gstCode = "";
    },

    closeEditCustomer: function () {
      this.customerId = -1;
    },

    deleteCustomer: function (customer_id) {
      axios
        .delete("/api/customer/delete/" + customer_id)
        .then((res) => {
          swal
            .fire({
              title: "Success",
              html: "<h5 style='color:#9C9794'>Customer Deleted Successfully</h5>",
              icon: "success",
            })
            .then((result) => {
              this.getAllCustomers(this.customers.current_page);
            });
        })
        .catch((err) => {
          toastr.error(res.error.message);
          console.log(res.error.message);
        });
    },
  },
};
</script>
