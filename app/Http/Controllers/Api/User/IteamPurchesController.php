<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\iteamPurches;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\User;
use App\Models\IteamsModel;
use App\Models\UniteTable;
use App\Models\GstModel;
use App\Models\PurchesDetails;
use App\Models\LedgerModel;
use Illuminate\Support\Facades\Response;
use App\Models\BatchModel;
use App\Models\FinalPurchesItem;
use ZipArchive;
use App\Models\PurchesModel;
use App\Models\Distributer;
use DateTime;

class IteamPurchesController extends ResponseController
{
    public function itemPurchase(Request $request)
    {
        try {
            $iteamStore = new iteamPurches;
            $iteamStore->random_number = $request->random_number;
            $iteamStore->batch_number = $request->batch_number;
            $iteamStore->expiry = $request->expiry;
            $iteamStore->mrp = $request->mrp;
            $iteamStore->ptr = $request->ptr;
            $iteamStore->qty = $request->qty;
            $iteamStore->hsn_code = $request->hsn_code;
            $iteamStore->first_qty = $request->free_qty;
            $iteamStore->scheme_account = $request->scheme_account;
            $iteamStore->discount = $request->discount;
            $iteamStore->base_price = $request->base_price;
            $iteamStore->gst = $request->gst;
            $iteamStore->location = $request->location;
            $iteamStore->user_id = $request->user_id;
            $iteamStore->unit = $request->weightage;
            $iteamStore->total_amount = round($request->total_amount, 2);
            $iteamStore->textable = $request->textable;
            $iteamStore->item_id = $request->item_id;
            $iteamStore->margin = $request->margin;
            $iteamStore->weightage = $request->weightage;
            $iteamStore->net_rate = $request->net_rate;
            $iteamStore->save();

            return $this->sendResponse([], 'Item Added Successfully');
        } catch (\Exception $e) {
            Log::info("Create Iteam store api" . $e->getMessage());
            return $e->getMessage();
        }
    }
  
  	public function visualItemPurchaseImport(Request $request)
    {
    	$file = $request->file;
      	if(empty($file)) {
        	return $this->sendError('Please select a file');
        } else if(empty($request->distributor_id)) {
        	return $this->sendError('Please select a distributor');
        } else if(isset($request->distributor_id)) {
        	$distributorData = Distributer::where('id',$request->distributor_id)->where('user_id',auth()->user()->id)->first();
            if($distributorData->name == 'OPENING DISTRIBUTOR' && $distributorData->phone_number == '4242424242') {
                $filePath = $file->store('temp');
                $fullPath = storage_path('app/' . $filePath);

                $extension = strtolower($file->getClientOriginalExtension());

                if ($extension === 'csv') {
                    $file = $request->file;
                    $filePath = $file->getRealPath();
                    $data = array_map('str_getcsv', file($filePath));
                    array_shift($data);
                } elseif ($extension === 'xlsx') {
                    $data = $this->parseXlsx($fullPath);
                    array_shift($data);
                } else {
                    return response()->json(['false' => 'Please upload csv , xlsx file']);
                }

                if (isset($data)) {
                    foreach ($data as $listData) {
                        $gst = 12;
                        $freeQty = 0;
                        $discount = 0;
                        $qty = isset($listData[4]) ? $listData[4] : 0;
                        $mrp = is_numeric($listData[6]) ? (float)$listData[6] : 0;

                        $values = $this->calculateAmount($listData[8], $listData[4], $freeQty, $discount, $gst, $mrp);

                        // number hse to j add thase otherwise 1 add thase.
                        $unit = is_numeric($listData[2]) ? $listData[2] : 1;

                        $iteamsData = IteamsModel::where('iteam_name', $listData[1])->where('user_id',auth()->user()->id)->first();
                        if (isset($iteamsData)) {
                            $iteamIds = $iteamsData->id;
                        } else {
                            $iteamNames = new IteamsModel;
                            $iteamNames->iteam_name = isset($listData[1]) ? $listData[1] : '';
                            $iteamNames->old_unit = $unit;
                            $iteamNames->stock = isset($listData[4]) ? $listData[4] : '';
                            $iteamNames->user_id = auth()->user()->id;
                            $iteamNames->save();
                            $iteamIds = $iteamNames->id;
                        }

                        $iteamStore = new iteamPurches;
                        $iteamStore->random_number = $request->random_number;
                        $iteamStore->batch_number = isset($listData[3]) ? $listData[3] : '';
                        $iteamStore->expiry = isset($listData[10]) ? date("m/y", strtotime($listData[10])) : '';
                        $iteamStore->mrp = isset($listData[6]) ? $listData[6] : '';
                        $iteamStore->ptr = isset($listData[8]) ? $listData[8] : '';
                        if ((isset($gst)) && ($gst == '0')) {
                          $iteamStore->gst = 1;
                        } else if ((isset($gst)) && ($gst == '5')) {
                          $iteamStore->gst = 2;
                        } else if ((isset($gst)) && ($gst == '12')) {
                          $iteamStore->gst = 3;
                        } else if ((isset($gst)) && ($gst == '18')) {
                          $iteamStore->gst = 4;
                        } else if ((isset($gst)) && ($gst == '28')) {
                          $iteamStore->gst = 6;
                        }
                        $iteamStore->location = isset($listData[9]) ? $listData[9] : '';
                        $iteamStore->qty = isset($listData[4]) ? $listData[4] : '';
                        $iteamStore->scheme_account = isset($values['scheme_amount']) ? $values['scheme_amount'] : "";
                        $iteamStore->hsn_code = null;
                        $iteamStore->first_qty = isset($freeQty) ? $freeQty : '';
                        $iteamStore->discount = isset($discount) ? $discount : '';
                        $iteamStore->base_price = isset($values['base_price']) ? $values['base_price'] : '';
                        $iteamStore->user_id = auth()->user()->id;
                        $iteamStore->unit = $unit;
                        $iteamStore->item_id = isset($iteamIds) ? $iteamIds : '';
                        $iteamStore->weightage = $unit;
                        $iteamStore->margin = isset($values['margin']) ? $values['margin'] : '';
                        $iteamStore->net_rate = isset($values['net_rate']) ? $values['net_rate'] : '';
                        $iteamStore->total_amount = isset($values['amount']) ? $values['amount'] : '';
                        $iteamStore->save();
                    }
                }

                return $this->sendResponse([], 'Item Import Successfully.');
            } else {
                return $this->sendError('Please select opening distributor');
            }
        } else {
        	$filePath = $file->store('temp');
            $fullPath = storage_path('app/' . $filePath);

            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'csv') {
                $file = $request->file;
                $filePath = $file->getRealPath();
                $data = array_map('str_getcsv', file($filePath));
                array_shift($data);
            } elseif ($extension === 'xlsx') {
                $data = $this->parseXlsx($fullPath);
                array_shift($data);
            } else {
                return response()->json(['false' => 'Please upload csv , xlsx file']);
            }

            if (isset($data)) {
                foreach ($data as $listData) {
                    $gst = 12;
                    $freeQty = 0;
                    $discount = 0;
                    $qty = isset($listData[4]) ? $listData[4] : 0;
                    $mrp = is_numeric($listData[6]) ? (float)$listData[6] : 0;

                    $values = $this->calculateAmount($listData[8], $listData[4], $freeQty, $discount, $gst, $mrp);

                    // number hse to j add thase otherwise 1 add thase.
                    $unit = is_numeric($listData[2]) ? $listData[2] : 1;

                    $iteamsData = IteamsModel::where('iteam_name', $listData[1])->first();
                    if (isset($iteamsData)) {
                        $iteamIds = $iteamsData->id;
                    } else {
                        $iteamNames = new IteamsModel;
                        $iteamNames->iteam_name = isset($listData[1]) ? $listData[1] : '';
                        $iteamNames->old_unit = $unit;
                      	$iteamNames->stock = isset($listData[4]) ? $listData[4] : '';
                      	$iteamNames->user_id = auth()->user()->id;
                        $iteamNames->save();
                        $iteamIds = $iteamNames->id;
                    }

                    $iteamStore = new iteamPurches;
                    $iteamStore->random_number = $request->random_number;
                    $iteamStore->batch_number = isset($listData[3]) ? $listData[3] : '';
                    $iteamStore->expiry = isset($listData[10]) ? date("m/y", strtotime($listData[10])) : '';
                    $iteamStore->mrp = isset($listData[6]) ? $listData[6] : '';
                    $iteamStore->ptr = isset($listData[8]) ? $listData[8] : '';
                    if ((isset($gst)) && ($gst == '0')) {
                      $iteamStore->gst = 1;
                    } else if ((isset($gst)) && ($gst == '5')) {
                      $iteamStore->gst = 2;
                    } else if ((isset($gst)) && ($gst == '12')) {
                      $iteamStore->gst = 3;
                    } else if ((isset($gst)) && ($gst == '18')) {
                      $iteamStore->gst = 4;
                    } else if ((isset($gst)) && ($gst == '28')) {
                      $iteamStore->gst = 6;
                    }
                  	$iteamStore->location = isset($listData[9]) ? $listData[9] : '';
                    $iteamStore->qty = isset($listData[4]) ? $listData[4] : '';
                    $iteamStore->scheme_account = isset($values['scheme_amount']) ? $values['scheme_amount'] : "";
                    $iteamStore->hsn_code = null;
                    $iteamStore->first_qty = isset($freeQty) ? $freeQty : '';
                    $iteamStore->discount = isset($discount) ? $discount : '';
                    $iteamStore->base_price = isset($values['base_price']) ? $values['base_price'] : '';
                    $iteamStore->user_id = auth()->user()->id;
                    $iteamStore->unit = $unit;
                    $iteamStore->item_id = isset($iteamIds) ? $iteamIds : '';
                    $iteamStore->weightage = $unit;
                    $iteamStore->margin = isset($values['margin']) ? $values['margin'] : '';
                    $iteamStore->net_rate = isset($values['net_rate']) ? $values['net_rate'] : '';
                    $iteamStore->total_amount = isset($values['amount']) ? $values['amount'] : '';
                    $iteamStore->save();
                }
            }

            return $this->sendResponse([], 'Item Import Successfully.');
        }
    }

    public function purchaseItemImport(Request $request)
    {
        $file = $request->file;
        $filePath = $file->store('temp');
        $fullPath = storage_path('app/' . $filePath);

        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'csv') {
            $file = $request->file;
            $filePath = $file->getRealPath();
            $data = array_map('str_getcsv', file($filePath));
            array_shift($data);
        } elseif ($extension === 'xlsx') {
            $data = $this->parseXlsx($fullPath);
            array_shift($data);
        } else {
            return response()->json(['false' => 'Please upload csv , xlsx file']);
        }
      	
        if (isset($data)) {
            foreach ($data as $listData) {
                if ((isset($listData)) && ($listData[0] == 'T')) {
                    $sgst = isset($listData[28]) ? $listData[28] : 0;
                    $cgst = isset($listData[29]) ? $listData[29] : 0;

                    $freeQty = is_numeric($listData[21]) ? (float)$listData[21] : 0.0;
                    $discount = is_numeric($listData[22]) ? (float)$listData[22] : 0.0;
                    $gst = $sgst + $cgst;
                    $mrp = is_numeric($listData[16]) ? (float)$listData[16] : 0.0;

                    $values = $this->calculateAmount($listData[13], $listData[20], $freeQty, $discount, $gst, $mrp);
                  
                  	// number hse to j add thase otherwise 1 add thase.
                  	$unit = is_numeric($listData[6]) ? $listData[6] : 1;

                    $iteamsData = IteamsModel::where('iteam_name', $listData[5])->first();
                    if (isset($iteamsData)) {
                        $iteamIds = $iteamsData->id;
                    } else {
                        $iteamNames = new IteamsModel;
                        $iteamNames->iteam_name = isset($listData[5]) ? $listData[5] : '';
                        $iteamNames->old_unit = $unit;
                        $iteamNames->user_id = auth()->user()->id;
                        $iteamNames->save();
                        $iteamIds = $iteamNames->id;
                    }

                    $iteamStore = new iteamPurches;
                    $iteamStore->random_number = $request->random_number;
                    $iteamStore->batch_number = isset($listData[8]) ? $listData[8] : '';
                    $iteamStore->expiry = isset($listData[9]) ? date("m/y", strtotime($listData[9])) : '';
                    $iteamStore->mrp = isset($listData[16]) ? $listData[16] : '';
                    $iteamStore->ptr = isset($listData[13]) ? $listData[13] : '';
                    if ((isset($gst)) && ($gst == '0')) {
                        $iteamStore->gst = 1;
                    } else if ((isset($gst)) && ($gst == '5')) {
                        $iteamStore->gst = 2;
                    } else if ((isset($gst)) && ($gst == '12')) {
                        $iteamStore->gst = 3;
                    } else if ((isset($gst)) && ($gst == '18')) {
                        $iteamStore->gst = 4;
                    } else if ((isset($gst)) && ($gst == '28')) {
                        $iteamStore->gst = 6;
                    }
                    $iteamStore->qty = isset($listData[20]) ? $listData[20] : '';
                    $iteamStore->scheme_account =  isset($values['scheme_amount']) ? $values['scheme_amount'] : "";
                    $iteamStore->hsn_code = isset($listData[26]) ? $listData[26] : '';
                    $iteamStore->first_qty = isset($listData[21]) ? $listData[21] : '';
                    $iteamStore->discount = isset($listData[22]) ? $listData[22] : '';
                    $iteamStore->base_price = isset($values['base_price']) ? $values['base_price'] : '';
                    $iteamStore->user_id = auth()->user()->id;
                    $iteamStore->unit = $unit;
                    $iteamStore->item_id = isset($iteamIds) ? $iteamIds : '';
                    $iteamStore->weightage = $unit;
                    $iteamStore->margin = isset($values['margin']) ? $values['margin'] : '';
                    $iteamStore->net_rate = isset($values['net_rate']) ? $values['net_rate'] : '';
                    $iteamStore->total_amount = isset($values['amount']) ? $values['amount'] : '';
                    $iteamStore->save();
                }
            }
        }

        return $this->sendResponse([], 'Iteam Added Successfully.');
    }

    public function calculateAmount($ptr, $qty, $freeQty, $discount, $gst, $mrp)
    {
        // Ensure inputs are numeric
        $ptr = is_numeric($ptr) ? (float)$ptr : 0.0;
        $qty = is_numeric($qty) ? (float)$qty : 0.0;
        $freeQty = is_numeric($freeQty) ? (float)$freeQty : 0.0;
        $discount = is_numeric($discount) ? (float)$discount : 0.0;
        $gst = is_numeric($gst) ? (float)$gst : 0.0;
        $mrp = is_numeric($mrp) ? (float)$mrp : 0.0;

        // Scheme Amount Calculation
        $schemeCalculateAmount = ($ptr * ($discount / 100)) * $qty;
        $schemeAmount = number_format($schemeCalculateAmount, 2, '.', '');

        // Base Price Calculation
        $basePriceCalculate = ($ptr * $qty) - $schemeCalculateAmount;
        $basePrice = number_format($basePriceCalculate, 2, '.', '');

        // Amount Calculation
        $amountCalculate = $basePriceCalculate + ($basePriceCalculate * ($gst / 100));
        $amount = number_format($amountCalculate, 2, '.', '');

        // Net Rate Calculation
        $totalQty = $qty + $freeQty;
        $amountPerUnit = ($totalQty > 0) ? ($amountCalculate / $totalQty) : 0;
        $netRate = number_format($amountPerUnit, 2, '.', '');

        // Margin Calculation
        $mrpDifference = $mrp - $amountPerUnit;
        $percentageDifference = ($mrp > 0) ? (($mrpDifference / $mrp) * 100) : 0;
        $margin = number_format($percentageDifference, 2, '.', '');

        return [
            'scheme_amount' => $schemeAmount,
            'base_price' => $basePrice,
            'amount' => $amount,
            'net_rate' => $netRate,
            'margin' => $margin,
        ];
    }

    public function technoItemImport(Request $request)
    {
        $file = $request->file;
        $filePath = $file->store('temp');
        $fullPath = storage_path('app/' . $filePath);

        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'csv') {
            $file = $request->file;
            $filePath = $file->getRealPath();
            $data = array_map('str_getcsv', file($filePath));
            array_shift($data);
        } elseif ($extension === 'xlsx') {
            $data = $this->parseXlsx($fullPath);
            array_shift($data);
        } else {
            return response()->json(['false' => 'Please upload csv , xlsx file']);
        }

        if (isset($data)) {
            foreach ($data as $listData) {
                if ((isset($listData)) && ($listData[0] == 'T')) {
                    $sgst = isset($listData[22]) ? $listData[22] : 0;
                    $cgst = isset($listData[26]) ? $listData[26] : 0;

                    $freeQty = is_numeric($listData[16]) ? (float)$listData[16] : 0.0;
                    $discount = is_numeric($listData[17]) ? (float)$listData[17] : 0.0;
                    $gst = $sgst + $cgst;
                    $mrp = is_numeric($listData[12]) ? (float)$listData[12] : 0.0;

                    $values = $this->calculateAmount($listData[10], $listData[15], $freeQty, $discount, $gst, $mrp);
                  
                  	// number hse to j add thase otherwise 1 add thase.
                  	$unit = is_numeric($listData[6]) ? $listData[6] : 1;

                    $iteamsData = IteamsModel::where('iteam_name', $listData[5])->first();
                    if (isset($iteamsData)) {
                        $iteamIds = $iteamsData->id;
                    } else {
                        $iteamNames = new IteamsModel;
                        $iteamNames->iteam_name = isset($listData[5]) ? $listData[5] : '';
                        $iteamNames->old_unit = $unit;
                        $iteamNames->user_id = auth()->user()->id;
                        $iteamNames->save();
                        $iteamIds = $iteamNames->id;
                    }

                    $date = isset($listData[9]) ? DateTime::createFromFormat('mdY', $listData[9]) : '';

                    $iteamStore = new iteamPurches;
                    $iteamStore->random_number = $request->random_number;
                    $iteamStore->batch_number = isset($listData[8]) ? $listData[8] : '';
                    $iteamStore->expiry = isset($listData[9]) ? $date->format('m/y') : '';
                    $iteamStore->mrp = isset($listData[12]) ? $listData[12] : '';
                    $iteamStore->ptr = isset($listData[10]) ? $listData[10] : '';
                    if ((isset($gst)) && ($gst == '0')) {
                        $iteamStore->gst = 1;
                    } else if ((isset($gst)) && ($gst == '5')) {
                        $iteamStore->gst = 2;
                    } else if ((isset($gst)) && ($gst == '12')) {
                        $iteamStore->gst = 3;
                    } else if ((isset($gst)) && ($gst == '18')) {
                        $iteamStore->gst = 4;
                    } else if ((isset($gst)) && ($gst == '28')) {
                        $iteamStore->gst = 6;
                    }
                    $iteamStore->qty = isset($listData[15]) ? $listData[15] : '';
                    $iteamStore->scheme_account =  isset($values['scheme_amount']) ? $values['scheme_amount'] : "";
                    $iteamStore->hsn_code = isset($listData[30]) ? $listData[30] : '';
                    $iteamStore->first_qty = isset($listData[16]) ? $listData[16] : '';
                    $iteamStore->discount = isset($listData[17]) ? $listData[17] : '';
                    $iteamStore->base_price = isset($values['base_price']) ? $values['base_price'] : '';
                    $iteamStore->user_id = auth()->user()->id;
                    $iteamStore->unit = $unit;
                    $iteamStore->item_id = isset($iteamIds) ? $iteamIds : '';
                    $iteamStore->weightage = $unit;
                    $iteamStore->margin = isset($values['margin']) ? $values['margin'] : '';
                    $iteamStore->net_rate = isset($values['net_rate']) ? $values['net_rate'] : '';
                    $iteamStore->total_amount = isset($values['amount']) ? $values['amount'] : '';
                    $iteamStore->save();
                }
            }
        }

        return $this->sendResponse([], 'Iteam Added Successfully.');
    }

    public function mahalaxmiItemImport(Request $request)
    {
        $file = $request->file;
        $filePath = $file->store('temp');
        $fullPath = storage_path('app/' . $filePath);

        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'csv') {
            $file = $request->file;
            $filePath = $file->getRealPath();
            $data = array_map('str_getcsv', file($filePath));
            array_shift($data);
        } elseif ($extension === 'xlsx') {
            $data = $this->parseXlsx($fullPath);
            array_shift($data);
        } else {
            return response()->json(['false' => 'Please upload csv , xlsx file']);
        }

        if (isset($data)) {
            foreach ($data as $listData) {
                if ((isset($listData)) && ($listData[0] == 'T')) {
                    $freeQty = is_numeric($listData[21]) ? (float)$listData[21] : 0.0;
                    $discount = is_numeric($listData[22]) ? (float)$listData[22] : 0.0;
                    $gst = isset($listData[12]) ? $listData[12] : 0;
                    $mrp = is_numeric($listData[16]) ? (float)$listData[16] : 0.0;

                    $values = $this->calculateAmount($listData[14], $listData[20], $freeQty, $discount, $gst, $mrp);
                  
                  	// number hse to j add thase otherwise 1 add thase.
                   	$unit = is_numeric($listData[6]) ? $listData[6] : 1;

                    $iteamsData = IteamsModel::where('iteam_name', $listData[5])->first();
                    if (isset($iteamsData)) {
                        $iteamIds = $iteamsData->id;
                    } else {
                        $iteamNames = new IteamsModel;
                        $iteamNames->iteam_name = isset($listData[5]) ? $listData[5] : '';
                        $iteamNames->old_unit = $unit;
                      	$iteamNames->user_id = auth()->user()->id;
                        $iteamNames->save();
                        $iteamIds = $iteamNames->id;
                    }

                    $date = isset($listData[9]) ? DateTime::createFromFormat('mdY', $listData[9]) : '';

                    $iteamStore = new iteamPurches;
                    $iteamStore->random_number = $request->random_number;
                    $iteamStore->batch_number = isset($listData[8]) ? $listData[8] : '';
                    $iteamStore->expiry = isset($listData[9]) ? $date->format('m/y') : '';
                    $iteamStore->mrp = isset($listData[16]) ? $listData[16] : '';
                    $iteamStore->ptr = isset($listData[14]) ? $listData[14] : '';
                    if ((isset($gst)) && ($gst == '0')) {
                        $iteamStore->gst = 1;
                    } else if ((isset($gst)) && ($gst == '5')) {
                        $iteamStore->gst = 2;
                    } else if ((isset($gst)) && ($gst == '12')) {
                        $iteamStore->gst = 3;
                    } else if ((isset($gst)) && ($gst == '18')) {
                        $iteamStore->gst = 4;
                    } else if ((isset($gst)) && ($gst == '28')) {
                        $iteamStore->gst = 6;
                    }
                    $iteamStore->qty = isset($listData[20]) ? $listData[20] : '';
                    $iteamStore->scheme_account =  isset($values['scheme_amount']) ? $values['scheme_amount'] : "";
                    $iteamStore->hsn_code = isset($listData[38]) ? $listData[38] : '';
                    $iteamStore->first_qty = isset($listData[21]) ? $listData[21] : '';
                    $iteamStore->discount = isset($listData[22]) ? $listData[22] : '';
                    $iteamStore->base_price = isset($values['base_price']) ? $values['base_price'] : '';
                    $iteamStore->user_id = auth()->user()->id;
                    $iteamStore->unit = $unit;
                    $iteamStore->item_id = isset($iteamIds) ? $iteamIds : '';
                    $iteamStore->weightage = $unit;
                    $iteamStore->margin = isset($values['margin']) ? $values['margin'] : '';
                    $iteamStore->net_rate = isset($values['net_rate']) ? $values['net_rate'] : '';
                    $iteamStore->total_amount = isset($values['amount']) ? $values['amount'] : '';
                    $iteamStore->save();
                }
            }
        }

        return $this->sendResponse([], 'Iteam Added Successfully');
    }

    public function pharmabyteItemImport(Request $request)
    {
        $file = $request->file;
        $filePath = $file->store('temp');
        $fullPath = storage_path('app/' . $filePath);

        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'csv') {
            $file = $request->file;
            $filePath = $file->getRealPath();
            $data = array_map('str_getcsv', file($filePath));
            array_shift($data);
        } elseif ($extension === 'xlsx') {
            $data = $this->parseXlsx($fullPath);
            array_shift($data);
        } else {
            return response()->json(['false' => 'Please upload csv , xlsx file']);
        }

        if (isset($data)) {
            foreach ($data as $listData) {
                if ((isset($listData)) && ($listData[0] == 'T')) {
                    $sgst = isset($listData[28]) ? $listData[28] : 0;
                    $cgst = isset($listData[29]) ? $listData[29] : 0;

                    $freeQty = is_numeric($listData[21]) ? (float)$listData[21] : 0.0;
                    $discount = is_numeric($listData[22]) ? (float)$listData[22] : 0.0;
                    $gst = $sgst + $cgst;
                    $mrp = is_numeric($listData[16]) ? (float)$listData[16] : 0.0;

                    $values = $this->calculateAmount($listData[13], $listData[20], $freeQty, $discount, $gst, $mrp);
                  
                  	// number hse to j add thase otherwise 1 add thase.
                  	$unit = is_numeric($listData[6]) ? $listData[6] : 1;

                    $iteamsData = IteamsModel::where('iteam_name', $listData[5])->first();
                    if (isset($iteamsData)) {
                        $iteamIds = $iteamsData->id;
                    } else {
                        $iteamNames = new IteamsModel;
                        $iteamNames->iteam_name = isset($listData[5]) ? $listData[5] : '';
                        $iteamNames->old_unit = $unit;
                      	$iteamNames->user_id = auth()->user()->id;
                        $iteamNames->save();
                        $iteamIds = $iteamNames->id;
                    }

                    $iteamStore = new iteamPurches;
                    $iteamStore->random_number = $request->random_number;
                    $iteamStore->batch_number = isset($listData[8]) ? $listData[8] : '';
                    $iteamStore->expiry = isset($listData[9]) ? date("m/y", strtotime($listData[9])) : '';
                    $iteamStore->mrp = isset($listData[16]) ? $listData[16] : '';
                    $iteamStore->ptr = isset($listData[13]) ? $listData[13] : '';
                    if ((isset($gst)) && ($gst == '0')) {
                        $iteamStore->gst = 1;
                    } else if ((isset($gst)) && ($gst == '5')) {
                        $iteamStore->gst = 2;
                    } else if ((isset($gst)) && ($gst == '12')) {
                        $iteamStore->gst = 3;
                    } else if ((isset($gst)) && ($gst == '18')) {
                        $iteamStore->gst = 4;
                    } else if ((isset($gst)) && ($gst == '28')) {
                        $iteamStore->gst = 6;
                    }
                    $iteamStore->qty = isset($listData[20]) ? $listData[20] : '';
                    $iteamStore->scheme_account =  isset($values['scheme_amount']) ? $values['scheme_amount'] : "";
                    $iteamStore->hsn_code = isset($listData[26]) ? $listData[26] : '';
                    $iteamStore->first_qty = isset($listData[21]) ? $listData[21] : '';
                    $iteamStore->discount = isset($listData[22]) ? $listData[22] : '';
                    $iteamStore->base_price = isset($values['base_price']) ? $values['base_price'] : '';
                    $iteamStore->user_id = auth()->user()->id;
                    $iteamStore->unit = $unit;
                    $iteamStore->item_id = isset($iteamIds) ? $iteamIds : '';
                    $iteamStore->weightage = $unit;
                    $iteamStore->margin = isset($values['margin']) ? $values['margin'] : '';
                    $iteamStore->net_rate = isset($values['net_rate']) ? $values['net_rate'] : '';
                    $iteamStore->total_amount = isset($values['amount']) ? $values['amount'] : '';
                    $iteamStore->save();
                }
            }
        }

        return $this->sendResponse([], 'Iteam Added Successfully');
    }

    public function purchasesIteamUpload(Request $request)
    {

        $file = $request->file;
        $filePath = $file->store('temp');
        $fullPath = storage_path('app/' . $filePath);

        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'csv') {
            $file = $request->file;
            $filePath = $file->getRealPath();
            $data = array_map('str_getcsv', file($filePath));
            array_shift($data);
        } elseif ($extension === 'xlsx') {
            $data = $this->parseXlsx($fullPath);
            array_shift($data);
        } else {
            return response()->json(['false' => 'Please upload csv , xlsx file']);
        }


        if (isset($data)) {
            foreach ($data as $list) {

                $iteamName = isset($list[0]) ? $list[0] : "";
                if ((isset($iteamName)) && ($iteamName != "")) {
                  
                  	$unit = is_numeric($list[1]) ? $list[1] : 1;

                    $iteamsData = IteamsModel::where('iteam_name', $iteamName)->first();
                    if (isset($iteamsData)) {
                        $iteamIds = $iteamsData->id;
                    } else {
                        $iteamNames = new IteamsModel;
                        $iteamNames->iteam_name = $iteamName;
                        $iteamNames->old_unit = $unit;
                      	$iteamNames->user_id = auth()->user()->id;
                        $iteamNames->save();
                        $iteamIds = $iteamNames->id;
                    }

                    $gstName = isset($list[12]) ? $list[12] : "";
                    $gstTotal = GstModel::where('id', $gstName)->first();

                    $iteamStore = new iteamPurches;
                    $iteamStore->random_number = $request->random_number;
                    $iteamStore->batch_number =  isset($list[3]) ? $list[3] : "";
                    $iteamStore->expiry = isset($list[4]) ? $list[4] : "";
                    $iteamStore->mrp = isset($list[5]) ? $list[5] : "";
                    $iteamStore->ptr = isset($list[8]) ? $list[8] : "";
                    $iteamStore->qty = isset($list[6]) ? $list[6] : "";
                    $iteamStore->hsn_code =  isset($list[2]) ? $list[2] : "";
                    $iteamStore->first_qty = isset($list[7]) ? $list[7] : "";
                    $iteamStore->scheme_account =  isset($list[10]) ? $list[10] : "";
                    $iteamStore->discount = isset($list[9]) ? $list[9] : "";
                    $iteamStore->base_price = isset($list[11]) ? $list[11] : "";
                    $iteamStore->gst = isset($gstTotal->name) ? $gstTotal->name : "";
                    $iteamStore->location = isset($list[13]) ? $list[13] : "";
                    $iteamStore->user_id = auth()->user()->id;
                    $iteamStore->unit = $unit;
                    $iteamStore->total_amount = isset($list[16]) ? $list[16] : "";

                    $iteamStore->item_id = $iteamIds;
                    $iteamStore->margin = isset($list[15]) ? $list[15] : "";
                    $iteamStore->weightage = $unit;
                    $iteamStore->net_rate = isset($list[14]) ? $list[14] : "";
                    $iteamStore->save();
                }
            }
        }
        return $this->sendResponse([], 'Iteam Added Successfully');
    }

    private function parseXlsx($filePath)
    {
        $data = [];
        $zip = new ZipArchive();
        if ($zip->open($filePath) === true) {
            $sharedStrings = [];
            $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
            if ($sharedStringsXml) {
                $sharedStringsXmlObj = simplexml_load_string($sharedStringsXml);
                foreach ($sharedStringsXmlObj->si as $item) {
                    $sharedStrings[] = (string) $item->t;
                }
            }

            $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
            if ($sheetXml) {
                $sheetXmlObj = simplexml_load_string($sheetXml);
                foreach ($sheetXmlObj->sheetData->row as $row) {
                    $rowData = [];
                    foreach ($row->c as $cell) {
                        $cellValue = (string) $cell->v;
                        $cellType = (string) $cell['t'];

                        if ($cellType === 's') {
                            $cellValue = $sharedStrings[intval($cellValue)] ?? $cellValue;
                        }

                        $rowData[] = $cellValue;
                    }
                    $data[] = $rowData;
                }
            }
            $zip->close();
        }
        return $data;
    }

    public function itemPurchasUpdatee(Request $request)
    {
        try {
            $iteamStore = iteamPurches::find($request->id);
            $iteamStore->random_number = $request->random_number;
            $iteamStore->batch_number = $request->batch_number;
            $iteamStore->expiry = $request->expiry;
            $iteamStore->mrp = $request->mrp;
            $iteamStore->ptr = $request->ptr;
            $iteamStore->qty = $request->qty;
            $iteamStore->hsn_code = $request->hsn_code;
            $iteamStore->first_qty = $request->free_qty;
            $iteamStore->scheme_account = $request->scheme_account;
            $iteamStore->discount = $request->discount;
            $iteamStore->base_price = $request->base_price;
            $iteamStore->gst = $request->gst;
            $iteamStore->location = $request->location;
            $iteamStore->user_id = $request->user_id;
            $iteamStore->unit = $request->unit;
            $iteamStore->total_amount = round($request->total_amount, 2);
            $iteamStore->textable = $request->textable;
            $iteamStore->margin = $request->margin;
            $iteamStore->weightage = $request->weightage;
            $iteamStore->net_rate = $request->net_rate;
            $iteamStore->update();

            return $this->sendResponse([], 'Item Updated Successfully');
        } catch (\Exception $e) {
            Log::info("Create Iteam store api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function itemDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Enter Package Id',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $packageDelete = iteamPurches::find($request->id);

            if (!$packageDelete) {
                return $this->sendError('Item not found.');
            }

            $userId = auth()->user()->id;
            $itemId = $packageDelete->item_id;
            $batchNo = $packageDelete->batch_number;

            // Delete specific ledger entry for this purchase
            $ledgerEntry = LedgerModel::where('iteam_id', $itemId)
                ->where('batch', $batchNo)
                ->where('user_id', $userId)
                ->first();
            if ($ledgerEntry) {
                $ledgerEntry->delete();
            }

            // Update Batch Stock
            $batchData = BatchModel::where('item_id', $itemId)
                ->where('batch_name', $batchNo)
                ->where('user_id', $userId)
                ->first();

            if ($batchData) {
                $batchData->qty -= (int) $packageDelete->qty;
                $batchData->free_qty -= (int) $packageDelete->first_qty;
                $batchData->purches_qty = $batchData->qty;
                $batchData->purches_free_qty = $batchData->free_qty;
                $batchData->total_qty = ($batchData->qty + $batchData->free_qty) * $packageDelete->unit;

                if ($batchData->qty <= 0 && $batchData->free_qty <= 0) {
                    $batchData->delete(); // remove batch if no stock
                } else {
                    $batchData->save();
                }
            }

            // Update Final Purchase Item Stock
            $finalItem = FinalPurchesItem::where('iteam_id', $itemId)
                ->where('batch', $batchNo)
                ->where('user_id', $userId)
                ->first();

            if ($finalItem) {
                $finalItem->qty -= (int) $packageDelete->qty;
                $finalItem->fr_qty -= (int) $packageDelete->first_qty;

                if ($finalItem->qty <= 0 && $finalItem->fr_qty <= 0) {
                    $finalItem->delete();
                } else {
                    $finalItem->save();
                }
            }

            // Delete purchase item
            $packageDelete->delete();

            // Recalculate Ledger Balances for this item
            $ledgerData = LedgerModel::where('iteam_id', $itemId)
                ->where('user_id', $userId)
                ->orderBy('id')
                ->get();

            $balance = 0;
            foreach ($ledgerData as $entry) {
                $in = (int) $entry->in;
                $out = (int) $entry->out;
                $balance += $in - $out;
                $entry->balance_stock = $balance;
                $entry->save();
            }

            return $this->sendResponse('', 'Item Deleted Successfully.');
        } catch (\Exception $e) {
            Log::error("Item Delete Error: " . $e->getMessage());
            return $this->sendError('Something went wrong: ' . $e->getMessage());
        }
    }

    public function itemDeleteAll(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'random_number' => 'required',
            ], [
                'random_number.required' => 'Enter Random Number',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $packageDelete = iteamPurches::where('random_number', $request->random_number)->get();
            if (isset($packageDelete)) {
                foreach ($packageDelete as $list) {
                    $list->delete();

                    $legaderData  = LedgerModel::where('iteam_id', $list->item_id)->where('user_id', auth()->user()->id)->orderBy('id')->get();

                    if (isset($legaderData)) {
                        $prevStock = null;
                        foreach ($legaderData as $ListData) {
                            $ListData->delete();
                        }
                    }
                }
            }

            return $this->sendResponse('', 'Item Deleted Successfully.');
        } catch (\Exception $e) {
            Log::info("Create Item store api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function itemDeleteNewAll(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'random_number' => 'required',
            ], [
                'random_number.required' => 'Enter Random Number',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $packageDelete = iteamPurches::where('random_number', $request->random_number)->get();
            if (isset($packageDelete)) {
                foreach ($packageDelete as $list) {
                    $list->delete();

                    $legaderData  = LedgerModel::where('iteam_id', $list->item_id)->where('user_id', auth()->user()->id)->orderBy('id')->get();

                    if (isset($legaderData)) {
                        $prevStock = null;
                        foreach ($legaderData as $ListData) {
                            $ListData->delete();
                        }
                    }
                }
            }

            return $this->sendResponse('', 'Item Deleted Successfully.');
        } catch (\Exception $e) {
            Log::info("Create Item store api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function itemList(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'random_number' => 'required',
            ], [
                'random_number.required' => 'Enter Random Number',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $iteamData = iteamPurches::where('random_number', $request->random_number);
            // $limit = 10;
            // $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            // $offset = ($page - 1) * $limit;
            // $iteamData->offset($offset)->limit($limit);
            $iteamData = $iteamData->get();

            $itemDataList = [];
            $iteamIds = [];
            $iteamQty = [];
            $iteamMargin = [];
            $iteamGst = [];
            $totalBase = [];
            $nateRates = [];
            $iteamAmount = [];
            $iteamNetRate = [];
            $iteamGstNew = [];
            $iteamfreeQty = [];
            if (isset($iteamData)) {
                foreach ($iteamData as $key => $list) {
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = [auth()->user()->id];

                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $userData = User::where('id', $list->user_id)->first();
                    $itemName  = IteamsModel::where('id', $list->item_id)->first();
                    $batchStock = BatchModel::where('batch_name', $list->batch_number)->where('item_id', $list->item_id)->whereIn('user_id', $allUserId)->first();
                    $uniteName = UniteTable::where('id', $list->unit)->first();
                    $gstName = GstModel::where('id', $list->gst)->first();
                    $itemDataList[$key]['id'] = isset($list->id) ? $list->id : "";
                    $itemDataList[$key]['iteam_name'] = isset($itemName->iteam_name) ? $itemName->iteam_name : "";
                    $itemDataList[$key]['front_photo'] = isset($itemName->front_photo) ? asset('/public/front_photo/' . $itemName->front_photo) : "";
                    $itemDataList[$key]['random_number'] = isset($list->random_number) ? $list->random_number : "";
                    $itemDataList[$key]['batch_number'] = isset($list->batch_number) ? $list->batch_number : '';
                    $itemDataList[$key]['total_stock'] = isset($batchStock->total_qty) ? $batchStock->total_qty : '';
                    $itemDataList[$key]['expiry'] = isset($list->expiry) ? $list->expiry : "";
                    $itemDataList[$key]['mrp'] = isset($list->mrp) ? $list->mrp : "";
                    $itemDataList[$key]['net_rate'] = isset($list->net_rate) ? $list->net_rate : "";
                    $itemDataList[$key]['item_id'] = isset($list->item_id) ? $list->item_id : "";
                    $itemDataList[$key]['ptr'] = isset($list->ptr) ? $list->ptr : "";
                    $itemDataList[$key]['user_name'] = isset($userData->name) ? $userData->name : "";
                    $itemDataList[$key]['user_id'] = isset($list->user_id) ? $list->user_id : "";
                    $itemDataList[$key]['qty'] = isset($list->qty) ? $list->qty : "";
                    $itemDataList[$key]['margin'] = isset($list->margin) ? $list->margin : "";
                    $itemDataList[$key]['weightage'] = isset($list->weightage) ? $list->weightage : "";
                    $itemDataList[$key]['free_qty'] = $list->first_qty != null ? $list->first_qty : "";
                    $itemDataList[$key]['scheme_account'] = isset($list->scheme_account) ? $list->scheme_account : "";
                    $itemDataList[$key]['discount'] = isset($list->discount) ? $list->discount : "";
                    $itemDataList[$key]['base_price'] = isset($list->base_price) ? $list->base_price : "";
                    $itemDataList[$key]['gst'] = isset($gstName->name) ? $gstName->name : "";
                    $itemDataList[$key]['gst_id'] = $list->gst != null ? $list->gst : "";
                    $itemDataList[$key]['hsn_code'] = $list->hsn_code != null ? $list->hsn_code : "";
                    $itemDataList[$key]['location'] = isset($list->location) ? $list->location : "";
                    // $itemDataList[$key]['unit'] = isset($uniteName->name) ? $uniteName->name :"";
                    $itemDataList[$key]['unit'] = isset($list->unit) ? $list->unit : "";
                    $itemDataList[$key]['total_amount'] = isset($list->total_amount) ? (string)round($list->total_amount, 2) : "";
                    $itemDataList[$key]['textable'] = isset($list->textable) ? $list->textable : "";
                    $totalQty =  (int)$list->qty;
                    $totalFreeQty =  (int)$list->first_qty != null ? $list->first_qty : 0;
                    $totalMargin = $list->margin;
                    $amountGst = $list->total_amount;
                    $resultGst = isset($gstName->name) ? $gstName->name : 0;

                    $baseAmount = $list->base_price;
                    $NewGst = ($list->base_price * $resultGst) / 100;

                    array_push($iteamQty, $totalQty);
                    array_push($iteamMargin, $totalMargin);
                    array_push($iteamGst, $resultGst);
                    array_push($iteamGstNew, $NewGst);
                    array_push($totalBase, $baseAmount);
                    array_push($nateRates, $list->net_rate);
                    array_push($iteamAmount, $list->mrp);
                    array_push($iteamNetRate, $list->net_rate);
                    array_push($iteamfreeQty, $totalFreeQty);
                }
            }

            // $purchhesData = PurchesDetails::where('iteam_id',$request->item_id)->where('random_number',$request->random_number)->first();
            $iteamDatacounts = iteamPurches::where('random_number', $request->random_number)->get();
            if (isset($iteamDatacounts) && $iteamDatacounts->isNotEmpty()) {
                $totalItems = $iteamDatacounts->count();  // Correct variable name
                $totalAmount = $iteamDatacounts->sum('total_amount');

                // Check to prevent division by zero
                $averageAmount = $totalItems > 0 ? array_sum($iteamMargin) / $totalItems : 0;

                $totalBase = (int)array_sum($totalBase);

                $gstData = $totalItems > 0 ? array_sum($iteamGst) / $totalItems : 0;
                $totalGst = $totalBase * $gstData / 100;

                $totalMarginAmount =  array_sum($iteamAmount) - array_sum($iteamNetRate);

                $iteamDetails['total_qty'] = (string)array_sum($iteamQty);
                $iteamDetails['total_free'] = (string)array_sum($iteamfreeQty);
                $iteamDetails['total_gst'] = (string)array_sum(array_map(function ($value) {
                    return round($value, 2);
                }, $iteamGstNew));
                $iteamDetails['total_margin'] = (string)round($averageAmount, 2);
                $iteamDetails['margin_net_profit'] = (string)round($totalMarginAmount, 2);
                $iteamDetails['total_base'] = (string)$totalBase;
            } else {
                $iteamDetails['total_qty'] = "";
                $iteamDetails['total_free'] = "";
                $iteamDetails['total_gst'] = "";
                $iteamDetails['total_margin'] = "";
                $iteamDetails['margin_net_profit'] = "";
                $iteamDetails['total_base'] = "";
            }
            $iteamDetails['total_net_rate'] = (string)array_sum($nateRates);

            $purchaesLastDate = PurchesModel::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
            $iteamDetails['last_bill_date'] = isset($purchaesLastDate->created_at) ? date("d-m-Y", strtotime($purchaesLastDate->created_at)) : "";

            $totalAmount = $iteamData->sum('total_amount');
            $iteamDetails['item'] = $itemDataList;
            $iteamDetails['sgst'] = isset($list->sgst) ? $list->sgst : "0";
            $iteamDetails['cgst'] = isset($list->cgst) ? $list->cgst : "0";
            $iteamDetails['total_price'] = (string)round($totalAmount, 2); // aa variable app mate che.
          	$iteamDetails['new_total_price'] = round($totalAmount, 2); // aa variable web mate che.

            return $this->sendResponse($iteamDetails, 'Item Purchase List Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("Item list api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function iteamBatchList(Request $request)
    {
        try {
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = [auth()->user()->id];

            $allUserId = array_merge($staffGetData, $ownerGet, $userId);
            $iteamBatch = BatchModel::whereIn('user_id', $allUserId)->get();


            $csvData = [];
            $csvData[] = ['Sr no', 'Iteam Name', 'unit', 'batch', 'Qty', 'MRP', 'Net Rate', 'PTR', 'Location', 'Exp Date', 'Barcode', 'Disc', 'Margin', 'Total By MRP', 'Total By PTR'];
            $i = 1;
            foreach ($iteamBatch as $key => $candidate) {

                $purchesDetails = PurchesDetails::where('batch', $candidate->batch_name)->whereIn('user_id', $allUserId)->first();
                $csvData[] = [
                    $i,
                    $candidate->getIteam->iteam_name ?? '',
                    $candidate->unit ?? '',
                    $candidate->batch_name ?? '',
                    $candidate->total_qty ?? '',
                    $candidate->mrp ?? '',
                    $purchesDetails->net_rate ?? '',
                    $candidate->ptr ?? '',
                    $candidate->location ?? '',
                    $candidate->expiry_date ?? '',
                    $candidate->getIteam->barcode ?? '',
                    $candidate->discount ?? '',
                    $candidate->margin ?? '',
                    $candidate->total_mrp ?? '',
                    $candidate->total_ptr ?? '',
                ];
                $i++;
            }

            return $this->sendResponse($csvData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Item list api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function str_putcsv($input)
    {
        // Handle enclosure of double quotes in CSV
        if (preg_match('/[",]/', $input)) {
            $input = '"' . str_replace('"', '""', $input) . '"';
        }
        return $input;
    }
}
