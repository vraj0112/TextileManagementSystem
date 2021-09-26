<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\tbl_credit;

class CreditController extends Controller
{
    public function insertCredit(Request $request)
    {
        $validated = validator($request->all(),[
            'creditDate' => 'required',
            'creditDesc' => 'required',
            'creditAmount' => 'required',
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );
            return response()->json($res);
        }

        $creditDate = $request->input('creditDate');
        $creditDesc = $request->input('creditDesc');
        $creditAmount = $request->input('creditAmount');

        if (tbl_credit::where('credit_date', "=", $creditDate)->where('credit_description', "=", $creditDesc)->where('credit_amount', "=", $creditAmount)->exists()) {
            $res = array(
                "status" => 0,
                "message" => "Record Already Exists",
                "errors" => null
            );
            return response()->json($res);
        }

        DB::beginTransaction();
        try{
            $Credit = new tbl_credit();
            $Credit->credit_date = $creditDate;
            $Credit->credit_description = $creditDesc;
            $Credit->credit_amount = $creditAmount;

            $Credit->save();
        }catch(QueryException $e){
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
            "message" => "Credit Details Added Successfully.",
            "errors" => null
        );

        return response()->json($res, 200);
    }

    public function getAllCreditDetails(Request $req){
        $paginate = request("paginate", 10);
        $search_term = request("search", "");
        $search_term = trim($search_term);
        $search_term = "%$search_term%";
        $sort_field = request("sortfield");
        $sort_direction = request("sortdirection");

        if(!in_array($sort_direction, ['asc', 'desc'])){
            $sort_direction = "asc";
        }

        if(!in_array($sort_field, ['credit_id', 'credit_date', 'credit_description', 'credit_amount'])){
            $sort_field = "credit_id";
        }

        return (tbl_credit::select('credit_id', 'credit_date', 'credit_description', 'credit_amount', 'credit_status')
        ->where('credit_status', '=', 1)
        ->where(function($query) use ($search_term)
        {
            $query->where('credit_date', 'like', $search_term)
            ->orWhere('credit_description', 'like', $search_term)
            ->orWhere('credit_amount', 'like', $search_term);
        })
        ->orderBy($sort_field, $sort_direction)    
        ->paginate($paginate));
    }

    public function updateCreditDetail(Request $req, $creditId){

        $validated = validator($req->all(),[
            'creditDateDetail' => 'required',
            'creditDescDetail' => 'required',
            'creditAmountDetail' => 'required'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );
            return response()->json($res);
        }

        $creditDateDetail = $req->input("creditDateDetail");
        $creditDescDetail = $req->input("creditDescDetail");
        $creditAmountDetail = $req->input("creditAmountDetail");

        $isSameDetail = tbl_credit::where('credit_date', '=', $creditDateDetail)
        ->where("credit_description", '=', $creditDescDetail)
        ->where("credit_amount", '=', $creditAmountDetail)
        ->where("credit_status", '=', 1)->first();

        if(is_null($isSameDetail)){

            $detail = tbl_credit::find($creditId);
            $detail->credit_date = $creditDateDetail;
            $detail->credit_description= $creditDescDetail;
            $detail->credit_amount= $creditAmountDetail;
            
            if($detail->save()){
                return response()->json(array(
                    "status" => 1,
                    "message"=> "Credit Updated Successfully"
                ));
            }
            else{
                return response()->json(array(
                    "status" => -1,
                    "message"=> "Updation Failed"
                ));
            }
        }
        else{
            return response()->json(array(
                "status" => 0,
                "message"=> "Please Update the fields"
            ));
        }
    }

    public function deleteDetail(Request $req, $creditId)
    {
        tbl_credit::where('credit_id', '=', $creditId)->update(['credit_status' => 0]);
        return response()->json(array(
            "status"=> 1,
            "message"=> "Credit Deleted Successfully"
        ));
    }
}