<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_challan_details extends Model
{
    use HasFactory;
    protected $primaryKey = "challan_details_id";


    public static function isNoExists($no, $type, $fromDate, $toDate){
        return tbl_challan_details::join("tbl_challan_msts", 'tbl_challan_msts.challan_mst_id', "=", "tbl_challan_details.challan_mst_id")
            ->where('no', $no)
            ->where('tbl_challan_details.challan_type', $type)
            ->where('challan_details_status', true)
            ->whereBetween('challan_date', [$fromDate, $toDate])
            ->exists();
        
        // return tbl_challan_details::with('challan_mst')->where('no', $no)->where('challan_type', $type)->where('challan_details_status', true)->get();
    }

    
}
