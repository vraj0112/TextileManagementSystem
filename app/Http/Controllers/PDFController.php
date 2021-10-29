<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_customer;
use App\Models\tbl_challan_mst;
use App\Models\tbl_challan_details;
use App\Models\tbl_sell_quality;
use App\Models\tbl_broker;
use PDF;

class PDFController extends Controller
{

    public function roundTo2($num){
        var_dump($num);
        $floorNum = floor($num);

        if($num-$floorNum == 0){
            return $num.".00";
        }
        else{
            return round((float)$num, 2);
        }
    }
    // public function generateChallanPDF(Request $req, $challan_mst_id)
    // {

    //     // $data = tbl_challan_details::join('tbl_challan_msts', 'tbl_challan_details.challan_mst_id', "=", 'tbl_challan_msts.challan_mst_id')
    //     // ->join('tbl_sell_qualities', 'tbl_challan_msts.sell_quality_id', "=", 'tbl_sell_qualities.sell_quality_id')
    //     // ->join('tbl_customers', 'tbl_challan_msts.customer_id', "=", 'tbl_customers.customer_id')
    //     // ->join('tbl_brokers', 'tbl_challan_msts.broker_id', "=", 'tbl_brokers.broker_id')
    //     // ->select('tbl_challan_details.challan_mst_id', 'challan_no', 'challan_date', 'customer_company_name', 'customer_address', 'broker_name', 'customer_gst_no', 'quality_name')
    //     // ->where('tbl_challan_msts.challan_no', '=', $challan_mst_id)
    //     // ->where('tbl_challan_msts.challan_mst_status', "=", 1)
    //     // ->where('tbl_challan_details.challan_details_status', "=", 1)
    //     // ->where('tbl_sell_qualities.sell_quality_status', "=", 1)
    //     // ->where('tbl_customers.customer_status', "=", 1)
    //     // ->where('tbl_brokers.broker_status', "=", 1)
    //     // ->get();

    //     // $product = tbl_challan_details::join('tbl_challan_msts', 'tbl_challan_details.challan_mst_id', "=", 'tbl_challan_msts.challan_mst_id')
    //     // ->select('no', 'qty')
    //     // ->where('tbl_challan_msts.challan_mst_status', "=", 1)
    //     // ->where('tbl_challan_details.challan_details_status', "=", 1);
        
    //     return $data;
        
    //     $pdf = PDF::loadView('challanPDF',["data" => $data, "product" => $product]);
    //     return $pdf->stream('Challan'.'-'.$data->challan_no.'.pdf');
    // }

    public function generateChallanPDF(Request $req, $challanId){

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
        
        $challanDataFetched = tbl_challan_mst::with("challan_details:challan_mst_id,challan_details_id,no,qty", "customer_relation:customer_id,customer_company_name,customer_address,customer_gst_no", "broker:broker_id,broker_name", "quality:sell_quality_id,quality_name,sell_quality_category_id")
        ->where("tbl_challan_msts.challan_mst_id", $challanId)
        ->first();

        if(is_null($challanDataFetched)){
            return response()->json(array(
                "status" => 0,
                "message" => "Challan Not Found"
            ));
        }

        $challanDetials01 = [];
        $challanDetials02 = [];
        $challanDetials03 = [];
        $challanDetials04 = [];
        $sum01 = 0;
        $sum02 = 0;
        $sum03 = 0;
        $sum04 = 0;
        $i=0;

        for($i; $i<count($challanDataFetched->challan_details) && $i < 12; $i++){
            array_push($challanDetials01, array('no'=>$challanDataFetched['challan_details'][$i]['no'], 'qty'=>number_format($challanDataFetched['challan_details'][$i]['qty'], 2, '.', '')));
            $sum01 += $challanDataFetched['challan_details'][$i]['qty'];
        }

        for($i; $i<count($challanDataFetched->challan_details) && $i < 24; $i++){
            array_push($challanDetials02, array('no'=>$challanDataFetched['challan_details'][$i]['no'], 'qty'=>number_format($challanDataFetched['challan_details'][$i]['qty'], 2, '.', '')));
            $sum02 += $challanDataFetched['challan_details'][$i]['qty'];
        }

        for($i; $i<count($challanDataFetched->challan_details) && $i < 36; $i++){
            array_push($challanDetials03, array('no'=>$challanDataFetched['challan_details'][$i]['no'], 'qty'=>number_format($challanDataFetched['challan_details'][$i]['qty'], 2, '.', '')));
            $sum03 += $challanDataFetched['challan_details'][$i]['qty'];
        }

        for($i; $i<count($challanDataFetched->challan_details) && $i < 48; $i++){
            array_push($challanDetials04, array('no'=>$challanDataFetched['challan_details'][$i]['no'], 'qty'=>number_format($challanDataFetched['challan_details'][$i]['qty'], 2, '.', '')));
            $sum04 += $challanDataFetched['challan_details'][$i]['qty'];
        }
        $sum = array('sum01'=> number_format($sum01,2,'.',''), 'sum02'=>number_format($sum02,2,'.',''), 'sum03'=>number_format($sum03,2,'.',''), 'sum04'=>number_format($sum04,2,'.',''), "totalSum" => number_format($sum01+$sum02+$sum03+$sum04, 2, ".", ""));


        for($i; $i < 12; $i++){
            array_push($challanDetials01, array('no'=> "", 'qty'=>""));
        }
        for($i; $i < 24; $i++){
            array_push($challanDetials02, array('no'=> "", 'qty'=>""));
        }
        for($i; $i < 36; $i++){
            array_push($challanDetials03, array('no'=> "", 'qty'=>""));
        }
        for($i; $i < 48; $i++){
            array_push($challanDetials04, array('no'=> "", 'qty'=>""));
        }

        $challanDetails = array(
            "challanDetails01" => $challanDetials01,
            "challanDetails02" => $challanDetials02,
            "challanDetails03" => $challanDetials03,
            "challanDetails04" => $challanDetials04,
        );

        $challanData = array(
            "challanid" => (int)$challanId,
            "challanno" => $challanDataFetched->challan_no,
            "challandate" => $challanDataFetched->challan_date,
            "customer" => $challanDataFetched->customer_relation,
            "broker"=>$challanDataFetched->broker,
            "quality" => $challanDataFetched->quality,
            "unit" => $challanDataFetched->qty_unit,
            "challandetails"=>$challanDetails,
            "sum" => $sum,
            "pieces" => count($challanDataFetched->challan_details)
        );

        $pdf = PDF::loadView('challanPDF',["challanData" => $challanData]);
        return $pdf->stream('Challan'.' - '.$challanData["challanno"].'.pdf');
    }
}
