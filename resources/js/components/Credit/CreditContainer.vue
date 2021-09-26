<template>
    <div>
        <aside></aside>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <NewCredit
                        @refreshDetailsTable="refreshDetailsTable"
                    />
                    <SMCredit ref="form" />
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import NewCredit from "./NewCredit";
import SMCredit from "./SMCredit";

export default {
    name: "CreditContainer",
    components: {
        NewCredit,
        SMCredit
    },
    methods: {
        refreshDetailsTable: function(event) {
            this.$refs.form.getAllCreditDetails();
        }
    }
};
</script>
