<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_inward_quality extends Model
{
    use HasFactory;
    protected $table = "tbl_inward_qualities";
    protected $fillable = [
        'quality_name',
        'inward_quality_category_id'
    ];

    public function setQualityNameAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['quality_name'] = ucwords(strtolower($value));
    }

    public static function isThereProductQualityWithQualityId($inwardQualityId){
        $isQualityAvailable = tbl_inward_quality::where("inward_quality_status", true)
            ->where("inward_quality_id", $inwardQualityId)
            ->first();

        if(is_null($isQualityAvailable)){
           return false; 
        }
        
        return true;
        
    }

    public function category(){
        return $this->hasOne('App\Models\tbl_inward_quality_category', 'inward_quality_category_id', 'inward_quality_category_id');
    }
}
