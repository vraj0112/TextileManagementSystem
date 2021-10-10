<template>
    <div>
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">New Broker</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="brokerName" class="text-md col-form-label">Broker Name <span
                                        class="required-mark" style="color: red;">*</span></label>
                            </div>

                            <div class="col-md-3">
                                <input type="text" class="form-control" v-model="brokerName" maxlength="70"
                                    placeholder="Enter Broker Name...">
                            </div>

                            <div class="col-md-2 ml-auto">
                                <label for="contactNo" class="text-md col-form-label">Contact Number <span
                                        class="required-mark" style="color: red;">*</span></label>
                            </div>

                            <div class="col-md-3 mr-5">
                                <input type="text" class="form-control" v-model="contactNo" maxlength="10"
                                    placeholder="Enter Broker Contact No...">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" v-on:click="addBroker" class="btn btn-primary text-md">Add</button>
                        <button type="reset" v-on:click="resetFields"
                            class="btn btn-primary ml-3 text-md">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import toastr from 'toastr';
    import swal from 'sweetalert2';

    toastr.options = {
        closeButton: true,
        closeDuration: 200,
        progressBar: true,
        newestOnTop: true,
        positionClass: "toast-top-center",
    };

    export default {
        name: "NewBroker",
        data() {
            return {
                brokerName: '',
                contactNo: '',
                status: null,
                message: null,
                errors: null
            }
        },
        methods: {
            addBroker() {
                if (this.brokerName == '' || this.contactNo == '') {
                    toastr["error"]('All Fields are Required');
                } else {
                    let payload = {
                        brokerName: this.brokerName,
                        contactNo: this.contactNo
                    };
                    axios
                        .post("../api/broker/insert", payload)
                        .then(response => {
                            if (response.data.status == -1) {
                                var errormsg = response.data.errors;
                                try {
                                    if (errormsg.brokerName)
                                        toastr["error"](errormsg.brokerName);
                                } catch (err) { }

                                try {
                                    if (errormsg.contactNo)
                                        toastr["error"](errormsg.contactNo);
                                } catch (err) { }

                            } else if (response.data.status == 0) {
                                toastr["warning"](response.data.message);
                            } else if (response.data.status == 1) {
                                swal.fire({
                                    title: 'Success',
                                    html: "<h5 style='color:#9C9794'>Broker Details Added Successfully!</h5>",
                                    icon: 'success'
                                }).then((result) => {
                                    this.$emit("refreshBrokersTable");
                                    this.resetFields();
                                });
                            }
                        })
                        .catch(err => {
                            console.log(err.response.data.message);
                            toastr["error"]("Something went Wrong.");
                        })
                }

            },

            resetFields() {
                this.brokerName = '';
                this.contactNo = '';
            }
        }
    }
</script>