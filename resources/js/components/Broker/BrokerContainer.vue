<template>
    <div>
        <aside></aside>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <NewBroker
                        @refreshBrokersTable="refreshBrokersTable"
                    />
                    <SMBroker ref="form" />
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import NewBroker from "./NewBroker";
import SMBroker from "./SMBroker";

export default {
    name: "BrokerContainer",
    components: {
        NewBroker,
        SMBroker
    },
    methods: {
        refreshBrokersTable: function(event) {
            this.$refs.form.getAllBrokers();
        }
    }
};
</script>