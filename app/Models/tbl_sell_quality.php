<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_sell_quality extends Model
{
    use HasFactory;
    protected $table = "tbl_sell_qualities";
    protected $fillable = [
        'quality_name',
        'sell_quality_category_id'
    ];

    public function setQualityNameAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['quality_name'] = ucwords(strtolower($value));
    }
}
