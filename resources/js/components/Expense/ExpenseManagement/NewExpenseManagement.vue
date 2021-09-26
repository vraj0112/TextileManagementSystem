<template>
    <div>
        <div class="row">
            <!-- left column -->
            <div class="col-md-12 mt-3">
                <!-- New Expense Category Form Elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            New Expense
                        </h3>
                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
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
                                <model-select :options="expenseCategories" v-model="expenseCategory"
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
                            @click="addExpense"
                        >
                            Add
                        </button>
                        <button
                            type="reset"
                            class="btn btn-primary ml-3 text-md"
                            @click="resetNewExpenseForm"
                        >
                            Reset
                        </button>

                        
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div>
</template>

<script>

import toastr from 'toastr';
import swal from 'sweetalert2';
import { ModelSelect } from "vue-search-select";

export default {
    name: "NewExpenseManagement",
    components: {
        ModelSelect
    },
    data(){
        return {
            expenseDate: '',
            expenseCategory: "",
            expenseDescription: "",
            expenseAmount: (0).toFixed(2),
            expenseCategories: []
        }
    },
    mounted() {
        this.expenseDate = this.getTodaysDate();
        this.getAllExpenseCategories();
    },
    methods: {
        roundFigureAmount: function(){
            this.expenseAmount = parseFloat(this.expenseAmount).toFixed(2)
        },

        selectAmount: function(){
            this.$refs.expenseAmount.select();
        },

        addExpense: function(){
            if(this.validateExpenseDate() && this.validateExpenseCategory() && this.validateExpenseDescription() && this.validateExpenseAmount()){
                
                let payload = {
                    expenseDate: this.expenseDate,
                    expenseCategory: this.expenseCategory,
                    expenseDescription: this.expenseDescription,
                    expenseAmount: this.expenseAmount
                }

                axios
                    .post("/api/expense", payload)
                    .then((res) => {
                        if(res.data.status == 1){
                            swal.fire({
                                    title: "Success",
                                    html:"<h5 style='color:#9C9794'>Expense Added Successfully</h5>",
                                    icon: "success"
                            })
                            .then(result => {
                                this.resetNewExpenseForm();
                                this.$emit("refreashExpenseTable");
                            })
                            .catch(err=>{
                                toastr.error("Something Went Wrong");
                                console.log("Exception in New Expense Addition");
                            });
                        }
                        else if(res.data.status == -1){     
                            if(res.data.message == "Validation Failed"){
                                let errorString = "";
                                let errors = res.data.errors;

                                for(let field in errors){
                                    for(let i=0; i<errors[field].length; i++){
                                        errorString += "<li>"+(errors[field][i]+"</li>");
                                    }
                                };
                                toastr.error(errorString, res.data.message , {timeOut: 20000, "closeButton": true})
                            }
                            else{
                                toastr.error(res.data.message);
                            }
                        }
                        else{
                            toastr.error("Some thing Went Wrong");
                        }
                    })
                    .catch(err => {
                        console.log("Exception In Add New Expense Function");
                        toastr.error("Something Went Wrong");
                    });
            }    
        },

        validateExpenseDate: function(){
            if(this.expenseDate == ""){
                toastr.info("Expense Date Is Required");
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
                toastr.info("Expense Amount is required");
                return false;
            }
            
            if(this.expenseAmount < 0){
                toastr.info("Expense Amount Can't be Negative");
                return false;
            }

            return true;
        },

        validateExpenseCategory: function () {
            if(this.expenseCategory == -1){
                toastr.info("Expense Category Is Required");
                return false;
            }

            return true;
        },

        resetNewExpenseForm: function(){
            this.expenseAmount = (0).toFixed(2);
            this.expenseDate = this.getTodaysDate();
            this.expenseDescription = "";
            this.expenseCategory = -1;
        },

        getTodaysDate: function(){
            let d = new Date()
            let month = '' + (d.getMonth() + 1);
            let day = '' + d.getDate();
            let year = d.getFullYear();
            if(month < 10){
                month = '0'+month;
            }

            if(day < 10){
                day = '0' + day;
            }

            return (year+"-"+month+"-"+day);
        },

        getAllExpenseCategories: function(){
            axios.get("/api/expensecategorieslist").then((res) => {
                if(res.data.status == 1){
                    this.expenseCategories = res.data.data.map(category => {
                        return {
                            value: category.expense_category_id,
                            text: category.expense_category
                        }
                    });
                }
            })
            .catch(() => {
                toastr.error("Some thing Went Wrong, Please Refreash The Page");
            })
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