<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class tbl_inward_mst extends Model
{
    use HasFactory;

    public function getInwardMstDateAttribute($value){
        return (Carbon::parse($value)->format('d-m-Y'));
    }

    public function setInwardMstInvoiceNoAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['inward_mst_invoice_no'] = strtoupper($value);
    }

    public function inward_details(){
        return $this->hasOne('App\Models\tbl_inward_details', 'inward_mst_id', 'inward_mst_id')->with('quality:inward_quality_id,inward_quality_category_id,quality_name');
    }
    
    public function getBroker(){
        return $this->hasOne('App\Models\tbl_broker', "broker_id", "inward_mst_broker_id");
    }

    public function getVendor(){
        return $this->hasOne('App\Models\tbl_vendor', 'vendor_id', 'inward_mst_vendor_id');
    }

}
