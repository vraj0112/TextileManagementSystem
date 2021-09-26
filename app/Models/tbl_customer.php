<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_customer extends Model
{
    use HasFactory;

    protected $primaryKey = "customer_id";

    public function setCustomerCompanyNameAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['customer_company_name'] = ucwords(strtolower($value));
    }

    /*public function setCustomerCompanyContactAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['customer_contact_no'] = ucwords(strtolower($value));
    }*/

    public function setCustomerEmailAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['customer_email'] = strtolower($value);
    }

    public function setCustomerGstNoAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['customer_gst_no'] = strtoupper($value);
    }

    public function setCustomerGstCodeAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['customer_gst_code'] = strtoupper($value);
    }

    public function setCustomerAddressAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['customer_address'] = ucwords(strtolower($value));
    }
}
