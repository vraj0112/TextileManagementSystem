<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\tbl_challan_mst;
use App\Models\tbl_challan_details;

class ChallanController extends Controller
{
    public function getFinancialYearOfChallanDate(Request $request, $challanDate){

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
        
        return response()->json(array(
            "fromDate" => $fromDate, 
            "toDate" => $toDate
        ));
    }

    public function verifyChallanNumber(Request $request, $challanNo, $fromDate, $toDate){
        if(tbl_challan_mst::where('challan_no', '=', $challanNo)->where('challan_mst_status', '=', 1)->whereBetween('challan_date', [$fromDate, $toDate])->exists()){
            return response()->json(array(
                "status" => 0,
                "message"=> "Entered Challan Number Already Exists!!!"
            ));
        }else{
            return response()->json(array(
                "status" => 1
            ));
        }
    }

    public function addNewChallan(Request $request){
        $validated = validator($request->all(),[
            'challanNo' => 'required | numeric',
            'challanDate' => 'required | date_format:Y-m-d',
            'customerId' => 'required | numeric',
            'sellCategoryId' => 'required | numeric',
            'sellQualityId' => 'required | numeric',
            'qtyUnit' => 'required |  max:10',
            'totalQty' => 'required | numeric',
            'brokerId' => 'required | numeric',
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );

            return response()->json($res);
        }

        $challanNo = $request->input("challanNo");
        $challanDate = $request->input("challanDate");
        $customerId = $request->input("customerId");
        $sellCategoryId = $request->input("sellCategoryId");
        $sellQualityId = $request->input("sellQualityId");
        $qtyUnit = $request->input("qtyUnit");
        $totalQty = $request->input("totalQty");
        $brokerId = $request->input("brokerId");
        $fromDate = $request->input("fromDate");
        $toDate = $request->input("toDate");
        $allData = $request->input("allData");

        if($sellCategoryId == 1 || $sellCategoryId == 2){
            for($i=0; $i<count($allData); $i++){
                if(tbl_challan_mst::join('tbl_challan_details','tbl_challan_msts.challan_mst_id', '=', 'tbl_challan_details.challan_mst_id')
                ->join('tbl_sell_qualities', 'tbl_sell_qualities.sell_quality_id', '=', 'tbl_challan_msts.sell_quality_id')
                ->select('tbl_challan_msts.challan_date', 'tbl_challan_msts.sell_quality_id', 'tbl_sell_qualities.sell_quality_category_id', 'tbl_challan_details.no')
                ->where('tbl_challan_details.challan_details_status', 1)
                ->where('tbl_challan_msts.challan_mst_status' , 1)
                ->whereBetween('challan_date', [$fromDate, $toDate])
                ->where('tbl_sell_qualities.sell_quality_category_id', '=', $sellCategoryId)
                ->where('tbl_challan_details.no', '=', $allData[$i]['no'])->exists()){
                    $res = array(
                        "status" => 0,
                        "message" => 'Product No '.($i+1).' Already Exists.',
                        "errors" => null
                    );
        
                    return response()->json($res);
                }
            }
        }

        DB::beginTransaction();
        try{
            $challanMst = new tbl_challan_mst();
            $challanMst->challan_no = $challanNo;
            $challanMst->challan_date = $challanDate;
            $challanMst->customer_id = $customerId;
            $challanMst->sell_quality_id = $sellQualityId;
            $challanMst->qty_unit = $qtyUnit;
            $challanMst->total_qty = $totalQty;
            $challanMst->broker_id = $brokerId;
            $challanMst->challan_type = $sellCategoryId;
            $challanMst->is_direct = 0;

            $challanMst->save();

            $challanMstId = tbl_challan_mst::select('challan_mst_id')->latest()->first();

            for($i=0;$i<count($allData);$i++){
                $challanDetails = new tbl_challan_details();
                $challanDetails->no = $allData[$i]['no'];
                $challanDetails->qty = $allData[$i]['qty'];
                $challanDetails->challan_mst_id = $challanMstId->challan_mst_id;
                $challanDetails->challan_type = $sellCategoryId;

                $challanDetails->save();
            }

        }catch(QueryException $e){
            DB::rollBack();
            $res = array(
                "status" => -1,
                "message" => "Server Error",
                "errors" => $e
            );
            return response()->json($res, 500);
        }

        DB::commit();

        $res = array(
            "status" => 1,
            "message" => "Sell Quality Added Successfully.",
            "errors" => null
        );

        return response()->json($res, 200);


    }
}
