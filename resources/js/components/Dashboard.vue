<template>
    <div>
        <aside></aside>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3 col-6 mt-3">
                            <!-- small card -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3 v-html="inward"></h3>

                                    <p>Inwards</p>
                                    <p style="margin-top: -1rem; margin-bottom: -0.5rem">
                                        <b>{{ financialYear }}</b>
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon nav-icon far bi bi-box-arrow-in-right"></i>
                                </div>
                                <router-link to="/manageinward" class="nav-link small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </router-link>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6 mt-3">
                            <!-- small card -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3 v-html="invoice"></h3>

                                    <p>Invoices</p>
                                    <p style="margin-top: -1rem; margin-bottom: -0.5rem">
                                        <b>{{ financialYear }}</b>
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon nav-icon far bi bi-receipt-cutoff"></i>
                                </div>
                                <router-link to="/managechallaninvoice" class="nav-link small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </router-link>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6 mt-3">
                            <!-- small card -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3 v-html="credit"></h3>

                                    <p>Credit</p>
                                    <p style="margin-top: -1rem; margin-bottom: -0.5rem">
                                        <b>{{ financialYear }}</b>
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fas fa-rupee-sign"></i>
                                </div>
                                <router-link to="/credit" class="nav-link small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </router-link>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6 mt-3">
                            <!-- small card -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3 v-html="expense"></h3>

                                    <p>Expense</p>
                                    <p style="margin-top: -1rem; margin-bottom: -0.5rem">
                                        <b>{{ financialYear }}</b>
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon far bi bi-dash-circle"></i>
                                </div>
                                <router-link to="/expensemanagement" class="nav-link small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </router-link>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>

    export default {
        name: "Dashboard",
        data() {
            return {
                inward: "",
                invoice: "",
                financialYear: "",
                credit: "",
                expense: "",
            };
        },
        mounted() {
            this.loadCalculations();
        },
        methods: {
            loadCalculations: function () {
                axios
                    .get("api/dashboarddata")
                    .then((res) => {
                        this.inward = res.data[1];
                        this.invoice = res.data[2];
                        this.financialYear =
                            this.printDate(res.data[0]["fromDate"]) +
                            " - " +
                            this.printDate(res.data[0]["toDate"]);
                        this.credit = "₹" + res.data[3];
                        this.expense = "₹" + res.data[4];
                    })
                    .catch((err) => {
                        console.log(err);
                        toastr.err("Something Went Wrong!");
                    });
            },

            printDate: function (date) {
                let printableDate = date.split("-");
                return printableDate[2] + "/" + printableDate[1] + "/" + printableDate[0];
            },
        },
    };
</script>