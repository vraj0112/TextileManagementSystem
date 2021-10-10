<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_sell_quality_category;
use App\Models\tbl_sell_quality;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SellQualityController extends Controller
{
    public function getQualityCategories(){
        $qualityCategoryQuery = DB::table('tbl_sell_quality_categories')->where('sell_quality_category_status','=',1)->get()->toArray();

        $response = array();
        foreach ($qualityCategoryQuery as $qualityCategories){
            array_push($response, array(
                'qualityCategoryId' => $qualityCategories->sell_quality_category_id,
                'qualityCategoryName' => $qualityCategories->sell_category_name
            ));
        }
        return response()->json(["qualityCategories"=>$response]);
    }

    public function getAllSellQualities(Request $request){
        $paginate = request("paginate", 10);
        $search_term = request("search", "");
        $search_term = trim($search_term);
        $search_term = "%$search_term%";
        $sort_field = request("sortfield");
        $sort_direction = request("sortdirection");

        if(!in_array($sort_direction, ['asc', 'desc'])){
            $sort_direction = "asc";
        }

        if(!in_array($sort_field, ['sell_quality_id', 'sell_category_name', 'quality_name'])){
            $sort_field = "sell_quality_id";
        }

        return  (tbl_sell_quality::join('tbl_sell_quality_categories', 'tbl_sell_qualities.sell_quality_category_id', '=', 'tbl_sell_quality_categories.sell_quality_category_id')
        ->select('tbl_sell_qualities.sell_quality_id', 'tbl_sell_quality_categories.sell_quality_category_id' ,'sell_category_name', 'quality_name')
        ->where('sell_quality_status', '=', 1)
        ->where(function($query) use ($search_term){
            $query->where('quality_name', 'like', $search_term)
                ->orWhere('sell_category_name', 'like', $search_term);
        })
        ->orderBy($sort_field, $sort_direction)    
        ->paginate($paginate));

    }

    public function insertSellQuality(Request $request){
        $validated = validator($request->all(),[
            'qualityCategoryId' => 'required | numeric | between:1,3',
            'qualityName' => 'required | regex:/[a-zA-Z0-9\s]+/ | max:50'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );

            return response()->json($res);
        }

        $qualityName = $request->input("qualityName");
        $qualityCategoryId = $request->input("qualityCategoryId");

        if (tbl_sell_quality::where('quality_name', "=", $qualityName)->where('sell_quality_category_id', "=", $qualityCategoryId)->exists()) {
            $res = array(
                "status" => 0,
                "message" => "Record Already Exists",
                "errors" => null
            );
            return response()->json($res);
        }

        DB::beginTransaction();
        try{
            $sellQuality = new tbl_sell_quality();
            $sellQuality->quality_name = $qualityName;
            $sellQuality->sell_quality_category_id = $qualityCategoryId;
            
            $sellQuality->save();

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
            "message" => "Sell Quality Added Successfully.",
            "errors" => null
        );

        return response()->json($res, 200);
    }

    public function updateSellQuality(Request $request, $sellQualityId){
        $validated = validator($request->all(),[
            'editQualityCategoryId' => 'required | numeric | between:1,3',
            'editQualityName' => 'required | regex:/[a-zA-Z0-9\s]+/ | max:50'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );

            return response()->json($res);
        }

        $editQualityCategoryId = $request->input('editQualityCategoryId');
        $editQualityName = $request->input('editQualityName');

        if (tbl_sell_quality::where('quality_name', "=", $editQualityName)->where('sell_quality_category_id', "=", $editQualityCategoryId)->exists()) {
            $res = array(
                "status" => 0,
                "message" => "Record Already Exists",
                "errors" => null
            );
            return response()->json($res);
        }

        tbl_sell_quality::where('sell_quality_id', '=', $sellQualityId)->update(['quality_name'=>$editQualityName, 'sell_quality_category_id'=>$editQualityCategoryId]);
        $res = array(
            "status" => 1,
            "message" => "Sell Quality Updated Successfully.",
            "errors" => null
        );
        return response()->json($res);
    }

    public function deleteSellQuality(Request $request, $sellQualityId){
        tbl_sell_quality::where('sell_quality_id', '=', $sellQualityId)->update(["sell_quality_status"=>0]);
        $res = array(
            "status" => 1,
            "message" => "Sell Quality Deleted Successfully.",
            "errors" => null
        );
        return response()->json($res);
    }

    public function getSellQualityOfGivenCategory(Request $request, $sellQualityCategoryId){
        return tbl_sell_quality::select("sell_quality_id", "quality_name")
        ->where("sell_quality_status", true)
        ->where('sell_quality_category_id', '=', $sellQualityCategoryId)
        ->get();
    }

}
