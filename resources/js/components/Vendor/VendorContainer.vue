<template>
  <div>
    <aside></aside>
    <div class="content-wrapper">
      <section class="content">
        <div class="container-fluid">
          <NewVendor @refreshVendorsTable="refreshVendorsTable" />
          <SMVendor
            ref="form"
          />
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import NewVendor from "./NewVendor";
import SMVendor from "./SMVendor";

export default {
  name: "VendorContainer",
  components: {
    NewVendor,
    SMVendor,
  },
  methods: {
    refreshVendorsTable: function (event) {
      this.$refs.form.getAllVendors();
    },
  },
};
</script>
