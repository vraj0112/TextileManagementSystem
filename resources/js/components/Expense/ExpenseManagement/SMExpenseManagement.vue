<template>
    <div>
        <!-- Content Wrapper. Contains page content -->
        <div class="row">
            <!-- left column -->
            <div class="col-md-12 mt-3">
                <!-- New Expense Category Form Elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            S&M Expense
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body table-responsive">
                        <div class="row">
                            <div class="col-md-1">
                                <label for="from-date">From Date</label>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="from-date" v-model="fromDate" />
                            </div>
                            <div class="col-md-1">
                                <label for="to-date">To Date</label>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="to-date" v-model="toDate" />
                            </div>
                            <div class="col-md-1">
                                <label for="expense-category">Expense Category</label>
                            </div>
                            <div class="col-md-3">
                                <model-select :options="categoriesForFilter" v-model="selectedExpenseCategoryForFilter"
                                    placeholder="Select Category">
                                </model-select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-1">
                                <label for="">Per Page</label>
                            </div>
                            <div class="col-md-3">
                                <select v-model="paginate" class="form-control">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                </select>
                            </div>
                            <div class="col-md-5"></div>
                            <div class="col-md-3 form-group">
                                <input v-model="search" type="search" class="form-control "
                                    placeholder="Search By ..." />
                            </div>
                        </div>

                        <div class="p-0 mt-3">
                            <table class="table table-hover table-bordered table-striped table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="12%">
                                            <a href="#" @click.prevent="updateSorting('expense_date')                                               ">Date</a>
                                            <span v-if="sort_field == 'expense_date'? 1: 0">
                                                <span v-if="sort_direction == 'asc'? 1: 0">&uarr;</span>
                                                <span v-if="sort_direction == 'desc'? 1: 0">&darr;</span>
                                            </span>
                                        </th>
                                        <th>
                                            <a href="#" @click.prevent="updateSorting('expense_description')">Expense Description</a>
                                            <span v-if="sort_field =='expense_description'? 1: 0">
                                                <span v-if="sort_direction == 'asc'? 1: 0">&uarr;</span>
                                                <span v-if="sort_direction == 'desc'? 1: 0">&darr;</span>
                                            </span>
                                        </th>
                                        <th width="25%">Expense Category</th>
                                        <th>Amount</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="expense in expenses.data" v-bind:key="expense.expense_id">
                                        <td>{{ expense.expense_date }}</td>
                                        <td>{{ expense.expense_description }}</td>
                                        <td>{{ expense.expense_category }}</td>
                                        <td class="text-right">{{ expense.expense_amount }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm" @click="editExpense(expense.expense_id,expense.expense_date,expense.expense_description,expense.expense_amount, expense.expense_category_id)">Edit</button>
                                            <button class="btn btn-danger btn-sm" @click="deleteExpense(expense.expense_id)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-6 offset-5">
                                <pagination :data="expenses" @pagination-change-page="getAllExpenses"></pagination>
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
                        <div class="row mt-2">
                            <!-- <div class="col-md-2"></div> -->
                            <div class="col-md-9 text-right">
                                <label for="" class="mt-2">
                                    Total Amount From <span class="text-success"> {{ printDate(fromDate) }} </span> to <span class="text-success"> {{ printDate(toDate) }} </span> of <span class="text-danger"> {{selectedExpenseCategoryForFilter.text}} </span> Category and Search Term = <span class="text-danger">"{{search}}"</span> :
                                </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control text-right" v-model="totalAmountOfGivenDateRangeAndCategoryAndSearchTerm" disabled />
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="expenseId == -1 ? 0:1" class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            Update Expense
                        </h3>
                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" @click="closeUpdateExpenseBtn" ><i class="fas fa-times"></i>
                        </button>
                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label
                                    for="expense-date"
                                    class="text-md col-form-label"
                                    >Date</label
                                >
                                <span class="required-mark" style="color: red;">*</span>
                            </div>
                            <div class="col-md-3">
                                <input
                                    id='expense-date'
                                    type="date"
                                    class="form-control text-md"
                                    v-model="expenseDate"
                                />
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-2">
                                <label
                                    for="expense-category"
                                    class="text-md col-form-label"
                                    >Expense Category</label
                                >
                                <span class="required-mark" style="color: red;">*</span>
                            </div>
                            <div class="col-md-3">
                                <model-select :options="categoriesForEdit" v-model="expenseCategory"
                                    placeholder="Select Category">
                                </model-select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label
                                    for="expense-description"
                                    class="text-md col-form-label"
                                    >Expense Description</label
                                >
                                <span class="required-mark" style="color: red;">*</span>
                            </div>
                            <div class="col-md-4">
                                <textarea
                                    id='expense-description'
                                    type="text"
                                    class="form-control text-md"
                                    rows="5"
                                    v-model="expenseDescription"
                                    placeholder="Enter Description Of Expense..."
                                />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label
                                    for="expense-amount"
                                    class="text-md col-form-label"
                                    >Amount</label
                                >
                                <span class="required-mark" style="color: red;">*</span>
                            </div>
                            <div class="col-md-4">
                                <input
                                    id='expense-amount'
                                    type="number"
                                    class="form-control text-md text-right"
                                    @click="selectAmount"
                                    @change="roundFigureAmount"
                                    v-model="expenseAmount"
                                    ref="expenseAmount"
                                />
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <!-- card-footer -->
                    <div class="card-footer">
                        <button
                            type="submit"
                            class="btn btn-primary text-md"
                            @click="updateExpense"
                        >
                            Update
                        </button>
                        <button
                            type="reset"
                            class="btn btn-primary ml-3 text-md"
                            @click="resetUpdateExpenseForm"
                        >
                            Reset
                        </button>

                        
                    </div>
                    <!-- /.card-footer -->
                </div>
                
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div>
</template>

<script>
    import toastr from "toastr";
    import swal from "sweetalert2";
    import { ModelSelect } from "vue-search-select";

    export default {
        name: "SMExpenseManagement",
        components: {
            ModelSelect
        },
        data() {
            return {
                expenses: {},
                categoriesForFilter: [],
                selectedExpenseCategoryForFilter: {
                    value: "",
                    text: "All"
                },
                paginate: "10",
                search: "",
                sort_direction: "desc",
                sort_field: "expense_date",
                fromDate: "",
                toDate: "",
                

                categoriesForEdit: [],
                selectedExpenseCategoryForEdit: "",

                expenseId: -1,
                expenseDate: "",
                expenseDescription: "",
                expenseCategory: "",
                expenseAmount: (0).toFixed(2),
                

                days: 10,
                totalAmountOfPage: 0,
                totalAmountOfGivenDateRangeAndCategoryAndSearchTerm: 0
            };
        },
        mounted() {
            this.toDate = this.getTodaysDate();
            this.fromDate = this.getDateBeforeDays();
            this.getAllExpenseCategoriesForFilter();
            this.getAllExpenses();
            this.getAllExpenseCategoriesForEdit();
        },
        watch: {
            paginate: function () {
                this.getAllExpenses();
            },
            search: function () {
                this.getAllExpenses();
                this.getAmountForGivenDateAndCategory();
            },
            fromDate: function () {
                this.getAllExpenses();
                this.getAmountForGivenDateAndCategory();
            },
            toDate: function () {
                this.getAllExpenses();
                this.getAmountForGivenDateAndCategory();
            },
            selectedExpenseCategoryForFilter: function () {
                this.getAllExpenses();
                this.getAmountForGivenDateAndCategory();
            }
        },
        methods: {
            getAllExpenses: function (page = 1) {
                axios
                    .get(
                        "/api/expense?page=" +
                        page +
                        "&paginate=" +
                        this.paginate +
                        "&search=" +
                        this.search +
                        "&sortdirection=" +
                        this.sort_direction +
                        "&sortfield=" +
                        this.sort_field +
                        "&fromdate=" +
                        this.fromDate +
                        "&todate=" +
                        this.toDate +
                        "&expense_category_id=" +
                        this.selectedExpenseCategoryForFilter.value
                    )
                    .then((response, err) => {
                        if (err) {
                            toastr.error("Something Went Wrong");
                        } else {
                            this.expenses = response.data;
                            let totalAmountOfPage = 0;
                            for(let i=0; i<this.expenses.data.length; i++){
                                this.expenses.data[i]["expense_amount"] = (this.expenses.data[i]["expense_amount"]).toFixed(2);
                                totalAmountOfPage += parseFloat(this.expenses.data[i]["expense_amount"]);
                            }
                            this.totalAmountOfPage = totalAmountOfPage.toFixed(2);
                        }
                    });
            },

            getAllExpenseCategoriesForFilter: function () {
                axios
                    .get("/api/expensecategorieslist")
                    .then(res => {
                        if (res.data.status == 1) {

                            let allEntry = [{value: "", text: "All"}];
                            let individualEntry = res.data.data.map(category => {
                                return {
                                    value: category.expense_category_id,
                                    text: category.expense_category
                                };
                            });

                            this.categoriesForFilter = allEntry.concat(individualEntry);
                        }
                    })
                    .catch(() => {
                        toastr.error(
                            "Some thing Went Wrong, Please Refreash The Page"
                        );
                    });
            },

            getAmountForGivenDateAndCategory: function(){
                axios.get(`/api/totalexpenseamount?fromdate=${this.fromDate}&todate=${this.toDate}&search=${this.search}&categoryid=${this.selectedExpenseCategoryForFilter.value}`).then((res) => {
                    this.totalAmountOfGivenDateRangeAndCategoryAndSearchTerm = res.data.toFixed(2);
                })
                .catch((err) => {
                    console.log(`Exception In ${this}`);
                    toastr.error("Something Went Wrong");
                })
            },

            getAllExpenseCategoriesForEdit: function(){
                console.log("Hello");
                axios
                    .get("/api/expensecategorieslist")
                    .then(res => {
                        if (res.data.status == 1) {

                            this.categoriesForEdit = res.data.data.map(category => {
                                return {
                                    value: category.expense_category_id,
                                    text: category.expense_category
                                };
                            });
                        }
                    })
                    .catch(() => {
                        toastr.error(
                            "Some thing Went Wrong, Please Refreash The Page"
                        );
                    });
            },

            printDate: function(date){
                let printableDate = date.split("-");
                return (printableDate[2] + "/" + printableDate[1] + "/" +   printableDate[0]);
            },

            selectAmount: function(){
                this.$refs.expenseAmount.select();
            },

            updateSorting: function (field) {
                if (this.sort_field == field) {
                    this.sort_direction =
                        this.sort_direction == "asc" ? "desc" : "asc";
                } else {
                    this.sort_field = field;
                }
                this.getAllExpenses(this.expenses.current_page);
            },

            getStdDate: function(date){
                date = date.split("-");
                return (date[2]+"-"+date[1]+"-"+date[0]);
            },

            editExpense: function (expense_id,expense_date,expense_description,expense_amount, expense_category_id) {
                this.expenseId = expense_id;
                this.expenseDescription = expense_description;
                this.expenseAmount = expense_amount;
                this.expenseCategory = expense_category_id;
                this.expenseDate = this.getStdDate(expense_date);
                toastr.info("Please Scroll Down");
            },

            roundFigureAmount: function(){
                this.expenseAmount = parseFloat(this.expenseAmount).toFixed(2)
            },

            updateExpense: function () {
                if(this.validateExpenseDate() && this.validateExpenseDescription() && this.validateExpenseCategory() && this.validateExpenseAmount()){
                    
                    let payload = {
                        expenseId: this.expenseId,
                        expenseDate: this.expenseDate,
                        expenseDescription: this.expenseDescription,
                        expenseCategoryId: this.expenseCategory,
                        expenseAmount: this.expenseAmount
                    }

                    axios
                        .put("/api/expense", payload)
                        .then(res => {
                            if(res.data.status == 1){
                                swal.fire({
                                    title: "Success",
                                    html:"<h5 style='color:#9C9794'>Expense Updated Successfully</h5>",
                                    icon: "success"
                                })
                                .then(result => {
                                    this.resetUpdateExpenseForm();
                                    this.getAllExpenses(this.expenses.current_page);
                                    this.expenseId = -1;
                                    this.getAmountForGivenDateAndCategory();
                                })
                            }
                            else if(res.data.status == -1){
                                if(res.data.message == "Validation Failed"){
                                    let errorString = "";
                                    let errors = res.data.errors;

                                    for(let field in errors){
                                        for(let i=0; i<errors[field].length; i++){
                                            errorString += "<li>"+(errors[field][i]+"</li>");
                                        }
                                    }

                                    toastr.error(errorString, res.data.message , {timeOut: 20000, "closeButton": true})
                                }
                                else{
                                    toastr.error(res.data.message);
                                }
                            }
                        })
                        .catch((err)=>{
                            console.log("Exception In Update Expense");
                            toastr.error("Something Went Wrong");
                        });
                    }
            },

            validateExpenseCategory: function(){
                if(this.expensesCategory == ""){
                    toastr.info("Please Select Expense Category");
                    return false;
                }

                return true;
            },

            validateExpenseDescription: function(){
                if(this.expenseDescription == ""){
                    toastr.info("Expense Description Is Required");
                    return false;
                }

                return true;
            },

            validateExpenseAmount: function(){

                if(this.expenseAmount == "" || isNaN(this.expenseAmount)){
                    toastr.info("Expense Amount Is Required");
                    return false;
                }

                if(this.expenseAmount < 0){
                    toastr.info("Expense Amount Can Not Be Negative");
                    return false;
                }

                return true;
            },

            validateExpenseDate: function(){
                if(this.expenseDate == ""){
                    toastr.info("Expense Date Is Required");
                    return false;
                }
                return true;
            },

            resetUpdateExpenseForm: function () {
                this.expenseDate = this.getTodaysDate();
                this.expenseDescription = "";
                this.expenseCategory = "";
                this.expenseAmount = (0).toFixed(2);
            },

            deleteExpense: function (expense_id) {
                axios
                    .delete("/api/expense/" + expense_id)
                    .then(res => {
                        if (res.data.status == 1) {
                            swal.fire({
                                title: "Success",
                                html:
                                    "<h5 style='color:#9C9794'>Expense Deleted Successfully</h5>",
                                icon: "success"
                            }).then(() => {
                                this.getAllExpenses(this.expenses.current_page);
                                this.getAmountForGivenDateAndCategory();
                            });
                        } else if (res.data.status == -1) {
                            toastr.error(res.data.message);
                        } else {
                            toastr.error("Something Went Wrong");
                            console.log("Error In Delete Expense API");
                        }
                    })
                    .catch(err => {
                        console.log("Exception in Delete Expense API");
                        toastr.error("Something Went Wrong");
                    });
            },

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
                console.log(month);

                if (day < 10) {
                    day = "0" + day;
                }

                if (month < 10) {
                    month = "0" + month;
                }
                console.log(month);
                return year + "-" + month + "-" + day;
            },

            closeUpdateExpenseBtn: function() {
                this.expenseId = -1;
                this.resetUpdateExpenseForm();
            },            
        }
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
</style>