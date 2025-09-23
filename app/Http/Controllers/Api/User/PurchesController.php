<?php

namespace App\Http\Controllers\Api\User;

use DB;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PurchesModel;
use App\Models\PurchesDetails;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\User;
use App\Models\PurchesReturn;
use App\Models\PurchesReturnDetails;
use App\Models\IteamsModel;
use App\Models\LedgerModel;
use App\Models\UniteTable;
use App\Models\GstModel;
use App\Models\iteamPurches;
use App\Models\FinalPurchesItem;
use App\Models\parcheReturnItemEdit;
use App\Models\BatchModel;
use App\Models\Distributer;
use Illuminate\Support\Facades\Session;
use App\Models\PurchesReturnHistory;
use App\Models\CashManagement;
use App\Models\CashCategory;
use App\Models\BankAccount;
use App\Models\PassBook;
use App\Models\LogsModel;
use App\Models\ItemLocation;
use App\Models\DistributorPrchesReturnTable;
use App\Models\OnlineOrder;
use App\Models\BatchStock;
use App\Models\FinalIteamId;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; // For HTTP requests
use Illuminate\Support\Facades\Artisan;
use App\Models\LicenseModel;

class PurchesController extends ResponseController
{
    // this function use purchase store
    public function purchesStore(Request $request)
    {
        try {
            $purchesReturnData = json_decode($request->purches_return_data);

            $listAmount = [];

            if (isset($purchesReturnData)) {
                foreach ($purchesReturnData as $listData) {
                    array_push($listAmount, $listData->amount);
                }
            }

            $amounts = round($request->net_amount, 2);

            $purchesNew = new PurchesModel;
            $purchesNew->distributor_id = $request->distributor_id;
            $purchesNew->bill_no = $request->bill_no;
            $purchesNew->bill_date = $request->bill_date;
            $purchesNew->due_date = $request->due_date;
            $purchesNew->margin_net_profit = $request->margin_net_profit;
            $purchesNew->sr_no = $request->sr_no;
            $purchesNew->round_off = $request->round_off;
            $purchesNew->net_amount =  round($request->net_amount, 2);
            $purchesNew->total_gst = $request->total_gst;
            $purchesNew->total_amount = round($request->total_amount, 2);
            $purchesNew->pending_amount =  round($request->net_amount, 2);
            $purchesNew->pending_amount_status = '0';
            $purchesNew->payment_type = $request->payment_type;
            $purchesNew->cn_amount = $request->cn_amount;
            $purchesNew->total_gst = $request->total_gst;
            $purchesNew->total_margin = $request->total_margin;
            $purchesNew->sgst = $request->sgst;
            $purchesNew->cgst = $request->cgst;
            $purchesNew->draft_save = $request->draft_save;
            $purchesNew->total_base = $request->total_base;
            $purchesNew->owner_type = $request->owner_type;
            $purchesNew->user_id = $request->user_id;
            $distributorData = Distributer::where('id', $request->distributor_id)->first();
            if (isset($distributorData)) {
                $totalGst = isset($request->total_gst) ? $request->total_gst : 0;
                if (isset($distributor->state) && strtolower($distributor->state) === strtolower($distributorData->state)) {
                    $purchesNew->sgst = $totalGst != 0 ? $totalGst / 2 : "";
                    $purchesNew->cgst =  $totalGst != 0 ? $totalGst / 2 : "";
                } else {
                    $purchesNew->igst = $totalGst;
                }
            }
            $purchesNew->save();

            // $user = User::where('id', $request->distributor_id)->first();
            // if ($user) {
            //     $user->balance = $user->balance - $request->net_amount;
            //     $user->balance_status = '0';
            //     $user->save();
            // }

            $purchesReturnData = json_decode($request->purches_return_data);
            if (isset($purchesReturnData)) {
                foreach ($purchesReturnData as $listReturn) {
                    $newDistributorData = new DistributorPrchesReturnTable;
                    $newDistributorData->user_id = auth()->user()->id;
                    $newDistributorData->distributor_id = $request->distributor_id;
                    $newDistributorData->amount = $listReturn->amount;
                    $newDistributorData->purches_return_bill_id = $listReturn->purches_return_bill_id;
                    $newDistributorData->purches_id = $purchesNew->id;
                    $newDistributorData->save();
                    $totalAmounts = DistributorPrchesReturnTable::where('purches_return_bill_id', $listReturn->purches_return_bill_id)->sum('amount');
                    $purchesBillUpdate = PurchesReturn::where('id', $listReturn->purches_return_bill_id)->where('net_amount', $totalAmounts)->first();
                    if (isset($purchesBillUpdate)) {
                        $purchesBillUpdate->purches_return_status = '1';
                        $purchesBillUpdate->update();
                    }
                }
            }

            $purchesData = json_decode($request->purches_data);
            if (isset($purchesData) && ($request->draft_save != '0')) {
                foreach ($purchesData as $list) {
                    $textbleVlaue = ((int)$list->qty ?? 0) * ((int)$list->ptr ?? 0) - ((int)$list->discount ?? 0);
                    $purchesStore = new PurchesDetails;
                    $purchesStore->purches_id = $purchesNew->id;
                    $purchesStore->iteam_id = $list->item_id;
                    $purchesStore->taxable_value = $textbleVlaue;
                    $purchesStore->batch = $list->batch_number;
                    $purchesStore->exp_dt = $list->expiry;
                    $purchesStore->mrp = $list->mrp;
                    $purchesStore->ptr = $list->ptr;
                    $purchesStore->qty = $list->qty;
                    $purchesStore->fr_qty = $list->free_qty;
                    $purchesStore->net_rate = $list->net_rate;
                    $purchesStore->disocunt = $list->discount;
                    $purchesStore->hsn_code = $list->hsn_code;
                    $purchesStore->gst = $list->gst_id;
                    $purchesStore->user_id = $request->user_id;
                    $purchesStore->location = $list->location;
                    $purchesStore->unit = $list->weightage;
                    $purchesStore->amount = round($list->total_amount, 2);
                    $purchesStore->base = $list->base_price;
                    $purchesStore->weightage = $list->weightage;
                    $purchesStore->textable = $list->textable;
                    $purchesStore->scheme_account = $list->scheme_account;
                    $purchesStore->margin = $list->margin;
                    $purchesStore->random_number = $list->random_number;
                    $purchesStore->iteam_purches_id = $list->id;
                    $purchesStore->save();

                    $batchStockStore = new BatchStock;
                    $batchStockStore->user_id = auth()->user()->id;
                    $batchStockStore->item_id = $list->item_id;
                    $batchStockStore->purchase_id = $purchesNew->id;
                    $batchStockStore->qty = $list->qty;
                    $batchStockStore->free_qty = $list->free_qty;
                    $batchStockStore->batch_number = $list->batch_number;
                    $batchStockStore->save();

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $purchesFinalStore = FinalPurchesItem::whereIn('user_id', $allUserId)->where('batch', $list->batch_number)->where('iteam_id', $list->item_id)->where('mrp', $list->mrp)->where('ptr', $list->ptr)
                        ->where('disocunt', $list->discount)->first();
                    if (isset($purchesFinalStore)) {
                        $batchUpdateQty = BatchStock::whereIn('user_id', $allUserId)->where('item_id', $list->item_id)->sum('qty');
                        $batchUpdateFreeQty = BatchStock::whereIn('user_id', $allUserId)->where('item_id', $list->item_id)->sum('free_qty');

                        $qtyData = $batchUpdateQty;
                        $qtyFreeData = $batchUpdateFreeQty;

                        $purchesFinalStore->iteam_id = $list->item_id;
                        $purchesFinalStore->batch = $list->batch_number;
                        $purchesFinalStore->exp_dt = $list->expiry;
                        $purchesFinalStore->mrp = $list->mrp;
                        $purchesFinalStore->ptr = $list->ptr;
                        $purchesFinalStore->qty = abs($qtyData);
                        $purchesFinalStore->fr_qty = abs($batchUpdateFreeQty);
                        $purchesFinalStore->disocunt = $list->discount;
                        $purchesFinalStore->gst = $list->gst_id;
                        $purchesFinalStore->user_id = $request->user_id;
                        $purchesFinalStore->location = $list->location;
                        $purchesFinalStore->unit = $list->weightage;
                        $totalAmount =  $purchesFinalStore->amount + round($list->total_amount, 2);
                        $purchesFinalStore->amount = abs($totalAmount);
                        $purchesFinalStore->weightage = $list->weightage;
                        $purchesFinalStore->random_number =  $list->random_number;
                        $purchesIteam = $purchesFinalStore->iteam_purches_id . ',' . $list->id;
                        $purchesFinalStore->iteam_purches_id = $purchesIteam;
                        $purchesFinalStore->update();
                        Log::info("Create puchaes bill iteam amount " . $totalAmount);
                        $finalData = new FinalIteamId;
                        $finalData->final_item_id = $purchesFinalStore->id;
                        $finalData->purchase_id = $purchesNew->id;
                        $finalData->save();
                    } else {
                        $purchesFinalStore = new FinalPurchesItem;
                        $purchesFinalStore->iteam_id = $list->item_id;
                        $purchesFinalStore->batch = $list->batch_number;
                        $purchesFinalStore->exp_dt = $list->expiry;
                        $purchesFinalStore->mrp = $list->mrp;
                        $purchesFinalStore->ptr = $list->ptr;
                        $purchesFinalStore->qty = $list->qty;
                        $purchesFinalStore->fr_qty = $list->free_qty;
                        $purchesFinalStore->disocunt = $list->discount;
                        $purchesFinalStore->gst = $list->gst_id;
                        $purchesFinalStore->user_id = $request->user_id;
                        $purchesFinalStore->location = $list->location;
                        $purchesFinalStore->unit = $list->weightage;
                        $purchesFinalStore->amount = $list->total_amount;
                        $purchesFinalStore->weightage = $list->weightage;
                        $purchesFinalStore->random_number = $list->random_number;
                        $purchesFinalStore->iteam_purches_id = $list->id;
                        $purchesFinalStore->save();
                        Log::info("Create puchaes bill iteam amount wgitoout round" . $list->total_amount);
                        Log::info("Create puchaes bill iteam amount " . $purchesFinalStore->amount);
                        $finalData = new FinalIteamId;
                        $finalData->final_item_id = $purchesFinalStore->id;
                        $finalData->purchase_id = $purchesNew->id;
                        $finalData->save();
                    }

                    $userName = Distributer::where('id', $request->distributor_id)->first();

                    $leaderData = new LedgerModel;
                    $leaderData->owner_id = $request->distributor_id;
                    $leaderData->entry_date = $request->bill_date;
                    $leaderData->transction = 'Purchase Invoice';
                    $leaderData->voucher = 'Purchase Invoice';
                    $leaderData->bill_no = '#' . $request->bill_no;
                    $leaderData->puches_id = $purchesNew->id;
                    $leaderData->batch = $list->batch_number;
                    $leaderData->bill_date = $request->bill_date;
                    $leaderData->name = $userName->name;
                    $leaderData->user_id = auth()->user()->id;
                    $leaderData->iteam_id = $list->item_id;
                    $ledgers = LedgerModel::where('iteam_id', $list->item_id)->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                    if (isset($ledgers)) {
                        $qty = (int)$list->qty;
                        $free_qty = (int)$list->free_qty;
                        $weightage = (int)$list->weightage;

                        // Perform the calculation.
                        $totalQty = ($qty + $free_qty) * $weightage;
                        Log::info("Create Iteams balance" . $totalQty);
                        $balance = $totalQty + $ledgers->balance_stock;
                        Log::info("Create Iteams balance total" . $balance);
                        $leaderData->in = ((int)$list->qty + (int)$list->free_qty) * (int)$list->weightage;

                        $leaderData->balance_stock = (int)$ledgers->balance_stock + $totalQty;
                        Log::info("Create Iteams balance total" . $leaderData->balance_stock);
                    } else {
                        $leaderData->in = ((int)$list->qty + (int)$list->free_qty) * (int)$list->weightage;
                        $leaderData->balance_stock = ((int)$list->qty + (int)$list->free_qty) * (int)$list->weightage;
                    }

                    $ledgers = LedgerModel::where('owner_id', $request->distributor_id)->orderBy('id', 'DESC')->first();
                    if (isset($ledgers)) {

                        $total = $ledgers->balance - round($request->net_amount);
                        $leaderData->debit = round($request->net_amount, 2);
                        $leaderData->balance = round($total, 2);
                    } else {
                        $leaderData->debit = round($request->net_amount, 2);
                        $leaderData->balance = round($request->net_amount, 2);
                    }
                    $leaderData->save();

                    $batchModelEdit = BatchModel::whereIn('user_id', $allUserId)->where('batch_number', $list->batch_number)->where('item_id', $list->item_id)->where('mrp', $list->mrp)->where('ptr', $list->ptr)
                        ->where('discount', $list->discount)->first();

                    if (isset($batchModelEdit)) {
                        $batchUpdateQty = BatchStock::whereIn('user_id', $allUserId)->where('item_id', $list->item_id)->sum('qty');
                        $batchUpdateFreeQty = BatchStock::whereIn('user_id', $allUserId)->where('item_id', $list->item_id)->sum('free_qty');

                        $qtyData = $batchUpdateQty;
                        $qtyFreeData = $batchUpdateFreeQty;

                        $batchModelEdit->item_id = $list->item_id;
                        $batchModelEdit->qty =  abs($qtyData);
                        $batchModelEdit->purches_qty = abs($qtyData);
                        $batchModelEdit->purches_free_qty = abs($qtyFreeData);
                        $batchModelEdit->free_qty = abs($qtyFreeData);
                        $batchModelEdit->margin = $list->margin;
                        $batchModelEdit->location = $list->location;
                        $batchModelEdit->stock = '0';
                        $batchModelEdit->discount = $list->discount;
                        $batchModelEdit->gst = $list->gst_id;
                        $batchModelEdit->base = $list->base_price;
                        $batchModelEdit->batch_name = $list->batch_number;
                        $batchModelEdit->expiry_date = $list->expiry;
                        $batchModelEdit->mrp = $list->mrp;
                        $batchModelEdit->unit = $list->weightage;
                        $batchModelEdit->batch_number = $list->batch_number;
                        $batchModelEdit->ptr = $list->ptr;
                        $totalQty = ((int)$list->qty + (int)$list->free_qty) * (int)$list->weightage;
                        $batchModelEdit->total_qty = (int)$batchModelEdit->total_qty + (int)$totalQty;
                        $totalMrp = (int)$list->mrp * (int)$list->qty;
                        $batchModelEdit->total_mrp = (int)$batchModelEdit->total_mrp +  (int)$totalMrp;
                        $totalPtr = (int)$list->ptr * (int)$list->qty;
                        $batchModelEdit->total_ptr = (int)$batchModelEdit->total_ptr + (int) $totalPtr;
                        $batchModelEdit->purches_bill_id_ = $purchesNew->id;
                        $batchModelEdit->save();
                    } else {
                        $batchData = new BatchModel;
                        $batchData->item_id = $list->item_id;
                        $batchData->qty = $list->qty;
                        $batchData->purches_qty = $list->qty;
                        $batchData->purches_free_qty = $list->free_qty;
                        $batchData->free_qty = $list->free_qty;
                        $batchData->margin = $list->margin;
                        $batchData->location = $list->location;
                        $batchData->stock = '0';
                        $batchData->discount = $list->discount;
                        $batchData->gst = $list->gst_id;
                        $batchData->base = $list->base_price;
                        $batchData->expiry_date = $list->expiry;
                        $batchData->mrp = $list->mrp;
                        $batchData->unit = $list->weightage;
                        $userId = auth()->user()->id;
                        $batchData->user_id = $userId;
                        $batchData->batch_name = $list->batch_number;
                        $batchData->batch_number = $list->batch_number;
                        $batchData->ptr = $list->ptr;
                        $batchData->total_qty = ((int)$list->qty + (int)$list->free_qty) * (int) $list->weightage;
                        $batchData->total_mrp = (int)$list->mrp * (int)$list->qty;
                        $batchData->total_ptr = (int)$list->ptr * (int)$list->qty;
                        $batchData->purches_bill_id_ = $purchesNew->id;
                        $batchData->save();
                        Log::info("purchase bill Qty: $list->qty, Free Qty: $list->free_qty, Weightage: $list->weightage, Total: $batchData->total_qty");
                    }

                    $userLogs = new LogsModel;
                    $userLogs->message = 'Batch Added';
                    $userLogs->user_id = auth()->user()->id;
                    $userLogs->date_time = date('Y-m-d H:i a');
                    $userLogs->save();

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                    $orderDelete = OnlineOrder::where('item_id', $list->item_id)->whereIn('user_id', $allUserId)->get();
                    if (isset($orderDelete)) {
                        foreach ($orderDelete as $listDelete) {
                            $listDelete->delete();
                        }
                    }

                    $iteamModel =  IteamsModel::where('id', $list->item_id)->first();
                    if (isset($iteamModel)) {

                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = array(auth()->user()->id);
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                        $itemLocation = ItemLocation::where('item_id', $list->item_id)->whereIn('user_id', $allUserId)->first();

                        if (isset($itemLocation)) {
                            $itemLocation->location = $list->location;
                            $itemLocation->update();
                        } else {
                            $itemLocation = new ItemLocation;
                            $itemLocation->user_id = auth()->user()->id;
                            $itemLocation->item_id =  $list->item_id;
                            $itemLocation->location = $list->location;
                            $itemLocation->save();
                        }
                    }
                }
            } else {
                if (isset($purchesData)) {
                    foreach ($purchesData as $list) {

                        $textbleVlaue = ((int)$list->qty ?? 0) * ((int)$list->ptr ?? 0) - ((int)$list->discount ?? 0);
                        $purchesStore = new PurchesDetails;
                        $purchesStore->purches_id = $purchesNew->id;
                        $purchesStore->iteam_id = $list->item_id;
                        $purchesStore->taxable_value = $textbleVlaue;
                        $purchesStore->batch = $list->batch_number;
                        $purchesStore->exp_dt = $list->expiry;
                        $purchesStore->mrp = $list->mrp;
                        $purchesStore->ptr = $list->ptr;
                        $purchesStore->qty = $list->qty;
                        $purchesStore->fr_qty = $list->free_qty;
                        $purchesStore->net_rate = $list->net_rate;
                        $purchesStore->disocunt = $list->discount;
                        $purchesStore->hsn_code = $list->hsn_code;
                        $purchesStore->gst = $list->gst_id;
                        $purchesStore->user_id = $request->user_id;
                        $purchesStore->location = $list->location;
                        $purchesStore->unit = $list->weightage;
                        $purchesStore->amount = round($list->total_amount, 2);
                        $purchesStore->base = $list->base_price;
                        $purchesStore->weightage = $list->weightage;
                        $purchesStore->textable = $list->textable;
                        $purchesStore->scheme_account = $list->scheme_account;
                        $purchesStore->margin = $list->margin;
                        $purchesStore->random_number = $list->random_number;
                        $purchesStore->iteam_purches_id = $list->id;
                        $purchesStore->save();
                    }
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Purchase Bill Added';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $dataPurcahes = [];
            $dataPurcahes['id'] = isset($purchesNew->id) ? $purchesNew->id : '';
            return $this->sendResponse($dataPurcahes, 'Purchase Added Successfully.');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Create Iteams api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesDetails(Request $request)
    {
        try {
            $purchesData = PurchesModel::where('id', $request->id)->orderBy('id', 'DESC')->first();

            $purchesDetails = [];
            $totalData = [];
            $netRates = [];
            $totalFreeData = [];
            $totalBase = [];
            if (isset($purchesData)) {
                $bankName = BankAccount::where('id', $purchesData->payment_type)->first();
                $TotalAmount = PurchesDetails::where('purches_id', $purchesData->id)->sum('amount');
                $distributorData = Distributer::where('id', $purchesData->distributor_id)->first();
                $userIdData = User::where('id', $purchesData->user_id)->first();
              	
                $purchesDetails['id'] = isset($purchesData->id) ? $purchesData->id : "";
                $purchesDetails['draft_save'] = isset($purchesData->draft_save) ? $purchesData->draft_save : "";
                $purchesDetails['round_off'] = isset($purchesData->round_off) ? $purchesData->round_off : "";
                $purchesDetails['sr_no'] = isset($purchesData->sr_no) ? $purchesData->sr_no : "";
                $purchesDetails['margin_net_profit'] = isset($purchesData->margin_net_profit) ? $purchesData->margin_net_profit : "";
                $purchesDetails['cn_amount'] = isset($purchesData->cn_amount) ? $purchesData->cn_amount : "0";
                $purchesDetails['distributor_id'] = isset($purchesData->distributor_id) ? $purchesData->distributor_id : "";
                $purchesDetails['distributor_name'] = isset($distributorData->name) ? $distributorData->name : "";
                $purchesDetails['user_name'] = isset($userIdData->name) ? $userIdData->name : "";
                $purchesDetails['owner_type'] = isset($purchesData->owner_type) ? $purchesData->owner_type : "";
                $purchesDetails['payment_type'] = isset($bankName->bank_name) ? $bankName->bank_name  : $purchesData->payment_type;
                $purchesDetails['bill_no'] = isset($purchesData->bill_no) ? $purchesData->bill_no : "";
                $purchesDetails['bill_date'] = isset($purchesData->bill_date) ? $purchesData->bill_date : "";
                $purchesDetails['due_date'] = isset($purchesData->due_date) ? $purchesData->due_date : "";
                $purchesDetails['net_amount'] = isset($purchesData->net_amount) ? (string)round($purchesData->net_amount, 2) : "";
                $purchesDetails['total_gst'] = isset($purchesData->total_gst) ? $purchesData->total_gst : "";
                $purchesDetails['total_amount'] = isset($purchesData->total_amount) ? (string) round($purchesData->total_amount, 2) : "";
                $purchesDetails['sgst'] = isset($purchesData->sgst) ? $purchesData->sgst : "0";
                $purchesDetails['cgst'] = isset($purchesData->cgst) ? $purchesData->cgst : "0";
                $purchesDetails['total_gst'] = isset($purchesData->total_gst) ? $purchesData->total_gst : "0";
                $purchesDetails['total_margin'] = isset($purchesData->total_margin) && is_numeric($purchesData->total_margin) ? (string) round((float) $purchesData->total_margin, 2) : "0";
                $purchesDetails['item_list'] = [];

                $purchesItemDetails = PurchesDetails::where('purches_id', $purchesData->id)->orderBy('id', 'DESC')->get();
                if (isset($purchesItemDetails)) {
                  	$totalMRP = "0";
                  	$totalPTR = "0";
                    foreach ($purchesItemDetails as $key => $details) {
                        $iteamModel =  IteamsModel::where('id', $details->iteam_id)->first();
                        $uniteName = UniteTable::where('id', $details->unit)->first();
                        $gstName = GstModel::where('id', $details->gst)->first();
                      	
                      	$mrps = isset($details->mrp) ? $details->mrp : "0";
                      	$ptrs = isset($details->ptr) ? $details->ptr : "0";
                      	$qtys = isset($details->qty) ? $details->qty : "0";
                      	
                      	$totalMRP = $mrps * $qtys;
                      	$totalPTR = $ptrs * $qtys;
                      
                        $purchesDetails['item_list'][$key]['id'] = isset($details->id) ? $details->id : "";
                        $purchesDetails['item_list'][$key]['item_id'] = isset($details->iteam_id) ? $details->iteam_id : "";
                        $purchesDetails['item_list'][$key]['front_photo'] = isset($iteamModel->front_photo) ? asset('/public/front_photo/' . $iteamModel->front_photo) : "";
                        $purchesDetails['item_list'][$key]['item_name'] = isset($iteamModel->iteam_name) ? $iteamModel->iteam_name : "";
                        $purchesDetails['item_list'][$key]['batch_number'] = isset($details->batch) ? $details->batch : "";
                        $purchesDetails['item_list'][$key]['gst_name'] = isset($gstName->name) ? $gstName->name : "";
                        $purchesDetails['item_list'][$key]['expiry'] = isset($details->exp_dt) ? $details->exp_dt : "";
                        $purchesDetails['item_list'][$key]['hsn_code'] = isset($details->hsn_code) ? $details->hsn_code : "";
                        $purchesDetails['item_list'][$key]['mrp'] = isset($details->mrp) ? $details->mrp : "";
                        $purchesDetails['item_list'][$key]['net_rate'] = isset($details->net_rate) ? $details->net_rate : "";
                        $purchesDetails['item_list'][$key]['ptr'] = isset($details->ptr) ? $details->ptr : "";
                        $purchesDetails['item_list'][$key]['random_number'] = isset($details->random_number) ? $details->random_number : "";
                        $purchesDetails['item_list'][$key]['iteam_purches_id'] = isset($details->iteam_purches_id) ? $details->iteam_purches_id : "";
                        $purchesDetails['item_list'][$key]['fr_qty'] = isset($details->fr_qty) ? $details->fr_qty : "";
                        $purchesDetails['item_list'][$key]['qty'] = isset($details->qty) ? $details->qty : "";
                        $purchesDetails['item_list'][$key]['disocunt'] = isset($details->disocunt) ? $details->disocunt : "";
                        $purchesDetails['item_list'][$key]['gst'] =  $details->gst;
                        $purchesDetails['item_list'][$key]['location'] = isset($details->location) ? $details->location : "";
                        $purchesDetails['item_list'][$key]['unit'] = isset($details->unit) ? $details->unit : "";
                        $purchesDetails['item_list'][$key]['amount'] = isset($details->amount) ? (string)round($details->amount, 2) : "";
                        $purchesDetails['item_list'][$key]['base_price'] = isset($details->base) ? $details->base : "";
                        $purchesDetails['item_list'][$key]['weightage'] = isset($details->weightage) ? $details->weightage : "";
                        $purchesDetails['item_list'][$key]['textable'] = isset($details->textable) ? $details->textable : "";
                        $purchesDetails['item_list'][$key]['scheme_account'] = isset($details->scheme_account) ? $details->scheme_account : "";
                        $purchesDetails['item_list'][$key]['margin'] = isset($details->margin) ? $details->margin : "";
                        $totalQty = (int)$details->qty;
                        $totalFreeQty = isset($details->fr_qty) ? (int)$details->fr_qty : 0;
                        $totalBases =  $details->base;
                        array_push($totalData, $totalQty);
                        array_push($totalBase, $totalBases);
                        array_push($totalFreeData, $totalFreeQty);
                        array_push($netRates, $details->net_rate);
                    }
                }

                $purchesDetails['total_qty'] = isset($totalData) ? (string)array_sum($totalData) : "0";
                $purchesDetails['total_net_rate'] = isset($netRates) ? (string)array_sum($netRates) : "0";
                $purchesDetails['total_free_qty'] = isset($totalFreeData) ? (string)array_sum($totalFreeData) : "0";
                $purchesDetails['total_base'] = isset($totalBase) ? (string)array_sum($totalBase) : "0";
              	$purchesDetails['total_mrp'] = isset($totalMRP) ? (string)$totalMRP : "0";
              	$purchesDetails['total_ptr'] = isset($totalPTR) ? (string)$totalPTR : "0";

                //$purchesDetails['margin_net_profit'] = isset($purchesData->margin_net_profit) ? $purchesData->margin_net_profit : "";

                $purchesDetails['cn_bill_list'] = [];
                $cnBillData = DistributorPrchesReturnTable::where('purches_id', $purchesData->id)->get();
                if (isset($cnBillData)) {
                    foreach ($cnBillData as $cn => $listData) {
                        $cnDatas = PurchesReturn::where('id', $listData->purches_return_bill_id)->orderBy('id', 'DESC')->first();
                        $purchesDetails['cn_bill_list'][$cn]['id'] = isset($listData->purches_return_bill_id) ? (int)$listData->purches_return_bill_id : "";
                        $purchesDetails['cn_bill_list'][$cn]['cn_amount'] = isset($listData->amount) ? $listData->amount : "";
                        $purchesDetails['cn_bill_list'][$cn]['bill_no'] = isset($cnDatas->bill_no) ? $cnDatas->bill_no : "";
                        $purchesDetails['cn_bill_list'][$cn]['bill_date'] = isset($cnDatas->select_date) ? $cnDatas->select_date : "";
                        $purchesDetails['cn_bill_list'][$cn]['total_amount'] = isset($cnDatas->net_amount) ? $cnDatas->net_amount : "";
                    }
                }
            }
            return $this->sendResponse($purchesDetails, 'Purchase Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("Purches Details api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesHistroy(Request $request)
    {
        // $iteamData = json_decode($request->item_id, true);
        ///if (isset($iteamData)) {
        // foreach ($iteamData as $list) {
        $iteamPurches = iteamPurches::where('random_number', $request->random_number)->get();

        if (isset($iteamPurches)) {
            foreach ($iteamPurches as $list) {
                $list->delete();
            }
        }
        //}
        //}

        $iteamPurchesData = PurchesDetails::where('random_number', $request->random_number)->get();
        if (isset($iteamPurchesData)) {
            foreach ($iteamPurchesData as $listData) {
                $iteamStore = new iteamPurches;
                $iteamStore->random_number = $listData->random_number;
                $iteamStore->batch_number = $listData->batch;
                $iteamStore->expiry = $listData->exp_dt;
                $iteamStore->mrp = $listData->mrp;
                $iteamStore->ptr = $listData->ptr;
                $iteamStore->qty = $listData->qty;
                $iteamStore->first_qty = $listData->fr_qty;
                $iteamStore->scheme_account = $listData->scheme_account;
                $iteamStore->discount = $listData->disocunt;
                $iteamStore->base_price = $listData->base;
                $iteamStore->gst = $listData->gst;
                $iteamStore->location = $listData->location;
                $iteamStore->user_id = $listData->user_id;
                $iteamStore->unit = $listData->unit;
                $iteamStore->total_amount = round($listData->amount, 2);
                $iteamStore->textable = $listData->textable;
                $iteamStore->item_id = $listData->iteam_id;
                $iteamStore->margin = $listData->margin;
                $iteamStore->weightage = $listData->weightage;
                $iteamStore->net_rate = $listData->net_rate;
                $iteamStore->hsn_code = $listData->hsn_code;

                $iteamStore->save();
                $listData->iteam_purches_id = $iteamStore->id;
                $listData->update();
            }
        }

        return $this->sendResponse("", 'Purchase Data History Fetch Successfully.');
    }

    public function purchesEditData(Request $request)
    {
        try {
            $purchesData = PurchesModel::where('id', $request->id)->first();

            $purchesDetails = [];
            $totalData = [];
            $iteamMargin = [];
            $iteamGst = [];
            $totalQtyData = [];
            $totalBase = [];
            $netRates = [];
            $iteamMrp = [];
            $iteamNetRate = [];
            $totalFreeQtyData = [];
            if (isset($purchesData)) {
                //  $TotalAmount = PurchesDetails::where('purches_id',$purchesData->id)->sum('amount');
                $TotalAmount =  iteamPurches::where('random_number', $request->random_number)->sum('total_amount');
                $distributorData = Distributer::where('id', $purchesData->distributor_id)->first();
                $userIdData = User::where('id', $purchesData->user_id)->first();
                $purchesDetails['id'] = isset($purchesData->id) ? $purchesData->id : "";
                $purchesDetails['draft_save'] = isset($purchesData->draft_save) ? $purchesData->draft_save : "";
                $purchesDetails['round_off'] = isset($purchesData->round_off) ? $purchesData->round_off : "";
                $purchesDetails['cn_amount'] = isset($purchesData->cn_amount) ? $purchesData->cn_amount : "0";
                $purchesDetails['sr_no'] = isset($purchesData->sr_no) ? $purchesData->sr_no : "";
                $purchesDetails['distributor_id'] = isset($purchesData->distributor_id) ? $purchesData->distributor_id : "";
                $purchesDetails['distributor_name'] = isset($distributorData->name) ? $distributorData->name : "";
                $purchesDetails['user_name'] = isset($userIdData->name) ? $userIdData->name : "";
                $purchesDetails['owner_type'] = isset($purchesData->owner_type) ? $purchesData->owner_type : "";
                $purchesDetails['payment_type'] = isset($purchesData->payment_type) ? $purchesData->payment_type : "";
                $purchesDetails['bill_no'] = isset($purchesData->bill_no) ? $purchesData->bill_no : "";
                $purchesDetails['bill_date'] = isset($purchesData->bill_date) ? $purchesData->bill_date : "";
                $purchesDetails['due_date'] = isset($purchesData->due_date) ? $purchesData->due_date : "";
                //$purchesDetails['net_amount'] = isset($purchesData->net_amount) ? (string)round($purchesData->net_amount, 2) : "";

                // $purchesDetails['total_amount'] = isset($purchesData->total_amount) ? (string) round($purchesData->total_amount, 2) : "";

                $cnAmount = isset($purchesData->cn_amount) ? $purchesData->cn_amount  : 0;
                $amountNew = abs($TotalAmount) - abs($cnAmount);
                $purchesDetails['total_amount'] = isset($TotalAmount) ? (string) abs($TotalAmount) : "";
                $purchesDetails['net_amount'] = isset($amountNew) ? (string)round($amountNew, 2) : "";

                $purchesDetails['sgst'] = isset($purchesData->sgst) ? $purchesData->sgst : "0";
                $purchesDetails['cgst'] = isset($purchesData->cgst) ? $purchesData->cgst : "0";

                $purchesDetails['item_list'] = [];

                $iteamPurchesData =  iteamPurches::where('random_number', $request->random_number)->orderBy('id', 'DESC')->get();

                if (isset($iteamPurchesData)) {
                    foreach ($iteamPurchesData as $key => $listData) {
                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = [auth()->user()->id];

                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                        $iteamModel =  IteamsModel::where('id', $listData->item_id)->first();
                        $uniteName = UniteTable::where('id', $listData->unit)->first();
                        $gstName = GstModel::where('id', $listData->gst)->first();
                        $batchStock = BatchModel::where('batch_name', $listData->batch_number)->where('item_id', $listData->item_id)->whereIn('user_id', $allUserId)->first();
                        $purchesDetails['item_list'][$key]['id'] = isset($listData->id) ? $listData->id : "";
                        $purchesDetails['item_list'][$key]['total_stock'] = isset($batchStock->total_qty) ? $batchStock->total_qty : '';
                        $purchesDetails['item_list'][$key]['item_id'] = isset($listData->item_id) ? $listData->item_id : "";
                        $purchesDetails['item_list'][$key]['front_photo'] = isset($iteamModel->front_photo) ? asset('/public/front_photo/' . $iteamModel->front_photo) : "";
                        $purchesDetails['item_list'][$key]['item_name'] = isset($iteamModel->iteam_name) ? $iteamModel->iteam_name : "";
                        $purchesDetails['item_list'][$key]['batch_number'] = isset($listData->batch_number) ? $listData->batch_number : "";
                        $purchesDetails['item_list'][$key]['gst_name'] = isset($gstName->name) ? $gstName->name : "";
                        $purchesDetails['item_list'][$key]['expiry'] = isset($listData->expiry) ? $listData->expiry : "";
                        $purchesDetails['item_list'][$key]['hsn_code'] = isset($listData->hsn_code) ? $listData->hsn_code : "";
                        $purchesDetails['item_list'][$key]['user_id'] = isset($listData->user_id) ? $listData->user_id : "";
                        $purchesDetails['item_list'][$key]['mrp'] = isset($listData->mrp) ? $listData->mrp : "";
                        $purchesDetails['item_list'][$key]['net_rate'] = isset($listData->net_rate) ? $listData->net_rate : "";
                        $purchesDetails['item_list'][$key]['ptr'] = isset($listData->ptr) ? $listData->ptr : "";
                        $purchesDetails['item_list'][$key]['random_number'] = isset($listData->random_number) ? $listData->random_number : "";
                        $purchesDetails['item_list'][$key]['iteam_purches_id'] = isset($listData->id) ? (string)$listData->id : "";
                        $purchesDetails['item_list'][$key]['fr_qty'] = isset($listData->first_qty) ? $listData->first_qty : "";
                        $purchesDetails['item_list'][$key]['qty'] = isset($listData->qty) ? $listData->qty : "";
                        $purchesDetails['item_list'][$key]['disocunt'] = isset($listData->discount) ? $listData->discount : "";
                        $purchesDetails['item_list'][$key]['gst'] = $listData->gst != "" ? $listData->gst : "";
                        $purchesDetails['item_list'][$key]['location'] = isset($listData->location) ? $listData->location : "";
                        $purchesDetails['item_list'][$key]['unit'] = isset($listData->unit) ? $listData->unit : "";
                        $purchesDetails['item_list'][$key]['amount'] = isset($listData->total_amount) ? (string)round($listData->total_amount, 2) : "";
                        $purchesDetails['item_list'][$key]['base_price'] = isset($listData->base_price) ? (string)round($listData->base_price, 2) : "";
                        $purchesDetails['item_list'][$key]['weightage'] = isset($listData->weightage) ? $listData->weightage : "";
                        $purchesDetails['item_list'][$key]['textable'] = isset($listData->textable) ? $listData->textable : "";
                        $purchesDetails['item_list'][$key]['scheme_account'] = isset($listData->scheme_account) ? $listData->scheme_account : "";
                        $purchesDetails['item_list'][$key]['margin'] = isset($listData->margin) ? $listData->margin : "";

                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = array(auth()->user()->id);
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                        $iteamPurchesBatches = PurchesDetails::where('random_number', $request->random_number)->whereIn('user_id', $allUserId)->where('iteam_id', $listData->item_id)->where('batch', $listData->batch_number)->first();
                        $totalQty =  (int)$listData->qty;
                        $totalFreeQty =  isset($listData->first_qty) ? (int)$listData->first_qty : "";
                        $totalMargin = $listData->margin;
                        $amountGst = $listData->total_amount;
                        $resultGst = isset($gstName->name) ? $gstName->name : "";

                        $baseAmount = isset($listData->base_price) ? (int)$listData->base_price : (int)$iteamPurchesBatches->base;

                        $NewGst = ((int)$baseAmount * (int)$resultGst) / 100;

                        array_push($totalQtyData, $totalQty);
                        array_push($totalFreeQtyData, $totalFreeQty);
                        array_push($iteamMargin, $totalMargin);
                        array_push($iteamGst, $NewGst);
                        array_push($netRates, $listData->net_rate);
                        array_push($totalBase, $baseAmount);
                        array_push($iteamMrp, $listData->mrp);
                        array_push($iteamNetRate, $listData->net_rate);
                    }
                }
                $iteamPurchesData =  iteamPurches::where('random_number', $request->random_number)->orderBy('id', 'DESC')->get();
                if (isset($iteamPurchesData)) {
                    $totalItems = $iteamPurchesData->count();
                    $averageAmount = $totalItems > 0 ? array_sum($iteamMargin) / $totalItems : 0;

                    $totalBase = (int)array_sum($totalBase);
                    $gstData = $totalItems > 0 ? array_sum($iteamGst) / $totalItems : 0;
                    $totalGst = $totalBase * $gstData / 100;

                    $totalMarginProfit = array_sum($iteamMrp) - array_sum($iteamNetRate);

                    $purchesDetails['total_qty'] = isset($totalQtyData) ? (string)array_sum($totalQtyData) : "0";
                    $purchesDetails['total_free_qty'] = isset($totalFreeQtyData) ? (string)array_sum($totalFreeQtyData) : "0";
                    $purchesDetails['total_base'] = (string)round($totalBase, 2);
                    $purchesDetails['total_gst'] = (string)round(array_sum($iteamGst), 2);
                    $purchesDetails['total_margin'] = (string)round($averageAmount, 2);
                    $purchesDetails['margin_net_profit'] = (string)$totalMarginProfit;
                } else {
                    $purchesDetails['total_qty'] =  "0";
                    $purchesDetails['total_base'] = "0";
                    $purchesDetails['total_free_qty'] =  "0";
                    $purchesDetails['total_gst'] = "0";
                    $purchesDetails['total_margin'] = "0";
                    $purchesDetails['margin_net_profit'] = "0";
                }

                $purchesDetails['total_net_rate'] = isset($netRates) ? (string)array_sum($netRates) : "0";
                $purchesDetails['cn_bill_list'] = [];
                $cnBillData = DistributorPrchesReturnTable::where('purches_id', $request->id)->get();
                if (isset($cnBillData)) {
                    foreach ($cnBillData as $cn => $listData) {
                        $cnDatas = PurchesReturn::where('id', $listData->purches_return_bill_id)->orderBy('id', 'DESC')->first();
                        $purchesDetails['cn_bill_list'][$cn]['id'] = isset($listData->purches_return_bill_id) ? (int)$listData->purches_return_bill_id : "";
                        $purchesDetails['cn_bill_list'][$cn]['cn_amount'] = isset($listData->amount) ? $listData->amount : "";
                        $purchesDetails['cn_bill_list'][$cn]['bill_no'] = isset($cnDatas->bill_no) ? $cnDatas->bill_no : "";
                        $purchesDetails['cn_bill_list'][$cn]['bill_date'] = isset($cnDatas->select_date) ? $cnDatas->select_date : "";
                        $purchesDetails['cn_bill_list'][$cn]['total_amount'] = isset($cnDatas->net_amount) ? $cnDatas->net_amount : "";
                    }
                }
            }
            return $this->sendResponse($purchesDetails, 'Purchase Get Successfully.');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Purches Details api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesDeleteData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'purches_id' => 'required',
            ], [
                'purches_id.required' => "Enter Purches Delete",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $purchesData = PurchesModel::where('id', $request->purches_id)->first();
            if (isset($purchesData)) {
                $purchesData->delete();
            }

            $batchStockData = BatchStock::where('purchase_id', $request->purches_id)->get();
            if (isset($batchStockData)) {
                foreach ($batchStockData as $listData) {
                    $listData->delete();
                }
            }

            $cnBillData = DistributorPrchesReturnTable::where('purches_id', $request->purches_id)->get();
            if (isset($cnBillData)) {
                foreach ($cnBillData as $list) {
                    $cnDatasPurches = PurchesReturn::where('id', $list->purches_return_bill_id)->orderBy('id', 'DESC')->first();
                    if (isset($cnDatasPurches)) {
                        $cnDatasPurches->purches_return_status = '0';
                        $cnDatasPurches->update();
                    }
                    $list->delete();
                }
            }

            $purchesDatas = PurchesDetails::where('purches_id', $request->purches_id)->get();
            if (isset($purchesDatas)) {
                foreach ($purchesDatas as $list) {

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $batchNumberData = BatchModel::where('batch_name', $list->batch)->where('item_id', $list->iteam_id)->whereIn('user_id', $allUserId)->first();

                    if (isset($batchNumberData)) {

                        if (($batchNumberData->qty == 0) && ($batchNumberData->free_qty == 0)) {
                            $batchNumberData->forceDelete();
                        }
                        $batchQty = BatchStock::where('item_id', $list->iteam_id)->whereIn('user_id', $allUserId)->where('batch_number', $list->batch)->sum('qty');
                        $batchFreeQty = BatchStock::where('item_id', $list->iteam_id)->whereIn('user_id', $allUserId)->where('batch_number', $list->batch)->sum('free_qty');
                        $qtyData = $batchQty;
                        $qtyFreeData = $batchFreeQty;
                        $totalQty = $batchQty + $batchFreeQty;
                        $batchNumberData->qty = abs($qtyData);
                        $batchNumberData->free_qty = abs($qtyFreeData);
                        $batchNumberData->purches_qty = abs($qtyData);
                        $batchNumberData->purches_free_qty = abs($qtyFreeData);
                        $batchNumberData->total_qty = (int)abs($totalQty) * (int)$list->unit;
                        $batchNumberData->update();

                        if (($batchNumberData->qty == 0) && ($batchNumberData->free_qty == 0)) {
                            $batchNumberData->forceDelete();
                        }
                    }

                    $finalPurchesAmount = FinalPurchesItem::where('batch', $list->batch)->where('iteam_id', $list->iteam_id)->whereIn('user_id', $allUserId)->withTrashed()->get();

                    if (isset($finalPurchesAmount)) {
                        foreach ($finalPurchesAmount as $finalAmounts) {
                            if (($finalAmounts->qty == 0) && ($finalAmounts->fr_qty == 0)) {
                                $finalAmounts->forceDelete();
                            }

                            $batchFinalQty = BatchStock::where('item_id', $list->iteam_id)->whereIn('user_id', $allUserId)->where('batch_number', $list->batch)->sum('qty');
                            $batchFinalFreeQty = BatchStock::where('item_id', $list->iteam_id)->whereIn('user_id', $allUserId)->where('batch_number', $list->batch)->sum('free_qty');

                            $qtyData = $batchFinalQty;
                            $qtyFreeData = $batchFinalFreeQty;
                            $totalQty = $batchFinalQty - $batchFinalFreeQty;
                            $finalAmounts->qty = abs($qtyData);
                            $finalAmounts->fr_qty = abs($qtyFreeData);
                            $finalAmounts->update();

                            if (($finalAmounts->qty == 0) && ($finalAmounts->fr_qty == 0)) {
                                $batchNumberData = BatchModel::where('batch_name', $list->batch)->where('item_id', $list->iteam_id)->whereIn('user_id', $allUserId)->first();
                                if (isset($batchNumberData)) {
                                    $batchNumberData->forceDelete();
                                }
                                $finalAmounts->forceDelete();
                            }
                        }
                    }

                    $legaderDataDelete  = LedgerModel::where('iteam_id', $list->iteam_id)->where('batch', $list->batch)->where('user_id', auth()->user()->id)->orderBy('id')->get();

                    if (isset($legaderDataDelete)) {
                        $prevStock = null;
                        foreach ($legaderDataDelete as $ListDetails) {
                            $ListDetails->delete();
                        }
                    }

                    $legaderData  = LedgerModel::where('iteam_id', $list->iteam_id)->where('user_id', auth()->user()->id)->orderBy('id')->get();

                    if (isset($legaderData)) {
                        $prevStock = null;
                        foreach ($legaderData as $ListData) {
                            if ($prevStock !== null) {
                                if ($prevStock->in) {
                                    $ListData->balance_stock = $prevStock->balance_stock + $ListData->in;
                                } else {
                                    $ListData->balance_stock = $prevStock->balance_stock + $ListData->out;
                                }
                            } else {
                                $ListData->balance_stock = $ListData->in ?? 0;
                            }
                            $ListData->update();
                            $prevStock = $ListData;
                        }
                    }

                    $iteamModel =  IteamsModel::where('id', $list->iteam_id)->first();
                    if (isset($iteamModel)) {
                        $iteamModel->stock = $iteamModel->stock - $list->qty;
                        $iteamModel->update();
                    }
                    // if(isset($list->getpurches->distributor_id))
                    // {
                    //     $distrutor = Distributer::find($list->getpurches->distributor_id);
                    //     $distrutor->balance = $distrutor->balance - $list->amount;
                    //     $distrutor->update();
                    // }

                    $list->delete();

                    $iteamPurchesData =  iteamPurches::where('random_number', $list->random_number)->get();
                    if (isset($iteamPurchesData)) {
                        foreach ($iteamPurchesData as $iteamPurches) {
                            $iteamPurches->delete();
                        }
                    }
                }
            }

            $batchStockData = FinalIteamId::where('purchase_id', $request->purches_id)->get();
            if (isset($batchStockData)) {
                foreach ($batchStockData as $listData) {
                    $listData->delete();
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Purchase Return Bill Delete';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
            return $this->sendResponse([], 'Purchase Return Delete Successfully.');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Purchase Delete api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesEdit(Request $request)
    {
        try {
          	// dd($request->all(),json_decode($request->purches_data, true));die;
            //  $purchesReturnData = json_decode($request->purches_return_data);

            //  $listAmount = [];

            // if (isset($purchesReturnData)) {
            // foreach ($purchesReturnData as $listData) {
            //   array_push($listAmount, $listData->amount);
            // }
            //

            // $pendingAmount = array_sum($listAmount);
            // if (isset($pendingAmount)) {
            //   $amounts = $pendingAmount - round($request->net_amount, 2);
            // } else 

            $amounts = round($request->net_amount, 2);
            // }

            $purchesNew = PurchesModel::find($request->id);
            if (empty($purchesNew)) {
                return $this->sendError('Data Not Found');
            }

            $purchesNew->distributor_id = $request->distributor_id;
            $purchesNew->bill_no = $request->bill_no;
            $purchesNew->bill_date = $request->bill_date;
            $purchesNew->due_date = $request->due_date;
            $purchesNew->round_off = $request->round_off;
            $purchesNew->draft_save = $request->draft_save;
            $purchesNew->sr_no = $request->sr_no;
            $purchesNew->net_amount = round($request->net_amount, 2);
            $purchesNew->total_amount = round($request->total_amount, 2);
            $purchesNew->pending_amount = round($request->net_amount, 2);
            $purchesNew->margin_net_profit = $request->margin_net_profit;
            $purchesNew->net_amount =  round($request->net_amount, 2);
            $purchesNew->payment_type = $request->payment_type;
            $purchesNew->pending_amount_status = '0';
            $purchesNew->cn_amount = $request->cn_amount;
            $purchesNew->total_gst = $request->total_gst;
            $purchesNew->total_margin = $request->total_margin;
            $purchesNew->owner_type = $request->owner_type;
            $purchesNew->user_id = $request->user_id;
            $purchesNew->sgst = $request->sgst;
            $purchesNew->cgst = $request->cgst;
            $purchesNew->total_base = $request->total_base;
            $distributorData = Distributer::where('id', $request->distributor_id)->first();
            if (isset($distributorData)) {
                $totalGst = isset($request->total_gst) ? $request->total_gst : 0;
                if (isset($distributor->state) && strtolower($distributor->state) === strtolower($distributorData->state)) {
                    $purchesNew->sgst = $totalGst != 0 ? $totalGst / 2 : "";
                    $purchesNew->cgst =  $totalGst != 0 ? $totalGst / 2 : "";
                } else {
                    $purchesNew->igst = $totalGst;
                }
            }
            $purchesNew->update();

            $purchesData = json_decode($request->purches_data, true);
            if ((isset($purchesData)) && ($request->draft_save != '0')) {
                $purchesDetails = PurchesDetails::where('purches_id', $purchesNew->id)->get();
                if (isset($purchesDetails)) {
                    foreach ($purchesDetails as $list) {
                        $list->delete();
                    }
                }

                if (($request->cn_amount) && ($request->cn_amount == '0.0')) {
                    $distributorPayment =  DistributorPrchesReturnTable::where('purches_id', $purchesNew->id)->get();
                    if (isset($distributorPayment)) {
                        foreach ($distributorPayment as $listData) {
                            $purchesBillUpdate = PurchesReturn::where('id', $listData->purches_return_bill_id)->first();
                            if (isset($purchesBillUpdate)) {
                                $purchesBillUpdate->purches_return_status = '0';
                                $purchesBillUpdate->update();
                            }
                            $listData->delete();
                        }
                    }
                }

                $purchesReturnData = json_decode($request->purches_return_data);
                if (isset($purchesReturnData)) {
                    foreach ($purchesReturnData as $listReturn) {

                        $newDistributorData = DistributorPrchesReturnTable::where('purches_return_bill_id', $listReturn->purches_return_bill_id)->first();
                        if (isset($newDistributorData)) {
                            $newDistributorData->user_id = auth()->user()->id;
                            $newDistributorData->distributor_id = $request->distributor_id;
                            $newDistributorData->amount = $listReturn->amount;
                            $newDistributorData->purches_return_bill_id = $listReturn->purches_return_bill_id;
                            $newDistributorData->update();

                            $purchesBillUpdate = PurchesReturn::where('id', $listReturn->purches_return_bill_id)->first();

                            if ($purchesBillUpdate->net_amount == $listReturn->amount) {
                                $purchesBillUpdate->purches_return_status = '1';
                            } else {
                                $purchesBillUpdate->purches_return_status = '0';
                            }
                            $purchesBillUpdate->update();
                        } else {
                            $newDistributorData = new DistributorPrchesReturnTable;
                            $newDistributorData->user_id = auth()->user()->id;
                            $newDistributorData->distributor_id = $request->distributor_id;
                            $newDistributorData->amount = $listReturn->amount;
                            $newDistributorData->purches_return_bill_id = $listReturn->purches_return_bill_id;
                            $newDistributorData->purches_id = $purchesNew->id;
                            $newDistributorData->save();

                            $totalAmounts = DistributorPrchesReturnTable::where('purches_return_bill_id', $listReturn->purches_return_bill_id)->sum('amount');
                            $purchesBillUpdate = PurchesReturn::where('id', $listReturn->purches_return_bill_id)->where('net_amount', $totalAmounts)->first();
                            if (isset($purchesBillUpdate)) {
                                $purchesBillUpdate->purches_return_status = '1';
                                $purchesBillUpdate->update();
                            }
                        }
                    }
                }

                //$purchesDetailsData = FinalPurchesItem::where('purches_id', $purchesNew->id)->get();
                //if (isset($purchesDetailsData)) {
                //  foreach ($purchesDetailsData as $list) {
                //    $list->forceDelete();
                //}
                //}

                // $BtachesList = BatchModel::where('purches_bill_id_', $purchesNew->id)->get();
                //if (isset($BtachesList)) {
                //  foreach ($BtachesList as $list) {
                //    $list->forceDelete();
                //}
                //}

                foreach ($purchesData as $list) {
                    $textbleVlaue = ($list['qty'] ?? 0) * ($list['ptr'] ?? 0) - ($list['disocunt'] ?? 0);
                    $purchesStore = new PurchesDetails;
                    $purchesStore->purches_id = $purchesNew->id;
                    $purchesStore->taxable_value =  $textbleVlaue;
                    $purchesStore->iteam_id = $list['item_id'];
                    $purchesStore->batch = $list['batch_number'];
                    $purchesStore->exp_dt =  $list['expiry'];
                    $purchesStore->mrp = $list['mrp'];
                    $purchesStore->ptr = $list['ptr'];
                    $purchesStore->qty = $list['qty'];
                    $purchesStore->hsn_code = $list['hsn_code'];
                    $purchesStore->fr_qty = $list['fr_qty'];
                    $purchesStore->disocunt = $list['disocunt'];
                    $purchesStore->gst = isset($list['gst_id']) ? $list['gst_id'] : $list['gst'];
                    $purchesStore->user_id = $request->user_id;
                    $purchesStore->net_rate = $list['net_rate'];
                    $purchesStore->location = $list['location'];
                    $purchesStore->unit = $list['unit'];
                    $purchesStore->amount = round($list['amount'], 2);
                    $purchesStore->base = $list['base_price'];
                    $purchesStore->weightage = $list['weightage'];
                    $purchesStore->textable = $list['textable'];
                    $purchesStore->scheme_account = $list['scheme_account'];
                    $purchesStore->margin = $list['margin'];
                    $purchesStore->random_number = $list['random_number'];
                    $purchesStore->iteam_purches_id = $list['id'];
                    $purchesStore->save();

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $batchUpdate = BatchStock::whereIn('user_id', $allUserId)->where('purchase_id', $purchesNew->id)->where('batch_number', $list['batch_number'])->first();
                    if (isset($batchUpdate)) {
                        $batchUpdate->qty = $list['qty'];
                        $batchUpdate->free_qty = $list['fr_qty'];
                        $batchUpdate->update();
                    } else {
                        $batchStockStore = new BatchStock;
                        $batchStockStore->user_id = auth()->user()->id;
                        $batchStockStore->item_id = $list['item_id'];
                        $batchStockStore->purchase_id = $purchesNew->id;
                        $batchStockStore->qty = $list['qty'];
                        $batchStockStore->free_qty = $list['fr_qty'];
                        $batchStockStore->batch_number = $list['batch_number'];
                        $batchStockStore->save();
                    }

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    // $purchesFinalStore = FinalPurchesItem::whereIn('user_id', $allUserId)->where('batch',$list['batch_number'])->where('iteam_id', $list['item_id'])->where('mrp', $list['mrp'])->where('ptr', $list['ptr'])
                    //   ->where('disocunt', $list['disocunt'])->first();
                    $purchesFinalStore = FinalPurchesItem::whereIn('user_id', $allUserId)->where('batch', $list['batch_number'])->where('iteam_id', $list['item_id'])->first();

                    if (isset($purchesFinalStore)) {

                        $batchUpdateQty = BatchStock::whereIn('user_id', $allUserId)->where('item_id', $list['item_id'])->sum('qty');
                        $batchUpdateFreeQty = BatchStock::whereIn('user_id', $allUserId)->where('item_id', $list['item_id'])->sum('free_qty');

                        $qtyData = $batchUpdateQty;
                        $qtyFreeData = $batchUpdateFreeQty;
                        $purchesFinalStore->iteam_id = $list['item_id'];
                        $purchesFinalStore->batch = $list['batch_number'];
                        $purchesFinalStore->exp_dt =  $list['expiry'];
                        $purchesFinalStore->mrp = $list['mrp'];
                        $purchesFinalStore->ptr = $list['ptr'];
                        $purchesFinalStore->qty = (string)abs($qtyData);
                        $purchesFinalStore->fr_qty = (string)abs($qtyFreeData);
                        $purchesFinalStore->disocunt = $list['disocunt'];
                        $purchesFinalStore->gst = isset($list['gst_id']) ? $list['gst_id'] : $list['gst'];
                        $purchesFinalStore->user_id = $request->user_id;
                        $purchesFinalStore->location = $list['location'];
                        $purchesFinalStore->unit = $list['weightage'];
                        $purchesFinalStore->amount = round($list['amount'], 2);
                        $purchesFinalStore->weightage = $list['weightage'];
                        $purchesFinalStore->update();
                    } else {
                        $purchesFinalStore =  new FinalPurchesItem;
                        $purchesFinalStore->purches_id = $purchesNew->id;
                        $purchesFinalStore->iteam_id = $list['item_id'];
                        $purchesFinalStore->batch = $list['batch_number'];
                        $purchesFinalStore->exp_dt =  $list['expiry'];
                        $purchesFinalStore->mrp = $list['mrp'];
                        $purchesFinalStore->ptr = $list['ptr'];
                        $purchesFinalStore->qty = $list['qty'];
                        $purchesFinalStore->fr_qty = $list['fr_qty'];
                        $purchesFinalStore->disocunt = $list['disocunt'];
                        $purchesFinalStore->gst = isset($list['gst_id']) ? $list['gst_id'] : $list['gst'];
                        $purchesFinalStore->user_id = $request->user_id;
                        $purchesFinalStore->location = $list['location'];
                        $purchesFinalStore->unit = $list['weightage'];
                        $purchesFinalStore->amount = round($list['amount'], 2);
                        $purchesFinalStore->weightage = $list['weightage'];
                        $purchesFinalStore->random_number = $list['random_number'];
                        $purchesFinalStore->iteam_purches_id = $list['id'];
                        $purchesFinalStore->save();

                        $finalData = new FinalIteamId;
                        $finalData->final_item_id = $purchesFinalStore->id;
                        $finalData->purchase_id = $purchesNew->id;
                        $finalData->save();
                    }

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                    $orderDelete = OnlineOrder::where('item_id', $list['item_id'])->whereIn('user_id', $allUserId)->get();

                    if (isset($orderDelete)) {
                        foreach ($orderDelete as $listDelete) {
                            $listDelete->delete();
                        }
                    }

                    $LeagerDelete = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->where('transction', 'Purchase Invoice')->where('batch', $list['batch_number'])->first();
                    if (isset($LeagerDelete)) {
                        $LeagerDelete->in = ((int)$list['qty'] + (int)$list['fr_qty']) * $list['weightage'];
                        $LeagerDelete->update();
                    } else {
                        $userName = Distributer::where('id', $request->distributor_id)->first();

                        $leaderData = new LedgerModel;
                        $leaderData->owner_id = $request->distributor_id;
                        $leaderData->entry_date = $request->bill_date;
                        $leaderData->transction = 'Purchase Invoice';
                        $leaderData->voucher = 'Purchase Invoice';
                        $leaderData->bill_no = '#' . $request->bill_no;
                        $leaderData->puches_id = $purchesNew->id;
                        $leaderData->batch = $list['batch_number'];
                        $leaderData->bill_date = $request->bill_date;
                        $leaderData->name = $userName->name;
                        $leaderData->user_id = auth()->user()->id;
                        $leaderData->iteam_id = $list['item_id'];
                        $ledgers = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                        if (isset($ledgers)) {
                            $totalQty = (int)$list['qty'] + (int)$list['fr_qty'];
                            $balance = $totalQty + $ledgers->balance_stock;
                            $leaderData->in = ((int)$list['qty'] + (int)$list['fr_qty']) * $list['weightage'];
                            $leaderData->balance_stock = $balance * $list->weightage;
                        } else {
                            $leaderData->in = ((int)$list['qty'] + (int)$list['fr_qty']) * $list['weightage'];
                            $leaderData->balance_stock = ((int)$list['qty'] + (int)$list['fr_qty']) * $list['weightage'];
                        }
                        $leaderData->save();
                    }

                    $legaderData  = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id')->get();

                    if (isset($legaderData)) {
                        $prevStock = null;
                        foreach ($legaderData as $ListData) {
                            if ($prevStock !== null) {
                                if ($prevStock->in) {
                                    $ListData->balance_stock = $prevStock->balance_stock + $ListData->in;
                                } else {
                                    $ListData->balance_stock = $prevStock->balance_stock + $ListData->out;
                                }
                            } else {
                                $ListData->balance_stock = $ListData->in ?? 0;
                            }
                            $ListData->update();
                            $prevStock = $ListData;
                        }
                    }

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    // $batchData = BatchModel::whereIn('user_id', $allUserId)->where('batch_number',$list['batch_number'])->where('item_id',$list['item_id'])->where('mrp', $list['mrp'])->where('ptr', $list['ptr'])->where('discount', $list['disocunt'])->first();
                    $batchData = BatchModel::whereIn('user_id', $allUserId)->where('batch_number', $list['batch_number'])->where('item_id', $list['item_id'])->first();
                    if (isset($batchData)) {
                        $batchUpdateQty = BatchStock::whereIn('user_id', $allUserId)->where('item_id', $list['item_id'])->sum('qty');
                        $batchUpdateFreeQty = BatchStock::whereIn('user_id', $allUserId)->where('item_id', $list['item_id'])->sum('free_qty');

                        $qtyData = $batchUpdateQty;
                        $qtyFreeData = $batchUpdateFreeQty;
                        $legaderData  = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                        $batchData->item_id = $list['item_id'];
                        $batchData->purches_qty = (string)abs($qtyData);
                        $batchData->purches_free_qty = (string)abs($qtyFreeData);
                        $batchData->qty = (string)abs($qtyData);
                        $batchData->free_qty = (string)abs($qtyFreeData);
                        $batchData->margin = $list['margin'];
                        $batchData->location =  $list['location'];
                        $batchData->stock = '0';
                        $batchData->discount = $list['disocunt'];
                        $batchData->gst = isset($list['gst_id']) ? $list['gst_id'] : $list['gst'];
                        $batchData->base = $list['base_price'];
                        $batchData->expiry_date = $list['expiry'];
                        $batchData->mrp = $list['mrp'];
                        $batchData->ptr = $list['ptr'];
                        $batchData->unit = $list['unit'];
                        $batchData->batch_name = $list['batch_number'];
                        $batchData->batch_number = $list['batch_number'];
                        $batchData->total_qty = isset($legaderData) ? $legaderData->balance_stock : "";
                        $batchData->total_mrp = $list['mrp'] * abs($qtyData);
                        $batchData->total_ptr = $list['ptr'] * abs($qtyData);
                        $batchData->update();
                    } else {
                        $legaderData  = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                        $qtyData = (int)$list['qty'];
                        $qtyFreeData = (int)$list['fr_qty'];
                        $batchData = new BatchModel;
                        $batchData->item_id = $list['item_id'];
                        $batchData->purches_qty = (string)abs($qtyData);
                        $batchData->purches_free_qty = (string)abs($qtyFreeData);
                        $batchData->qty = (string)abs($qtyData);
                        $batchData->free_qty = (string)abs($qtyFreeData);
                        $batchData->margin = $list['margin'];
                        $batchData->location =  $list['location'];
                        $batchData->batch_name = $list['batch_number'];
                        $batchData->batch_number = $list['batch_number'];
                        $batchData->stock = '0';
                        $batchData->discount = $list['disocunt'];
                        $batchData->gst = isset($list['gst_id']) ? $list['gst_id'] : $list['gst'];
                        $batchData->base = $list['base_price'];
                        $batchData->expiry_date = $list['expiry'];
                        $batchData->mrp = $list['mrp'];
                        $batchData->ptr = $list['ptr'];
                        $batchData->unit = $list['unit'];
                        $userId = auth()->user()->id;
                        $batchData->user_id = $userId;
                        $batchData->total_qty = isset($legaderData) ? $legaderData->balance_stock : "";
                        $batchData->total_mrp = $list['mrp'] * abs($qtyData);
                        $batchData->total_ptr = $list['ptr'] * abs($qtyData);
                        $batchData->save();
                    }

                    $iteamModel =  IteamsModel::where('id', $list['item_id'])->first();

                    if (isset($iteamModel)) {

                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = array(auth()->user()->id);
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                        $itemLocation = ItemLocation::where('item_id', $list['item_id'])->whereIn('user_id', $allUserId)->first();

                        if (isset($itemLocation)) {
                            $itemLocation->location = $list['location'];
                            $itemLocation->update();
                        } else {
                            $itemLocation = new ItemLocation;
                            $itemLocation->user_id = auth()->user()->id;
                            $itemLocation->item_id =  $list['item_id'];
                            $itemLocation->location = $list['location'];
                            $itemLocation->save();
                        }
                    }
                }
            } else {
                $purchesDetails = PurchesDetails::where('purches_id', $purchesNew->id)->get();
                if (isset($purchesDetails)) {
                    foreach ($purchesDetails as $list) {
                        $list->delete();
                    }
                }

                if (isset($purchesData)) {
                    foreach ($purchesData as $list) {

                        $textbleVlaue = ($list['qty'] ?? 0) * ($list['ptr'] ?? 0) - ($list['disocunt'] ?? 0);
                        $purchesStore = new PurchesDetails;
                        $purchesStore->purches_id = $purchesNew->id;
                        $purchesStore->taxable_value =  $textbleVlaue;
                        $purchesStore->iteam_id = $list['item_id'];
                        $purchesStore->batch = $list['batch_number'];
                        $purchesStore->exp_dt =  $list['expiry'];
                        $purchesStore->mrp = $list['mrp'];
                        $purchesStore->ptr = $list['ptr'];
                        $purchesStore->qty = $list['qty'];
                        $purchesStore->hsn_code = $list['hsn_code'];
                        $purchesStore->fr_qty = $list['fr_qty'];
                        $purchesStore->disocunt = $list['disocunt'];
                        $purchesStore->gst = isset($list['gst_id']) ? $list['gst_id'] : $list['gst'];
                        $purchesStore->user_id = $request->user_id;
                        $purchesStore->net_rate = $list['net_rate'];
                        $purchesStore->location = $list['location'];
                        $purchesStore->unit = $list['weightage'];
                        $purchesStore->amount = round($list['amount'], 2);
                        $purchesStore->base = $list['base_price'];
                        $purchesStore->weightage = $list['weightage'];
                        $purchesStore->textable = $list['textable'];
                        $purchesStore->scheme_account = $list['scheme_account'];
                        $purchesStore->margin = $list['margin'];
                        $purchesStore->random_number = $list['random_number'];
                        $purchesStore->iteam_purches_id = $list['id'];
                        $purchesStore->save();
                    }
                }
            }
            $userLogs = new LogsModel;
            $userLogs->message = 'Purchase Bill Updated';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $dataPurcahes = [];
            $dataPurcahes['id'] = isset($purchesStore->id) ? $purchesStore->id : '';
            return $this->sendResponse($dataPurcahes, 'Purchase Bill Updated Successfully.');
        } catch (\Exception $e) {
            Log::info("Create Iteams api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesList(Request $request)
    {
        try {
            $userid = auth()->user();

            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);
          
          	$purchaseDataCount = PurchesModel::where('user_id', $userId)->count();

            $purchesDetails = PurchesModel::whereIn('user_id', $allUserId);
            if (isset($request->start_date) && isset($request->from_date)) {
                $from_date = $request->start_date;
                $to_date = $request->from_date; // Corrected variable name
                $purchesDetails->whereBetween('bill_date', [$from_date, $to_date]);
            }
            if (isset($request->sr_no)) {
                $purchesDetails->where('sr_no', 'like', '%' . $request->sr_no . '%');
            }
            if (isset($request->bill_no)) {
                $purchesDetails->where('bill_no', 'like', '%' . $request->bill_no . '%');
            }
          	if (isset($request->bill_date)) {
                $purchesDetails->where('bill_date', 'like', '%' . $request->bill_date . '%');
            }
          	if (isset($request->total_amount)) {
                $purchesDetails->where('net_amount', 'like', '%' . $request->total_amount . '%');
            }
            if (isset($request->distributor_name)) {
                $userName = $request->distributor_name;
                $distributorName =  Distributer::where('name', 'like', '%' . $userName . '%')->pluck('id')->toArray();
                $purchesDetails->whereIn('distributor_id', $distributorName);
            }
            // Handle other search/filter criteria
            if ($request->filled('search')) {
                // Handle alphabetical search
                if ($request->search == 'Bill No. - A to Z') {
                    $purchesDetails->orderBy('bill_no', 'asc');
                } elseif ($request->search == 'Bill No. - Z to A') {
                    $purchesDetails->orderBy('bill_no', 'desc');
                } elseif ($request->search == 'Bill Date. - New to Old') {
                    $purchesDetails->orderBy('bill_date', 'asc');
                } elseif ($request->search == 'Bill Date. - Old to New') {
                    $purchesDetails->orderBy('bill_date', 'desc');
                } elseif ($request->search == 'Amount - 1 to 9') {
                    $purchesDetails->orderBy('total_amount', 'asc');
                } elseif ($request->search == 'Amount - 9 to 1') {
                    $purchesDetails->orderBy('total_amount', 'desc');
                }
            }
            $purchesDetailsCount = $purchesDetails->count();
            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $purchesDetails->offset($offset)->limit($limit);
            $purchesDetails = $purchesDetails->orderBy('id','DESC')->get();

            Artisan::call('testpurcheaspdf:cron');
            $detailsData = [];
            if ($purchesDetails->count() > 0) {
                foreach ($purchesDetails as $key => $list) {
                    //$pdfUrl = $this->purchesPdfDownloadsNew($list->id);
                    $distributorData = Distributer::where('id', $list->distributor_id)->first();
                    $userIdData = User::where('id', $list->user_id)->first();
                    $TotalAmount = PurchesDetails::where('purches_id', $list->id)->sum('amount');
                    $detailsData[$key]['id'] = $list->id;
                    $detailsData[$key]['sr_no'] = isset($list->sr_no) ? $list->sr_no : "1";
                    $detailsData[$key]['bill_no'] = $list->bill_no;
                    $detailsData[$key]['bill_date'] = $list->bill_date;
                    $detailsData[$key]['draft_save'] = $list->draft_save ?? '';
                    $detailsData[$key]['due_date'] = $list->due_date;
                    $detailsData[$key]['count'] = $purchesDetailsCount;
                    $detailsData[$key]['total_amount'] = (string)round($list->net_amount, 2);
                    $detailsData[$key]['user_name'] = isset($userIdData->name) ? $userIdData->name : "";
                    $detailsData[$key]['payment_type'] = isset($userIdData->payment_type) ? $userIdData->payment_type : "";
                    $detailsData[$key]['distributor_name'] = isset($distributorData->name) ? $distributorData->name : "";
                    $detailsData[$key]['bill_create_date_time'] = isset($list->created_at) ? date("Y-m-d h:i", strtotime($list->created_at)) : "";
                }
            }
            // $detailsDatas = array_reverse($detailsData);
          	$response = [
                'status'=> 200,
              	'current_page' => $request->page ?? "1",
              	'count' => !empty($request->page) ? $purchesDetails->count() : $purchaseDataCount,
              	'total_records' => $purchaseDataCount,
                'data'    => $detailsData,
                'message' => 'Purchase Bill Data Get Successfully.',
            ];
          	
            return response()->json($response, 200);
          
            // return $this->sendResponse($detailsData, 'Purchase Bill Data Get Successfully.');
        } catch (\Exception $e) {
            Log::error("Purchase List API Error: " . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    // this function use purches update
    public function purcheReturnStore(Request $request)
    {
        try {
            $puchesReturn = new PurchesReturn;
            $puchesReturn->distributor_id = $request->distributor_id;
            $puchesReturn->bill_no = $request->bill_no;
            $puchesReturn->select_date = $request->bill_date;
            $puchesReturn->margin = $request->margin;
            $puchesReturn->net_rate = $request->net_rate;
            $puchesReturn->draft_save = $request->draft_save;
            $puchesReturn->remark = $request->remark;
            $puchesReturn->ptr_discount = $request->discount;
            $puchesReturn->start_end_date = $request->start_date;
            $puchesReturn->end_end_date = $request->end_date;
            $puchesReturn->margin = $request->margin;
            $userId = auth()->user();
            $puchesReturn->user_id = $userId->id;
            $puchesReturn->round_off = $request->round_off;
            $puchesReturn->adjustment_amoount = $request->other_amount;
            $puchesReturn->final_amount = $request->final_amount;
            $puchesReturn->net_amount = $request->net_amount;
            $puchesReturn->payment_type = $request->payment_type;
            // $puchesReturn->margin_net_profit = $request->margin_net_profit;
            $puchesReturn->sgst = $request->sgst;
            $puchesReturn->cgst = $request->cgst;
            $puchesReturn->total_base = $request->total_base;
            $distributorData = Distributer::where('id', $request->distributor_id)->first();
            if (isset($distributorData)) {
                $totalGst = isset($request->total_gst) ? $request->total_gst : 0;
                if (isset($distributor->state) && strtolower($distributor->state) === strtolower($distributorData->state)) {
                    $puchesReturn->sgst = $totalGst != 0 ? $totalGst / 2 : "";
                    $puchesReturn->cgst =  $totalGst != 0 ? $totalGst / 2 : "";
                } else {
                    $puchesReturn->igst = $totalGst;
                }
            }
            $puchesReturn->save();
            if ($request->payment_mode == 'cash') {
                $cashManage = CashManagement::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();

                if (isset($cashManage)) {
                    $amount = $request->total - $cashManage->opining_balance;
                    $cashAdd = new CashManagement;
                    $cashAdd->date = $request->bill_date;
                    $cashAdd->description = 'Purchase';
                    $cashAdd->type = 'credit';
                    $cashAdd->amount = round($request->total, 2);
                    $cashAdd->reference_no = $puchesReturn->id;
                    $cashAdd->voucher     = 'purchase';
                    $cashAdd->user_id = auth()->user()->id;
                    $cashAdd->opining_balance = $amount;
                    $cashAdd->save();
                } else {

                    $cashAdd = new CashManagement;
                    $cashAdd->date = $request->bill_date;
                    $cashAdd->description = 'Purchase';
                    $cashAdd->type = 'credit';
                    $cashAdd->amount = round($request->total, 2);
                    $cashAdd->user_id = auth()->user()->id;
                    $cashAdd->reference_no = $puchesReturn->id;
                    $cashAdd->voucher     = 'purchase';
                    $cashAdd->opining_balance = $request->total;
                    $cashAdd->save();
                }
            } else {
                $passBook =  PassBook::where('bank_id', $request->payment_mode)->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                if (isset($passBook)) {
                    $amount = $request->total - $passBook->balance;
                    $distributorData = Distributer::where('id', $request->distributor_id)->first();
                    $passbook = new  PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $request->payment_date;
                    $passbook->party_name = $distributorData->name;
                    $passbook->bank_id = $request->payment_mode;
                    $passbook->deposit = "";
                    $passbook->withdraw     = round($request->total, 2);
                    $passbook->balance = round($amount, 2);
                    $passbook->mode = "";
                    $passbook->remark = $request->note;
                    $passbook->save();
                } else {
                    $distributorData = Distributer::where('id', $request->distributor_id)->first();

                    $passbook = new  PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $request->payment_date;
                    $passbook->party_name = $distributorData->name;
                    $passbook->bank_id = $request->payment_mode;
                    $passbook->deposit = "";
                    $passbook->withdraw    = round($request->total, 2);
                    $passbook->balance = round($request->total, 2);
                    $passbook->mode = "";
                    $passbook->remark = $request->note;
                    $passbook->save();
                }
            }

            $purchesTrueValue = json_decode($request->purches_return, true);

            $purchesData = array_filter($purchesTrueValue, function ($item) {
                return $item['iss_check'] === true;
            });

            $purchesDataFalesData = array_filter($purchesTrueValue, function ($item) {
                return $item['iss_check'] === false;
            });

            if ((isset($purchesDataFalesData)) && (!empty($purchesDataFalesData))) {

                foreach ($purchesDataFalesData as $listData) {
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $purchesDataIds = FinalIteamId::where('final_item_id', $listData['id'])->pluck('purchase_id')->toArray();

                    $purchesEdit = PurchesDetails::whereIn('user_id', $allUserId)->whereIn('purches_id', $purchesDataIds)->where('batch',  $listData['batch_number'])->orderBy('id', 'DESC')->first();

                    if (isset($purchesEdit)) {
                        $finalPurchesData = FinalPurchesItem::where('id', $listData['id'])->first();

                        if (isset($finalPurchesData)) {
                            $finalPurchesData->iteam_id = $purchesEdit->iteam_id;
                            $finalPurchesData->batch =  $purchesEdit->batch;
                            $finalPurchesData->exp_dt =  $purchesEdit->exp_dt;
                            $finalPurchesData->mrp = $purchesEdit->mrp;
                            $finalPurchesData->ptr = $purchesEdit->ptr;
                            $finalPurchesData->iss_check = '0';
                            $finalPurchesData->fr_qty =  $purchesEdit->fr_qty;
                            $finalPurchesData->qty = $purchesEdit->qty;
                            $finalPurchesData->disocunt = $purchesEdit->disocunt;
                            $finalPurchesData->gst =  $purchesEdit->gst;
                            $finalPurchesData->location =  $purchesEdit->location;
                            $finalPurchesData->unit = $purchesEdit->weightage;
                            $finalPurchesData->amount =  round($purchesEdit->net_rate, 2);
                            $finalPurchesData->weightage =  $purchesEdit->weightage;
                            $finalPurchesData->update();
                        }
                    }
                }
            }
          
            if ((isset($purchesData)) && (!empty($purchesData)) && ($puchesReturn->draft_save != '0')) {
                foreach ($purchesData as $list) {
                    $textbleVlaue = ($list['qty'] ?? 0) * ($list['ptr'] ?? 0) - ($list['disocunt'] ?? 0);
                    $purchesStore = new PurchesReturnDetails;
                    $purchesStore->purches_id = $puchesReturn->id;
                    $purchesStore->taxable_value = $textbleVlaue;
                    $purchesStore->iteam_id = $list['item_id'];
                    $purchesStore->batch = $list['batch_number'];
                    $purchesStore->exp_dt =  $list['expiry'];
                    $purchesStore->mrp = $list['mrp'];
                    $purchesStore->ptr = $list['ptr'];
                    $purchesStore->qty = $list['qty'];
                    $purchesStore->fr_qty = $list['fr_qty'];
                    $purchesStore->disocunt = $list['disocunt'];
                    $purchesStore->gst = $list['gst'];
                    $userId = auth()->user();
                    $purchesStore->user_id = $userId->id;
                    $purchesStore->location = $list['location'];
                    $purchesStore->unit = $list['unit'];
                    $purchesStore->amount = round($list['amount'], 2);
                    $purchesStore->weightage = $list['weightage'];
                    $purchesStore->save();

                    $purchesStoreNew = new parcheReturnItemEdit;
                    $purchesStoreNew->purches_id = $puchesReturn->id;
                    $purchesStoreNew->iteam_id = $list['item_id'];
                    $purchesStoreNew->batch = $list['batch_number'];
                    $purchesStoreNew->exp_dt =  $list['expiry'];
                    $purchesStoreNew->mrp = $list['mrp'];
                    $purchesStoreNew->ptr = $list['ptr'];
                    $purchesStoreNew->qty = $list['qty'];
                    $purchesStoreNew->fr_qty = $list['fr_qty'];
                    $purchesStoreNew->disocunt = $list['disocunt'];
                    $purchesStoreNew->gst = $list['gst'];
                    $userId = auth()->user();
                    $purchesStoreNew->user_id = $userId->id;
                    $purchesStoreNew->location = $list['location'];
                    $purchesStoreNew->unit = $list['unit'];
                    $purchesStoreNew->amount = round($list['amount'], 2);
                    $purchesStoreNew->weightage = $list['weightage'];
                    $purchesStoreNew->save();

                    $userName = Distributer::where('id', $request->distributor_id)->first();

                    $leaderData = new LedgerModel;
                    $leaderData->owner_id = $request->distributor_id;
                    $leaderData->entry_date = $request->bill_date;
                    $leaderData->transction = 'Purchase Return Invoice';
                    $leaderData->voucher = 'Purchase Return Invoice';
                    $leaderData->bill_no = '#' . $request->bill_no;
                    $leaderData->puches_id = $puchesReturn->id;
                    $leaderData->batch = $list['batch_number'];
                    $leaderData->bill_date = $request->bill_date;
                    $leaderData->name = $userName->name;
                    $leaderData->user_id = auth()->user()->id;
                    $leaderData->iteam_id =  $list['item_id'];
                    $ledgers = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                    if (isset($ledgers)) {
                        $totalQty = ((int)$list['qty'] + (int)$list['fr_qty']) * $list['unit'];
                        $balance = $ledgers->balance_stock - $totalQty;
                        Log::info("Purchase stock api" . $ledgers->balance_stock);
                        Log::info("Purchase qty api" . $totalQty);
                        Log::info("Purchase balance api" . $balance);
                        $leaderData->out = ((int)$list['qty'] + (int)$list['fr_qty']) * $list['unit'];
                        $leaderData->balance_stock = $balance;
                    } else {
                        $leaderData->out = ((int)$list['qty'] + (int)$list['fr_qty']) * $list['unit'];
                        $leaderData->balance_stock = ((int)$list['qty'] + (int)$list['fr_qty']) * $list['unit'];
                    }

                    $ledgers = LedgerModel::where('owner_id', $request->distributor_id)->orderBy('id', 'DESC')->first();
                    if (isset($ledgers)) {

                        $total = $ledgers->balance - $request->net_amount;
                        $leaderData->debit = round($request->net_amount, 2);
                        $leaderData->balance = abs($total);
                    } else {
                        $leaderData->debit = round($request->net_amount, 2);
                        $leaderData->balance = abs($request->net_amount);
                    }
                    $leaderData->save();

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $batchData = BatchModel::where('batch_number', $list['batch_number'])->whereIn('user_id', $allUserId)->where('item_id', $list['item_id'])->first();
                    if (isset($batchData)) {
                        $finalPurchesData = FinalPurchesItem::where('id', $list['id'])->first();
                        if (isset($finalPurchesData)) {

                            $purchaseQty = (int)$batchData->purches_qty;
                            $freeQty = (int)$batchData->purches_free_qty;
                            Log::info("Purchase qty api" . $purchaseQty);
                            Log::info("Purchase free api" . $freeQty);

                            $returnPurchaseQty = (int)$list['qty'];
                            $returnFreeQty = (int)$list['fr_qty'];

                            $freeQty -= $returnFreeQty;
                            $purchaseQty -= ($returnPurchaseQty - $freeQty);
                            $freeQty = 0;

                            $finalPurchesData->qty = abs($purchaseQty); // Keep purchased quantity as it is
                            $finalPurchesData->fr_qty = abs($freeQty); // Update free quantity
                            $finalPurchesData->status = ($purchaseQty > 0  && $freeQty > 0) ? '1' : '0'; // Update status
                            $finalPurchesData->iss_check = '0'; // Update status
                            $finalPurchesData->update();

                            Log::info("Purchase qty api" . $purchaseQty . '=' . $list['qty'] . ' = ' . $purchaseQty);
                            Log::info("Purchase free api" . $freeQty . '=' . $list['fr_qty'] . '=' . $freeQty);

                            $batchData->purches_qty = abs($purchaseQty);
                            $batchData->purches_free_qty = abs($freeQty);

                            $finalQty = $purchaseQty + $freeQty;

                            $batchData->item_id = $list['item_id'];
                            $batchData->qty = $list['qty'];
                            $batchData->free_qty = $list['fr_qty'];
                            $batchData->location =  $list['location'];
                            $batchData->unit = $list['unit'];
                            $batchData->stock = '0';
                            $batchData->discount = $list['disocunt'];
                            $batchData->gst = isset($list['gst']) ? $list['gst'] : $list['gst'];
                            $batchData->batch_name = $list['batch_number'];
                            $batchData->expiry_date = $list['expiry'];
                            $batchData->mrp = $list['mrp'];
                            $batchData->ptr = $list['ptr'];
                            $batchData->total_mrp = $list['mrp'] * $list['qty'];
                            $batchData->total_ptr = $list['ptr'] * $list['qty'];
                            $batchData->total_qty = abs($finalQty) * $list['unit'];
                            $batchData->update();
                        }
                    }
                }
            } else {
                if (isset($purchesData)) {
                    foreach ($purchesData as $list) {

                        $textbleVlaue = ($list['qty'] ?? 0) * ($list['ptr'] ?? 0) - ($list['disocunt'] ?? 0);
                        $purchesStore = new PurchesReturnDetails;
                        $purchesStore->purches_id = $puchesReturn->id;
                        $purchesStore->taxable_value = $textbleVlaue;
                        $purchesStore->iteam_id = $list['item_id'];
                        $purchesStore->batch = $list['batch_number'];
                        $purchesStore->exp_dt =  $list['expiry'];
                        $purchesStore->mrp = $list['mrp'];
                        $purchesStore->ptr = $list['ptr'];
                        $purchesStore->qty = $list['qty'];
                        $purchesStore->fr_qty = $list['fr_qty'];
                        $purchesStore->disocunt = $list['disocunt'];
                        $purchesStore->gst = $list['gst'];
                        $userId = auth()->user();
                        $purchesStore->user_id = $userId->id;
                        $purchesStore->location = $list['location'];
                        $purchesStore->unit = $list['unit'];
                        $purchesStore->amount = round($list['amount'], 2);
                        $purchesStore->weightage = $list['weightage'];
                        $purchesStore->save();

                        $purchesStoreNew = new parcheReturnItemEdit;
                        $purchesStoreNew->purches_id = $puchesReturn->id;
                        $purchesStoreNew->iteam_id = $list['item_id'];
                        $purchesStoreNew->batch = $list['batch_number'];
                        $purchesStoreNew->exp_dt =  $list['expiry'];
                        $purchesStoreNew->mrp = $list['mrp'];
                        $purchesStoreNew->ptr = $list['ptr'];
                        $purchesStoreNew->qty = $list['qty'];
                        $purchesStoreNew->fr_qty = $list['fr_qty'];
                        $purchesStoreNew->disocunt = $list['disocunt'];
                        $purchesStoreNew->gst = $list['gst'];
                        $userId = auth()->user();
                        $purchesStoreNew->user_id = $userId->id;
                        $purchesStoreNew->location = $list['location'];
                        $purchesStoreNew->unit = $list['unit'];
                        $purchesStoreNew->amount = round($list['amount'], 2);
                        $purchesStoreNew->weightage = $list['weightage'];
                        $purchesStoreNew->save();
                    }
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Purchase Return Bill Created';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $dataPurcahes = [];
            $dataPurcahes['id'] = isset($purchesStore->id) ? $purchesStore->id : '';
            return $this->sendResponse($dataPurcahes, 'Purchase Return Created Successfully.');
        } catch (\Exception $e) {
            Log::info("Purchase Delete api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use purchase return edit
    public function purcheReturEdit(Request $request)
    {
        try {
            $puchesReturn = PurchesReturn::find($request->id);
            $puchesReturn->margin = $request->margin;
            $puchesReturn->net_rate = $request->net_rate;
            $puchesReturn->distributor_id = $request->distributor_id;
            $puchesReturn->bill_no = $request->bill_no;
            $puchesReturn->select_date = $request->bill_date;
            $puchesReturn->remark = $request->remark;
            $puchesReturn->ptr_discount = $request->discount;
            $puchesReturn->start_end_date = $request->start_date;
            $puchesReturn->end_end_date = $request->end_date;
            $puchesReturn->round_off = $request->round_off;
            $puchesReturn->margin = $request->margin;
            $puchesReturn->draft_save = $request->draft_save;
            $userId = auth()->user();
            $puchesReturn->user_id = $userId->id;
            $puchesReturn->adjustment_amoount = round($request->other_amount, 2);
            $puchesReturn->final_amount = round($request->final_amount, 2);
            $puchesReturn->net_amount = round($request->net_amount, 2);
            $puchesReturn->payment_type = $request->payment_type;
            $puchesReturn->sgst = $request->sgst;
            $puchesReturn->cgst = $request->cgst;
            $puchesReturn->total_base = $request->total_base;
            $distributorData = Distributer::where('id', $request->distributor_id)->first();
            if (isset($distributorData)) {
                $totalGst = isset($request->total_gst) ? $request->total_gst : 0;
                if (isset($distributor->state) && strtolower($distributor->state) === strtolower($distributorData->state)) {
                    $puchesReturn->sgst = $totalGst != 0 ? $totalGst / 2 : "";
                    $puchesReturn->cgst =  $totalGst != 0 ? $totalGst / 2 : "";
                } else {
                    $puchesReturn->igst = $totalGst;
                }
            }
            $puchesReturn->update();

            $purchesTrueValue = json_decode($request->purches_return, true);
            $purchesData = array_filter($purchesTrueValue, function ($item) {
                return $item['iss_check'] === true;
            });

            $purchesDataFalesData = array_filter($purchesTrueValue, function ($item) {
                return $item['iss_check'] === false;
            });

            if (isset($purchesDataFalesData)) {
                foreach ($purchesDataFalesData as $listData) {

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $purchesDataIds = FinalIteamId::where('final_item_id', $listData['id'])->pluck('purchase_id')->toArray();
                    $purchesEdit = PurchesDetails::whereIn('user_id', $allUserId)->whereIn('purches_id', $purchesDataIds)->where('batch',  $listData['batch_number'])->orderBy('id', 'DESC')->first();
                    if (isset($purchesEdit)) {
                        $finalPurchesData = FinalPurchesItem::where('id', $listData->id)->first();
                        if (isset($finalPurchesData)) {
                            $finalPurchesData->iteam_id = $purchesEdit->iteam_id;
                            $finalPurchesData->batch =  $purchesEdit->batch;
                            $finalPurchesData->exp_dt =  $purchesEdit->exp_dt;
                            $finalPurchesData->mrp = $purchesEdit->mrp;
                            $finalPurchesData->ptr = $purchesEdit->ptr;
                            $finalPurchesData->iss_check = '0';
                            $finalPurchesData->fr_qty =  $purchesEdit->fr_qty;
                            $finalPurchesData->qty = $purchesEdit->qty;
                            $finalPurchesData->disocunt = $purchesEdit->disocunt;
                            $finalPurchesData->gst =  $purchesEdit->gst;
                            $finalPurchesData->location =  $purchesEdit->location;
                            $finalPurchesData->unit = $purchesEdit->weightage;
                            $finalPurchesData->amount =  round($purchesEdit->net_rate, 2);
                            $finalPurchesData->weightage =  $purchesEdit->weightage;
                            $finalPurchesData->update();
                        }
                    }
                }
            }

            if (isset($purchesData) && ($puchesReturn->draft_save != '0')) {
                $purchesDatas = PurchesReturnDetails::where('purches_id', $puchesReturn->id)->get();

                if (isset($purchesDatas)) {
                    foreach ($purchesDatas as $list) {
                        $list->delete();
                    }
                }

                $purchesDatas = parcheReturnItemEdit::where('purches_id', $puchesReturn->id)->get();
                if (isset($purchesDatas)) {
                    foreach ($purchesDatas as $list) {
                        $list->delete();
                    }
                }

                foreach ($purchesData as $list) {
                    $textbleVlaue = ($list['qty'] ?? 0) * ($list['ptr'] ?? 0) - ($list['disocunt'] ?? 0);
                    $purchesStore = new PurchesReturnDetails;
                    $purchesStore->purches_id = $puchesReturn->id;
                    $purchesStore->taxable_value = $textbleVlaue;
                    $purchesStore->iteam_id = $list['item_id'];
                    $purchesStore->batch = $list['batch_number'];
                    $purchesStore->exp_dt =  $list['expiry'];
                    $purchesStore->mrp = $list['mrp'];
                    $purchesStore->ptr = $list['ptr'];
                    $purchesStore->qty = $list['qty'];
                    $purchesStore->fr_qty = $list['fr_qty'];
                    $purchesStore->disocunt = $list['disocunt'];
                    $purchesStore->gst = $list['gst'];
                    $userId = auth()->user();
                    $purchesStore->user_id = $userId->id;
                    $purchesStore->location = $list['location'];
                    $purchesStore->unit = $list['unit'];
                    $purchesStore->amount = round($list['amount'], 2);
                    $purchesStore->weightage = $list['weightage'];
                    $purchesStore->save();

                    $purchesStoreNew = new parcheReturnItemEdit;
                    $purchesStoreNew->purches_id = $puchesReturn->id;
                    $purchesStoreNew->iteam_id = $list['item_id'];
                    $purchesStoreNew->batch = $list['batch_number'];
                    $purchesStoreNew->exp_dt =  $list['expiry'];
                    $purchesStoreNew->mrp = $list['mrp'];

                    $purchesStoreNew->ptr = $list['ptr'];
                    $purchesStoreNew->qty = $list['qty'];
                    $purchesStoreNew->fr_qty = $list['fr_qty'];
                    $purchesStoreNew->disocunt = $list['disocunt'];
                    $purchesStoreNew->gst = $list['gst'];
                    $userId = auth()->user();
                    $purchesStoreNew->user_id = $userId->id;
                    $purchesStoreNew->location = $list['location'];
                    $purchesStoreNew->unit = $list['unit'];
                    $purchesStoreNew->amount = round($list['amount'], 2);
                    $purchesStoreNew->weightage = $list['weightage'];
                    $purchesStoreNew->save();

                    $LeagerDelete = LedgerModel::where('transction', 'Purchase Return Invoice')->where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->where('batch', $list['batch_number'])->orderBy('id', 'DESC')->first();
                    if (isset($LeagerDelete)) {
                        $LeagerDelete->out =  ((int)$list['qty'] + (int)$list['fr_qty']) * $list['unit'];
                        $LeagerDelete->update();
                    } else {

                        $userName = Distributer::where('id', $request->distributor_id)->first();

                        $leaderData = new LedgerModel;
                        $leaderData->owner_id = $request->distributor_id;
                        $leaderData->entry_date = $request->bill_date;
                        $leaderData->transction = 'Purchase Return Invoice';
                        $leaderData->voucher = 'Purchase Return Invoice';
                        $leaderData->bill_no = '#' . $request->bill_no;
                        $leaderData->puches_id = $puchesReturn->id;
                        $leaderData->batch = $list['batch_number'];
                        $leaderData->bill_date = $request->bill_date;
                        $leaderData->name = $userName->name;
                        $leaderData->user_id = auth()->user()->id;
                        $leaderData->iteam_id =  $list['item_id'];

                        $ledgers = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();

                        if (isset($ledgers)) {
                            $totalQty = ((int)$list['qty'] + (int)$list['fr_qty']) * $list['unit'];
                            $balance =  $ledgers->balance_stock - $totalQty;
                            $leaderData->out = ((int)$list['qty'] + (int)$list['fr_qty']) * $list['unit'];
                            $leaderData->balance_stock = abs($balance);
                        } else {
                            $leaderData->out = ((int)$list['qty'] + (int)$list['fr_qty']) * $list['unit'];
                            $leaderData->balance_stock = ((int)$list['qty'] + (int)$list['fr_qty']) * $list['unit'];
                        }
                        $leaderData->save();
                    }

                    $legaderData  = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id')->get();

                    if (isset($legaderData)) {
                        $prevStock = null;
                        foreach ($legaderData as $ListData) {

                            if ($prevStock !== null) {
                                Log::info("Purchase return leger stock" . $ListData->in);
                                if (isset($ListData->in)) {
                                    $amount = $prevStock->balance_stock + $ListData->in;
                                    Log::info("Purchase return leger stock" . $amount);
                                    $ListData->balance_stock = round($amount, 2);
                                } else {
                                    $amount = (int)$prevStock->balance_stock - (int)$ListData->out;
                                    $ListData->balance_stock = round($amount, 2);
                                }
                            } else {
                                $ListData->balance_stock = $ListData->balance_stock ?? 0;
                            }

                            $ListData->update();
                            $prevStock = $ListData;
                        }
                    }
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $batchData = BatchModel::where('batch_number', $list['batch_number'])->whereIn('user_id', $allUserId)->where('item_id', $list['item_id'])
                        ->where('mrp', $list['mrp'])->where('ptr', $list['ptr'])
                        ->where('discount', $list['disocunt'])->first();

                    if (isset($batchData)) {
                        $finalPurchesData = FinalPurchesItem::where('batch', $list['batch_number'])->whereIn('user_id', $allUserId)
                            ->where('iteam_id', $list['item_id'])->first();

                        if (isset($finalPurchesData)) {

                            $purchaseQty = PurchesDetails::where('batch', $list['batch_number'])->whereIn('user_id', $allUserId)->where('iteam_id', $list['item_id'])
                                ->where('mrp', $list['mrp'])->where('ptr', $list['ptr'])
                                ->where('disocunt', $list['disocunt'])->sum('qty');

                            $freeQty = PurchesDetails::where('batch', $list['batch_number'])->whereIn('user_id', $allUserId)->where('iteam_id', $list['item_id'])
                                ->where('mrp', $list['mrp'])->where('ptr', $list['ptr'])
                                ->where('disocunt', $list['disocunt'])->sum('fr_qty');

                            $returnPurchaseQty = PurchesReturnDetails::where('batch', $list['batch_number'])->whereIn('user_id', $allUserId)->where('iteam_id', $list['item_id'])
                                ->where('mrp', $list['mrp'])->where('ptr', $list['ptr'])
                                ->where('disocunt', $list['disocunt'])->sum('qty');

                            $returnFreeQty = PurchesReturnDetails::where('batch', $list['batch_number'])->whereIn('user_id', $allUserId)->where('iteam_id', $list['item_id'])
                                ->where('mrp', $list['mrp'])->where('ptr', $list['ptr'])
                                ->where('disocunt', $list['disocunt'])->sum('fr_qty');

                            $freeQty -= $returnFreeQty;
                            $purchaseQty -= ($returnPurchaseQty - $freeQty);
                            $freeQty = 0;

                            //   $purchaseQty = abs($newBatchStockQty);
                            //   $freeQty = abs($newBatchStockFree);
                            Log::info("Purchase qty api" . $purchaseQty);
                            Log::info("Purchase free api" . $freeQty);

                            //   $returnPurchaseQty = $list['qty'];
                            //   $returnFreeQty = $list['fr_qty'];

                            //   $freeQty -= $returnFreeQty;
                            //   $purchaseQty -= ($returnPurchaseQty - $freeQty);
                            //   $freeQty = 0;

                            $finalPurchesData->qty = abs($purchaseQty); // Keep purchased quantity as it is
                            $finalPurchesData->fr_qty = abs($freeQty); // Update free quantity
                            $finalPurchesData->status = ($purchaseQty > 0  && $freeQty > 0) ? '1' : '0'; // Update status
                            $finalPurchesData->iss_check = '1'; // Update status
                            $finalPurchesData->update();

                            Log::info("Purchase qty api" . $purchaseQty . '=' . $list['qty'] . ' = ' . $purchaseQty);
                            Log::info("Purchase free api" . $freeQty . '=' . $list['fr_qty'] . '=' . $freeQty);

                            $batchData->purches_qty = abs($purchaseQty);
                            $batchData->purches_free_qty = abs($freeQty);

                            $finalQty = $purchaseQty + $freeQty;

                            $batchData->item_id = $list['item_id'];
                            $batchData->qty = $list['qty'];
                            $batchData->free_qty = $list['fr_qty'];
                            $batchData->location =  $list['location'];
                            $batchData->unit = $list['unit'];
                            $batchData->stock = '0';
                            $batchData->discount = $list['disocunt'];
                            $batchData->gst = isset($list['gst']) ? $list['gst'] : $list['gst'];
                            $batchData->batch_name = $list['batch_number'];
                            $batchData->expiry_date = $list['expiry'];
                            $batchData->mrp = $list['mrp'];
                            $batchData->ptr = $list['ptr'];
                            $batchData->total_mrp = $list['mrp'] * $list['qty'];
                            $batchData->total_ptr = $list['ptr'] * $list['qty'];

                            $batchData->total_qty = abs($finalQty) * $list['unit'];
                            $batchData->update();
                        }
                    }

                    $iteamModel = IteamsModel::where('id', $list['item_id'])->first();
                    if (isset($iteamModel)) {
                        $qtyData =  (int)$list['fr_qty'] + (int)$list['qty'];
                        $iteamModel->stock = $iteamModel->stock - $qtyData;
                        $iteamModel->update();
                    }
                }
            } else {
                $purchesDatas = PurchesReturnDetails::where('purches_id', $puchesReturn->id)->get();

                if (isset($purchesDatas)) {
                    foreach ($purchesDatas as $list) {
                        $list->delete();
                    }
                }

                $purchesDatas = parcheReturnItemEdit::where('purches_id', $puchesReturn->id)->get();
                if (isset($purchesDatas)) {
                    foreach ($purchesDatas as $list) {
                        $list->delete();
                    }
                }
                if (isset($purchesData)) {
                    foreach ($purchesData as $list) {

                        $textbleVlaue = ($list['qty'] ?? 0) * ($list['ptr'] ?? 0) - ($list['disocunt'] ?? 0);
                        $purchesStore = new PurchesReturnDetails;
                        $purchesStore->purches_id = $puchesReturn->id;
                        $purchesStore->taxable_value = $textbleVlaue;
                        $purchesStore->iteam_id = $list['item_id'];
                        $purchesStore->batch = $list['batch_number'];
                        $purchesStore->exp_dt =  $list['expiry'];
                        $purchesStore->mrp = $list['mrp'];
                        $purchesStore->ptr = $list['ptr'];
                        $purchesStore->qty = $list['qty'];
                        $purchesStore->fr_qty = $list['fr_qty'];
                        $purchesStore->disocunt = $list['disocunt'];
                        $purchesStore->gst = $list['gst'];
                        $userId = auth()->user();
                        $purchesStore->user_id = $userId->id;
                        $purchesStore->location = $list['location'];
                        $purchesStore->unit = $list['unit'];
                        $purchesStore->amount = round($list['amount'], 2);
                        $purchesStore->weightage = $list['weightage'];
                        $purchesStore->save();

                        $purchesStoreNew = new parcheReturnItemEdit;
                        $purchesStoreNew->purches_id = $puchesReturn->id;
                        $purchesStoreNew->iteam_id = $list['item_id'];
                        $purchesStoreNew->batch = $list['batch_number'];
                        $purchesStoreNew->exp_dt =  $list['expiry'];
                        $purchesStoreNew->mrp = $list['mrp'];
                        $purchesStoreNew->ptr = $list['ptr'];
                        $purchesStoreNew->qty = $list['qty'];
                        $purchesStoreNew->fr_qty = $list['fr_qty'];
                        $purchesStoreNew->disocunt = $list['disocunt'];
                        $purchesStoreNew->gst = $list['gst'];
                        $userId = auth()->user();
                        $purchesStoreNew->user_id = $userId->id;
                        $purchesStoreNew->location = $list['location'];
                        $purchesStoreNew->unit = $list['unit'];
                        $purchesStoreNew->amount = round($list['amount'], 2);
                        $purchesStoreNew->weightage = $list['weightage'];
                        $purchesStoreNew->save();
                    }
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Purchase Return Bill Edit';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $dataPurcahes = [];
            $dataPurcahes['id'] = isset($puchesReturn->id) ? $puchesReturn->id : '';
            return $this->sendResponse($dataPurcahes, 'Purchase Return Edit Successfully.');
        } catch (\Exception $e) {
            Log::info("Purchase Return api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use purchase return
    public function purcheReturnList(Request $request)
    {
        try {
            $data = auth()->user();
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $purchesDetails = PurchesReturn::whereIn('user_id', $allUserId);
            if (isset($request->start_date) && isset($request->from_date)) {
                $from_date = $request->start_date;
                $to_date = $request->from_date; // Corrected variable name
                $purchesDetails->whereBetween('select_date', [$from_date, $to_date]);
            }
            if (isset($request->bill_no)) {
                $purchesDetails->where('bill_no', 'like', '%' . $request->bill_no . '%');
            }
          	if (isset($request->bill_amount)) {
                $purchesDetails->where('net_amount', 'like', '%' . $request->bill_amount . '%');
            }
            if (isset($request->distributor_name)) {
                $userName = $request->distributor_name;
                $purchesDetails->whereHas('getUser', function ($query) use ($userName) {
                    $query->where('name', 'like', '%' . $userName . '%');
                });
            }
            // Handle other search/filter criteria
            if ($request->filled('search')) {
                // Handle alphabetical search
                if ($request->search == 'Bill No. - A to Z') {
                    $purchesDetails->orderBy('bill_no', 'asc');
                } elseif ($request->search == 'Bill No. - Z to A') {
                    $purchesDetails->orderBy('bill_no', 'desc');
                } elseif ($request->search == 'Bill Date. - New to Old') {
                    $purchesDetails->orderBy('select_date', 'asc');
                } elseif ($request->search == 'Bill Date. - Old to New') {
                    $purchesDetails->orderBy('select_date', 'desc');
                } elseif ($request->search == 'Amount - 1 to 9') {
                    $purchesDetails->orderBy('final_amount', 'asc');
                } elseif ($request->search == 'Amount - 9 to 1') {
                    $purchesDetails->orderBy('final_amount', 'desc');
                }
            }
            $purchesDetailsCount = $purchesDetails->count();
            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $purchesDetails->offset($offset)->limit($limit);
            $purchesDetails = $purchesDetails->orderBy('id', 'DESC')->get();

            $detailsData = [];
            if ($purchesDetails->count() > 0) { // Checking if there are any results
                foreach ($purchesDetails as $key => $list) {
                    $distributorData = Distributer::where('id', $list->distributor_id)->first();
                    $userIdData = User::where('id', $list->user_id)->first();
                    $TotalAmount = $list->net_amount;
                    $cnAmount = DistributorPrchesReturnTable::where('purches_return_bill_id', $list->id)->sum('amount');
                    $amount = (string)round((float)$TotalAmount, 2);
                    $cnamounts = (string)$cnAmount;
                    $status = '';
                    if ($amount == $cnamounts) {
                        $status = 'Paid';
                    } else {
                        $status = 'Due';
                    }

                    $dueAmounts = $amount - $cnAmount;

                    $detailsData[$key]['id'] = $list->id;
                    $detailsData[$key]['bill_no'] = $list->bill_no;
                    $detailsData[$key]['bill_date'] = $list->select_date;
                    $detailsData[$key]['draft_save'] = $list->draft_save;
                    $detailsData[$key]['round_off'] = $list->round_off;
                    $detailsData[$key]['due_amount'] = isset($dueAmounts) ? (string)round($dueAmounts, 2) : "0";
                    $detailsData[$key]['cn_amount'] = isset($cnAmount) ? (string)$cnAmount : "-";
                    $detailsData[$key]['count'] = $purchesDetailsCount;
                    $detailsData[$key]['status'] = $status;
                    $detailsData[$key]['total_amount'] = (string)round((float)$TotalAmount, 2);
                    $detailsData[$key]['user_name'] = isset($userIdData->name) ? $userIdData->name : "";
                    $detailsData[$key]['distributor_name'] = isset($distributorData->name) ? $distributorData->name : "";
                    $detailsData[$key]['bill_create_date_time'] = isset($list->created_at) ? date("Y-m-d h:i", strtotime($list->created_at)) : "";
                    $totalAmountsCn = DistributorPrchesReturnTable::where('purches_return_bill_id', $list->id)->get();
                    $detailsData[$key]['cn_amount_bills'] = [];
                    if (isset($totalAmountsCn)) {
                        foreach ($totalAmountsCn as $keys => $listData) {
                            $purchesAmouts = PurchesModel::where('id', $listData->purches_id)->first();
                            $detailsData[$key]['cn_amount_bills'][$keys]['amount'] = isset($listData->amount) ? $listData->amount : "";
                            $detailsData[$key]['cn_amount_bills'][$keys]['bill_number'] = isset($purchesAmouts->bill_no) ? $purchesAmouts->bill_no : "";
                        }
                    }
                }
            }
          
          	$response = [
              'status' => 200,
              'count' => !empty($request->page) ? $purchesDetails->count() : $purchesDetailsCount,
              'total_records' => $purchesDetailsCount,
              'data'   => $detailsData,
              'message' => 'Purchase Return List Successfully.',
            ];
            return response()->json($response, 200);
            // return $this->sendResponse($detailsData, 'Purchase Return List Successfully.');
        } catch (\Exception $e) {
            Log::info("Purchase Return api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this ufnction use purchase details
    public function purcheReturnDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'purches_return_id' => 'required'
            ], [
                'purches_return_id.required' => "Enter Purches Retur Id"
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $purchesData = PurchesReturn::where('id', $request->purches_return_id)->orderBy('id', 'DESC')->first();
          	$cnAmount = DistributorPrchesReturnTable::where('purches_return_bill_id', $request->purches_return_id)->sum('amount');
          
          	$dueAmounts = $purchesData->net_amount - $cnAmount;

            $purchesReturn = [];
            $iteamQty = [];
            $iteamGst = [];
            $totalBase = [];
            $iteamFreeQty = [];
            if (isset($purchesData)) {
                $TotalAmount = PurchesReturnDetails::where('purches_id', $purchesData->id)->sum('amount');
                $distributorData = Distributer::where('id', $purchesData->distributor_id)->first();
                $userIdData = User::where('id', $purchesData->user_id)->first();
                $purchesReturn['id'] = isset($purchesData->id) ? $purchesData->id : "";
                $purchesReturn['round_off'] = isset($purchesData->round_off) ? $purchesData->round_off : "";
                $purchesReturn['total_margin'] = isset($purchesData->margin) ? $purchesData->margin : "";
                $purchesReturn['draft_save'] = isset($purchesData->draft_save) ? $purchesData->draft_save : "";
                $purchesReturn['total_net_rate'] = isset($purchesData->net_rate) ? (string)$purchesData->net_rate : "";
                //$purchesReturn['margin_net_profit'] = isset($purchesData->margin_net_profit) ? (string)$purchesData->margin_net_profit : "";
                $purchesReturn['distributor_id'] = isset($purchesData->distributor_id) ? $purchesData->distributor_id : "";
                $purchesReturn['distributor_name'] = isset($distributorData->name) ? $distributorData->name : "";
                $purchesReturn['user_name'] = isset($userIdData->name) ? $userIdData->name : "";
                $purchesReturn['bill_no'] = isset($purchesData->bill_no) ? $purchesData->bill_no : "";
                $purchesReturn['bill_date'] = isset($purchesData->select_date) ? $purchesData->select_date : "";
                $purchesReturn['start_date'] = isset($purchesData->start_end_date) ? $purchesData->start_end_date : "";
                $purchesReturn['end_date'] = isset($purchesData->end_end_date) ? $purchesData->end_end_date : "";
                $purchesReturn['remark'] = isset($purchesData->remark) ? $purchesData->remark : "";
                $purchesReturn['other_amount'] = isset($purchesData->adjustment_amoount) ? (string) round($purchesData->adjustment_amoount, 2) : "";
                $purchesReturn['net_amount'] = isset($purchesData->net_amount) ? (string) round($purchesData->net_amount, 2) : "";
                $purchesReturn['final_amount'] = isset($TotalAmount) ? (string)round($TotalAmount, 2) : "";
                $purchesReturn['total_amount'] = isset($purchesData->net_amount) ? (string)round($purchesData->net_amount, 2) : "";
                $purchesReturn['due_amount'] = isset($dueAmounts) ? (string)round($dueAmounts, 2) : "";
                $purchesReturn['cgst'] = isset($purchesData->cgst) ? $purchesData->cgst : "";
                $purchesReturn['sgst'] = isset($purchesData->sgst) ? $purchesData->sgst : "";

                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $purchesReturn['item_list'] = [];
                $purchesItemDetails = PurchesReturnDetails::where('purches_id', $purchesData->id)->whereIn('user_id', $allUserId)->orderBy('id', 'DESC')->get();
                if (isset($purchesItemDetails)) {
                    foreach ($purchesItemDetails as $key => $details) {

                        $iteamModel =  IteamsModel::where('id', $details->iteam_id)->first();
                        $uniteName = UniteTable::where('id', $details->unit)->first();
                        $gstName = GstModel::where('id', $details->gst)->first();
                        $purchesReturn['item_list'][$key]['id'] = isset($details->id) ? $details->id : "";
                        $purchesReturn['item_list'][$key]['item_id'] = isset($details->iteam_id) ? $details->iteam_id : "";
                        $purchesReturn['item_list'][$key]['front_photo'] = isset($iteamModel->front_photo) ? asset('/public/front_photo/' . $iteamModel->front_photo) : "";
                        $purchesReturn['item_list'][$key]['item_name'] = isset($iteamModel->iteam_name) ? $iteamModel->iteam_name : "";
                        $purchesReturn['item_list'][$key]['batch_number'] = isset($details->batch) ? $details->batch : "";
                        $purchesReturn['item_list'][$key]['gst_name'] = isset($gstName->name) ? $gstName->name : "";
                        $purchesReturn['item_list'][$key]['expiry'] = isset($details->exp_dt) ? $details->exp_dt : "";
                        $purchesReturn['item_list'][$key]['mrp'] = isset($details->mrp) ? $details->mrp : "";
                        $purchesReturn['item_list'][$key]['fr_qty'] = isset($details->fr_qty) ? $details->fr_qty : "";
                        $purchesReturn['item_list'][$key]['qty'] = isset($details->qty) ? $details->qty : "";
                        $purchesReturn['item_list'][$key]['disocunt'] = isset($details->disocunt) ? $details->disocunt : "";
                        $purchesReturn['item_list'][$key]['gst'] =  isset($details->gst) ? $details->gst : "";
                        $purchesReturn['item_list'][$key]['ptr'] =  isset($details->ptr) ? $details->ptr : "";
                        $purchesReturn['item_list'][$key]['location'] = isset($details->location) ? $details->location : "";
                        $purchesReturn['item_list'][$key]['unit'] = isset($details->unit) ? $details->unit : "";
                        $purchesReturn['item_list'][$key]['amount'] = isset($details->amount) ? (string)round($details->amount, 2) : "";
                        $purchesReturn['item_list'][$key]['weightage'] = isset($details->weightage) ? $details->weightage : "";
                        $totalQty =  (int)$details->qty;
                        $totalFreeQty =  (int)$details->fr_qty;
                        $resultGst = isset($gstName->name) ? $gstName->name : 0;
                        $baseAmount = $details->ptr;
                        $getData = ($baseAmount *  $resultGst) / 100;

                        array_push($iteamQty, $totalQty);
                        array_push($iteamFreeQty, $totalFreeQty);
                        array_push($iteamGst, $getData);
                        array_push($totalBase, $baseAmount);
                    }
                }
                if (isset($purchesItemDetails) && $purchesItemDetails->isNotEmpty()) {
                    $totalItems = $purchesItemDetails->count();

                    $totalBase = (int)array_sum($totalBase);
                    $gstData = $totalItems > 0 ? array_sum($iteamGst) / $totalItems : 0;
                    $totalGst = $totalBase * $gstData / 100;

                    $purchesReturn['total_qty'] = (string)array_sum($iteamQty);
                    $purchesReturn['total_base'] = $totalBase;
                    $purchesReturn['total_free'] = (string)array_sum($iteamQty);
                    $purchesReturn['total_gst'] = (string)round(array_push($iteamFreeQty), 2);
                } else {
                    $purchesReturn['total_qty'] = "";
                    $purchesReturn['total_gst'] = "";
                    $purchesReturn['total_free'] = "";
                    $purchesReturn['total_base'] = "";
                }
            }

            return $this->sendResponse($purchesReturn, 'Purchase Return Details Successfully.');
        } catch (\Exception $e) {
            Log::info("Purchase Return api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purcheReturnEditData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'purches_return_id' => 'required'
            ], [
                'purches_return_id.required' => "Enter Purches Retur Id"
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $purchesData = PurchesReturn::where('id', $request->purches_return_id)->orderBy('id', 'DESC')->first();

            $purchesItemDetailsTotal = parcheReturnItemEdit::where('purches_id', $purchesData->id)->whereIn('user_id', $allUserId);
            if (isset($request->search)) {
                $iteamName = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->where('iteam_name', 'like', '%' . $request->search . '%')->pluck('id')->toArray();
                $purchesItemDetailsTotal->whereIn('iteam_id', $iteamName);
            }
            $purchesItemDetailsTotals = $purchesItemDetailsTotal->sum('amount');

            if (isset($purchesData)) {
                $purchesData->final_amount = $purchesItemDetailsTotals;
                $purchesData->update();
            }

            $purchesReturn = [];
            $arrayData = [];
            $iteamQty = [];
            $iteamGst = [];
            $totalBase = [];
            $arrayDataNetRate = [];
            $iteamFreeQty = [];
            $arrayDataMargin = [];
            if (isset($purchesData)) {
                $TotalAmount = parcheReturnItemEdit::where('purches_id', $purchesData->id)->sum('amount');
                $distributorData = Distributer::where('id', $purchesData->distributor_id)->first();
                $userIdData = User::where('id', $purchesData->user_id)->first();
                $purchesReturn['id'] = isset($purchesData->id) ? $purchesData->id : "";
                $purchesReturn['round_off'] = isset($purchesData->round_off) ? $purchesData->round_off : "";

                $purchesReturn['distributor_id'] = isset($purchesData->distributor_id) ? $purchesData->distributor_id : "";
                $purchesReturn['distributor_name'] = isset($distributorData->name) ? $distributorData->name : "";
                $purchesReturn['user_name'] = isset($userIdData->name) ? $userIdData->name : "";
                $purchesReturn['bill_no'] = isset($purchesData->bill_no) ? $purchesData->bill_no : "";
                $purchesReturn['bill_date'] = isset($purchesData->select_date) ? $purchesData->select_date : "";
                $purchesReturn['start_date'] = isset($purchesData->start_end_date) ? $purchesData->start_end_date : "";
                $purchesReturn['end_date'] = isset($purchesData->end_end_date) ? $purchesData->end_end_date : "";
                $purchesReturn['remark'] = isset($purchesData->remark) ? $purchesData->remark : "";
                $purchesReturn['other_amount'] = isset($purchesData->adjustment_amoount) ? (string) round($purchesData->adjustment_amoount, 2) : "";
                $purchesReturn['net_amount'] = isset($purchesData->net_amount) ? (string) round($purchesData->net_amount, 2) : "";
                // $purchesReturn['final_amount'] = isset($purchesItemDetailsTotals) ? (string)round($purchesItemDetailsTotals, 2) : "";
                $purchesReturn['cgst'] = isset($purchesData->cgst) ? $purchesData->cgst : "";
                $purchesReturn['sgst'] = isset($purchesData->sgst) ? $purchesData->sgst : "";
                $purchesReturn['item_list'] = [];

                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $purchesItemDetails = parcheReturnItemEdit::where('purches_id', $purchesData->id)->whereIn('user_id', $allUserId);
                if (isset($request->search)) {
                    $iteamName = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->where('iteam_name', 'like', '%' . $request->search . '%')->pluck('id')->toArray();
                    $purchesItemDetails->whereIn('iteam_id', $iteamName);
                }
                $purchesItemDetails = $purchesItemDetails->orderBy('id', 'DESC')->get();

                if (isset($purchesItemDetails)) {
                    foreach ($purchesItemDetails as $key => $details) {
                        $iteamModel =  IteamsModel::where('id', $details->iteam_id)->first();
                        $uniteName = UniteTable::where('id', $details->unit)->first();
                        $gstName = GstModel::where('id', $details->gst)->first();

                        if ($details->iss_check == '1') {
                            $dataTable = false;
                        } else {
                            $dataTable = true;
                        }

                        $batchStockQty = BatchModel::where('batch_name', $details->batch)->where('item_id', $details->iteam_id)->whereIn('user_id', $allUserId)->sum('purches_qty');
                        $batchStockFreeQty = BatchModel::where('batch_name', $details->batch)->where('item_id', $details->iteam_id)->whereIn('user_id', $allUserId)->sum('purches_free_qty');
                        $totalStock = (int)$batchStockQty + (int)$batchStockFreeQty;

                        $purchaseMargin = PurchesDetails::where('batch', $details->batch)->whereIn('user_id', $allUserId)->where('iteam_id', $details->iteam_id)->sum('margin');
                        $purchaseNetRate = PurchesDetails::where('batch', $details->batch)->whereIn('user_id', $allUserId)->where('iteam_id', $details->iteam_id)->sum('net_rate');

                        $totalStockQty = isset($details->qty) ? $details->qty : 0;
                        $totalStockQtyFree = isset($details->fr_qty) ? $details->fr_qty : 0;
                        $totalAllStock = (int)$totalStockQty + (int)$totalStockQtyFree + (int)$totalStock;
                      
                      	$exampleSubtotal = (string)$totalAllStock * $details->ptr;
              			$exampleAfterCd = $exampleSubtotal * (1 - ($details->disocunt / 100));
              			$finalTotal = $exampleAfterCd * (1 + ($gstName->name / 100));
                      
                        $purchesReturn['item_list'][$key]['id'] = isset($details->id) ? $details->id : "";
                        $purchesReturn['item_list'][$key]['total_stock'] = isset($totalAllStock) ? (string)abs($totalAllStock) : '';
                        $purchesReturn['item_list'][$key]['item_id'] = isset($details->iteam_id) ? $details->iteam_id : "";
                        $purchesReturn['item_list'][$key]['random_number'] = isset($purchesData->random_number) ? $purchesData->random_number : "";
                        $purchesReturn['item_list'][$key]['front_photo'] = isset($iteamModel->front_photo) ? asset('/public/front_photo/' . $iteamModel->front_photo) : "";
                        $purchesReturn['item_list'][$key]['item_name'] = isset($iteamModel->iteam_name) ? $iteamModel->iteam_name : "";
                        $purchesReturn['item_list'][$key]['batch_number'] = isset($details->batch) ? $details->batch : "";
                        $purchesReturn['item_list'][$key]['gst_name'] = isset($gstName->name) ? $gstName->name : "";
                        $purchesReturn['item_list'][$key]['expiry'] = isset($details->exp_dt) ? $details->exp_dt : "";
                        $purchesReturn['item_list'][$key]['mrp'] = isset($details->mrp) ? $details->mrp : "";
                        $purchesReturn['item_list'][$key]['purches_id'] = "";
                        $purchesReturn['item_list'][$key]['fr_qty'] = isset($details->fr_qty) ? $details->fr_qty : "";
                        $purchesReturn['item_list'][$key]['qty'] = isset($details->qty) ? $details->qty : "";
                        $purchesReturn['item_list'][$key]['disocunt'] = isset($details->disocunt) ? $details->disocunt : "";
                        $purchesReturn['item_list'][$key]['gst'] =  $details->gst;
                        $purchesReturn['item_list'][$key]['iss_check'] = $dataTable;
                        $purchesReturn['item_list'][$key]['ptr'] =  isset($details->ptr) ? $details->ptr : "";
                        $purchesReturn['item_list'][$key]['location'] = isset($details->location) ? $details->location : "";
                        $purchesReturn['item_list'][$key]['unit'] = isset($details->unit) ? $details->unit : "";
                        // $purchesReturn['item_list'][$key]['amount'] = isset($details->amount) ? (string)round($details->amount, 2) : "";
                      	$purchesReturn['item_list'][$key]['amount'] = isset($finalTotal) ? (string)round($finalTotal, 2) : "0";
                        $purchesReturn['item_list'][$key]['weightage'] = isset($details->weightage) ? $details->weightage : "";
                        if ($details->iss_check == '0') {
                            $totalQty =  (int)$details->qty;
                            $totalFreeQty =  isset($details->fr_qty) ? (int)$details->fr_qty : 0;
                            $resultGst = isset($gstName->name) ? $gstName->name : 0;
                            $baseAmount = $details->ptr;

                            $getData = ($baseAmount *  $resultGst) / 100;

                            // array_push($arrayData, $details->amount);
                          	array_push($arrayData, $finalTotal);
                            array_push($arrayDataMargin, $purchaseMargin);
                            array_push($arrayDataNetRate, $purchaseNetRate);

                            array_push($iteamFreeQty, $totalFreeQty);
                            array_push($iteamQty, $totalQty);
                            array_push($iteamGst, $getData);
                            array_push($totalBase, $baseAmount);
                        }
                    }
                }

                if (isset($purchesItemDetails) && $purchesItemDetails->isNotEmpty()) {
                    $totalItems = $purchesItemDetails->count();

                    $totalBase = (int)array_sum($totalBase);
                    $gstData = $totalItems > 0 ? array_sum($iteamGst) / $totalItems : 0;
                    $totalGst = $totalBase * $gstData / 100;

                    $purchesReturn['total_qty'] = (string)array_sum($iteamQty);
                    $purchesReturn['total_base'] =  $totalBase;
                    $purchesReturn['total_free_qty'] = (string)array_sum($iteamFreeQty);

                    $purchesReturn['total_gst'] = (string)round(array_push($iteamGst), 2);
                } else {
                    $purchesReturn['total_qty'] = "";
                    $purchesReturn['total_gst'] = "";
                    $purchesReturn['total_base'] =  "";
                    $purchesReturn['total_free_qty'] = "";
                }
            }

            $purchesReturn['total_amount'] = (string)round(array_sum($arrayData), 2);

            $purchesReturn['final_amount'] = "0";
            $purchesReturn['total_margin'] = (string)array_sum($arrayDataMargin);
            $purchesReturn['total_net_rate'] = (string)round(array_sum($arrayDataNetRate), 2);

            return $this->sendResponse($purchesReturn, 'Purchase Return Details Successfully.');
        } catch (\Exception $e) {
            Log::info("Purchase Return api Edit" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purcheReturnFilter(Request $request)
    {
        try {
            $user = auth()->user();
            $distributorId = $request->distributor_id;

            $purchesDataId = PurchesModel::where('user_id', $user->id)->where('distributor_id', $distributorId)->pluck('id')->toArray();

            $staffGetData = User::where('create_by', $user->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', $user->id)->pluck('create_by')->toArray();
            $allUserId = array_merge($staffGetData, $ownerGet, [$user->id]);

            $puchesDatas = [];
            if (!empty($purchesDataId)) {
                $finalAmoiunt = FinalIteamId::whereIn('purchase_id', $purchesDataId)->pluck('final_item_id')->toArray();

                $purchesDataQuery = FinalPurchesItem::whereIn('id', $finalAmoiunt)
                    ->where(function ($query) {
                        $query->orWhere('qty', '>', '0')
                            ->orWhere('fr_qty', '>', '0');
                    })
                    ->where('status', '0');

                if ($request->filled('search')) {
                    $iteamName = IteamsModel::whereNull('user_id')
                        ->orWhere('user_id', $user->id)
                        ->where('iteam_name', 'like', '%' . $request->search . '%')
                        ->pluck('id')
                        ->toArray();
                    $purchesDataQuery->whereIn('iteam_id', $iteamName);
                }

                if ($request->has(['start_date', 'end_date'])) {
                    $startDate = Carbon::createFromFormat('m/y', $request->start_date)->format('Ym');
                    $endDate = Carbon::createFromFormat('m/y', $request->end_date)->endOfMonth()->format('Ym');

                    $purchesDataQuery->whereRaw("CONCAT('20', SUBSTRING(exp_dt, 4, 2), SUBSTRING(exp_dt, 1, 2)) >= ?", [$startDate])->whereRaw("CONCAT('20', SUBSTRING(exp_dt, 4, 2), SUBSTRING(exp_dt, 1, 2)) <= ?", [$endDate]);
                }

                $puchesDatas = $purchesDataQuery->whereIn('user_id', $allUserId)->get();
            }

            $purchesDetails = [];
            $arrayAmount = [];
            $iteamQty = [];
            $iteamGst = [];
            $totalBase = [];
            $maeginTotal = [];
            $purcheasDataRate = [];
            $totalCount = [];
            $iteamFreeQty = [];

            foreach ($puchesDatas as $key => $details) {
                $purchesItem = FinalPurchesItem::find($details->id);

                $isscheck = isset($purchesItem->iss_check) && $purchesItem->iss_check == '0' ? false : true;

                $purchaseMargin = PurchesDetails::where('batch', $details->batch)
                    ->whereIn('user_id', $allUserId)
                    ->where('iteam_id', $details->iteam_id)
                    ->sum('margin');

                $purchaseNetRate = PurchesDetails::where('batch', $details->batch)
                    ->whereIn('user_id', $allUserId)
                    ->where('iteam_id', $details->iteam_id)
                    ->sum('net_rate');

                $iteamModel = IteamsModel::find($details->iteam_id);
                $uniteName = UniteTable::find($details->unit);
                $gstName = GstModel::find($details->gst);

                $batchStockQty = BatchModel::where('batch_name', $details->batch)
                    ->where('item_id', $details->iteam_id)
                    ->whereIn('user_id', $allUserId)
                    ->sum('purches_qty');

                $batchStockFreeQty = BatchModel::where('batch_name', $details->batch)
                    ->where('item_id', $details->iteam_id)
                    ->whereIn('user_id', $allUserId)
                    ->sum('purches_free_qty');

                $totalStock = (int)$batchStockQty + (int)$batchStockFreeQty;
              
              	$totalAmountGet = ($totalStock * $details->ptr) * (1 + ($gstName->name / 100));
              
              	$exampleSubtotal = (string)$totalStock * $details->ptr;
              	$exampleAfterCd = $exampleSubtotal * (1 - ($details->disocunt / 100));
              	$finalTotal = $exampleAfterCd * (1 + ($gstName->name / 100));
              	// print_r(compact('exampleSubtotal','exampleAfterCd','finalTotal'));

                $purchesDetails[$key] = [
                    'id' => $details->id ?? "",
                    'total_stock' => (string)abs($totalStock),
                    'item_id' => $details->iteam_id ?? "",
                    'purches_id' => $purchesItem->purches_id ?? "",
                    'front_photo' => isset($iteamModel->front_photo) ? asset('/public/front_photo/' . $iteamModel->front_photo) : "",
                    'item_name' => $iteamModel->iteam_name ?? "",
                    'batch_number' => $details->batch ?? "",
                    'gst_name' => $gstName->name ?? "",
                    'expiry' => $details->exp_dt ?? "",
                    'mrp' => $details->mrp ?? "",
                    'ptr' => $details->ptr ?? "",
                    'iss_check' => $isscheck,
                    'random_number' => $details->random_number ?? "",
                    'iteam_purches_id' => $details->iteam_purches_id ?? "",
                    'fr_qty' => (string)($details->fr_qty ?? 0),
                    'qty' => (string)($details->qty ?? 0),
                    'disocunt' => (string)($details->disocunt ?? 0),
                    'gst' => $details->gst,
                    'location' => (string)($details->location ?? 0),
                    'unit' => $details->unit ?? "",
                    // 'amount' => isset($details->amount) ? (string)round($details->amount, 2) : "0",
                  	// 'amount' => isset($totalAmountGet) ? (string)round($totalAmountGet) : "0",
                  	'amount' => isset($finalTotal) ? (string)round($finalTotal, 2) : "0",
                    'weightage' => (string)($details->weightage ?? 0),
                ];

                if ($purchesItem->iss_check == '1') {
                    $totalQty = (int)($details->qty ?? 0);
                    $totalFreeQty = (int)($details->fr_qty ?? 0);
                    $gstPercent = (float)($gstName->name ?? 0);
                    $baseAmount = (float)$details->ptr;
                    $gstAmount = ($baseAmount * $gstPercent) / 100;

                    // $arrayAmount[] = (float)$purchesItem->amount;
                  	// $arrayAmount[] = (float)$totalAmountGet;
                  	$arrayAmount[] = (float)$finalTotal;
                    $maeginTotal[] = (float)$purchaseMargin;
                    $iteamQty[] = $totalQty;
                    $iteamFreeQty[] = $totalFreeQty;
                    $iteamGst[] = $gstAmount;
                    $totalBase[] = $baseAmount;
                    $purcheasDataRate[] = (float)$purchaseNetRate;
                    $totalCount[] = 1;
                }
            }

            $count = array_sum($totalCount);

            if ($count > 0) {
                $totalItems = $count;
                $totalBaseSum = array_sum($totalBase);
                $gstData = $totalItems > 0 ? array_sum($iteamGst) / $totalItems : 0;
                $marginData = $totalItems > 0 ? array_sum($maeginTotal) / $totalItems : 0;
                $totalGst = $totalBaseSum * $gstData / 100;

                $dataDetails['total_margin'] = (string)round($marginData, 2);
                $dataDetails['total_base_margin'] = (string)$totalBaseSum;
                $dataDetails['total_free_qty'] = (string)array_sum($iteamFreeQty);
                $dataDetails['total_qty'] = (string)array_sum($iteamQty);
                $dataDetails['total_gst'] = (string)round(array_sum($iteamGst), 2);
                $dataDetails['total_net_rate'] = (string)array_sum($purcheasDataRate);
            } else {
                $dataDetails = [
                    'total_qty' => "0",
                    'total_base_margin' => "0",
                    'total_free_qty' => "0",
                    'total_margin' => "0",
                    'total_gst' => "0",
                    'total_net_rate' => "0",
                ];
            }

            $dataDetails['item_list'] = $purchesDetails;

            $totalAmount = array_sum($arrayAmount);
            $amount = $totalAmount > 0 ? $totalAmount : 0;
            $totalMarginAmount = array_sum($purcheasDataRate) - $amount;

            $dataDetails['final_amount'] = (string)round($amount, 2);
            $dataDetails['margin'] = (string)round(array_sum($maeginTotal), 2);
            // $dataDetails['margin_net_profit'] = (string)round($totalMarginAmount, 2);
          	
            return $this->sendResponse($dataDetails, 'Purchase Return List Successfully.');
        } catch (\Exception $e) {
            Log::error("Purchase Return API error: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function purchesReturnEditIteam(Request $request)
    {
        try {
            $puchesDatas = FinalPurchesItem::where('id', $request->purches_return_id)->first();

            if (isset($puchesDatas)) {
                $puchesDatas->iteam_id = $request->iteam_id;
                $puchesDatas->batch =  $request->batch;
                $puchesDatas->exp_dt =  $request->exp_dt;
                $puchesDatas->mrp = $request->mrp;
                $puchesDatas->ptr = $request->ptr;
                $puchesDatas->fr_qty =  $request->fr_qty;
                $puchesDatas->qty = $request->qty;
                $puchesDatas->disocunt = $request->disocunt;
                $puchesDatas->gst =  $request->gst;
                $puchesDatas->location =  $request->location;
                $puchesDatas->unit = $request->weightage;
                $puchesDatas->amount =  round($request->amount, 2);
                $puchesDatas->weightage =  $request->weightage;
                $puchesDatas->update();

                $userLogs = new LogsModel;
                $userLogs->message = 'Purchase Return Bill Edit Item';
                $userLogs->user_id = auth()->user()->id;
                $userLogs->date_time = date('Y-m-d H:i a');
                $userLogs->save();
            }
            return $this->sendResponse([], 'Purchase Return Edit Successfully.');
        } catch (\Exception $e) {
            Log::info("Purchase Return Edit Iteam" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesReturnIteamDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'purches_return_id' => 'required',
                'type' => 'required'
            ], [
                'purches_return_id.required' => "Enter Purches Retur Id",
                'type.required' => 'Please Enter Type'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            if ($request->type == '0') {
                $finalUpdate = FinalPurchesItem::where('id', $request->purches_return_id)->first();

                if (isset($finalUpdate)) {
                    $legaderData  = LedgerModel::where('iteam_id', $finalUpdate->iteam_id)->where('batch', $finalUpdate->batch)->where('user_id', auth()->user()->id)->orderBy('id')->first();
                    if (isset($legaderData)) {
                        $legaderData->delete();
                    }

                    $legaderData  = LedgerModel::where('iteam_id', $finalUpdate->iteam_id)->where('user_id', auth()->user()->id)->orderBy('id')->get();

                    if (isset($legaderData)) {
                        $prevStock = null;
                        foreach ($legaderData as $ListData) {
                            if ($prevStock !== null) {
                                if ($prevStock->in) {
                                    $amount = $prevStock->balance_stock + $ListData->in;
                                    $ListData->balance_stock = round($amount, 2);
                                } else {
                                    $amount = $prevStock->balance_stock + $ListData->out;
                                    $ListData->balance_stock = round($amount, 2);
                                }
                            } else {
                                $ListData->balance_stock = $ListData->out ?? 0;
                            }
                            $ListData->update();
                            $prevStock = $ListData;
                        }
                    }
                    $finalUpdate->delete();
                }
            } else if ($request->type == '1') {
                $puchesDatas = parcheReturnItemEdit::where('id', $request->purches_return_id)->first();
                if (isset($puchesDatas)) {
                    $legaderData  = LedgerModel::where('iteam_id', $puchesDatas->iteam_id)->where('batch', $puchesDatas->batch)->where('user_id', auth()->user()->id)->where('transction', 'Purchase Return Invoice')->orderBy('id')->first();
                    if (isset($legaderData)) {
                        $legaderData->delete();
                    }

                    $legaderData  = LedgerModel::where('iteam_id', $puchesDatas->iteam_id)->where('user_id', auth()->user()->id)->orderBy('id')->get();

                    if (isset($legaderData)) {
                        $prevStock = null;
                        foreach ($legaderData as $ListData) {
                            if ($prevStock !== null) {
                                if ($prevStock->in) {
                                    $amount = $prevStock->balance_stock - $ListData->in;
                                    $ListData->balance_stock = round($amount, 2);
                                } else {
                                    $amount = $prevStock->balance_stock - $ListData->out;
                                    $ListData->balance_stock = round($amount, 2);
                                }
                            } else {
                                $ListData->balance_stock = $ListData->out ?? 0;
                            }
                            $ListData->update();
                            $prevStock = $ListData;
                        }
                    }

                    $puchesDatas->delete();
                }
            }
          
            $userLogs = new LogsModel;
            $userLogs->message = 'Purchase Return Bill Item Delete';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            return $this->sendResponse([], 'Purchase Return Iteam Delete Successfully.');
        } catch (\Exception $e) {
            Log::info("Purchase Return delete api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesReturnIteamHistroy(Request $request)
    {
        try {

            if ($request->type == '0') {
                $userId = auth()->user()->id;
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                // Get the distributor ID from the request
                $distributorId = $request->distributor_id;

                // Fetch the IDs of PurchesModel records based on user_id and distributor_id
                $purchesDataId = PurchesModel::whereIn('user_id',  $allUserId)->where('distributor_id', $distributorId)->pluck('id')->toArray();

                $purchesDetails =  PurchesDetails::whereIn('purches_id', $purchesDataId);
                if ($request->has(['start_date', 'end_date'])) {
                    $startDate = $request->start_date;
                    $endDate = $request->end_date;

                    // Convert start and end dates to 'Ym' format
                    $startDateFormatted = Carbon::createFromFormat('m/y', $startDate)->format('Ym');
                    $endDateFormatted = Carbon::createFromFormat('m/y', $endDate)->endOfMonth()->format('Ym');

                    // Add the whereRaw conditions to the query
                    $purchesDetails->whereRaw("CONCAT('20', SUBSTRING(exp_dt, 4, 2), SUBSTRING(exp_dt, 1, 2)) >= ?", [$startDateFormatted])
                        ->whereRaw("CONCAT('20', SUBSTRING(exp_dt, 4, 2), SUBSTRING(exp_dt, 1, 2)) <= ?", [$endDateFormatted]);
                }

                $purchesDetails = $purchesDetails->pluck('purches_id')->toArray();

                $finalAmoiunt = FinalIteamId::whereIn('purchase_id', $purchesDetails)->pluck('final_item_id')->toArray();

                // Initialize the query for FinalPurchesItem based on purches_id
                $puchesDatas = FinalPurchesItem::withTrashed()->whereIn('id', $finalAmoiunt)->get();

                if (isset($puchesDatas)) {
                    foreach ($puchesDatas as $list) {
                        $list->deleted_at = null;
                        $list->update();
                        $userId = auth()->user()->id;
                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = array(auth()->user()->id);
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                        $purchesDataIds = FinalIteamId::where('final_item_id', $list->id)->pluck('purchase_id')->toArray();

                        $purchesEdit = PurchesDetails::whereIn('user_id', $allUserId)->whereIn('purches_id', $purchesDataIds)->where('batch', $list->batch)->orderBy('id', 'DESC')->first();

                        if (isset($purchesEdit)) {
                            $finalPurchesData = FinalPurchesItem::where('id', $list->id)->first();

                            if (isset($finalPurchesData)) {
                                $batchData = BatchModel::where('batch_number', $finalPurchesData->batch)->whereIn('user_id', $allUserId)->where('item_id', $finalPurchesData->iteam_id)->first();

                                $qtyDataFree = (int)$batchData->purches_free_qty;
                                $qtyData = (int)$batchData->purches_qty;

                                $finalPurchesData->qty = abs($qtyData);
                                $finalPurchesData->fr_qty = abs($qtyDataFree);
                                $finalPurchesData->iss_check = '0';
                                $finalPurchesData->status = '0';
                                $finalPurchesData->iteam_id = $purchesEdit->iteam_id;
                                $finalPurchesData->batch =  $purchesEdit->batch;
                                $finalPurchesData->exp_dt =  $purchesEdit->exp_dt;
                                $finalPurchesData->mrp = $purchesEdit->mrp;
                                $finalPurchesData->ptr = $purchesEdit->ptr;
                                $finalPurchesData->iss_check = '0';
                                $finalPurchesData->disocunt = $purchesEdit->disocunt;
                                $finalPurchesData->gst =  $purchesEdit->gst;
                                $finalPurchesData->location =  $purchesEdit->location;
                                $finalPurchesData->unit = $purchesEdit->weightage;
                                $finalPurchesData->amount =  round($purchesEdit->net_rate, 2);
                                $finalPurchesData->weightage =  $purchesEdit->weightage;
                                $finalPurchesData->update();
                            }
                        }
                    }
                }
            } else if ($request->type == '1') {
                $userId = auth()->user()->id;
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                // Get the distributor ID from the request
                $distributorId = $request->distributor_id;
                $purchesDeatils = PurchesReturn::whereIn('user_id', $allUserId)
                    ->where('distributor_id', $distributorId);
                $purchesReturn = $purchesDeatils->pluck('id')
                    ->toArray();

                $purchesIteamData = PurchesReturnDetails::whereIn('purches_id', $purchesReturn);

                if ($request->has(['start_date', 'end_date'])) {
                    $startDate = $request->start_date;
                    $endDate = $request->end_date;

                    // Convert start and end dates to 'Ym' format
                    $startDateFormatted = Carbon::createFromFormat('m/y', $startDate)->format('Ym');
                    $endDateFormatted = Carbon::createFromFormat('m/y', $endDate)->endOfMonth()->format('Ym');

                    // Add the whereRaw conditions to the query
                    $purchesIteamData->whereRaw("CONCAT('20', SUBSTRING(exp_dt, 4, 2), SUBSTRING(exp_dt, 1, 2)) >= ?", [$startDateFormatted])
                        ->whereRaw("CONCAT('20', SUBSTRING(exp_dt, 4, 2), SUBSTRING(exp_dt, 1, 2)) <= ?", [$endDateFormatted]);
                }
                $purchesReturnId = $purchesIteamData->pluck('purches_id')
                    ->toArray();

                $puchesDatasNew = parcheReturnItemEdit::withTrashed()->whereIn('purches_id', $purchesReturnId)->get();
                if (isset($puchesDatasNew)) {
                    foreach ($puchesDatasNew as $list) {
                        $list->forceDelete();
                    }
                }

                $purchesReturnId = $purchesIteamData->get();

                if (isset($purchesReturnId)) {
                    foreach ($purchesReturnId as $purchesHistroyData) {
                        $purchesReturnDatas = new parcheReturnItemEdit;
                        $purchesReturnDatas->purches_id = $purchesHistroyData->purches_id;
                        $purchesReturnDatas->iteam_id = $purchesHistroyData->iteam_id;
                        $purchesReturnDatas->batch = $purchesHistroyData->batch;
                        $purchesReturnDatas->exp_dt = $purchesHistroyData->exp_dt;
                        $purchesReturnDatas->mrp = $purchesHistroyData->mrp;
                        $purchesReturnDatas->ptr = $purchesHistroyData->ptr;
                        $purchesReturnDatas->iss_check = '0';
                        $purchesReturnDatas->user_id = auth()->user()->id;
                        $purchesReturnDatas->fr_qty = $purchesHistroyData->fr_qty;
                        $purchesReturnDatas->qty = $purchesHistroyData->qty;
                        $purchesReturnDatas->disocunt = $purchesHistroyData->disocunt;
                        $purchesReturnDatas->gst = $purchesHistroyData->gst;
                        $purchesReturnDatas->location = $purchesHistroyData->location;
                        $purchesReturnDatas->unit = $purchesHistroyData->unit;
                        $purchesReturnDatas->amount = round($purchesHistroyData->amount, 2);
                        $purchesReturnDatas->weightage = $purchesHistroyData->weightage;
                        $purchesReturnDatas->save();
                    }
                }

                // // Initialize the query for FinalPurchesItem based on purches_id
                // $puchesDatasNew = parcheReturnItemEdit::withTrashed()->whereIn('purches_id', $purchesReturnId)->get();

                // if (isset($puchesDatasNew)) {
                //     foreach ($puchesDatasNew as $list) {

                //        $purchesHistroyData =  PurchesReturnDetails::where('id',$list->id)->first();

                //         if (!empty($purchesHistroyData)) {
                //             $purchesReturnDatas = parcheReturnItemEdit::find($list->id);
                //             $purchesReturnDatas->iteam_id = $purchesHistroyData->iteam_id;
                //             $purchesReturnDatas->batch = $purchesHistroyData->batch;
                //             $purchesReturnDatas->exp_dt = $purchesHistroyData->exp_dt;
                //             $purchesReturnDatas->mrp = $purchesHistroyData->mrp;
                //             $purchesReturnDatas->ptr = $purchesHistroyData->ptr;
                //             $purchesReturnDatas->iss_check = '0';
                //             $purchesReturnDatas->fr_qty = $purchesHistroyData->fr_qty;
                //             $purchesReturnDatas->qty = $purchesHistroyData->qty;
                //             $purchesReturnDatas->disocunt = $purchesHistroyData->disocunt;
                //             $purchesReturnDatas->gst = $purchesHistroyData->gst;
                //             $purchesReturnDatas->location = $purchesHistroyData->location;
                //             $purchesReturnDatas->unit = $purchesHistroyData->unit;
                //             $purchesReturnDatas->amount = round($purchesHistroyData->amount, 2);
                //             $purchesReturnDatas->weightage = $purchesHistroyData->weightage;
                //             $purchesReturnDatas->update();
                //         }
                //     }
                // }
            }

            return $this->sendResponse([], 'Purchase Return Iteam History Successfully.');
        } catch (\Exception $e) {
            Log::info("Purchase Return histroy api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purcheReturDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => "Enter Purches Retur Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $purchesData = PurchesReturn::where('id', $request->id)->first();
            if (isset($purchesData)) {
                $purchesData->delete();
            }

            $purchesIteamData = PurchesReturnDetails::where('purches_id', $request->id)->get();
            if (isset($purchesIteamData)) {
                foreach ($purchesIteamData as $list) {

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $batchNumber = BatchModel::where('batch_number', $list->batch)->whereIn('user_id', $allUserId)->where('item_id', $list->iteam_id)->first();
                    if (isset($batchNumber)) {

                        $qty = (int)$batchNumber->purches_qty + (int)$list->qty;
                        $qtyFree = (int)$batchNumber->purches_free_qty + (int)$list->fr_qty;
                        $dataValue = ((int)$list->qty + (int)$list->fr_qty) * $list->unit;
                        $totalQty = (int)$batchNumber->total_qty + $dataValue;

                        $batchNumber->qty = abs($qty);
                        $batchNumber->free_qty = abs($qtyFree);
                        $batchNumber->purches_qty = abs($qty);
                        $batchNumber->purches_free_qty = abs($qtyFree);
                        $batchNumber->total_qty = abs($totalQty);
                        $batchNumber->update();
                    }

                    $finalPurchesAmount = FinalPurchesItem::where('batch', $list->batch)->whereIn('user_id', $allUserId)->where('iteam_id', $list->iteam_id)->first();
                    if (isset($finalPurchesAmount)) {
                        $qtyNew = (int)$finalPurchesAmount->qty + (int)$list->qty;
                        $qtyFreeNew = (int)$finalPurchesAmount->fr_qty + (int)$list->fr_qty;
                        if (($qtyNew <= $batchNumber->qty) || ($qtyFreeNew <= $batchNumber->free_qty)) {
                            $finalPurchesAmount->qty = abs($batchNumber->qty);
                            $finalPurchesAmount->fr_qty = abs($batchNumber->free_qty);
                            $finalPurchesAmount->status = '0';
                            $finalPurchesAmount->iss_check = '0';
                            $finalPurchesAmount->iss_delete_check = '1';
                            $finalPurchesAmount->update();
                        } else {
                            $finalPurchesAmount->qty = abs($qtyNew);
                            $finalPurchesAmount->fr_qty = abs($qtyFreeNew);
                            $finalPurchesAmount->status = '0';
                            $finalPurchesAmount->iss_check = '0';
                            $finalPurchesAmount->iss_delete_check = '1';
                            $finalPurchesAmount->update();
                        }
                    }

                    $legaderData  = LedgerModel::where('iteam_id', $list->iteam_id)->where('batch', $list->batch)->where('user_id', auth()->user()->id)->where('transction', 'Purchase Return Invoice')->orderBy('id')->first();
                    if (isset($legaderData)) {
                        $legaderData->delete();
                    }

                    $legaderData  = LedgerModel::where('iteam_id', $list->iteam_id)->where('user_id', auth()->user()->id)->orderBy('id')->get();

                    if (isset($legaderData)) {
                        $prevStock = null;
                        foreach ($legaderData as $ListData) {
                            if ($prevStock !== null) {
                                if ((isset($prevStock->in)) && (isset($ListData->in))) {
                                    $amount = $prevStock->balance_stock - $ListData->in;
                                    $ListData->balance_stock = round($amount, 2);
                                } else {
                                    $amount = $prevStock->balance_stock - $ListData->out;
                                    $ListData->balance_stock = round($amount, 2);
                                }
                            } else {
                                $ListData->balance_stock = $ListData->in ?? 0;
                            }
                            $ListData->update();
                            $prevStock = $ListData;
                        }
                    }

                    $iteamModel =  IteamsModel::where('id', $list->iteam_id)->first();
                    if (isset($iteamModel)) {
                        $iteamModel->stock = $iteamModel->stock + $list->qty;
                        $iteamModel->update();
                    }
                    $list->delete();
                }
            }

            $purchesEditData = parcheReturnItemEdit::where('purches_id', $request->id)->get();
            if (isset($purchesEditData)) {
                foreach ($purchesEditData as $listData) {
                    $purcherReturnHistory = PurchesReturnHistory::where('item_history_id', $listData->id)->first();
                    if (isset($purcherReturnHistory)) {
                        $purcherReturnHistory->delete();
                    }
                    $listData->delete();
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Purchase Return Bill delete';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
            return $this->sendResponse([], 'Purchase Return Delete Successfully.');
        } catch (\Exception $e) {
            Log::info("Purchase Return delete api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function distributorPayment(Request $request)
    {
        try {

            $userId = auth()->user()->id;
            $purcheData = PurchesModel::where('pending_amount_status', '0')->where('user_id', $userId)->pluck('distributor_id')->toArray();
            $distributorName = Distributer::whereIn('id', $purcheData)->get();

            $purchesDetails = [];
            if (isset($distributorName)) {
                foreach ($distributorName as $key => $list) {

                    $purchesDetails[$key]['id'] = isset($list->id) ? $list->id : "";
                    $purchesDetails[$key]['name'] = isset($list->name) ? $list->name : "";
                }
            }

            return $this->sendResponse($purchesDetails, 'Distributor Payment Successfully.');
        } catch (\Exception $e) {
            Log::info("distributor Payment api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesReturniteamUpdate(Request $request)
    {
        try {
            $puchesDatas = parcheReturnItemEdit::where('id', $request->purches_return_id)->first();

            if (isset($puchesDatas)) {
                $purchesHistroyData = PurchesReturnHistory::where('item_history_id', $puchesDatas->id)->first();

                if (empty($purchesHistroyData)) {
                    $purchesHistroyData = new PurchesReturnHistory;
                }
                $purchesHistroyData->item_history_id = $puchesDatas->id;
                $purchesHistroyData->iteam_id = $puchesDatas->iteam_id;
                $purchesHistroyData->batch = $puchesDatas->batch;
                $purchesHistroyData->exp_dt = $puchesDatas->exp_dt;
                $purchesHistroyData->mrp = $puchesDatas->mrp;
                $purchesHistroyData->ptr = $puchesDatas->ptr;
                $purchesHistroyData->fr_qty = $puchesDatas->fr_qty;
                $purchesHistroyData->qty = $puchesDatas->qty;
                $purchesHistroyData->disocunt = $puchesDatas->disocunt;
                $purchesHistroyData->gst = $puchesDatas->gst;
                $purchesHistroyData->location = $puchesDatas->location;
                $purchesHistroyData->amount = round($puchesDatas->amount, 2);
                $purchesHistroyData->weightage = $puchesDatas->weightage;
                $purchesHistroyData->save();

                // Update the $puchesDatas object
                $puchesDatas->iteam_id = $request->iteam_id;
                $puchesDatas->batch = $request->batch;
                $puchesDatas->exp_dt = $request->exp_dt;
                $puchesDatas->mrp = $request->mrp;
                $puchesDatas->ptr = $request->ptr;
                $puchesDatas->fr_qty = $request->fr_qty;
                $puchesDatas->qty = $request->qty;
                $puchesDatas->disocunt = $request->disocunt;
                $puchesDatas->gst = $request->gst;
                $puchesDatas->location = $request->location;
                $puchesDatas->unit = $request->unit;
                $puchesDatas->amount = round($request->amount, 2);
                $puchesDatas->weightage = $request->weightage;
                $puchesDatas->update();

                $userLogs = new LogsModel;
                $userLogs->message = 'Purchase Return Bill Edit';
                $userLogs->user_id = auth()->user()->id;
                $userLogs->date_time = date('Y-m-d H:i a');
                $userLogs->save();
            }
            return $this->sendResponse([], 'Purchase Return Edit Successfully.');
        } catch (\Exception $e) {
            Log::info("Purchase Return update api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesPdfDownloadsNew($id)
    {
        try {
            return $this->generatePdf($id);
        } catch (\Exception $e) {
            Log::info("Purchase Return update api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesPdfDownloads(Request $request)
    {
        try {
            $html_url = route('generate.pdf', $request->id);

            // Define directory path correctly
            $directory = public_path('pdfs/' . date('Y-m-d'));
          	
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Generate PDF using API
            $pdfFileName = 'gen_' . date('YmdHis') . '.pdf';
            $response = $this->generatePdf_api2pdf($html_url, $pdfFileName);
            $pdf = json_decode($response, true);
			
            if (!isset($pdf['FileUrl'])) {
                throw new \Exception("PDF generation failed.");
            }

            // Define file path
            $p_file_name = "pdf_" . date('YmdHis') . ".pdf";
            $pdfurl = 'pdfs/' . date('Y-m-d') . '/' . $p_file_name;
            file_put_contents(public_path($pdfurl), file_get_contents($pdf['FileUrl']));
            // Generate public URL
            $pdfPublicUrl = asset('public/' . $pdfurl);

            return $this->sendResponse(['pdf_url' => $pdfPublicUrl], 'PDF Generated Successfully.');
        } catch (\Exception $e) {
            Log::error("PDF Generation Error: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generatePdf($id)
    {
        $purchesData = PurchesModel::where('id', $id)->first();
        $purchesDetails = [];

        if ($purchesData) {
            $TotalAmount = PurchesDetails::where('purches_id', $purchesData->id)->sum('amount');
            $distributorData = Distributer::where('id', $purchesData->distributor_id)->first();
            $userIdData = User::where('id', $purchesData->user_id)->first();

            $purchesDetails = $this->populatePurchesDetails($purchesData, $TotalAmount, $distributorData, $userIdData);
            $purchesDetails['item_list'] = $this->populateItemDetails($purchesData->id);
        }
        //return $this->savePdf($purchesDetails, $id);
        return view('pdf_template', compact('purchesDetails')); // Render the view into HTML
    }

    private function populatePurchesDetails($purchesData, $TotalAmount, $distributorData, $userIdData)
    {
        $bankName = BankAccount::where('id', $purchesData->payment_type)->first();
        $purchesItemDetailsCount = PurchesDetails::where('purches_id', $purchesData->id)->orderBy('id', 'DESC')->count();
        $purchesItemDetailsQty = PurchesDetails::where('purches_id', $purchesData->id)->orderBy('id', 'DESC')->sum('qty');
        $purchesItemDetailsFree = PurchesDetails::where('purches_id', $purchesData->id)->orderBy('id', 'DESC')->sum('fr_qty');
      	$licenseData = LicenseModel::where('user_id',$userIdData->id)->first();

        return [
            'id' => $purchesData->id ?? "",
          	'license_20' => isset($licenseData->license_no) ? $licenseData->license_no : "",
          	'license_21' => isset($licenseData->license_no_two) ? $licenseData->license_no_two : "",
          	'fssai_no' => isset($licenseData->license_no_three) ? $licenseData->license_no_three : "",
            'cn_amount' => number_format($purchesData->cn_amount, 0, '', ',') ?? "",
            'distributor_id' => $purchesData->distributor_id ?? "",
            'distributor_name' => $distributorData->name ?? "",
            'address' => $userIdData->address ?? "",
            'phone_number' => $userIdData->phone_number ?? "",
            'logo' => isset($userIdData->pharmacy_logo) ? asset('/pharmacy_logo/' . $userIdData->pharmacy_logo) : '',
            'gst_pan' => $userIdData->gst_pan ?? "",
            'pan_card' => $userIdData->pan_card ?? "",
            'distributor_phone_number' => $distributorData->phone_number ?? "",
            'distributor_gst' => $distributorData->gst ?? "",
            'user_name' => $userIdData->name ?? "",
            'owner_type' => $purchesData->owner_type ?? "",
            'payment_type' => isset($bankName->bank_name) ? $bankName->bank_name : $purchesData->payment_type,
            'bill_no' => $purchesData->bill_no ?? "",
            'bill_date' => $purchesData->bill_date ?? "",
            'due_date' => $purchesData->due_date ?? "",
            'round_off' => $purchesData->round_off ?? "",
            'net_amount' => $purchesData->net_amount ? 'Rs. ' . number_format($purchesData->net_amount, 0, '', ',') . '' : "",
            'total_gst' => $purchesData->total_gst,
            'total_amount' => $purchesData->total_amount ? number_format($purchesData->total_amount, 0, '', ',') : "",
            'sgst' => $purchesData->sgst ?? "",
          	'total_item_qty' => $purchesItemDetailsQty,
          	'total_free_item_qty' => $purchesItemDetailsFree,
            'total_qty' => $purchesItemDetailsQty + $purchesItemDetailsFree,
            'cgst' => $purchesData->cgst ?? "",
            'iteam_count' => $purchesItemDetailsCount ?? "",
            'item_list' => []
        ];
    }

    private function populateItemDetails($purchesId)
    {
        $itemDetails = [];
        $purchesItemDetails = PurchesDetails::where('purches_id', $purchesId)->orderBy('id', 'DESC')->get();

        foreach ($purchesItemDetails as $key => $details) {
            $iteamModel = IteamsModel::where('id', $details->iteam_id)->first();
            $uniteName = UniteTable::where('id', $details->unit)->first();
            $gstName = GstModel::where('id', $details->gst)->first();

            $itemDetails[$key] = [
                'id' => $details->id ?? "",
                'item_id' => $details->iteam_id ?? "",
                'front_photo' => isset($iteamModel->front_photo) ? asset('/public/front_photo/' . $iteamModel->front_photo) : "",
                'item_name' => $iteamModel->iteam_name ?? "",
                'batch_number' => $details->batch ?? "",
                'gst_name' => $gstName->name ?? "",
                'expiry' => $details->exp_dt ?? "",
                'mrp' => $details->mrp ?? "",
                'net_rate' => $details->net_rate ?? "",
                'ptr' => $details->ptr ?? "",
                'random_number' => $details->random_number ?? "",
                'iteam_purches_id' => $details->iteam_purches_id ?? "",
                'fr_qty' => $details->fr_qty ?? "",
                'qty' => $details->qty ?? "",
                'disocunt' => $details->disocunt ?? "",
                'gst' => $details->gst ?? "",
                'location' => $details->location ?? "",
                'unit' => $details->unit ?? "",
                'amount' => $details->amount ? round($details->amount, 2) : "",
                'base_price' => $details->base ?? "",
                'weightage' => $details->weightage ?? "",
                'textable' => $details->textable ?? "",
                'scheme_account' => $details->scheme_account ?? "",
                'margin' => $details->margin ?? "",
                'disocunt' => $details->disocunt ?? "",
            ];
        }

        return $itemDetails;
    }

    private function savePdf($purchesDetails, $id)
    {
        $baseDirectory = public_path('pdfs'); // Base directory
        if (!file_exists($baseDirectory)) {
            mkdir($baseDirectory, 0777, true);
        }


        $htmlContent = view('pdf_template', compact('purchesDetails'))->render(); // Render the view into HTML

        $directory = public_path('pdfs/' . date('Y-m-d')); // Ensure full path
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $pdfFileName = 'purches_details_' . $id . '.pdf';
        $response = $this->generatePdf_api2pdf($htmlContent, $pdfFileName);


        if (!isset($pdf['FileUrl']) || empty($pdf['FileUrl'])) {
            return $this->sendError('PDF generation failed', 500);
        }

        $pdfPath = $directory . '/' . $pdfFileName;
        file_put_contents($pdfPath, file_get_contents($pdf['FileUrl']));

        // Return the public URL of the stored PDF
        $pdfPublicUrl = asset('pdfs/' . date('Y-m-d') . '/' . $pdfFileName);

        return $this->sendResponse(['pdf_url' => $pdfPublicUrl], 'PDF generated successfully');
    }

    private function logPdfDownload()
    {
        $userLogs = new LogsModel;
        $userLogs->message = 'Purchase Bill PDF Downloads';
        $userLogs->user_id = auth()->user()->id;
        $userLogs->date_time = now(); // Use now() instead of date()
        $userLogs->save();
    }

    public function purchesReturnPdf(Request $request)
    {
        $html_url = route('generate.pdf.retrun', $request->id);

        // Define directory path correctly
        $directory = public_path('pdfs/' . date('Y-m-d'));
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate PDF using API
        $pdfFileName = 'gen_' . date('YmdHis') . '.pdf';
        $response = $this->generatePdf_api2pdf($html_url, $pdfFileName);
        $pdf = json_decode($response, true);

        if (!isset($pdf['FileUrl'])) {
            throw new \Exception("PDF generation failed.");
        }

        // Define file path
        $p_file_name = "pdf_" . date('YmdHis') . ".pdf";
        $pdfurl = 'pdfs/' . date('Y-m-d') . '/' . $p_file_name;
        file_put_contents(public_path($pdfurl), file_get_contents($pdf['FileUrl']));

        // Generate public URL
        $pdfPublicUrl = asset('public/' . $pdfurl);

        return $this->sendResponse(['pdf_url' => $pdfPublicUrl], 'PDF Generated Successfully.');
    }

    public function getPurcahesRetrunPdfGenrate($id)
    {
        $purchesData = PurchesReturn::where('id', $id)->orderBy('id', 'DESC')->first();

        if (!$purchesData) {
            return $this->sendError('Purchase data not found');
        }

        // Calculate Total Amount
        $TotalAmount = PurchesReturnDetails::where('purches_id', $purchesData->id)->sum('amount');
        $distributorData = Distributer::where('id', $purchesData->distributor_id)->first();
        $userIdData = User::where('id', $purchesData->user_id)->first();
      	$licenseData = LicenseModel::where('user_id',$userIdData->id)->first();
        $bankName = BankAccount::where('id', $purchesData->payment_type)->first();

        $purchesItemDetails = PurchesReturnDetails::where('purches_id', $purchesData->id)
            ->orderBy('id', 'DESC')
            ->get(['qty', 'fr_qty', 'gst', 'ptr']);

        $purchesItemDetailsQty = $purchesItemDetails->sum('qty');
        $purchesItemDetailsFree = $purchesItemDetails->sum('fr_qty');
        $purchesItemDetailsCount = $purchesItemDetails->count();
        $purchesItemBase = $purchesItemDetails->sum('ptr');
        $purchesItemGST = $purchesItemDetails->sum('gst');

        if ($purchesItemDetailsCount > 0) {
            $totalBase = (int)$purchesItemBase;
            $gstData = $purchesItemGST / $purchesItemDetailsCount;
            $totalGst = $totalBase * $gstData / 100;
            $total_gst = (string)round($totalGst, 0);
        } else {
            $total_gst = '0';
        }

        // Prepare Purchase Details
        $purchesDetails = [
            'id' => $purchesData->id ?? "",
            'license_20' => isset($licenseData->license_no) ? $licenseData->license_no : "",
            'license_21' => isset($licenseData->license_no_two) ? $licenseData->license_no_two : "",
            'fssai_no' => isset($licenseData->license_no_three) ? $licenseData->license_no_three : "",
            'distributor_id' => $purchesData->distributor_id ?? "",
            'distributor_name' => $distributorData->name ?? "",
            'distributor_gst' => $distributorData->gst ?? "",
            'logo' => isset($userIdData->pharmacy_logo) ? asset('/pharmacy_logo/' . $userIdData->pharmacy_logo) : '',
            'address' => $userIdData->address ?? "",
            'phone_number' => $userIdData->phone_number ?? "",
            'gst_pan' => $userIdData->gst_pan ?? "",
            'pan_card' => $userIdData->pan_card ?? "",
            'distributor_phone_number' => $distributorData->phone_number ?? "",
            'user_name' => $userIdData->name ?? "",
            'bill_no' => $purchesData->bill_no ?? "",
            'payment_type' => isset($bankName->bank_name) ? $bankName->bank_name : $purchesData->payment_type,
            'bill_date' => $purchesData->select_date ?? "",
            'remark' => $purchesData->remark ?? "",
            'round_off' => $purchesData->round_off ?? "",
            'net_amount' => isset($purchesData->net_amount) ? (string)   'Rs. ' . number_format($purchesData->net_amount, 0, '', ',') : "",
            'final_amount' => isset($purchesData->final_amount) ? '₹ ' . number_format($purchesData->final_amount, 0, '', ',') . '/-' : "",
            'total_amount' => isset($TotalAmount) ? (string)round($TotalAmount, 2) : "",
            'cgst' => isset($purchesData->cgst) ? $purchesData->cgst : "",
            'sgst' => isset($purchesData->sgst) ? $purchesData->sgst : "",
          	'total_item_qty' => $purchesItemDetailsQty,
          	'total_item_free_qty' => $purchesItemDetailsFree,
            'total_qty' => $purchesItemDetailsQty + $purchesItemDetailsFree,
            'iteam_count' => $purchesItemDetailsCount ?? "",
            'total_gst' =>  $total_gst,
            'other_amount' => isset($purchesData->adjustment_amoount) ? number_format($purchesData->adjustment_amoount, 0, '', ',') : "",
            'item_list' => []
        ];

        // Fetch and Append Purchase Item Details
        $purchesItemDetails = PurchesReturnDetails::where('purches_id', $purchesData->id)->orderBy('id', 'DESC')->get();
        foreach ($purchesItemDetails as $key => $details) {
            $iteamModel =  IteamsModel::where('id', $details->iteam_id)->first();
            $uniteName = UniteTable::where('id', $details->unit)->first();
            $gstName = GstModel::where('id', $details->gst)->first();
            $purchesDetails['item_list'][$key] = [
                'id' => isset($details->id) ? $details->id : "",
                'item_id' => isset($details->iteam_id) ? $details->iteam_id : "",
                'front_photo' => isset($iteamModel->front_photo) ? asset('/public/front_photo/' . $iteamModel->front_photo) : "",
                'item_name' => isset($iteamModel->iteam_name) ? $iteamModel->iteam_name : "",
                'batch_number' => isset($details->batch) ? $details->batch : "",
                'gst_name' => isset($gstName->name) ? $gstName->name : "",
                'expiry' => isset($details->exp_dt) ? $details->exp_dt : "",
                'mrp' => isset($details->mrp) ? $details->mrp : "",
                'fr_qty' => isset($details->fr_qty) ? $details->fr_qty : "",
                'qty' => isset($details->qty) ? $details->qty : "",
                'disocunt' => isset($details->disocunt) ? $details->disocunt : "",
                'gst' =>  isset($details->gst) ? $details->gst : "",
                'ptr' =>  isset($details->ptr) ? $details->ptr : "",
                'location' => isset($details->location) ? $details->location : "-",
                'unit' => isset($details->unit) ? $details->unit : "",
                'amount' => isset($details->amount) ? (string)round($details->amount, 2) : "",
                'weightage' => isset($details->weightage) ? $details->weightage : "",
            ];
        }

        return view('pdf_template_purches_return', compact('purchesDetails')); // Render the view into HTML
    }

    public function purchesReturnPendingBills(Request $request)
    {
        try {

            $purchesDetails = PurchesReturn::where('distributor_id', $request->distributor_id)->where('purches_return_status', '0');

            $purchesDetailsCount = $purchesDetails->count();
            $purchesDetails = $purchesDetails->orderBy('id', 'DESC')->get();

            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);
            $detailsData = [];
            if ($purchesDetails->count() > 0) { // Checking if there are any results
                foreach ($purchesDetails as $key => $list) {
                    $distributorData = Distributer::where('id', $list->distributor_id)->first();
                    $userIdData = User::where('id', $list->user_id)->first();
                    //$TotalAmount = PurchesReturnDetails::where('purches_id', $list->id)->sum('amount');
                    $TotalAmount = $list->net_amount;
                    $TotalAmountFinal = DistributorPrchesReturnTable::where('purches_return_bill_id', $list->id)->whereIn('user_id', $allUserId)->sum('amount');
                    $pendingAmount = (string)abs($TotalAmount) - (string)abs($TotalAmountFinal);
                    $detailsData[$key]['id'] = $list->id;
                    $detailsData[$key]['bill_no'] = $list->bill_no;
                    $detailsData[$key]['bill_date'] = $list->select_date;
                    $detailsData[$key]['count'] = $purchesDetailsCount;
                    $detailsData[$key]['total_amount'] = (string)round($pendingAmount, 2);
                    $detailsData[$key]['user_name'] = isset($userIdData->name) ? $userIdData->name : "";
                    $detailsData[$key]['distributor_name'] = isset($distributorData->name) ? $distributorData->name : "";
                }
            }
            return $this->sendResponse($detailsData, 'Purchase Return Pending Bills Successfully.');
        } catch (\Exception $e) {
            Log::info("Purchase Return pending  api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchaseReturnIteamSelect(Request $request)
    {
        if ($request->type == '1') {
            $firstData = parcheReturnItemEdit::where('id', $request->id)->first();
            if ($firstData->iss_check == '0') {
                $firstData->iss_check = '1';
            } else if ($firstData->iss_check == '1') {
                $firstData->iss_check = '0';
            }
            $firstData->update();
        } else {
            $firstData = FinalPurchesItem::where('id', $request->id)->first();
            if (isset($firstData)) {
                if ($firstData->iss_check == '0') {
                    $firstData->iss_check = '1';
                } else if ($firstData->iss_check == '1') {
                    $firstData->iss_check = '0';
                }
                $firstData->update();
            }
        }

        return $this->sendResponse([], 'Purchase Return Iteam Selected Successfully.');
    }

    public function multiplePurchesPdfDownloads(Request $request)
    {
        try {
            $html_url = route('multple.pdf.dwonalod', ['user_id' => auth()->user()->id, 'start_date' => $request->start_date, 'end_date' => $request->end_date]);

            // Define directory path correctly
            $directory = public_path('pdfs/' . date('Y-m-d'));
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $pdfFileName = 'gen_' . date('YmdHis') . '.pdf';
            $response = $this->generatePdf_api2pdf($html_url, $pdfFileName);
            $pdf = json_decode($response, true);

            if (!isset($pdf['FileUrl'])) {
                throw new \Exception("PDF generation failed.");
            }

            // Define file path
            $p_file_name = "pdf_" . date('YmdHis') . ".pdf";
            $pdfurl = 'pdfs/' . date('Y-m-d') . '/' . $p_file_name;
            file_put_contents(public_path($pdfurl), file_get_contents($pdf['FileUrl']));

            // Generate public URL
            $pdfPublicUrl = asset('public/' . $pdfurl);

            return $this->sendResponse(['pdf_url' => $pdfPublicUrl], 'PDF Generated Successfully.');
        } catch (\Exception $e) {
            Log::info("Purchase Return pending  api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function multplePdfGenrate($userId, $startDate, $endDate)
    {
        $staffGetData = User::where('create_by', $userId)->pluck('id')->toArray();
        $ownerGet = User::where('id', $userId)->pluck('create_by')->toArray();
        $userId = array($userId);
        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

        // Get the purchases between the provided dates
        $purchesData = PurchesModel::whereBetween('bill_date', [$startDate, $endDate])->whereIn('user_id', $allUserId)->orderBy('id', 'DESC')->get();

        $purchesDetails = [];

        foreach ($purchesData as $list) {
            $TotalAmount = PurchesDetails::where('purches_id', $list->id)->sum('amount');
            $distributorData = Distributer::where('id', $list->distributor_id)->first();
            $userIdData = User::where('id', $list->user_id)->first();
        	$licenseData = LicenseModel::where('user_id', $userIdData->id)->first();

            $bankName = BankAccount::where('id', $list->payment_type)->first();
            $purchesItemDetailsCount = PurchesDetails::where('purches_id', $list->id)->count();
            $purchesItemDetailsQty = PurchesDetails::where('purches_id', $list->id)->sum('qty');
            $purchesItemDetailsFree = PurchesDetails::where('purches_id', $list->id)->sum('fr_qty');

            $itemDetails = [];
            $purchesItemDetails = PurchesDetails::where('purches_id', $list->id)->orderBy('id', 'DESC')->get();

            foreach ($purchesItemDetails as $key => $details) {
                $iteamModel = IteamsModel::where('id', $details->iteam_id)->first();
                $uniteName = UniteTable::where('id', $details->unit)->first();
                $gstName = GstModel::where('id', $details->gst)->first();

                $itemDetails[] = [
                    'id' => $details->id ?? "",
                    'item_id' => $details->iteam_id ?? "",
                    'front_photo' => isset($iteamModel->front_photo) ? asset('/public/front_photo/' . $iteamModel->front_photo) : "",
                    'item_name' => $iteamModel->iteam_name ?? "",
                    'batch_number' => $details->batch ?? "",
                    'gst_name' => $gstName->name ?? "",
                    'expiry' => $details->exp_dt ?? "",
                    'mrp' => $details->mrp ?? "",
                    'net_rate' => $details->net_rate ?? "",
                    'ptr' => $details->ptr ?? "",
                    'random_number' => $details->random_number ?? "",
                    'iteam_purches_id' => $details->iteam_purches_id ?? "",
                    'fr_qty' => $details->fr_qty ?? "",
                    'qty' => $details->qty ?? "",
                    'disocunt' => $details->disocunt ?? "",
                    'gst' => $details->gst ?? "",
                    'location' => $details->location ?? "",
                    'unit' => $details->unit ?? "",
                    'amount' => $details->amount ? round($details->amount, 2) : "",
                    'base_price' => $details->base ?? "",
                    'weightage' => $details->weightage ?? "",
                    'textable' => $details->textable ?? "",
                    'scheme_account' => $details->scheme_account ?? "",
                    'margin' => $details->margin ?? "",
                ];
            }

            $purchesDetails[] = [
                'id' => $list->id ?? "",
                'license_20' => isset($licenseData->license_no) ? $licenseData->license_no : "",
                'license_21' => isset($licenseData->license_no_two) ? $licenseData->license_no_two : "",
                'fssai_no' => isset($licenseData->license_no_three) ? $licenseData->license_no_three : "",
                'cn_amount' => number_format($list->cn_amount, 0, '', ',') ?? "",
                'logo' => isset($userIdData->pharmacy_logo) ? asset('/pharmacy_logo/' . $userIdData->pharmacy_logo) : '',
                'distributor_id' => $list->distributor_id ?? "",
                'distributor_name' => $distributorData->name ?? "",
              	'distributor_gst' => $distributorData->gst ?? "",
                'address' => $userIdData->address ?? "",
                'phone_number' => $userIdData->phone_number ?? "",
                'gst_pan' => $userIdData->gst_pan ?? "",
                'pan_card' => $userIdData->pan_card ?? "",
                'distributor_phone_number' => $distributorData->phone_number ?? "",
                'user_name' => $userIdData->name ?? "",
                'owner_type' => $list->owner_type ?? "",
                'payment_type' => isset($bankName->bank_name) ? $bankName->bank_name : $list->payment_type,
                'bill_no' => $list->bill_no ?? "",
                'bill_date' => $list->bill_date ?? "",
                'due_date' => $list->due_date ?? "",
                'round_off' => $list->round_off ?? "",
                'net_amount' => $list->net_amount ? 'Rs. ' . number_format($list->net_amount, 0, '', ',') : "0",
                'total_gst' => $list->total_gst,
                'total_amount' => $list->total_amount ? number_format($list->total_amount, 0, '', ',') : "",
                'sgst' => $list->sgst ?? "",
              	'total_item_qty' => $purchesItemDetailsQty,
              	'total_item_free_qty' => $purchesItemDetailsFree,
                'total_qty' => $purchesItemDetailsQty + $purchesItemDetailsFree,
                'cgst' => $list->cgst ?? "",
                'iteam_count' => $purchesItemDetailsCount ?? "",
                'item_list' => $itemDetails
            ];
        }

        return view('purchaes_multiple', compact('purchesDetails'));
    }

    public function multiplePurchesReturnPdfDownloads(Request $request)
    {
        try {
            $html_url = route('multple.pdf.dwonalod.retrun', ['user_id' => auth()->user()->id, 'start_date' => $request->start_date, 'end_date' => $request->end_date]);

            // Define directory path correctly
            $directory = public_path('pdfs/' . date('Y-m-d'));
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $pdfFileName = 'gen_' . date('YmdHis') . '.pdf';
            $response = $this->generatePdf_api2pdf($html_url, $pdfFileName);
            $pdf = json_decode($response, true);

            if (!isset($pdf['FileUrl'])) {
                throw new \Exception("PDF generation failed.");
            }

            // Define file path
            $p_file_name = "pdf_" . date('YmdHis') . ".pdf";
            $pdfurl = 'pdfs/' . date('Y-m-d') . '/' . $p_file_name;
            file_put_contents(public_path($pdfurl), file_get_contents($pdf['FileUrl']));

            // Generate public URL
            $pdfPublicUrl = asset('public/' . $pdfurl);

            return $this->sendResponse(['pdf_url' => $pdfPublicUrl], 'PDF generated successfully');
        } catch (\Exception $e) {
            Log::info("Purchase Return pending  api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function getMultplePdfGenrate($userId, $startDate, $endDate)
    {
        $staffGetData = User::where('create_by', $userId)->pluck('id')->toArray();
        $ownerGet = User::where('id', $userId)->pluck('create_by')->toArray();
        $userId = array($userId);
        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

        $purchesReturnData = PurchesReturn::whereBetween('select_date', [$startDate, $endDate])->whereIn('user_id', $allUserId)->orderBy('id', 'DESC')->get();

        $purchesReturnDetails = [];

        foreach ($purchesReturnData as $list) {
            $TotalAmount = PurchesReturnDetails::where('purches_id', $list->id)->sum('amount');
            $distributorData = Distributer::where('id', $list->distributor_id)->first();
            $userIdData = User::where('id', $list->user_id)->first();
      		$licenseData = LicenseModel::where('user_id',$userIdData->id)->first();

            $bankName = BankAccount::where('id', $list->payment_type)->first();
            $purchesItemDetailsCount = PurchesReturnDetails::where('purches_id', $list->id)->count();
            $purchesItemDetailsQty = PurchesReturnDetails::where('purches_id', $list->id)->sum('qty');
            $purchesItemDetailsFree = PurchesReturnDetails::where('purches_id', $list->id)->sum('fr_qty');

            $itemDetails = [];
            $purchaseReturnItemDetails = PurchesReturnDetails::where('purches_id', $list->id)->orderBy('id', 'DESC')->get();

            foreach ($purchaseReturnItemDetails as $key => $details) {
                $iteamModel = IteamsModel::where('id', $details->iteam_id)->first();
                $uniteName = UniteTable::where('id', $details->unit)->first();
                $gstName = GstModel::where('id', $details->gst)->first();

                $itemDetails[] = [
                    'id' => isset($details->id) ? $details->id : "",
                    'item_id' => isset($details->iteam_id) ? $details->iteam_id : "",
                    'front_photo' => isset($iteamModel->front_photo) ? asset('/public/front_photo/' . $iteamModel->front_photo) : "",
                    'item_name' => isset($iteamModel->iteam_name) ? $iteamModel->iteam_name : "",
                    'batch_number' => isset($details->batch) ? $details->batch : "",
                    'gst_name' => isset($gstName->name) ? $gstName->name : "",
                    'expiry' => isset($details->exp_dt) ? $details->exp_dt : "",
                    'mrp' => isset($details->mrp) ? $details->mrp : "",
                    'fr_qty' => isset($details->fr_qty) ? $details->fr_qty : "",
                    'qty' => isset($details->qty) ? $details->qty : "",
                    'disocunt' => isset($details->disocunt) ? $details->disocunt : "",
                    'gst' =>  isset($details->gst) ? $details->gst : "",
                    'ptr' =>  isset($details->ptr) ? $details->ptr : "",
                    'location' => isset($details->location) ? $details->location : "",
                    'unit' => isset($details->unit) ? $details->unit : "",
                    'amount' => isset($details->amount) ? (string)round($details->amount, 2) : "",
                    'weightage' => isset($details->weightage) ? $details->weightage : "",
                ];
            }

            $purchesReturnDetails[] = [
                'id' => $list->id ?? "",
                'license_20' => isset($licenseData->license_no) ? $licenseData->license_no : "",
                'license_21' => isset($licenseData->license_no_two) ? $licenseData->license_no_two : "",
                'fssai_no' => isset($licenseData->license_no_three) ? $licenseData->license_no_three : "",
                'distributor_id' => $list->distributor_id ?? "",
                'distributor_name' => $distributorData->name ?? "",
                'logo' => isset($userIdData->pharmacy_logo) ? asset('/pharmacy_logo/' . $userIdData->pharmacy_logo) : '',
                'address' => $userIdData->address ?? "",
                'phone_number' => $userIdData->phone_number ?? "",
                'gst_pan' => $userIdData->gst_pan ?? "",
                'pan_card' => $userIdData->pan_card ?? "",
                'distributor_phone_number' => $distributorData->phone_number ?? "",
                'user_name' => $userIdData->name ?? "",
                'bill_no' => $list->bill_no ?? "",
                'payment_type' => isset($bankName->bank_name) ? $bankName->bank_name : $list->payment_type,
                'bill_date' => $list->select_date ?? "",
                'remark' => $list->remark ?? "",
                'round_off' => $list->round_off ?? "",
                'net_amount' => isset($list->net_amount) ? (string) 'Rs. ' . number_format($list->net_amount, 0, '', ',') : "",
                'final_amount' => isset($list->final_amount) ? number_format($list->final_amount, 0, '', ',') : "",
                'total_amount' => isset($TotalAmount) ? (string)round($TotalAmount, 2) : "",
                'cgst' => isset($list->cgst) ? $list->cgst : "",
                'sgst' => isset($list->sgst) ? $list->sgst : "",
              	'total_item_qty' => $purchesItemDetailsQty,
              	'total_item_free_qty' => $purchesItemDetailsFree,
                'total_qty' => $purchesItemDetailsQty + $purchesItemDetailsFree,
                'iteam_count' => $purchesItemDetailsCount ?? "",
                'total_gst' =>  $list->total_gst,
                'other_amount' => isset($list->adjustment_amoount) ? number_format($list->adjustment_amoount, 0, '', ',') : "",
                'item_list' => $itemDetails
            ];
        }

        return view('purchase_return_multiple_pdf', compact('purchesReturnDetails'));
    }

    public function generatePdf_api2pdf($url, $filename)
    {
        $api_url = 'https://v2.api2pdf.com/chrome/pdf/url';
        $api_key = "e77b45fa-6feb-4ffb-9dea-1b6fb0bcf82e";

        $payload = [
            'url' => $url,
            'inline' => false,
            'filename' => $filename,
            'options' => [
                "width" => "8.27in",
                "height" => "11.7in",
                "marginTop" => "0in",
                "marginBottom" => "0in",
                "marginLeft" => "0in",
                "marginRight" => "0in"
            ],
        ];

        $headers = [
            'Authorization: ' . $api_key,
            'Content-Type: application/json',
        ];

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
