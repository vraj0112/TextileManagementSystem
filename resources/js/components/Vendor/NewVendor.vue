<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary mt-3">
          <div class="card-header">
            <h3 class="card-title">New Vendor</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div
              class="form-group"
              style="
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
              "
            >
              <label for="companyName" class="text-md col-md-2">Company Name <span class="required-mark" style="color: red;">*</span></label>
              <input
                type="text"
                class="form-control col-md-5"
                v-model="companyName"
                placeholder="Enter Company Name..."
              />
              <div class="col-md-1"></div>
              <label for="companyContact" class="text-md col-md-2"
                >Contact Number <span class="required-mark" style="color: red;">*</span></label
              >
              <input
                type="tel"
                class="form-control col-md-2"
                v-model="companyContact"
                placeholder="Enter Contact Number..."
              />
            </div>
            <div
              class="form-group"
              style="
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
              "
            >
              <label for="companyAddress" class="text-md col-md-2"
                >Company Address <span class="required-mark" style="color: red;">*</span></label
              >
              <textarea
                class="form-control col-md-10"
                v-model="companyAddress"
                placeholder="Enter Company Address..."
              ></textarea>
            </div>
            <div
              class="form-group"
              style="
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
              "
            >
              <label for="emailAddress" class="text-md col-md-2">Email Address</label>
              <input
                type="email"
                class="form-control col-md-2"
                v-model="emailAddress"
                placeholder="Enter Email Address..."
              />
              <div class="col-md-1"></div>
              <label for="gstNumber" class="text-md col-md-1">GST No.<span class="required-mark" style="color: red;">*</span></label>
              <input
                type="text"
                class="form-control col-md-2"
                v-model="gstNumber"
                placeholder="Enter GST Number..."
              />
              <div class="col-md-1"></div>
              <label for="gstCode" class="text-md col-md-1">GST Code<span class="required-mark" style="color: red;">*</span></label>
              <input
                type="text"
                class="form-control col-md-2"
                v-model="gstCode"
                placeholder="Enter GST Code..."
              />
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary" @click="addVendor">
              Add
            </button>
            <button
              type="reset"
              class="btn btn-primary"
              @click="resetNewVendor"
            >
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
  newsOnTop: true,
  positionClass: "toast-top-center",
};

export default {
  name: "NewVendor",
  data() {
    return {
      companyName: "",
      companyContact: "",
      companyAddress: "",
      emailAddress: "",
      gstNumber: "",
      gstCode: "",
    };
  },
  methods: {
    validateCompanyName: function () {
      if (this.companyName == "") {
        toastr.info("Please Enter Company Name!");
        return false;
      } else if (this.companyName.length > 50) {
        toastr.warning("Company Name must be less than 50 characters!");
        return false;
      } else {
        return true;
      }
    },

    validateCompanyContact: function () {
      if (this.companyContact == "") {
        toastr.info("Please Enter Contact Number!");
        return false;
      } else if (this.companyContact.length != 10) {
        toastr.warning("Contact Number must be equal to 10 characters!");
        return false;
      } else {
        return true;
      }
    },

    validateCompanyAddress: function () {
      if (this.companyAddress == "") {
        toastr.info("Please Enter Company Address!");
        return false;
      } else if (this.companyAddress.length > 255) {
        toastr.warning("Company Address must be less than 255 characters!");
        return false;
      } else {
        return true;
      }
    },

    validateEmailAddress: function () {
      if (this.emailAddress.length > 255) {
        toastr.warning("Email Address must be less than 255 characters!");
        return false;
      } 
      
      if (this.emailAddress != "") {
        if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.emailAddress))) 
        {
          toastr.warning("Please enter a valid email address!");
          return false;
        }
      }
      return true;
    },

    validateGSTNumber: function () {
      if (this.gstNumber == "") {
        toastr.info("Please Enter GST Number!");
        return false;
      } else if (this.gstNumber.length != 24) {
        toastr.warning("GST Number must be equal to 24 characters!");
        return false;
      } else {
        return true;
      }
    },

    validateGSTCode: function () {
      if (this.gstCode == "") {
        toastr.info("Please Enter GST Code!");
        return false;
      } else if (this.gstCode.length != 2) {
        toastr.warning("GST Code must be equal to 2 characters!");
        return false;
      } else if (this.gstCode != this.gstNumber.substring(0, 2)) {
        toastr.warning(
          "GST Code must be equal to first 2 characters of GST Number!"
        );
      } else {
        return true;
      }
    },

    addVendor: function () {
      if (
        this.validateCompanyName() &&
        this.validateCompanyContact() &&
        this.validateCompanyAddress() &&
        this.validateEmailAddress() &&
        this.validateGSTNumber() &&
        this.validateGSTCode()
      ) {
        let payload = {
          companyName: this.companyName,
          companyContact: this.companyContact,
          companyAddress: this.companyAddress,
          emailAddress: this.emailAddress,
          gstNumber: this.gstNumber,
          gstCode: this.gstCode,
        };
        axios
          .post("/api/vendor", payload)
          .then((res) => {
            if (res.data.status == 1) {
              swal
                .fire({
                  title: "Success",
                  html: "<h5 style='color:#9C9794'>Vendor Added Successfully</h5>",
                  icon: "success",
                })
                .then(() => {
                  this.resetNewVendor();
                  this.$emit("refreshVendorsTable");
                });
            } else if (res.data.status == 0) {
              toastr.info(res.data.message);
            } else {
              toastr.error(res.data.message);
            }
          })
          .catch((err) => {
            toastr.error(res.error.message);
            console.log(res.error.message);
          });
      }
    },

    resetNewVendor: function () {
      this.companyName = "";
      this.companyContact = "";
      this.companyAddress = "";
      this.emailAddress = "";
      this.gstNumber = "";
      this.gstCode = "";
    },
  },
};
</script>

