<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_vendor extends Model
{
    use HasFactory;

    protected $primaryKey = "vendor_id";

    public function setVendorCompanyNameAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['vendor_company_name'] = ucwords(strtolower($value));
    }

    /*public function setvendorCompanyContactAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['vendor_contact_no'] = ucwords(strtolower($value));
    }*/

    public function setVendorEmailAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['vendor_email'] = strtolower($value);
    }

    public function setVendorGstNoAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['vendor_gst_no'] = strtoupper($value);
    }

    public function setVendorGstCodeAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['vendor_gst_code'] = strtoupper($value);
    }

    public function setVendorAddressAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['vendor_address'] = ucwords(strtolower($value));
    }
}
