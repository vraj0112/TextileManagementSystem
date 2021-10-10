<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class tbl_challan_mst extends Model
{
    use HasFactory;

    protected $primaryKey = "challan_mst_id";

    function getChallanDateAttribute($value){
        return (Carbon::parse($value)->format('d-m-Y'));
    }

    function customer(){
        return $this->hasOne('App\Models\tbl_customer', 'customer_id', 'customer_id');
    }

    function broker(){
        return $this->hasOne('App\Models\tbl_broker', 'broker_id', 'broker_id');
    }

    function challan_details(){
        return $this->hasMany('App\Models\tbl_challan_details', 'challan_mst_id', 'challan_mst_id')->where('challan_details_status', true);
    }

    function customer_relation(){
        return $this->belongsTo('App\Models\tbl_customer', 'customer_id', 'customer_id');
    }

    // function category(){
    //     return $this
    //         ->hasOneThrough( 'App\Models\tbl_sell_quality','App\Models\tbl_sell_quality_category','sell_quality_category_id', 'sell_quality_id','sell_quality_id','sell_quality_category_id');
    // }

    function quality(){
        return $this->hasOne('App\Models\tbl_sell_quality', 'sell_quality_id', "sell_quality_id")->with('category:sell_quality_category_id,sell_category_name');
    }

    public static function isChallanNoExists($challanNo, $fromDate, $toDate){
        return tbl_challan_mst::where('challan_no', '=', $challanNo)->where('challan_mst_status', '=', 1)->whereBetween('challan_date', [$fromDate, $toDate])->exists();
    }
}
