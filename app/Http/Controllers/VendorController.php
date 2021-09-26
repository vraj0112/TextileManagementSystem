<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_vendor;

class VendorController extends Controller
{
    public function addNewvendor(Request $req)
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

        $isSameVendor = tbl_vendor::where('vendor_company_name', '=', $companyName)
        ->where("vendor_contact_no",'=',$companyContact)
        ->where("vendor_email",'=',$emailAddress)
        ->where("vendor_gst_no",'=',$gstNumber)
        ->where("vendor_gst_code",'=',$gstCode)
        ->where("vendor_address",'=',$companyAddress)
        ->where("vendor_status", '=', '1')
        ->first();

        if(is_null($isSameVendor)){
            $newVendor = new tbl_vendor();
            $newVendor->vendor_company_name = $req->input("companyName");
            $newVendor->vendor_contact_no = $req->input("companyContact");
            $newVendor->vendor_email = $req->input("emailAddress");
            $newVendor->vendor_gst_no = $req->input("gstNumber");
            $newVendor->vendor_gst_code = $req->input("gstCode");
            $newVendor->vendor_address = $req->input("companyAddress");
            
            if($newVendor->save()){
                return response()->json(array(
                    "status" => 1,
                    "message" => "New Vendor Added Succesfully..."
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
                "message" => "Vendor Already Exists!!!"
            ));
        }
    }

    public function getAllVendors(Request $req){
        $paginate = request("paginate", 10);
        $search_term = request("search", "");
        $search_term = trim($search_term);
        $search_term = "%$search_term%";
        $sort_field = request("sortfield");
        $sort_direction = request("sortdirection");

        if(!in_array($sort_direction, ['asc', 'desc'])){
            $sort_direction = "asc";
        }

        if(!in_array($sort_field, ['vendor_id', 'vendor_company_name','vendor_contact_no','vendor_email','vendor_gst_no','vendor_gst_code','vendor_address'])){
            $sort_field = "vendor_id";
        }

        return  (tbl_vendor::select('vendor_id', 'vendor_company_name', 'vendor_contact_no', 'vendor_email', 'vendor_gst_no', 'vendor_gst_code', 'vendor_address', 'vendor_status')
        ->where('vendor_status', '=', 1)
        ->where(function($query) use ($search_term)
        {
            $query->where('vendor_company_name', 'like', $search_term)
            ->orWhere('vendor_contact_no', 'like', $search_term)
            ->orWhere('vendor_email', 'like', $search_term)
            ->orWhere('vendor_gst_no', 'like', $search_term)
            ->orWhere('vendor_gst_code', 'like', $search_term)
            ->orWhere('vendor_address', 'like', $search_term);
        })
        ->orderBy($sort_field, $sort_direction)    
        ->paginate($paginate));
    }

    public function updateVendor(Request $req, $vendorId){

        $companyName = $req->input("companyName");
        $companyContact = $req->input("companyContact");
        $companyAddress = $req->input("companyAddress");
        $emailAddress = $req->input("emailAddress");
        $gstNumber = $req->input("gstNumber");
        $gstCode = $req->input("gstCode");
    
        $isSameVendor = tbl_vendor::where('vendor_company_name', '=', $companyName)
        ->where("vendor_contact_no",'=',$companyContact)
        ->where("vendor_email",'=',$emailAddress)
        ->where("vendor_gst_no",'=',$gstNumber)
        ->where("vendor_gst_code",'=',$gstCode)
        ->where("vendor_address",'=',$companyAddress)
        ->where("vendor_status", '=', '1')
        ->first();

        if(is_null($isSameVendor)){

            $vendor = tbl_vendor::find($vendorId);
            $vendor->vendor_company_name= $companyName;
            $vendor->vendor_contact_no= $companyContact;
            $vendor->vendor_email= $emailAddress;
            $vendor->vendor_gst_no= $gstNumber;
            $vendor->vendor_gst_code= $gstCode;
            $vendor->vendor_address= $companyAddress;
            
            if($vendor->save()){
                return response()->json(array(
                    "status" => 1,
                    "message"=> "Vendor Updated Succesfully..."
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
                "message"=> "Vendor Already Exists!!!"
            ));
        }
    }

    public function deleteVendor(Request $req, $vendorId)
    {
        tbl_vendor::where('vendor_id','=',$vendorId)->update(['vendor_status' => 0]);
        return response()->json(array(
            "message"=> "Vendor Deleted Succesfully..."
        ));
    }
}