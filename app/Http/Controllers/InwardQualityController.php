<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_inward_quality_category;
use App\Models\tbl_inward_quality;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class InwardQualityController extends Controller
{
    public function getQualityCategories(){
        $qualityCategoryQuery = DB::table('tbl_inward_quality_categories')->where('inward_quality_category_status','=',1)->get()->toArray();

        $response = array();
        foreach ($qualityCategoryQuery as $qualityCategories){
            array_push($response, array(
                'qualityCategoryId' => $qualityCategories->inward_quality_category_id,
                'qualityCategoryName' => $qualityCategories->inward_category_name
            ));
        }
        return response()->json(["qualityCategories"=>$response]);
    }

    public function getAllInwardQualities(Request $request){
        $paginate = request("paginate", 10);
        $search_term = request("search", "");
        $search_term = trim($search_term);
        $search_term = "%$search_term%";
        $sort_field = request("sortfield");
        $sort_direction = request("sortdirection");

        if(!in_array($sort_direction, ['asc', 'desc'])){
            $sort_direction = "asc";
        }

        if(!in_array($sort_field, ['inward_quality_id', 'inward_category_name', 'quality_name'])){
            $sort_field = "inward_quality_id";
        }

        return  (tbl_inward_quality::join('tbl_inward_quality_categories', 'tbl_inward_qualities.inward_quality_category_id', '=', 'tbl_inward_quality_categories.inward_quality_category_id')
        ->select('tbl_inward_qualities.inward_quality_id', 'tbl_inward_quality_categories.inward_quality_category_id' ,'inward_category_name', 'quality_name')
        ->where('inward_quality_status', '=', 1)
        ->where(function($query) use ($search_term){
            $query->where('quality_name', 'like', $search_term)
                ->orWhere('inward_category_name', 'like', $search_term);
        })
        ->orderBy($sort_field, $sort_direction)    
        ->paginate($paginate));

    }

    public function insertInwardQuality(Request $request){
        $validated = validator($request->all(),[
            'qualityCategoryId' => 'required | numeric | between:1,2',
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

        if (tbl_inward_quality::where('quality_name', "=", $qualityName)->where('inward_quality_category_id', "=", $qualityCategoryId)->exists()) {
            $res = array(
                "status" => 0,
                "message" => "Record Already Exists",
                "errors" => null
            );
            return response()->json($res);
        }

        DB::beginTransaction();
        try{
            $inwardQuality = new tbl_inward_quality();
            $inwardQuality->quality_name = $qualityName;
            $inwardQuality->inward_quality_category_id = $qualityCategoryId;
            
            $inwardQuality->save();

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
            "message" => "Inward Quality Added Successfully.",
            "errors" => null
        );

        return response()->json($res, 200);
    }

    public function updateInwardQuality(Request $request, $inwardQualityId){
        $validated = validator($request->all(),[
            'editQualityCategoryId' => 'required | numeric | between:1,2',
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

        if (tbl_inward_quality::where('quality_name', "=", $editQualityName)->where('inward_quality_category_id', "=", $editQualityCategoryId)->exists()) {
            $res = array(
                "status" => 0,
                "message" => "Record Already Exists",
                "errors" => null
            );
            return response()->json($res);
        }

        tbl_inward_quality::where('inward_quality_id', '=', $inwardQualityId)->update(['quality_name'=>$editQualityName, 'inward_quality_category_id'=>$editQualityCategoryId]);
        $res = array(
            "status" => 1,
            "message" => "Inward Quality Updated Successfully.",
            "errors" => null
        );
        return response()->json($res);
    }

    public function deleteInwardQuality(Request $request, $inwardQualityId){
        tbl_inward_quality::where('inward_quality_id', '=', $inwardQualityId)->update(["inward_quality_status"=>0]);
        $res = array(
            "status" => 1,
            "message" => "Inward Quality Deleted Successfully.",
            "errors" => null
        );
        return response()->json($res);
    }

    public function getSelectedProductQualities(Request $request, $inwardQualityCategoryId){
        return tbl_inward_quality::select("inward_quality_id", "quality_name")
        ->where("inward_quality_status", true)
        ->where('inward_quality_category_id', '=', $inwardQualityCategoryId)
        ->get();
    }

    public function getProductQualityCategories(Request $req){
        return (tbl_inward_quality_category::select("inward_quality_category_id", "inward_category_name")->where("inward_quality_category_status",true)->get());
    }
}
