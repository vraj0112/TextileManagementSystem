<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_broker extends Model
{
    use HasFactory;
    protected $table = "tbl_brokers";
    protected $fillable = [
        'broker_name',
        'broker_contact_no'
    ];

    public function setBrokerNameAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['broker_name'] = ucwords(strtolower($value));
    }
}
