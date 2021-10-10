<template>
  <div>
    <aside></aside>
    <div class="content-wrapper">
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12 mt-3">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Search and Manage Inward</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>

                <div class="card-body table-responsive">
                  <div class="row">
                    <div class="col-md-1">
                      <label for="from-date" class="text-md">From Date</label>
                    </div>
                    <div class="col-md-3">
                      <input type="date" class="form-control" id="from-date" v-model="fromDate" />
                    </div>
                    <div class="col-md-1">
                      <label for="to-date" class="text-md">To Date</label>
                    </div>
                    <div class="col-md-3">
                      <input type="date" class="form-control" id="to-date" v-model="toDate" />
                    </div>
                    <div class="col-md-1">
                      <label for="company-name" class="text-md">Company Name</label>
                    </div>
                    <div class="col-md-3">
                      <model-select :options="companiesForFilter" v-model="selectedCompanyForFilter"
                        placeholder="Select Company">
                      </model-select>
                    </div>
                  </div>

                  <div class="row mt-2">
                    <div class="col-md-1">
                      <label for="" class="text-md" >Category</label>
                    </div>
                    <div class="col-md-3">
                      <model-select :options="categoriesForFilter" v-model="selectedCategoryForFilter"
                        @blur="loadQualitiesOfCategoryForFilter" placeholder="Select Category">
                      </model-select>
                    </div>
                    <div class="col-md-1">
                      <label for="" class="text-md">Quality</label>
                    </div>
                    <div class="col-md-3">
                      <model-select :options="qualitiesForFilter" v-model="selectedQualityForFilter"
                        placeholder="Select Quality">
                      </model-select>
                    </div>

                    <div class="col-md-1">
                      <label for="broker" class="text-md">Broker</label>
                    </div>
                    <div class="col-md-3">
                      <model-select :options="brokersForFilter" v-model="selectedBrokerForFilter"
                        placeholder="Select Broker">
                      </model-select>
                    </div>
                  </div>

                  <div class="row mt-4">
                    <div class="col-md-1">
                      <label for="" class="text-md">Per Page</label>
                    </div>
                    <div class="col-md-3">
                      <select v-model="paginate" class="form-control">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                      </select>
                    </div>
                  </div>

                  <div class="p-0 mt-3">
                    <table class="
                        table table-hover table-bordered table-striped table-sm
                      ">
                      <thead class="text-md">
                        <tr>
                          <th>
                            <a href="#" @click.prevent="updateSorting('inward_mst_id')">Sr. No.</a>
                            <span v-if="sort_field == 'inward_mst_id' ? 1 : 0">
                              <span v-if="sort_direction == 'asc' ? 1 : 0">&uarr;</span>
                              <span v-if="sort_direction == 'desc' ? 1 : 0">&darr;</span>
                            </span>
                          </th>
                          <th width="12%">
                            <a href="#" @click.prevent="updateSorting('inward_mst_date')">Date</a>
                            <span v-if="sort_field == 'inward_mst_date' ? 1 : 0">
                              <span v-if="sort_direction == 'asc' ? 1 : 0">&uarr;</span>
                              <span v-if="sort_direction == 'desc' ? 1 : 0">&darr;</span>
                            </span>
                          </th>
                          <th>Invoice No.</th>
                          <th>Company</th>
                          <th>Broker</th>
                          <th>Quality</th>
                          <th>Category</th>
                          <th>Net Amount</th>
                          <th width="15%">Action</th>
                        </tr>
                      </thead>
                      <tbody class="text-md">
                        <tr v-for="inward in inwards.data" v-bind:key="inward.inward_mst_id">
                          <td>{{ inward.inward_mst_id }}</td>
                          <td>{{ inward.inward_mst_date }}</td>
                          <td>{{ inward.inward_mst_invoice_no }}</td>
                          <td>{{ inward.vendor_company_name }}</td>
                          <td>{{ inward.broker_name }}</td>
                          <td>{{ inward.quality_name }}</td>
                          <td>{{ inward.inward_category_name }}</td>
                          <td>
                            {{ inward.nettotal }}
                          </td>
                          <td class="text-center">
                            <button class="btn btn-info btn-sm text-md" @click="viewInward(inward.inward_mst_id)">
                              <i class="fa fa-eye"></i>
                            </button>
                            <button class="btn btn-primary btn-sm text-md" @click="editInward(inward.inward_mst_id)">
                              <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-danger btn-sm text-md" @click="deleteInward(inward.inward_mst_id)">
                              <i class="fas fa-trash"></i>
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="row mt-4">
                    <div class="col-sm-6 offset-5">
                      <pagination :data="inwards" @pagination-change-page="getInwards"></pagination>
                    </div>
                  </div>

                  <div class="row">
                    <!-- <div class="col-md-5"></div> -->
                    <div class="col-md-9 text-right">
                      <label for="" class="mt-2">
                        Total Amount of this page :
                      </label>
                    </div>
                    <div class="col-md-3">
                      <input type="text" class="form-control text-right" v-model="totalAmountOfPage" disabled />
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="inwardIdToBeEdit != -1 ? 1 : 0" class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Edit Inward</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" @click="cancelEdit">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="form-group" style="display: flex; flex-direction: row">
                    <div class="col-md-2">
                      <label for="date" class="col-form-label text-md">Inward Date
                        <span class="required-mark" style="color: red">*</span></label>
                    </div>
                    <div class="col-md-3">
                      <input type="date" class="form-control" v-model="inwardDate" placeholder="Enter Inward Date..." />
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                      <label for="companyName" class="col-form-label text-md">Company Name
                        <span class="required-mark" style="color: red">*</span>
                      </label>
                    </div>
                    <div class="col-md-3">
                      <model-select :options="editCompanyNames" @blur="editGetFromSelectedCompany"
                        v-model="editSelectedCompanyName" placeholder="Select Company Name...">
                      </model-select>
                    </div>
                  </div>
                  <div class="form-group" style="display: flex; flex-direction: row">
                    <div class="col-md-2">
                      <label for="gstNo" class="col-form-label text-md">GST No.
                        <span class="required-mark" style="color: red">*</span></label>
                    </div>
                    <div class="col-md-3">
                      <input type="text" class="form-control" v-model="gstNo" placeholder="GST No..." disabled />
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                      <label for="mobileNo" class="col-form-label text-md">Mobile No.
                        <span class="required-mark" style="color: red">*</span></label>
                    </div>
                    <div class="col-md-3">
                      <input type="text" class="form-control" v-model="mobileNo" placeholder="Mobile No..." disabled />
                    </div>
                  </div>
                  <div class="form-group" style="display: flex; flex-direction: row">
                    <div class="col-md-2">
                      <label for="invoiceNo" class="col-form-label text-md">Invoice No.
                        <span class="required-mark" style="color: red">*</span></label>
                    </div>
                    <div class="col-md-3">
                      <input type="text" class="form-control" v-model="invoiceNo" placeholder="Enter Invoice No..." />
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                      <label for="broker" class="col-form-label text-md">Broker Name<span class="required-mark"
                          style="color: red">*</span>
                      </label>
                    </div>
                    <div class="col-md-3">
                      <model-select :options="editBrokers" @blur="editLoadBroker" v-model="editSelectedBroker"
                        placeholder="Select Broker Name..">
                      </model-select>
                    </div>
                  </div>
                  <div class="form-group" style="display: flex; flex-direction: row">
                    <div class="col-md-2">
                      <label for="" class="col-form-label text-md">Prod. Category
                        <span class="required-mark" style="color: red">*</span>
                      </label>
                    </div>
                    <div class="col-md-3">
                      <model-select :options="editProductQualityCategories" @blur="editLoadFromSelectedCategory"
                        v-model="editSelectedProductQualityCategory" placeholder="Select Product Category...">
                      </model-select>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                      <label for="unit" class="col-form-label text-md">Unit
                        <span class="required-mark" style="color: red">*</span></label>
                    </div>
                    <div class="col-md-3">
                      <input type="text" class="form-control" v-model="unit" placeholder="Unit..." disabled />
                    </div>
                  </div>
                  <div class="form-group" style="display: flex; flex-direction: row">
                    <div class="col-md-2">
                      <label for="productQuality" class="col-form-label text-md">Product Quality
                        <span class="required-mark" style="color: red">*</span>
                      </label>
                    </div>
                    <div class="col-md-3">
                      <model-select :options="editProductQualities" v-model="editSelectedProductQuality"
                        placeholder="Select Product Quality...">
                      </model-select>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                      <label for="quantity" class="col-form-label text-md">Quantity
                        <span class="required-mark" style="color: red">*</span></label>
                    </div>
                    <div class="col-md-3">
                      <input type="text" id="quantity" class="form-control" v-model="quantity"
                        placeholder="Enter Quantity..." />
                    </div>
                  </div>
                  <div class="form-group" style="display: flex; flex-direction: row">
                    <div class="col-md-2">
                      <label for="rate" id="rate" class="col-form-label text-md">Rate/Qty
                        <span class="required-mark" style="color: red">*</span></label>
                    </div>
                    <div class="col-md-3">
                      <input type="text" id="quantity" class="form-control" v-model="rate"
                        placeholder="Enter Rate..." />
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                      <label for="gstPercentage" class="col-form-label text-md">GST
                        <span class="required-mark" style="color: red">*</span></label>
                    </div>
                    <div class="col-md-3">
                      <select class="form-select form-control" id="gstPercentage"
                        v-model="gstPercentage">
                        <option value="0" selected>0%</option>
                        <option value="5">5%</option>
                        <option value="12">12%</option>
                        <option value="18">18%</option>
                        <option value="28">28%</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group" style="display: flex; flex-direction: row">
                    <div class="col-md-2">
                      <label for="totalAmount" class="col-form-label text-md">Total Amount
                        <span class="required-mark" style="color: red">*</span></label>
                    </div>
                    <div class="col-md-3">
                      <input type="text" class="form-control" v-model="totalAmount" placeholder="Total Amount..."
                        disabled />
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                      <label for="gstAmount" class="col-form-label text-md">GST Amount
                        <span class="required-mark" style="color: red">*</span></label>
                    </div>
                    <div class="col-md-3">
                      <input type="text" class="form-control" v-model="gstAmount" placeholder="GST Amount..."
                        disabled />
                    </div>
                  </div>
                  <div class="form-group" style="display: flex; flex-direction: row">
                    <div class="col-md-2">
                      <label for="netAmount" class="col-form-label text-md">Net Amount
                        <span class="required-mark" style="color: red">*</span></label>
                    </div>
                    <div class="col-md-3">
                      <input type="text" class="form-control" v-model="netAmount" placeholder="Net Amount..."
                        disabled />
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" v-on:click="updateInward" class="btn btn-primary">
                    Update
                  </button>
                </div>
              </div>

              <div class="card card-primary" v-if="inwardToView.inwardIdToBeViewd != -1 ? 1:0">
                <div class="card-header">
                  <h3 class="card-title">View Inward</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" @click="closeViewInward">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">

                  <div class="row">
                    <div class="col-md-2">
                      <label class="text-md">Inward Date</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.inwardDate" disabled>
                    </div>
                    <div class="col-md-2">
                      <label class="text-md">Company</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.company" disabled>
                    </div>
                  </div>

                  <div class="row mt-3">
                    <div class="col-md-2">
                      <label class="text-md">GST No.</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.gstNo" disabled>
                    </div>
                    <div class="col-md-2">
                      <label class="text-md">Mobile No</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.mobileNo" disabled>
                    </div>
                  </div>

                  <div class="row mt-3">
                    <div class="col-md-2">
                      <label class="text-md">Invoice No.</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.invoiceNo" disabled>
                    </div>
                    <div class="col-md-2">
                      <label class="text-md">Broker</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.broker" disabled>
                    </div>
                  </div>

                  <div class="row mt-3">
                    <div class="col-md-2">
                      <label class="text-md">Category</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.category" disabled>
                    </div>
                    <div class="col-md-2">
                      <label class="text-md">Unit</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.unit" disabled>
                    </div>
                  </div>

                  <div class="row mt-3">
                    <div class="col-md-2">
                      <label class="text-md">Quality</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.quality" disabled>
                    </div>
                    <div class="col-md-2">
                      <label class="text-md">Quantity</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.qty" disabled>
                    </div>
                  </div>

                  <div class="row mt-3">
                    <div class="col-md-2">
                      <label class="text-md">Rate</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.rate" disabled>
                    </div>
                    <div class="col-md-2">
                      <label class="text-md">GST Per.</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.gstPercentage" disabled>
                    </div>
                  </div>

                  <div class="row mt-3">
                    <div class="col-md-2">
                      <label class="text-md">Total Amount</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.totalAmount" disabled>
                    </div>
                    <div class="col-md-2">
                      <label class="text-md">GST Amount</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.gstAmount" disabled>
                    </div>
                  </div>
                  
                  <div class="row mt-3">
                    <div class="col-md-6"></div>
                    <div class="col-md-2">
                      <label class="text-md">Net Amount</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="inwardToView.netAmount" disabled>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
  import toastr from "toastr";
  import swal from "sweetalert2";
  import { ModelSelect } from "vue-search-select";

  export default {
    name: "ManageInward",
    components: {
      ModelSelect,
    },
    data() {
      return {
        //For Filter
        fromDate: this.getDateBeforeDays(), 
        toDate: this.getTodaysDate(),
        
        companiesForFilter: [],
        selectedCompanyForFilter: "",

        categoriesForFilter: [],
        selectedCategoryForFilter: "",

        qualitiesForFilter: [],
        selectedQualityForFilter: "",

        brokersForFilter: [],
        selectedBrokerForFilter: "",

        //for Data Table
        paginate: "10",
        days: 10,
        search: "",
        sort_direction: "desc",
        sort_field: "inward_mst_id",

        inwards: {},
        totalAmountOfPage: (0).toFixed(2),

        // For Edit
        inwardIdToBeEdit: -1,
        
        inwardDate: "",
        editCompanyNames: [],
        editSelectedCompanyName: "",
        
        mobileNo: "",
        gstNo: "",

        invoiceNo: "",
        editBrokers: [],
        editSelectedBroker: "",

        editProductQualityCategories: [],
        editSelectedProductQualityCategory: "",
        unit: "",

        editProductQualities: [],
        editSelectedProductQuality: "",
        quantity: "",
        
        rate: "",
        gstPercentage: "0",

        totalAmount: "",
        gstAmount: "",
        netAmount: "",

        status: null,
        message: null,
        errors: null,

        inwardIdToBeEdit: -1,

        inwardToView : {
          inwardIdToBeViewd: -1,
          inwardDate: '',
          company: "",
          gstNo: "",
          mobileNo: "",
          invoiceNo: '',
          broker: "",
          category: "",
          unit: "",
          quality: "",
          qty: "",
          rate: "",
          gstPercentage: "",
          totalAmount:"",
          gstAmount: "",
          netAmount: ""
        }
      };
    },
    mounted() {
      this.fromDate = this.getDateBeforeDays();
      this.toDate = this.getTodaysDate();




      this.getInwards();
      this.editLoadCompanyName();
      this.loadProductQualityCategory();
      this.loadQualityCategoriesForFilter();
      this.loadQualitiesOfCategoryForFilter();
      this.getVendorsList();
      this.getBrokersList();
    },
    watch: {
      quantity: function () {
        this.calcTotalAmount();
      },

      rate: function () {
        this.calcTotalAmount();
      },

      totalAmount: function () {
        this.calcGstAmount();
        this.calcNetAmount();
      },

      gstPercentage: function () {
        this.calcGstAmount();
      },

      gstAmount: function () {
        this.calcNetAmount();
      },

      selectedCategoryForFilter: function () {
        this.loadQualitiesOfCategoryForFilter();
        this.getInwards();
      },

      fromDate: function () {
        this.getInwards();
      },

      toDate: function () {
        this.getInwards();
      },

      selectedCompanyForFilter: function () {
        this.getInwards();
      },

      selectedQualityForFilter: function () {
        this.getInwards();
      },

      selectedBrokerForFilter: function () {
        this.getInwards();
      },

      editSelectedProductQualityCategory: function () {
        this.loadFromSelectedCategory();
      },

    },
    methods: {

      // Methods For Filter's Option Loading
      getVendorsList: function () {
        axios
          .get("/api/vendorcompanies")
          .then((result) => {
            let allEntry = [{ value: "", text: "All" }];
            let individualEntry = result.data.map((company) => {
              return {
                text:
                  company.vendor_company_name + "-" + company.vendor_contact_no,
                value: company.vendor_id,
              };
            });

            this.companiesForFilter = allEntry.concat(individualEntry);
          })
          .catch((err) => {
            toastr.error("Something Went Wrong!, Please Refresh The Page");
          });
      },

      loadQualityCategoriesForFilter: function () {
        axios
          .get("/api/productqualitycategories")
          .then((response) => {
            let allEntry = [{ text: "All", value: "" }];
            let individualEntry = response.data.map((category) => {
              return {
                value: category.inward_quality_category_id,
                text: category.inward_category_name,
              };
            });
            this.categoriesForFilter = allEntry.concat(individualEntry);
          })
          .catch((err) => {
            console.log(err);
            toastr["error"]("Something went Wrong! Please refresh The Page");
          });
      },

      loadQualitiesOfCategoryForFilter: function () {
        if (this.selectedCategoryForFilter == "") {
          let allEntry = [{ text: "All", value: "" }];
          this.qualitiesForFilter = allEntry;
          this.selectedQualityForFilter = "";
          return;
        }

        axios
          .get("/api/inwardqualityofcategories/" + this.selectedCategoryForFilter)
          .then((response) => {
            let allEntry = [{ text: "All", value: "" }];
            let individualEntry = response.data.map((quality) => {
              return {
                text: quality.quality_name,
                value: quality.inward_quality_id,
              };
            });

            this.qualitiesForFilter = allEntry.concat(individualEntry);
            this.selectedQualityForFilter = "";
          })
          .catch((err) => {
            toastr["error"]("Something went Wrong! Please refresh The Page");
          });
      },

      getBrokersList: function () {
        axios
          .get("/api/getbrokers")
          .then((response) => {
            let allEntry = [{ value: "", text: "All" }];
            let individualEntry = response.data.map((broker) => {
              return {
                text: broker.broker_name + "-" + broker.broker_contact_no,
                value: broker.broker_id,
              };
            });

            this.brokersForFilter = allEntry.concat(individualEntry);
          })
          .catch((err) => { });
      },


      // Methods For Datatable
      getInwards: function (page = 1) {
        axios
          .get(
            "/api/inwards?page=" +
            page +
            "&paginate=" +
            this.paginate +
            "&company=" +
            this.selectedCompanyForFilter +
            "&category=" +
            this.selectedCategoryForFilter +
            "&quality=" +
            this.selectedQualityForFilter +
            "&broker=" +
            this.selectedBrokerForFilter +
            "&search=" +
            this.search +
            "&sortfield=" +
            this.sort_field +
            "&sortdirection=" +
            this.sort_direction +
            "&fromdate=" +
            this.fromDate +
            "&todate=" +
            this.toDate
          )
          .then((result) => {
            this.inwards = result.data;
            let totalAmountOfPage = 0;
            for (let i = 0; i < this.inwards.data.length; i++) {
              let total = this.inwards.data[i].qty * this.inwards.data[i].rate;
              this.inwards.data[i].nettotal =
                total +
                total * this.inwards.data[i].inward_mst_gst_percentage * 0.01;
              totalAmountOfPage += parseFloat(this.inwards.data[i].nettotal);
            }
            this.totalAmountOfPage = totalAmountOfPage.toFixed(2);
          })
          .catch((err) => {
            console.log(err);
            toastr.error("Something Went Wrong, Please Refrash");
          });
      },


      // Methods For Options Loading In Editing
      editLoadBroker() {
        axios
          .get("../api/getbrokers")
          .then((response) => {
            this.editBrokers = response.data.map((broker) => {
              return {
                value: broker.broker_id,
                text: broker.broker_name + "-" + broker.broker_contact_no,
              };
            });
          })
          .catch((err) => {
            console.log(err);
            toastr["error"]("Something went Wrong");
          });
      },

      editLoadCompanyName() {
        axios
          .get("../api/vendorcompanies")
          .then((response) => {
            this.editCompanyNames = response.data.map((name) => {
              return {
                value: name.vendor_id,
                text: name.vendor_company_name + "-" + name.vendor_contact_no,
              };
            });
          })
          .catch((err) => {
            console.log(err);
            toastr["error"]("Something went Wrong");
          });
      },

      editGetFromSelectedCompany: function () {
        if (
          this.editSelectedCompanyName == "" ||
          typeof this.editSelectedCompanyName === "undefined"
        ) {
          this.mobileNo = "";
          this.gstNo = "";
          return;
        }

        axios
          .get("../api/selectedvendordata/" + this.editSelectedCompanyName)
          .then((response) => {
            this.mobileNo = response.data.vendor_contact_no;
            this.gstNo = response.data.vendor_gst_no;
          })
          .catch((err) => {
            console.log(err);
            toastr["info"]("Please Select Company Name.");
          });
      },

      loadProductQualityCategory() {
        axios
          .get("../api/productqualitycategories")
          .then((response) => {
            this.editProductQualityCategories = response.data.map((category) => {
              return {
                value: category.inward_quality_category_id,
                text: category.inward_category_name,
              };
            });
          })
          .catch((err) => {
            console.log(err);
            toastr["error"]("Something Went Wrong");
          });
      },

      editLoadFromSelectedCategory: function () {
        if (
          this.editSelectedProductQualityCategory == "" ||
          typeof this.editSelectedProductQualityCategory === "undefined"
        ) {
          this.unit = "";
          this.editProductQualities = [];
          return;
        }

        axios
          .get(
            "../api/inwardqualityofcategories/" +
            this.editSelectedProductQualityCategory
          )
          .then((response) => {
            this.editProductQualities = response.data.map((quality) => {
              return {
                value: quality.inward_quality_id,
                text: quality.quality_name,
              };
            });

            if (this.editSelectedProductQualityCategory == "1") {
              this.unit = "Meters";
            } else if (this.editSelectedProductQualityCategory == "2") {
              this.unit = "Kg.";
            }
          })
          .catch((err) => {
            console.log(err);
            toastr["info"]("Please Select Product Category.");
          });
      },

      loadFromSelectedCategory: function () {
        if (
          this.editSelectedProductQualityCategory == "" ||
          typeof this.editSelectedProductQualityCategory === "undefined"
        ) {
          this.unit = "";
          this.productQualities = [];
          return;
        }

        axios
          .get("../api/inwardqualityofcategories/" + this.editSelectedProductQualityCategory)
          .then((response) => {
            this.productQualities = response.data.map((quality) => {
              return {
                value: quality.inward_quality_id,
                text: quality.quality_name,
              };
            });

            if (this.editSelectedProductQualityCategory == "1") {
              this.unit = "Meters";
            } else if (
              this.editSelectedProductQualityCategory == "2" ||
              this.editSelectedProductQualityCategory == "3"
            ) {
              this.unit = "Kg.";
            }
          })
          .catch((err) => {
            console.log(err);
            toastr["error"]("Something went Wrong.");
          });
      },

      



      // Methods For Editing Inward
      editInward: async function (inwardMstId) {
        axios
          .get("/api/inward/view/" + inwardMstId)
          .then((response) => {
            let inward = response.data;

            this.invoiceNo = inward.inward_mst_invoice_no;
            this.inwardDate = this.getStdDate(inward.inward_mst_date);

            this.editLoadBroker();
            this.editSelectedBroker = inward.inward_mst_broker_id;

            this.editLoadCompanyName();
            this.editSelectedCompanyName = inward.inward_mst_vendor_id;
            this.editGetFromSelectedCompany();

            this.loadProductQualityCategory();
            this.editSelectedProductQualityCategory =
              inward.inward_details.quality.inward_quality_category_id;

            this.editLoadFromSelectedCategory();
            this.editSelectedProductQuality =
              inward.inward_details.inward_quality_id;

            this.quantity = inward.inward_details.qty;
            this.rate = inward.inward_details.rate;
            this.gstPercentage = inward.inward_mst_gst_percentage;
            this.totalAmount = parseFloat(this.quantity) * parseFloat(this.rate);
            this.gstAmount = this.totalAmount * 0.01 * this.gstPercentage;
            this.netAmount = this.totalAmount + this.gstAmount;
            this.inwardIdToBeEdit = inwardMstId;
          })
          .catch((err) => {
            console.log(err);
            toastr.error("Something Went Wrong");
          });
      },

    
      // Calculate Anount Watcher's Call back
      calcTotalAmount: function () {
        if (this.quantity == "" || this.rate == "") {
          this.totalAmount = "";
        } else {
          this.totalAmount = this.quantity * this.rate;
        }
      },

      calcGstAmount: function () {
        if (this.gstPercentage == "0" || this.totalAmount == "") {
          this.gstAmount = "";
        } else {
          this.gstAmount = this.totalAmount * this.gstPercentage * 0.01;
        }
      },

      calcNetAmount: function () {
        if (this.totalAmount == "") {
          this.netAmount = "";
        } else {
          this.netAmount = this.totalAmount + this.gstAmount;
        }
      },




      // Comman
      updateSorting: function (field) {
        if (this.sort_field == field) {
          this.sort_direction = this.sort_direction == "asc" ? "desc" : "asc";
        } else {
          this.sort_field = field;
        }
        this.getInwards(this.inwards.current_page);
      },

      
      // methods For Validation On Update
      editDateValidation: function () {
        if (this.inwardDate == "") {
          toastr.info("Inward Date Is Required");
          return false;
        } else {
          return true;
        }
      },

      editCompanyNameValidation: function () {
        if (
          this.editSelectedCompanyName == "" ||
          typeof this.editSelectedCompanyName === "undefined"
        ) {
          toastr.info("Company Name Is Required");
          return false;
        } else {
          return true;
        }
      },

      editInvoiceNoValidation: function () {
        if (this.invoiceNo == "") {
          toastr.info("Invoice No Is Required");
          return false;
        } else if (this.invoiceNo.length > 20) {
          toastr.warning("Invoice No must be less or equal than 20 characters!");
          return false;
        } else {
          return true;
        }
      },

      editBrokerValidation: function () {
        if (
          this.editSelectedBroker == "" ||
          typeof this.editSelectedBroker === "undefined"
        ) {
          toastr.info("Please Enter Broker");
          return false;
        } else {
          return true;
        }
      },

      editProductCategoryValidation: function () {
        if (
          this.editSelectedProductQualityCategory == "" ||
          typeof this.editSelectedProductQualityCategory === "undefined"
        ) {
          this.editSelectedProductQuality = "";
          toastr.info("Product Category Is Required");
          return false;
        } else {
          return true;
        }
      },

      editProductQualityValidation: function () {
        if (
          this.editSelectedProductQuality == "" ||
          typeof this.editSelectedProductQuality === "undefined"
        ) {
          toastr.info("Please Enter Product Quality");
          return false;
        } else {
          return true;
        }
      },

      editQuantityValidation: function () {
        if (this.quantity == "") {
          toastr.info("Please Enter Quantity");
          return false;
        } else if (this.quantity.length > 11) {
          toastr.warning("Quantity must be less or equal than 11 digits");
          return false;
        } else if (this.quantity < 0) {
          toastr.info("Quantity Can't Be minus");
          return false;
        } else {
          return true;
        }
      },

      editRatePerQtyValidation: function () {
        if (this.rate == "") {
          toastr.info("Please Enter Rate Per Quantity");
          return false;
        } else if (this.rate.length > 18) {
          toastr.warning("Rate must be less or equal than 18 digits");
          return false;
        } else if (this.rate < 0) {
          toastr.info("Rate Can't Be minus");
          return false;
        } else {
          return true;
        }
      },

      editUnitValidation: function () {
        if (this.unit == "" || typeof this.unit === "undefined") {
          toastr.error("Unit Is Required");
          return false;
        }
        return true;
      },



      // Update Inward Method
      updateInward: function () {
        if (
          this.editDateValidation() &&
          this.editCompanyNameValidation() &&
          this.editInvoiceNoValidation() &&
          this.editBrokerValidation() &&
          this.editProductCategoryValidation() &&
          this.editProductQualityValidation() &&
          this.editQuantityValidation() &&
          this.editRatePerQtyValidation() &&
          this.editUnitValidation()
        ) {
          let payload = {
            inwardMstId: this.inwardIdToBeEdit,
            inwardDate: this.inwardDate,
            invoiceNo: this.invoiceNo,
            company: this.editSelectedCompanyName,
            broker: this.editSelectedBroker,
            category: this.editSelectedProductQualityCategory,
            quality: this.editSelectedProductQuality,
            unit: this.unit,
            rate: this.rate,
            qty: this.quantity,
            gstPercentage: this.gstPercentage
          };

          //return;
          axios
          .put("../api/inward/update/" + this.inwardIdToBeEdit, payload)
          .then((res) => {
            console.log(res);
            if (res.data.status == -1) {
              var errormsg = res.data.errors;
              try {
                if (errormsg.date) toastr["error"](errormsg.date);
              } catch (err) {}
              try {
                if (errormsg.editSelectedCompanyName) toastr["error"](errormsg.company);
              } catch (err) {}
              try {
                if (errormsg.gstNo) toastr["error"](errormsg.gstNo);
              } catch (err) {}
              try {
                if (errormsg.mobileNo) toastr["error"](errormsg.mobileNo);
              } catch (err) {}
              try {
                if (errormsg.invoiceNo) toastr["error"](errormsg.invoiceNo);
              } catch (err) {}

              try {
                if (errormsg.broker) toastr["error"](errormsg.broker);
              } catch (err) {}
              
               try {
                if (errormsg.category)
                  toastr["error"](errormsg.category);
              } catch (err) {}

              try {
                if (errormsg.quality)
                  toastr["error"](errormsg.quality);
              } catch (err) {}

              try {
                if (errormsg.unit) toastr["error"](errormsg.unit);
              } catch (err) {}

              try {
                if (errormsg.qty) toastr["error"](errormsg.qty);
              } catch (err) {}

              try {
                if (errormsg.rate) toastr["error"](errormsg.rate);
              } catch (err) {}

              try {
                if (errormsg.gstPercentage)
                  toastr["error"](errormsg.gstPercentage);
              } catch (err) {}
            } 
            else if (res.data.status == 0) {
              toastr["warning"](res.data.message);
            } 
            else if (res.data.status == 1) 
            {
              swal
                .fire({
                  title: "Success",
                  html: "<h5 style='color:#9C9794'>Inward Updated Successfully</h5>",
                  icon: "success",
                })
                .then((result) => {
                  this.getInwards();
                  this.resetEditingInward();
                });
            }
          })
          .catch((err) => {
            console.log(err.response.data.message);
            toastr["error"]("Something Went Wrong!");
          });
      }
      },



      // Delete Inward
      deleteInward(inwardMstId) {
        axios
          .delete("../api/inward/delete/" + inwardMstId)
          .then((res) => {

            if(res.data.status == 1){
              swal
                .fire({
                  title: "Success",
                  html: "<h5 style='color:#9C9794'>Inward Deleted Successfully</h5>",
                  icon: "success",
                  allowOutsideClick: false
                })
                .then((result) => {
                  this.getInwards(this.inwards.current_page);
                });
            }
            else if(res.data.status == -1){
              toastr.error("Inward Deletaion Failed! SOmething Went Wrong");
            }
            
          })
          .catch((err) => {
            console.log("Err In Delete Inward API Call");
            toastr["error"]("Something went Wrong");
          });
      },


      // Reset Functions

      resetEditingInward: function(){
          this.invoiceNo = "";
          this.inwardDate = "";

          this.editSelectedBroker="";
          this.editSelectedCompanyName ="";
          this.editSelectedProductQualityCategory ="";
          this.editSelectedProductQuality="";
          
          this.quantity = "";
          this.rate = "";
          this.gstPercentage = "";
          this.totalAmount = "";
          this.gstAmount = "";
          this.netAmount = "";
          this.inwardIdToBeEdit = -1;
      },

      cancelEdit: function() {
        this.resetEditingInward();
      },



      // Comman Methods For Date Handling
      getTodaysDate: function () {
        let d = new Date();
        let month = "" + (d.getMonth() + 1);
        let day = "" + d.getDate();
        let year = d.getFullYear();
        if (month < 10) {
          month = "0" + month;
        }

        if (day < 10) {
          day = "0" + day;
        }

        return year + "-" + month + "-" + day;
      },

      getDateBeforeDays: function () {
        let date = new Date();
        let last = new Date(date.getTime() - this.days * 24 * 60 * 60 * 1000);
        let day = "" + last.getDate();
        let month = "" + (last.getMonth() + 1);
        let year = "" + last.getFullYear();

        if (day < 10) {
          day = "0" + day;
        }

        if (month < 10) {
          month = "0" + month;
        }
        return year + "-" + month + "-" + day;
      },

      getStdDate: function (date) {
        date = date.split("-");
        return date[2] + "-" + date[1] + "-" + date[0];
      },


      // View Inward
      viewInward: function(inwardMstId){
        this.resetViewInward();

        axios
         .get("../api/inward/view/" + inwardMstId)
         .then((res) => {
           console.log(res);
            this.inwardToView.inwardDate = res.data.inward_mst_date;
            this.inwardToView.invoiceNo = res.data.inward_mst_invoice_no;
            this.inwardToView.gstNo = res.data.get_vendor.vendor_gst_no;
            this.inwardToView.mobileNo = res.data.get_vendor.vendor_contact_no;
            this.inwardToView.broker=res.data.get_broker.broker_name;
            this.inwardToView.company=res.data.get_vendor.vendor_company_name;
            this.inwardToView.quality =res.data.inward_details.quality.quality_name;
            this.inwardToView.category=res.data.inward_details.quality.category.inward_category_name;
            this.inwardToView.qty = res.data.inward_details.qty;
            this.inwardToView.rate = res.data.inward_details.rate;
            this.inwardToView.unit = res.data.inward_details.qty_unit;
            this.inwardToView.gstPercentage = res.data.inward_mst_gst_percentage;
            this.inwardToView.totalAmount = (this.inwardToView.qty * this.inwardToView.rate).toFixed(2);
            this.inwardToView.gstAmount = (this.inwardToView.totalAmount * 0.01 * this.inwardToView.gstPercentage).toFixed(2);
            this.inwardToView.netAmount = (parseFloat(this.inwardToView.totalAmount) + parseFloat(this.inwardToView.gstAmount)).toFixed(2);
            this.inwardToView.inwardIdToBeViewd = inwardMstId;
         })
         .catch((err) => {
           console.log(err);
           console.log("Err In View Inward API Call");
           toastr.error("Something Went Wrong");
         })

      },

      resetViewInward: function(){
          this.inwardToView.inwardDate = "";
          this.inwardToView.invoiceNo = "";
          this.inwardToView.gstNo = "";
          this.inwardToView.mobileNo = "";
          this.inwardToView.broker="";
          this.inwardToView.company="";
          this.inwardToView.quality ="";
          this.inwardToView.category="";
          this.inwardToView.qty = "";
          this.inwardToView.rate = "";
          this.inwardToView.unit = "";
          this.inwardToView.gstPercentage = "";
          this.inwardToView.totalAmount = "";
          this.inwardToView.gstAmount = "";
          this.inwardToView.netAmount = "";
          this.inwardToView.inwardIdToBeViewd = -1;
      },

      closeViewInward: function(){
        this.resetViewInward();
      },
      

      //dont know
      /*
      loadBrokerName() {
        axios
          .get("../api/brokerslist")
          .then((response) => {
            this.brokerNames = response.data.map((broker) => {
              return {
                value: broker.broker_id,
                text: broker.broker_name + " - " + broker.broker_contact_no,
              };
            });
          })
          .catch((err) => {
            console.log(err);
            toastr["error"]("Something went Wrong.");
          });
      },
      */

      /*
      loadQualityCategories() {
        axios
          .get("../api/inwardqualitycategories")
          .then((response) => {
            this.editProductQualityCategories = response.data.qualityCategories.map(
              (category) => {
                return {
                  value: category.qualityCategoryId,
                  text: category.qualityCategoryName,
                };
              }
            );
          })
          .catch((err) => {
            console.log(err);
            toastr["error"]("Something went Wrong");
          });
      },
      */
    },
  };
</script>