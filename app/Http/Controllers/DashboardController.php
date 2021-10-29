<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\tbl_inward_mst;
use App\Models\tbl_invoice_mst;
use App\Models\tbl_credit;
use App\Models\tbl_expense;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboardCalculations(Request $req)
    {
        $data = array();

        $financialYear = $this -> getFinancialYear(Carbon::now());
        array_push($data, $financialYear);

        $inwardCount = tbl_inward_mst::whereBetween("inward_mst_date",[$financialYear['fromDate'],$financialYear['toDate']])->where("inward_mst_status","=","1")->count();
        array_push($data, $inwardCount);

        $invoiceCount = tbl_invoice_mst::whereBetween("invoice_date",[$financialYear['fromDate'],$financialYear['toDate']])->where("invoice_mst_status","=","1")->count();
        array_push($data, $invoiceCount);

        $creditAmount = tbl_credit::whereBetween("credit_date",[$financialYear['fromDate'],$financialYear['toDate']])->where("credit_status","=","1")->sum('credit_amount');
        array_push($data, $creditAmount);

        $expenseAmount = tbl_expense::whereBetween("expense_date",[$financialYear['fromDate'],$financialYear['toDate']])->where("expense_status","=","1")->sum('expense_amount');
        array_push($data, $expenseAmount);

        return $data;
    }

    public function getFinancialYear($date){

        $splitDate = explode("-",$date);
        $Month = $splitDate[1];
        $Year = $splitDate[0];
        
        $fromDate = $Year;
        $toDate = $Year;
        if((int)$Month<4){
            $fromDate = (int)$Year - 1;
        }else{
            $toDate = (int)$Year + 1;
        }
        
        $fromDate = $fromDate."-04-01";
        $toDate = $toDate."-03-31";
        
        return array(
            "fromDate" => $fromDate, 
            "toDate" => $toDate
        );
    }

    // public function getCreditRecord(){
    //     $toDate = Carbon::now();
    //     $data = array();
    //     for($i=0; $i < 10; $i++){
    //         $fromDate = $toDate->subDays(2);
    //         $data[strval($toDate)] = tbl_credit::whereBetween('credit_date', [$fromDate, $toDate])->where('credit_status', true)->sum('credit_amount');
    //         $toDate = $fromDate;
    //     }

    //     return response()->json(array(
    //         "status" => 1,
    //         "data" => $data
    //     ));
    // }
}

