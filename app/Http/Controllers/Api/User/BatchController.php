<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\BatchModel;
use App\Models\IteamsModel;
use App\Models\GstModel;
use App\Models\LogsModel;
use App\Models\User;
use App\Models\CompanyModel;
use App\Models\PurchesDetails;

class BatchController extends ResponseController
{
    //this function use batch create
    public function batchCreate(Request $request)
    {
          try {
            // $validator = Validator::make($request->all(), [
            //     'iteam_id' => 'required',
            //     'iteam_qty'=>'required',
            //     'discount'=>'required',
            //     'gst'=>'required',
            //     'batch_name'=>'required',
            //     'expiry'=>'required',
            //     'mrp'=>'required'
            // ], [
            //     'iteam_id.required'=>'Please Enter Batch Id',
            //     'iteam_qty.required'=>'Please Enter Iteam Qty',
            //     'discount.required'=>'Please Entre Disocunt',
            //     'gst.required'=>'Please Enter Gst',
            //     'batch_name.required'=>'Please Enter Batch Name',
            //     'expiry.required'=>'Please Enter Expiry',
            //     'mrp.required'=>'Please Enter Mrp'
            // ]);

            // if ($validator->fails()) {
            //     $error = $validator->getMessageBag();
            //     return $this->sendError($error->first());
            // }

            $batchData = new BatchModel;
            $batchData->item_id = $request->iteam_id;
            $batchData->qty = $request->iteam_qty;
            $batchData->stock = '0';
            $batchData->lp = $request->lp;
            $batchData->discount = $request->discount;
            $batchData->gst = $request->gst;
            $batchData->batch_name = $request->batch_name;
            $batchData->expiry_date = $request->expiry;
            $batchData->mrp = $request->mrp;
            $batchData->batch_number = $request->batch_number;
            $batchData->ptr = $request->ptr;
            $batchData->margin = $request->margin;
            $batchData->total_mrp = $request->mrp * $request->iteam_qty;
            $batchData->total_ptr = $request->ptr * $request->iteam_qty;
            $batchData->save();
 
            $iteamModel = IteamsModel::where('id',$request->iteam_id)->first();
            if(isset($iteamModel))
            {
                $iteamModel->stock = $iteamModel->stock + $request->iteam_qty;
                $iteamModel->total_ptr = $iteamModel->total_ptr + $batchData->total_ptr;
                $iteamModel->save();
            }
            
             $userLogs = new LogsModel;
                    $userLogs->message = 'Batch Added ';
                    $userLogs->user_id = auth()->user()->id;
                    $userLogs->date_time = date('Y-m-d H:i a');
                    $userLogs->save();
            
            $dataDetails = [];
            $dataDetails['id'] = isset($batchData->id) ? $batchData->id :"";
            return $this->sendResponse( $dataDetails, 'Batch Added Successfully');

         } catch (\Exception $e) {
            Log::info("Create Batch api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    public function distributorBatch(Request $request)
    {
         try{
               $validator = Validator::make($request->all(), [
                'distributor_id' => 'required',
                ], [
                    'distributor_id.required'=>'Please Enter Batch Id',
                ]);
    
                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return $this->sendError($error->first());
                }
                
                $iteamData = IteamsModel::whereNull('user_id')->orWhere('user_id',auth()->user()->id)->where('distributer_id',$request->distributor_id)->pluck('id')->toArray();
          
                $batchData = BatchModel::whereIn('item_id',$iteamData)->get();
                $dataBatch = [];
                if(isset($batchData))
                {
                    foreach($batchData as $key => $list)
                    {
                         $dataBatch[$key]['id'] = isset($list->id) ? $list->id :"";
                         $dataBatch[$key]['batch_number'] = isset($list->batch_number) ? $list->batch_number :"";
                         $dataBatch[$key]['qty'] = isset($list->total_qty) ? $list->total_qty :"";
                         $dataBatch[$key]['discount'] = isset($list->discount) ? $list->discount :"";
                         $dataBatch[$key]['gst'] = isset($list->gst) ? $list->gst :"";
                         $dataBatch[$key]['expiry_date'] = isset($list->expiry_date) ? $list->expiry_date :"";
                         $dataBatch[$key]['mrp'] = isset($list->mrp) ? $list->mrp :"";
                         $dataBatch[$key]['ptr'] = isset($list->ptr) ? $list->ptr :"";
                         $dataBatch[$key]['margin'] = isset($list->margin) ? $list->margin :"";
                         $dataBatch[$key]['total_mrp'] = isset($list->total_mrp) ? $list->total_mrp :"";
                         $dataBatch[$key]['location'] = isset($list->location) ? $list->location :"";
                         $dataBatch[$key]['total_ptr'] = isset($list->total_ptr) ? $list->total_ptr :"";
                         $dataBatch[$key]['unit'] = isset($list->unit) ? $list->unit :"";
                    }
                }
               return $this->sendResponse($dataBatch, 'Batch Get Successfully');
         } catch (\Exception $e) {
            Log::info("Create Batch api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use batch list
    public function batchList(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'iteam_id' => 'required',
            ], [
                'iteam_id.required'=>'Please Enter Item Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $userid = auth()->user();
            $staffGetData = User::where('create_by',auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id',auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData,$ownerGet,$userId);
          
          	//     $batchData = BatchModel::where('item_id', $request->iteam_id)
            //                 ->where('total_qty', '>', '0')
            //                 ->whereIn('user_id',$allUserId)
            //                 ->orderBy('total_qty', 'desc')
            //                 ->get();
            //   	dd($batchData);

            //     $dataBatch = [];

            //     if(empty($batchData))
            //     {
            //       dd('yes');
            //           foreach($batchData as $key => $list)
            //           {
            //                 $gstTotal = GstModel::where('id',$list->gst)->first();
            //                 $iteamModel = IteamsModel::where('id',$list->item_id)->first();
            //                 $companyName = CompanyModel::where('id',$iteamModel->pharma_shop)->first();

            //                 $dataBatch[$key]['id'] = isset($list->id) ? $list->id : "";
            //                 $dataBatch[$key]['iteam_name'] = isset($iteamModel->iteam_name) ? $iteamModel->iteam_name : "";
            //                 $dataBatch[$key]['company_id'] = isset($iteamModel->pharma_shop) ? $iteamModel->pharma_shop : "";
            //                 $dataBatch[$key]['company_name'] = isset($companyName->company_name) ? $companyName->company_name : "";
            //                 $dataBatch[$key]['item_id'] = isset($list->item_id) ? $list->item_id : "";
            //                 $dataBatch[$key]['qty'] = isset($list->total_qty) ? $list->total_qty : "";
            //                 $dataBatch[$key]['purchase_qty'] = isset($list->qty) ? $list->qty : "";
            //                 $dataBatch[$key]['purchase_free_qty'] = isset($list->free_qty) ? $list->free_qty : "";
            //                 $dataBatch[$key]['batch_number'] = isset($list->batch_number) ? $list->batch_number : "";
            //                 $dataBatch[$key]['lp'] = isset($list->lp) ? $list->lp : "";
            //                 $dataBatch[$key]['ptr'] = isset($list->ptr) ? $list->ptr : "";
            //                 $dataBatch[$key]['margin'] = isset($list->margin) ? $list->margin : "";
            //                 $dataBatch[$key]['total_mrp'] = isset($list->total_mrp) ? $list->total_mrp : "";
            //                 $dataBatch[$key]['total_ptr'] = isset($list->total_ptr) ? $list->total_ptr : "";
            //                 $dataBatch[$key]['discount'] = isset($list->discount) ? $list->discount : "";
            //                 $dataBatch[$key]['gst'] = isset($list->gst) ? $list->gst : "";
            //                 $dataBatch[$key]['gst_name'] = isset($gstTotal->name) ? $gstTotal->name :$list->gst;
            //                 $dataBatch[$key]['batch_name'] = isset($list->batch_name) ? $list->batch_name : "";
            //                 $dataBatch[$key]['expiry_date'] = isset($list->expiry_date) ? $list->expiry_date : "";
            //                 $dataBatch[$key]['mrp'] = isset($list->mrp) ? $list->mrp : "";
            //                 $dataBatch[$key]['stock'] = isset($list->total_qty) ? $list->total_qty : "";
            //                 $dataBatch[$key]['unit'] = isset($list->unit) ? $list->unit : "";
            //                 $dataBatch[$key]['location'] = isset($list->location) ? $list->location : "";
            //           }
            //       $alternativeCheck = false;
            //    } else {
            //       dd('no');
            //       $itemList = IteamsModel::where('id',$request->iteam_id)->first();
            //       $drugGroupData = IteamsModel::where('drug_group',$itemList->drug_group)->pluck('id')->toArray();

            //       $batchData = BatchModel::whereIn('item_id',$drugGroupData)
            //                   ->where('total_qty', '>', '0')
            //                   ->whereIn('user_id',$allUserId)
            //                   ->orderBy('total_qty', 'desc')
            //                   ->get();

            //       foreach($batchData as $key => $list)
            //       {
            //                 $gstTotal = GstModel::where('id',$list->gst)->first();
            //                 $iteamModel = IteamsModel::where('id',$list->item_id)->first();
            //                 $companyName = CompanyModel::where('id',$iteamModel->pharma_shop)->first();

            //                 $dataBatch[$key]['id'] = isset($list->id) ? $list->id :"";
            //                 $dataBatch[$key]['iteam_name'] = isset($iteamModel->iteam_name) ? $iteamModel->iteam_name :"";
            //                 $dataBatch[$key]['company_id'] = isset($iteamModel->pharma_shop) ? $iteamModel->pharma_shop :"";
            //                 $dataBatch[$key]['company_name'] = isset($companyName->company_name) ? $companyName->company_name :"";
            //                 $dataBatch[$key]['item_id'] = isset($list->item_id) ? $list->item_id :"";
            //                 $dataBatch[$key]['qty'] = isset($list->total_qty) ? $list->total_qty :"";
            //                 $dataBatch[$key]['purchase_qty'] = isset($list->qty) ? $list->qty :"";
            //                 $dataBatch[$key]['purchase_free_qty'] = isset($list->free_qty) ? $list->free_qty :"";
            //                 $dataBatch[$key]['batch_number'] = isset($list->batch_number) ? $list->batch_number :"";
            //                 $dataBatch[$key]['lp'] = isset($list->lp) ? $list->lp :"";
            //                 $dataBatch[$key]['ptr'] = isset($list->ptr) ? $list->ptr :"";
            //                 $dataBatch[$key]['margin'] = isset($list->margin) ? $list->margin :"";
            //                 $dataBatch[$key]['total_mrp'] = isset($list->total_mrp) ? $list->total_mrp :"";
            //                 $dataBatch[$key]['total_ptr'] = isset($list->total_ptr) ? $list->total_ptr :"";
            //                 $dataBatch[$key]['discount'] = isset($list->discount) ? $list->discount :"";
            //                 $dataBatch[$key]['gst'] = isset($list->gst) ? $list->gst :"";
            //                 $dataBatch[$key]['gst_name'] = isset($gstTotal->name) ? $gstTotal->name :$list->gst;
            //                 $dataBatch[$key]['batch_name'] = isset($list->batch_name) ? $list->batch_name :"";
            //                 $dataBatch[$key]['expiry_date'] = isset($list->expiry_date) ? $list->expiry_date :"";
            //                 $dataBatch[$key]['mrp'] = isset($list->mrp) ? $list->mrp :"";
            //                 $dataBatch[$key]['stock'] = isset($list->total_qty) ? $list->total_qty :"";
            //                 $dataBatch[$key]['unit'] = isset($list->unit) ? $list->unit :"";
            //                 $dataBatch[$key]['location'] = isset($list->location) ? $list->location :"";
            //        }
            //        $alternativeCheck = true;
            //    }
          
          	//if($request->iteam_id)
            // {
            	$batchData = BatchModel::where('item_id', $request->iteam_id)
                ->where('total_qty', '>', '0')
                ->whereIn('user_id', $allUserId)
                ->orderBy('total_qty', 'desc')
                ->get();
            // }else {
            //    $itemBulkIds = explode(',', str_replace(['{', '}', '"'], '', $request->item_bulk_id));
              
            //	$batchData = BatchModel::whereIn('item_id', $itemBulkIds)
            //    ->where('total_qty', '>', '0')
            //    ->whereIn('user_id', $allUserId)
            //    ->orderBy('total_qty', 'desc')
            //    ->get();
            // }

            $dataBatch = [];
            $alternativeCheck = false; // default false

            if ($batchData->isNotEmpty()) {
                // Primary batch data found
                foreach ($batchData as $key => $list) {
                    $gstTotal = GstModel::find($list->gst);
                    $iteamModel = IteamsModel::find($list->item_id);
                    $companyName = CompanyModel::find($iteamModel->pharma_shop);

                    $dataBatch[$key] = [
                        'id' => $list->id ?? "",
                        'iteam_name' => $iteamModel->iteam_name ?? "",
                        'company_id' => $iteamModel->pharma_shop ?? "",
                        'company_name' => $companyName->company_name ?? "",
                        'item_id' => $list->item_id ?? "",
                        'qty' => $list->total_qty ?? "",
                        'purchase_qty' => $list->qty ?? "",
                        'purchase_free_qty' => $list->free_qty ?? "",
                        'batch_number' => $list->batch_number ?? "",
                        'lp' => $list->lp ?? "",
                        'ptr' => $list->ptr ?? "",
                        'margin' => $list->margin ?? "",
                        'total_mrp' => $list->total_mrp ?? "",
                        'total_ptr' => $list->total_ptr ?? "",
                        'discount' => $list->discount ?? "",
                        'gst' => $list->gst ?? "",
                        'gst_name' => $gstTotal->name ?? $list->gst,
                        'batch_name' => $list->batch_name ?? "",
                        'expiry_date' => $list->expiry_date ?? "",
                        'mrp' => $list->mrp ?? "",
                        'stock' => $list->total_qty ?? "",
                        'unit' => $list->unit ?? "",
                        'location' => $list->location ?? "",
                    ];
                }
            } else {
                // No batch found → search alternative
                $alternativeCheck = true;

                $itemList = IteamsModel::find($request->iteam_id);
              	if ($itemList && $itemList->drug_group !== null && $itemList->drug_group !== '') {
                	$drugGroupData = IteamsModel::where('drug_group', $itemList->drug_group)->whereNotNull('drug_group')->pluck('id')->toArray();
                  
                    $batchData = BatchModel::whereIn('item_id', $drugGroupData)
                        ->where('total_qty', '>', '0')
                        ->whereIn('user_id', $allUserId)
                        ->orderBy('total_qty', 'desc')
                        ->get();

                    foreach ($batchData as $key => $list) {
                        $gstTotal = GstModel::find($list->gst);
                        $iteamModel = IteamsModel::find($list->item_id);
                        $companyName = CompanyModel::find($iteamModel->pharma_shop);

                        $dataBatch[$key] = [
                            'id' => $list->id ?? "",
                            'iteam_name' => $iteamModel->iteam_name ?? "",
                            'company_id' => $iteamModel->pharma_shop ?? "",
                            'company_name' => $companyName->company_name ?? "",
                            'item_id' => $list->item_id ?? "",
                            'qty' => $list->total_qty ?? "",
                            'purchase_qty' => $list->qty ?? "",
                            'purchase_free_qty' => $list->free_qty ?? "",
                            'batch_number' => $list->batch_number ?? "",
                            'lp' => $list->lp ?? "",
                            'ptr' => $list->ptr ?? "",
                            'margin' => $list->margin ?? "",
                            'total_mrp' => $list->total_mrp ?? "",
                            'total_ptr' => $list->total_ptr ?? "",
                            'discount' => $list->discount ?? "",
                            'gst' => $list->gst ?? "",
                            'gst_name' => $gstTotal->name ?? $list->gst,
                            'batch_name' => $list->batch_name ?? "",
                            'expiry_date' => $list->expiry_date ?? "",
                            'mrp' => $list->mrp ?? "",
                            'stock' => $list->total_qty ?? "",
                            'unit' => $list->unit ?? "",
                            'location' => $list->location ?? "",
                        ];
                    }
                }                
            }
          
          	if($dataBatch != [])
            {
            	$finalAlternativeCheck = $alternativeCheck;
            }else
            {
            	$finalAlternativeCheck = false;
            }
          
          	$response = [
                'status'=> 200,
                'data'    => $dataBatch,
              	// 'alternative_item_check' => $alternativeCheck,
              	'alternative_item_check' => $finalAlternativeCheck,
                'message' => 'Batch Data Fetch Successfully.',
            ];
          	
            return response()->json($response, 200);

           // return $this->sendResponse($dataBatch, 'Batch Data Fetch Successfully.');

           } catch (\Exception $e) {
            Log::info("Batch List api" . $e->getMessage());
            return $e->getMessage();
          }
    }
  
  	public function multipleBatchList(Request $request)
    {
      	try {
          	$userid = auth()->user();
            $staffGetData = User::where('create_by',auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id',auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData,$ownerGet,$userId);
          
    		$itemBulkIds = explode(',', str_replace(['[', ']', '"'], '', $request->item_bulk_id));
          	
          	$batchData = BatchModel::whereIn('item_id', $itemBulkIds)
                ->where('total_qty', '>', '0')
                ->whereIn('user_id', $allUserId)
                ->orderBy('total_qty', 'desc')
                ->get();
          
          	$dataBatch = [];
          
          	if ($batchData->isNotEmpty()) {
                // Primary batch data found
                foreach ($batchData as $key => $list) {
                    $gstTotal = GstModel::find($list->gst);
                    $iteamModel = IteamsModel::find($list->item_id);
                    $companyName = CompanyModel::find($iteamModel->pharma_shop);

                    $dataBatch[$key] = [
                        'id' => $list->id ?? "",
                        'iteam_name' => $iteamModel->iteam_name ?? "",
                        'company_id' => $iteamModel->pharma_shop ?? "",
                        'company_name' => $companyName->company_name ?? "",
                        'item_id' => $list->item_id ?? "",
                        'qty' => $list->total_qty ?? "",
                        'purchase_qty' => $list->qty ?? "",
                        'purchase_free_qty' => $list->free_qty ?? "",
                        'batch_number' => $list->batch_number ?? "",
                        'lp' => $list->lp ?? "",
                        'ptr' => $list->ptr ?? "",
                        'margin' => $list->margin ?? "",
                        'total_mrp' => $list->total_mrp ?? "",
                        'total_ptr' => $list->total_ptr ?? "",
                        'discount' => $list->discount ?? "",
                        'gst' => $list->gst ?? "",
                        'gst_name' => $gstTotal->name ?? $list->gst,
                        'batch_name' => $list->batch_name ?? "",
                        'expiry_date' => $list->expiry_date ?? "",
                        'mrp' => $list->mrp ?? "",
                        'stock' => $list->total_qty ?? "",
                        'unit' => $list->unit ?? "",
                        'location' => $list->location ?? "",
                    ];
                }
            }
          
          	$response = [
                'status'=> 200,
                'data'    => $dataBatch,
                'message' => 'Batch Data Fetch Successfully.',
            ];
          
          	return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::info("Item Bulk Batch List api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use batch edit details 
    public function batchEdit(Request $request)
    {
            try{

                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                ], [
                    'id.required'=>'Please Enter Id',
                ]);
    
                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return $this->sendError($error->first());
                }

                $batchData = BatchModel::where('id',$request->id)->first();

                $dataDetails = [];
                $dataDetails['id'] = isset($batchData->id) ? $batchData->id : "";
                $dataDetails['qty'] = isset($batchData->qty) ? $batchData->qty : "";
                $dataDetails['ptr'] = isset($batchData->ptr) ? $batchData->ptr : "";
                $dataDetails['discount'] = isset($batchData->discount) ? $batchData->discount : "";
                $dataDetails['gst'] = isset($batchData->gst) ? $batchData->gst : "";
                $dataDetails['batch_name'] = isset($batchData->batch_name) ? $batchData->batch_name : "";
                $dataDetails['expiry_date'] = isset($batchData->expiry_date) ? $batchData->expiry_date : "";
                $dataDetails['mrp'] = isset($batchData->mrp) ? $batchData->mrp : "";
                $dataDetails['stock'] = isset($batchData->stock) ? $batchData->stock : "";
                $dataDetails['lp'] = isset($batchData->lp) ? $batchData->lp : "";

                return $this->sendResponse($dataDetails, 'Batch Get Successfully');
           } catch (\Exception $e) {
            Log::info("Batch Edit api" . $e->getMessage());
            return $e->getMessage();
          }
    }

    //this function use update batch 
    public function batchUpdate(Request $request)
    {
               try{

                $batchData = BatchModel::where('id',$request->id)->first();
                if(empty($batchData))
                {
                    return $this->sendError('Id Not Found');
                }
                  $batchData->item_id = $request->iteam_id;
                $batchData->qty = $request->iteam_qty;
                $batchData->stock = '0';
                $batchData->lp = $request->lp;
                $batchData->discount = $request->discount;
                $batchData->gst = $request->gst;
                $batchData->batch_name = $request->batch_name;
                $batchData->expiry_date = $request->expiry;
                $batchData->mrp = $request->mrp;
                $batchData->batch_number = $request->batch_number;
                $batchData->ptr = $request->ptr;
                $batchData->margin = $request->margin;
                $batchData->total_mrp = $request->mrp * $request->iteam_qty;
                $batchData->total_ptr = $request->ptr * $request->iteam_qty;
                $batchData->update();
                
                $iteamModel = IteamsModel::where('id',$batchData->item_id)->first();
                if(isset($iteamModel))
                { 
                    $batchQty = BatchModel::where('item_id',$batchData->item_id)->sum('qty');
                    $totalPtr = BatchModel::where('item_id',$batchData->item_id)->sum('total_ptr');

                    $iteamModel->stock = $batchQty;
                    $iteamModel->total_ptr = $totalPtr;
                    $iteamModel->save();
                }
                  $userLogs = new LogsModel;
                    $userLogs->message = 'Batch Updated';
                    $userLogs->user_id = auth()->user()->id;
                    $userLogs->date_time = date('Y-m-d H:i a');
                    $userLogs->save();
                $dataDetails = [];
                $dataDetails['id'] = isset($batchData->id) ? $batchData->id :"";
                return $this->sendResponse( $dataDetails, 'Batch Updated Successfully');

                } catch (\Exception $e) {
                Log::info("Batch Update api" . $e->getMessage());
                return $e->getMessage();
            }
    }
    

    //thsi fun1234ction use delete batch
    public function batchDelete(Request $request)
    {
             try{

                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                ], [
                    'id.required'=>'Please Enter Id',
                ]);
    
                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return $this->sendError($error->first());
                }

                $batchData = BatchModel::where('id',$request->id)->first();
                if(!empty($batchData))
                {
                    $iteamModel = IteamsModel::where('id',$batchData->item_id)->first();
                    if(isset($iteamModel))
                    {
                        $stock = $iteamModel->stock - $batchData->qty;
                        $ptrTotal = $iteamModel->total_ptr - $batchData->total_ptr;
                        $iteamModel->stock = $stock;
                        $iteamModel->total_ptr = $ptrTotal;
                        $iteamModel->save();
                        $batchData->delete(); 
                    }
                }
                
                  $userLogs = new LogsModel;
                    $userLogs->message = 'Batch Deleted ';
                    $userLogs->user_id = auth()->user()->id;
                    $userLogs->date_time = date('Y-m-d H:i a');
                    $userLogs->save();
                return $this->sendResponse( [], 'Batch Deleted Successfully');
              } catch (\Exception $e) {
                Log::info("Batch Delete api" . $e->getMessage());
                return $e->getMessage();
            }
    }
}
