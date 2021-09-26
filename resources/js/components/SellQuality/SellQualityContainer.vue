<template>
    <div>
        <aside></aside>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <NewSellQuality
                        @refreshSellQualityTable = "refreshSellQualityTable"
                    />
                    <SMSellQuality ref="form"/>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import NewSellQuality from './NewSellQuality';
import SMSellQuality from './SMSellQuality';

export default {
    name: "SellQualityContainer",
    components: {
        NewSellQuality,
        SMSellQuality
    },
    methods: {
        refreshSellQualityTable: function(event){
            this.$refs.form.getAllSellQualities();
        }
    }
}
</script>