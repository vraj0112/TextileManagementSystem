<template>
    <div>
        <aside></aside>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <NewBankDetails
                        @refreshBankDetailsTable="refreshBankDetailsTable"
                    />
                    <SMBankDetails ref="form" />
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import NewBankDetails from "./NewBankDetails";
import SMBankDetails from "./SMBankDetails";

export default {
    name: "BankDetailsContainer",
    components: {
        NewBankDetails,
        SMBankDetails
    },
    methods: {
        refreshBankDetailsTable: function(event) {
            this.$refs.form.getAllBankDetails();
        }
    }
};
</script>
