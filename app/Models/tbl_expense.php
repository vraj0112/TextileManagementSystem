<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class tbl_expense extends Model
{
    use HasFactory;

    protected $tablename = "tbl_expenses";
    protected $primaryKey = 'expense_id';


    public function getExpenseDateAttribute($value){
        return (Carbon::parse($value)->format('d-m-Y'));
    }

    public function setExpenseDescriptionAttribute($value){
        $value = preg_replace('/\s+/', ' ', $value);
        $this->attributes['expense_description'] = ucwords(strtolower($value));     
    }

    public static function hasExpenseWithCategoryId($expenseCategoryId){
        $getExpenseWithCategoryId = tbl_expense::where('expense_category_id', "=", $expenseCategoryId)
                                    ->where("expense_status", "=", true)->first();
        
        if(is_null($getExpenseWithCategoryId)){
            return false;
        }
        else{
            return true;
        }
    }
}
