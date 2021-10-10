<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Query\Builder;
use App\Models\tbl_challan_details;
use App\Models\tbl_challan_mst;
use App\Models\tbl_sell_quality;
use Carbon\Carbon;

class ChallanController extends Controller
{
    //

    function getChallans(Request $req){
        $paginate = request("paginate", 10);
        $company = request("company","");
        $category = request("category","");
        $quality = request("quality","");
        $broker = request("broker","");


        $search_term = request("search", "");
        $search_term = trim($search_term);
        $search_term = "%$search_term%";

        $sort_field = request("sortfield");
        $sort_direction = request("sortdirection");

        $from_date = request("fromdate", Carbon::now()->subDays(10));
        $to_date = request("todate", Carbon::now());

 
        if(!in_array($sort_direction, ['asc', 'desc'])){
            $sort_direction = "desc";
        }

        if(!in_array($sort_field, ['challan_date','challan_no'])){
            $sort_field = 'challan_date';
        }

        $data = tbl_challan_mst::join('tbl_challan_details', 'tbl_challan_details.challan_mst_id', "=", 'tbl_challan_msts.challan_mst_id')
        ->join('tbl_customers', 'tbl_customers.customer_id',"=", 'tbl_challan_msts.customer_id')
        ->join('tbl_brokers', 'tbl_brokers.broker_id', "=", 'tbl_challan_msts.broker_id')
        ->join('tbl_sell_qualities', 'tbl_sell_qualities.sell_quality_id',"=", 'tbl_challan_msts.sell_quality_id')
        ->join('tbl_sell_quality_categories', 'tbl_sell_quality_categories.sell_quality_category_id', "=", "tbl_sell_qualities.sell_quality_category_id")
        ->where('tbl_challan_msts.challan_mst_status', true)
        ->where('tbl_challan_details.challan_details_status', true)
        ->whereDate('tbl_challan_msts.challan_date', "<=", $to_date)
        ->whereDate('tbl_challan_msts.challan_date', ">=", $from_date)
        ->when($company, function($query) use ($company){
            $query->where("tbl_challan_msts.customer_id", '=', $company);
        })
        ->when($category, function($query) use ($category){
            $query->where('tbl_sell_qualities.sell_quality_category_id', $category);
        })
        ->when($quality, function($query) use ($quality){
            $query->where('tbl_challan_msts.sell_quality_id', $quality);
        })
        ->when($broker, function($query) use ($broker){
            $query->where('tbl_challan_msts.broker_id', $broker);
        })
        ->orderBy($sort_field, $sort_direction)
        ->select('tbl_challan_msts.challan_mst_id', 'tbl_challan_msts.challan_no','tbl_challan_msts.challan_date','customer_company_name','tbl_brokers.broker_name',DB::raw("SUM(tbl_challan_details.qty) as totalqty"), 'tbl_sell_qualities.quality_name', 'tbl_sell_quality_categories.sell_category_name')
        ->groupBy('tbl_challan_msts.challan_mst_id', 'tbl_customers.customer_company_name', 'tbl_brokers.broker_name', 'tbl_sell_qualities.quality_name', 'tms.tbl_challan_msts.challan_date','tbl_challan_msts.challan_no', 'tbl_sell_quality_categories.sell_category_name')->paginate($paginate);

        return $data;
    }

    function getChallanDataOfChallanId(Request $req, $challanId){

        $dataToBeValidate = array(
            "challanid" => $challanId
        );

        $validated = validator($dataToBeValidate,[
            'challanid' => 'numeric'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "Challan Id Is Not In Valid Format"
            );

            return response()->json($res);
        }
        
        $challanDataFetched = tbl_challan_mst::with("challan_details:challan_mst_id,challan_details_id,no,qty", "customer_relation:customer_id,customer_company_name", "broker:broker_id,broker_name", "quality:sell_quality_id,quality_name,sell_quality_category_id")
        ->where("tbl_challan_msts.challan_mst_id", $challanId)
        ->first();

        if(is_null($challanDataFetched)){
            return response()->json(array(
                "status" => 0,
                "message" => "Challan Not Found"
            ));
        }

        $challanData = array(
            "challanid" => (int)$challanId,
            "challanno" => $challanDataFetched->challan_no,
            "challandate" => $challanDataFetched->challan_date,
            "customer" => $challanDataFetched->customer_relation,
            "broker"=>$challanDataFetched->broker,
            "quality" => $challanDataFetched->quality,
            "unit" => $challanDataFetched->qty_unit,
            "challandetails"=>$challanDataFetched->challan_details
        );

        return response()->json($challanData);
    }

    public function getFinancialYearOfChallanDate($challanDate){

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

    function updateChallan(Request $req){

        $validated = validator($req->all(),[
            'challanMstId' => 'required | numeric',
            'oldChallanNo' => 'required | numeric',
            'challanNo' => 'required | numeric',
            'challandate' => 'required | date_format:Y-m-d',
            'company' => 'required | numeric',
            'category' => 'required | numeric',
            'quality' => 'required | numeric',
            'unit' => 'required |  max:10',
            'broker' => 'required | numeric'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "statuscode" => 1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );
            return response()->json($res);
        }

        $challanMstId = $req->input('challanMstId');
        $challanDate = $req->input('challandate');
        $challanNo = $req->input('challanNo');
        $oldChallanNo = $req->input("oldChallanNo");
        $company = $req->input('company');
        $broker = $req->input('broker');
        $category = $req->input('category');
        $quality = $req->input('quality');
        $unit = $req->input('unit');
        $challanDetails = $req->input('challanDetails');
        $newProductDetails = $req->input('newProductDetails');
        $challanDetailsIdsToBeDeleted = $req->input('challanDetailsIdsToBeDeleted');


        $notValidInEditing = array();
        $notValidInNew = array();

        foreach($challanDetails as $index=>$challanDetail){
            if(empty($challanDetail['no']) || !is_numeric($challanDetail['no']) || empty($challanDetail['qty']) || !is_numeric($challanDetail['qty']) ){
                array_push($notValidInEditing, $index);
            }
        }

        foreach($newProductDetails as $index=>$newProductDetail){
            if(empty($newProductDetail['no']) || !is_numeric($newProductDetail['no']) || empty($newProductDetail['qty']) || !is_numeric($newProductDetail['qty']) ){
                array_push($notValidInNew, $index);
            }
        }

        if(count($notValidInEditing) != 0 || count($notValidInNew) != 0){
            return response()->json(array(
                "status" => -1,
                "statuscode" => 2,
                "message" => "Some Of Given Field Is Empty",
                "notValidInEditing" => $notValidInEditing,
                "notValidInNew" => $notValidInNew
            ));
        }

        DB::beginTransaction(); 

        try{

            $challanMst = tbl_challan_mst::find($challanMstId);

            $oldSellQuality = $challanMst->sell_quality_id;
            $oldCategory = tbl_sell_quality::getCategory($oldSellQuality);

            $challanMst->challan_date = $challanDate;
            $challanMst->customer_id = $company;
            $challanMst->broker_id = $broker;
            $challanMst->sell_quality_id = $quality;
            $challanMst->qty_unit = $unit;
            $challanMst->challan_type = $category;
            


            $financialYear = $this->getFinancialYearOfChallanDateInArray($challanDate);
            
            if((int)$challanNo != (int)$oldChallanNo){
                if(tbl_challan_mst::isChallanNoExists($challanNo,$financialYear['fromDate'], $financialYear['toDate'])){
                    return response()->json(array(
                        "status" => -1,
                        "statuscode" => 3,
                        "message" => "Challan No Already Existes"
                    ));
                }
            }

            $challanMst->challan_no = $challanNo;
            $challanMst->save();

            $n = count($challanDetailsIdsToBeDeleted);
        
            for($i=0; $i<$n; $i++){
                $challanDetailsEntry = tbl_challan_details::find($challanDetailsIdsToBeDeleted[$i]);
                $challanDetailsEntry->challan_details_status = false;
                $challanDetailsEntry->save();
            }

            $noExists = array();
            $noError = array();

            $n = count($challanDetails);
            for($i=0; $i<$n; $i++){
                $challanDetailsEntry = tbl_challan_details::find($challanDetails[$i]['challanDetailsId']);

                if($oldCategory != 3 && $category == 3){
                    $challanDetailsEntry->no = (int)$challanDetails[$i]['no']; 
                }
                else if($oldCategory == 3 && $category != 3){
                    // echo $challanDetails[$i]['no'];
                    // echo $category;
                    if(tbl_challan_details::isNoExists((int)$challanDetails[$i]['no'], $category, $financialYear['fromDate'], $financialYear['toDate'])){
                        //echo $challanDetails[$i]['no'];
                        array_push($noExists,(int)$challanDetails[$i]['no']);
                        continue;
                    }
                    $challanDetailsEntry->no = (int)$challanDetails[$i]['no'];
                }
                else if($oldCategory == 3 && $category == 3){
                    $challanDetailsEntry->no = (int)$challanDetails[$i]['no'];
                }
                else{
                    //oldcategory !=3 && new Category !=  3 &&
                    if($challanDetailsEntry->no != (int)$challanDetails[$i]['no']){
                        if(tbl_challan_details::isNoExists((int)$challanDetails[$i]['no'], $category, $financialYear['fromDate'], $financialYear['toDate'])){
                            array_push($noExists,(int)$challanDetails[$i]['no']);
                            continue;
                        }
                    }
                    $challanDetailsEntry->no = (int)$challanDetails[$i]['no'];
                } 

                $challanDetailsEntry->qty = (int)$challanDetails[$i]['qty'];
                $challanDetailsEntry->challan_type = $category;
                if(!($challanDetailsEntry->save())){
                    array_push($noError,(int)$challanDetails[$i]['no']);
                }
            }
            
            
            
            $n = count($newProductDetails);
            for($i=0; $i<$n; $i++){
                if($category != 3 && tbl_challan_details::isNoExists((int)$newProductDetails[$i]['no'], $category, $financialYear['fromDate'], $financialYear['toDate'])){
                    array_push($noExists,(int)$newProductDetails[$i]['no']);
                    continue;
                }
                $challanDetailsEntry = new tbl_challan_details();
                $challanDetailsEntry->no = $newProductDetails[$i]['no'];
                $challanDetailsEntry->qty = $newProductDetails[$i]['qty'];
                $challanDetailsEntry->challan_mst_id = $challanMstId;
                $challanDetailsEntry->challan_type = $category;
                $challanDetailsEntry->save();
            }

            if(count($noExists) == 0 && count($noError) == 0){
                DB::commit();
                return response()->json(array(
                    "status" => 1,
                    "message" => "Challan Updated Successfully"
                ));
            }
            else{
                DB::rollback();
                return response()->json(array(
                    "status" => -1,
                    "statuscode" => 4,
                    "message" => "Something Went Wrong",
                    "noExists" => $noExists,
                    "noError" => $noError
                ));
            }
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(array(
                "status" => -1,
                "statuscode" => 5,
                "message" => "Something Went Wrong"
            ));
        }
    }

    public function deleteChallan(Request $req, $challanMstId){
        DB::beginTransaction();
        try{
            tbl_challan_details::where("challan_mst_id", $challanMstId)->update(['challan_details_status'=>false]);
            
            $challan = tbl_challan_mst::find($challanMstId);
            $challanNo = $challan->challan_no;
            $challan->challan_mst_status = true;
            $challan->save();

            DB::commit();

            return response()->json(array(
                "status" => 1,
                "challanNo" => $challanNo,
                "message" => "Challan Deleted Successfully" 
            ));
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(array(
                "status" => -1,
                "message" => "Challan Deletation Failed" 
            ));
        }
    }
}