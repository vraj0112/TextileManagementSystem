<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class tbl_inward_details extends Model
{
    use HasFactory;
    public function getInwardMstDateAttribute($value){
        return (Carbon::parse($value)->format('d-m-Y'));
    }


    public function quality(){
        return $this->hasOne('App\Models\tbl_inward_quality', 'inward_quality_id', 'inward_quality_id')->with("category:inward_quality_category_id,inward_category_name");
    }
}
