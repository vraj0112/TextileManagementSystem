<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_gst_code extends Model
{
    use HasFactory;

    protected $primaryKey = "gst_code_id";

    protected $table = "tbl_gst_codes";
    protected $fillable = [
        'state_name',
        'gst_code'
    ];
}
