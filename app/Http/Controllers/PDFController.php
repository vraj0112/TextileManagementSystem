<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_customer;
use PDF;

class PDFController extends Controller
{
    public function generateChallanPDF(Request $req, $challan_id)
    {
        $students = tbl_customer::all();

        $pdf = PDF::loadView('challanPDF',["students" => $students]);
        return $pdf->stream('challan.pdf');
    }

    public function generateInvoicePDF(Request $req, $invoice_id)
    {

    }
}
