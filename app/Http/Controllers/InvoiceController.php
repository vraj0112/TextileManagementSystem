<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_challan_mst;
use App\Models\tbl_invoice_mst;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    //

    public function getFinancialYearOfDate($date){

        $date = explode("-",$date);
        $month = $date[1];
        $year = $date[0];
        
        $fromDate = $year;
        $toDate = $year;
        if((int)$month<4){
            $fromDate = (int)$year - 1;
        }else{
            $toDate = (int)$year + 1;
        }
        
        $fromDate = $fromDate."-04-01";
        $toDate = $toDate."-03-31";
        
        // return json_encode(array(
        //     "fromDate" => $fromDate, 
        //     "toDate" => $toDate
        // ));
        
        return array(
            "fromDate" => $fromDate,
            "toDate" => $toDate
        );
    }

    public function addNewDirectInvoice(Request $req){
        $validated = validator($req->all(),[
            'invoiceDate' => 'required | date_format:Y-m-d',
            'invoiceNo' => 'required | numeric',
            'customerId' => 'required | numeric',
            'brokerId' => 'required | numeric',
            'categoryId' => 'required | numeric',
            'qualityId' => 'required | numeric',
            'qty' => "required | numeric",
            "unit" => "required | regex:/[a-zA-Z]+\./i",
            'noOfUnits' => "required | numeric",
            'rate' => "required | numeric",
            'gstPercentage' => "required | numeric"
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );

            return response()->json($res);
        }

        $invoiceNo = $req->input('invoiceNo');
        $invoiceDate = $req->input('invoiceDate');
        $customerId = $req->input('customerId');
        $brokerId = $req->input('brokerId');
        $categoryId = $req->input('categoryId');
        $qualityId = $req->input('qualityId');
        $qty = $req->input('qty');
        $unit = $req->input('unit');
        $noOfUnits = $req->input('noOfUnits');
        $rate = $req->input('rate');
        $gstPercentage = $req->input('gstPercentage');

        $financialYear = $this->getFinancialYearOfDate($invoiceDate);

        if(tbl_invoice_mst::hasChallanOrInvoiceWithInGivenInvoceNoAndFinancialYear($invoiceNo, $financialYear)){
            return response()->json(array(
                'status' => 0,
                "message" => "Invoice No. Already Exists Or Challan For This Invoice Is Generated."
            )); 
        }

        DB::beginTransaction();

        try{
            $challanMst = new tbl_challan_mst();
            $challanMst->challan_no = $invoiceNo;
            $challanMst->challan_date = $invoiceDate;
            $challanMst->customer_id = $customerId;
            $challanMst->sell_quality_id = $qualityId;
            $challanMst->qty_unit = $unit;
            $challanMst->total_qty = $qty;
            $challanMst->broker_id = $brokerId;
            $challanMst->challan_type = $categoryId;
            $challanMst->is_direct = 1;

            $challanMst->save();

            $invoiceMst = new tbl_invoice_mst();
            $invoiceMst->invoice_mst_id = $challanMst->challan_mst_id;
            $invoiceMst->invoice_date = $invoiceDate;
            $invoiceMst->no_of_units = $noOfUnits;
            $invoiceMst->rate = $rate;
            $invoiceMst->gst_percentage = $gstPercentage;

            $invoiceMst->save();

            DB::commit();

            return response()->json(array(
                "status" => 1,
                "message" => "Invoice Added Successfully"
            ));
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(array(
                'status' => -1,
                "message" => "Something Went Wrong While Generating Invoice"
            ));
        }

    }

    public function getAllDirectInvoices(Request $req){
        
        $fromDate = request('fromdate', Carbon::now()->subDays(10));
        $toDate = request('todate', Carbon::now());

        //$fromDate = request('fromdate', '2019-10-04');
        //$toDate = request('todate', '2022-10-15');

        $customer = request('customer');
        $category = request('category');
        $quality = request('quality');
        $broker = request('broker');

        $paginate = request("paginate", 10);

        $sortDirection = request('sortdirection');
        if(!in_array($sortDirection,['asc', 'desc'])){
            $sortDirection = "desc";
        }

        $sortField = request('sortfield');
        if(!in_array($sortField,['invoice_date', 'invoice_no'])){
            $sortField = "invoice_date";
        }


        
        return tbl_invoice_mst::join('tbl_challan_msts', 'tbl_challan_msts.challan_mst_id', 'tbl_invoice_msts.invoice_mst_id')        
        ->join('tbl_sell_qualities', 'tbl_sell_qualities.sell_quality_id', 'tbl_challan_msts.sell_quality_id')
        ->join('tbl_sell_quality_categories', 'tbl_sell_quality_categories.sell_quality_category_id', 'tbl_sell_qualities.sell_quality_category_id')
        ->join('tbl_customers', 'tbl_challan_msts.customer_id', 'tbl_customers.customer_id')
        ->join('tbl_brokers', 'tbl_challan_msts.broker_id', 'tbl_brokers.broker_id')
        ->where('tbl_invoice_msts.invoice_mst_status', true)
        ->where('tbl_challan_msts.is_direct', true)
        ->whereDate('invoice_date', "<=", $toDate)
        ->whereDate('invoice_date', ">=", $fromDate)
        ->when($customer, function ($query) use ($customer){
            $query->where('tbl_challan_msts.customer_id',$customer);
        })
        ->when($category, function ($query) use ($category){
            $query->where('tbl_sell_quality_categories.sell_quality_category_id', $category);
        })
        ->when($quality, function ($query) use ($quality){
            $query->where('tbl_challan_msts.sell_quality_id', $quality);
        })
        ->when($broker, function ($query) use ($broker){
            $query->where('tbl_challan_msts.broker_id', $broker);
        })
        ->orderBy($sortField, $sortDirection)
        ->select('invoice_mst_id', 'invoice_date', 'challan_no','quality_name',  'tbl_challan_msts.customer_id', 'customer_company_name', 'tbl_challan_msts.broker_id', 'broker_name', 'tbl_sell_quality_categories.sell_category_name', 'tbl_challan_msts.total_qty', 'tbl_invoice_msts.rate', 'tbl_invoice_msts.gst_percentage')
        ->paginate($paginate);
    }

    public function getDirectInvoiceOfInvoiceId(Request $req, $invoiceMstId){
        
        $data = tbl_invoice_mst::with('challanMstForInvoice')
            ->where('invoice_mst_status', true)
            ->where('invoice_mst_id', $invoiceMstId)
            ->select('invoice_mst_id', 'invoice_date', 'no_of_units', 'rate', 'gst_percentage')
            ->first();

        if(!$data){
            return response()->json(array(
                "status" => 0,
                "message" => "Invoice With Given Invoice No Doesnt Exists"
            ));
        }

        return response()->json(array(
            "status" => 1,
            "message" => "Invoice Data Fetched",
            "data" => $data
        ));
    }

    public function updateDirectInvoice(Request $req){
        $validated = validator($req->all(),[
            'invoiceId' => 'required | numeric',
            'invoiceDate' => 'required | date_format:Y-m-d',
            'oldInvoiceDate' => 'required | date_format:Y-m-d',
            'invoiceNo' => 'required | numeric',
            'oldInvoiceNo' => 'required | numeric',
            'customer' => 'required | numeric',
            'broker' => 'required | numeric',
            'category' => 'required | numeric',
            'quality' => 'required | numeric',
            'qty' => "required | numeric",
            "unit" => "required",
            // "unit" => "required | regex:/[a-zA-Z]+\./i",
            'noOfUnits' => "required | numeric",
            'rate' => "required | numeric",
            'gstPercentage' => "required | numeric"
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "statusCode" => 1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );

            return response()->json($res);
        }

        $invoiceId = $req->input('invoiceId');
        $invoiceNo = $req->input('invoiceNo');
        $oldInvoiceNo = $req->input('oldInvoiceNo');
        $invoiceDate = $req->input('invoiceDate');
        $oldInvoiceDate = $req->input('oldInvoiceDate');
        $customerId = $req->input('customer');
        $brokerId = $req->input('broker');
        $categoryId = $req->input('category');
        $qualityId = $req->input('quality');
        $qty = $req->input('qty');
        $unit = $req->input('unit');
        $noOfUnits = $req->input('noOfUnits');
        $rate = $req->input('rate');
        $gstPercentage = $req->input('gstPercentage');


        $financialYearOfNewInvoiceDate = $this->getFinancialYearOfDate($invoiceDate);
        $financialYearOfOldInvoiceDate = $this->getFinancialYearOfDate($oldInvoiceDate);
        
        if($financialYearOfNewInvoiceDate['fromDate'] == $financialYearOfOldInvoiceDate['fromDate'] && $financialYearOfNewInvoiceDate['toDate'] == $financialYearOfOldInvoiceDate['toDate']){
            if($oldInvoiceNo != $invoiceNo){
                if(tbl_invoice_mst::hasChallanOrInvoiceWithInGivenInvoceNoAndFinancialYear($invoiceNo, $financialYearOfNewInvoiceDate)){
                    return response()->json(array(
                        'status' => 0,
                        "message" => "Invoice No. Already Exists Or Challan For This Invoice Is Generated."
                    )); 
                }
        
            }
        }else{
            if(tbl_invoice_mst::hasChallanOrInvoiceWithInGivenInvoceNoAndFinancialYear($invoiceNo, $financialYearOfNewInvoiceDate)){
                return response()->json(array(
                    'status' => 0,
                    "message" => "Invoice No. Already Exists Or Challan For This Invoice Is Generated."
                )); 
            }
        }

        
        DB::beginTransaction();

        try{
            

            $invoiceMst = tbl_invoice_mst::find($invoiceId);
            $invoiceMst->invoice_date = $invoiceDate;
            $invoiceMst->no_of_units = $noOfUnits;
            $invoiceMst->rate = $rate;
            $invoiceMst->gst_percentage = $gstPercentage;

            $invoiceMst->save();

            $challanMst = tbl_challan_mst::find($invoiceId);
            $challanMst->challan_no = $invoiceNo;
            $challanMst->challan_date = $invoiceDate;
            $challanMst->customer_id = $customerId;
            $challanMst->sell_quality_id = $qualityId;
            $challanMst->qty_unit = $unit;
            $challanMst->total_qty = $qty;
            $challanMst->broker_id = $brokerId;
            $challanMst->challan_type = $categoryId;
            $challanMst->is_direct = 1;

            $challanMst->save();



            DB::commit();

            return response()->json(array(
                "status" => 1,
                "message" => "Invoice Updated Successfully"
            ));
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(array(
                'status' => -1,
                "statusCode" => 0,
                "message" => "Something Went Wrong While Generating Invoice"
            ));
        }

    }

    public function deleteDirectInvoice(Request $req, $invoiceMstId){
        
        DB::beginTransaction();
        try{
            $invoiceMst = tbl_invoice_mst::find($invoiceMstId);
            $invoiceMst->invoice_mst_status = false;
            $invoiceMst->save();

            $challanMst = tbl_challan_mst::find($invoiceMstId);
            $challanMst->challan_mst_status = false;
            $invoiceNo = $challanMst->challan_no;
            $challanMst->save();

            DB::commit();

            return response()->json(array(
                "status" => 1,
                'invoiceNo' => $invoiceNo,
                "message" => "Invoice Deleted Successfully"
            ));
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(array(
                "status" => -1,
                "message" => "Invoice Deletation Failed"
            ));
        }
    }



    
}
