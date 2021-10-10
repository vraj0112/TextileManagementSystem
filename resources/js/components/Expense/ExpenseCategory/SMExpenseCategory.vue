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
                            Search and Manage Expense Category
                        </h3>
                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                        </button>
                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body table-responsive">
                        <div
                            class="d-flex justify-content-between align-content-center mb-2"
                        >
                            <div class="d-flex">
                                <div>
                                    <div class="d-flex align-items-center ml-4">
                                        <label
                                            for="paginate"
                                            class="text-nowrap mr-2 mb-0"
                                        >
                                            Per Page
                                        </label>
                                        <select
                                            v-model="paginate"
                                            class="form-control form-control-sm"
                                        >
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <input
                                    v-model="search"
                                    type="search"
                                    class="form-control "
                                    placeholder="Search By ..."
                                />
                            </div>
                        </div>

                        <div class="p-0">
                            <table
                                class="table table-hover table-bordered table-striped table-sm"
                            >
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="10%">
                                            <a
                                                href="#"
                                                @click.prevent="
                                                    updateSorting(
                                                        'expense_category_id'
                                                    )
                                                "
                                                >Sr. No.</a
                                            >
                                            <span
                                                v-if="
                                                    sort_field ==
                                                    'expense_category_id'
                                                        ? 1
                                                        : 0
                                                "
                                            >
                                                <span
                                                    v-if="
                                                        sort_direction == 'asc'
                                                            ? 1
                                                            : 0
                                                    "
                                                    >&uarr;</span
                                                >
                                                <span
                                                    v-if="
                                                        sort_direction == 'desc'
                                                            ? 1
                                                            : 0
                                                    "
                                                    >&darr;</span
                                                >
                                            </span>
                                        </th>
                                        <th>
                                            <a
                                                href="#"
                                                @click.prevent="
                                                    updateSorting(
                                                        'expense_category'
                                                    )
                                                "
                                                >Category Name</a
                                            >
                                            <span
                                                v-if="
                                                    sort_field ==
                                                    'expense_category'
                                                        ? 1
                                                        : 0
                                                "
                                            >
                                                <span
                                                    v-if="
                                                        sort_direction == 'asc'
                                                            ? 1
                                                            : 0
                                                    "
                                                    >&uarr;</span
                                                >
                                                <span
                                                    v-if="
                                                        sort_direction == 'desc'
                                                            ? 1
                                                            : 0
                                                    "
                                                    >&darr;</span
                                                >
                                            </span>
                                        </th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="category in categories.data"
                                        v-bind:key="
                                            category.expense_category_id
                                        "
                                    >
                                        <td>
                                            {{ category.expense_category_id }}
                                        </td>
                                        <td>{{ category.expense_category }}</td>

                                        <td class="text-center">
                                            <button
                                                class="btn btn-primary btn-sm"
                                                @click="
                                                    editCategoryBtn(
                                                        category.expense_category_id,
                                                        category.expense_category
                                                    )
                                                "
                                            >
                                                Edit
                                            </button>

                                            <button
                                                class="btn btn-danger btn-sm"
                                                @click="deleteCategory(category.expense_category_id)"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-6 offset-5">
                                <pagination
                                    :data="categories"
                                    @pagination-change-page="
                                        getAllExpenseCategories
                                    "
                                ></pagination>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="expenseCategoryId == -1 ? 0 : 1"
                    class="card card-primary" id="updating-card"
                >
                    <div class="card-header">
                        Update Category
                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" @click="closeUpdateExpenseCategoryBtn" ><i class="fas fa-times"></i>
                        </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="">Category</label>
                                <span class="required-mark" style="color: red;">*</span>
                            </div>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    maxlength="50"
                                    class="form-control"
                                    v-model="expenseCategoryTxt"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button
                            class="btn btn-primary"
                            @click="updateCategorySaveUpdate"
                        >
                            Update
                        </button>
                        <button
                            class="btn btn-primary"
                            @click="resetUpdateCategory"
                        >
                            Reset
                        </button>
                    </div>
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

export default {
    name: "SMExpenseCategory",
    data() {
        return {
            categories: {},
            paginate: "10",
            search: "",
            sort_direction: "asc",
            sort_field: "expense_category_id",
            expenseCategoryId: -1,
            expenseCategoryTxt: ""
        };
    },
    mounted() {
        this.getAllExpenseCategories();
    },
    watch: {
        paginate: function() {
            this.getAllExpenseCategories();
        },
        search: function() {
            this.getAllExpenseCategories();
        }
    },
    methods: {
        getAllExpenseCategories: function(page = 1) {
            axios
                .get(
                    "/api/expensecategories?page=" +
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
                    this.categories = response.data;
                });
        },

        updateSorting: function(field) {
            if (this.sort_field == field) {
                this.sort_direction =
                    this.sort_direction == "asc" ? "desc" : "asc";
            } else {
                this.sort_field = field;
            }
            this.getAllExpenseCategories(this.categories.current_page);
        },

        editCategoryBtn: function(expense_category_id, expense_category) {
            this.expenseCategoryId = expense_category_id;
            this.expenseCategoryTxt = expense_category;

            toastr.info("Please Scroll Down");
        },

        updateCategorySaveUpdate: function() {
            if (this.expenseCategoryTxt == "") {
                toastr.info("Please Enter Expense Category To Be Updated");
            } else if (this.expenseCategoryTxt.length > 50) {
                toastr.warning("Max Character In Expense Category is 50");
            } else {
                axios
                    .put(
                        "/api/expensecategory/update/" + this.expenseCategoryId,
                        {
                            expenseCategory: this.expenseCategoryTxt
                        }
                    )
                    .then(res => {
                        if (res.data.status == 1) {
                            swal.fire({
                                title: "Success",
                                html:
                                    "<h5 style='color:#9C9794'>Expense Category Updated Successfully</h5>",
                                icon: "success"
                            }).then(result => {
                                this.expenseCategoryId = -1;
                                this.expenseCategoryTxt = "";
                                this.getAllExpenseCategories(
                                    this.categories.current_page
                                );
                            });
                        } else if (res.data.status == 0) {
                            toastr.info(res.data.message);
                        } else {
                            toastr.error(res.data.message);
                        }
                    })
                    .catch(err => {
                        toastr.error("Something Went Wrong");
                    });
            }
        },

        resetUpdateCategory: function() {
            this.expenseCategoryTxt = "";
        },

        deleteCategory: function(expense_category_id) {
            axios
                .delete("/api/expensecategory/"+ expense_category_id)
                .then(res => {
                    if(res.data.status == 1){
                        swal.fire({
                                title: "Success",
                                html:
                                    "<h5 style='color:#9C9794'>Expense Category Deleted Successfully</h5>",
                                icon: "success"
                            }).then(() => { 
                                this.getAllExpenseCategories(
                                    this.categories.current_page
                                );
                            });
                    }
                    else if(res.data.status == 0){
                        toastr.warning(res.data.message);
                    }
                    else{
                        toastr.error(res.data.message + " " +  res.data.errors.expenseCategoryId[0]);

                    }
                })
                .catch((err)=>{
                    console.log(err);
                    toastr.error("Something Went Wrong");
                });
        },

        closeUpdateExpenseCategoryBtn: function(){
            this.resetUpdateCategory();
            this.expenseCategoryId = -1;
        }
    }
};
</script>
