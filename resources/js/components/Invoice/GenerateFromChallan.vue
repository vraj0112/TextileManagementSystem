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
                                    <h3 class="card-title">Generate Invoice From Challan</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="invoiceNo" class="text-md col-form-label">Invoice No. <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="text-md text-right form-control"
                                                v-model="invoiceNo" @blur="getFromInvoiceNo">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="financialYear" class="text-md col-form-label">Financial Year
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <model-select :options="financialYear" v-model="selectedFinancialYear"
                                                @blur="getFromNumberAndFinancialYear"
                                                placeholder="Select a Financial Year">
                                            </model-select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="challanNo" class="text-md col-form-label">Challan No
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="text-md text-right form-control"
                                                v-model="challanNo" disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="challanDate" class="text-md col-form-label">Challan Date
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="text-md form-control" v-model="challanDate"
                                                disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="brokerName" class="text-md col-form-label">Broker Name
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="text-md form-control" v-model="brokerName"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="customerName" class="text-md col-form-label">Customer Name
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="text-md form-control" v-model="customerName"
                                                disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="gstNo" class="text-md col-form-label">GST Number
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="text-md text-right form-control" v-model="gstNo"
                                                disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="code" class="text-md col-form-label">Code
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="text-md text-right form-control" v-model="code"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="address" class="text-md col-form-label">Address
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <textarea class="text-md form-control" v-model="address"
                                                disabled></textarea>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="state" class="text-md col-form-label">State
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="text-md form-control" v-model="state" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="productQuality" class="text-md col-form-label">Product Quality
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="text-md form-control" v-model="productQuality"
                                                disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="totalPieces" class="text-md col-form-label">Total Pieces
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="text-md text-right form-control"
                                                v-model="totalPieces" disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="totalQuantity" class="text-md col-form-label">Total Quantity
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="text-md text-right form-control"
                                                v-model="totalQuantity" @change="calcAmount" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="invoiceDate" class="text-md col-form-label">Invoice Date <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" class="text-md form-control" v-model="invoiceDate">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="dueDate" class="text-md col-form-label">Due Date <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" class="text-md form-control" v-model="dueDate">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="rate" class="text-md col-form-label">Rate <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="text-md text-right form-control" v-model="rate"
                                                @change="calcAmount">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="amount" class="text-md col-form-label">Amount <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="text-md text-right form-control"
                                                v-model="amount" disabled @change="calcGstAmount">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="gstPercentage" class="text-md col-form-label">GST <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-select form-control" v-model="gstPercentage"
                                                @change="calcGstAmount">
                                                <option value="0" selected>0%</option>
                                                <option value="5">5%</option>
                                                <option value="12">12%</option>
                                                <option value="18">18%</option>
                                                <option value="28">28%</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="gstAmount" class="text-md col-form-label">GST Amount <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="text-md text-right form-control"
                                                v-model="gstAmount" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="grandTotal" class="text-md col-form-label">Grand Total <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="text-md text-right form-control"
                                                v-model="grandTotal" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="bank" class="text-md col-form-label">Bank <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="bank" v-model="selectedBank"
                                                placeholder="Select a Bank" @blur="getBranchName">
                                            </model-select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="branch" class="text-md col-form-label">Branch <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="text-md form-control" v-model="branch" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary text-md"
                                        @click="addInvoice">Add</button>
                                    <button type="reset" class="btn btn-primary ml-3 text-md"
                                        @click="resetFields">Reset</button>
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
    //Here we have imported toastr and sweetalert2 for the alerts and Model Select for dynamic searchable options
    import toastr from 'toastr';
    import swal from 'sweetalert2';
    import { ModelSelect } from "vue-search-select";

    //toastr options contains properties of the alerts so on firing it will display as per the below options
    toastr.options = {
        closeButton: true,
        closeDuration: 200,
        progressBar: true,
        newestOnTop: true,
        positionClass: "toast-top-center",
    };

    export default {
        name: 'GenerateFromChallan',
        components: {
            ModelSelect,
        },
        mounted() {
            this.invoiceDate = this.getTodaysDate();
            this.dueDate = this.getDueDate();
            this.getBank();
        },
        watch: {
            amount: function () {
                this.calcGrandTotal();
            },

            gstAmount: function () {
                this.calcGrandTotal();
            },

            invoiceNo: function () {
                this.resetDisplayDataFields();
                this.financialYear = [];
                this.selectedFinancialYear = '';
            }
        },
        data() {
            return {
                invoiceNo: '',
                financialYear: [],
                selectedFinancialYear: '',

                challanNo: '',
                challanDate: '',
                brokerName: '',

                customerName: '',
                gstNo: '',
                code: '',

                address: '',
                state: '',

                productQuality: '',
                totalPieces: '',
                totalQuantity: '',

                invoiceDate: '',
                dueDate: '',

                rate: '',
                amount: '',

                gstPercentage: 0,
                gstAmount: '',

                grandTotal: '',

                bank: [],
                selectedBank: '',

                branch: ''
            }
        },
        methods: {
            //this function will take todays date and format it in the form "yyyy-mm-dd"
            getTodaysDate: function () {
                let d = new Date();
                let month = '' + (d.getMonth() + 1);
                let day = '' + d.getDate();
                let year = d.getFullYear();
                if (month < 10) {
                    month = '0' + month;
                }

                if (day < 10) {
                    day = '0' + day;
                }

                return (year + "-" + month + "-" + day);
            },

            getDueDate: function () {
                let d = new Date();
                let month = '' + (d.getMonth() + 1);
                let day = '' + d.getDate();
                let year = d.getFullYear() + 1;
                if (month < 10) {
                    month = '0' + month;
                }

                if (day < 10) {
                    day = '0' + day;
                }

                return (year + "-" + month + "-" + day);
            },

            getFromInvoiceNo() {
                if (this.invoiceNo == '') {
                    this.financialYear = [];
                    this.selectedFinancialYear = '';
                    this.resetDisplayDataFields();
                    return;
                }

                axios.get('../api/invoice/getfinancialyear/' + this.invoiceNo).then(response => {
                    let i = 0;
                    this.financialYear = response.data.map(year => {
                        return {
                            value: year.replace("-", " - "),
                            text: year,
                        }
                    })
                }).catch(err => {
                    console.log(err);
                    toastr["error"]('Something went Wrong.');
                })
            },

            getFromNumberAndFinancialYear() {
                if (this.invoiceNo == '' || this.selectedFinancialYear == '' || this.selectedFinancialYear == undefined) {
                    this.resetDisplayDataFields();
                    return;
                }

                let splitYear = this.selectedFinancialYear.split(" - ");
                let fromDateYear = splitYear[0];
                let toDateYear = splitYear[1];

                let fromDate = fromDateYear + '-04-01';
                let toDate = toDateYear + '-03-31';

                axios.get('../api/invoice/challandata/' + this.invoiceNo + '/' + fromDate + '/' + toDate).then(response => {
                    if (response.data.status == -1) {
                        this.resetDisplayDataFields();
                        var errormsg = response.data.errors;
                        toastr["error"](errormsg);
                    } else {
                        this.totalPieces = response.data[0];
                        this.challanNo = response.data[1]["challan_no"];
                        this.challanDate = response.data[1]["challan_date"];
                        this.brokerName = response.data[1]["broker_name"];
                        this.customerName = response.data[1]["customer_company_name"];
                        this.gstNo = response.data[1]["customer_gst_no"];
                        this.code = response.data[1]["customer_gst_code"];
                        this.address = response.data[1]["customer_address"];
                        this.productQuality = response.data[1]["quality_name"];
                        this.totalQuantity = response.data[1]["total_qty"];

                        axios.get('../api/getstate/' + this.code).then(response => {
                            this.state = response.data.state_name;
                        }).catch(err => {
                            console.log(err);
                            toastr["error"]('Something went Wrong!');
                        })
                    }

                }).catch(err => {
                    console.log(err);
                    toastr['error']("Something Went Wrong!");
                })
            },

            resetDisplayDataFields() {
                this.challanNo = '';
                this.challanDate = '';
                this.brokerName = '';

                this.customerName = '';
                this.gstNo = '';
                this.code = '';

                this.address = '';
                this.state = '';

                this.productQuality = '';
                this.totalPieces = '';
                this.totalQuantity = '';

                this.rate = '';
                this.amount = '';

                this.gstPercentage = 0;
                this.gstAmount = '';

                this.grandTotal = '';
            },

            calcAmount() {
                if (this.rate == '' || this.totalQuantity == '') {
                    return;
                }
                this.amount = parseFloat(this.rate) * parseFloat(this.totalQuantity);
                this.amount = this.amount.toFixed(2);
            },

            calcGstAmount() {
                if (this.amount == '') {
                    return;
                }
                this.gstAmount = parseFloat(this.amount) * parseFloat(this.gstPercentage) * 0.01;
                this.gstAmount = this.gstAmount.toFixed(2);
            },

            calcGrandTotal() {
                if (this.amount == '' || this.gstAmount == '') {
                    return;
                }
                this.grandTotal = parseFloat(this.amount) + parseFloat(this.gstAmount);
                this.grandTotal = this.grandTotal.toFixed(2);
            },

            getBank() {
                axios.get('../api/bankinfo').then(response => {
                    this.bank = response.data.map(bank => {
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

            getBranchName() {
                if (this.selectedBank == '' || this.selectedBank == undefined) {
                    this.branch = '';
                    return;
                }
                axios.get('../api/bankbranch/' + this.selectedBank).then(response => {
                    this.branch = response.data.branch_name;
                }).catch(err => {
                    console.log(err);
                    toastr["error"]('Something went Wrong!');
                })
            },

            addInvoice() {
                var addData = {};
                addData["invoiceId"] = this.invoiceNo;
                addData["invoiceDate"] = this.invoiceDate;
                addData["rate"] = this.rate;
                addData["gstPercentage"] = this.gstPercentage;
                addData["bankId"] = this.selectedBank;
                addData["dueDate"] = this.dueDate;

                let splitYear = this.selectedFinancialYear.split(" - ");
                let fromDateYear = splitYear[0];
                let toDateYear = splitYear[1];

                let fromDate = fromDateYear + '-04-01';
                let toDate = toDateYear + '-03-31';

                addData["fromDate"] = fromDate;
                addData["toDate"] = toDate;

                if (this.invoiceNo == '' || this.invoiceDate == '' || this.dueDate == '' || this.rate == '' || this.selectedBank == '' || this.selectedBank == undefined || this.selectedFinancialYear == '' || this.selectedFinancialYear == undefined) {
                    toastr["error"]("All Fields are Required!");
                } else {
                    axios.get('../api/verifyinvoicedate/' + this.invoiceDate + '/' + fromDate + '/' + toDate).then(response => {
                        if (response.data.status == 1) {
                            axios.post('../api/invoice/insert', addData).then(response => {
                                if (response.data.status == -1) {
                                    var errormsg = response.data.errors;

                                    try {
                                        if (errormsg.invoiceId)
                                            toastr["error"](errormsg.invoiceId)
                                    } catch (err) { }

                                    try {
                                        if (errormsg.invoiceDate)
                                            toastr["error"](errormsg.invoiceDate)
                                    } catch (err) { }

                                    try {
                                        if (errormsg.rate)
                                            toastr["error"](errormsg.rate)
                                    } catch (err) { }

                                    try {
                                        if (errormsg.gstPercentage)
                                            toastr["error"](errormsg.gstPercentage)
                                    } catch (err) { }

                                    try {
                                        if (errormsg.bankId)
                                            toastr["error"](errormsg.bankId)
                                    } catch (err) { }

                                    try {
                                        if (errormsg.dueDate)
                                            toastr["error"](errormsg.dueDate)
                                    } catch (err) { }

                                } else if (response.data.status == 0) {
                                    toastr["warning"](response.data.message);
                                } else if (response.data.status == 1) {
                                    swal.fire({
                                        title: 'Success',
                                        html: "<h5 style='color:#9C9794'>Invoice Created Successfully!</h5>",
                                        icon: 'success'
                                    }).then((result) => {
                                        this.resetFields();
                                    });
                                }

                            }).catch(err => {
                                console.log(err);
                                toastr["error"]("Something went Wrong!");
                            })
                        }else if(response.data.status == 0){
                            toastr["error"](response.data.message);
                        }
                    })
                }
            },

            resetFields() {
                this.invoiceNo = '';
                this.financialYear = [];
                this.selectedFinancialYear = '';
                this.resetDisplayDataFields();
                this.invoiceDate = this.getTodaysDate();
                this.dueDate = this.getDueDate();
                this.selectedBank = '';
                this.branch = '';
            }
        }
    };
</script>

<!-- This style will be applicable for this page only which will hide the buttons in the number field-->
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