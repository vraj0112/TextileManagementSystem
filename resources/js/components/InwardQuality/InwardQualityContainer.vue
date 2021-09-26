<template>
    <div>
        <aside></aside>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <NewInwardQuality
                        @refreshInwardQualityTable = "refreshInwardQualityTable"
                    />
                    <SMInwardQuality ref="form"/>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import NewInwardQuality from './NewInwardQuality';
import SMInwardQuality from './SMInwardQuality';

export default {
    name: "InwardQualityContainer",
    components: {
        NewInwardQuality,
        SMInwardQuality
    },
    methods: {
        refreshInwardQualityTable: function(event){
            this.$refs.form.getAllInwardQualities();
        }
    }
}
</script>