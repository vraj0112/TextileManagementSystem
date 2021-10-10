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
                                    <h3 class="card-title">New Challan</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="challanDate" class="text-md col-form-label">Date <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" id="challanDate" v-model="challanDate"
                                                class="form-control text-md" @change="resetChallanNo">
                                        </div>

                                        <div class="col-md-2 ml-auto">
                                            <label for="challanNo" class="text-md col-form-label">Challan Number <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
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
    import toastr from 'toastr';
    import swal from 'sweetalert2';
    import { ModelSelect } from "vue-search-select";

    toastr.options = {
        closeButton: true,
        closeDuration: 200,
        progressBar: true,
        newestOnTop: true,
        positionClass: "toast-top-center",
    };

    export default {
        name: 'NewChallan',
        components: {
            ModelSelect,
        },
        data() {
            return {
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
        mounted() {
            this.challanDate = this.getTodaysDate();
            this.loadCompanyName();
            this.loadBrokerName();
            this.loadQualityCategories();
        },

        methods: {
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

            addRow: function () {
                if (this.allData.length < 48) {
                    this.allData.push({
                        no: "",
                        qty: (0).toFixed(2)
                    })

                } else {
                    toastr["warning"]("Limit Exceeded! You can only add 48 entries in One Challan.");
                }
            },

            enterPressed: function (index = -1) {
                if (this.allData.length == (index + 1)) {
                    this.addRow();
                }
            },

            deleteRow: function (index) {
                this.allData.splice(index, 1);
                this.sumTotalQuantity();
            },

            tranferCursor: function (index) {
                if (this.allData.length == (index + 1)) {
                    return;
                }
                this.$refs['takano' + (index + 1)][0].focus();
            },

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

            getFromSelectedCompany: function () {
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

            loadFromSelectedCategory: function () {

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

            sumTotalQuantity: function () {
                this.totalQty = (0).toFixed(2);
                for (let i = 0; i < this.allData.length; i++) {
                    this.totalQty = parseFloat(this.totalQty) + parseFloat(this.allData[i].qty);
                }
                if (this.totalQty != 0.00) {
                    this.totalQty = this.totalQty.toFixed(2);
                }
            },

            resetChallanNo() {
                this.challanNo = '';
            },

            getFinancialYearOfChallanDate() {
                axios.get('../api/getfinancialyear/' + this.challanDate).then(response => {
                    this.verifyChallanNo(response.data.fromDate, response.data.toDate);
                }).catch(err => {
                    console.log(err);
                    toastr["error"]("Something went Wrong.")
                })
            },

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

            addChallan() {
                var addData = {};
                addData["challanNo"] = this.challanNo;
                addData["challanDate"] = this.challanDate;
                addData["customerId"] = this.selectedCompanyName;
                addData["sellCategoryId"] = this.selectedProductCategory;
                addData["sellQualityId"] = this.selectedProductQuality;
                addData["qtyUnit"] = this.unit;
                addData["totalQty"] = this.totalQty;
                addData["brokerId"] = this.selectedBrokerName;
                addData["allData"] = this.allData;

                axios.get('../api/getfinancialyear/' + this.challanDate).then(response => {
                    addData["fromDate"] = response.data.fromDate;
                    addData["toDate"] = response.data.toDate;
                    if (this.challanNo == '' || this.challanDate == '' || this.selectedCompanyName == '' || this.selectedProductQuality == '' || this.unit == '' || this.totalQty === '' || this.selectedBrokerName == '') {
                        toastr["error"]('All Fields are Required');
                    } else if (this.allData.length == 0) {
                        toastr["error"]("Insert Product.");
                    } else {
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

            selectQuantity(index) {
                this.$refs['qty' + (index)][0].select();
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