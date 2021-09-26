<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_expense_category extends Model
{
    protected $table = 'tbl_expense_categories';
    protected $primaryKey = 'expense_category_id';
    use HasFactory;

    public function setExpenseCategoryAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['expense_category'] = ucwords(strtolower($value));
        
    }


    public static function isThereExpenseCategoryWithCategoryId($expeseCategoryId){
        $isExpenseCategoryAvailable = tbl_expense_category::where("expense_category_status", true)
            ->where("expense_category_id", $expeseCategoryId)
            ->first();

        if(is_null($isExpenseCategoryAvailable)){
           return false; 
        }
        
        return true;
        
    }
}
