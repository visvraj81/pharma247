<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class BatchController extends Controller
{
    //this function use batch
    public function batchCreate($id)
    {
           try{

            return view('pharma.batch_create',compact('id'));

            } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use batch create
    public function batchAdd(Request $request)
    {
            try{

                $url = url('/') . '/api/batch-add';
                $data = [
                    'iteam_id' => $request->id,
                    'batch_number' => $request->batch_number,
                    'iteam_qty' => $request->qty,
                    'expiry' => $request->expiry_date,
                    'mrp' => $request->mrp,
                    'ptr' => $request->ptr,
                    'discount' => $request->discount,
                    'lp' => $request->lp,
                    'margin' => $request->margin
                ];
    
                $response = Http::post($url, $data);
    
                $responseData = $response->json();
                $editDetails = [];
                if (isset($responseData['data'])) {
                    $editDetails = $responseData['data'];
                }
                return redirect()->route('iteam.edit',$request->id)->with('success', 'Batch Added Successfully');

           } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function batchDelete($id)
    {
           try{

            $url = url('/') . '/api/batch-delete';
            $data = [
                'id' => $id,
            ];

            $response = Http::post($url, $data);

            $responseData = $response->json();
            $editDetails = [];
            if (isset($responseData['data'])) {
                $editDetails = $responseData['data'];
            }
            return redirect()->back()->with('success', 'Batch Deleted Successfully');

             } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    //this function use update batch
    public function batchUpdate(Request $request)
    {
            try{

                $url = url('/') . '/api/batch-update';
                $data = [
                    'id' => $request->id,
                    'iteam_qty' => $request->quantity,
                    'discount' => $request->discount,
                ];
    
                $response = Http::post($url, $data);
    
                $responseData = $response->json();
                $editDetails = [];
                if (isset($responseData['data'])) {
                    $editDetails = $responseData['data'];
                }

                return redirect()->back()->with('success', 'Batch Updated Successfully');
           } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
