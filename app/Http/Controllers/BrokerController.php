<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\tbl_broker;

class BrokerController extends Controller
{
    public function getAllBrokers(Request $request){
        $paginate = request("paginate", 10);
        $search_term = request("search", "");
        $search_term = trim($search_term);
        $search_term = "%$search_term%";
        $sort_field = request("sortfield");
        $sort_direction = request("sortdirection");

        if(!in_array($sort_direction, ['asc', 'desc'])){
            $sort_direction = "asc";
        }

        if(!in_array($sort_field, ['broker_id', 'broker_name', 'broker_contact_no'])){
            $sort_field = "broker_id";
        }

        return (tbl_broker::select('broker_id', 'broker_name', 'broker_contact_no')
        ->where('broker_status', '=', 1)
        ->where(function($query) use ($search_term){
            $query->where('broker_name', 'like', $search_term)
                ->orWhere('broker_contact_no', 'like', $search_term);
        })
        ->orderBy($sort_field, $sort_direction)
        ->paginate($paginate));
    }

    public function insertBroker(Request $request){
        $validated = validator($request->all(),[
            'brokerName' => 'required | regex:/^[\pL\s]+$/u | max:70',
            'contactNo' => 'required | numeric | digits:10'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );

            return response()->json($res);
        }

        $brokerName = $request->input('brokerName');
        $contactNo = $request->input('contactNo');

        if (tbl_broker::where('broker_contact_no', "=", $contactNo)->exists()) {
            $res = array(
                "status" => 0,
                "message" => "Record Already Exists",
                "errors" => null
            );
            return response()->json($res);
        }

        DB::beginTransaction();
        try{
            $broker = new tbl_broker();
            $broker->broker_name = $brokerName;
            $broker->broker_contact_no = $contactNo;

            $broker->save();
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
            "message" => "Broker Details Added Successfully.",
            "errors" => null
        );

        return response()->json($res, 200);
    }

    public function updateBroker(Request $request, $brokerId){
        $validated = validator($request->all(),[
            'editBrokerName' => 'required | regex:/^[\pL\s]+$/u | max:70',
            'editContactNo' => 'required | numeric | digits:10'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );

            return response()->json($res);
        }

        $editBrokerName = $request->input('editBrokerName');
        $editContactNo = $request->input('editContactNo');

        if(tbl_broker::where('broker_contact_no', '=', $editContactNo)->exists()){
            $res = array(
                "status" => 0,
                "message" => "Record Already Exists",
                "errors" => null
            );
            return response()->json($res);
        }

        tbl_broker::where('broker_id', '=', $brokerId)->update(['broker_name'=>$editBrokerName, 'broker_contact_no'=>$editContactNo]);
        $res = array(
            "status" => 1,
            "message" => "Sell Quality Updated Successfully.",
            "errors" => null
        );
        return response()->json($res);
    }

    public function deleteBroker(Request $request, $brokerId){
        tbl_broker::where('broker_id', '=', $brokerId)->update(["broker_status"=>0]);
        $res = array(
            "status" => 1,
            "message" => "Broker Details Deleted Successfully.",
            "errors" => null
        );
        return response()->json($res);
    }

    public function getBrokers(Request $req)
    {
        return tbl_broker::select("broker_id", "broker_name", "broker_contact_no")->where("broker_status",true)->get();
    }
}