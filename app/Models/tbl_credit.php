<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class tbl_credit extends Model
{
    use HasFactory;
    protected $primaryKey = "credit_id";
    protected $table = "tbl_credits";
    protected $fillable = [
            'credit_date',
            'credit_description',
            'credit_amount'
    ];

    public function getCreditDateAttribute($value)
    {
        return (Carbon::parse($value)->format("d-m-Y"));
    }
}
