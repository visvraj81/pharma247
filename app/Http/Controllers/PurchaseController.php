<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\Distributer;
use App\Models\User;

class PurchaseController extends Controller
{
    //this function use purchase product
    public function purchaseAdd(Request $request)
    {
        try{
              $distrubuter = User::where('role','4')->get();

            return  view('pharma.purchase_add',compact('distrubuter'));

        } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
    }

    //this fuunction add purches store
    public function purchaseStore(Request $request)
    {
         try{
            
            $outputArray = [];

            // Loop through each key in the input array
            foreach ($request['iteam'] as $index => $value) {
                // Create a new associative array for each index
                $outputArray[$index] = [
                    'iteam' => $request['iteam'][$index],
                    'hsn_code' => $request['hsn_code'][$index],
                    'unit' => $request['unit'][$index],
                    'batch' => $request['batch'][$index],
                    'exp_date' => $request['exp_date'][$index],
                    'mrp' => $request['mrp'][$index],
                    'qty' => $request['qty'][$index],
                    'fr_qty' => $request['fr_qty'][$index],
                    'ptr' => $request['ptr'][$index],
                    'd_percent' => $request['d_percent'][$index],
                    'disc' => $request['disc'][$index],
                    'base' => $request['base'][$index],
                    'gst' => $request['gst'][$index],
                    'amount' => $request['amount'][$index],
                    'lp' => $request['lp'][$index],
                    'location' => $request['location'][$index]
                ];
            }
            $arrayData = json_encode($outputArray); 
            
            $url = url('/') . '/api/purches-store';
            $data = [
                'distributor_id' => $request->distributor_id,
                'bill_no' => $request->bill_no,
                'bill_date' => $request->due_date,
                'due_date' => $request->due_date,
                'purches_date' => $arrayData,
                'ptr_total' => $request->ptr_total,
                'ptr_discount' => $request->ptr_discount,
                'cess' => $request->cess,
                'tcs' => $request->tcs,
                'extra_charge' => $request->extra_charge,
                'adjustment_amoount' => $request->adjustment_amoount,
                'round_off' => $request->round_off,
                'net_amount' => $request->net_amount,
            ];

            // dD($data);
            // Make the HTTP POST request
            $response = Http::post($url, $data);

            $responseData = $response->json();
            return redirect()->back()->with('success', 'Item Added Successfully');

         } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
    }

    //thsi function use purches return 
    public function purchesReturn(Request $request)
    {
       try{
        $distrubuter = User::where('role','4')->get();
        return view('pharma.purches_retur',compact('distrubuter'));
        } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use purches data
    public function purchesData(Request $request)
    {
          try{

            $url = url('/') . '/api/purches-return';
            $data = [
                'distributor_id' => $request->id,
            ];

            // dD($data);
            // Make the HTTP POST request
            $response = Http::post($url, $data);

            $responseData = $response->json();

            $detailsData = [];
            if(isset( $responseData['data']))
            {
                foreach($responseData['data'] as $key => $list)
                {
                      $detailsData[$key]['id'] = isset($list['id']) ? $list['id'] :"";
                      $detailsData[$key]['iteam_id'] = isset($list['iteam_id']) ? $list['iteam_id'] :"";
                      $detailsData[$key]['hsn_code'] = isset($list['hsn_code']) ? $list['hsn_code'] :"";
                      $detailsData[$key]['unit'] = isset($list['unit']) ? $list['unit'] :"";
                      $detailsData[$key]['batch'] = isset($list['batch']) ? $list['batch'] :"";
                      $detailsData[$key]['exp_dt'] = isset($list['exp_dt']) ? $list['exp_dt'] :"";
                      $detailsData[$key]['mrp'] = isset($list['mrp']) ? $list['mrp'] :"";
                      $detailsData[$key]['qty'] = isset($list['qty']) ? $list['qty'] :"";
                      $detailsData[$key]['fr_qty'] = isset($list['fr_qty']) ? $list['fr_qty'] :"";
                      $detailsData[$key]['ptr'] = isset($list['ptr']) ? $list['ptr'] :"";
                      $detailsData[$key]['d_percent'] = isset($list['d_percent']) ? $list['d_percent'] :"";
                      $detailsData[$key]['disocunt'] = isset($list['disocunt']) ? $list['disocunt'] :"";
                      $detailsData[$key]['base'] = isset($list['base']) ? $list['base'] :"";
                      $detailsData[$key]['gst'] = isset($list['gst']) ? $list['gst'] :"";
                      $detailsData[$key]['amount'] = isset($list['amount']) ? $list['amount'] :"";
                      $detailsData[$key]['lp'] = isset($list['lp']) ? $list['lp'] :"";
                      $detailsData[$key]['location'] = isset($list['location']) ? $list['location'] :"";
                }
            }

            return response()->json(['dataReponse'=>$detailsData]);
         } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
         }
    }

    //this function purches return 
    public function purchesReturnData(Request $request)
    {
         try{
          
               $result = [];
                for ($i = 0; $i < count($request['iteam']); $i++) {
                    $result[] = [
                        'iteam' => $request['iteam'][$i],
                        'hsn_code' => $request['hsn_code'][$i],
                        'unit' => $request['unit'][$i],
                        'batch' => $request['batch'][$i],
                        'exp_dt' => $request['exp_dt'][$i],
                        'mrp' => $request['mrp'][$i],
                        'qty' => $request['qty'][$i],
                        'fr_qty' => $request['fr_qty'][$i],
                        'ptr' => $request['ptr'][$i],
                        'd_percent' => $request['d_percent'][$i],
                        'disocunt' => $request['disocunt'][$i],
                        'base' => $request['base'][$i],
                        'gst' => $request['gst'][$i],
                        'amount' => $request['amount'][$i],
                        'lp' => $request['lp'][$i],
                        'location' => $request['location'][$i],
                    ];
                }
                dD($result,$request->all());

                $arrayData = json_encode($result); 
            
                $url = url('/') . '/api/purches-store';
                $data = [
                    'distributor_id' => $request->distributor_id,
                    'bill_date' => $request->bill_date,
                    'select_date' => $request->select_date,
                    'remark' => $request->remark,
                    'purches_return' => $arrayData
                ];

         } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
         }
    }
}
