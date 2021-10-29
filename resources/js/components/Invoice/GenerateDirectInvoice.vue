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
                                        Generate Direct Invoice
                                    </span>
                                    <span class="p-2 bd-highlight">
                                        <button type="button" class="btn btn-tool text-md" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool text-md" >
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
                                            <input type="date" id="invoice-date" class="form-control" v-model="invoice.invoiceDate">
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2">
                                            <label for="invoice-no" class="text-md mt-1">Invoice No</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" id="invoice-no" class="form-control text-right" v-model="invoice.invoiceNo">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Customer</label>
                                        </div>
                                        <div class="col-md-4">
                                            <model-select :options="options.customers" v-model="invoice.customerId"
                                            placeholder="Select Customer">
                                            </model-select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Broker</label>
                                        </div>
                                        <div class="col-md-4">
                                            <model-select :options="options.brokers" v-model="invoice.brokerId"
                                            placeholder="Select Broker">
                                            </model-select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Category</label>
                                        </div>
                                        <div class="col-md-4">
                                            <model-select :options="options.categories" v-model="invoice.categoryId"
                                            placeholder="Select Category">
                                            </model-select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Quality</label>
                                        </div>
                                        <div class="col-md-4">
                                            <model-select :options="options.qualities" v-model="invoice.qualityId"
                                            placeholder="Select Quality">
                                            </model-select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">  
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">No Of Units</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control text-right" v-model="invoice.noOfUnits">
                                        </div>
                                    </div>
                                    <div class="row mt-3">  
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Qty</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control text-right" v-model="invoice.qty">
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Unit</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" v-model="invoice.unit">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Rate</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control text-right" v-model="invoice.rate">
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">GST Percentage</label>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control text-right" v-model="invoice.gstPercentage">
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
                                            <input type="number" class="form-control text-right" disabled v-model="invoice.totalAmount">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">GST Amount</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control text-right" disabled v-model="invoice.gstAmount">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="" class="text-md mt-1">Net Amount</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control text-right" disabled v-model="invoice.netAmount">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary" @click="generateInvoice">Generate Invoice</button>
                                    <button class="btn btn-primary" @click="resetInvoiceForm">Reset</button>
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
    name: "GenerateDirectInvoice",
    components: {
        ModelSelect
    },
    data() {
        return{
            invoice: {
                invoiceDate: "",
                invoiceNo: "",
                customerId: "",
                brokerId: "",
                categoryId: "",
                qualityId: "",
                noOfUnits: '',
                qty: "",
                rate: "",
                gstPercentage: "0",
                totalAmount: (0).toFixed(2),
                gstAmount: (0).toFixed(2),
                netAmount: (0).toFixed(2),
                unit:""
            },

            options: {
                customers: [],
                brokers: [],
                categories: [],
                qualities: []
            }
        }
    },
    mounted() {
        this.invoice.invoiceDate = this.getTodaysDate();
        this.loadCustomers();
        this.loadBrokers();
        this.loadCategories();
    },
    watch: {
        'invoice.categoryId' : function(){
            this.loadQualitiesOfSelectedCategory();
        },

        'invoice.qty' : function(){
            this.calculateAmounts();
        },

        'invoice.rate' : function(){
            this.calculateAmounts();
        },

        'invoice.gstPercentage' : function(){
            this.calculateAmounts();
        }
    },
    methods: {
        




        // Load Options In Select Menu

        loadCustomers: function(){
            axios
                .get('../api/customerlist').then((response) => {
                    this.options.customers = response.data.map(company => {
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
                    this.options.brokers = response.data.map(broker => {
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
                this.options.categories = response.data.qualityCategories.map(category => {
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
            if (this.invoice.categoryId == '' || typeof (this.invoice.categoryId) === 'undefined' || this.invoice.categoryId == null) {
                this.invoice.unit = '';
                this.options.qualities = [];
                this.invoice.qualityId = "";
                return;
            }

            axios
                .get('../api/sellqualityofcategory/' + this.invoice.categoryId)
                .then(response => {
                    this.options.qualities = response.data.map(quality => {
                        return {
                            value: quality.sell_quality_id,
                            text: quality.quality_name
                        }
                    });

                    if (this.invoice.categoryId == '1') {
                        this.invoice.unit = "Meters";
                    }else if (this.invoice.categoryId == '2' || this.invoice.categoryId == '3') {
                        this.invoice.unit = "Kg.";
                    }
                })
                .catch(err => {
                    console.log(err);
                    toastr["error"]("Something went Wrong.")
                })
        },


        // validate Method
        isInvoiceDateValid: function(){
            if(this.invoice.invoiceDate == "" || typeof this.invoice.invoiceDate === 'undefined' || this.invoice.invoiceDate == null){
                toastr.info("Please Selecte Invoice Date");
                return false;
            }
            return true;
        },

        isInvoiceNoValid: function () {
            if(this.invoice.invoiceNo == ""){
                toastr.info("Please Insert Invoice No");
                return false;
            }

            if(this.invoice.invoiceNo <= 0){
                toastr.info("Invoice No Is In-Valid");
                return false;
            }

            return true;
        },

        isCustomerValid: function(){
            if(typeof this.invoice.customerId === 'undefined' || this.invoice.customerId == ""){
                toastr.info("Please Select Customer");
                return false;
            }
            return true;
        },

        isBrokerValid: function(){
            if(typeof this.invoice.brokerId === 'undefined' || this.invoice.brokerId == ""){
                toastr.info("Please Select Broker");
                return false;
            }
            return true;
        },

        isCategoryValid: function(){
            if(typeof this.invoice.categoryId === 'undefined' || this.invoice.categoryId == ""){
                toastr.info("Please Select Category");
                return false;
            }
            return true;
        },

        isQualityValid: function(){
            if(typeof this.invoice.qualityId === 'undefined' || this.invoice.qualityId == ""){
                toastr.info("Please Select Quality");
                return false;
            }
            return true;
        },

        isNoOfUnitsValid: function(){
            if(this.invoice.noOfUnits == ""){
                toastr.info("Please Enter No Of Units");
                return false;
            }

            if(this.invoice.noOfUnits < 0){
                toastr.info("No Of Units Is In-Valid");
                return false;
            }
            return true;
        },

        isUnitValid: function(){
            if(typeof this.invoice.unit === 'undefined' || this.invoice.unit == ""){
                toastr.info("Please Select Unit");
                return false;
            }
            return true;
        },

        isQtyValid: function(){
            if(this.invoice.qty == "" || typeof this.invoice.qty === 'undefined'){
                toastr.info("Please Enter Qty");
                return false;
            }
            if(this.invoice.qty < 0){
                toastr.info("Qty Is Invalid");
                return false;
            }

            return true;
        },

        isRateValid: function(){
            if(this.invoice.rate == "" || typeof this.invoice.rate === 'undefined'){
                toastr.info("Please Enter Rate");
                return false;
            }
            if(this.invoice.rate < 0){
                toastr.info("Rate Is Invalid");
                return false;
            }

            return true;
        },

        isGSTPercentageValid: function(){
            if(this.invoice.gstPercentage == "" || typeof this.invoice.gstPercentage === 'undefined'){
                toastr.info("Please Select GST Pecentage");
                return false;
            }
            return true;
        },




        // Generate Invoice Btn
        generateInvoice: function() {
            console.log(this.invoice);
            if(this.isInvoiceDateValid() && this.isInvoiceNoValid() && this.isCustomerValid() && this.isBrokerValid() && this.isCategoryValid() && this.isNoOfUnitsValid() && this.isQualityValid() && this.isQtyValid() && this.isUnitValid() && this.isRateValid() && this.isGSTPercentageValid()){
                axios
                    .post('/api/directinvoice', this.invoice)
                    .then(response => {
                        if(response.data.status == 1){
                            swal
                                .fire({
                                    title: "Success",
                                    html:  "<h5 style='color:#9C9794'>Invoice Generated Successfully</h5>",
                                    icon: "success",
                                    allowOutsideClick: false
                                })
                                .then(() => {
                                    this.resetInvoiceForm();
                                });
                        }
                        else if(response.data.status == -1){
                            let errorString = "";
                            let errors = response.data.errors;

                            for(let field in errors){
                                for(let i=0; i<errors[field].length; i++){
                                    errorString += "<li>"+errors[field][i]+"</li>";
                                }
                            };

                            toastr.error(errorString, response.data.message , {timeOut: 20000, "closeButton": true})
                        }
                        else if(response.data.status == 0){
                            toastr.error(response.data.message);
                        }
                        else{
                            console.log("Other Then Expected Response Recieved In Generate Direct Invoice");
                            toastr.error("Something Went Wrong");
                        }

                        
                    })  
                    .catch(err=>{
                        console.log("Err In Generatin Invoice");
                        toastr.error("Something Went Wrong");
                    });
            }
        },

        // Reset Invoice Form Btn
        resetInvoiceForm: function() {
            this.invoice.invoiceDate = this.getTodaysDate();
            this.invoice.invoiceNo = "";
            this.invoice.customerId = "";
            this.invoice.brokerId = "";
            this.invoice.categoryId = "";
            this.invoice.qualityId ="";
            this.invoice.noOfUnits = "";          
            this.invoice.qty = "";
            this.invoice.rate = "";
            this.invoice.gstPercentage = "0";
            this.invoice.totalAmount = (0).toFixed(2);
            this.invoice.gstAmount = (0).toFixed(2);
            this.invoice.netAmount = (0).toFixed(2);
            this.invoice.unit = "";
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
                date.getTime() - this.days * 24 * 60 * 60 * 1000
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


        // Calculate Amount
        calculateAmounts: function (){
            let totalAmount = this.invoice.qty * this.invoice.rate;
            let gstAmount = totalAmount * this.invoice.gstPercentage * 0.01;
            let netAmount = totalAmount + gstAmount;
            
            this.invoice.totalAmount = totalAmount.toFixed(2);
            this.invoice.gstAmount = gstAmount.toFixed(2);
            this.invoice.netAmount = netAmount.toFixed(2);
        }
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