<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_expense_category;
use App\Models\tbl_expense;
use Illuminate\Support\Facades\Validator;

class ExpenseCategoryControler extends Controller
{
    //
    /* will give all the expense categories for
     Searching and managing expense categories
     with pagination */

    public function getAllExpenseCategories(Request $req){
        $paginate = request("paginate", 10);
        $search_term = request("search", "");
        $search_term = trim($search_term);
        $search_term = "%$search_term%";
        $sort_field = request("sortfield");
        $sort_direction = request("sortdirection");

        if(!in_array($sort_direction, ['asc', 'desc'])){
            $sort_direction = "asc";
        }

        if(!in_array($sort_field, ['expense_category_id', 'expense_category'])){
            $sort_field = "expense_category_id";
        }

        return  (tbl_expense_category::select('expense_category_id', 'expense_category', 'expense_category_status')
        ->where('expense_category', 'like', $search_term)
        ->where('expense_category_status', '=', 1)
        ->orderBy($sort_field, $sort_direction)    
        ->paginate($paginate));
    }

    // function for updating expense category
    public function updateExpenseCategory(Request $req, $expenseCategoryId){

        $data = $req->all();
        $data['expenseCategoryId'] = $expenseCategoryId;

        $validate = Validator::make($data, [
            'expenseCategory' => 'required | max:50',
            'expenseCategoryId' => 'required | integer'
        ]);

        if($validate->fails()){
            return response()->json(array(
                'status'=> -1,
                "message" => "Validation Failed",
                "errors" => $validate->errors()
            ));
        }

        $expenseCategory = $req->input("expenseCategory");
    
        $isSameCategory = tbl_expense_category::where('expense_category', '=', $expenseCategory)
        ->where("expense_category_status", '=', 1)->first();

        if(is_null($isSameCategory)){

            $category = tbl_expense_category::find($expenseCategoryId);
            $category->expense_category = $expenseCategory;
            
            if($category->save()){
                return response()->json(array(
                    "status" => 1,
                    "message"=> "Category Updated Successfully"
                ));
            }
            else{
                return response()->json(array(
                    "status" => -1,
                    "message"=> "Updation Failed"
                ));
            }
        }
        else{
            return response()->json(array(
                "status" => 0,
                "message"=> "Expense Category Already Exists"
            ));
        }
    }

    // function for adding new category
    public function addNewCategory(Request $req){

        $validate = Validator::make($req->all(), [
            'expenseCategory' => 'required | max:50'
        ]);

        if($validate->fails()){
            return response()->json(array(
                'status'=> -1,
                "message" => "Validation Failed",
                "errors" => $validate->errors()
            ));
        }

        $expenseCategory = $req->input("expenseCategory");

        $isSameCategory = tbl_expense_category::where('expense_category', '=', $expenseCategory)
        ->where("expense_category_status", '=', '1')
        ->first();

        if(is_null($isSameCategory)){
            $newCategory = new tbl_expense_category();
            $newCategory->expense_category = $req->input("expenseCategory");
            
            if($newCategory->save()){
                return response()->json(array(
                    "status" => 1,
                    "message" => "New Expense Category Added Succesfully"
                ));
            }
            else{
                return response()->json(array(
                    "status" => -1,
                    "message" => "Something Went Wrong Please Try Again"
                ));
            }
        }
        else{
            return response()->json(array(
                "status" => 0,
                "message" => "Expense Category Already Exists"
            ));
        }
    }

    // function to delete category
    public function deleteCategory(Request $req, $expenseCategoryId)
    {
        $data['expenseCategoryId'] = $expenseCategoryId;

        $validate = Validator::make($data, [
            'expenseCategoryId' => 'required | integer'
        ]);


        if($validate->fails()){
            return response()->json(array(
                'status' => -1,
                'message' => 'Validation Failed'
            ));
        }

        if(tbl_expense::hasExpenseWithCategoryId($expenseCategoryId)){
            return response()->json(array(
                'status' => 0,
                'message' => "There is an Expense Having This Category First Delete It Then Delete Category"
            ));
        }


        $categoryToBeDelete = tbl_expense_category::where('expense_category_id', '=', $expenseCategoryId)
                            ->where("expense_category_status", "=", true)->first();

        //echo $categoryToBeDelete;

        if(is_null($categoryToBeDelete)){
            return response()->json(array(
                "status" => -1,
                "message" => "There Is No Category With These Category Id"
            ));
        }

        $categoryToBeDelete->expense_category_status = false;
        if($categoryToBeDelete->save()){
            return response()->json(array(
                "status"=>1,
                "message" => "Category Successfully Deleted"
            ));
        }
        else{
            return response()->json(array(
                "status" => -1,
                "message" => "Category Deletation Failed, Please Try Again"
            ));
        }   

        



    }

    public function getAllExpenseCategoriesList(){

        $expenseCategoryList = tbl_expense_category::where('expense_category_status', '=', true)
                            ->select('expense_category_id', 'expense_category')
                            ->get();

        return response()->json(array(
            "status" => 1,
            "data"=> $expenseCategoryList
        ));
    }

    
}
