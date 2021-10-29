<!--
DESCRIPTION
    This module generates a Challan where user have to enter info. related to purchased stocks
    and add that information to Databse
NOTES
    Version         : 1.0
    Date            : 01/10/2021
    Author          : Vraj Shah

    Initial Release : v1.0: Initial Release
-->

<template>
    <div>
        <aside></aside>

        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <div class="card card-primary">
                                <!-- card Header of  New Challan -->
                                <div class="card-header">
                                    <h3 class="card-title">New Challan</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                    </div>
                                </div>

                                <!-- card Body of New Challan -->
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="challanDate" class="text-md col-form-label">Date <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>

                                        <!-- here I have called a resetChallanNo function which will be called when challan Date changes -->
                                        <div class="col-md-3">
                                            <input type="date" id="challanDate" v-model="challanDate"
                                                class="form-control text-md" @change="resetChallanNo">
                                        </div>

                                        <div class="col-md-2 ml-auto">
                                            <label for="challanNo" class="text-md col-form-label">Challan Number <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>

                                        <!-- here I have called an getFinancialYearOfChallanDate function which will call when we move outside from the challan no field -->
                                        <div class="col-md-3 mr-5">
                                            <input type="number" class="text-md form-control" v-model="challanNo"
                                                @blur="getFinancialYearOfChallanDate">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="companyName" class="text-md col-form-label">Company Name <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>

                                        <!-- here I have called an getFromSelectedCompany function which will be called when i move outside from the company Name field -->
                                        <div class="col-md-3">
                                            <model-select :options="companyNames" v-model="selectedCompanyName"
                                                @blur="getFromSelectedCompany" placeholder="Select a Company Name">
                                            </model-select>
                                        </div>

                                        <div class="col-md-2 ml-auto">
                                            <label for="brokerName" class="text-md col-form-label">Broker Name <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3 mr-5">
                                            <model-select :options="brokerNames" v-model="selectedBrokerName"
                                                placeholder="Select a Broker Name">
                                            </model-select>
                                        </div>
                                    </div>

                                    <!-- Here contact number and gst number will be populated autommatically when wes select a company -->
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="companyContactNo" class="text-md col-form-label">Company Contact
                                                No. <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="text-md form-control" v-model="companyContactNo"
                                                disabled>
                                        </div>

                                        <div class="col-md-2 ml-auto">
                                            <label for="companyGSTNo" class="text-md col-form-label">Company GST No.
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3 mr-5">
                                            <input type="text" class="text-md form-control" v-model="companyGSTNo"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="productCategory" class="text-md col-form-label">Product Category
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>

                                        <!-- here I have called an loadFromSelectedCategory function which will be called when we move outside the product category field -->
                                        <div class="col-md-3">
                                            <model-select :options="productCategories" v-model="selectedProductCategory"
                                                @blur="loadFromSelectedCategory"
                                                placeholder="Select a Product Category">
                                            </model-select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="productQuality" class="text-md col-form-label">Product Quality
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="productQualities" v-model="selectedProductQuality"
                                                placeholder="Select a Product Quality">
                                            </model-select>
                                        </div>

                                        <div class="col-md-2 ml-auto">
                                            <label for="Unit" class="text-md col-form-label">Unit
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3 mr-5">
                                            <input type="text" class="text-md form-control" v-model="unit" disabled>
                                        </div>
                                    </div>

                                    <div class="row overflow-auto" style="max-height: 300px;min-height: 300px;">
                                        <div class="col-md-6 table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-secondary text-md text-dark">
                                                    <th>Sr. No.</th>
                                                    <th>No</th>
                                                    <th>Qty</th>
                                                    <th></th>
                                                </thead>
                                                <tbody>
                                                    <!-- this table body will be called when the index number is an even number and will add two ffields and 1 button in the row  -->
                                                    <tr v-for="(data, index) in allData" :key="index">
                                                        <td v-if="index % 2 ? 0 : 1">
                                                            {{index + 1}}
                                                        </td>
                                                        <td v-if="index % 2 ? 0 : 1">
                                                            <input type="number" class="form-control text-right"
                                                                v-model="data.no" :ref="'takano'+index">
                                                        </td>
                                                        <td v-if="index % 2 ? 0 : 1">
                                                            <input type="number" class="form-control text-right"
                                                                v-model="data.qty" @blur="sumTotalQuantity"
                                                                @click="selectQuantity(index)"
                                                                @keydown.tab.prevent="tranferCursor(index)"
                                                                @keyup.enter="enterPressed(index)" :ref="'qty'+index">
                                                        </td>
                                                        <td v-if="index % 2 ? 0 : 1">
                                                            <button class="btn btn-danger text-md" @click="deleteRow(index)"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-md-6 table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-secondary text-md text-dark">
                                                    <th>Sr. No.</th>
                                                    <th>No</th>
                                                    <th>Qty</th>
                                                    <th></th>
                                                </thead>
                                                <tbody>
                                                    <!-- this table body will be called when the index number is an odd number and will add two fields and 1 button in the row  -->
                                                    <tr v-for="(data, index) in allData" :key="index">
                                                        <td v-if="index % 2 ? 1 : 0">
                                                            {{index + 1}}
                                                        </td>
                                                        <td v-if="index % 2 ? 1 : 0">
                                                            <input type="number" class="form-control text-right"
                                                                v-model="data.no" :ref="'takano'+index">
                                                        </td>
                                                        <td v-if="index % 2 ? 1 : 0">
                                                            <input type="number" class="form-control text-right"
                                                                v-model="data.qty" @blur="sumTotalQuantity"
                                                                @click="selectQuantity(index)"
                                                                @keydown.tab.prevent="tranferCursor(index)"
                                                                @keyup.enter="enterPressed(index)" :ref="'qty'+index">
                                                        </td>
                                                        <td v-if="index % 2? 1 : 0">
                                                            <button class="btn btn-danger text-md" @click="deleteRow(index)"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- when we click on add product button add row function will be called -->
                                    <button class="btn btn-primary text-md mt-2" @click="addRow">Add Product</button>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="totalQty" class="text-md col-form-label mt-3">Total Quantity
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="text-md form-control mt-3 text-right"
                                                v-model="totalQty" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" @click="addChallan"
                                        class="btn btn-primary text-md">Add</button>
                                    <button type="reset" @click="resetFields"
                                        class="btn btn-primary ml-3 text-md">Reset</button>
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

    //it contains all the data properties and methods of all the events.
    export default {
        name: 'NewChallan',
        components: {
            ModelSelect,
        },
        data() {
            return {
                /*this are all the data properties which I have used as a v-model and here I have initailized it all*/
                challanDate: '',
                challanNo: '',
                companyNames: [],
                selectedCompanyName: '',
                brokerNames: [],
                selectedBrokerName: '',
                companyContactNo: '',
                companyGSTNo: '',
                productCategories: [],
                selectedProductCategory: '',
                productQualities: [],
                selectedProductQuality: '',
                unit: '',
                allData: [],
                totalQty: (0).toFixed(2),
                status: null,
                message: null,
                errors: null
            }
        },

        /*whatever we write in the mounted function will load on page refresh so here we have called some functions 
        like to display todays date, populate options for Company Name, Broker Name and Quality Categories on refreshing 
        the page.*/
        mounted() {
            this.challanDate = this.getTodaysDate();
            this.loadCompanyName();
            this.loadBrokerName();
            this.loadQualityCategories();
        },

        methods: {
            //this function will take todays date and format it in the form "yyyy-mm-dd"
            getTodaysDate: function () {
                let d = new Date()
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

            /* this function will be called when we click on Add Product button so on clicking it one row will be added
            and also have given a limit that no one could enter a row more than 48.*/
            addRow: function () {
                if (this.allData.length < 48) {
                    //so when the if condition will return true then two data properties will be added no and qty
                    this.allData.push({
                        no: "",
                        qty: (0).toFixed(2)
                    })

                } else {
                    toastr["warning"]("Limit Exceeded! You can only add 48 entries in One Challan.");
                }
            },

            //this function is used for entering a row by pressing enter key
            enterPressed: function (index = -1) {
                if (this.allData.length == (index + 1)) {
                    this.addRow();
                }
            },

            //this function will be called when we click on trash icon in the table which will delete that particular row   
            deleteRow: function (index) {
                this.allData.splice(index, 1);
                this.sumTotalQuantity();
            },

            //this function is used when we click the tab key so it will transfer the cursor to next input field 
            tranferCursor: function (index) {
                if (this.allData.length == (index + 1)) {
                    return;
                }
                this.$refs['takano' + (index + 1)][0].focus();
            },

            /*this function will call an api customerlist which will get all the customer informaiation like id, name and
            contact number and will populate in the searchable dropdown menu having value as an id and tet as an 
            mixture of company name and contact number*/
            loadCompanyName() {
                axios.get('../api/customerlist').then((response) => {
                    this.companyNames = response.data.map(company => {
                        return {
                            value: company.customer_id,
                            text: company.customer_company_name + ' - ' + company.customer_contact_no
                        }
                    });
                }).catch(err => {
                    console.log(err);
                    toastr["error"]("Something went Wrong.")
                })
            },

            /*this function will call an api brokerslist which will get all the broker informaiation like id, name and
            contact number and will populate in the searchable dropdown menu having value as an id and tet as an 
            mixture of company name and contact number*/
            loadBrokerName() {
                axios.get('../api/brokerslist').then((response) => {
                    this.brokerNames = response.data.map(broker => {
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

            /*this function will call an api sellqualitycategories which will get all the sell quality category 
            informaiation like id and name and will populate in the searchable dropdown menu having value as an 
            id and tet as a company name*/
            loadQualityCategories() {
                axios.get('../api/sellqualitycategories').then((response) => {
                    this.productCategories = response.data.qualityCategories.map(category => {
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

            /*this function will call when we select company name and we will call an api selected customer data with
            selected company id so that it will get all the information of the customer like contactNo and GST No
            of that particular selected customer*/
            getFromSelectedCompany: function () {
                /*this condition will be true if we have not selected a company name then it will 
                set the null value in contact no and gst no filed*/
                if (this.selectedCompanyName == '' || typeof (this.selectedCompanyName) === 'undefined') {
                    this.companyContactNo = '';
                    this.companyGSTNo = '';
                    return;
                }

                axios.get('../api/selectedcustomerdata/' + this.selectedCompanyName).then(response => {
                    this.companyContactNo = response.data.customer_contact_no;
                    this.companyGSTNo = response.data.customer_gst_no;
                }).catch(err => {
                    console.log(err);
                    toastr["error"]("Something went Wrong.")
                })
            },

            /*this function will call when we select product category and we will call an api sellquality 
            category with select product category id s that it will get all quality of that category and populate
            all the value in the options of that particular selected product category and null value to the unit field*/
            loadFromSelectedCategory: function () {
                /*this condition will be true if we have not selected a product category then it will 
                set the quality options to null*/
                if (this.selectedProductCategory == '' || typeof (this.selectedProductCategory) === 'undefined') {
                    this.unit = '';
                    this.productQualities = [];
                    return;
                }

                axios.get('../api/sellqualityofcategory/' + this.selectedProductCategory).then(response => {
                    this.productQualities = response.data.map(quality => {
                        return {
                            value: quality.sell_quality_id,
                            text: quality.quality_name
                        }
                    })

                    if (this.selectedProductCategory == '1') {
                        this.unit = "Meters";
                    } else if (this.selectedProductCategory == '2' || this.selectedProductCategory == '3') {
                        this.unit = "Kg.";
                    }
                }).catch(err => {
                    console.log(err);
                    toastr["error"]("Something went Wrong.")
                })
            },

            // this function will sum all the enetered quantities in the table and round off with precision 2
            sumTotalQuantity: function () {
                this.totalQty = (0).toFixed(2);
                for (let i = 0; i < this.allData.length; i++) {
                    this.totalQty = parseFloat(this.totalQty) + parseFloat(this.allData[i].qty);
                }
                if (this.totalQty != 0.00) {
                    this.totalQty = this.totalQty.toFixed(2);
                }
            },

            //this function will reset the challan no  it all already exists
            resetChallanNo() {
                this.challanNo = '';
            },
            
            /*this function will call an api get financial api by taking the challan date and will get a response of the
            financial year of entered challan date*/
            getFinancialYearOfChallanDate() {
                axios.get('../api/getfinancialyear/' + this.challanDate).then(response => {
                    this.verifyChallanNo(response.data.fromDate, response.data.toDate);
                }).catch(err => {
                    console.log(err);
                    toastr["error"]("Something went Wrong.")
                })
            },

            //this function will take the challan date and the financial year and check if the entered challan number exists or not 
            verifyChallanNo(fromDate, toDate) {
                if (this.challanNo == '') {
                    return;
                }
                axios.get('../api/verifychallan/' + this.challanNo + '/' + fromDate + '/' + toDate).then(response => {
                    if (response.data.status == 0) {
                        toastr["error"](response.data.message);
                        this.challanNo = '';
                    }
                }).catch(err => {
                    console.log(err);
                    toastr["error"]("Something went Wrong.")
                })

            },

            //this function will be called when we click on add challan button and take all the field values and enter it into the database by calling api
            addChallan() {
                //below addData objects conatins all field values information
                var addData = {};
                addData["challanNo"] = this.challanNo;
                addData["challanDate"] = this.challanDate;
                addData["customerId"] = this.selectedCompanyName;
                addData["sellCategoryId"] = this.selectedProductCategory;
                addData["sellQualityId"] = this.selectedProductQuality;
                addData["qtyUnit"] = this.unit;
                addData["totalQty"] = this.totalQty;
                addData["brokerId"] = this.selectedBrokerName;
                addData["allData"] = this.allData;//this contains an array of table value of no and quantity values

                axios.get('../api/getfinancialyear/' + this.challanDate).then(response => {
                    addData["fromDate"] = response.data.fromDate;
                    addData["toDate"] = response.data.toDate;
                    //here it will check if any of the field is empty or not
                    if (this.challanNo == '' || this.challanDate == '' || this.selectedCompanyName == '' || this.selectedProductQuality == '' || this.unit == '' || this.totalQty === '' || this.selectedBrokerName == '') {
                        toastr["error"]('All Fields are Required');
                    } else if (this.allData.length == 0) {//here it will check whether we have entered 1 product or not
                        toastr["error"]("Insert Product.");
                    } else {
                        //Here it will trigger an alert if any of the quantity or challan field is empty
                        for (let i = 0; i < this.allData.length; i++) {
                            if (this.allData[i].no == '') {
                                toastr["error"]((i + 1) + " number field is empty.");
                                return;
                            }
                        }

                        if (this.selectedProductCategory == 1 || this.selectedProductCategory == 2) {
                            var productNo = [];
                            for (let i = 0; i < this.allData.length; i++) {
                                productNo.push(this.allData[i].no);
                            }

                            var productNoSet = new Set(productNo);
                            var setArray = Array.from(productNoSet);
                            //it will trigger an alert if any of the product no and quantoty field contains a same value specifically for grey and beam quality
                            if (productNoSet.size != productNo.length) {
                                for (let i = 0; i < this.allData.length; i++) {
                                    if (productNo[i] != setArray[i]) {
                                        toastr["error"]((i + 1) + " number field contains duplicate value.");
                                        return;
                                    }
                                }
                            }
                        }

                        axios.post('../api/challan/insert', addData)
                            .then((res) => {
                                //status -1 indiactes an error 0 indictaes an warning and 1 indictaes an success message 
                                if (res.data.status == -1) {
                                    var errormsg = res.data.errors;

                                    try {
                                        if (errormsg.challanNo)
                                            toastr["error"](errormsg.challanNo);
                                    } catch (err) { }

                                    try {
                                        if (errormsg.challanDate)
                                            toastr["error"](errormsg.challanNo);
                                    } catch (err) { }

                                    try {
                                        if (errormsg.customerId)
                                            toastr["error"](errormsg.customerId);
                                    } catch (err) { }

                                    try {
                                        if (errormsg.sellCategoryId)
                                            toastr["error"](errormsg.sellCategoryId);
                                    } catch (err) { }

                                    try {
                                        if (errormsg.sellQualityId)
                                            toastr["error"](errormsg.sellQualityId);
                                    } catch (err) { }

                                    try {
                                        if (errormsg.qtyUnit)
                                            toastr["error"](errormsg.qtyUnit);
                                    } catch (err) { }

                                    try {
                                        if (errormsg.totalQty)
                                            toastr["error"](errormsg.totalQty);
                                    } catch (err) { }

                                    try {
                                        if (errormsg.brokerId)
                                            toastr["error"](errormsg.brokerId);
                                    } catch (err) { }

                                } else if (res.data.status == 0) {
                                    toastr["warning"](res.data.message);
                                } else if (res.data.status == 1) {
                                    swal.fire({
                                        title: 'Success',
                                        html: "<h5 style='color:#9C9794'>Challan Created Successfully!</h5>",
                                        icon: 'success'
                                    }).then((result) => {
                                        this.resetFields();
                                    });
                                }
                            }).catch((err) => {
                                console.log(err);
                                toastr["error"]("Something went Wrong!");
                            })
                    }

                }).catch(err => {
                    console.log(err);
                    toastr["error"]("Something went Wrong.")
                });


            },

            //this function will reset all fields of the form
            resetFields() {
                this.challanDate = this.getTodaysDate();
                this.challanNo = '',
                this.companyContactNo = '',
                this.companyGSTNo = '',
                this.selectedCompanyName = '',
                this.selectedBrokerName = '',
                this.unit = '',
                this.selectedProductQuality = '',
                this.selectedProductCategory = '',
                this.allData = [],
                this.totalQty = (0).toFixed(2)

            },

            //this function is used when we click an input field of the quantity in the table and select all data of that field
            selectQuantity(index) {
                this.$refs['qty' + (index)][0].select();
            }
        }

    }
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
</style>