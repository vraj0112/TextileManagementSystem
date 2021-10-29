<!--
DESCRIPTION
    This module generates a Manages Pre-Generated Invoices where user have to enter info. related to Invoioce 
    and add that information to Database
NOTES
    Version         : 1.0
    Date            : 01/10/2021
    Author          : Uddhav Savani

    Initial Release : v1.0: Initial Release
-->
<template>
    <div>
        <aside></aside>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary mt-3">
                                <div class="card-header card-title d-flex">
                                    <span class="p-2 flex-grow-1 bd-highlight">
                                        Manage Direct Invoice
                                    </span>
                                    <span class="p-2 bd-highlight">
                                        <button type="button" class="btn btn-tool text-md" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>                                   
                                    </span>
                                </div>
                                
                                <div class="card-body table-responsive">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <label for="from-date" class="text-md">From Date</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" class="form-control" id="from-date" v-model="filters.fromDate" />
                                        </div>
                                        <div class="col-md-1">
                                            <label for="to-date" class="text-md">To Date</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" class="form-control" id="to-date" v-model="filters.toDate" />
                                        </div>
                                        <div class="col-md-1">
                                            <label for="company-name" class="text-md">Company Name</label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="filters.options.customers" v-model="filters.customer" placeholder="Select Company">
                                            </model-select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-1">
                                            <label for="" class="text-md">Category</label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="filters.options.categories"
                                                v-model="filters.category"
                                                placeholder="Select Category">
                                            </model-select>
                                        </div>

                                        <div class="col-md-1">
                                            <label for="" class="text-md">Quality</label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select
                                                :options="filters.options.qualities"
                                                v-model="filters.quality"
                                                placeholder="Select Quality"
                                            >
                                            </model-select>
                                        </div>

                                        <div class="col-md-1">
                                            <label for="" class="text-md">Broker</label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select
                                                :options="filters.options.brokers"
                                                v-model="filters.broker"
                                                placeholder="Select Broker"
                                            >
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
                                                        <a href="#" @click.prevent="updateSorting('invoice_date')">Date</a>
                                                        <span v-if="filters.sort_field == 'invoice_date'?1:0">
                                                            <span v-if=" filters.sort_direction == 'asc' ? 1 : 0 " >&uarr;</span>
                                                            <span v-if=" filters.sort_direction == 'desc' ? 1 : 0 " >&darr;</span>
                                                        </span>
                                                    </th>
                                                    <th>
                                                        <a href="#"  @click.prevent="updateSorting('challan_no')">Invoice No</a>
                                                        <span v-if=" filters.sort_field == 'challan_no'?1:0">
                                                            <span v-if=" filters.sort_direction == 'asc'? 1: 0">&uarr;</span>
                                                            <span v-if="filters.sort_direction =='desc'? 1: 0">&darr;</span>
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
                                                <tr
                                                    v-for="invoice in filters.invoices.data"
                                                    v-bind:key="
                                                        invoice.invoice_mst_id
                                                    "
                                                >
                                                    <td> {{ invoice.invoice_date }}</td>
                                                    <td>{{ invoice.challan_no }}</td>
                                                    <td>{{ invoice.customer_company_name }}</td>
                                                    <td>{{ invoice.broker_name }}</td>
                                                    <td>{{ invoice.quality_name }}</td>
                                                    <td>{{ invoice.sell_category_name }}</td>
                                                    <td class="text-right">{{ invoice.netAmount }}</td>
                                                    <td class="text-center">
                                                        <a :href="'/directinvoice/pdf/'+invoice.invoice_mst_id" target="_blank" class="btn btn-danger btn-sm text-md"><i class="fas fa-file-pdf"></i></a>
                                                        <button class="btn btn-info btn-sm text-md" @click="viewInvoice(invoice.invoice_mst_id, invoice.challan_no)"><i class="fas fa-eye"></i></button>
                                                        <button class="btn btn-primary btn-sm text-md" @click="editInvoice(invoice.invoice_mst_id)"><i class="fas fa-pen"></i></button>
                                                        <button class="btn btn-danger btn-sm text-md" @click="confirmInvoiceDeletation(invoice.invoice_mst_id, invoice.challan_no)"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-sm-6 offset-5">
                                            <pagination
                                                :data="filters.invoices"
                                                @pagination-change-page="getInvoices"
                                            ></pagination>
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
                                            <input
                                                type="text"
                                                class="form-control text-right"
                                                v-model="filters.totalAmountOfPage"
                                                disabled
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="invoiceToEdit.invoiceId == -1 ? 0: 1" class="card card-primary">
                                
                                <div class="card-header card-title d-flex">
                                    <span class="p-2 flex-grow-1 bd-highlight">
                                        Edit Invoice
                                    </span>
                                    <span class="p-2 bd-highlight">
                                        <button type="button" class="btn btn-tool text-md" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool text-md" @click="cancleEditInvoice"> 
                                            <i class="fas fa-times"></i>
                                        </button>                                    
                                    </span>
                                </div>
                                
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="invoice-date" class="text-md mt-1">Invoice Date</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" id="invoice-date" class="form-control" v-model="invoiceToEdit.invoiceDate">
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2">
                                            <label for="invoice-no" class="text-md mt-1">Invoice No</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" id="invoice-no" class="form-control text-right" v-model="invoiceToEdit.invoiceNo">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Customer</label>
                                        </div>
                                        <div class="col-md-4">
                                            <model-select :options="invoiceToEdit.customersOptions" v-model="invoiceToEdit.customer"
                                            placeholder="Select Customer">
                                            </model-select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Broker</label>
                                        </div>
                                        <div class="col-md-4">
                                            <model-select :options="invoiceToEdit.brokersOptions" v-model="invoiceToEdit.broker"
                                            placeholder="Select Broker">
                                            </model-select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Category</label>
                                        </div>
                                        <div class="col-md-4">
                                            <model-select :options="invoiceToEdit.categoriesOptions" v-model="invoiceToEdit.category"
                                            placeholder="Select Category">
                                            </model-select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Quality</label>
                                        </div>
                                        <div class="col-md-4">
                                            <model-select :options="invoiceToEdit.qualitiesOptions" v-model="invoiceToEdit.quality"
                                            placeholder="Select Quality">
                                            </model-select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">  
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">No Of Units</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control text-right" v-model="invoiceToEdit.noOfUnits">
                                        </div>
                                    </div>
                                    <div class="row mt-3">  
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Qty</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control text-right" v-model="invoiceToEdit.qty">
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Unit</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" v-model="invoiceToEdit.unit">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Rate</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control text-right" v-model="invoiceToEdit.rate">
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">GST Percentage</label>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control text-right" v-model="invoiceToEdit.gstPercentage">
                                                <option value='0'>0</option>
                                                <option value='5'>5</option>
                                                <option value='12'>12</option>
                                                <option value='18'>18</option>
                                                <option value='28'>28</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Total Amount</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control text-right" disabled v-model="invoiceToEdit.totalAmount">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">GST Amount</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control text-right" disabled v-model="invoiceToEdit.gstAmount">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Net Amount</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control text-right" disabled v-model="invoiceToEdit.netAmount">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary" @click="updateInvoice">Update</button>
                                </div>
                            </div>

                            <div v-if="invoiceToView.invoiceId == -1 ? 0 : 1" class="card card-primary mt-3">
                                
                                <div class="card-header card-title d-flex">
                                    <span class="p-2 flex-grow-1 bd-highlight">
                                        View Invoice
                                    </span>
                                    <span class="p-2 bd-highlight">
                                        <button type="button" class="btn btn-tool text-md" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool text-md" @click="closeInvoiceToView"> 
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
                                            <input type="text" class="form-control" v-model="invoiceToView.invoiceDate" disabled>
                                        </div>
                                        <div class="col-md-1 text-md">
                                            Challan Date:
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" v-model="invoiceToView.invoiceDate" disabled>
                                        </div>
                                        <div class="col-md-2 text-md mt-2">
                                            ChallanNo/InvoiceNo:
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" v-model="invoiceToView.invoiceNo" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md mt-2">
                                            Customer
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" v-model="invoiceToView.customer" disabled>
                                        </div>
                                        <div class="col-md-1 text-md mt-2">
                                            Broker
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" v-model="invoiceToView.broker" disabled>
                                        </div>                                        
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            Customer Mobile
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" v-model="invoiceToView.customerMobileNo" disabled>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-1 text-md">
                                            Customer GST No
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" v-model="invoiceToView.customerGSTNo" disabled>
                                        </div>
                                        
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            Quality
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" v-model="invoiceToView.quality" disabled>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-1 text-md">
                                            Category
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" v-model="invoiceToView.category" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            No Of Units
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" v-model="invoiceToView.noOfUnits" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            Total Quantity
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right" v-model="invoiceToView.qty" disabled>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-1 text-md">
                                            Unit
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" v-model="invoiceToView.unit" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            Rate
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right" v-model="invoiceToView.rate" disabled>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-1 text-md">
                                            GST
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right" v-model="invoiceToView.gstPercentage" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-1 text-md">
                                            Total Amount
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right" v-model="invoiceToView.totalAmount" disabled>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-1 text-md">
                                            GST Amount
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right" v-model="invoiceToView.gstAmount" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-1 text-md">
                                            Net Amount
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-right" v-model="invoiceToView.netAmount" disabled>
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
    name: "ManageDirectInvoice",
    
    data(){
        return{
            filters: {
                days: 5000,
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
                    qualities: [{text: "All", value: ""}]
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
                category: "",
                quality: "",
                noOfUnits: "",
                qty: "",
                unit: "",
                rate: "",
                gstPercentage: "",
                totalAmount: "",
                gstAmount: "",
                netAmount: ""
            },

            invoiceToEdit: {
                invoiceId: -1,
                invoiceDate: "",
                oldInvoiceDate: "",
                invoiceNo: "",
                oldInvoiceNo: "",

                customersOptions: [],
                customer: "",
                customerMobileNo: "",
                customerGSTNo: "",

                brokersOptions: [],
                broker: "",

                categoriesOptions: [],
                category: "",

                qualitiesOptions: [],
                quality: "",

                noOfUnits: "",
                qty: "",
                unit: "",
                rate: "",
                gstPercentage: "",
                totalAmount: "",
                gstAmount: "",
                netAmount: ""
            },
        }
    },
    mounted() {
        // Methods For Loading Filters Option
        this.loadCustomersForFilter();
        this.loadCategoriesForFilter();
        this.loadBrokersForFilter();
        this.getInvoices();
    },
    created(){
        this.filters.fromDate = this.getDateBeforeDays();
        this.filters.toDate = this.getTodaysDate();
    },
    beforeMount(){
        
    },
    watch: {
        
        'filters.fromDate': function(){
            this.getInvoices();
        },

        'filters.toDate': function(){
            this.getInvoices();
        },

        'filters.customer': function(){
            this.getInvoices();
        },

        'filters.category': function(){
            this.loadQualitiesFromCategoryForFilter();
            this.getInvoices();
        },

        'filters.quality': function(){
            this.getInvoices();
        },

        'filters.broker': function(){
            this.getInvoices();
        },

        'filters.paginate': function(){
            this.getInvoices();
        },

        'invoiceToEdit.category': function(){
            this.loadQualitiesOfSelectedCategory();
        },

        'invoiceToEdit.qty': function(){
            this.updateAmounts();
        },

        'invoiceToEdit.rate': function(){
            this.updateAmounts();
        },

        'invoiceToEdit.gstPercentage': function(){
            this.updateAmounts();
        },


    },
    methods: {



        // load options for filters
        loadCustomersForFilter: function(){
            axios
                .get('../api/customerlist').then((response) => {
                    let allEntry = [{text: "All", value: ""}];
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

        loadCategoriesForFilter: function(){
            
            axios
            .get('../api/sellqualitycategories').then((response) => {
                let allEntry = [{text: "All", value: ""}];
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

        loadQualitiesFromCategoryForFilter: function(){
            if(typeof this.filters.category === 'undefined' || this.filters.category == '' || this.filters.category == null){
                this.filters.options.qualities = [{text:"All", value:""}];
                this.filters.quality = "";
                return;
            }

            axios
                .get('../api/sellqualityofcategory/' + this.filters.category)
                .then(response => {
                    let allEntry = [{text:"All", value: ""}];
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

        loadBrokersForFilter: function(){
            axios.get('../api/brokerslist').then((response) => {
                let allEntry = [{text: "All", value: ""}];
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
                    "/api/directinvoices?page=" +
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

                    for(let i = 0; i < invoices.length; i++){
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

        getStdDate: function(date){
            date = date.split("-");
            return (date[2]+"-"+date[1]+"-"+date[0]);
        },

        // View Invoices
        viewInvoice: function(invoiceMstId, invoiceNo){
            axios
                .get('/api/directinvoice/' + invoiceMstId)
                .then(response => {
                    if(response.data.status == 0){
                        toastr.info(response.data.message);
                    }
                    else if(response.data.status == 1){
                        this.invoiceToView.invoiceDate = this.getStdDate(response.data.data.challan_mst_for_invoice.challan_date);
                        this.invoiceToView.invoiceNo = response.data.data.challan_mst_for_invoice.challan_no;
                        this.invoiceToView.customer = response.data.data.challan_mst_for_invoice.customer_relation.customer_company_name;
                        this.invoiceToView.broker = response.data.data.challan_mst_for_invoice.broker.broker_name;
                        this.invoiceToView.customerMobileNo = response.data.data.challan_mst_for_invoice.customer_relation.customer_contact_no;
                        this.invoiceToView.customerGSTNo = response.data.data.challan_mst_for_invoice.customer_relation.customer_gst_no;
                        this.invoiceToView.quality = response.data.data.challan_mst_for_invoice.quality.quality_name;
                        this.invoiceToView.category = response.data.data.challan_mst_for_invoice.quality.category.sell_category_name;
                        this.invoiceToView.noOfUnits = response.data.data.no_of_units;
                        this.invoiceToView.qty = response.data.data.challan_mst_for_invoice.total_qty;
                        this.invoiceToView.unit = response.data.data.challan_mst_for_invoice.qty_unit;
                        this.invoiceToView.rate = response.data.data.rate;
                        this.invoiceToView.gstPercentage = response.data.data.gst_percentage;
                        let totalAmount = this.invoiceToView.qty * this.invoiceToView.rate;
                        let gstAmount = totalAmount * this.invoiceToView.gstPercentage * 0.01;
                        let netAmount = totalAmount + gstAmount;
                        this.invoiceToView.totalAmount = totalAmount.toFixed(2);
                        this.invoiceToView.gstAmount = gstAmount.toFixed(2);
                        this.invoiceToView.netAmount = netAmount.toFixed(2);
                        this.invoiceToView.invoiceId = response.data.data.invoice_mst_id;
                    }
                    else{
                        toastr.error("Something Went Wrong");
                        console.log("Other then Expected Answer Recieved In Viewing Invoice");
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        },

        // Edit Invoices
        editInvoice: function(invoiceMstId){
            axios
                .get('/api/directinvoice/' + invoiceMstId)
                .then(response => {
                    if(response.data.status == 0){
                        toastr.info(response.data.message);
                    }
                    else if(response.data.status == 1){
                        this.invoiceToEdit.invoiceDate = this.getStdDate(response.data.data.challan_mst_for_invoice.challan_date);
                        this.invoiceToEdit.oldInvoiceDate = this.invoiceToEdit.invoiceDate;
                        this.invoiceToEdit.invoiceNo = response.data.data.challan_mst_for_invoice.challan_no;
                        this.invoiceToEdit.oldInvoiceNo = this.invoiceToEdit.invoiceNo;

                        this.loadCustomers();
                        this.invoiceToEdit.customer = response.data.data.challan_mst_for_invoice.customer_relation.customer_id;

                        this.loadBrokers();
                        this.invoiceToEdit.broker = response.data.data.challan_mst_for_invoice.broker.broker_id;
                        this.invoiceToEdit.customerMobileNo = response.data.data.challan_mst_for_invoice.customer_relation.customer_contact_no;
                        this.invoiceToView.customerGSTNo = response.data.data.challan_mst_for_invoice.customer_relation.customer_gst_no;

                        this.loadCategories();
                        this.invoiceToEdit.category = response.data.data.challan_mst_for_invoice.quality.category.sell_quality_category_id;

                        this.loadQualitiesOfSelectedCategory();
                        this.invoiceToEdit.quality = response.data.data.challan_mst_for_invoice.quality.sell_quality_id;

                        this.invoiceToEdit.noOfUnits = response.data.data.no_of_units;
                        this.invoiceToEdit.qty = response.data.data.challan_mst_for_invoice.total_qty;
                        this.invoiceToEdit.unit = response.data.data.challan_mst_for_invoice.qty_unit;
                        this.invoiceToEdit.rate = response.data.data.rate;
                        this.invoiceToEdit.gstPercentage = response.data.data.gst_percentage;
                        let totalAmount = this.invoiceToEdit.qty * this.invoiceToEdit.rate;
                        let gstAmount = totalAmount * this.invoiceToEdit.gstPercentage * 0.01;
                        let netAmount = totalAmount + gstAmount;
                        this.invoiceToEdit.totalAmount = totalAmount.toFixed(2);
                        this.invoiceToEdit.gstAmount = gstAmount.toFixed(2);
                        this.invoiceToEdit.netAmount = netAmount.toFixed(2);
                        this.invoiceToEdit.invoiceId = response.data.data.invoice_mst_id;
                    }
                    else{
                        toastr.error("Something Went Wrong");
                        console.log("Other then Expected Answer Recieved In Viewing Invoice");
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        },



        // validate Method
        isInvoiceDateValid: function(){
            if(this.invoiceToEdit.invoiceDate == "" || typeof this.invoiceToEdit.invoiceDate === 'undefined' || this.invoiceToEdit.invoiceDate == null){
                toastr.info("Please Selecte Invoice Date");
                return false;
            }
            return true;
        },

        isInvoiceNoValid: function () {
            if(this.invoiceToEdit.invoiceNo == ""){
                toastr.info("Please Insert Invoice No");
                return false;
            }

            if(this.invoiceToEdit.invoiceNo <= 0){
                toastr.info("Invoice No Is In-Valid");
                return false;
            }

            return true;
        },

        isCustomerValid: function(){
            if(typeof this.invoiceToEdit.customer === 'undefined' || this.invoiceToEdit.customer == ""){
                toastr.info("Please Select Customer");
                return false;
            }
            return true;
        },

        isBrokerValid: function(){
            if(typeof this.invoiceToEdit.broker === 'undefined' || this.invoiceToEdit.broker == ""){
                toastr.info("Please Select Broker");
                return false;
            }
            return true;
        },

        isCategoryValid: function(){
            if(typeof this.invoiceToEdit.category === 'undefined' || this.invoiceToEdit.category == ""){
                toastr.info("Please Select Category");
                return false;
            }
            return true;
        },

        isQualityValid: function(){
            if(typeof this.invoiceToEdit.quality === 'undefined' || this.invoiceToEdit.quality == ""){
                toastr.info("Please Select Quality");
                return false;
            }
            return true;
        },

        isNoOfUnitsValid: function(){
            if(this.invoiceToEdit.noOfUnits == ""){
                toastr.info("Please Enter No Of Units");
                return false;
            }

            if(this.invoiceToEdit.noOfUnits < 0){
                toastr.info("No Of Units Is In-Valid");
                return false;
            }
            return true;
        },

        isUnitValid: function(){
            if(typeof this.invoiceToEdit.unit === 'undefined' || this.invoiceToEdit.unit == ""){
                toastr.info("Please Select Unit");
                return false;
            }
            return true;
        },

        isQtyValid: function(){
            if(this.invoiceToEdit.qty == "" || typeof this.invoiceToEdit.qty === 'undefined'){
                toastr.info("Please Enter Qty");
                return false;
            }
            if(this.invoiceToEdit.qty < 0){
                toastr.info("Qty Is Invalid");
                return false;
            }

            return true;
        },

        isRateValid: function(){
            if(this.invoiceToEdit.rate == "" || typeof this.invoiceToEdit.rate === 'undefined'){
                toastr.info("Please Enter Rate");
                return false;
            }
            if(this.invoiceToEdit.rate < 0){
                toastr.info("Rate Is Invalid");
                return false;
            }

            return true;
        },

        isGSTPercentageValid: function(){
            if(this.invoiceToEdit.gstPercentage === "" || typeof this.invoiceToEdit.gstPercentage === 'undefined'){
                toastr.info("Please Select GST Pecentage");
                return false;
            }
            return true;
        },


        // update Invoice
        updateInvoice: function(){

            if(this.isInvoiceDateValid() && this.isInvoiceNoValid() && this.isCustomerValid() && this.isBrokerValid() && this.isCategoryValid() && this.isNoOfUnitsValid() && this.isQualityValid() && this.isQtyValid() && this.isUnitValid() && this.isRateValid() && this.isGSTPercentageValid()){
                let invoice = {
                    invoiceId: this.invoiceToEdit.invoiceId,
                    invoiceDate: this.invoiceToEdit.invoiceDate,
                    oldInvoiceDate: this.invoiceToEdit.oldInvoiceDate,
                    invoiceNo: this.invoiceToEdit.invoiceNo,
                    oldInvoiceNo: this.invoiceToEdit.oldInvoiceNo,

                    customer: this.invoiceToEdit.customer,

                    broker: this.invoiceToEdit.broker,

                    category: this.invoiceToEdit.category,

                    quality: this.invoiceToEdit.quality,

                    noOfUnits: this.invoiceToEdit.noOfUnits,
                    qty: this.invoiceToEdit.qty,
                    unit: this.invoiceToEdit.unit,
                    rate: this.invoiceToEdit.rate,
                    gstPercentage: this.invoiceToEdit.gstPercentage,
                    totalAmount: this.invoiceToEdit.totalAmount,
                    gstAmount: this.invoiceToEdit.gstAmount,
                    netAmount: this.invoiceToEdit.netAmount
                }

                // console.log(invoice);

                axios
                    .put('/api/directinvoice', invoice)
                    .then(response => {
                        if(response.data.status == 1){
                            swal
                                .fire({
                                    title: "Success",
                                    html:  "<h5 style='color:#9C9794'>Invoice Updated Successfully</h5>",
                                    icon: "success",
                                    allowOutsideClick: false
                                })
                                .then(() => {
                                    this.cancleEditInvoice();
                                    this.getInvoices(this.filters.invoices.current_page);
                                });
                        }
                        else if(response.data.status == -1){
                            if(response.data.statusCode == 1){
                                let errorString = "";
                                let errors = response.data.errors;

                                for(let field in errors){
                                    for(let i=0; i<errors[field].length; i++){
                                        errorString += "<li>"+errors[field][i]+"</li>";
                                    }
                                };

                            toastr.error(errorString, response.data.message , {timeOut: 20000, "closeButton": true})
                            }
                        }
                        else{
                            toastr.error("Something Went Wrong");
                            console.log("Err In Update Invoice API Call");
                        }
                    })  
                    .catch(err=>{
                        console.log("Err In Generatin Invoice");
                        toastr.error("Something Went Wrong");
                    });
            }
        },



        // CLose Invoice TO View
        closeInvoiceToView: function(){
            this.resetInvoiceToView();
        },

        // Close Invoice To Edit
        cancleEditInvoice: function(){
            this.invoiceToEdit.invoiceDate = "";
            this.invoiceToEdit.oldInvoiceDate = "";
            this.invoiceToEdit.invoiceNo = "";
            this.invoiceToEdit.oldInvoiceNo = "";
            this.invoiceToEdit.customersOptions = [],
            this.invoiceToEdit.customer = "";
            this.invoiceToEdit.customerMobileNo = "";
            this.invoiceToEdit.customerGSTNo = "";
            this.invoiceToEdit.brokersOptions = [],
            this.invoiceToEdit.broker = "";
            this.invoiceToEdit.categoriesOptions = [],
            this.invoiceToEdit.category = "";
            this.invoiceToEdit.qualitiesOptions = [],
            this.invoiceToEdit.quality = "";
            this.invoiceToEdit.noOfUnits = "";
            this.invoiceToEdit.qty = "";
            this.invoiceToEdit.unit = "";
            this.invoiceToEdit.rate = "";
            this.invoiceToEdit.gstPercentage = "";
            this.invoiceToEdit.totalAmount = "";
            this.invoiceToEdit.gstAmount = "";
            this.invoiceToEdit.netAmount = "";
            this.invoiceToEdit.invoiceId = -1;
        },  

        // Reset Invoice To Viewing
        resetInvoiceToView: function(){
            this.invoiceToView.invoiceDate = ""
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

        // Reset Invoice Updating
        resetUpdateInvoiceForm: function(){

        },


        //load Options For Edit
        loadCustomers: function(){
            axios
                .get('../api/customerlist').then((response) => {
                    this.invoiceToEdit.customersOptions = response.data.map(company => {
                        return {
                            value: company.customer_id,
                            text: company.customer_company_name + ' - ' + company.customer_contact_no
                        }
                    });
                })
                .catch(err => {
                    console.log(err);
                    toastr["error"]("Something went Wrong.")
                })
        },

        loadBrokers: function(){
            axios.get('../api/brokerslist').then((response) => {
                    this.invoiceToEdit.brokersOptions = response.data.map(broker => {
                        return {
                            value: broker.broker_id,
                            text: broker.broker_name + ' - ' + broker.broker_contact_no
                        }
                    })
                }).catch(err => {
                    console.log(err);
                    toastr["error"]("Something went Wrong.")
                })
        },

        loadCategories: function(){
            axios
            .get('../api/sellqualitycategories').then((response) => {
                this.invoiceToEdit.categoriesOptions = response.data.qualityCategories.map(category => {
                    return {
                        value: category.qualityCategoryId,
                        text: category.qualityCategoryName
                    }
                });
            })
            .catch(err => {
                console.log(err);
                toastr["error"]("Something went Wrong");
            })
        },

        loadQualitiesOfSelectedCategory: function(){
            if (this.invoiceToEdit.category == '' || typeof (this.invoiceToEdit.category) === 'undefined' || this.invoiceToEdit.category == null) {
                this.invoiceToEdit.unit = '';
                this.invoiceToEdit.qualitiesOptions = [];
                this.invoiceToEdit.quality = "";
                return;
            }

            axios
                .get('../api/sellqualityofcategory/' + this.invoiceToEdit.category)
                .then(response => {
                    this.invoiceToEdit.qualitiesOptions = response.data.map(quality => {
                        return {
                            value: quality.sell_quality_id,
                            text: quality.quality_name
                        }
                    });

                    if (this.invoiceToEdit.category == '1') {
                        this.invoiceToEdit.unit = "Meters";
                    }else if (this.invoiceToEdit.category == '2' || this.invoiceToEdit.category == '3') {
                        this.invoiceToEdit.unit = "Kg.";
                    }
                })
                .catch(err => {
                    console.log(err);
                    toastr["error"]("Something went Wrong.")
                })
        },


        updateAmounts: function(){
            let totalAmount = this.invoiceToEdit.rate * this.invoiceToEdit.qty;
            let gstAmount = totalAmount * this.invoiceToEdit.gstPercentage * 0.01;
            let netAmount = totalAmount + gstAmount;
            this.invoiceToEdit.totalAmount = totalAmount.toFixed(2);
            this.invoiceToEdit.gstAmount = gstAmount.toFixed(2);
            this.invoiceToEdit.netAmount = netAmount.toFixed(2);
        },


        // Delete Invoice
        confirmInvoiceDeletation: function(invoiceMstId, invoiceNo){
            swal
                .fire({
                    title: `<h5 style='color:#9C9794'>Are you sure to delete Invoice No: ${invoiceNo} ?</h5>`,
                    html: `<h5 style='color:#9C9794'>Once Invoice Is Deleted It can not be undo.</h5>`,
                    icon: "info",
                    allowOutsideClick: false,
                    showDenyButton: true,
                    confirmButtonText: 'Yes, Delete',
                    denyButtonText: `No`,
                })
                .then((result) => {
                    if(result.isConfirmed){
                        this.deleteInvoice(invoiceMstId);
                    }
                    else if(result.isDenied){
                        toastr.info("Invoice Deletation Canceled");
                    }
                })
        },

        deleteInvoice: function(invoiceMstId){
            axios
                .delete('/api/directinvoice/'+ invoiceMstId)
                .then(res => {
                    if(res.data.status == 1){
                        swal
                            .fire({
                                title: "Success",
                                html:  `<h5 style='color:#9C9794'>Invoice No: ${res.data.invoiceNo} Deleted Successfully</h5>`,
                                icon: "success",
                                allowOutsideClick: false
                            })
                            .then(() => {
                                this.getInvoices(this.filters.invoices.current_page);
                            });
                    }
                    else if(res.data.status == -1){
                        console.log("Err In Delete Invoice API Server Side");
                        toastr.error("Something Went wrong");
                    }
                    else{
                        console.log("Unexpected Respose Recived In Delete Delete Invoice API Call");
                        toastr.error("Something Went wrong");           
                    }
                })
                .catch(error => {
                    console.log("Err While Invoice Deletation API Call");
                    toastr.error("Something Went Wrong");
                })
        }
    },
    components: {
        ModelSelect
    }
}
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
</style>