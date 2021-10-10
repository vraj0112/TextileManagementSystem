<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_inward_mst;
use App\Models\tbl_inward_details;
use App\Models\tbl_vendor;
use App\Models\tbl_broker;
use App\Models\tbl_inward_quality;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class InwardController extends Controller
{
    public function addNewInward(Request $request)
    {
        $validated = validator($request->all(),[
            'date' => 'required | date_format:Y-m-d',
            'invoiceNo' => 'required | max:20',
            'unit' => 'required | max:10',
            'quantity' => 'required | numeric',
            'rate' => 'required | numeric',
            'gstPercentage' => 'required'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "Validation Failed!",
                "errors" => $validated->errors()
            );

            return response()->json($res);
        }

        $date = $request->input("date");
        $invoiceNo = $request->input("invoiceNo");
        $companyName = $request->input("companyName");
        $brokerName = $request->input("brokerName");
        $productQuality = $request->input("productQuality");
        $unit = $request->input("unit");
        $quantity = $request->input("quantity");
        $rate = $request->input("rate");
        $gstPercentage = $request->input("gstPercentage");

        if(!tbl_vendor::isThereCompanyNameWithVendorId($companyName)){
            return response()->json(array(
                "status" => -1,
                "message" => "Vendor Not Available!"
            ));
        }

        if(!tbl_broker::isThereBrokerWithBrokerId($brokerName)){
            return response()->json(array(
                "status" => -1,
                "message" => "Broker Not Available!"
            ));
        }

        if(!tbl_inward_quality::isThereProductQualityWithQualityId($productQuality)){
            return response()->json(array(
                "status" => -1,
                "message" => "Inward Product Quality Not Available!"
            ));
        }

        if (tbl_inward_details::join('tbl_inward_msts','tbl_inward_details.inward_mst_id',"=","tbl_inward_msts.inward_mst_id")
        ->where('inward_mst_date', "=", $date)
        ->where('inward_mst_invoice_no', "=", $invoiceNo)
        ->where('inward_mst_vendor_id', "=", $companyName)
        ->where('inward_mst_status', "=", '1')
        ->where('inward_details_status',"=",'1')
        ->exists()) {
            $res = array(
                "status" => 0,
                "message" => "Inward Record Already Exists!",
                "errors" => null
            );
            return response()->json($res);
        }

        DB::beginTransaction();
        try{
            $inward_mst = new tbl_inward_mst();
            $inward_mst->inward_mst_date = $date;
            $inward_mst->inward_mst_invoice_no = $invoiceNo;
            $inward_mst->inward_mst_vendor_id = $companyName;
            $inward_mst->inward_mst_broker_id = $brokerName;
            $inward_mst->inward_mst_gst_percentage = $gstPercentage;
            $inward_mst->save();

            $mst_id = $inward_mst->id;

            $inward_detail = new tbl_inward_details();
            $inward_detail->inward_quality_id = $productQuality;
            $inward_detail->qty = $quantity;
            $inward_detail->qty_unit = $unit;
            $inward_detail->rate = $rate;
            $inward_detail->inward_mst_id = $mst_id;
            $inward_detail->save();

            DB::commit();

            $res = array(
                "status" => 1,
                "message" => "Inward Details Added Successfully.",
                "errors" => null
            );

            return response()->json($res, 200);
        }
        catch(QueryException $e){
            DB::rollBack();
            $res = array(
                "status" => -1,
                "message" => "Server Error!",
                "errors" => "Exception Generated!"
            );
            return response()->json($res, 500);
        }
    }

    public function getAllInwards(Request $request)
    {
        $paginate = request("paginate", 10);
        $search_term = request("search", "");
        $search_term = trim($search_term);
        $search_term = "%$search_term%";
        $sort_field = request("sortfield");
        $sort_direction = request("sortdirection");
        $from_date = request("fromdate", Carbon::now()->subDays(10));
        $to_date = request("todate", Carbon::now());
        $inward_mst_id = request("inward_mst_id", '');
 
        if(!in_array($sort_direction, ['asc', 'desc'])){
            $sort_direction = "asc";
        }

        if(!in_array($sort_field, ['inward_mst_id','inward_mst_date','inward_mst_vendor_id','inward_quality_id','qty','rate'])){
            $sort_field = 'inward_mst_id';
        }

        return (tbl_inward_details::join('tbl_inward_msts','tbl_inward_details.inward_mst_id',"=","tbl_inward_msts.inward_mst_id")
        ->join('tbl_vendors','tbl_inward_msts.inward_mst_vendor_id','=','tbl_vendors.vendor_id')
        ->join('tbl_inward_qualities','tbl_inward_details.inward_quality_id',"=",'tbl_inward_qualities.inward_quality_id')
        ->select('tbl_inward_details.inward_mst_id', 'inward_mst_date','inward_mst_invoice_no','vendor_company_name','quality_name','qty','rate')
        ->where('tbl_inward_msts.inward_mst_status', '=', 1)
        ->where('tbl_inward_details.inward_details_status', '=', 1)
        ->where(function($query) use ($search_term)
        {
            $query->where('tbl_inward_msts.inward_mst_date', 'like', $search_term)
            ->orWhere('tbl_inward_msts.inward_mst_invoice_no', 'like', $search_term)
            ->orWhere('tbl_inward_msts.inward_mst_vendor_id', 'like', $search_term)
            ->orWhere('tbl_inward_details.inward_quality_id', 'like', $search_term)
            ->orWhere('tbl_inward_details.qty', 'like', $search_term)
            ->orWhere('tbl_inward_details.rate', 'like', $search_term);
        })
        ->orderBy($sort_field, $sort_direction)    
        ->paginate($paginate));
    }

    public function viewInwardDetails(Request $request, $inwardId)
    {
        $date = $request->input("date");
        $invoiceNo = $request->input("invoiceNo");
        $companyName = $request->input("companyName");
        $brokerName = $request->input("brokerName");
        $productQuality = $request->input("productQuality");
        $unit = $request->input("unit");
        $quantity = $request->input("quantity");
        $rate = $request->input("rate");
        $gstPercentage = $request->input("gstPercentage");
    }

    public function updateInward(Request $request, $inwardId)
    {
        $validated = validator($request->all(),[
            'date' => 'required | date_format:Y-m-d',
            'invoiceNo' => 'required | max:20',
            'unit' => 'required | max:10',
            'quantity' => 'required | numeric',
            'rate' => 'required | numeric',
            'gstPercentage' => 'required'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "Validation Failed!",
                "errors" => $validated->errors()
            );

            return response()->json($res);
        }

        $date = $request->input("date");
        $invoiceNo = $request->input("invoiceNo");
        $companyName = $request->input("companyName");
        $brokerName = $request->input("brokerName");
        $productQuality = $request->input("productQuality");
        $unit = $request->input("unit");
        $quantity = $request->input("quantity");
        $rate = $request->input("rate");
        $gstPercentage = $request->input("gstPercentage");

        if(!tbl_vendor::isThereCompanyNameWithVendorId($companyName)){
            return response()->json(array(
                "status" => -1,
                "message" => "Vendor Not Available!"
            ));
        }

        if(!tbl_broker::isThereBrokerWithBrokerId($brokerName)){
            return response()->json(array(
                "status" => -1,
                "message" => "Broker Not Available!"
            ));
        }

        if(!tbl_inward_quality::isThereProductQualityWithQualityId($productQuality)){
            return response()->json(array(
                "status" => -1,
                "message" => "Inward Product Quality Not Available!"
            ));
        }

        if (tbl_inward_details::join('tbl_inward_msts','tbl_inward_details.inward_mst_id',"=","tbl_inward_msts.inward_mst_id")
        ->where('inward_mst_date', "=", $date)
        ->where('inward_mst_invoice_no', "=", $invoiceNo)
        ->where('inward_mst_vendor_id', "=", $companyName)
        ->where('inward_mst_status', "=", '1')
        ->where('inward_details_status',"=",'1')
        ->exists()) {
            $res = array(
                "status" => 0,
                "message" => "Inward Record Already Exists!",
                "errors" => null
            );
            return response()->json($res);
        }

        tbl_inward_details::join('tbl_inward_msts','tbl_inward_details.inward_mst_id',"=","tbl_inward_msts.inward_mst_id")
        ->where('inward_details_id','=',$inwardId)
        ->update(['inward_mst_date' => $date, 
        'inward_mst_invoice_no' => $invoiceNo, 
        'inward_mst_vendor_id' => $companyName,
        'inward_mst_broker_id' => $brokerName,
        'inward_quality_id' => $productQuality,
        'qty' => $quantity,
        'qty_unit' => $unit,
        'rate' => $rate,
        'inward_mst_gst_percentage' => $gstPercentage]);
        return response()->json(array(
            "message"=> "Inward Record Updated Succesfully..."
        ));
    }

    public function deleteInward(Request $request, $inwardId)
    {
        tbl_inward_details::join('tbl_inward_msts','tbl_inward_details.inward_mst_id',"=","tbl_inward_msts.inward_mst_id")
        ->where('inward_details_id','=',$inwardId)
        ->update(['inward_mst_status' => 0, 'inward_details_status' => 0]);
        return response()->json(array(
            "message"=> "Inward Record Deleted Succesfully..."
        ));
    }
}
