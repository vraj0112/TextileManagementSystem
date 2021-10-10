<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_customer;

class CustomerController extends Controller
{
    public function addNewCustomer(Request $req)
    {
        $validated = validator($req->all(),[
            'companyName' => 'required | max:50',
            'companyContact' => 'required | digits:10',
            'companyAddress' => 'required | max:255',
            'emailAddress' => 'nullable | email | max:255',
            'gstNumber' => 'required | alpha_num | min:24 | max:24',
            'gstCode' => 'required | alpha_num | min:2 | max:2'
        ]);

        if($validated->fails()){
            $res = array(
                "status" => -1,
                "message" => "The given data was invalid.",
                "errors" => $validated->errors()
            );

            return response()->json($res);
        }

        $companyName = $req->input("companyName");
        $companyContact = $req->input("companyContact");
        $companyAddress = $req->input("companyAddress");
        $emailAddress = $req->input("emailAddress");
        $gstNumber = $req->input("gstNumber");
        $gstCode = $req->input("gstCode");

        if($gstCode != substr($gstNumber,0,2))
        {
            $res = array(
                "status" => -1,
                "message" => "GST Code must be equal to first 2 characters of GST Number!",
                "errors" => $validated->errors()
            );

            return response()->json($res);
        }

        $isSameCustomer = tbl_customer::where('customer_company_name', '=', $companyName)
        ->where("customer_contact_no",'=',$companyContact)
        ->where("customer_email",'=',$emailAddress)
        ->where("customer_gst_no",'=',$gstNumber)
        ->where("customer_gst_code",'=',$gstCode)
        ->where("customer_address",'=',$companyAddress)
        ->where("customer_status", '=', '1')
        ->first();

        if(is_null($isSameCustomer)){
            $newCustomer = new tbl_customer();
            $newCustomer->customer_company_name = $req->input("companyName");
            $newCustomer->customer_contact_no = $req->input("companyContact");
            $newCustomer->customer_email = $req->input("emailAddress");
            $newCustomer->customer_gst_no = $req->input("gstNumber");
            $newCustomer->customer_gst_code = $req->input("gstCode");
            $newCustomer->customer_address = $req->input("companyAddress");
            
            if($newCustomer->save()){
                return response()->json(array(
                    "status" => 1,
                    "message" => "New Customer Added Succesfully..."
                ));
            }
            else{
                return response()->json(array(
                    "status" => -1,
                    "message" => "Something Went Wrong! Please Try Again..."
                ));
            }
        }
        else{
            return response()->json(array(
                "status" => 0,
                "message" => "Customer Already Exists!!!"
            ));
        }
    }

    public function getAllCustomers(Request $req){
        $paginate = request("paginate", 10);
        $search_term = request("search", "");
        $search_term = trim($search_term);
        $search_term = "%$search_term%";
        $sort_field = request("sortfield");
        $sort_direction = request("sortdirection");

        if(!in_array($sort_direction, ['asc', 'desc'])){
            $sort_direction = "desc";
        }

        if(!in_array($sort_field, ['customer_id', 'customer_company_name','customer_contact_no','customer_email','customer_gst_no','customer_gst_code','customer_address'])){
            $sort_field = "customer_id";
        }

        return  (tbl_customer::select('customer_id', 'customer_company_name', 'customer_contact_no', 'customer_email', 'customer_gst_no', 'customer_gst_code', 'customer_address', 'customer_status')
        ->where('customer_status', '=', 1)
        ->where(function($query) use ($search_term)
        {
            $query->where('customer_company_name', 'like', $search_term)
            ->orWhere('customer_contact_no', 'like', $search_term)
            ->orWhere('customer_email', 'like', $search_term)
            ->orWhere('customer_gst_no', 'like', $search_term)
            ->orWhere('customer_gst_code', 'like', $search_term)
            ->orWhere('customer_address', 'like', $search_term);
        })
        ->orderBy($sort_field, $sort_direction)    
        ->paginate($paginate));
    }

    public function updateCustomer(Request $req, $customerId){

        $companyName = $req->input("companyName");
        $companyContact = $req->input("companyContact");
        $companyAddress = $req->input("companyAddress");
        $emailAddress = $req->input("emailAddress");
        $gstNumber = $req->input("gstNumber");
        $gstCode = $req->input("gstCode");
    
        $isSameCustomer = tbl_customer::where('customer_company_name', '=', $companyName)
        ->where("customer_contact_no",'=',$companyContact)
        ->where("customer_email",'=',$emailAddress)
        ->where("customer_gst_no",'=',$gstNumber)
        ->where("customer_gst_code",'=',$gstCode)
        ->where("customer_address",'=',$companyAddress)
        ->where("customer_status", '=', '1')
        ->first();

        if(is_null($isSameCustomer)){

            $customer = tbl_customer::find($customerId);
            $customer->customer_company_name= $companyName;
            $customer->customer_contact_no= $companyContact;
            $customer->customer_email= $emailAddress;
            $customer->customer_gst_no= $gstNumber;
            $customer->customer_gst_code= $gstCode;
            $customer->customer_address= $companyAddress;
            
            if($customer->save()){
                return response()->json(array(
                    "status" => 1,
                    "message"=> "Customer Updated Succesfully..."
                ));
            }
            else{
                return response()->json(array(
                    "status" => -1,
                    "message"=> "Something Went Wrong! Please Try Again..."
                ));
            }
        }
        else{
            return response()->json(array(
                "status" => 0,
                "message"=> "Customer Already Exists!!!"
            ));
        }
    }

    public function deleteCustomer(Request $req, $customerId)
    {
        tbl_customer::where('customer_id','=',$customerId)->update(['customer_status' => 0]);
        return response()->json(array(
            "message"=> "Customer Deleted Succesfully..."
        ));
    }

    public function getCustomersList(Request $req){
        return (
            tbl_customer::select("customer_id", "customer_company_name", "customer_contact_no")->where('customer_status', true)->get()
        );
    }

    public function getSelectedCustomerData(Request $request, $customerId){
        return (
            tbl_customer::select("customer_gst_no", "customer_contact_no")->where('customer_id', '=', $customerId)->where('customer_status', true)->first()
        );
    }

}