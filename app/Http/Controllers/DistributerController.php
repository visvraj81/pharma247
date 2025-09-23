<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DistributerController extends Controller
{
    //this function use add distrubuter
    public function AddDistributer(Request $request)
    {
        try {

            return view('pharma.add_distrubuter');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    //this function use store distributor
    public function distributorStore(Request $request)
    {
          try{

            // dd($request->all());

            $url = url('/') . '/api/create-distributer';

            
            $data = [
                'gst_number' => $request->gst_number,
                'distributor_name' => $request->distributor_name,
                'email' => $request->email,
                'mobile_no' => $request->mobile_no,
                'phone' => $request->phone,
                'whatsapp' => $request->whatsapp,
                'address' => $request->address,
                'area' => $request->area,
                'pincode' => $request->pincode,
                'bank_name' => $request->bank_name,
                'account_no' => $request->account_no,
                'ifsc_code' => $request->ifsc_code,
                'food_licence_no' => $request->food_licence_no,
                'distributor_durg_distributor' => $request->distributor_durg_distributor,
                'payment_due_days' => $request->payment_due_days,
            ];

            $response = Http::post($url, $data);

            $responseData = $response->json();

            if($responseData['status'] == 400)
            {
                return redirect()->back()->with('error',$responseData['message']);
            }else{
                return redirect()->back()->with('success', 'Distributor Added Successfully');
            }

          } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
