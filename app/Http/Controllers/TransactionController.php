<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\Common;
use App\Imports\TransactionsImport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    use Common;
    private $csv;
    public function __construct()
    {
        $this->csv = Storage::disk("public")->path("transactions.csv");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data = $this->read_csv($this->csv);
        return $this->successResponse($data,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "startDate" =>  "required|date_format:d/m/Y",
            "endDate"   =>  "required|date_format:d/m/Y|after_or_equal:startDate",
            "firstName" =>  "required",
            "lastName"  =>  "required",
            "email"     =>  "required|email",
            "phone"     =>  "required|min:10",
            "address1"  =>  "required",
            "address2"  =>  "required",
            "city"      =>  "required",
            "country"   =>  "required|max:2",
            "postCode"  =>  "required",
            "product"   =>  "required",
            "price"     =>  "required|regex:/^\d+(\.\d{1,2})?$/",
            "currencyName"  =>  "required|in:usd,gbp,pkr",
            "transactionDate"   =>  "required|date_format:d/m/Y|after_or_equal:startDate"
        ];

        $this->validate($request,$rules);
        $row[0] = $request->input("startDate");
        $row[1] = $request->input("endDate");
        $row[2] = $request->input("firstName");
        $row[3] = $request->input("lastName");
        $row[4] = $request->input("email");
        $row[5] = $request->input("phone");
        $row[6] = $request->input("address1");
        $row[7] = $request->input("address2");
        $row[8] = $request->input("city");
        $row[9] = $request->input("country");
        $row[10] = $request->input("postCode");
        $row[11] = $request->input("product");
        $row[12] = $request->input("price");
        $row[13] = $request->input("currencyName");
        $row[14] = $request->input("transactionDate");
        if($this->append_row($this->csv,$row))
        {
            return $this->successResponse($row,201);
        }
        else
        {
            return $this->errorResponse("There is some error in inserting new row to csv",422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->read_csvline($this->csv,$id);
        return $this->successResponse($data,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($id==1)
        {
            return $this->errorResponse("First row is not allowed to edit",422);
        }
        $rules = [
            "startDate" =>  "date_format:d/m/Y",
            "endDate"   =>  "date_format:d/m/Y|after_or_equal:startDate",
            "email"     =>  "email",
            "phone"     =>  "min:13",
            "country"   =>  "max:2",
            "price"     =>  "regex:/^\d+(\.\d{1,2})?$/",
            "currencyName"  =>  "in:usd,gbp,pkr",
            "transactionDate"   =>  "date_format:d/m/Y|after_or_equal:startDate"
        ];

        $this->validate($request,$rules);
        if($request->has("startDate"))
            $row[0] = $request->input("startDate");
        if($request->has("endDate"))
            $row[1] = $request->input("endDate");
        if($request->has("firstName"))
            $row[2] = $request->input("firstName");
        if($request->has("lastName"))
            $row[3] = $request->input("lastName");
        if($request->has("email"))
            $row[4] = $request->input("email");
        if($request->has("phone"))
            $row[5] = $request->input("phone");
        if($request->has("address1"))
            $row[6] = $request->input("address1");
        if($request->has("address2"))
            $row[7] = $request->input("address2");
        if($request->has("city"))
            $row[8] = $request->input("city");
        if($request->has("country"))
            $row[9] = $request->input("country");
         if($request->has("postCode"))
            $row[10] = $request->input("postCode");
         if($request->has("product"))
            $row[11] = $request->input("product");
         if($request->has("price"))
            $row[12] = $request->input("price");
         if($request->has("currencyName"))
            $row[13] = $request->input("currencyName");
         if($request->has("transactionDate"))
            $row[14] = $request->input("transactionDate");
        if($this->edit_row($this->csv,$id,$row))
        {
            return $this->successResponse($row,200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->delete_record($this->csv,$id))
        {
            $data = $this->read_csv($this->csv);
            return $this->successResponse($data,200);
        }
    }
}