<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_invoice_mst;
use App\Models\tbl_customer;
use App\Models\tbl_challan_mst;
use App\Models\tbl_challan_details;
use App\Models\tbl_sell_quality;
use App\Models\tbl_broker;
use App\Models\tbl_bank_details;
use App\Models\tbl_gst_code;
use PDF;

class PDFController extends Controller
{
    public function generateDirectInvoicePDF(Request $req, $invoice_id){
        $data = tbl_invoice_mst::with(['challanMstForInvoice','bank'])
            ->where('invoice_mst_status', true)
            ->where('invoice_mst_id', $invoice_id)
            ->select('invoice_mst_id', 'invoice_date', 'no_of_units', 'rate', 'gst_percentage', 'due_date','bank_details_id')
            ->first();

            
            $gstEntry = tbl_gst_code::find(intval($data['challanMstForInvoice']['customer_relation']['customer_gst_code']));
            $state = $gstEntry->state_name;
            
            // return $state;

        $invoice = array(
            "customer_company_name" => $data['challanMstForInvoice']['customer_relation']['customer_company_name'],
            "customer_address" => $data['challanMstForInvoice']['customer_relation']['customer_address'],
            "customer_gst_no" => $data['challanMstForInvoice']['customer_relation']['customer_gst_no'],
            "customer_gst_code"=> $data['challanMstForInvoice']['customer_relation']['customer_gst_code'],
            "broker_name"=> $data['challanMstForInvoice']['broker']['broker_name'],
            "bank_name"=>$data['bank']['bank_name'],
            "branch_name"=> $data['bank']['branch_name'],
            "account_no"=> $data['bank']['account_no'],
            "ifsc_code"=> $data['bank']['ifsc_code'],
            "invoice_date"=> $data['invoice_date'],
            "due_date"=> $data['due_date'],
            "invoice_mst_id"=> intval($data['invoice_mst_id']),
            "challan_no"=> $data['challanMstForInvoice']['challan_no'],
            "quality_name"=> $data['challanMstForInvoice']['quality']['quality_name'],
            "total_qty"=> $data['challanMstForInvoice']['total_qty'],
            "qty_unit"=> $data['challanMstForInvoice']['qty_unit'],
            "state_name"=> $state,
            "rate"=> $data['rate'],
            "gst_percentage" => $data['gst_percentage']
        );

        $invoice = (object)$invoice;

        $pdf = PDF::loadView('invoicePDF', array("invoice" => $invoice, "piecesCount" => $data['no_of_units']));
        return $pdf->stream('Invoice - ' . $invoice_id . '.pdf');
    }

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
    
    public function generateInvoicePDF(Request $req, $invoice_id)
    {
        $piecesCount = tbl_invoice_mst::join('tbl_challan_msts','tbl_invoice_msts.invoice_mst_id',"=","tbl_challan_msts.challan_no")
        ->join('tbl_challan_details','tbl_challan_msts.challan_mst_id',"=",'tbl_challan_details.challan_mst_id')
        ->where('tbl_challan_details.challan_mst_id',"=",$invoice_id)
        ->count();

        $invoice = tbl_invoice_mst::join('tbl_challan_msts','tbl_invoice_msts.invoice_mst_id',"=","tbl_challan_msts.challan_no")
        ->join('tbl_challan_details','tbl_challan_msts.challan_mst_id',"=",'tbl_challan_details.challan_mst_id')
        ->join('tbl_customers','tbl_challan_msts.customer_id','=','tbl_customers.customer_id')
        ->join('tbl_brokers','tbl_challan_msts.broker_id','=','tbl_brokers.broker_id')
        ->join('tbl_bank_details','tbl_invoice_msts.bank_details_id','=','tbl_bank_details.bank_details_id')
        ->join('tbl_sell_qualities','tbl_challan_msts.sell_quality_id','=','tbl_sell_qualities.sell_quality_id')
        ->join('tbl_gst_codes','tbl_customers.customer_gst_code',"=",'tbl_gst_codes.gst_code')
        ->select('customer_company_name','customer_address','customer_gst_no','customer_gst_code','broker_name','bank_name','branch_name','account_no','ifsc_code',
        'invoice_date','due_date','invoice_mst_id','challan_no','quality_name','total_qty','qty_unit','state_name','rate','tbl_invoice_msts.gst_percentage')
        ->where('tbl_invoice_msts.invoice_mst_id',"=",$invoice_id)
        ->where('tbl_invoice_msts.invoice_mst_status',"=",1)
        ->first();

        $pdf = PDF::loadView('invoicePDF', array("invoice" => $invoice, "piecesCount" => $piecesCount));
        return $pdf->stream('Invoice - ' . $invoice_id . '.pdf');
    }
}
