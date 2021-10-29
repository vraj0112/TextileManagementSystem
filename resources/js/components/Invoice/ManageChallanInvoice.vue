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
                                    <h3 class="card-title">
                                        Manage Challan Invoice
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"></button>
                                    </div>
                                </div>

                                <div class="card-body table-responsive">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <label for="from-date" class="text-md">From Date</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" class="form-control" id="from-date"
                                                v-model="filters.fromDate" />
                                        </div>
                                        <div class="col-md-1">
                                            <label for="to-date" class="text-md">To Date</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" class="form-control" id="to-date"
                                                v-model="filters.toDate" />
                                        </div>
                                        <div class="col-md-1">
                                            <label for="company-name" class="text-md">Company Name</label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="filters.options.customers"
                                                v-model="filters.customer" placeholder="Select Company">
                                            </model-select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-1">
                                            <label for="" class="text-md">Category</label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="filters.options.categories"
                                                v-model="filters.category" placeholder="Select Category">
                                            </model-select>
                                        </div>

                                        <div class="col-md-1">
                                            <label for="" class="text-md">Quality</label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="filters.options.qualities" v-model="filters.quality"
                                                placeholder="Select Quality">
                                            </model-select>
                                        </div>

                                        <div class="col-md-1">
                                            <label for="" class="text-md">Broker</label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="filters.options.brokers" v-model="filters.broker"
                                                placeholder="Select Broker">
                                            </model-select>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-1">
                                            <label for="" class="text-md">Per Page</label>
                                        </div>
                                        <div class="col-md-3">
                                            <select v-model="filters.paginate" class="form-control">
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                            </select>
                                        </div>
                                        <!-- <div class="col-md-5"></div>
                                        <div class="col-md-3 form-group">
                                            <input v-model="search" type="search" class="form-control "
                                                placeholder="Search By ..." />
                                        </div> -->
                                    </div>

                                    <div class="p-0 mt-3">
                                        <table class="table table-hover table-bordered table-striped table-sm">
                                            <thead class="text-md">
                                                <tr>
                                                    <th width="12%">
                                                        <a href="#"
                                                            @click.prevent="updateSorting('invoice_date')">Date</a>
                                                        <span v-if="filters.sort_field == 'invoice_date'?1:0">
                                                            <span
                                                                v-if=" filters.sort_direction == 'asc' ? 1 : 0 ">&uarr;</span>
                                                            <span
                                                                v-if=" filters.sort_direction == 'desc' ? 1 : 0 ">&darr;</span>
                                                        </span>
                                                    </th>
                                                    <th width="12%">
                                                        <a href="#" @click.prevent="updateSorting('due_date')">Due
                                                            Date</a>
                                                        <span v-if="filters.sort_field == 'due_date'?1:0">
                                                            <span
                                                                v-if=" filters.sort_direction == 'asc' ? 1 : 0 ">&uarr;</span>
                                                            <span
                                                                v-if=" filters.sort_direction == 'desc' ? 1 : 0 ">&darr;</span>
                                                        </span>
                                                    </th>
                                                    <th>
                                                        <a href="#" @click.prevent="updateSorting('challan_no')">Invoice
                                                            No</a>
                                                        <span v-if=" filters.sort_field == 'challan_no'?1:0">
                                                            <span
                                                                v-if=" filters.sort_direction == 'asc'? 1: 0">&uarr;</span>
                                                            <span
                                                                v-if="filters.sort_direction =='desc'? 1: 0">&darr;</span>
                                                        </span>
                                                    </th>
                                                    <th>Company</th>
                                                    <th>Broker</th>
                                                    <th>Quality</th>
                                                    <th>Category</th>
                                                    <th>Net Amount</th>
                                                    <th width="15%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-md">
                                                <tr v-for="invoice in filters.invoices.data" v-bind:key="
                                                        invoice.invoice_mst_id
                                                    ">
                                                    <td> {{ invoice.invoice_date }}</td>
                                                    <td> {{ invoice.due_date }}</td>
                                                    <td>{{ invoice.challan_no }}</td>
                                                    <td>{{ invoice.customer_company_name }}</td>
                                                    <td>{{ invoice.broker_name }}</td>
                                                    <td>{{ invoice.quality_name }}</td>
                                                    <td>{{ invoice.sell_category_name }}</td>
                                                    <td class="text-right">{{ invoice.netAmount }}</td>
                                                    <td class="text-center">
                                                        <a :href="'/invoice/pdf/'+invoice.invoice_mst_id"
                                                            target="_blank" class="btn btn-danger btn-sm text-md"><i
                                                                class="fas fa-file-pdf"></i></a>
                                                        <button class="btn btn-info btn-sm text-md"
                                                            @click="viewInvoice(invoice.invoice_mst_id    , invoice.challan_no)"><i
                                                                class="fas fa-eye"></i></button>
                                                        <button class="btn btn-primary btn-sm text-md"
                                                            @click="editInvoice(invoice.invoice_mst_id)"><i
                                                                class="fas fa-pen"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-sm-6 offset-5">
                                            <pagination :data="filters.invoices" @pagination-change-page="getInvoices">
                                            </pagination>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- <div class="col-md-5"></div> -->
                                        <div class="col-md-9 text-right">
                                            <label for="" class="mt-2 text-md">
                                                Total Amount of this page :
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right"
                                                v-model="filters.totalAmountOfPage" disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="invoiceToView.invoiceId == -1 ? 0 : 1" class="card card-primary mt-3">

                                <div class="card-header card-title d-flex">
                                    <span class="flex-grow-1 bd-highlight">
                                        View Invoice
                                    </span>
                                    <span class="p-2 bd-highlight">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" @click="closeInvoiceToView">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </span>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-1 text-md">
                                            Invoice Date:
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" v-model="invoiceToView.invoiceDate"
                                                disabled>
                                        </div>
                                        <div class="col-md-1 text-md">
                                            Challan Date:
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" v-model="invoiceToView.invoiceDate"
                                                disabled>
                                        </div>
                                        <div class="col-md-2 text-md mt-2">
                                            ChallanNo/InvoiceNo:
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" v-model="invoiceToView.invoiceNo"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md mt-2">
                                            Customer
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" v-model="invoiceToView.customer"
                                                disabled>
                                        </div>
                                        <div class="col-md-2">

                                        </div>
                                        <div class="col-md-1 text-md mt-2">
                                            Broker
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" v-model="invoiceToView.broker"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            Customer Mobile
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control"
                                                v-model="invoiceToView.customerMobileNo" disabled>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-1 text-md">
                                            Customer GST No
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control"
                                                v-model="invoiceToView.customerGSTNo" disabled>
                                        </div>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            Quality
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" v-model="invoiceToView.quality"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            No Of Units
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" v-model="invoiceToView.noOfUnits"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            Total Quantity
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right"
                                                v-model="invoiceToView.qty" disabled>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-1 text-md">
                                            Unit
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" v-model="invoiceToView.unit"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            Rate
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right"
                                                v-model="invoiceToView.rate" disabled>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-1 text-md">
                                            GST
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right"
                                                v-model="invoiceToView.gstPercentage" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            Total Amount
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right"
                                                v-model="invoiceToView.totalAmount" disabled>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-1 text-md">
                                            GST Amount
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right"
                                                v-model="invoiceToView.gstAmount" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            Bank
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right"
                                                v-model="invoiceToView.bankDetails" disabled>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-1 text-md">
                                            Net Amount
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right"
                                                v-model="invoiceToView.netAmount" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="invoiceToEdit.invoiceId == -1 ? 0 :1" class="card card-primary mt-3">
                                <div class="card-header">
                                    <h3 class="card-title">Edit Invoice</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                        <button type="button" class="btn btn-tool" @click="closeInvoiceToEdit">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body">

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="dueDate" class="text-md col-form-label">Due Date <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" class="text-md form-control"
                                                v-model="invoiceToEdit.dueDate">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="rate" class="text-md col-form-label">Rate <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="text-md text-right form-control"
                                                v-model="invoiceToEdit.rate">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="gstPercentage" class="text-md col-form-label">GST <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-select form-control"
                                                v-model="invoiceToEdit.gstPercentage">
                                                <option value="0" selected>0%</option>
                                                <option value="5">5%</option>
                                                <option value="12">12%</option>
                                                <option value="18">18%</option>
                                                <option value="28">28%</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">

                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="bank" class="text-md col-form-label">Bank <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="invoiceToEdit.bank"
                                                v-model="invoiceToEdit.selectedBank" placeholder="Select a Bank"
                                                @blur="getBranchName">
                                            </model-select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="branch" class="text-md col-form-label">Branch <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="text-md form-control"
                                                v-model="invoiceToEdit.branch" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary text-md"
                                        @click="updateInvoice">Update</button>
                                    <button type="reset" class="btn btn-primary ml-3 text-md"
                                        @click="resetEditFields">Reset</button>
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

    toastr.options = {
        closeButton: true,
        closeDuration: 200,
        progressBar: true,
        newestOnTop: true,
        positionClass: "toast-top-center",
    };

    export default {
        name: "ManageChallanInvoice",
        components: {
            ModelSelect
        },
        watch: {
            'filters.fromDate': function () {
                this.getInvoices();
            },

            'filters.toDate': function () {
                this.getInvoices();
            },

            'filters.customer': function () {
                this.getInvoices();
            },

            'filters.category': function () {
                this.loadQualitiesFromCategoryForFilter();
                this.getInvoices();
            },

            'filters.quality': function () {
                this.getInvoices();
            },

            'filters.broker': function () {
                this.getInvoices();
            },

            'filters.paginate': function () {
                this.getInvoices();
            },
        },
        data() {
            return {
                filters: {
                    days: 10,
                    fromDate: "",
                    toDate: "",
                    customer: "",
                    category: "",
                    quality: "",
                    broker: "",
                    paginate: 10,
                    sort_field: "invoice_date",
                    sort_direction: "desc",
                    totalAmountOfPage: (0).toFixed(2),

                    options: {
                        customers: [],
                        brokers: [],
                        categories: [],
                        qualities: [{ text: "All", value: "" }]
                    },

                    invoices: {}
                },


                invoiceToView: {
                    invoiceId: -1,
                    invoiceDate: "",
                    invoiceNo: "",
                    customer: "",
                    customerMobileNo: "",
                    customerGSTNo: "",
                    broker: "",
                    quality: "",
                    noOfUnits: "",
                    qty: "",
                    unit: "",
                    rate: "",
                    gstPercentage: "",
                    totalAmount: "",
                    gstAmount: "",
                    netAmount: "",
                    bankDetails: "",
                },

                invoiceToEdit: {
                    invoiceId: -1,
                    dueDate: '',
                    rate: '',
                    gstPercentage: 0,
                    bank: [],
                    selectedBank: '',

                    branch: ''
                },

            };
        },
        mounted() {

            // Methods For Loading Filters Option
            this.loadCustomersForFilter();
            this.loadCategoriesForFilter();
            this.loadBrokersForFilter();
            this.getInvoices();
            this.getBank();
        },
        created() {
            this.filters.fromDate = this.getDateBeforeDays();
            this.filters.toDate = this.getTodaysDate();
        },
        methods: {
            // load options for filters
            loadCustomersForFilter: function () {
                axios
                    .get('../api/customerlist').then((response) => {
                        let allEntry = [{ text: "All", value: "" }];
                        let individualEntry = response.data.map(company => {
                            return {
                                value: company.customer_id,
                                text: company.customer_company_name + ' - ' + company.customer_contact_no
                            }
                        });
                        this.filters.options.customers = allEntry.concat(individualEntry);
                    })
                    .catch(err => {
                        console.log(err);
                        toastr["error"]("Something went Wrong.")
                    })
            },

            loadCategoriesForFilter: function () {

                axios
                    .get('../api/sellqualitycategories').then((response) => {
                        let allEntry = [{ text: "All", value: "" }];
                        let individualEntry = response.data.qualityCategories.map(category => {
                            return {
                                value: category.qualityCategoryId,
                                text: category.qualityCategoryName
                            }
                        });

                        this.filters.options.categories = allEntry.concat(individualEntry);
                    })
                    .catch(err => {
                        console.log(err);
                        toastr["error"]("Something went Wrong");
                    })
            },

            loadQualitiesFromCategoryForFilter: function () {
                if (typeof this.filters.category === 'undefined' || this.filters.category == '' || this.filters.category == null) {
                    this.filters.options.qualities = [{ text: "All", value: "" }];
                    this.filters.quality = "";
                    return;
                }

                axios
                    .get('../api/sellqualityofcategory/' + this.filters.category)
                    .then(response => {
                        let allEntry = [{ text: "All", value: "" }];
                        let individualEntry = response.data.map(quality => {
                            return {
                                value: quality.sell_quality_id,
                                text: quality.quality_name
                            }
                        });

                        this.filters.options.qualities = allEntry.concat(individualEntry);
                    })
                    .catch(err => {
                        console.log(err);
                        toastr["error"]("Something went Wrong.")
                    })
            },

            loadBrokersForFilter: function () {
                axios.get('../api/brokerslist').then((response) => {
                    let allEntry = [{ text: "All", value: "" }];
                    let individualEntry = response.data.map(broker => {
                        return {
                            value: broker.broker_id,
                            text: broker.broker_name + ' - ' + broker.broker_contact_no
                        }
                    });

                    this.filters.options.brokers = allEntry.concat(individualEntry);

                }).catch(err => {
                    console.log(err);
                    toastr["error"]("Something went Wrong.")
                })
            },


            // Date Management Methods
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
                let last = new Date(
                    date.getTime() - this.filters.days * 24 * 60 * 60 * 1000
                );
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
                return (date[2] + "-" + date[1] + "-" + date[0]);
            },


            // Data Tables Methods
            updateSorting: function (field) {
                if (this.filters.sort_field == field) {
                    this.filters.sort_direction = this.filters.sort_direction == "asc" ? "desc" : "asc";
                } else {
                    this.filters.sort_field = field;
                }
                this.getInvoices(this.filters.invoices.current_page);
            },

            getInvoices: function (page = 1) {
                axios
                    .get(
                        "/api/invoices?page=" +
                        page +
                        "&customer=" +
                        this.filters.customer +
                        "&category=" +
                        this.filters.category +
                        "&quality=" +
                        this.filters.quality +
                        "&broker=" +
                        this.filters.broker +
                        "&sortfield=" +
                        this.filters.sort_field +
                        "&sortdirection=" +
                        this.filters.sort_direction +
                        "&fromdate=" +
                        this.filters.fromDate +
                        "&todate=" +
                        this.filters.toDate
                    )
                    .then(result => {
                        let invoices = result.data.data;
                        let totalAmountOfPage = 0;

                        for (let i = 0; i < invoices.length; i++) {
                            let totalAmount = invoices[i].total_qty * invoices[i].rate;
                            let gstAmount = totalAmount * invoices[i].gst_percentage * 0.01;
                            let netAmount = totalAmount + gstAmount;

                            totalAmountOfPage += netAmount;
                            invoices[i].netAmount = netAmount.toFixed(2);
                        }

                        this.filters.invoices = result.data;
                        this.filters.invoices.data = invoices;
                        this.filters.totalAmountOfPage = totalAmountOfPage.toFixed(2);
                    })
                    .catch(err => {
                        console.error(err)
                        console.log("Err in Fetching Challans");
                        toastr.error("Something Went Wrong, Please Refrash");
                    });
            },

            // View Invoices
            viewInvoice: function (invoiceMstId, invoiceNo) {
                axios
                    .get('/api/invoice/' + invoiceMstId)
                    .then(response => {
                        console.log(response);
                        if (response.data.status == 0) {
                            toastr.info(response.data.message);
                        }
                        else if (response.data.status == 1) {
                            console.log(response);
                            this.invoiceToView.invoiceDate = this.getStdDate(response.data.data.challan_mst_for_invoice_from_challan.challan_date);
                            this.invoiceToView.invoiceNo = response.data.data.challan_mst_for_invoice_from_challan.challan_no;
                            this.invoiceToView.customer = response.data.data.challan_mst_for_invoice_from_challan.customer.customer_company_name;
                            this.invoiceToView.broker = response.data.data.challan_mst_for_invoice_from_challan.broker.broker_name + ' - ' + response.data.data.challan_mst_for_invoice_from_challan.broker.broker_contact_no;
                            this.invoiceToView.customerMobileNo = response.data.data.challan_mst_for_invoice_from_challan.customer.customer_contact_no;
                            this.invoiceToView.customerGSTNo = response.data.data.challan_mst_for_invoice_from_challan.customer.customer_gst_no;
                            this.invoiceToView.quality = response.data.data.challan_mst_for_invoice_from_challan.quality.quality_name;
                            this.invoiceToView.noOfUnits = response.data.data.challan_mst_for_invoice_from_challan.challan_details.length;
                            this.invoiceToView.qty = response.data.data.challan_mst_for_invoice_from_challan.total_qty;
                            this.invoiceToView.unit = response.data.data.challan_mst_for_invoice_from_challan.qty_unit;
                            this.invoiceToView.rate = response.data.data.rate;
                            this.invoiceToView.gstPercentage = response.data.data.gst_percentage;
                            let totalAmount = this.invoiceToView.qty * this.invoiceToView.rate;
                            let gstAmount = totalAmount * this.invoiceToView.gstPercentage * 0.01;
                            let netAmount = totalAmount + gstAmount;
                            this.invoiceToView.totalAmount = totalAmount.toFixed(2);
                            this.invoiceToView.gstAmount = gstAmount.toFixed(2);
                            this.invoiceToView.netAmount = netAmount.toFixed(2);
                            this.invoiceToView.invoiceId = response.data.data.invoice_mst_id;
                            this.invoiceToView.bankDetails = response.data.data.bank.bank_name + ' - ' + response.data.data.bank.account_no;
                        }
                        else {
                            toastr.error("Something Went Wrong");
                            console.log("Other then Expected Answer Recieved In Viewing Invoice");
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },

            editInvoice: function (invoiceMstId) {
                axios
                    .get('/api/invoice/' + invoiceMstId)
                    .then(response => {
                        if (response.data.status == 0) {
                            toastr.info(response.data.message);
                        } else if (response.data.status == 1) {
                            this.invoiceToEdit.invoiceId = invoiceMstId;
                            this.invoiceToEdit.dueDate = this.getStdDate(response.data.data.due_date);
                            this.invoiceToEdit.rate = response.data.data.rate;
                            this.invoiceToEdit.gstPercentage = response.data.data.gst_percentage;
                            this.invoiceToEdit.selectedBank = response.data.data.bank.bank_details_id;
                            this.invoiceToEdit.branch = response.data.data.bank.branch_name;
                        }
                    })
                    .catch(err => {
                        console.log(err);
                        toastr["error"]('Something Went Wrong!');
                    })
            },


            getBranchName() {
                if (this.invoiceToEdit.selectedBank == '' || this.invoiceToEdit.selectedBank == undefined) {
                    this.invoiceToEdit.branch = '';
                    return;
                }
                axios.get('../api/bankbranch/' + this.invoiceToEdit.selectedBank).then(response => {
                    this.invoiceToEdit.branch = response.data.branch_name;
                }).catch(err => {
                    console.log(err);
                    toastr["error"]('Something went Wrong!');
                })
            },


            getBank() {
                axios.get('../api/bankinfo').then(response => {
                    this.invoiceToEdit.bank = response.data.map(bank => {
                        return {
                            value: bank.bank_details_id,
                            text: bank.bank_name + ' - ' + bank.account_no
                        }
                    })
                }).catch(err => {
                    console.log(err);
                    toastr["error"]('Something went Wrong.');
                });
            },

            // CLose Invoice TO View
            closeInvoiceToView: function () {
                this.resetInvoiceToView();
            },

            closeInvoiceToEdit() {
                this.invoiceToEdit.invoiceId = -1;
            },

            // Reset Invoice To Viewing
            resetInvoiceToView: function () {
                this.invoiceToView.invoiceDate = "";
                this.invoiceToView.invoiceNo = "";
                this.invoiceToView.customer = "";
                this.invoiceToView.broker = "";
                this.invoiceToView.customerMobileNo = "";
                this.invoiceToView.customerGSTNo = "";
                this.invoiceToView.quality = "";
                this.invoiceToView.category = "";
                this.invoiceToView.noOfUnits = "";
                this.invoiceToView.qty = "";
                this.invoiceToView.unit = "";
                this.invoiceToView.rate = "";
                this.invoiceToView.gstPercentage = "";
                this.invoiceToView.totalAmount = "";
                this.invoiceToView.gstAmount = "";
                this.invoiceToView.netAmount = "";
                this.invoiceToView.invoiceId = -1;
            },

            resetEditFields() {
                this.invoiceId = -1,
                    this.dueDate = '',
                    this.rate = '',
                    this.gstPercentage = 0,
                    this.bank = [],
                    this.selectedBank = '',
                    this.branch = ''
            },

            updateInvoice: function () {
                let payload = {
                    invoiceId: this.invoiceToEdit.invoiceId,
                    dueDate: this.invoiceToEdit.dueDate,
                    rate: this.invoiceToEdit.rate,
                    gstPercentage: this.invoiceToEdit.gstPercentage,
                    bankId: this.invoiceToEdit.selectedBank
                }

                axios
                    .put('/api/invoice', payload)
                    .then(response => {
                        if (response.data.status == 1) {
                            swal.fire({
                                title: 'Success',
                                html: "<h5 style='color:#9C9794'>Invoice Updated Successfully!</h5>",
                                icon: 'success'
                            }).then((result) => {
                                this.invoiceToEdit.invoiceId = -1;
                                this.getInvoices(this.filters.invoices.current_page);
                            });
                        } else if (response.data.status == -1) {
                            toastr.error("Something Went Worng");
                            console.log("Something Went Wrong in Update Invoice API Call in serve");
                        } else if (response.data.status == 0) {
                            let errorString = "";
                            let errors = response.data.errors;

                            for (let field in errors) {
                                for (let i = 0; i < errors[field].length; i++) {
                                    errorString += "<li>" + errors[field][i] + "</li>";
                                }
                            };

                            toastr.error(errorString, response.data.message, { timeOut: 20000, "closeButton": true })
                        }
                    })
                    .catch(err => {
                        console.log("Err In Updateing Invoice API Call");
                        toastr.error("Something Went Wrong");
                    })
            }
        },
    };
</script>

<style scoped>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    input:disabled,
    textarea:disabled {
        cursor: not-allowed;
        opacity: .6;
        background: red;
    }
</style>