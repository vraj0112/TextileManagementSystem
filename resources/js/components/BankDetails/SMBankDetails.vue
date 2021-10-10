<template>
    <div>
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Bank Details</h3>
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
                                        <label for="paginate" class="text-nowrap mr-2 mb-0">
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
                                <input v-model="search" type="search" class="form-control" placeholder="Search By..." />
                            </div>
                        </div>

                        <div class="p-0">
                            <table class="table table-hover table-bordered table-striped table-sm">
                                <thead class="text-md">
                                    <tr>
                                        <th width="10%">
                                            <a href="#" @click.prevent="updateSorting('bank_details_id')">Sr. No.</a>
                                            <span v-if="sort_field == 'bank_details_id' ? 1 : 0">
                                                <span v-if="sort_direction == 'asc' ? 1 : 0">&uarr;</span>
                                                <span v-if="sort_direction == 'desc' ? 1 : 0">&darr;</span>
                                            </span>
                                        </th>
                                        <th>
                                            <a href="#" @click.prevent="updateSorting('bank_name')">Bank Name</a>
                                            <span v-if="sort_field == 'bank_name' ? 1 : 0">
                                                <span v-if="sort_direction == 'asc' ? 1 : 0">&uarr;</span>
                                                <span v-if="sort_direction == 'desc' ? 1 : 0">&darr;</span>
                                            </span>
                                        </th>
                                        <th>
                                            <a href="#" @click.prevent="updateSorting('branch_name')">Branch
                                                Name</a>
                                            <span v-if="sort_field == 'branch_name' ? 1 : 0">
                                                <span v-if="sort_direction == 'asc' ? 1 : 0">&uarr;</span>
                                                <span v-if="sort_direction == 'desc' ? 1 : 0">&darr;</span>
                                            </span>
                                        </th>
                                        <th width="20%">IFSC Code</th>
                                        <th width="20%">Account No.</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-md">
                                    <tr v-for="bankdetail in bankdetails.data" v-bind:key="bankdetail.bank_details_id">
                                        <td>
                                            {{ bankdetail.bank_details_id }}
                                        </td>
                                        <td>
                                            {{ bankdetail.bank_name }}
                                        </td>
                                        <td>
                                            {{ bankdetail.branch_name }}
                                        </td>
                                        <td>
                                            {{ bankdetail.ifsc_code }}
                                        </td>
                                        <td>
                                            {{ bankdetail.account_no }}
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm text-md" @click="
                                                    updateBankDetailBtn(
                                                    bankdetail.bank_details_id,
                                                    bankdetail.bank_name,
                                                    bankdetail.branch_name,
                                                    bankdetail.ifsc_code,
                                                    bankdetail.account_no
                                                )
                                            ">
                                                <i class="fas fa-pen"></i>
                                            </button>

                                            <button class="btn btn-danger btn-sm text-md"
                                                @click="deleteBankDetail(bankdetail.bank_details_id)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-6 offset-5">
                                <pagination :data="bankdetails" @pagination-change-page="getAllBankDetails">
                                </pagination>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="bankDetailId == -1 ? 0 : 1" class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Update Bank Details</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" @click="closeEditBankDetail">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group" style="display: flex; flex-direction: row">
                            <label for="bankName" class="col-md-2 text-md">Bank Name <span class="required-mark"
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control col-md-3" v-model="bankName"
                                placeholder="Enter Bank Name..." />
                            <div class="col-md-1"></div>
                            <label for="branchName" class="col-md-2 text-md">Branch Name <span class="required-mark"
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control col-md-3" v-model="branchName"
                                placeholder="Enter Branch Name..." />
                        </div>
                        <div class="form-group" style="display: flex; flex-direction: row">
                            <label for="ifscCode" class="col-md-2 text-md">IFSC Code <span class="required-mark"
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control col-md-3" maxlength="11" v-model="ifscCode"
                                placeholder="Enter IFSC Code..." />
                            <div class="col-md-1"></div>
                            <label for="accNo" class="col-md-2 text-md">Account No. <span class="required-mark"
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control col-md-3" v-model="accNo"
                                placeholder="Enter Account No..." />
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" @click="updateBankDetailsSaveUpdate">
                            Update
                        </button>
                        <button class="btn btn-primary" @click="resetUpdateBankDetail">
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
        name: "SMBankDetails",
        data() {
            return {
                bankdetails: {},
                paginate: "10",
                search: "",
                sort_direction: "asc",
                sort_field: "bank_details_id",
                bankDetailId: -1,
                bankName: "",
                branchName: "",
                ifscCode: "",
                accNo: "",
                status: null,
                message: null,
                errors: null,
            };
        },
        mounted() {
            this.getAllBankDetails();
        },
        watch: {
            paginate: function () {
                this.getAllBankDetails();
            },
            search: function () {
                this.getAllBankDetails();
            },
        },
        methods: {
            getAllBankDetails: function (page = 1) {
                axios
                    .get(
                        "/api/bankdetails?page=" +
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
                        this.bankdetails = response.data;
                    });
            },

            updateSorting: function (field) {
                if (this.sort_field == field) {
                    this.sort_direction = this.sort_direction == "asc" ? "desc" : "asc";
                } else {
                    this.sort_field = field;
                }
                this.getAllBankDetails(this.bankdetails.current_page);
            },

            updateBankDetailBtn: function (bank_details_id, bank_name, branch_name, ifsc_code, account_no) {
                toastr.info("Please scroll down the page!");
                this.bankDetailId = bank_details_id;
                this.bankName = bank_name;
                this.branchName = branch_name;
                this.ifscCode = ifsc_code;
                this.accNo = account_no;
            },

            updateBankDetailsSaveUpdate: function () {
                if (this.bankName == "" || this.branchName == "" || this.ifscCode == "" || this.accNo == "") {
                    toastr["error"]("All Fields are Required");
                } else {
                    axios
                        .put("/api/bankdetail/update/" + this.bankDetailId, {
                            bankNameDetail: this.bankName,
                            branchNameDetail: this.branchName,
                            ifscCodeDetail: this.ifscCode,
                            accNoDetail: this.accNo,
                        })
                        .then((res) => {
                            if (res.data.status == 1) {
                                swal
                                    .fire({
                                        title: "Success",
                                        html: "<h5 style='color:#9C9794'>Bank Details Updated Successfully</h5>",
                                        icon: "success",
                                    })
                                    .then((result) => {
                                        this.bankDetailId = -1;
                                        this.bankName = "";
                                        this.branchName = "";
                                        this.ifscCode = "";
                                        this.accNo = "";
                                        this.getAllBankDetails(this.bankdetails.current_page);
                                    });
                            } else if (res.data.status == 0) {
                                toastr.info(res.data.message);
                            } else {
                                toastr.error(res.data.message);
                            }
                        })
                        .catch((err) => {
                            toastr.error("Something Went Wrong");
                        });
                }
            },

            resetUpdateBankDetail: function () {
                this.bankName = "";
                this.branchName = "";
                this.ifscCode = "";
                this.accNo = "";
            },

            deleteBankDetail: function (bank_detail_id) {
                axios
                    .delete("/api/bankdetail/delete/" + this.bank_details_id)
                    .then((res) => { })
                    .catch({});
            },

            closeEditBankDetail: function () {
                this.bankDetailId = -1;
            },

            deleteBankDetail: function (bank_details_id) {
                axios
                    .delete("/api/bankdetail/delete/" + bank_details_id)
                    .then((res) => {
                        swal
                            .fire({
                                title: "Success",
                                html: "<h5 style='color:#9C9794'>Bank Details Deleted Successfully</h5>",
                                icon: "success",
                            })
                            .then((result) => {
                                this.$emit("refreshBankDetailsTable");
                                this.getAllBankDetails(this.bankdetails.current_page);
                            });
                    })
                    .catch((err) => {
                        toastr.error(res.error.message);
                        console.log(res.error.message);
                    });
            }
        },
    };
</script>