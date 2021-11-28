<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_challan_mst;
use App\Models\tbl_challan_details;
use App\Models\tbl_gst_code;
use App\Models\tbl_invoice_mst;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function getFromInvoiceNo(Request $request, $invoiceNo){
        $challanDateQuery = tbl_challan_mst::select('challan_date')->where('challan_no', '=', $invoiceNo)->where('challan_mst_status', '=',1)->get();
        $financialYears = array();
        foreach($challanDateQuery as $challanDates){
            $challanDate = $challanDates->challan_date;
            $challanSplitDate = explode("-",$challanDate);
            $challanMonth = $challanSplitDate[1];
            $challanYear = $challanSplitDate[2];

            $fromDate = $challanYear;
            $toDate = $challanYear;

            if((int)$challanMonth<4){
                $fromDate = (int)$challanYear - 1;
            }else{
                $toDate = (int)$challanYear + 1;
            }
            
            $fromDate = $fromDate;
            $toDate = $toDate;

            array_push($financialYears, $fromDate.'-'.$toDate);
        }
        return $financialYears;
    }

    public function getFromInvoiceNoAndFinancialYear(Request $request, $invoiceNo, $fromDate, $toDate){
        
        $displayData = array(); 
        try{
            $challanMstIdQuery = tbl_challan_mst::select('challan_mst_id')->where('challan_no', '=', $invoiceNo)
            ->whereBetween('challan_date', [$fromDate, $toDate])->first();

            if($challanMstIdQuery === null){
                $res = array(
                    "status" => -1,
                    "message" => "Data Not Found",
                    "errors" => "Data Not Found"
                );
                return response()->json($res);       
            }
    
            $challanMstId = $challanMstIdQuery->challan_mst_id;

            
            $totalPiecesQuery = tbl_challan_details::where('challan_mst_id', '=', $challanMstId)
            ->where('challan_details_status', 1)->get();
            $totalPieces = $totalPiecesQuery->count();
            
            $invoiceDataQuery = tbl_challan_mst::join('tbl_brokers', 'tbl_challan_msts.broker_id', '=', 'tbl_brokers.broker_id')
            ->join('tbl_customers', 'tbl_customers.customer_id', '=', 'tbl_challan_msts.customer_id')
            ->join('tbl_sell_qualities', 'tbl_sell_qualities.sell_quality_id', '=', 'tbl_challan_msts.sell_quality_id')
            ->select('tbl_challan_msts.challan_no','tbl_challan_msts.challan_date', 'tbl_challan_msts.total_qty', 
            'tbl_brokers.broker_name', 'tbl_customers.customer_company_name', 'tbl_customers.customer_gst_no', 
            'tbl_customers.customer_gst_code', 'tbl_customers.customer_address', 'tbl_sell_qualities.quality_name')
            ->where('tbl_challan_msts.challan_no', '=', $invoiceNo)
            ->whereBetween('tbl_challan_msts.challan_date', [$fromDate, $toDate])
            ->where('tbl_challan_msts.challan_mst_status',1)
            ->where('tbl_brokers.broker_status', 1)
            ->where('tbl_customers.customer_status', 1)
            ->where('tbl_sell_qualities.sell_quality_status', 1)
            ->first();
        }catch(QueryException $e){
            $res = array(
                "status" => -1,
                "message" => "Server Error",
                "errors" => $e
            );
            return response()->json($res, 500);
        }

        array_push($displayData, $totalPieces);
        array_push($displayData, $invoiceDataQuery);

        return $displayData;
        
    }

    public function getStateFromCode(Request $request, $gstCode){
        return (tbl_gst_code::select('state_name')->where('gst_code', '=', $gstCode)->first());
    }

    public function addNewInvoiceFromChallan(Request $request){
        $validated = validator($request->all(),[
            'invoiceId' => 'required | numeric',
            'invoiceDate' => 'required | date_format:Y-m-d',
            'rate' => 'required | numeric',
            'gstPercentage' => 'required | numeric',
            'bankId' => 'required | numeric',
            'dueDate' => 'required | date_format:Y-m-d',
            'fromDate' => 'required |date_format:Y-m-d',
            'toDate' => 'required |date_format:Y-m-d'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );
            return response()->json($res);
        }

        $invoiceId = $request->input("invoiceId");
        $invoiceDate = $request->input("invoiceDate");
        $rate = $request->input("rate");
        $gstPercentage = $request->input("gstPercentage");
        $bankId = $request->input("bankId");
        $dueDate = $request->input("dueDate");
        $fromDate = $request->input("fromDate");
        $toDate = $request->input("toDate");

        if(tbl_invoice_mst::where('invoice_mst_id', '=', $invoiceId)->whereBetween('invoice_date', [$fromDate, $toDate])->exists()){
            $res = array(
                "status" => 0,
                "message" => "Record Already Exists",
                "errors" => null
            );
            return response()->json($res);
        }


        DB::beginTransaction();
        try{

            // tbl_invoice_mst::find()

            $invoice = new tbl_invoice_mst();
            $invoice->invoice_mst_id = $invoiceId;  
            $invoice->invoice_date = $invoiceDate;
            $invoice->no_of_units = 0;
            $invoice->rate = $rate;
            $invoice->gst_percentage = $gstPercentage;
            $invoice->bank_details_id = $bankId;
            $invoice->due_date = $dueDate;

            $invoice->save();

        }catch(Exception $e){
            DB::rollBack();
            $res = array(
                "status" => -1,
                "message" => "Server Error",
                "errors" => "Exception Generated"
            );
            return response()->json($res, 500);
        }

        DB::commit();

        $res = array(
            "status" => 1,
            "message" => "Invoice Added Successfully.",
            "errors" => null
        );

        return response()->json($res, 200);


    }

    public function verifyInvoiceDate(Request $request, $invoiceDate, $fromDate, $toDate){
        $invoiceSplitDate = explode("-", $invoiceDate);
        $invoiceMonth = $invoiceSplitDate[1];
        $invoiceYear = $invoiceSplitDate[0];

        $fromDateInvoice = $invoiceYear;
        $toDateInvoice = $invoiceYear;
        if((int)$invoiceMonth<4){
            $fromDateInvoice = (int)$invoiceYear - 1;
        }else{
            $toDateInvoice = (int)$invoiceYear + 1;
        }

        $fromDateInvoice = $fromDateInvoice."-04-01";
        $toDateInvoice = $toDateInvoice."-03-31";

        if($fromDateInvoice === $fromDate && $toDateInvoice === $toDate){
            $res = array(
                "status" => "1",
                "message" => "Invoice Date is Valid.",
            );

            return response()->json($res);
        }else{
            $res = array(
                "status" => "0",
                "message" => "Invoice Date is Invalid. It must be in the entered Financial Year.",
            );

            return response()->json($res);
        }
    }

    public function getAllChallanInvoices(Request $req){

        $fromDate = request('fromdate', '2019-10-04');
        $toDate = request('todate', '2022-10-15');

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
        ->where('tbl_challan_msts.is_direct', false)
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
        ->select('invoice_mst_id', 'invoice_date', 'due_date','challan_no','quality_name',  'tbl_challan_msts.customer_id', 'customer_company_name', 'tbl_challan_msts.broker_id', 'broker_name', 'tbl_sell_quality_categories.sell_category_name', 'tbl_challan_msts.total_qty', 'tbl_invoice_msts.rate', 'tbl_invoice_msts.gst_percentage')
        ->paginate($paginate);
    }

    public function getFinancialYearOfChallanDateInArray($challanDate){

        $challanSplitDate = explode("-",$challanDate);
        $challanMonth = $challanSplitDate[1];
        $challanYear = $challanSplitDate[0];
        
        $fromDate = $challanYear;
        $toDate = $challanYear;
        if((int)$challanMonth<4){
            $fromDate = (int)$challanYear - 1;
        }else{
            $toDate = (int)$challanYear + 1;
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

    public function updateChallanInvoice(Request $req){
        $validated = validator($req->all(),[
            'invoiceId' => "required | numeric",
            'invoiceDate' => 'required | date_format:Y-m-d',
            'oldInvoiceDate' => 'required | date_format:Y-m-d',
            'invoiceDueDate' => 'required | date_format:Y-m-d',
            'rate' => "required | numeric",
            'gstPercentages' => "required | numeric",
            'bankId' => "required | numeric"
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
        $invoiceDate = $req->input('invoiceDate');
        $oldInvoiceDate = $req->input('oldInvoiceDate');
        $invoiceDueDate = $req->input('invoiceDueDate');
        $rate = $req->input('rate');
        $gstPercentage = $req->input('gstPercentage');
        $bankId = $req -> input("bankId");

        

        DB::beginTransaction();

        try{
            
            $challanMst = tbl_challan_mst::find($invoiceId);
            $challanDate = $challanMst -> challan_date;

            if($oldInvoiceDate != $invoiceDate ){
                $financialYearOfChallanDate = $this->getFinancialYearOfChallanDateInArray($challanDate);
                $financialYearOfInvoiceDate = $this->getFinancialYearOfChallanDateInArray($invoiceDate);

                if($financialYearOfChallanDate['fromDate'] != $financialYearOfInvoiceDate['fromDate'] || $financialYearOfChallanDate['toDate'] != $financialYearOfInvoiceDate['toDate']){
                    return response()->json(array(
                        "status" => 0,
                        "message" => `Challan Belongs To Financial Year ${$financialYearOfChallanDate['fromDate']} to ${$financialYearOfChallanDate['toDate']}`
                    ));
                }
            }

            $invoiceMst = tbl_invoice_mst::find($invoiceId)->where('invoice_mst_status', true);
            $invoiceMst->invoice_date = $invoiceDate;
            $invoiceMst->rate = $rate;
            $invoiceMst->gst_percentage = $gstPercentage;
            $invoiceMst->bank_detials_id = $bankId;

            $invoiceMst->save();
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

    public function getChallanInvoiceOfInvoiceId(Request $req, $invoiceMstId){
        
        $data = tbl_invoice_mst::with(['challanMstForInvoiceFromChallan', 'bank:bank_details_id,bank_name,branch_name,account_no'])
            ->where('invoice_mst_status', true)
            ->where('invoice_mst_id', $invoiceMstId)
            ->select('invoice_mst_id', 'invoice_date', 'rate', 'gst_percentage', 'due_date', 'bank_details_id')
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

    public function updateInvoiceForChallan(Request $req){
        $validated = validator($req->all(),[
            'invoiceId' => 'required | numeric',
            'dueDate' => 'required | date_format:Y-m-d',
            'rate' => 'required | numeric',
            'gstPercentage' => 'required | numeric',
            'bankId' => 'required | numeric'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );
            return response()->json($res);
        }

        $invoiceId = $req->input('invoiceId');
        $dueDate = $req->input('dueDate');
        $rate = $req->input('rate');
        $gstPercentage = $req->input('gstPercentage');
        $bankId = $req->input('bankId');

        DB::beginTransaction();

        try{
            $invoice = tbl_invoice_mst::find($invoiceId);
            $invoice->due_date = $dueDate;
            $invoice->rate = $rate;
            $invoice->gst_percentage = $gstPercentage;
            $invoice->bank_details_id = $bankId;
    
            $invoice->save();

            DB::commit();

            return response()->json(array(
                "status" => 1,
                "message" => "Invoice Updated Successfully"
            ));
        }
        catch(Exeption $e){
            DB::rollback();

            return response()->json(array(
                "status" => -1,
                "message" => "Err In Updating Invoice"
            ));
        }
        
    }

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
            "unit" => "required",
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
            $invoiceMst->bank_details_id = 1;
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