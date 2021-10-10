<template>
  <div>
    <div class="row">
      <div class="col-md-12 mt-3">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">
              Add Bank Details
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>

          <div class="card-body">
            <div class="form-group" style="display: flex; flex-direction: row">
              <label for="bankName" class="col-md-2 text-md">Bank Name <span class="required-mark"
                  style="color: red;">*</span></label>
              <input type="text" class="form-control col-md-3" maxlength="50" v-model="bankName" placeholder="Enter Bank Name..." />
              <div class="col-md-1"></div>
              <label for="branchName" class="col-md-2 text-md">Branch Name <span class="required-mark"
                  style="color: red;">*</span></label>
              <input type="text" class="form-control col-md-3" maxlength="50" v-model="branchName"
                placeholder="Enter Branch Name..." />
            </div>
            <div class="form-group" style="display: flex; flex-direction: row">
              <label for="ifscCode" class="col-md-2 text-md">IFSC Code <span class="required-mark"
                  style="color: red;">*</span></label>
              <input type="text" class="form-control col-md-3" maxlength="11" v-model="ifscCode"
                placeholder="Enter IFSC Code..." />
              <div class="col-md-1"></div>
              <label for="accNo" class="col-md-2 text-md">Account No. <span class="required-mark"
                  style="color: red;">*</span></label>
              <input type="text" class="form-control col-md-3" maxlength="18" v-model="accNo"
                placeholder="Enter Account No..." />
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" v-on:click="addBankDetails" class="btn btn-primary">
              Add
            </button>
            <button type="reset" v-on:click="resetFields" class="btn btn-primary">
              Reset
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import toastr from "toastr";
  import swal from "sweetalert2";

  toastr.options = {
    closeButton: true,
    closeDuration: 200,
    progressBar: true,
    newestOnTop: true,
    positionClass: "toast-top-center",
  };

  export default {
    name: "NewBankDetails",
    data() {
      return {
        bankName: "",
        branchName: "",
        ifscCode: "",
        accNo: "",
        status: null,
        message: null,
        errors: null,
      };
    },
    methods: {
      addBankDetails() {
        if (this.bankName == "" || this.branchName == "" || this.ifscCode == "" || this.accNo == "") {
          toastr["error"]("All Fields are Required");
        } else {
          let payload = {
            bankName: this.bankName,
            branchName: this.branchName,
            ifscCode: this.ifscCode,
            accNo: this.accNo,
          };
          axios
            .post("../api/bankdetails/insert", payload)
            .then((response) => {
              if (response.data.status == -1) {
                var errormsg = response.data.errors;
                try {
                  if (errormsg.bankName) toastr["error"]("Bank Name is invalid");
                } catch (err) { }

                try {
                  if (errormsg.branchName) toastr["error"]("Branch Name is invalid");
                } catch (err) { }

                try {
                  if (errormsg.ifscCode) toastr["error"]("IFSC Code is invalid");
                } catch (err) { }

                try {
                  if (errormsg.accNo) toastr["error"]("Account No is invalid");
                } catch (err) { }

              } else if (response.data.status == 0) {
                toastr["warning"](response.data.message);
              } else if (response.data.status == 1) {
                swal
                  .fire({
                    title: "Success",
                    html: "<h5 style='color:#9C9794'>Bank Details Added Successfully!</h5>",
                    icon: "success",
                  })
                  .then((result) => {
                    this.resetFields();
                    this.$emit("refreshBankDetailsTable");
                  });
              }
            })
            .catch((err) => {
              console.log(err.response.data.message);
              toastr["error"]("Something went Wrong.");
            });
        }
      },

      resetFields() {
        this.bankName = "";
        this.branchName = "";
        this.ifscCode = "";
        this.accNo = "";
      },
    },
  };
</script>