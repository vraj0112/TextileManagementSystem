<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\tbl_bank_details;

class BankDetailsController extends Controller
{
    public function insertBankDetails(Request $request)
    {
        $validated = validator($request->all(),[
            'bankName' => 'required | regex:/^[\pL\s]+$/u | max:50',
            'branchName' => 'required | regex:/^[\pL\s]+$/u | max:50',
            'ifscCode' => 'required | alpha_num | min:11 | max:11',
            'accNo' => 'required | digits_between:9,18'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );
            return response()->json($res);
        }

        $bankName = $request->input('bankName');
        $branchName = $request->input('branchName');
        $ifscCode = $request->input('ifscCode');
        $accNo = $request->input('accNo');

        if (tbl_bank_details::where('account_no', "=", $accNo)->exists()) {
            $res = array(
                "status" => 0,
                "message" => "Record Already Exists",
                "errors" => null
            );
            return response()->json($res);
        }

        DB::beginTransaction();
        try{
            $bankDetails = new tbl_bank_details();
            $bankDetails->bank_name = $bankName;
            $bankDetails->branch_name = $branchName;
            $bankDetails->ifsc_code = $ifscCode;
            $bankDetails->account_no = $accNo;

            $bankDetails->save();
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
            "message" => "Bank Details Added Successfully.",
            "errors" => null
        );

        return response()->json($res, 200);
    }

    public function getAllBankDetails(Request $req){

        $paginate = request("paginate", 10);
        $search_term = request("search", "");
        $search_term = trim($search_term);
        $search_term = "%$search_term%";
        $sort_field = request("sortfield");
        $sort_direction = request("sortdirection");

        if(!in_array($sort_direction, ['asc', 'desc'])){
            $sort_direction = "asc";
        }

        if(!in_array($sort_field, ['bank_details_id', 'bank_name', 'branch_name', 'ifsc_code', 'account_no'])){
            $sort_field = "bank_details_id";
        }

        return (tbl_bank_details::select('bank_details_id', 'bank_name', 'branch_name', 'ifsc_code', 'account_no', 'bank_details_status')
        ->where('bank_details_status', '=', 1)
        ->where(function($query) use ($search_term)
        {
            $query->where('bank_name', 'like', $search_term)
            ->orWhere('branch_name', 'like', $search_term)
            ->orWhere('ifsc_code', 'like', $search_term)
            ->orWhere('account_no', 'like', $search_term);
        })
        ->orderBy($sort_field, $sort_direction)
        ->paginate($paginate));
    }

    public function updateBankDetail(Request $req, $bankDetailId){

        $validated = validator($req->all(),[
            'bankNameDetail' => 'required | regex:/^[\pL\s]+$/u | max:50',
            'branchNameDetail' => 'required | regex:/^[\pL\s]+$/u | max:50',
            'ifscCodeDetail' => 'required | alpha_num | min:11 | max:11',
            'accNoDetail' => 'required | digits_between:9,18'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );
            return response()->json($res);
        }

        $bankNameDetail = $req->input("bankNameDetail");
        $branchNameDetail = $req->input("branchNameDetail");
        $ifscCodeDetail = $req->input("ifscCodeDetail");
        $accNoDetail = $req->input("accNoDetail");

        $isSameBankDetail = tbl_bank_details::where('bank_name', '=', $bankNameDetail)
        ->where("branch_name", '=', $branchNameDetail)
        ->where("ifsc_code", '=', $ifscCodeDetail)
        ->where("account_no", '=', $accNoDetail)
        ->where("bank_details_status", '=', 1)->first();

        if(is_null($isSameBankDetail)){

            $bankdetail = tbl_bank_details::find($bankDetailId);
            $bankdetail->bank_name = $bankNameDetail;
            $bankdetail->branch_name= $branchNameDetail;
            $bankdetail->ifsc_code = $ifscCodeDetail;
            $bankdetail->account_no= $accNoDetail;
            
            if($bankdetail->save()){
                return response()->json(array(
                    "status" => 1,
                    "message"=> "Bank Detail Updated Successfully"
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

    public function deleteBankDetail(Request $req, $bankDetailId)
    {
        tbl_bank_details::where('bank_details_id', '=', $bankDetailId)->update(['bank_details_status' => 0]);
        return response()->json(array(
            "status"=> 1,
            "message"=> "Bank Details Deleted Successfully"
        ));
    }

    public function getBankInfo(Request $req){
        return (tbl_bank_details::select('bank_name', 'account_no', 'bank_details_id')->where('bank_details_status','=',1)->get());
    }

    public function getBankBranch(Request $req, $bankId){
        return (tbl_bank_details::select('branch_name')->where('bank_details_id', '=', $bankId)->where('bank_details_status', '=', 1)->first());
    }
}
