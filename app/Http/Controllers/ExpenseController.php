<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_expense;
use App\Models\tbl_expense_category;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class ExpenseController extends Controller
{
    //
  

    function addExpense(Request $req){

        $validate = Validator::make($req->all(), [
            "expenseDate" => "required | date_format:Y-m-d",
            "expenseCategory" => "required | numeric",
            "expenseDescription" => "required",
            "expenseAmount" => "required | numeric | min:0"
        ]);

        if($validate->fails()){
            return response()->json(array(
                "status" =>  -1,
                "message" => "Validation Failed",
                "errors" => $validate->errors()
            ));
        }



        $expenseDate = $req->input('expenseDate');
        $expenseCategory = $req->input('expenseCategory');
        $expenseDescription = $req->input('expenseDescription');
        $expenseAmount = $req->input('expenseAmount');

        if(!tbl_expense_category::isThereExpenseCategoryWithCategoryId($expenseCategory)){
            return response()->json(array(
                "status" => -1,
                "message" => "Expense Category Not Available"
            ));
        }
    
        $newExpense = new tbl_expense();
        $newExpense->expense_date = $expenseDate;
        $newExpense->expense_description = $expenseDescription;
        $newExpense->expense_category_id = $expenseCategory;
        $newExpense->expense_amount = $expenseAmount;

        if($newExpense->save()){
            return response()->json(array(
                "status" => 1,
                "message" => "Expense Added Succesfully"
            ));
        }
        else{
            return response()->json(array(
                "status" => -1,
                "message" => "Expense Addition Failed"
            ));
        }
    }
    
    function getExpenses(Request $req){

        $paginate = request("paginate", 10);
        $search_term = request("search", "");
        $search_term = trim($search_term);
        $search_term = "%$search_term%";
        $sort_field = request("sortfield");
        $sort_direction = request("sortdirection");
        $from_date = request("fromdate", Carbon::now()->subDays(10));
        $to_date = request("todate", Carbon::now());
        $expense_category_id = request("expense_category_id", '');
 
        if(!in_array($sort_direction, ['asc', 'desc'])){
            $sort_direction = "desc";
        }

        if(!in_array($sort_field, ['expense_date','expense_description'])){
            $sort_field = 'expense_date';
        }

        return  (tbl_expense::join('tbl_expense_categories', 'tbl_expenses.expense_category_id', '=', 'tbl_expense_categories.expense_category_id')
        ->where('tbl_expenses.expense_description', 'like', $search_term)
        ->where('expense_status', '=', true)
        ->whereDate('expense_date', '<=' , $to_date)
        ->whereDate('expense_date', '>=', $from_date)
        ->when($expense_category_id, function($query) use ($expense_category_id){
            $query->where('tbl_expenses.expense_category_id', '=', $expense_category_id);
        })
        ->orderBy($sort_field, $sort_direction)   
        ->select("expense_id","expense_date","expense_description", 'expense_category', 'expense_amount', 'tbl_expenses.expense_category_id') 
        ->paginate($paginate));
    }

    function getTotalAmountOfGivenDateRangeAndCategory(Request $req){
        $fromDate = request("fromdate", Carbon::now()->subDays(10));
        $toDate = request("todate", Carbon::now());
        $expenseCategoryId = request("categoryid", "");
        $searchTerm = request("search", "");
        $searchTerm = "%$searchTerm%";

        return tbl_expense::where("expense_status", true)
        ->whereDate("expense_date", ">=", $fromDate)
        ->whereDate("expense_date", "<=", $toDate)
        ->where("expense_description", 'like', $searchTerm)
        ->when($expenseCategoryId,function($query) use ($expenseCategoryId){
            $query->where('expense_category_id', $expenseCategoryId);
        })
        ->sum('expense_amount');

        // return expense()
    }

    function updateExpenses(Request $req){
        
        $validate = Validator::make($req->all(), [
            "expenseId" => "required | numeric",
            "expenseDate" => "required | date_format:Y-m-d",
            "expenseCategoryId" => "required | numeric",
            "expenseDescription" => "required",
            "expenseAmount" => "required | numeric | min:0"
        ]);

        if($validate->fails()){
            return response()->json(array(
                "status" =>  -1,
                "message" => "Validation Failed",
                "errors" => $validate->errors()
            ));
        }



        $expenseId = $req->input("expenseId");
        $expenseDate = $req->input('expenseDate');
        $expenseCategoryId = $req->input('expenseCategoryId');
        $expenseDescription = $req->input('expenseDescription');
        $expenseAmount = $req->input('expenseAmount');

        if(!tbl_expense_category::isThereExpenseCategoryWithCategoryId($expenseCategoryId)){
            return response()->json(array(
                "status" => -1,
                "message" => "Expense Category Not Available"
            ));
        }
    
        $findExpenseEntry = tbl_expense::find($expenseId);
        $findExpenseEntry->expense_date = $expenseDate;
        $findExpenseEntry->expense_description = $expenseDescription;
        $findExpenseEntry->expense_category_id = $expenseCategoryId;
        $findExpenseEntry->expense_amount = $expenseAmount;

        if($findExpenseEntry->save()){
            return response()->json(array(
                "status" => 1,
                "message" => "Expense Updated Succesfully"
            ));
        }
        else{
            return response()->json(array(
                "status" => -1,
                "message" => "Expense Updation Failed"
            ));
        }
    }

    function deleteExpenses(Request $req, $expenseId){
        $data['expenseId'] = $expenseId;

        $validate = Validator::make($data, [
            'expenseId' => 'required | integer'
        ]);


        if($validate->fails()){
            return response()->json(array(
                'status' => -1,
                'message' => 'Validation Failed'
            ));
        }

        


        $expenseToBeDelete = tbl_expense::find($expenseId);
        
        if(is_null($expenseToBeDelete)){
            return response()->json(array(
                "status" => -1,
                "message" => "Expense Entry Not Found"
            ));
        }

        if(!($expenseToBeDelete->expense_status)){
            return response()->json(array(
                "status" => -1,
                "message" => "Expense Entry Not Found"
            ));
        }

        $expenseToBeDelete->expense_status = false;

        if($expenseToBeDelete -> save()){
            return response()->json(array(
                "status" => 1,
                "message" => "Expense Deleted Successfully"
            ));
        }
        else{
            return response()->json(array(
                "status" => -1,
                "message" => "Expense Deletation Failed"
            ));
        }

    }
}
