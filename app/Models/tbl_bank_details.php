<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_bank_details extends Model
{
    use HasFactory;
    protected $primaryKey = "bank_details_id";
    protected $table = "tbl_bank_details";
    protected $fillable = [
            'bank_name',
            'branch_name',
            'account_no',
            'ifsc_code'
    ];

    public function setBankNameAttribiute($value){
        $value = preg_replace('/\s+/',' ', $value);
        $this->attrributes['bank_name'] = ucwords(strtolower($value));
    }

    public function setBranchNameAttribiute($value){
        $value = preg_replace('/\s+/',' ', $value);
        $this->attrributes['branch_name'] = ucwords(strtolower($value));
    }

    public function setAccountNoAttribiute($value){
        $value = preg_replace('/\s+/',' ', $value);
        $this->attrributes['account_name'] = ucwords(strtolower($value));
    }

    public function setIfscCodeAttribiute($value){
        $value = preg_replace('/\s+/',' ', $value);
        $this->attrributes['ifsc_code'] = ucwords(strtoupper($value));
    }
}
