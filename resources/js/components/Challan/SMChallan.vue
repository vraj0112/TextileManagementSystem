<template>
    <div>
        <aside></aside>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        S&M Challan
                                    </h3>
                                    <div class="card-tools">
                                        <button
                                            type="button"
                                            class="btn btn-tool"
                                            data-card-widget="collapse"
                                        >
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="card-body table-responsive">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <label for="from-date" class="text-md">From Date</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" class="form-control" id="from-date" v-model="fromDate"/>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="to-date" class="text-md">To Date</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" class="form-control" id="to-date" v-model="toDate"/>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="company-name" class="text-md">Company Name</label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="compniesForFilter" v-model="selectedCompanyForFilter" placeholder="Select Company">
                                            </model-select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-1">
                                            <label for="" class="text-md">Category</label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="categoriesForFilter"
                                                v-model="selectedCategoryForFilter"
                                                placeholder="Select Category">
                                            </model-select>
                                        </div>

                                        <div class="col-md-1">
                                            <label for="" class="text-md">Quality</label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select
                                                :options="qualitiesForFilter"
                                                v-model="selectedQualityForFilter"
                                                placeholder="Select Quality"
                                            >
                                            </model-select>
                                        </div>

                                        <div class="col-md-1">
                                            <label for="" class="text-md">Broker</label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select
                                                :options="brokersForFilter"
                                                v-model="selectedBrokerForFilter"
                                                placeholder="Select Broker"
                                            >
                                            </model-select>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-1">
                                            <label for="" class="text-md">Per Page</label>
                                        </div>
                                        <div class="col-md-3">
                                            <select v-model="paginate" class="form-control">
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                            </select>
                                        </div>
                                    <!-- <div class="col-md-5"></div>
                                        <div class="col-md-3 form-group">
                                            <input v-model="search" type="search" class="form-control "
                                                placeholder="Search By ..." />
                                        </div> -->
                                    </div>

                                    <div class="p-0 mt-3">
                                        <table class="table table-hover table-bordered table-striped table-sm">
                                            <thead class="text-md">
                                                <tr>
                                                    <th width="12%">
                                                        <a href="#" @click.prevent="updateSorting('challan_date')">Date</a>
                                                        <span v-if="sort_field == 'challan_date'?1:0">
                                                            <span v-if=" sort_direction == 'asc' ? 1 : 0 " >&uarr;</span>
                                                            <span v-if=" sort_direction == 'desc' ? 1 : 0 " >&darr;</span>
                                                        </span>
                                                    </th>
                                                    <th>
                                                        <a href="#"  @click.prevent="updateSorting('challan_no')">Challan No</a>
                                                        <span v-if=" sort_field == 'challan_no'?1:0">
                                                            <span v-if=" sort_direction == 'asc'? 1: 0">&uarr;</span>
                                                            <span v-if="sort_direction =='desc'? 1: 0">&darr;</span>
                                                        </span>
                                                    </th>
                                                    <th>Company</th>
                                                    <th>Broker</th>
                                                    <th>Quality</th>
                                                    <th>Category</th>
                                                    <th>Qty</th>
                                                    <th width="18%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-md">
                                                <tr
                                                    v-for="challan in challans.data"
                                                    v-bind:key="
                                                        challan.challan_id
                                                    "
                                                >
                                                    <td> {{ challan.challan_date }}</td>
                                                    <td>{{ challan.challan_no }}</td>
                                                    <td>{{ challan.customer_company_name }}</td>
                                                    <td>{{ challan.broker_name }}</td>
                                                    <td>{{ challan.quality_name }}</td>
                                                    <td>{{ challan.sell_category_name }}</td>
                                                    <td class="text-right">{{ challan.totalqty }}</td>
                                                    <td class="text-center">
                                                        <a :href="'challan/pdf/'+challan.challan_mst_id" target="_blank" class="btn btn-danger btn-sm text-md" ><i class="fas fa-file-pdf" style="font-size: 20px;"></i></a>
                                                        <button class="btn btn-info btn-sm text-md" @click="viewChallan(challan.challan_mst_id, challan.challan_no)"><i class="fas fa-eye"></i></button>
                                                        <button class="btn btn-primary btn-sm text-md" @click="editChallan(challan.challan_mst_id)"><i class="fas fa-pen"></i></button>
                                                        <button class="btn btn-danger btn-sm text-md" @click="confirmChallandeletation(challan.challan_mst_id, challan.challan_no )"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-sm-6 offset-5">
                                            <pagination
                                                :data="challans"
                                                @pagination-change-page="
                                                    getChallans
                                                "
                                            ></pagination>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- <div class="col-md-5"></div> -->
                                        <div class="col-md-9 text-right">
                                            <label for="" class="mt-2 text-md">
                                                Total Amount of this page :
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <input
                                                type="text"
                                                class="form-control text-right"
                                                v-model="totalAmountOfPage"
                                                disabled
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="challanIdToBeEdit != -1 ? 1:0" class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Edit Challan</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                        <button type="button" class="btn btn-tool" @click="cancelEdit" ><i class="fas fa-times"></i></button>        
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="challanDate" class="text-md col-form-label">Date <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" id="challanDate" v-model="challanDate"
                                                class="form-control text-md">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="challanNo" class="text-md col-form-label">Challan Number <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="text-md form-control" v-model="challanNo">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="companyName" class="text-md col-form-label">Company Name <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="companyNames" v-model="selectedCompanyName"
                                                @blur="getFromSelectedCompany" placeholder="Select a Company Name">
                                            </model-select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="brokerName" class="text-md col-form-label">Broker Name <span
                                                    class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="brokerNames" v-model="selectedBrokerName"
                                                placeholder="Select a Broker Name">
                                            </model-select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="companyContactNo" class="text-md col-form-label">Company Contact
                                                No. <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="text-md form-control" v-model="companyContactNo"
                                                disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="companyGSTNo" class="text-md col-form-label">Company GST No.
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="text-md form-control" v-model="companyGSTNo"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="productCategory" class="text-md col-form-label">Product Category
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="productCategories" v-model="selectedProductCategory"
                                                @blur="resetQualities" placeholder="Select a Product Category">
                                            </model-select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="productQuality" class="text-md col-form-label">Product Quality
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <model-select :options="productQualities" v-model="selectedProductQuality"
                                                placeholder="Select a Product Quality">
                                            </model-select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="Unit" class="text-md col-form-label">Unit
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="text-md form-control" v-model="unit" disabled>
                                        </div>
                                    </div>

                                    
                                            <div class="row overflow-auto mt-5" style="max-height: 300px; min-height: 300px;">
                                                <div class="col-md-6 table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead class="table-secondary text-md text-dark">
                                                            <th >Sr. No.</th>
                                                            <th >No</th>
                                                            <th >Qty</th>
                                                            <th width="20%"></th>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(data, index) in allData" :key="index">
                                                                <td v-if="index % 2 ? 0 : 1">{{index + 1}}</td>
                                                                <td v-if="index % 2 ? 0 : 1">
                                                                    <input type="number" class="form-control text-right"
                                                                        :disabled="data.isDisabled"
                                                                        v-model="data.no" 
                                                                        :ref="'takano'+index">
                                                                </td>
                                                                <td v-if="index % 2 ? 0 : 1">
                                                                    <input type="number" class="form-control text-right"
                                                                        v-model="data.qty" 
                                                                        @blur="sumTotalQuantity"
                                                                        @click="selectQuantity(index)"
                                                                        @keydown.tab.prevent="tranferCursor(index)"
                                                                        :disabled="data.isDisabled"
                                                                        :ref="'qty'+index">
                                                                </td>
                                                                <td v-if="index % 2 ? 0 : 1" class="text-center">
                                                                    <button class="btn btn-success text-md" @click="data.isDisabled == true ? editChallanDetailsEntry(index, data.challanDetailsId, data.no, data.qty):cancelEditChallanDetailsEntry(index)"><i
                                                                        class="fas" :class="data.isDisabled == true?'fa-pen':'fa-times'"></i></button>
                                                                    <button class="btn btn-danger text-md" @click="deleteChallanDetailsId(index, data.challanDetailsId)"><i
                                                                        class="fas fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="col-md-6 table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead class="table-secondary text-md text-dark">
                                                            <th >Sr. No.</th>
                                                            <th >No</th>
                                                            <th >Qty</th>
                                                            <th width="20%"></th>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(data, index) in allData" :key="index">
                                                                <td v-if="index % 2 ? 1 : 0">
                                                                    {{index + 1}}
                                                                </td>
                                                                <td v-if="index % 2 ? 1 : 0">
                                                                    <input type="number" class="form-control text-right"
                                                                        v-model="data.no" 
                                                                        :disabled="data.isDisabled"
                                                                        :ref="'takano'+index">
                                                                </td>
                                                                <td v-if="index % 2 ? 1 : 0">
                                                                    <input type="number" class="form-control text-right"
                                                                        v-model="data.qty" 
                                                                        @blur="sumTotalQuantity"
                                                                        @click="selectQuantity(index)"
                                                                        @keydown.tab.prevent="tranferCursor(index)"
                                                                        :disabled="data.isDisabled"
                                                                        :ref="'qty'+index">
                                                                </td>
                                                                <td v-if="index % 2? 1 : 0" class="text-center">
                                                                    <button class="btn btn-success text-md" @click="data.isDisabled == true? editChallanDetailsEntry(index, data.challanDetailsId, data.no, data.qty):cancelEditChallanDetailsEntry(index)"><i
                                                                        class="fas" :class="data.isDisabled == true?'fa-pen':'fa-times'"></i></button>
                                                                    <button class="btn btn-danger text-md" @click="deleteChallanDetailsId(index, data.challanDetailsId)"><i
                                                                        class="fas fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="totalQty" class="text-md col-form-label mt-3">Total Quantity
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="text-md form-control mt-3 text-right"
                                                v-model="totalQty" disabled>
                                        </div>
                                    </div>

                                    

                                            <h4 class="mt-5">Add New Details</h4>
                                            <div class="row overflow-auto mt-5  " style="max-height: 300px; min-height: 300px;" :style="isNewProductDetailsFull?'border: 2px solid red':''">
                                                <div class="col-md-6 table-responsive border-danger" >
                                                    <table class="table table-bordered">
                                                        <thead class="table-secondary text-md text-dark">
                                                            <th >Sr. No.</th>
                                                            <th >No</th>
                                                            <th >Qty</th>
                                                            <th width="20%"></th>
                                                        </thead>
                                                        <tbody class="text-md">
                                                            <tr v-for="(data, index) in newProductDetails" :key="index">
                                                                <td v-if="index % 2 ? 0 : 1">{{allData.length + index + 1}}</td>
                                                                <td v-if="index % 2 ? 0 : 1">
                                                                    <input type="number" class="form-control text-right"
                                                                        v-model="data.no" :ref="'newtakano'+index">
                                                                </td>
                                                                <td v-if="index % 2 ? 0 : 1">
                                                                    <input type="number" class="form-control text-right"
                                                                        v-model="data.qty"
                                                                        @click="selectQuantity(index, 'newqty')"
                                                                        @blur="sumNewTotalQuantity"
                                                                        @keydown.tab.prevent="tranferCursor(index, 'newtakano')"
                                                                        @keyup.enter="enterPressed(index)" :ref="'newqty'+index">
                                                                </td>
                                                                <td v-if="index % 2 ? 0 : 1" class="text-center">
                                                                    <button class="btn btn-danger text-md" @click="deleteNewChallanDetailsId(index)"><i
                                                                        class="fas fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="col-md-6 table-responsive">
                                                    <table class="table table-bordered text-md">
                                                        <thead class="table-secondary text-md text-dark">
                                                            <th >Sr. No.</th>
                                                            <th >No</th>
                                                            <th >Qty</th>
                                                            <th width="20%"></th>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(data, index) in newProductDetails" :key="index">
                                                                <td v-if="index % 2 ? 1 : 0">
                                                                    {{allData.length + index + 1}}
                                                                </td>
                                                                <td v-if="index % 2 ? 1 : 0">
                                                                    <input type="number" class="form-control text-right"
                                                                        :disabled="data.isDisabled"
                                                                        v-model="data.no" :ref="'newtakano'+index">
                                                                </td>
                                                                <td v-if="index % 2 ? 1 : 0">
                                                                    <input type="number" class="form-control text-right"
                                                                        v-model="data.qty" 
                                                                        @blur="sumNewTotalQuantity"
                                                                        :disabled="data.isDisabled"
                                                                        @click="selectQuantity(index, 'newqty')"
                                                                        @keydown.tab.prevent="tranferCursor(index, 'newtakano')"
                                                                        @keyup.enter="enterPressed(index)" :ref="'newqty'+index">
                                                                </td>
                                                                <td v-if="index % 2? 1 : 0" class="text-center">
                                                                    <button class="btn btn-danger text-md" @click="deleteNewChallanDetailsId(index)"><i
                                                                        class="fas fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                    
                                            <button class="btn btn-primary text-md mt-3" @click="addRow">Add Product</button>

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="totalQty" class="text-md col-form-label mt-3">Total Quantity(New)
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="text-md form-control mt-3 text-right"
                                                v-model="totalNewQty" disabled>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="text-md col-form-label mt-3">Total Quantity(Net)
                                                <span class="required-mark" style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="text-md form-control mt-3 text-right"
                                                v-model="netTotalQty" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button v-on:click="updateChallan"
                                        class="btn btn-primary text-md">Update</button>
                                </div>
                            </div>

                            <div v-if="challanToView.challanIdToBeViewd != -1 ? 1:0" class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Viewing Challan No: {{challanToView.challanNoToView}}</h3>
                                    <span class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                        <button type="button" class="btn btn-tool" @click="closeView" ><i class="fas fa-times"></i></button>        
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <label class="col-md-2 mt-2 text-md">
                                            Challan Date
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" v-model="challanToView.challanDate" disabled>
                                        </div>
                                        <label class="col-md-2 mt-2 text-md">
                                            Challan No
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" v-model="challanToView.challanNo" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-md-2 mt-2 text-md">
                                            Company
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" v-model="challanToView.company" disabled>
                                        </div>
                                        <label class="col-md-2 mt-2 text-md">
                                            Broker
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" v-model="challanToView.broker" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-md-2 mt-2 text-md">
                                            Quality
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" v-model="challanToView.quality" disabled>
                                        </div>
                                        <label class="col-md-2 mt-2 text-md">
                                            Category
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" v-model="challanToView.category" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-md-2 mt-2 text-md">
                                            Unit
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" v-model="challanToView.unit" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <table class="table table-hover table-bordered table-striped table-sm">
                                                <thead class="text-md">
                                                    <th width="15%">Sr. No.</th>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Qty</th>
                                                </thead>
                                                <tbody class="text-md">
                                                    <tr v-for="(product, index) in challanToView.products.productPart1" v-bind:key="index">
                                                        <td class="text-right">{{index+1}}</td>
                                                        <td class="text-right">{{product.no}}</td>
                                                        <td class="text-right">{{product.qty}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-4">
                                            <table class="table table-hover table-bordered table-striped table-sm">
                                                <thead class="text-md">
                                                    <th width="15%">Sr. No.</th>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Qty</th>
                                                </thead>
                                                <tbody class="text-md">
                                                    <tr v-for="(product, index) in challanToView.products.productPart2" v-bind:key="index">
                                                        <td class="text-right">{{index+17}}</td>
                                                        <td class="text-right">{{product.no}}</td>
                                                        <td class="text-right">{{product.qty}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-4">
                                            <table class="table table-hover table-bordered table-striped table-sm">
                                                <thead class="text-md">
                                                    <th width="15%">Sr. No.</th>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Qty</th>
                                                </thead>
                                                <tbody class="text-md">
                                                    <tr v-for="(product, index) in challanToView.products.productPart3" v-bind:key="index">
                                                        <td class="text-right">{{index+33}}</td>
                                                        <td class="text-right">{{product.no}}</td>
                                                        <td class="text-right">{{product.qty}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-2 mt-2 text-right text-md">
                                            <label class="text-md">Total</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="text-right form-control" v-model="challanToView.totalQty.totalQtyProductPart1" disabled="disabled">
                                        </div>
                                        <div class="col-md-2 mt-2 text-right text-md">
                                            <label class="text-md">Total</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="text-right form-control" v-model="challanToView.totalQty.totalQtyProductPart2" disabled="disabled">
                                        </div>
                                        <div class="col-md-2 mt-2 text-right text-md">
                                            <label class="text-md">Total</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="text-right form-control" v-model="challanToView.totalQty.totalQtyProductPart3" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-10 text-right text-md">
                                            <label for="" class="text-md">Net Total Qty</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class='form-control text-right' v-model="challanToView.totalQty.totalQty"  disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import toastr from "toastr";
import swal from "sweetalert2";
import { ModelSelect } from "vue-search-select";

export default {
    name: "SMChallan",
    data() {
        return {
            compniesForFilter: [],
            selectedCompanyForFilter: "",

            categoriesForFilter: [],
            selectedCategoryForFilter: "",

            qualitiesForFilter: [],
            selectedQualityForFilter: "",

            brokersForFilter: [],
            selectedBrokerForFilter: "",

            paginate: "10",

            challans: {},

            days: 10,
            search: "",
            sort_direction: "desc",
            sort_field: "challan_date",
            fromDate: this.getTodaysDate(),
            toDate: this.getDateBeforeDays(),

            totalAmountOfPage: (0).toFixed(2),

            challanIdToBeEdit: -1,

            challanDate: "",
            oldChallanDate: "",
            challanNo: "",
            oldChallanNo: '',

            companyNames: [],
            selectedCompanyName: "",

            brokerNames: [],
            selectedBrokerName: "",

            companyContactNo: '',
            companyGSTNo: "",

            productCategories: [],
            selectedProductCategory: '',

            productQualities: [],
            selectedProductQuality: '',

            unit: '',

            allData: [],

            totalQty: (0).toFixed(2),
            totalNewQty: (0).toFixed(2),
            netTotalQty: (0).toFixed(2),

            editedChallanDetailsIds: new Set(),
            challanDetailsIdToBeDeleted: new Set(),

            newProductDetails: [],
            isNewProductDetailsFull: false,

            challanToView : {
                challanIdToBeViewd: -1,
                challanNoToView: -1,
                challanDate: '',
                challanNo: '',
                company: "",
                broker: "",
                quality: "",
                category: "",
                unit: "",
                products: {
                    productPart1:[],
                    productPart2:[],
                    productPart3:[]
                },
                totalQty: {
                    totalQtyProductPart1: (0).toFixed(2),
                    totalQtyProductPart2: (0).toFixed(2),
                    totalQtyProductPart3: (0).toFixed(2),
                    totalQty: (0).toFixed(2)
                }
            }
        };
    },
    mounted() {
        this.fromDate = this.getDateBeforeDays(),
        this.toDate = this.getTodaysDate();
        this.getChallans();
        this.loadQualityCategoriesForFilter();
        this.loadQualitiesOfCategoryForFilter();
        this.getCustomersList();
        this.getBrokersList();
        this.editedChallanDetailsIds = new Set();
        this.challanDetailsIdToBeDeleted = new Set();
        this.sumNewTotalQuantity();
        this.sumTotalQuantity();
        this.sumNetTotalQty();
    },
    watch: {
        selectedCategoryForFilter: function() {
            this.loadQualitiesOfCategoryForFilter();
            this.getChallans();
        },

        fromDate: function() {
            this.getChallans();
        },

        toDate: function() {
            this.getChallans();
        },

        selectedCompanyForFilter: function() {
            this.getChallans();
        },

        selectedQualityForFilter: function() {
            this.getChallans();
        },

        selectedBrokerForFilter: function() {
            this.getChallans();
        },

        selectedProductCategory: function() {
            this.loadFromSelectedCategory();
        },

        newProductDetails: function() {
            this.sumNewTotalQuantity();
            if(this.newProductDetails.length + this.allData.length < 48){
                this.isNewProductDetailsFull = false;
            }
            else{
                this.isNewProductDetailsFull = true;
            }
        },

        allData: function() {
            if(this.newProductDetails.length + this.allData.length < 48){
                this.isNewProductDetailsFull = false;
            }
            else{
                this.isNewProductDetailsFull = true;
            }
        },

        totalQty: function() {
            this.sumNetTotalQty();
        },

        totalNewQty: function() {
            this.sumNetTotalQty();
        }

    },
    methods: {
        getChallans: function(page = 1) {
            axios
                .get(
                    "/api/challans?page=" +
                        page +
                        "&company=" +
                        this.selectedCompanyForFilter +
                        "&category=" +
                        this.selectedCategoryForFilter +
                        "&quality=" +
                        this.selectedQualityForFilter +
                        "&broker=" +
                        this.selectedBrokerForFilter +
                        "&search=" +
                        this.search +
                        "&sortfield=" +
                        this.sort_field +
                        "&sortdirection=" +
                        this.sort_direction +
                        "&fromdate=" +
                        this.fromDate +
                        "&todate=" +
                        this.toDate
                )
                .then(result => {
                    this.challans = result.data;
                    let totalAmountOfPage = 0;
                    for (let i = 0; i < this.challans.data.length; i++) {
                        this.challans.data[i]["totalqty"] = this.challans.data[i]["totalqty"].toFixed(2);
                        totalAmountOfPage += parseFloat(
                            this.challans.data[i]["totalqty"]
                        );
                    }
                    this.totalAmountOfPage = totalAmountOfPage.toFixed(2);
                })
                .catch(err => {
                    console.log("Err in Fetching Challans");
                    toastr.error("Something Went Wrong, Please Refrash");
                });
        },

        getTodaysDate: function() {
            let d = new Date();
            let month = "" + (d.getMonth() + 1);
            let day = "" + d.getDate();
            let year = d.getFullYear();
            if (month < 10) {
                month = "0" + month;
            }

            if (day < 10) {
                day = "0" + day;
            }

            return year + "-" + month + "-" + day;
        },

        getDateBeforeDays: function() {
            let date = new Date();
            let last = new Date(
                date.getTime() - this.days * 24 * 60 * 60 * 1000
            );
            let day = "" + last.getDate();
            let month = "" + (last.getMonth() + 1);
            let year = "" + last.getFullYear();

            if (day < 10) {
                day = "0" + day;
            }

            if (month < 10) {
                month = "0" + month;
            }
            return year + "-" + month + "-" + day;
        },

        getStdDate: function(date){
            date = date.split("-");
            return (date[2]+"-"+date[1]+"-"+date[0]);
        },

        getCustomersList: function() {
            axios
                .get("/api/customerlist")
                .then(result => {
                    let allEntry = [{ value: "", text: "All" }];
                    let individualEntry = result.data.map(company => {
                        return {
                            text: company.customer_company_name,
                            value: company.customer_id
                        };
                    });

                    this.compniesForFilter = allEntry.concat(individualEntry);
                })
                .catch(err => {
                    console.log("Err In Fetching Customers List For Filter");
                    toastr.error(
                        "Something Went Wrong!, Please Refresh The Page"
                    );
                });
        },

        loadQualityCategoriesForFilter: function() {
            axios
                .get("/api/sellqualitycategories")
                .then(response => {
                    let allEntry = [{ text: "All", value: "" }];
                    let individualEntry = response.data.qualityCategories.map(
                        category => {
                            return {
                                value: category.qualityCategoryId,
                                text: category.qualityCategoryName
                            };
                        }
                    );
                    this.categoriesForFilter = allEntry.concat(individualEntry);
                })
                .catch(err => {
                    console.log("Err in Fetching Sell Quality Categories");
                    console.log(err);
                    toastr["error"](
                        "Something went Wrong! Please refresh The Page"
                    );
                });
        },

        loadQualitiesOfCategoryForFilter: function() {
            if (this.selectedCategoryForFilter == "") {
                let allEntry = [{ text: "All", value: "" }];
                this.qualitiesForFilter = allEntry;
                this.selectedQualityForFilter = "";
                return;
            }

            axios
                .get(
                    "/api/sellqualityofcategory/" +
                        this.selectedCategoryForFilter
                )
                .then(response => {
                    let allEntry = [{ text: "All", value: "" }];
                    let individualEntry = response.data.map(quality => {
                        return {
                            text: quality.quality_name,
                            value: quality.sell_quality_id
                        };
                    });

                    this.qualitiesForFilter = allEntry.concat(individualEntry);
                    this.selectedQualityForFilter = "";
                })
                .catch(err => {
                    console.log(
                        "Err in Fetching Sell Quality Of Selected Categories"
                    );
                    toastr["error"](
                        "Something went Wrong! Please refresh The Page"
                    );
                });
        },

        resetQualities: function(){
            this.selectedProductQuality = "";
        },

        getBrokersList: function() {
            axios
                .get("/api/brokerslist")
                .then(response => {
                    let allEntry = [{ value: "", text: "All" }];
                    let individualEntry = response.data.map(broker => {
                        return {
                            text: broker.broker_name,
                            value: broker.broker_id
                        };
                    });

                    this.brokersForFilter = allEntry.concat(individualEntry);
                })
                .catch(err => {});
        },

        closeUpdateExpenseBtn: function() {
            this.expenseId = -1;
            this.resetUpdateExpenseForm();
        },

        updateSorting: function(field) {
            if (this.sort_field == field) {
                this.sort_direction = this.sort_direction == "asc" ? "desc" : "asc";
            } else {
                this.sort_field = field;
            }
            this.getChallans(this.challans.current_page);
        },

        editChallan: async function(challan_id) {

            this.resetChallanEditing();
            axios
                .get("/api/challan/"+challan_id)
                .then((response)=>{
                    let challan = response.data;
                    this.challanDate = this.getStdDate(challan.challandate);  
                    this.oldChallanDate = this.challanDate; 
                    this.challanNo = challan.challanno;
                    this.oldChallanNo = challan.challanno;

                    this.loadCompanyName();
                    this.selectedCompanyName = challan.customer.customer_id;
                    this.getFromSelectedCompany();

                    this.loadBrokerName();
                    this.selectedBrokerName = challan.broker.broker_id;

                    this.loadQualityCategories();
                    this.selectedProductCategory = challan.quality.category.sell_quality_category_id;

                    this.loadFromSelectedCategory();
                    this.selectedProductQuality = challan.quality.sell_quality_id;

                    let challanDetails = challan.challandetails;

                    for(let i=0; i<challanDetails.length; i++){
                        this.allData.push({
                            no: challanDetails[i].no,
                            qty: challanDetails[i].qty,
                            challanDetailsId: challanDetails[i].challan_details_id,
                            isDisabled: true
                        });
                    }

                    this.sumTotalQuantity();

                    this.challanIdToBeEdit = challan_id;
                })
                .catch((err)=>{
                    console.log(err);
                    console.log("Err In Getting Challan Data For Updating");
                    toastr.error("Something Went Wrong");
                })


            
            
            
        },

        editChallanDetailsEntry: function(index, challanDetailsId, no, qty) {
            this.allData[index].isDisabled = false; 
            this.editedChallanDetailsIds.add(challanDetailsId);         
        },

        disableField: function(index){
            this.allData[index].isDisabled = true;
        },

        cancelEditChallanDetailsEntry: function(index){
            this.allData[index].isDisabled = true;
        },

        getFromSelectedCompany: function () {
            if (this.selectedCompanyName == '' || typeof (this.selectedCompanyName) === 'undefined') {
                this.companyContactNo = '';
                this.companyGSTNo = '';
                return;
            }

            axios.get('../api/selectedcustomerdata/' + this.selectedCompanyName).then(response => {
                this.companyContactNo = response.data.customer_contact_no;
                this.companyGSTNo = response.data.customer_gst_no;
            }).catch(err => {
                console.log(err);
                toastr["error"]("Something went Wrong.")
            })
        },

        loadFromSelectedCategory: function () {
            if (this.selectedProductCategory == '' || typeof (this.selectedProductCategory) === 'undefined') {
                this.unit = '';
                this.productQualities = [];
                this.selectedProductQuality = '';
                return;
            }

            axios.get('../api/sellqualityofcategory/' + this.selectedProductCategory).then(response => {
                this.productQualities = response.data.map(quality => {
                    return {
                        value: quality.sell_quality_id,
                        text: quality.quality_name
                    }
                })

                if (this.selectedProductCategory == '1') {
                    this.unit = "Meters";
                } else if (this.selectedProductCategory == '2' || this.selectedProductCategory == '3') {
                    this.unit = "Kg.";
                }
            }).catch(err => {
                console.log(err);
                toastr["error"]("Something went Wrong.")
            })
        },

        addRow: function () {
            if (this.allData.length < 48) {
                if((this.allData.length + this.newProductDetails.length) < 48){
                    this.newProductDetails.push({
                        no: "",
                        qty: (0).toFixed(2)
                    });
                }
                // else{
                //     toastr.warning("Limit Exeeds, Only 48 Entries Are Allowed");
                // }

            } 
            // else {
            //     toastr["warning"]("Already 48 Entries Are There");
            // }
        },

        enterPressed: function (index = -1) {
            if (this.newProductDetails.length == (index + 1)) {
                this.addRow();
            }
        },

        deleteChallanDetailsId: function (index, challanDetailsId) {
            this.challanDetailsIdToBeDeleted.add(challanDetailsId);
            this.allData.splice(index, 1);
            this.sumTotalQuantity();
        },

        tranferCursor: function (index, prefix = 'takano') {
            
            if (prefix === "takano" && this.allData.length == (index + 1)) {
                return;
            }
            
            if(prefix === "newtakano" && this.newProductDetails.length == (index + 1)) {
                return;

            }
            this.$refs[prefix + (index + 1)][0].focus();
        },

        loadCompanyName() {
            axios.get('../api/customerlist').then((response) => {
                this.companyNames = response.data.map(company => {
                    return {
                        value: company.customer_id,
                        text: company.customer_company_name + ' - ' + company.customer_contact_no
                    }
                });
            }).catch(err => {
                console.log(err);
                toastr["error"]("Something went Wrong.")
            })
        },

        loadBrokerName() {
            axios.get('../api/brokerslist').then((response) => {
                this.brokerNames = response.data.map(broker => {
                    return {
                        value: broker.broker_id,
                        text: broker.broker_name + ' - ' + broker.broker_contact_no
                    }
                });
            }).catch(err => {
                console.log(err);
                toastr["error"]("Something went Wrong.")
            })
        },

        loadQualityCategories() {
            axios.get('../api/sellqualitycategories').then((response) => {
                this.productCategories = response.data.qualityCategories.map(category => {
                    return {
                        value: category.qualityCategoryId,
                        text: category.qualityCategoryName
                    }
                });
            }).catch(err => {
                console.log(err);
                toastr["error"]("Something went Wrong");
            })
        },

        validateChallanDate: function(){
            if(this.challanDate == ""){
                toastr.info("Challan Date Is Required");
                return false;
            }

            return true;
        },

        validateChallanNo: function(){
            if(this.challanNo == ''){
                toastr.info("Challan No Is Required");
                return false;
            }
            return true;
        },

        validateCompany: function(){
            if(this.selectedCompanyName == ""){
                toastr.info("Company Name Is Required");
                return false;
            }
            return true;
        },

        validateBroker: function(){
            if(this.selectedBrokerName == ""){
                toastr.info("Broker Name Is Required");
                return false;
            }
            return true;
        },

        validateCategory: function(){
            if(typeof this.selectedProductCategory === 'undefined' || this.selectedProductCategory===''){
                toastr.info("Product Category Is Required");
                return false;
            }
            return true;
        },

        validateQuality: function(){
            if(typeof this.selectedProductQuality === 'undefined' || this.selectedProductQuality === ''){
                toastr.info("Product Quality Is Required");
                return false;
            }
            return true;
        },

        updateChallan: function() {

            if((this.allData.length + this.newProductDetails.length) < 1 ) {
                toastr["info"]("Min 1 Entry is required");
                return;
            }

            if(this.challanMstId == -1 || !this.validateChallanDate() || !this.validateChallanNo() || !this.validateCompany() || !this.validateBroker() || !this.validateCategory() || !this.validateQuality()){
                return;
            }
            
            let payload = {
                challanMstId: this.challanIdToBeEdit,
                challandate: this.challanDate,
                oldChallanDate: this.challanDate,
                challanNo: this.challanNo,
                oldChallanNo: this.oldChallanNo,
                company: this.selectedCompanyName,
                broker: this.selectedBrokerName,
                category: this.selectedProductCategory,
                quality: this.selectedProductQuality,
                unit: this.unit,
                challanDetails: this.allData,
                newProductDetails: this.newProductDetails,
                challanDetailsIdsToBeDeleted: Array.from(this.challanDetailsIdToBeDeleted)
            }
            

            axios
                .put("/api/challan", payload)
                .then((response) => {
                    if(response.data.status == 1){
                        swal
                            .fire({
                                    title: "Success",
                                    html:  "<h5 style='color:#9C9794'>Challan Updated Successfully</h5>",
                                    icon: "success",
                                    allowOutsideClick: false

                            })
                            .then(() => {
                                this.getChallans();
                                this.resetChallanEditing();
                            });
                    }
                    else if(response.data.status == -1){

                        if(response.data.statuscode == 1){
                            let errorString = "";
                            let errors = response.data.errors;

                            for(let field in errors){
                                for(let i=0; i<errors[field].length; i++){
                                    errorString += "<li>"+errors[field][i]+"</li>";
                                }
                            };

                            toastr.error(errorString, response.data.message , {timeOut: 20000, "closeButton": true})
                        }
                        else if(response.data.statuscode == 2){
                            let errMsg = "";
                            let notValidInEditing = response.data.notValidInEditing;  
                            let notValidInNew = response.data.notValidInNew; 

                            notValidInEditing.forEach((srno) => {
                                errMsg += `${1 + srno}, `;
                            });

                            notValidInNew.forEach((srno) => {                  
                                errMsg += `${this.allData.length + srno + 1}, `;
                            });

                            swal
                                .fire({
                                    title: "Error",
                                    html:  "<h5 style='color:#9C9794'>Following Sr No. Fields Are Empty</h5><br><p style='color:#9C9794'>"+errMsg+"</p>",
                                    icon: "error"
                                })
                                .then(() => {
                                    
                                });
                        }
                        else if(response.data.statuscode == 3){
                            toastr.error("Challan No Alredy Exists");
                        }
                        else if(response.data.statuscode == 4){
                            let noExists = response.data.noExists;
                            let noError = response.data.noError;

                            if(noError.length > 0){
                                console.log("One Or More Challan Detials Entries Updation Failed");
                                toastr.error("Something Went Wrong")
                            }
                            else if(noExists.length > 0){
                                let errMsg = "";
                                noExists.forEach((no)=>{
                                    errMsg += `${no}, `;
                                });

                                swal
                                    .fire({
                                        title: "Error",
                                        html:  "<h5 style='color:#9C9794'>Following Taka No/Beam No Already Exists</h5><br><p style='color:#9C9794'>"+errMsg+"</p>",
                                        icon: "error",
                                        allowOutsideClick: false
                                    })

                            }
                            else{
                                console.log("Err In Update Challan With Status Code: ", response.data.statuscode);
                                toastr.error("Something Went Wrong");
                            }

                        }
                        else if(response.data.statuscode == 5){
                            console.log("Exception Generated On Server Side");
                            toastr.error("Something Went Wrong");
                        }
                    }
                    else{
                        console.log("Other Then Expected Error Found In Challan Updation");
                        toast.error("Something Went Wrong");
                    }
                })
                .catch((error) => {
                    console.log("Err In Challan Updation API Calls");
                    toastr.error("Something Went Wrong"); 
                })
        },

        deleteNewChallanDetailsId: function(index) {
            this.newProductDetails.splice(index, 1);
            
        },

        resetFields() {
            this.challanDate = this.getTodaysDate();
            this.challanNo = '',
            this.companyContactNo = '',
            this.companyGSTNo = '',
            this.selectedCompanyName = '',
            this.selectedBrokerName = '',
            this.unit = '',
            this.selectedProductQuality = '',
            this.selectedProductCategory = '',
            this.allData = [],
            this.totalQty = (0).toFixed(2)

        },

        resetChallanEditing: function(){
            this.challanDate = "";
            this.oldChallanDate="";
            this.challanNo = "";
            this.oldChallanNo = "";
            this.companyNames = [];
            this.selectedCompanyName = "";
            this.brokerNames = [];
            this.companyContactNo = '';
            this.companyGSTNo = "";
            this.productCategories = [];
            this.selectedProductCategory = '';

            this.productQualities = [];
            this.selectedProductQuality = '';

            this.unit = '';

            this.allData = [];

            this.totalQty = (0).toFixed(2);
            this.totalNewQty = (0).toFixed(2);
            this.netTotalQty = (0).toFixed(2);

            this.editedChallanDetailsIds = new Set();
            this.challanDetailsIdToBeDeleted = new Set(),

            this.newProductDetails = [];
            this.isNewProductDetailsFull = false;

            this.challanIdToBeEdit = -1;
        },

        selectQuantity(index, prefix = 'qty') {
            this.$refs[prefix + (index)][0].select();
        },

        cancelEdit: function() {
            this.resetChallanEditing();
        },

        sumTotalQuantity: function () {
            this.totalQty = (0).toFixed(2);
            for (let i = 0; i < this.allData.length; i++) {
                this.totalQty = parseFloat(this.totalQty) + parseFloat(this.allData[i].qty);
            }
            if (this.totalQty != 0.00) {
                this.totalQty = this.totalQty.toFixed(2);
            }
        },

        sumNewTotalQuantity: function () {
            this.totalNewQty = (0).toFixed(2);
            for (let i = 0; i < this.newProductDetails.length; i++) {
                this.totalNewQty = parseFloat(this.totalNewQty) + parseFloat(this.newProductDetails[i].qty);
            }
            if (this.totalNewQty != 0.00) {
                this.totalNewQty = this.totalNewQty.toFixed(2);
            }
        },

        sumNetTotalQty: function () {
            this.netTotalQty = parseFloat(this.totalQty) + parseFloat(this.totalNewQty);
            this.netTotalQty = parseFloat(this.netTotalQty).toFixed(2);
            
        },

        confirmChallandeletation: function(challanMstId, challanNo){

            swal
                .fire({
                    title: `<h5 style='color:#9C9794'>Are you sure to delete Challan No: ${challanNo} ?</h5>`,
                    html: `<h5 style='color:#9C9794'>Once Challan Is Deleted It can not be undo.</h5>`,
                    icon: "info",
                    allowOutsideClick: false,
                    showDenyButton: true,
                    confirmButtonText: 'Yes, Delete',
                    denyButtonText: `No`,
                })
                .then((result) => {
                    if(result.isConfirmed){
                        this.deleteChallan(challanMstId);
                    }
                    else if(result.isDenied){
                        toastr.info("Challan Deletation Canceled");
                    }
                })
        },

        deleteChallan: function(challanMstId){
            axios
                .delete("/api/challan/"+challanMstId)
                .then((res) => {

                    if(res.data.status == 1){
                        swal
                            .fire({
                                title: "Success",
                                html:  `<h5 style='color:#9C9794'>Challan No: ${res.data.challanNo} Deleted Successfully</h5>`,
                                icon: "success",
                                allowOutsideClick: false
                            })
                            .then(() => {
                                this.getChallans();
                            });
                    }
                    else if(res.data.status == -1){
                        console.log("Err In Delete Challan API Server Side");
                        toastr.error("Something Went wrong");
                    }
                    else{
                        console.log("Unexpected Respose Recived In Delete Challan API Call");
                        toastr.error("Something Went wrong");           
                    }
                    
                })
                .catch((err) => {
                    console.log("Err In Delete Challan API");
                    toastr.error("Something Went wrong");
                    
                })
        },

        viewChallan: function(challanMstId, challanNo){
            
            this.resetProductsArrayAndTotalQty();
            
            axios
                .get("/api/challan/"+challanMstId)
                .then((response) => {
                    this.challanToView.challanDate = response.data.challandate;
                    this.challanToView.challanNo = response.data.challanno;
                    this.challanToView.company = response.data.customer.customer_company_name;
                    this.challanToView.broker = response.data.broker.broker_name;
                    this.challanToView.quality = response.data.quality.quality_name;
                    this.challanToView.category = response.data.quality.category.sell_category_name;
                    this.challanToView.unit = response.data.unit;

                    let products = response.data.challandetails;
                    
                    let totalQtyPart1 = 0;
                    for(let i=0; i<products.length && i<16; i++){
                        
                        totalQtyPart1 += products[i].qty;

                        this.challanToView.products.productPart1.push({
                            no: products[i].no,
                            qty: (products[i].qty).toFixed(2)
                        }); 
                    }

                    let totalQtyPart2 = 0;
                    for(let i=16; i<products.length && i<32; i++){
                        
                        totalQtyPart2 += products[i].qty;
                        
                        this.challanToView.products.productPart1.push({
                            no: products[i].no,
                            qty: (products[i].qty).toFixed(2)
                        });
                    }

                    let totalQtyPart3 = 0;
                    for(let i=32; i<products.length && i<48; i++){
                        
                        totalQtyPart3 += products[i].qty;
                        
                        this.challanToView.products.productPart1.push({
                            no: products[i].no,
                            qty: (products[i].qty).toFixed(2)
                        });
                    }

                    this.challanToView.totalQty.totalQtyProductPart1 = totalQtyPart1.toFixed(2);
                    this.challanToView.totalQty.totalQtyProductPart2 = totalQtyPart2.toFixed(2);
                    this.challanToView.totalQty.totalQtyProductPart3 = totalQtyPart3.toFixed(2);
                    this.challanToView.totalQty.totalQty = (totalQtyPart1+totalQtyPart2+totalQtyPart3).toFixed(2);
                    this.challanToView.challanIdToBeViewd = challanMstId;
                    this.challanToView.challanNoToView = challanNo;
                })
                .catch((err)=>{
                    console.log("Err In View Challan API Call");
                    toastr.error("Something Went Wrong");
                })
        },
        
        closeView: function(){
            this.challanToView.challanIdToBeViewd = -1;
            this.challanToView.challanNoToView = -1;
            this.challanToView.challanDate = '';
            this.challanToView.challanNo = '';
            this.challanToView.company = "";
            this.challanToView.broker = "";
            this.challanToView.quality = "";
            this.challanToView.category = "";
            this.challanToView.unit = "";
            this.resetProductsArrayAndTotalQty();

        },

        resetProductsArrayAndTotalQty: function(){
            this.challanToView.products.productPart1 = [];
            this.challanToView.products.productPart2 = [];
            this.challanToView.products.productPart3 = [];
            this.challanToView.totalQty.totalQtyProductPart1 = (0).toFixed(2);
            this.challanToView.totalQty.totalQtyProductPart2 = (0).toFixed(2);
            this.challanToView.totalQty.totalQtyProductPart3 = (0).toFixed(2);
            this.challanToView.totalQty.totalQty = (0).toFixed(2);
        }
    },
    components: {
        ModelSelect
    }
};
</script>

<style scoped>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>