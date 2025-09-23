<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\SalesModel;
use App\Models\salesDetails;
use PDF;
use Illuminate\Support\Facades\File;
use App\Models\IteamsModel;
use App\Models\User;
use App\Models\BatchModel;
use App\Models\LedgerModel;
use App\Models\SalesIteam;
use App\Models\GstModel;
use App\Models\SalesFinalIteam;
use App\Models\CompanyModel;
use App\Models\PurchesDetails;
use App\Models\OnlineOrder;
use App\Models\CashManagement;
use App\Models\CashCategory;
use App\Models\BankAccount;
use App\Models\PassBook;
use App\Models\CustomerModel;
use App\Models\DoctorModel;
use App\Models\Distributer;
use App\Models\LogsModel;
use App\Models\PurchesModel;
use App\Models\orderStatus;
use App\Models\FinalPurchesItem;
use App\Models\FinalIteamId;
use App\Models\PurchesReturnDetails;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use App\Models\RoyaltyPoint;
use App\Models\LicenseModel;
use Illuminate\Support\Str;

class SalesController extends ResponseController
{
    // this function use sales create
    public function createSales(Request $request)
    {
        try {
          	// dd($request->all());die;
            // $dataList = json_decode($request->product_list, true);
            // foreach ($dataList as $list) {
            // }
            $userId = auth()->user()->id;
            $sales = new SalesModel;
            $sales->bill_date = $request->bill_date;
            $sales->customer_id = $request->customer_id;
            $sales->bill_no = $request->bill_no;
            $sales->net_rate = $request->net_rate;
            $sales->customer_address = $request->customer_address;
            $sales->doctor_id = (isset($request->doctor_id) &&  $request->doctor_id != 'undefined') ? $request->doctor_id : "";
            $sales->mrp_total = $request->total_amount;
            $sales->dicount = $request->total_discount;
            $sales->margin = $request->margin;
            $sales->round_off = $request->round_off;
            $sales->total_discount = $request->discount_amount;
            $sales->adjustment = isset($request->other_amount) ? (string)round((float)$request->other_amount, 2) : "0";
            $sales->net_amt = round($request->net_amount, 2);
            $sales->owner_name = $request->owner_name;
            $sales->pickup = $request->pickup;
            $sales->total_base = $request->total_base;
            $sales->given_amount = $request->given_amount;
            $sales->draft_save = $request->draft_save;
            $sales->due_amount = $request->due_amount;
            $sales->sgst = $request->sgst;
            $sales->cgst = $request->cgst;
            $sales->igst = $request->igst;
          	$sales->previous_loylti_point = $request->previous_loylti_point;
          	$sales->today_loylti_point = $request->today_loylti_point;
            $sales->last_bill_date = $request->last_bill_date;
            $sales->roylti_point = $request->roylti_point;
            $sales->user_id = $userId;
            $sales->status = $request->status;
            $sales->payment_name = $request->payment_name;
            $sales->margin_net_profit = $request->margin_net_profit;
            if ($request->payment_name == 'cash') {
                $cashManage = CashManagement::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();

                if (isset($cashManage)) {
                    $previewData = CashManagement::where('user_id', auth()->user()->id)->where('id', $cashManage->id)->where('description', 'Purchase')->orderBy('id', 'DESC')->first();
                    if (isset($previewData)) {
                        $amountData =  $cashManage->opining_balance - $request->net_amount;
                        $amount = abs($amountData);
                    } else {
                        $amountData =  $cashManage->opining_balance + $request->net_amount;
                        $amount = abs($amountData);
                    }
                    $cashAdd = new CashManagement;
                    $cashAdd->date = $request->bill_date;
                    $cashAdd->description = 'Sales Manage';
                    $cashAdd->type = 'credit';
                    $cashAdd->amount = round($request->net_amount, 2);
                    $cashAdd->reference_no = $request->bill_no;
                    $cashAdd->voucher     = 'sales';
                    $cashAdd->user_id = auth()->user()->id;
                    $cashAdd->opining_balance = round($amount, 2);
                    $cashAdd->save();
                } else {
                    $cashAdd = new CashManagement;
                    $cashAdd->date = $request->bill_date;
                    $cashAdd->description = 'Sales Manage';
                    $cashAdd->type = 'credit';
                    $cashAdd->amount = round($request->net_amount, 2);
                    $cashAdd->user_id = auth()->user()->id;
                    $cashAdd->reference_no = $request->bill_no;
                    $cashAdd->voucher     = 'sales';
                    $cashAdd->opining_balance = round($request->net_amount, 2);
                    $cashAdd->save();
                }
            } else {
                $passBook =  PassBook::where('bank_id', $request->payment_name)->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                if (isset($passBook)) {
                    $amount =  $passBook->balance + $request->net_amount;

                    $customerName  = CustomerModel::where('id', $request->customer_id)->first();
                    $passbook = new  PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $request->bill_date;
                    $passbook->party_name = $customerName->name;
                    $passbook->bank_id = $request->payment_name;
                    $passbook->deposit = round($request->net_amount, 2);
                    $passbook->withdraw     = "";
                    $passbook->balance = round($amount, 2);
                    $passbook->mode = "";
                    $passbook->save();
                } else {
                    $customerName  = CustomerModel::where('id', $request->customer_id)->first();
                    $passbook = new  PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $request->bill_date;
                    $passbook->party_name = $customerName->name;
                    $passbook->bank_id = $request->payment_name;
                    $passbook->deposit = round($request->net_amount, 2);
                    $passbook->withdraw    = "";
                    $passbook->balance = round($request->net_amount, 2);
                    $passbook->mode = "";
                    $passbook->save();
                }
            }
            // payment_name
            $sales->order_number = rand(11111, 99999);
            $distributorData = CustomerModel::where('id', $request->customer_id)->first();
            if (isset($distributorData)) {
                $totalGst = isset($request->total_gst) ? $request->total_gst : 0;
                if (isset($distributor->state) && strtolower($distributor->state) === strtolower($distributorData->state)) {
                    $sales->sgst = $totalGst != 0 ? $totalGst / 2 : "";
                    $sales->cgst =  $totalGst != 0 ? $totalGst / 2 : "";
                } else {
                    $sales->igst = $totalGst;
                }
            }
            $sales->save();

            $dataList = json_decode($request->product_list, true);
            if ((isset($dataList)) && ($sales->draft_save != '0')) {
                foreach ($dataList as $list) {
                    $textbleVlaue = ($list['qty'] ?? 0) * ($list['base'] ?? 0);
                    $userId = auth()->user()->id;
                    $salesDetails = new salesDetails;
                    $salesDetails->sales_id = $sales->id;
                    $salesDetails->taxable_value = $textbleVlaue;
                    $salesDetails->iteam_id = $list['item_id'];
                    $salesDetails->unit = $list['unit'];
                    $salesDetails->batch = $list['batch'];
                    $salesDetails->exp = $list['exp'];
                    $salesDetails->base = $list['base'];
                    $salesDetails->mrp = $list['mrp'];
                    $salesDetails->gst = $list['gst'];
                    $salesDetails->discount = isset($list['discount']) ? $list['discount'] : "";
                    $salesDetails->ptr = isset($list['ptr']) ? $list['ptr'] : "";
                    $salesDetails->qty = $list['qty'];
                    $salesDetails->order = $list['order'];
                    $salesDetails->amt = round($list['net_rate'], 2);
                    $salesDetails->user_id = $userId;
                    $salesDetails->location = $list['location'];
                    $salesDetails->random_number = $list['random_number'];
                    $salesDetails->save();

                    $salesFinalData = new SalesFinalIteam;
                    $salesFinalData->sales_id = $sales->id;
                    $salesFinalData->random_number = $list['random_number'];
                    $salesFinalData->user_id = $userId;
                    $salesFinalData->item_id = $list['item_id'];
                    $salesFinalData->qty = $list['qty'];
                    $salesFinalData->exp = $list['exp'];
                    $salesFinalData->gst = $list['gst'];
                    $salesFinalData->mrp = $list['mrp'];
                    $salesFinalData->amt = $list['net_rate'];
                    $salesFinalData->unit = $list['unit'];
                    $salesFinalData->batch = $list['batch'];
                    $salesFinalData->base = $list['base'];
                    $salesFinalData->order = $list['order'];
                    $salesFinalData->location = $list['location'];
                    $salesFinalData->net_rate = round($list['net_rate'], 2);
                    $salesFinalData->status = '0';
                    $salesFinalData->save();

                    $userName = CustomerModel::where('id', $request->customer_id)->first();

                    $leaderData = new LedgerModel;
                    $leaderData->owner_id = $request->customer_id;
                    $leaderData->entry_date = $request->bill_date;
                    $leaderData->transction = 'Sales Invoice';
                    $leaderData->voucher = 'Sales Invoice';
                    $leaderData->bill_no = '#' . $request->bill_no;
                    $leaderData->puches_id = $sales->id;
                    $leaderData->batch = $list['batch'];
                    $leaderData->bill_date = $request->bill_date;
                    $leaderData->name = $userName->name;
                    $leaderData->user_id = auth()->user()->id;
                    $leaderData->iteam_id = $list['item_id'];
                    $ledgers = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                    if (isset($ledgers)) {
                        $balance = $list['qty'] - $ledgers->balance_stock;
                        $leaderData->out = $list['qty'];
                        $leaderData->balance_stock = abs($balance);
                    } else {
                        $leaderData->out = $list['qty'];
                        $leaderData->balance_stock = $list['qty'];
                    }
                    $ledgers = LedgerModel::where('owner_id', $request->customer_id)->orderBy('id', 'DESC')->first();
                    if (isset($ledgers)) {
                        $total = $ledgers->balance + $request->net_amount;
                        $leaderData->credit = round($request->net_amount, 2);
                        $leaderData->balance = round($total, 2);
                    } else {
                        $leaderData->credit = round($request->net_amount, 2);
                        $leaderData->balance = round($request->net_amount, 2);
                    }
                    $leaderData->save();

                    if (($list['order'] == 'O') || ($list['order'] == 'o')) {
                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = array(auth()->user()->id);
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                        $totalStock = BatchModel::whereIn('user_id', $allUserId)->where('item_id', $list['item_id'])->sum('total_qty');

                        $iteamName  = IteamsModel::where('id', $list['item_id'])->first();
                        $companyData = CompanyModel::where('id', $iteamName->pharma_shop)->first();
                        $purrchesData = PurchesDetails::where('user_id', auth()->user()->id)->where('iteam_id', $list['item_id'])->orderBy('id', 'DESC')->first();
                        if (isset($purrchesData->getpurches->distributor_id)) {
                            $distributorName =  Distributer::where('id', $purrchesData->getpurches->distributor_id)->first();
                        } else {
                            $distributorName = null;
                        }
                      
                      	$alreadyItemDataExist = OnlineOrder::where('item_id',$list['item_id'])->where('y_n',1)->first();
                      	if(isset($alreadyItemDataExist))
                        {
                        	$alreadyItemDataExist->updated_at = date('Y-m-d H:i:s');
                          	$alreadyItemDataExist->update();
                        }else
                        {
                            $orderData = new OnlineOrder;
                            $orderData->sales_id = $sales->id;
                            $orderData->user_id = auth()->user()->id;
                            $orderData->item_id = $list['item_id'];
                            $orderData->y_n = '1';
                            $orderData->stock = isset($totalStock) ? $totalStock : "";
                            $orderData->company_name = isset($companyData->company_name) ? $companyData->company_name : "";
                            $orderData->supplier_name = isset($distributorName->name) ? $distributorName->name : "";
                            $orderData->item_name = isset($iteamName->iteam_name) ? $iteamName->iteam_name : "";
                            $orderData->save();
                        }
                    }

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $batchData = BatchModel::where('batch_number', $list['batch'])->where('item_id', $list['item_id'])->whereIn('user_id', $allUserId)->first();

                    $finalSalesData = FinalPurchesItem::where('batch', $list['batch'])->where('iteam_id', $list['item_id'])->whereIn('user_id', $allUserId)->first();
                    if (isset($finalSalesData)) {

                        $finalData = FinalIteamId::where('final_item_id', $finalSalesData->id)->first();

                        // purchase retrun get data issue
                        $purchaseData = PurchesDetails::where('purches_id', $finalData->purchase_id)->whereIn('user_id', $allUserId)->first();
                        if (isset($purchaseData)) {
                            $salesQty = salesDetails::where('batch', $list['batch'])->where('iteam_id', $list['item_id'])->whereIn('user_id', $allUserId)->sum('qty');
                            $unit = (int)$list['unit'];
                            // $sold_units = (int)$list['qty'] * $unit;
                          	$sold_units = (int)$list['qty'];

                            $purch_qty = (int)$batchData->purches_qty;
                            $free_qty = (int)$batchData->purches_free_qty;
                            $total_available_units = ($purch_qty + $free_qty) * $unit;

                            if ($sold_units > $total_available_units) {
                                return response()->json(['error' => 'Insufficient stock.'], 400);
                            }

                            $remaining_units = $total_available_units - $sold_units;
                            $new_qty = $unit > 0 ? $remaining_units / $unit : 0;

                            // Update stock records
                            $finalSalesData->qty = $new_qty;
                            $finalSalesData->update();
                        }
                    }
                    if (isset($batchData)) {
                        $batchData->item_id = $list['item_id'];
                        $batchData->unit = $list['unit'];
                        $batchData->qty = $list['qty'];
                        $batchData->purches_qty =  $new_qty;
                        $batchData->purches_free_qty = 0;
                        $batchData->gst = isset($list['gst']) ? $list['gst'] : "";
                        $batchData->expiry_date = $list['exp'];
                        $batchData->mrp = $list['mrp'];
                        $batchData->location = $list['location'];

                        $batchData->sales_qty = $batchData->sales_qty + $sold_units;
                        $batchData->total_qty = $remaining_units;
                        $batchData->total_mrp = $list['mrp'] * $list['qty'];
                        $batchData->total_ptr = $list['base'] * $list['qty'];
                        $batchData->update();
                    }
                }
            } else {
                if (isset($dataList)) {
                    foreach ($dataList as $list) {
                        $textbleVlaue = ($list['qty'] ?? 0) * ($list['base'] ?? 0);
                        $userId = auth()->user()->id;
                        $salesDetails = new salesDetails;
                        $salesDetails->sales_id = $sales->id;
                        $salesDetails->taxable_value = $textbleVlaue;
                        $salesDetails->iteam_id = $list['item_id'];
                        $salesDetails->unit = $list['unit'];
                        $salesDetails->batch = $list['batch'];
                        $salesDetails->exp = $list['exp'];
                        $salesDetails->base = $list['base'];
                        $salesDetails->mrp = $list['mrp'];
                        $salesDetails->gst = $list['gst'];
                        $salesDetails->discount = isset($list['discount']) ? $list['discount'] : "";
                        $salesDetails->ptr = isset($list['ptr']) ? $list['ptr'] : "";
                        $salesDetails->qty = $list['qty'];
                        $salesDetails->order = $list['order'];
                        $salesDetails->amt = round($list['net_rate'], 2);
                        $salesDetails->user_id = $userId;
                        $salesDetails->location = $list['location'];
                        $salesDetails->random_number = $list['random_number'];
                        $salesDetails->save();

                        $salesFinalData = new SalesFinalIteam;
                        $salesFinalData->sales_id = $sales->id;
                        $salesFinalData->random_number = $list['random_number'];
                        $salesFinalData->user_id = $userId;
                        $salesFinalData->item_id = $list['item_id'];
                        $salesFinalData->qty = $list['qty'];
                        $salesFinalData->exp = $list['exp'];
                        $salesFinalData->gst = $list['gst'];
                        $salesFinalData->mrp = $list['mrp'];
                        $salesFinalData->amt = $list['net_rate'];
                        $salesFinalData->unit = $list['unit'];
                        $salesFinalData->batch = $list['batch'];
                        $salesFinalData->base = $list['base'];
                        $salesFinalData->order = $list['order'];
                        $salesFinalData->location = $list['location'];
                        $salesFinalData->net_rate = round($list['net_rate'], 2);
                        $salesFinalData->status = '0';
                        $salesFinalData->save();
                    }
                }
              	// else{
                //    return $this->sendError('Please select at least on item.');
                // }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Sales Bill Added';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $datasSales  = [];
            $datasSales['id'] = isset($sales->id) ? $sales->id : '';
            return $this->sendResponse($datasSales, 'Sales Added Successfully.');
        } catch (\Exception $e) {
            Log::info("create sales api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesHistory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'random_number' => 'required',
            ], [
                'random_number.required' => 'Please Enter Random Number'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $iteamSalesData =  SalesIteam::where('random_number', $request->random_number)->orderBy('id', 'DESC')->get();

            if (isset($iteamSalesData)) {
                foreach ($iteamSalesData as $iteamSales) {

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $salesDetailsData = salesDetails::where('random_number', $request->random_number)->whereIn('user_id', $allUserId)->where('iteam_id', $iteamSales->item_id)->where('batch', $iteamSales->batch)->first();
                    if (empty($salesDetailsData)) {
                        $batchData = BatchModel::where('batch_number', $iteamSales->batch)->where('item_id', $iteamSales->item_id)->whereIn('user_id', $allUserId)->first();

                        if (isset($batchData)) {
                            $totalStock = ((int)$batchData->purches_qty + (int)$batchData->purches_free_qty) * $iteamSales['unit'];
                            $batchData->total_qty = $totalStock;
                            $batchData->update();
                        }
                    }
                    $iteamSales->delete();
                }
            }


            $salesDetailsData = salesDetails::where('random_number', $request->random_number)->get();
            if (isset($salesDetailsData)) {
                foreach ($salesDetailsData as $listData) {

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $batchData = BatchModel::where('batch_number', $listData->batch)->where('item_id', $listData->iteam_id)->whereIn('user_id', $allUserId)->first();

                    if (isset($batchData)) {
                        Log::info("history sales api id" . $batchData->id);
                        $totalStock = ((int)$batchData->purches_qty + (int)$batchData->purches_free_qty) * $listData['unit'];
                        Log::info("history sales api purches qty" . $batchData->purches_qty);
                        Log::info("history sales api purches free qty" . $batchData->purches_free_qty);
                        Log::info("history sales api purches total" . $totalStock);
                        $batchData->total_qty = $totalStock;
                        $batchData->update();
                    }

                    $userId = auth()->user()->id;
                    $salesIteam = new SalesIteam;
                    $salesIteam->random_number = $listData['random_number'];
                    $salesIteam->item_id = $listData['iteam_id'];
                    $salesIteam->user_id = $userId;
                    $salesIteam->qty = $listData['qty'];
                    $salesIteam->exp = $listData['exp'];
                    $salesIteam->discount = isset($listData['discount']) ? $listData['discount'] : "";
                    $salesIteam->ptr = isset($listData['ptr']) ? $listData['ptr'] : "";
                    $salesIteam->gst = $listData['gst'];
                    $salesIteam->mrp = $listData['mrp'];
                    $salesIteam->amt = $listData['amt'];
                    $salesIteam->unit = $listData['unit'];
                    $salesIteam->batch = $listData['batch'];
                    $salesIteam->base = $listData['base'];
                    $salesIteam->order = $listData['order'];
                    $salesIteam->location = $listData['location'];
                    $salesIteam->net_rate = round($listData['amt'], 2);
                    $salesIteam->save();
                }
            }

            return $this->sendResponse('', 'Sales History Successfully.');
        } catch (\Exception $e) {
            Log::info("history sales api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesItemAdd(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            $salesIteam = new SalesIteam;
            $salesIteam->random_number = $request->random_number;
            $salesIteam->item_id = $request->item_id;
            $salesIteam->user_id = $userId;
            $salesIteam->qty = $request->qty;
            $salesIteam->exp = $request->exp;
            $salesIteam->gst = $request->gst;
            $salesIteam->mrp = $request->mrp;
            $salesIteam->amt = $request->net_rate;
            $salesIteam->unit = $request->unit;
            $salesIteam->batch = $request->batch;
            $salesIteam->base = $request->base;
            $salesIteam->order = $request->order;
            $salesIteam->location = $request->location;
            if (isset($request->discount)) {
                $salesIteam->discount = $request->discount;
            }
            if (isset($request->ptr)) {
                $salesIteam->ptr = $request->ptr;
            }
            $salesIteam->net_rate = round($request->net_rate, 2);
            $salesIteam->save();

            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $batchData = BatchModel::whereIn('user_id', $allUserId)->where('batch_number', $request->batch)->where('item_id', $request->item_id)->where('mrp', $request->mrp)->where('ptr', $request->ptr)
                ->where('discount', $request->discount)->first();
            if (isset($batchData)) {
                //$totalStock = ((int)$batchData->purches_qty + (int)$batchData->purches_free_qty) * $request->unit;
                $totalStock = (int)$batchData->total_qty;
                Log::info("create batch Id" . $batchData->id);
                Log::info("create sales api" . $totalStock);
                Log::info("create sales api" . $request->qty);
                $qty = (int)$totalStock - (int)$request->qty;
                Log::info("create sales api total" . $qty);
                $batchData->total_qty = abs($qty);
                $batchData->update();
            }


            $userLogs = new LogsModel;
            $userLogs->message = 'Sales Bill Added Item';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            return $this->sendResponse('', 'Sales Added Iteam Successfully');
        } catch (\Exception $e) {

            Log::info("create sales api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function deleteSales(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Please Enter Bill Date',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $salesData = SalesModel::where('id', $request->id)->first();
            if (isset($salesData)) {
                $salesData->delete();
            }

            $salesIteamData = SalesFinalIteam::where('sales_id', $request->id)->get();
            if (isset($salesIteamData)) {
                foreach ($salesIteamData  as $listData) {
                    $listData->delete();
                }
            }

            $salesDatas = salesDetails::where('sales_id',  $request->id)->get();
            if (isset($salesDatas)) {
                foreach ($salesDatas as $list) {

                    $batchNumber = BatchModel::where('batch_name', $list->batch)->first();
                    if (isset($batchNumber)) {
                        $qty = (int)$batchNumber->purches_qty + (int)$batchNumber->purches_free_qty;
                        $totalQty = (int)$qty - (int)$list->qty;
                        $batchNumber->qty = abs($qty);
                        $batchNumber->total_qty = abs($totalQty) * $batchNumber->unit;
                        $batchNumber->update();
                    }

                    $legaderData  = LedgerModel::where('iteam_id', $list->item_id)->where('batch', $list->batch)->where('user_id', auth()->user()->id)->where('transction', 'Sales Invoice')->orderBy('id')->first();
                    if (isset($legaderData)) {
                        $legaderData->delete();
                    }

                    $legaderDetails  = LedgerModel::where('iteam_id', $list->item_id)->where('user_id', auth()->user()->id)->orderBy('id')->get();

                    if (isset($legaderDetails)) {
                        $prevStock = null;
                        foreach ($legaderDetails as $ListData) {
                            if ($prevStock !== null) {
                                if ($prevStock->in) {
                                    $amount =  $prevStock->balance_stock - $ListData->in;
                                    $ListData->balance_stock = abs($amount);
                                } else {
                                    $amount = $ListData->out - $prevStock->balance_stock;
                                    $ListData->balance_stock = abs($amount);
                                }
                            } else {
                                $ListData->balance_stock = $ListData->out ?? 0;
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
                    // if(isset($list->getSales->distributor_id))
                    // {
                    //     $distrutor = CustomerModel::find($list->getSales->distributor_id);
                    //     $distrutor->balance = $distrutor->balance + $list->amount;
                    //     $distrutor->update();
                    // }

                    $list->delete();

                    $iteamSalesData =  SalesIteam::where('random_number', $list->random_number)->get();
                    if (isset($iteamSalesData)) {
                        foreach ($iteamSalesData as $iteamSales) {
                            $iteamSales->delete();
                        }
                    }
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Sales Bill Delete';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            return $this->sendResponse('', 'Sales Delete Successfully');
        } catch (\Exception $e) {
            Log::info("delete sales api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function allSalesItemDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'random_number' => 'required',
            ], [
                'random_number.required' => 'Please Enter Bill Date',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $salesDelete = SalesIteam::where('random_number', $request->random_number)->get();
            if (isset($salesDelete)) {
                foreach ($salesDelete as $list) {

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $batchData = BatchModel::where('batch_number', $list->batch)->where('item_id', $list->item_id)->whereIn('user_id', $allUserId)->first();
                    if (isset($batchData)) {
                        $totalQty = ((int)$batchData->purches_qty + (int)$batchData->purches_free_qty) * $batchData->unit;
                        $batchData->total_qty = $totalQty;
                        $batchData->update();
                    }

                    $list->delete();

                    $userLogs = new LogsModel;
                    $userLogs->message = 'All Sales Item Delete';
                    $userLogs->user_id = auth()->user()->id;
                    $userLogs->date_time = date('Y-m-d H:i a');
                    $userLogs->save();
                }
            }

            return $this->sendResponse('', 'All Sales Iteam Delete Successfully');
        } catch (\Exception $e) {
            Log::info("create sales api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesItemEdit(Request $request)
    {
        try {
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $batchData = BatchModel::whereIn('user_id', $allUserId)->where('batch_number', $request->batch)->where('item_id', $request->item_id)->where('mrp', $request->mrp)->where('ptr', $request->ptr)
                ->where('discount', $request->discount)->first();
            Log::info("edit sales api - disocunt: " . $request->discount);
            Log::info("edit sales api - PTR: " . $request->ptr);
            if (isset($batchData)) {
                // Total stock in units
                $totalStock = ((int)$batchData->purches_qty + (int)$batchData->purches_free_qty) * (int)$batchData->unit;
                $totalStockData = (int)$batchData->sales_qty +  (int)$totalStock;
                Log::info("edit sales api - Total Stock: " . $totalStock . ' - Requested Qty: ' . $request->qty);
                $currentStock =  (int)$totalStockData -  (int)$request->qty;
                // Update the total stock in the batch
                $batchData->total_qty = $currentStock;
                $batchData->update();
            }

            //if (isset($batchData)) {
            // $totalStock =  ((int)$batchData->purches_qty + (int)$batchData->purches_free_qty) * $batchData->unit;
            //Log::info("edit sales api" . $totalStock . ' - ' . $request->qty);

            // Initial stock value
            //$currentStock = $totalStock;

            //$salesIteam = SalesIteam::find($request->id);

            //$previousQty = (int)$salesIteam->qty / (int)$batchData->unit;
            // Log::info("edit sales api previousQty" .$salesIteam->qty . ' / ' . $batchData->unit);
            //Log::info("edit sales api previousQty total" .$previousQty);
            // Sale quantity before editing
            // $previousQty = isset($salesIteam->qty) ? $salesIteam->qty : "";

            //$editedQty = (int)$request->qty / (int)$batchData->unit;
            //Log::info("edit sales api new Qty" .$salesIteam->qty . ' / ' . $batchData->unit);
            //Log::info("edit sales api new total" .$previousQty);

            //if ($editedQty > $previousQty) {
            // Sales quantity increased, reduce stock
            //  $difference = $editedQty - $previousQty;
            // $currentStock -= $difference;
            //} elseif ($editedQty < $previousQty) {
            // Sales quantity decreased, increase stock
            //  $difference = $previousQty - $editedQty;
            //$currentStock += $difference;
            //}

            //$batchData->total_qty = abs($currentStock) * $batchData->unit;
            //$batchData->update();
            //  }

            $userId = auth()->user()->id;
            $salesIteam = SalesIteam::find($request->id);
            $salesIteam->random_number = $request->random_number;
            $salesIteam->item_id = $request->item_id;
            $salesIteam->user_id = $userId;
            $salesIteam->qty = $request->qty;
            $salesIteam->exp = $request->exp;
            $salesIteam->gst = $request->gst;
            $salesIteam->mrp = $request->mrp;
            $salesIteam->amt = $request->net_rate;
            $salesIteam->unit = $request->unit;
            $salesIteam->batch = $request->batch;
            $salesIteam->base = $request->base;
            $salesIteam->order = $request->order;
            $salesIteam->location = $request->location;
            if (isset($request->discount)) {
                $salesIteam->discount = $request->discount;
            }
            if (isset($request->ptr)) {
                $salesIteam->ptr = $request->ptr;
            }
            $salesIteam->net_rate = round($request->net_rate, 2);
            $distributorData = CustomerModel::where('id', $request->customer_id)->first();
            if (isset($distributorData)) {
                $totalGst = isset($request->total_gst) ? $request->total_gst : 0;
                if (isset($distributor->state) && strtolower($distributor->state) === strtolower($distributorData->state)) {
                    $salesIteam->sgst = $totalGst != 0 ? $totalGst / 2 : "";
                    $salesIteam->cgst =  $totalGst != 0 ? $totalGst / 2 : "";
                } else {
                    $salesIteam->igst = $totalGst;
                }
            }
            $salesIteam->update();


            $userLogs = new LogsModel;
            $userLogs->message = 'Sales Bill Item Updated';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            return $this->sendResponse('', 'Sales Updated Iteam Successfully');
        } catch (\Exception $e) {
            Log::info("create sales api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use sales list
    public function listSales(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);
          
          	$saleBillCount = SalesModel::where('user_id', $userId)->count();

            $salesData = SalesModel::whereIn('user_id', $allUserId);
            if (isset($request->bill_no)) {
                $salesData->where('bill_no', $request->bill_no);
            }
          	if (isset($request->bill_date)) {
                $salesData->where('bill_date', 'like', '%' . $request->bill_date . '%');
            }
          	if (isset($request->payment_method)) {
                $salesData->where('payment_name', 'like', '%' . $request->payment_method . '%');
            }
          	if (isset($request->bill_amount)) {
              	$salesData->where('net_amt', 'like', '%' . $request->bill_amount . '%');
                // $salesData->where('mrp_total', 'like', '%' . $request->bill_amount . '%');
            }
            if (isset($request->sort_by)) {
                if ($request->sort_by == 'Entry Date - New to Old') {
                    $salesData->orderBy('created_at', 'desc');
                } else if ($request->sort_by == 'Entry Date - Old to New') {
                    $salesData->orderBy('created_at', 'asc');
                } else if ($request->sort_by == 'Order No. - A to Z') {
                    $salesData->orderBy('id', 'asc');
                } else if ($request->sort_by == 'Order No. - Z to A') {
                    $salesData->orderBy('id', 'desc');
                } else if ($request->sort_by == 'Bill Date - New to Old') {
                    $salesData->orderBy('bill_date', 'desc');
                } else if ($request->sort_by == 'Bill Date - Old to New') {
                    $salesData->orderBy('bill_date', 'asc');
                } else if ($request->sort_by == 'Amount - 1 to 9') {
                    $salesData->orderBy('net_amt', 'asc');
                } else if ($request->sort_by == 'Amount - 9 to 1') {
                    $salesData->orderBy('net_amt', 'desc');
                }
            } else {
                $salesData->orderBy('created_at', 'desc');
            }
            if (isset($request->order_number)) {
                $salesData->where('order_number', $request->order_number);
            }
           	if (!empty($request->name_mobile)) {
                $salesData->whereHas('getUserName', function ($q) use ($request) {
                    $q->where(function ($subQuery) use ($request) {
                        $subQuery->where('name', 'LIKE', '%' . $request->name_mobile . '%')
                                 ->orWhere('phone_number', 'LIKE', '%' . $request->name_mobile . '%');
                    });
                });
            }
            if (isset($request->start_date)) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                $salesData->whereBetween('bill_date', [$startDate, $endDate]);
            }
            if (isset($request->payment_type)) {
                $salesData->where('payment_name', $request->payment_type);
            }
            if (isset($request->item_name)) {
                $iteamName = IteamsModel::whereRaw('LOWER(iteam_name) LIKE ?', ['%' . strtolower($request->item_name) . '%'])->pluck('id')->toArray();
                $salesDetails = salesDetails::whereIn('iteam_id', $iteamName)->pluck('sales_id')->toArray();
                $salesData->whereIn('id', $salesDetails);
            }
            if (isset($request->staff) && ($request->staff != 'All')) {
                if ($request->staff == 'owner') {
                    $salesData->where('user_id', auth()->user()->id);
                } else {
                    $salesData->where('user_id', $request->staff);
                }
            }
          	if ($request->filled('status')) {
                $status = strtolower($request->status); // lowercase just in case

                // Status for Cash
                $cashStatus = ['p', 'pa', 'pai', 'paid', 'a', 'i', 'd', 'ai', 'id'];

                // Status for Credit
                $creditStatus = ['d', 'du', 'due', 'u', 'e', 'ue'];

                if (in_array($status, $cashStatus)) {
                  $salesData->where('payment_name', 'Cash');
                } elseif (in_array($status, $creditStatus)) {
                  $salesData->where('payment_name', 'Credit');
                } else {
                  $salesData->where('payment_name','All');
                }
            }
            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $salesData->offset($offset)->limit($limit);
            $salesData = $salesData->orderBy('id', 'DESC')->get();

            $salesDetails = [];
            if (isset($salesData)) {
                foreach ($salesData as $key => $list) {
                    $bankName  = BankAccount::where('id', $list->payment_name)->first();

                    $salesDetails[$key]['id'] = isset($list->id) ? $list->id : '';
                    $salesDetails[$key]['draft_save'] = isset($list->draft_save) ? $list->draft_save : '';
                    $salesDetails[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : '';
                    $salesDetails[$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date : '';
                    $salesDetails[$key]['payment_name'] = isset($bankName->bank_name) ? $bankName->bank_name : $list->payment_name;
                    $salesDetails[$key]['name'] = isset($list->getUserName->name) ? $list->getUserName->name : '';
                    $salesDetails[$key]['mobile_numbr'] = isset($list->getUserName->phone_number) ? $list->getUserName->phone_number : '';
                    // $salesDetails[$key]['net_amt'] = isset($list->net_amt) ? (string)round($list->net_amt, 2) : '';
                    $salesDetails[$key]['net_amt'] = isset($list->mrp_total) ? (string)round($list->mrp_total, 2) : '';
                    $salesDetails[$key]['bill_create_date_time'] = isset($list->created_at) ? date("Y-m-d h:i", strtotime($list->created_at)) : "";
                    if ($list->payment_name == 'credit') {
                        $salesDetails[$key]['status'] = 'Due';
                    } else {
                        $salesDetails[$key]['status'] = 'Paid';
                    }
                    $salesDetails[$key]['count'] = SalesModel::where('user_id', $userId)->count();
                }
            }
          
          	$response = [
                'status'=> 200,
              	'current_page' => $request->page ?? "1",
              	'count' => !empty($request->page) ? $salesData->count() : $saleBillCount,
              	'total_records' => $saleBillCount,
                'data'    => $salesDetails,
                'message' => 'Sale Bill Data Get Successfully.',
            ];
          	
            return response()->json($response, 200);

            // return $this->sendResponse($salesDetails, 'Sale Bill Data Get Successfully.');
        } catch (\Exception $e) {
            Log::info("sales List api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use sales pdf
    public function salesPdf(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'sales_id' => 'required',
            ], [
                'sales_id.required' => 'Enter Sales_id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $salesId =  SalesModel::where('id', $request->sales_id)->first();
            if (empty($salesId)) {
                return $this->sendError('Data Not Found');
            }
            $data = [
                'doctor_name' => isset($salesId->getDoctor->name) ? $salesId->getDoctor->name : "",
                'doctor_number' => isset($salesId->getDoctor->phone_number) ? $salesId->getDoctor->phone_number : "",
                'bill_no' => isset($salesId->bill_date) ? $salesId->bill_date : "",
                'payment_name' => isset($salesId->getPayment->payment_method) ? $salesId->getPayment->payment_method : "",
                'order_number' => isset($salesId->order_number) ? '#' . $salesId->order_number : "",
                'date_time' => isset($salesId->created_at) ? $salesId->created_at : "",
                'pation_name' => isset($salesId->getUserName->name) ? $salesId->getUserName->name : "",
                'pation_number' => isset($salesId->getUserName->phone_number) ? $salesId->getUserName->phone_number : "",
                'total_gst' => isset($salesId->total_gst) ? $salesId->total_gst : "",
                'mrp' => isset($salesId->mrp) ? $salesId->mrp : "",
                'round_off' => isset($salesId->round_off) ? $salesId->round_off : "",
                'net_amt' => isset($salesId->net_amt) ? $salesId->net_amt : "",
            ];

            $salesData = salesDetails::where('sales_id', $salesId->id)->get();
            $pdf = PDF::loadView('admin.pdf.sales', compact('data', 'salesData'));

            $directory = public_path('pdf');

            // Create the directory if it doesn't exist
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            $filename = time() . '.pdf';
            $pdf->save(public_path('pdf/' . $filename));
            $salesId->pdf =  $filename;
            $salesId->update();

            $userLogs = new LogsModel;
            $userLogs->message = 'PDF create';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $dataPdf = [];
            $dataPdf['id'] = isset($salesId->id) ? $salesId->id : "";
            $dataPdf['pdf'] = isset($salesId->pdf) ? asset('/public/pdf/' . $salesId->pdf) : "";
            return $this->sendResponse($dataPdf, 'PDF create Successfully');
        } catch (\Exception $e) {
            Log::info("sales pdf list api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use sales details
    public function listView(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sales_id' => 'required',
            ], [
                'sales_id.required' => 'Enter Sales_id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $salesId = SalesModel::where('id', $request->sales_id)->first();

            if (empty($salesId)) {
                return $this->sendError('Sale Bill Data Not Found.');
            }

            $bankName = BankAccount::where('id', $salesId->payment_name)->first();

            $salesDetails = [];

            $salesDetails['id'] = $salesId->id ?? "";
            $salesDetails['draft_save'] = $salesId->draft_save ?? '';
            $salesDetails['today_loylti_point'] = $salesId->today_loylti_point ?? "";
            $salesDetails['last_bill_date'] = $salesId->last_bill_date ?? "";
            $salesDetails['roylti_point'] = $salesId->roylti_point ?? "";
            $salesDetails['round_off'] = $salesId->round_off ?? "0";
            $salesDetails['margin_net_profit'] = $salesId->margin_net_profit ?? "0";
            $salesDetails['total_margin'] = $salesId->margin ?? "0";
            $salesDetails['total_net_rate'] = $salesId->net_rate ?? "0";
            $salesDetails['discount_amount'] = $salesId->total_discount ?? "";
            $salesDetails['customer_name'] = isset($salesId->getUserName->name) ? $salesId->getUserName->name . ' ' . $salesId->getUserName->last_name : '';
            $salesDetails['customer_address'] = $salesId->customer_address ?? '';
            $salesDetails['mobile_numbr'] = $salesId->getUserName->phone_number ?? '';
            $salesDetails['doctor_name'] = isset($salesId->getDoctor->name) ? $salesId->getDoctor->name . ' ' . $salesId->getDoctor->last_name : '';
            $salesDetails['doctor_mobile_numbr'] = $salesId->getDoctor->phone_number ?? '';
            $salesDetails['dicount'] = $salesId->dicount ?? '';
            $salesDetails['other_amount'] = isset($salesId->adjustment) ? (string)round($salesId->adjustment, 2) : '0';
            $salesDetails['owner_name'] = $salesId->owner_name ?? '';
            $salesDetails['bill_date'] = $salesId->bill_date ?? '';
            $salesDetails['customer_id'] = $salesId->customer_id ?? '';
            $salesDetails['doctor_id'] = $salesId->doctor_id ?? '';
            $salesDetails['net_amt'] = isset($salesId->mrp_total) ? (string)$salesId->mrp_total : '';
            $salesDetails['payment_id'] = $salesId->payment_name ?? '';
            $salesDetails['payment_name'] = $bankName->bank_name ?? $salesId->payment_name;
            $salesDetails['bill_no'] = $salesId->bill_no ?? '';
            $salesDetails['pdf'] = isset($salesId->pdf) ? asset('/public/pdf/' . $salesId->pdf) : '';
            $salesDetails['net_amount'] = isset($salesId->mrp_total) ? (string)round($salesId->mrp_total, 2) : '';
            $salesDetails['total_amount'] = $salesId->mrp_total ?? '';
            $salesDetails['total_discount'] = $salesId->dicount ?? '';
            $salesDetails['adjustment'] = $salesId->adjustment ?? '';
            $salesDetails['pickup'] = $salesId->pickup ?? '';
            $salesDetails['total_base'] = $salesId->total_base ?? '';
            $salesDetails['given_amount'] = $salesId->given_amount ?? '';
            $salesDetails['due_amount'] = isset($salesId->due_amount) ? (string)round($salesId->due_amount, 2) : '';
            $salesDetails['sgst'] = $salesId->sgst ?? '';
            $salesDetails['cgst'] = $salesId->cgst ?? '';
            $salesDetails['igst'] = $salesId->igst ?? '';
            $salesDetails['status'] = $salesId->status ?? '';

            $salesDetails['sales_item'] = [];
            $salesTotalAmount = [];
            $iteamGst = [];
            $baseTotal = [];

            if ($salesId->getSales && $salesId->getSales->count() > 0) {
                foreach ($salesId->getSales as $key => $list) {
                    $gstModel = GstModel::where('id', $list->gst)->first();
                    $gstRate = isset($gstModel->rate) ? (float)$gstModel->rate : 0;

                    $salesDetails['sales_item'][$key]['id'] = $list->id ?? "";
                    $salesDetails['sales_item'][$key]['iteam_name'] = $list->getIteam->iteam_name ?? "";
                    $salesDetails['sales_item'][$key]['iteam_id'] = $list->iteam_id ?? "";
                    $salesDetails['sales_item'][$key]['user_id'] = $list->user_id ?? "";
                    $salesDetails['sales_item'][$key]['base'] = $list->base ?? "";
                    $salesDetails['sales_item'][$key]['exp'] = $list->exp ?? "";
                    $salesDetails['sales_item'][$key]['mrp'] = $list->mrp ?? "";
                    $salesDetails['sales_item'][$key]['qty'] = $list->qty ?? "";
                    $salesDetails['sales_item'][$key]['discount'] = $list->discount ?? "";
                    $salesDetails['sales_item'][$key]['gst'] = $list->gst ?? "";
                    $salesDetails['sales_item'][$key]['gst_name'] = $gstModel->name ?? $list->gst;
                    $salesDetails['sales_item'][$key]['net_rate'] = isset($list->amt) ? (string)round($list->amt, 2) : "";
                    $salesDetails['sales_item'][$key]['location'] = $list->location ?? "";
                    $salesDetails['sales_item'][$key]['unit'] = $list->unit ?? "";
                    $salesDetails['sales_item'][$key]['batch'] = $list->batch ?? "";
                    $salesDetails['sales_item'][$key]['order'] = $list->order ?? "";
                    $salesDetails['sales_item'][$key]['random_number'] = $list->random_number ?? "";

                    // Calculate GST amount
                    $baseAmount = (float)$list->base;
                    $gstDatta = ($baseAmount * $gstRate) / 100;

                    array_push($salesTotalAmount, (float)$list->amt);
                    array_push($iteamGst, $gstDatta);
                    array_push($baseTotal, $baseAmount);
                }

                $qtyDataTotal = $salesId->getSales->sum('qty') ?: "0";
                $totalGst = array_sum($iteamGst);

                $salesDetails['total_qty'] = (string)$qtyDataTotal;
                $salesDetails['total_gst'] = (string)round($totalGst, 2);
            } else {
                $salesDetails['total_qty'] = "0";
                $salesDetails['total_gst'] = "0";
            }

            return $this->sendResponse($salesDetails, 'Sales Details Get Successfully.');
        } catch (\Exception $e) {
            Log::info("sales edit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesEditDetails(Request $request)
    {
        try {
            $salesId =  SalesModel::where('id', $request->id)->first();

            if (empty($salesId)) {
                return $this->sendResponse([], 'Data Not Found');
            }
            $salesDetails = [];
            $salesDetails['id'] = isset($salesId->id) ? $salesId->id : "";
            $salesDetails['draft_save'] = isset($salesId->draft_save) ? $salesId->draft_save : '';
            $salesDetails['roylti_point'] = isset($salesId->roylti_point) ? $salesId->roylti_point : "";
            $salesDetails['round_off'] = isset($salesId->round_off) ? $salesId->round_off : "0";
            $salesDetails['discount_amount'] = isset($salesId->total_discount) ? $salesId->total_discount : "0";
            $salesDetails['customer_name'] = isset($salesId->getUserName->name) ? $salesId->getUserName->name : '';
            $salesDetails['customer_id'] = isset($salesId->customer_id) ?  $salesId->customer_id : '';
            $salesDetails['doctor_id'] = (isset($salesId->doctor_id) && $salesId->doctor_id != 'undefined') ?  $salesId->doctor_id : '-';
            $salesDetails['customer_address'] = isset($salesId->customer_address) ? $salesId->customer_address : '';
            $salesDetails['mobile_numbr'] = isset($salesId->getUserName->phone_number) ? $salesId->getUserName->phone_number : '';
            $salesDetails['doctor_name'] = (isset($salesId->getDoctor->name) && $salesId->doctor_id != 'undefined') ? $salesId->getDoctor->name : '-';
            $salesDetails['doctor_mobile_numbr'] = (isset($salesId->getDoctor->phone_number) && $salesId->doctor_id != 'undefined') ? $salesId->getDoctor->phone_number : '-';
            $salesDetails['dicount'] = isset($salesId->dicount) ? $salesId->dicount : '0';
            $salesDetails['other_amount'] = isset($salesId->adjustment) ? (string)round($salesId->adjustment, 2) : '0';

            $salesDetails['owner_name'] = isset($salesId->owner_name) ? $salesId->owner_name : '';
            $salesDetails['net_amt'] = isset($salesId->net_amt) ? (string)$salesId->net_amt : '';
            $salesDetails['bill_no'] = isset($salesId->bill_no) ? $salesId->bill_no : '';
            $salesDetails['bill_date'] = isset($salesId->bill_date) ? $salesId->bill_date : '';
            $salesDetails['net_amount'] = isset($salesId->mrp_total) ? (string)$salesId->mrp_total : '';
            $salesDetails['total_amount'] = isset($salesId->mrp_total) ? $salesId->mrp_total : '0';
            $salesDetails['total_discount'] = isset($salesId->dicount) ? $salesId->dicount : '0';
            $salesDetails['adjustment'] = isset($salesId->adjustment) ? $salesId->adjustment : '';
            $salesDetails['pickup'] = isset($salesId->pickup) ? $salesId->pickup : '';

            // $salesDetails['total_base'] = isset($salesId->total_base) ? $salesId->total_base : '';
            $salesDetails['given_amount'] = isset($salesId->given_amount) && $salesId->given_amount != 'null' ? $salesId->given_amount : '0';

            $salesDetails['due_amount'] = isset($salesId->due_amount) ? (string)round($salesId->due_amount, 2) : '';
            //  $salesDetails['sgst'] = isset($salesId->sgst) ? $salesId->sgst : '';
            //  $salesDetails['cgst'] = isset($salesId->cgst) ? $salesId->cgst : '';
            $salesDetails['igst'] = isset($salesId->igst) ? $salesId->igst : '';
            $salesDetails['status'] = isset($salesId->status) ? $salesId->status : '';

            $salesDetails['sales_item'] = [];
            $netRateNew  = [];
            $baseTotal = [];
            $withoutGst = [];
            $marginData = [];
            $purcheasrate = [];
            $netRates = [];
            $loyaltiPoint = [];
            if (isset($request->random_number)) {
                $salesDataList = SalesIteam::where('random_number', $request->random_number)->orderBy('id', 'DESC')->get();

                $salesDetails['sales_item'] = [];
                $netRate  = [];
                $baseTotal = [];
                $withoutGst = [];
                $iteamGst = [];
                $purcheasrate = [];
                $iteamAmount = [];
                if (isset($salesDataList)) {
                    foreach ($salesDataList as $key => $list) {
                        $gstName  = GstModel::where('id', $list->gst)->first();
                        $iteamName  = IteamsModel::where('id', $list->item_id)->first();

                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = array(auth()->user()->id);
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                        $batchStock = BatchModel::where('batch_name', $list->batch)->where('item_id', $list->item_id)->whereIn('user_id', $allUserId)->first();

                        $purchaesDataDetails = PurchesDetails::where('batch', $list->batch)->where('iteam_id', $list->item_id)->whereIn('user_id', $allUserId)->sum('margin');
                        $purchaesNetRate = PurchesDetails::whereIn('user_id', $allUserId)->where('iteam_id', $list->item_id)->where('batch', $list->batch)->sum('net_rate');

                        $purchaesDataDetailsdiscount = PurchesDetails::where('batch', $list->batch)->where('iteam_id', $list->item_id)->whereIn('user_id', $allUserId)->first();
                        $totalStock = 0;
                        if (isset($batchStock->total_qty, $list->qty)) {
                            $totalStock = (int)$batchStock->total_qty + (int)$list->qty;
                        }

                        $salesDetails['sales_item'][$key]['id'] = isset($list->id) ? $list->id : "";
                        $salesDetails['sales_item'][$key]['ptr'] = isset($list->ptr) ? $list->ptr : "";
                        $salesDetails['sales_item'][$key]['total_stock'] = isset($totalStock) ? (string)$totalStock : "";
                        $salesDetails['sales_item'][$key]['iteam_name'] = isset($iteamName->iteam_name) ? $iteamName->iteam_name : "";
                        $salesDetails['sales_item'][$key]['front_photo'] = isset($iteamName->front_photo) ? asset('/public/front_photo/' . $iteamName->front_photo) : "";
                        $salesDetails['sales_item'][$key]['item_id'] = isset($list->item_id) ? $list->item_id : "";
                        $salesDetails['sales_item'][$key]['user_id'] = isset($list->user_id) ? $list->user_id : "";
                        $salesDetails['sales_item'][$key]['mrp'] = isset($list->mrp) ? (string)round($list->mrp, 2) : "";
                        $salesDetails['sales_item'][$key]['net_rate'] = isset($list->net_rate) ? $list->net_rate : "";
                        $salesDetails['sales_item'][$key]['unit'] = isset($list->unit) ? $list->unit : "";
                        $salesDetails['sales_item'][$key]['batch'] = isset($list->batch) ? $list->batch : "";
                        $salesDetails['sales_item'][$key]['order'] = isset($list->order) ? $list->order : "";
                        $salesDetails['sales_item'][$key]['base'] = isset($list->base) ? $list->base : "";
                        $salesDetails['sales_item'][$key]['location'] = isset($list->location) ? $list->location : "";
                        $salesDetails['sales_item'][$key]['exp'] = isset($list->exp) ? $list->exp : "";
                        $salesDetails['sales_item'][$key]['qty'] = isset($list->qty) ? $list->qty : "";
                        $salesDetails['sales_item'][$key]['discount'] = isset($list->discount) ? $list->discount : "0";
                        $salesDetails['sales_item'][$key]['gst'] = isset($list->gst) ? $list->gst : "";
                        $salesDetails['sales_item'][$key]['gst_name'] = $gstName->name ?? $list->gst ?? "";
                        $salesDetails['sales_item'][$key]['random_number'] = isset($list->random_number) ? $list->random_number : "";

                        $totalAmount = isset($list->net_rate) ? $list->net_rate : 0;
                        $gstRate = isset($list->gst) ? $list->gst : "";
                        $gstTotal = (int)$totalAmount * (int)$gstRate / 100;
                        $gstAmount = (int)$totalAmount - (int)$gstTotal;
                        $totalGst =   (int)$totalAmount - (int)$gstAmount;

                        $resultGst = isset($gstName->name) ? $gstName->name : $list->gst;
                        $gstDatta = ($list->base * $resultGst) / 100;

                        $royaltyPoints = RoyaltyPoint::where('user_id', auth()->user()->id)->get();

                        $totalPercent = 0;
                        foreach ($royaltyPoints as $royalty) {
                            $salesAmount = $totalAmount;  // Assuming 'net_amt' is the sales bill amount

                            // Check if the sales amount is within the RoyaltyPoint range
                            if ($salesAmount >= $royalty->minimum && $salesAmount <= $royalty->maximum) {
                                Log::info("sales amount" . $salesAmount);
                                Log::info("sales minimum" . $royalty->minimum);
                                Log::info("sales maximum" . $royalty->maximum);
                                Log::info("sales percent" . $royalty->percent);
                                $totalPercent += $royalty->percent;
                            }
                        }
                        $totalLoaytiPoint = isset($totalPercent) ? (int)$totalPercent : "";

                        array_push($loyaltiPoint, $totalLoaytiPoint);

                        array_push($iteamGst, $gstDatta);
                        array_push($withoutGst, abs($totalGst));
                        array_push($netRateNew, $list->net_rate);
                        array_push($baseTotal, $list->base);
                        array_push($marginData, $purchaesDataDetails);
                        array_push($netRates, $purchaesNetRate);
                        array_push($iteamAmount, $list->mrp);
                    }
                }
            }

            $randomNumber = SalesIteam::where('random_number', $request->random_number)->get();
            if (isset($randomNumber)) {
                $totalItems = $randomNumber->count();
                $qtyDataTotal = $randomNumber->sum('qty');
                $totalBase = (int)array_sum($baseTotal);
                $gstData = $totalItems > 0 ? array_sum($iteamGst) / $totalItems : 0;
                $totalGst = $totalBase * $gstData / 100;

                $amrginAmount = (int)array_sum($iteamAmount) - (int)array_sum($netRates);
                $marginPrecent = $totalItems > 0 ? array_sum($marginData) / $totalItems : 0;

                $salesDetails['total_qty'] = isset($qtyDataTotal) ? (string)$qtyDataTotal : "0";
                $salesDetails['total_gst'] = (string)round(array_push($iteamGst), 2);
                $salesDetails['margin_net_profit'] = (string)$amrginAmount;
                $salesDetails['total_margin'] = (string)$marginPrecent;
            } else {
                $salesDetails['total_qty'] = "0";
                $salesDetails['total_gst'] = "0";
                $salesDetails['margin_net_profit'] = "0";
                $salesDetails['total_margin'] = "0";
            }

            $salesDetails['sales_amount'] = (string)array_sum($netRateNew);
            $salesDetails['total_base'] = (string)array_sum($baseTotal);
            $salesDetails['total_net_rate'] = (string)array_sum($netRates);

            $salesDataData = SalesModel::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->orderBy('id', 'DESC')->first();

            $salesDetails['today_loylti_point'] = (string)array_sum($loyaltiPoint);
            $salesDetails['last_bill_date'] = date('Y-m-d h:i A', strtotime($salesDataData->created_at));

            $salesSgst = (string)array_sum($withoutGst);
            $salesDetails['sgst'] = round($salesSgst / 2);
            $salesDetails['cgst'] = round($salesSgst / 2);

            return $this->sendResponse($salesDetails, 'Sales Edit Details Get Successfully.');
        } catch (\Exception $e) {
            dD($e);
            Log::info("sales edit Details api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use update sales
    public function SalesUpdate(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            $sales = SalesModel::find($request->id);
            $sales->bill_date = $request->bill_date;
            $sales->net_rate = $request->net_rate;
            $sales->draft_save = $request->draft_save;
            $sales->customer_id = $request->customer_id;
            $sales->total_discount = $request->discount_amount;
            $sales->round_off = $request->round_off;
            $sales->margin = $request->margin;
            $sales->margin_net_profit = $request->margin_net_profit;
            $sales->bill_no = $request->bill_no;
            $sales->customer_address = $request->customer_address;
            $sales->doctor_id = (isset($request->doctor_id) &&  $request->doctor_id != 'undefined') ? $request->doctor_id : "";
            $sales->mrp_total = $request->total_amount;
            $sales->dicount = $request->total_discount;
            $sales->adjustment = isset($request->other_amount) ? (string)round($request->other_amount, 2) : "0";
            $sales->net_amt = $request->net_amount;
            $sales->owner_name = $request->owner_name;
            $sales->pickup = $request->pickup;
            $sales->total_base = $request->total_base;
            $sales->given_amount = $request->given_amount;
            $sales->due_amount = (string)round($request->due_amount, 2);
            $sales->sgst = $request->sgst;
            $sales->cgst = $request->cgst;
            $sales->igst = $request->igst;
            $sales->status = $request->status;
            $sales->today_loylti_point = $request->today_loylti_point;
            $sales->last_bill_date = $request->last_bill_date;
            if (isset($request->roylti_point)) {
                $sales->roylti_point = $request->roylti_point;
            }
            $sales->user_id = $userId;
            $sales->payment_name = $request->payment_name;
            if ($request->payment_name == 'cash') {
                $cashManage = CashManagement::where('user_id', auth()->user()->id)->where('description', 'Sales Manage')->orderBy('id', 'DESC')->first();
                if (isset($cashManage)) {
                    $cashManage->delete();
                }

                $cashManage = CashManagement::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                if (isset($cashManage)) {

                    $previewData = CashManagement::where('user_id', auth()->user()->id)->where('id', $cashManage->id)->where('description', 'Purchase')->orderBy('id', 'DESC')->first();
                    if (isset($previewData)) {
                        $amountData =  $cashManage->opining_balance - $request->net_amount;
                        $amount = abs($amountData);
                    } else {
                        $amountData =  $cashManage->opining_balance + $request->net_amount;
                        $amount = abs($amountData);
                    }
                    $cashAdd = new CashManagement;
                    $cashAdd->date = $request->bill_date;

                    $cashAdd->description = 'Sales Manage';
                    $cashAdd->type = 'credit';
                    $cashAdd->amount = (string)round($request->net_amount, 2);
                    $cashAdd->user_id = auth()->user()->id;
                    $cashAdd->reference_no = $request->bill_no;
                    $cashAdd->voucher     = 'sales';
                    $cashAdd->opining_balance = (string)round($amount, 2);
                    $cashAdd->save();
                } else {

                    $cashAdd = new CashManagement;
                    $cashAdd->date = $request->bill_date;
                    $cashAdd->description = 'Sales Manage';
                    $cashAdd->type = 'credit';
                    $cashAdd->amount = (string)round($request->net_amount, 2);
                    $cashAdd->reference_no = $request->bill_no;
                    $cashAdd->voucher     = 'sales';
                    $cashAdd->user_id = auth()->user()->id;
                    $cashAdd->opining_balance = (string)round($request->net_amount, 2);
                    $cashAdd->save();
                }
            } else {
                $passbookData =  PassBook::where('bank_id', $request->payment_name)->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                if (isset($passbookData)) {
                    $passbookData->delete();
                }
                $passBook =  PassBook::where('bank_id', $request->payment_name)->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                if (isset($passBook)) {
                    $amount =  $passBook->balance + $request->net_amount;
                    $customerName  = CustomerModel::where('id', $request->customer_id)->first();
                    $passbook = new  PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $request->bill_date;
                    $passbook->party_name = $customerName->name;
                    $passbook->bank_id = $request->payment_name;
                    $passbook->deposit = (string)round($request->net_amount, 2);
                    $passbook->withdraw     = "";
                    $passbook->balance = (string)round($amount, 2);
                    $passbook->mode = "";
                    // $passbook->remark = $request->remark;
                    $passbook->save();
                } else {
                    $customerName  = CustomerModel::where('id', $request->customer_id)->first();
                    $passbook = new  PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $request->bill_date;
                    $passbook->party_name = $customerName->name;
                    $passbook->bank_id = $request->payment_name;
                    $passbook->deposit = (string)round($request->net_amount, 2);
                    $passbook->withdraw    = "";
                    $passbook->balance = (string)round($request->net_amount, 2);
                    $passbook->mode = "";
                    $passbook->save();
                }
            }
            $sales->update();

            if (isset($request->product_list)) {
                $salesDetailsData = salesDetails::where('sales_id', $sales->id)->get();
                if (isset($salesDetailsData)) {
                    foreach ($salesDetailsData as $deleteSales) {

                        $deleteSales->delete();
                    }
                }

                $salesData = SalesFinalIteam::where('sales_id', $sales->id)->get();
                if (isset($salesData)) {
                    foreach ($salesData as $listData) {
                        $listData->delete();
                    }
                }

                $onlineOrder = OnlineOrder::where('sales_id', $sales->id)->get();
                if (isset($onlineOrder)) {
                    foreach ($onlineOrder as $listDataOnline) {
                        $listDataOnline->delete();
                    }
                }

                $dataList = json_decode($request->product_list, true);

                if ((isset($dataList)) && ($sales->draft_save != '0')) {
                    foreach ($dataList as $list) {
                        $textbleVlaue = ($list['qty'] ?? 0) * ($list['base'] ?? 0);
                        $userId = auth()->user()->id;
                        $salesDetails = new salesDetails;
                        $salesDetails->sales_id = $sales->id;
                        $salesDetails->taxable_value = $textbleVlaue;
                        $salesDetails->iteam_id = $list['item_id'];
                        $salesDetails->unit = $list['unit'];
                        $salesDetails->discount = isset($list['discount']) ? $list['discount'] : "";
                        $salesDetails->ptr = isset($list['ptr']) ? $list['ptr'] : "";
                        $salesDetails->batch = $list['batch'];
                        $salesDetails->exp = $list['exp'];
                        $salesDetails->base = $list['base'];
                        $salesDetails->mrp = $list['mrp'];
                        $salesDetails->gst = $list['gst'];
                        $salesDetails->qty = $list['qty'];
                        $salesDetails->order = $list['order'];
                        $salesDetails->amt = (string)round($list['net_rate'], 2);
                        $salesDetails->user_id = $userId;
                        $salesDetails->location = $list['location'];
                        $salesDetails->random_number = $list['random_number'];
                        $salesDetails->save();

                        $salesFinalData = new SalesFinalIteam;
                        $salesFinalData->sales_id = $sales->id;
                        $salesFinalData->random_number = $list['random_number'];
                        $salesFinalData->user_id = $userId;
                        $salesFinalData->item_id = $list['item_id'];
                        $salesFinalData->qty = $list['qty'];
                        $salesFinalData->exp = $list['exp'];
                        $salesFinalData->gst = $list['gst'];
                        $salesFinalData->mrp = $list['mrp'];
                        $salesFinalData->amt = $list['net_rate'];
                        $salesFinalData->unit = $list['unit'];
                        $salesFinalData->batch = $list['batch'];
                        $salesFinalData->base = $list['base'];
                        $salesFinalData->order = $list['order'];
                        $salesFinalData->location = $list['location'];
                        $salesFinalData->net_rate = (string)round($list['net_rate'], 2);
                        $salesFinalData->status = '0';
                        $salesFinalData->save();

                        $LeagerDelete = LedgerModel::where('status', '0')->where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->where('transction', 'Sales Invoice')->where('batch', $list['batch'])->first();

                        if (isset($LeagerDelete)) {
                            Log::info("sales update api leger one" . $list['qty']);
                            $LeagerDelete->out = (int)$list['qty'];
                            $LeagerDelete->status = '1';
                            $LeagerDelete->update();
                        } else {
                            $userName = CustomerModel::where('id', $request->customer_id)->first();

                            $leaderData = new LedgerModel;
                            $leaderData->owner_id = $request->customer_id;
                            $leaderData->entry_date = $request->bill_date;
                            $leaderData->transction = 'Sales Invoice';
                            $leaderData->voucher = 'Sales Invoice';
                            $leaderData->bill_no = '#' . $request->bill_no;
                            $leaderData->puches_id = $sales->id;
                            $leaderData->batch = $list['batch'];
                            $leaderData->bill_date = $request->bill_date;
                            $leaderData->name = $userName->name;
                            $leaderData->user_id = auth()->user()->id;
                            $leaderData->iteam_id = $list['item_id'];
                            $ledgers = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                            if (isset($ledgers)) {
                                $balance =  (int)$list['qty'] - $ledgers->balance_stock;
                                Log::info("sales update api leger two" . $balance);
                                $leaderData->out = (int)$list['qty'];
                                $leaderData->status = '1';
                                $leaderData->balance_stock = abs($balance);
                            } else {
                                $leaderData->out = (int)$list['qty'];
                                $leaderData->status = '1';
                                $leaderData->balance_stock =  (int)$list['qty'];
                            }

                            $leaderData->save();
                        }

                        $legaderData  = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id')->get();

                        if (isset($legaderData)) {
                            $prevStock = null;
                            foreach ($legaderData as $ListData) {

                                if ($prevStock !== null) {
                                    if ((isset($prevStock->in)) && (isset($ListData->in))) {
                                        $amount =  $ListData->in - $prevStock->balance_stock;
                                        $ListData->balance_stock = abs($amount);
                                        Log::info("sales leager api one" . $amount);
                                        Log::info("sales leager api in" . $ListData->in);
                                        Log::info("sales leager api stock" . $prevStock->balance_stock);
                                    } else {
                                        $amount = $ListData->out - $prevStock->balance_stock;
                                        $ListData->balance_stock = abs($amount);
                                        Log::info("sales leager api two" . $amount);
                                        Log::info("sales leager api out" .  $ListData->out);
                                        Log::info("sales leager api stock" .  $prevStock->balance_stock);
                                    }
                                } else {
                                    $ListData->balance_stock = $ListData->balance_stock ?? 0;
                                }
                                $ListData->update();
                                $prevStock = $ListData;
                            }
                        }


                        if (($list['order'] == 'O') || ($list['order'] == 'o')) {
                            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                            $userId = array(auth()->user()->id);
                            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                            $totalStock = BatchModel::whereIn('user_id', $allUserId)->where('item_id', $list['item_id'])->sum('total_qty');

                            $iteamName  = IteamsModel::where('id', $list['item_id'])->first();
                            $companyData = CompanyModel::where('id', $iteamName->pharma_shop)->first();

                            $purrchesData = PurchesDetails::whereIn('user_id', $allUserId)->where('iteam_id', $list['item_id'])->orderBy('id', 'DESC')->first();
                            if (isset($purrchesData->getpurchesData->distributor_id)) {
                                $distributorName =  Distributer::where('id', $purrchesData->getpurchesData->distributor_id)->first();
                            } else {
                                $distributorName = null;
                            }

                            $orderData = new OnlineOrder;
                            $orderData->sales_id = $sales->id;
                            $orderData->user_id = auth()->user()->id;
                            $orderData->item_id = $list['item_id'];
                            $orderData->y_n = '1';
                            $orderData->stock = isset($totalStock) ? $totalStock : "";
                            $orderData->company_name = isset($companyData->company_name) ? $companyData->company_name : "";
                            $orderData->supplier_name = isset($distributorName->name) ? $distributorName->name : "";
                            $orderData->item_name = isset($iteamName->iteam_name) ? $iteamName->iteam_name : "";
                            $orderData->save();
                        }

                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = array(auth()->user()->id);
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                        $batchData = BatchModel::where('batch_number', $list['batch'])->whereIn('user_id', $allUserId)->first();

                        if ($batchData->sales_qty != $list['qty']) {
                            $finalSalesData = FinalPurchesItem::where('batch', $list['batch'])->where('iteam_id', $list['item_id'])->whereIn('user_id', $allUserId)->first();
                            if (isset($finalSalesData)) {

                                $purchaseQtyOld = PurchesDetails::where('batch', $list['batch'])->whereIn('user_id', $allUserId)->where('iteam_id', $list['item_id'])->sum('qty');

                                $freeQtyOld = PurchesDetails::where('batch', $list['batch'])->whereIn('user_id', $allUserId)->where('iteam_id', $list['item_id'])->sum('fr_qty');

                                $returnPurchaseQty = PurchesReturnDetails::where('batch', $list['batch'])->whereIn('user_id', $allUserId)->where('iteam_id', $list['item_id'])->sum('qty');

                                $returnFreeQty = PurchesReturnDetails::where('batch', $list['batch'])->whereIn('user_id', $allUserId)->where('iteam_id', $list['item_id'])->sum('fr_qty');

                                $freeQtyOld -= $returnFreeQty;
                                $purchaseQtyOld -= ($returnPurchaseQty - $freeQtyOld);
                                $freeQtyOld = 0;

                                $salesQty = salesDetails::where('batch', $list['batch'])->where('iteam_id', $list['item_id'])->whereIn('user_id', $allUserId)->sum('qty');
                                $purchase_qty = (int)$purchaseQtyOld;
                                $free_qty = (int)$freeQtyOld;
                                $sales_qty = (int)$salesQty / (int)$batchData->unit;
                                Log::info("sales update api qty old" . $purchase_qty);
                                Log::info("sales update api free old" . $free_qty);
                                Log::info("sales update api sales old" . $sales_qty);

                                // First, deduct from the free_qty
                                if ($sales_qty <= $free_qty) {
                                    $free_qty -= $sales_qty;
                                } else {
                                    $sales_qty -= $free_qty;
                                    $free_qty = 0;
                                    $purchase_qty -= $sales_qty;
                                }
                                $finalSalesData->qty = abs($purchase_qty);
                                $finalSalesData->fr_qty = abs($free_qty);
                                $finalSalesData->update();

                                Log::info("sales update api qty new" . $purchase_qty);
                                Log::info("sales update api free new" . $free_qty);
                            }
                        }
                        if (isset($batchData)) {
                            $salesQty = salesDetails::where('batch', $list['batch'])->where('iteam_id', $list['item_id'])->whereIn('user_id', $allUserId)->sum('qty');
                            $finalSalesData = FinalPurchesItem::where('batch', $list['batch'])->where('iteam_id', $list['item_id'])->whereIn('user_id', $allUserId)->first();
                            $batchData->item_id = $list['item_id'];
                            $batchData->unit = $list['unit'];
                            $batchData->qty = $list['qty'];
                            $batchData->purches_qty = abs($finalSalesData->qty);
                            $batchData->purches_free_qty = abs($finalSalesData->fr_qty);
                            $batchData->gst = isset($list['gst']) ? $list['gst'] : "";
                            $batchData->expiry_date = $list['exp'];
                            $batchData->mrp = $list['mrp'];

                            $batchData->location = $list['location'];
                            $batchData->sales_qty =  $salesQty;
                            $ledgers = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                            $batchData->total_qty = isset($ledgers->balance_stock) ? $ledgers->balance_stock : "";
                            //$batchData->total_qty = ((int)abs($finalSalesData->qty) + (int)abs($finalSalesData->fr_qty)) * $list['unit'];
                            $batchData->total_mrp = $list['mrp'] * $list['qty'];
                            $batchData->total_ptr = $list['base'] * $list['qty'];
                            $batchData->update();
                            Log::info("sales update api total qtys" . $batchData->total_qty);
                        }
                    }
                } else {
                    if (isset($dataList)) {
                        foreach ($dataList as $list) {
                            $textbleVlaue = ($list['qty'] ?? 0) * ($list['base'] ?? 0);
                            $userId = auth()->user()->id;
                            $salesDetails = new salesDetails;
                            $salesDetails->sales_id = $sales->id;
                            $salesDetails->taxable_value = $textbleVlaue;
                            $salesDetails->iteam_id = $list['item_id'];
                            $salesDetails->unit = $list['unit'];
                            $salesDetails->discount = isset($list['discount']) ? $list['discount'] : "";
                            $salesDetails->ptr = isset($list['ptr']) ? $list['ptr'] : "";
                            $salesDetails->batch = $list['batch'];
                            $salesDetails->exp = $list['exp'];
                            $salesDetails->base = $list['base'];
                            $salesDetails->mrp = $list['mrp'];
                            $salesDetails->gst = $list['gst'];
                            $salesDetails->qty = $list['qty'];
                            $salesDetails->order = $list['order'];
                            $salesDetails->amt = (string)round($list['net_rate'], 2);
                            $salesDetails->user_id = $userId;
                            $salesDetails->location = $list['location'];
                            $salesDetails->random_number = $list['random_number'];
                            $salesDetails->save();

                            $salesFinalData = new SalesFinalIteam;
                            $salesFinalData->sales_id = $sales->id;
                            $salesFinalData->random_number = $list['random_number'];
                            $salesFinalData->user_id = $userId;
                            $salesFinalData->item_id = $list['item_id'];
                            $salesFinalData->qty = $list['qty'];
                            $salesFinalData->exp = $list['exp'];
                            $salesFinalData->gst = $list['gst'];
                            $salesFinalData->mrp = $list['mrp'];
                            $salesFinalData->amt = $list['net_rate'];
                            $salesFinalData->unit = $list['unit'];
                            $salesFinalData->batch = $list['batch'];
                            $salesFinalData->base = $list['base'];
                            $salesFinalData->order = $list['order'];
                            $salesFinalData->location = $list['location'];
                            $salesFinalData->net_rate = (string)round($list['net_rate'], 2);
                            $salesFinalData->status = '0';
                            $salesFinalData->save();
                        }
                    }
                }
            }

            $dataList = json_decode($request->product_list, true);
            if (isset($dataList)) {
                foreach ($dataList as $list) {
                    $legaderData  = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id')->get();
                    if (isset($legaderData)) {
                        foreach ($legaderData as $list) {
                            $list->status = '0';
                            $list->update();
                        }
                    }
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Sales Bill Updated';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $datasSales  = [];
            $datasSales['id'] = isset($sales->id) ? $sales->id : '';

            return $this->sendResponse($datasSales, 'Sales Updated Successfully.');
        } catch (\Exception $e) {
            Log::info("sales update api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use sales list
    public function salesIteam(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required',
            ], [
                'customer_id.required' => 'Enter customer id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $salesData = SalesModel::where('customer_id', $request->customer_id)->orderBy('id', 'DESC');
            if (isset($request->bill_no)) {
                $salesData->where('bill_no', $request->bill_no);
            }
            if (isset($request->start_date) && (isset($request->end_date))) {

                $start_date = date('Y-m-d', strtotime($request->start_date));
                $end_date = date('Y-m-d', strtotime($request->end_date));
                $salesData->whereBetween('created_at', [$start_date, $end_date]);
            }
            $salesData = $salesData->orderBy('id', 'DESC')->get();

            $salesDetails = [];
            if (isset($salesData)) {

                foreach ($salesData as $keyList => $listData) {
                    $salesDetails[$keyList]['id'] = isset($listData->id) ? $listData->id : "";
                    $salesDetails[$keyList]['bill_no'] = isset($listData->bill_no) ? $listData->bill_no : "";
                    $salesDetails[$keyList]['bill_date'] = isset($listData->bill_date) ? $listData->bill_date : "";
                    $salesDetails[$keyList]['status'] = isset($listData->status) ? $listData->status : "";
                    $salesDetails[$keyList]['bill_amount'] = isset($listData->bill_amount) ? (string)round($listData->bill_amount, 2) : "";
                }
            }
            return $this->sendResponse($salesDetails, 'Sales List Successfully.');
        } catch (\Exception $e) {
            Log::info("sales Iteam api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use product iteam list
    public function salesIteamList(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'sales_id' => 'required',
            ], [
                'sales_id.required' => 'Enter Sales id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $salesData = SalesModel::where('id', $request->sales_id)->orderBy('id', 'DESC')->orderBy('id', 'DESC')->first();

            $salesDetails = [];
            if (isset($salesData)) {
                $salesDetails['id'] = isset($salesData->id) ? $salesData->id : "";
                $salesDetails['order_number'] = isset($salesData->order_number) ? $salesData->order_number : "";
                $salesDetails['customer_name'] = isset($salesData->getUserName) ? $salesData->getUserName->name : "";
                $salesDetails['mobile_number'] = isset($salesData->getUserName->phone_number) ? $salesData->getUserName->phone_number : "";
                $salesDetails['email'] = isset($salesData->getUserName->email) ? $salesData->getUserName->email : "";
                $salesDetails['address'] = isset($salesData->getUserName->address) ? $salesData->getUserName->address : "";
                $salesDetails['bill_no'] = isset($salesData->bill_no) ? $salesData->bill_no : "";
                $salesDetails['bill_date'] = isset($salesData->bill_date) ? $salesData->bill_date : "";
                $salesDetails['doctor_name'] = isset($salesData->getDoctor) ? $salesData->getDoctor->name : "";
                $salesDetails['total_gst'] = isset($salesData->total_gst) ? $salesData->total_gst : "";
                $salesDetails['margin'] = isset($salesData->margin) ? $salesData->margin : "";
                $salesDetails['dicount'] = isset($salesData->dicount) ? $salesData->dicount : "";
                $salesDetails['adjustment'] = isset($salesData->adjustment) ? $salesData->adjustment : "";
                $salesDetails['round_off'] = isset($salesData->round_off) ? $salesData->round_off : "";
                $salesDetails['order_number'] = isset($salesData->order_number) ? $salesData->order_number : "";
                $salesDetails['mrp_total'] = isset($salesData->mrp_total) ? (string)round($salesData->mrp_total, 2) : "";
                $salesDetails['cess'] = isset($salesData->cess) ? $salesData->cess : "";
                $salesDetails['customer_address'] = isset($salesData->customer_address) ? $salesData->customer_address : "";


                $salesDetails['sales_details'] = [];
                if (isset($salesData->getSales)) {
                    foreach ($salesData->getSales as $key => $list) {
                        $gstName  = GstModel::where('id', $list->gst)->first();

                        $salesDetails['sales_details'][$key]['id'] = isset($list->id) ? $list->id : "";
                        $salesDetails['sales_details'][$key]['iteam_name'] = isset($list->getIteam) ? $list->getIteam->iteam_name : "";
                        $salesDetails['sales_details'][$key]['front_photo'] = isset($list->getIteam) ? asset('/public/front_photo/' . $list->getIteam->front_photo) : "";
                        $salesDetails['sales_details'][$key]['base'] = isset($list->base) ? $list->base : "";
                        $salesDetails['sales_details'][$key]['exp'] = isset($list->exp) ? $list->exp : "";
                        $salesDetails['sales_details'][$key]['mrp'] = isset($list->mrp) ? $list->mrp : "";
                        $salesDetails['sales_details'][$key]['qty'] = isset($list->qty) ? $list->qty : "";
                        $salesDetails['sales_details'][$key]['discount'] = isset($list->discount) ? $list->discount : "";
                        $salesDetails['sales_details'][$key]['gst'] = isset($list->gst) ? $list->gst : "";
                        $salesDetails['sales_details'][$key]['gst_name'] = isset($gstName->name) ? $gstName->name : "";
                        $salesDetails['sales_details'][$key]['amt'] = isset($list->amt) ? (string)round($list->amt, 2) : "";
                        $salesDetails['sales_details'][$key]['ptr'] = isset($list->ptr) ? $list->ptr : "";
                        $salesDetails['sales_details'][$key]['discount'] = isset($list->discount) ? $list->discount : "";
                    }
                }
            }

            return $this->sendResponse($salesDetails, 'Sales List Successfully.');
        } catch (\Exception $e) {
            Log::info("sales Iteam list api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use customer find
    public function customerDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'customer_id' => 'required',
            ], [
                'customer_id.required' => 'Enter customer id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $salesData = CustomerModel::where('id', $request->customer_id)->first();
            $salesDetails = [];
            if (isset($salesData)) {
                $salesDetails['id'] = isset($salesData->id) ? $salesData->id : "";
                $salesDetails['customer_name'] = isset($salesData) ? $salesData->name : "";
                $salesDetails['email'] = isset($salesData) ? $salesData->email : "";
                $salesDetails['phone_number'] = isset($salesData) ? $salesData->phone_number : "";
                $salesDetails['address'] = isset($salesData) ? $salesData->address : "";
            }
            return $this->sendResponse($salesDetails, 'Sales List Successfully.');
        } catch (\Exception $e) {
            Log::info("sales Iteam api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesItemDelete(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Enter customer id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $salesIteam = SalesIteam::where('id', $request->id)->first();
            if (isset($salesIteam)) {

                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $batchData = BatchModel::whereIn('user_id', $allUserId)->where('batch_number', $salesIteam->batch)->where('item_id', $salesIteam->item_id)->where('mrp', $salesIteam->mrp)->where('ptr', $salesIteam->ptr)
                    ->where('discount', $salesIteam->discount)->first();

                if (isset($batchData)) {
                    $totalStock = (int)$batchData->purches_qty + (int)$batchData->purches_free_qty;
                    $batchData->total_qty = $totalStock * $batchData->unit;
                    $batchData->update();
                }

                // $legaderData  = LedgerModel::where('iteam_id',$salesIteam->item_id)->where('batch',$salesIteam->batch)->where('user_id',auth()->user()->id)->where('transction','Sales Invoice')->orderBy('id')->first();
                //if(isset($legaderData))
                // {
                //   $legaderData->delete();
                //}

                // $legaderDetails  = LedgerModel::where('iteam_id',$salesIteam->item_id)->where('user_id',auth()->user()->id)->orderBy('id')->get();

                // if(isset($legaderDetails))
                //{
                //  $prevStock = null;
                // foreach($legaderDetails as $ListData)
                // {
                //         if ($prevStock !== null) {
                //           if($prevStock->in)
                //         {
                //          $amount =  $ListData->in - $prevStock->balance_stock;
                //         $ListData->balance_stock = abs($amount);
                //     }else{
                //     $amount = $ListData->out - $prevStock->balance_stock;
                //   $ListData->balance_stock = abs($amount); 
                // }
                // } else {
                //   $ListData->balance_stock = $ListData->out ?? 0;
                //}
                //$ListData->update();
                //$prevStock = $ListData;
                //}
                //}


                $salesIteam->delete();
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Sales Bill Delete';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            return $this->sendResponse([], 'Sales Delete Successfully.');
        } catch (\Exception $e) {
            Log::info("sales Iteam api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesItemList(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'random_number' => 'required',
            ], [
                'random_number.required' => 'Enter random number',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->getMessageBag()->first());
            }

            $randomNumberItems = SalesIteam::where('random_number', $request->random_number)->get();

            $salesDetails = [];
            $netRate = [];
            $baseTotal = [];
            $iteamGst = [];
            $withoutGst = [];
            $totalMargin = [];
            $totalRate = [];
            $totalMRP = [];
            $loyaltiPoint = [];
            $pointAmount = [];

            foreach ($randomNumberItems as $key => $list) {
                $staffIds = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerIds = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userIds = array_merge([$request->user()->id], $staffIds, $ownerIds);

                $batchStock = BatchModel::where('batch_name', $list->batch)
                    ->where('item_id', $list->item_id)
                    ->whereIn('user_id', $userIds)
                    ->first();

                $item = IteamsModel::find($list->item_id);
                $gst = GstModel::find($list->gst);

                $marginSum = PurchesDetails::where('batch', $list->batch)
                    ->where('iteam_id', $list->item_id)
                    ->whereIn('user_id', $userIds)
                    ->sum('margin');

                $netRateSum = PurchesDetails::where('batch', $list->batch)
                    ->where('iteam_id', $list->item_id)
                    ->whereIn('user_id', $userIds)
                    ->sum('net_rate');

                $salesDetails[$key] = [
                    'id' => $list->id ?? '',
                    'total_stock' => $batchStock->total_qty ?? '',
                    'item_id' => $list->item_id ?? '',
                    'iteam_name' => $item->iteam_name ?? '',
                    'user_id' => $list->user_id ?? '',
                    'front_photo' => isset($item->front_photo) ? asset('/public/front_photo/' . $item->front_photo) : '',
                    'qty' => $list->qty ?? '',
                    'exp' => $list->exp ?? '',
                    'mrp' => $list->mrp ?? '',
                    'random_number' => $list->random_number ?? '',
                    'gst' => $list->gst ?? '',
                    'gst_name' => $gst->name ?? $list->gst,
                    'net_rate' => isset($list->net_rate) ? (string)round($list->net_rate, 2) : '',
                    'unit' => $list->unit ?? '',
                    'batch' => $list->batch ?? '',
                    'base' => $list->base ?? '',
                    'order' => $list->order ?? '',
                    'location' => $list->location ?? '',
                    'ptr' => $list->ptr ?? '',
                    'discount' => $list->discount ?? '',
                ];

                $totalAmount = $list->net_rate ?? 0;
                $gstRate = $list->gst ?? ($gst->name ?? 0);
                $gstTotal = (int)$totalAmount * (int)$gstRate / 100;
                $gstAmount = (int)$totalAmount - (int)$gstTotal;
                $totalGst = (int)$totalAmount - (int)$gstAmount;
                $resultGst = $gst->name ?? $list->gst;

                array_push($pointAmount, $totalAmount);

                // Loyalty points calculation
                array_push($iteamGst, $resultGst);
                array_push($withoutGst, abs($totalGst));
                array_push($netRate, $list->net_rate);
                array_push($baseTotal, $list->base);
                array_push($totalMargin, $marginSum);
                array_push($totalRate, $netRateSum);
                array_push($totalMRP, $list->mrp);
            }

            $salesAmount = array_sum($pointAmount); // Make sure this is a numeric value

            $royaltyPoints = RoyaltyPoint::where('user_id', auth()->id())->get();
            $loyaltyPointsEarned = 0;
            $matched = false;

            foreach ($royaltyPoints as $royalty) {
                if ($salesAmount >= $royalty->minimum && $salesAmount <= $royalty->maximum) {
                    $loyaltyPointsEarned = ($salesAmount * $royalty->percent) / 100;
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                $loyaltyPointsEarned = 0;
            }
            // array_push($loyaltiPoint, $loyaltyPointsEarned);

            $qtyDataTotal = $randomNumberItems->sum('qty');
            $totalItems = $randomNumberItems->count();
            $totalBase = (int)array_sum($baseTotal);
            $gstData = $totalItems > 0 ? array_sum($iteamGst) / $totalItems : 0;
            $totalGst = $totalBase * $gstData / 100;

            $totalMarginAvg = $totalItems > 0 ? array_sum($totalMargin) / $totalItems : 0;
            $marginAmount = (int)array_sum($totalMRP) - (int)array_sum($totalRate);

            $salesDataData = SalesModel::where('user_id', auth()->user()->id)->orderByDesc('id')->first();

            $salesSgst = (string)array_sum($withoutGst);

            $dataList = [
                'total_qty' => (string)$qtyDataTotal,
                'total_gst' => (string)round($totalGst, 0),
                'total_margin' => (string)$totalMarginAvg,
                'margin_net_profit' => (string)$marginAmount,
                'sales_item' => $salesDetails,
                'today_loylti_point' => (string)round($loyaltyPointsEarned, 2),
                'sales_amount' => (string)array_sum($netRate),
                'total_base' => (string)array_sum($baseTotal),
                'total_net_rate' => (string)array_sum($totalRate),
                'last_bill_date' => isset($salesDataData) ? date('Y-m-d h:i A', strtotime($salesDataData->created_at)) : '',
                'sgst' => round($salesSgst / 2),
                'cgst' => round($salesSgst / 2),
            ];

            return $this->sendResponse($dataList, 'Sales Item List Successfully.');
        } catch (\Exception $e) {
            Log::error("sales Iteam list api: " . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function onlineSalesOrder(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $salesDetails = OnlineOrder::whereIn('user_id', $allUserId);
            if (isset($request->distributor_id)) {
                $dtsributorData = str_replace('+', ' ', $request->distributor_id);
                $salesDetails->where('supplier_name', 'like', '%' . $dtsributorData . '%');
            }
            if (isset($request->company_id)) {
                $salesDetails->where('company_name', 'like', '%' . $request->company_id . '%');
            }
          	if (isset($request->item_name)) {
                $salesDetails->where('item_name', 'like', '%' . $request->item_name . '%');
            }
          	if($request->filled('status'))
            {
            	if($request->status == 1)
                {
                	$salesDetails->where('y_n', 1);
                }else
                {
                	$salesDetails->where('y_n', 2);
                }
            }
          	//         if ($request->filled('status')) {
            //     $status = strtolower($request->status); // lowercase just in case

            //     // Status for Pending
            //     $pendingStatus = ['p', 'pe', 'pen', 'pend', 'pendi', 'pendin', 'pending', 'en', 'nd', 'di', 'in', 'ng', 'e', 'n', 'd', 'i', 'n', 'g', 'end', 'ndi', 'din', 'ing', 'endi', 'ndin', 'ding', 'pendi', 'endin', 'nding', 
            //                       'pendin', 'ending'];

            //     // Status for Order
            //     $orderStatus = ['o', 'r', 'd', 'or', 'ord', 'orde', 'order', 'rd', 'de', 'er', 'e', 'rde', 'der', 'rder'];

            //     if (in_array($status, $pendingStatus)) {
            //         $salesDetails->where('y_n', 1);
            //     } elseif (in_array($status, $orderStatus)) {
            //         $salesDetails->where('y_n', 2);
            //     } else {
            //         $salesDetails->where('y_n', 3);
            //     }
            // }
          	if (isset($request->stock)) {
                $salesDetails->where('stock', 'like', '%' . $request->stock . '%');
            }
            if (isset($request->iss_value)) {
                $salesDetails = $salesDetails->orderBy('id', 'DESC')->get();
            } else {
                $limit = 10;
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
                $salesDetails->offset($offset)->limit($limit);
            	$salesDetails = $salesDetails->orderBy('id', 'DESC')->get();
            }
          
          	$saleOrderTotalCount = OnlineOrder::where('user_id',auth()->user()->id)->count();

            $salesDataDetails = [];
            if (isset($salesDetails)) {
                foreach ($salesDetails as $key => $list) {
                    $orderStatus = '';
                    if ($list->y_n == '1') {
                        $orderStatus = 'Pending';
                    } else {
                        $orderStatus = 'Order';
                    }

                    $totalStock = BatchModel::where('item_id', $list->item_id)->whereIn('user_id', $allUserId)->sum('total_qty');
                    $salesDataDetails[$key]['id'] = isset($list->id) ? $list->id : "";
                    $salesDataDetails[$key]['item_id'] = isset($list->item_id) ? $list->item_id     : "";
                    $salesDataDetails[$key]['company_name'] = isset($list->company_name) ? $list->company_name : "";
                    $salesDataDetails[$key]['iteam_name'] = isset($list->item_name) ? $list->item_name : "";
                    $salesDataDetails[$key]['supplier_name'] = isset($list->supplier_name) ? $list->supplier_name : "";
                    $salesDataDetails[$key]['stock'] = isset($totalStock) ? (string)$totalStock : "";
                    $salesDataDetails[$key]['y_n'] = $orderStatus;
                }
            }
          
          	$response = [
              'status' => 200,
              'count' => !empty($request->page) ? $salesDetails->count() : $saleOrderTotalCount,
              'total_records' => $saleOrderTotalCount,
              'data'   => $salesDataDetails,
              'message' => 'Sales Order List Fetch Successfully.',
            ];
            return response()->json($response, 200);

            // return $this->sendResponse($salesDataDetails, 'Sales Order List Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("sales Iteam list api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function onlineOrderItem(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required',
            ], [
                'item_id.required' => 'Enter Item Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $purrchesData = PurchesDetails::where('user_id', auth()->user()->id)->where('iteam_id', $request->item_id)->orderBy('id', 'DESC')->take(5)->get();

            $purchesRecords = [];
            if (isset($purrchesData)) {
                foreach ($purrchesData as $key => $list) {
                    $distributoData = Distributer::where('id', $list->getpurches->distributor_id)->first();
                    $purchesRecords[$key]['id'] = isset($list->id) ? $list->id : "";
                    $purchesRecords[$key]['supplier_name'] = isset($distributoData->name) ? $distributoData->name : "";
                    $purchesRecords[$key]['qty'] = isset($list->qty) ? $list->qty : "";
                    $purchesRecords[$key]['fr_qty'] = isset($list->fr_qty) ? $list->fr_qty : "";
                    $purchesRecords[$key]['scheme_account'] = isset($list->scheme_account) ? $list->scheme_account : "";
                    $purchesRecords[$key]['ptr'] = isset($list->ptr) ? $list->ptr : "";
                    $purchesRecords[$key]['mrp'] = isset($list->mrp) ? $list->mrp : "";
                    $purchesRecords[$key]['margin'] = isset($list->margin) ? $list->margin : "";
                    $purchesRecords[$key]['bill_date'] = isset($list->getpurches->bill_date) ? $list->getpurches->bill_date : "";
                    $purchesRecords[$key]['bill_no'] = isset($list->getpurches->bill_no) ? $list->getpurches->bill_no : "";
                }
            }
            return $this->sendResponse($purchesRecords, 'Purchase Order Get Successfully.');
        } catch (\Exception $e) {
            Log::info("online order item api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesPdfDownloads(Request $request)
    {
        try {
            $html_url = route('generate.pdf.sales', $request->id);

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
            Log::info("Purchase Return update api" . $e->getMessage());
            return $e->getMessage();
        }
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

    public function getGenrateSalesPdf($id)
    {
        $salesId = SalesModel::where('id', $id)->first();
        if (empty($salesId)) {
            return $this->sendError('Data Not Found.');
        }
        $userIdData = User::where('id', $salesId->user_id)->first();
      	// dD($salesId->getSales);

        if ($salesId->getSales && $salesId->getSales->count() > 0) {
            // Calculating totals after processing all items
            $totalItems = $salesId->getSales->count();
            $qtyDataTotal = $salesId->getSales->sum('qty');
            $totalBase = $salesId->getSales->sum('base');
            $iteamGst = $salesId->getSales->sum('gst');

            $gstData = $totalItems > 0 ? $iteamGst / $totalItems : 0;
            $totalGst = $totalBase * $gstData / 100;
            $total_qty = (string)$qtyDataTotal;
            $total_gst = (string)round($totalGst, 0);
        } else {
            $total_qty = '0';
            $total_gst = '0';
        }
        $qtyDataTotal = $salesId->getSales->sum('qty');
        $totalItems = $salesId->getSales->count();
        $bankName  = BankAccount::where('id', $salesId->payment_name)->first();
      	$licenseData = LicenseModel::where('user_id',$salesId->user_id)->first();
        $salesDetails = [
            'id' => isset($salesId->id) ? $salesId->id : "",
          	'license_20' => isset($licenseData->license_no) ? $licenseData->license_no : "",
          	'license_21' => isset($licenseData->license_no_two) ? $licenseData->license_no_two : "",
          	'fssai_no' => isset($licenseData->license_no_three) ? $licenseData->license_no_three : "",
            'address' => $userIdData->address ?? "",
            'logo' => isset($userIdData->pharmacy_logo) ? asset('/pharmacy_logo/' . $userIdData->pharmacy_logo) : '',
            'total_qty' =>  $qtyDataTotal ?? "",
            'total_iteam' =>  $totalItems ?? "",
            'phone_number' => $userIdData->phone_number ?? "",
            'gst_pan' => $userIdData->gst_pan ?? "",
            'pan_card' => $userIdData->pan_card ?? "",
            'user_name' => $userIdData->name ?? "",
            'total_gst' => (string)round($total_gst, 0),
          	'loyalti_point' => isset($salesId->today_loylti_point) ? $salesId->today_loylti_point : "",
            'round_off' => isset($salesId->round_off) ? $salesId->round_off : "",
            'total_margin' => isset($salesId->margin) ? $salesId->margin : "",
            'total_net_rate' => isset($salesId->net_rate) ? $salesId->net_rate : "",
            'discount_amount' => isset($salesId->total_discount) ? number_format($salesId->total_discount, 0, '', ',') : "",
            'customer_name' => isset($salesId->getUserName->name) ? $salesId->getUserName->name . ' ' . $salesId->getUserName->last_name : '',
            'customer_address' => isset($salesId->getUserName->address) ? $salesId->getUserName->address : '',
            'mobile_numbr' => isset($salesId->getUserName->phone_number) ? $salesId->getUserName->phone_number : '',
            'doctor_name' => isset($salesId->getDoctor->name) ? $salesId->getDoctor->name . ' ' . $salesId->getDoctor->last_name : '',
            'doctor_mobile_numbr' => isset($salesId->getDoctor->phone_number) ? $salesId->getDoctor->phone_number : '',
            'dicount' => isset($salesId->dicount) ? $salesId->dicount : '',
            'other_amount' => isset($salesId->adjustment) ? (string)round($salesId->adjustment, 2) : '0',
            'owner_name' => isset($salesId->owner_name) ? $salesId->owner_name : '',
            'bill_date' => isset($salesId->created_at) ? $salesId->created_at : '',
            'customer_id' => isset($salesId->customer_id) ? $salesId->customer_id : '',
            'doctor_id' => isset($salesId->doctor_id) ? $salesId->doctor_id : '',
            'payment_id' => isset($salesId->payment_name) ? $salesId->payment_name : '',
            'payment_name' => isset($bankName->bank_name) ? $bankName->bank_name : $salesId->payment_name,
            'bill_no' => isset($salesId->bill_no) ? $salesId->bill_no : '',
            'pdf' => isset($salesId->pdf) ? asset('/public/pdf/' . $salesId->pdf) : '',
            // 'net_amount' => isset($salesId->net_amt) ? (string) 'Rs. ' . number_format($salesId->net_amt, 0, '', ',') . '' : '', 
          	'net_amount' => isset($salesId->mrp_total) ? (string) 'Rs. ' . number_format($salesId->mrp_total, 0, '', ',') . '' : '',
            'total_amount' => isset($salesId->mrp_total) ? number_format($salesId->mrp_total, 0, '', ',') : '',
            'total_discount' => isset($salesId->dicount) ? $salesId->dicount : '',
            'adjustment' => isset($salesId->adjustment) ? $salesId->adjustment : '',
            'pickup' => isset($salesId->pickup) ? $salesId->pickup : '',
            'total_base' => isset($salesId->total_base) ? $salesId->total_base : '',
            'given_amount' => isset($salesId->given_amount) ? $salesId->given_amount : '',
            'due_amount' => isset($salesId->due_amount) ? (string)round($salesId->due_amount, 2) : '',
            'sgst' => isset($salesId->sgst) ? $salesId->sgst : '',
            'cgst' => isset($salesId->cgst) ? $salesId->cgst : '',
            'igst' => isset($salesId->igst) ? $salesId->igst : '',
            'status' => isset($salesId->status) ? $salesId->status : '',
            'sales_item' => [],
        ];

        $netRate = [];
        $baseTotal = [];
        $withoutGst = [];

        if ($salesId->getSales && $salesId->getSales->count() > 0) {
            foreach ($salesId->getSales as $key => $list) {
                $gstModel = GstModel::where('id', $list->gst)->first();
                $iteamName  = IteamsModel::where('id', $list->iteam_id)->first();

                $salesDetails['sales_item'][$key] = [
                    'id' => $list->id ?? "",
                    'item_name' => $iteamName->iteam_name ?? "",
                    'front_photo' => isset($iteamName->front_photo) ? asset('/public/front_photo/' . $iteamName->front_photo) : "",
                    'item_id' => $list->iteam_id ?? "",
                    'user_id' => $list->user_id ?? "",
                    'mrp' => isset($list->mrp) ? (string)round($list->mrp, 2) : "",
                    'net_rate' => number_format($list->amt, 0, '', ',') ?? "",
                    'unit' => $list->unit ?? "",
                    'batch' => $list->batch ?? "",
                    'order' => $list->order ?? "",
                    'base' => $list->base ?? "",
                    'location' => $list->location ?? "",
                    'exp' => $list->exp ?? "",
                    'qty' => $list->qty ?? "",
                    'gst' => $list->gst ?? "",
                    'gst_name' => $gstName->name ?? "",
                    'random_number' => $list->random_number ?? "",
                ];
            }
        }

        return view('pdf_template_sales', compact('salesDetails')); // Render the view into HTML
    }

    public function staffList(Request $request)
    {
        try {
            $staffGetData = User::where('create_by', auth()->user()->id)->get();
            //   array_unshift($staffGetData, "all", "owner");

            $allList = [];

            if ($staffGetData->isNotEmpty()) {
                // Adding "All" option
                $allList[] = [
                    'id' => 'All',
                    'name' => 'All',
                ];

                // Adding "Owner" option
                $allList[] = [
                    'id' => 'owner',
                    'name' => 'owner',
                ];

                // Adding staff data to the list
                foreach ($staffGetData as $list) {
                    $allList[] = [
                        'id' => $list->id ?? '',
                        'name' => $list->name ?? '',
                    ];
                }
            }

            // Return response
            return $this->sendResponse($allList, 'Data Fetched Successfully.');
        } catch (\Exception $e) {
            Log::info("Staff List update api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function onlineBulkOrder(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'item_id' => 'required',
            ], [
                'item_id.required' => 'Enter Item Name',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }
            $itemIds = explode(',', $request->item_id);

            if (is_array($itemIds) && count($itemIds) > 0) {
                foreach ($itemIds as $itemId) {
                    $itemMaster = IteamsModel::find($itemId);

                    if ($itemMaster) {
                        $userid = auth()->user();
                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = array(auth()->user()->id);
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                        $companyData = CompanyModel::find($itemMaster->pharma_shop);
                        $purchesDatas = PurchesDetails::where('iteam_id', $itemMaster->id)->whereIn('user_id', $allUserId)->orderBy('id', 'DESC')->first();
                        $distributorName = isset($purchesDatas->getpurchesData->distributor_id) ? $purchesDatas->getpurchesData->distributor_id : "";

                        $supplierName = Distributer::find($distributorName);
                        $onlineOrder = new OnlineOrder;
                        $onlineOrder->item_id = $itemMaster->id;
                        $onlineOrder->item_name = $itemMaster->iteam_name; // Fixed typo
                        $onlineOrder->company_name = $companyData->company_name ?? 'N/A'; // Added null check
                        $onlineOrder->supplier_name = $supplierName->name ?? '-'; // Added null check
                        $onlineOrder->stock = $itemMaster->stock;
                        $onlineOrder->y_n = '1';
                        $onlineOrder->user_id = auth()->user()->id;
                        $onlineOrder->sales_id = "";
                        $onlineOrder->save();
                    }
                }

                $userLogs = new LogsModel;
                $userLogs->message = 'Bulk Online Order';
                $userLogs->user_id = auth()->user()->id;
                $userLogs->date_time = now(); // Using Laravel's helper for current date and time
                $userLogs->save();
            }

            return $this->sendResponse([], 'Item added to order.');
        } catch (\Exception $e) {
            dD($e);
            Log::error("Bulk Online Order API Error: " . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing the order'], 500);
        }
    }

    public function onlineSalesStatusChanges(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'status' => 'required',
            ], [
                'id.required' => 'Enter Id',
                'status.required' => 'Enter Status',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $idsData = $request->id;
            $explodeData = explode(',', $idsData);
            $onlineOrder = OnlineOrder::whereIn('item_id', $explodeData)->get();
            if (isset($onlineOrder)) {
                foreach ($onlineOrder as $list) {
                    $orderList = OnlineOrder::find($list->id);
                    $orderList->y_n = $request->status;
                    $orderList->update();
                }
            }
            // if(isset($orderList))
            //{
            //foreach($orderList as $list)
            //{
            //$orderList = OnlineOrder::find($list);

            //$orderList->y_n = $request->status;
            //$orderList->update();
            //}
            ///}

            return $this->sendResponse([], 'Online Order Successfully.');
        } catch (\Exception $e) {
            Log::error("Status Online Order API Error: " . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing the order'], 500);
        }
    }

    public function onlineSalesStatus(Request $request)
    {
        try {
            $orderStatus = orderStatus::orderBy('id', 'DESC')->get();
            $data = [];
            if (isset($orderStatus)) {
                foreach ($orderStatus as $key => $listStatus) {
                    $data[$key]['id'] = isset($listStatus->id) ? $listStatus->id : "";
                    $data[$key]['name'] = isset($listStatus->name) ? $listStatus->name : "";
                }
            }
            return $this->sendResponse($data, 'Data Fetch Succesffuly.');
        } catch (\Exception $e) {
            Log::error("Status Online Order API Error: " . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing the order'], 500);
        }
    }

    public function multipleSalePdfDownloads(Request $request)
    {
        try {
            $html_url = route('multple.pdf.sales.dwonalod', ['user_id' => auth()->user()->id, 'start_date' => $request->start_date, 'end_date' => $request->end_date]);

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

    public function getmultipleSalePdfDownloads($userId, $startDate, $endDate)
    {
        try {
            $staffGetData = User::where('create_by', $userId)->pluck('id')->toArray();
            $ownerGet = User::where('id', $userId)->pluck('create_by')->toArray();
            $userId = array($userId);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $salesData = SalesModel::whereBetween('bill_date', [$startDate, $endDate])->whereIn('user_id', $allUserId)->orderBy('id', 'DESC')->get();

            $saleDetails = [];

            foreach ($salesData as $list) {
                $userIdData = User::where('id', $list->user_id)->first();
        		$licenseData = LicenseModel::where('user_id', $list->user_id)->first();

                if ($list->getSales && $list->getSales->count() > 0) {
                    $totalItems = $list->getSales->count();
                    $qtyDataTotal = $list->getSales->sum('qty');
                    $totalBase = $list->getSales->sum('base');
                    $iteamGst = $list->getSales->sum('gst');

                    $gstData = $totalItems > 0 ? $iteamGst / $totalItems : 0;
                    $totalGst = $totalBase * $gstData / 100;
                    $total_qty = (string)$qtyDataTotal;
                    $total_gst = (string)round($totalGst, 0);
                } else {
                    $total_qty = '0';
                    $total_gst = '0';
                }
                $qtyDataTotal = $list->getSales->sum('qty');
                $totalItems = $list->getSales->count();
                $bankName  = BankAccount::where('id', $list->payment_name)->first();

                if ($list->getSales &&  $list->getSales->count() > 0) {
                    foreach ($list->getSales as $key => $itemList) {
                        $gstModel = GstModel::where('id', $itemList->gst)->first();
                        $iteamName  = IteamsModel::where('id', $itemList->iteam_id)->first();

                        $salesItemsDetails[$key] = [
                            'id' => $itemList->id ?? "",
                            'item_name' => $iteamName->iteam_name ?? "",
                            'front_photo' => isset($iteamName->front_photo) ? asset('/public/front_photo/' . $iteamName->front_photo) : "",
                            'item_id' => $itemList->iteam_id ?? "",
                            'user_id' => $itemList->user_id ?? "",
                            'mrp' => isset($itemList->mrp) ? (string)round($itemList->mrp, 2) : "",
                            'net_rate' => number_format($itemList->amt, 0, '', ',') ?? "",
                            'unit' => $itemList->unit ?? "",
                            'batch' => $itemList->batch ?? "",
                            'order' => $itemList->order ?? "",
                            'base' => $itemList->base ?? "",
                            'location' => $itemList->location ?? "",
                            'exp' => $itemList->exp ?? "",
                            'qty' => $itemList->qty ?? "",
                            'gst' => $itemList->gst ?? "",
                            'gst_name' => $gstName->name ?? "",
                            'random_number' => $itemList->random_number ?? "",
                        ];
                    }
                }

                $saleDetails[] = [
                    'id' => isset($list->id) ? $list->id : "",
                    'license_20' => isset($licenseData->license_no) ? $licenseData->license_no : "",
                    'license_21' => isset($licenseData->license_no_two) ? $licenseData->license_no_two : "",
                    'fssai_no' => isset($licenseData->license_no_three) ? $licenseData->license_no_three : "",
                    'logo' => isset($userIdData->pharmacy_logo) ? asset('/pharmacy_logo/' . $userIdData->pharmacy_logo) : '',
                    'address' => $userIdData->address ?? "",
                    'total_qty' =>  $total_qty ?? "",
                    'total_iteam' =>  $totalItems ?? "",
                    'phone_number' => $userIdData->phone_number ?? "",
                    'gst_pan' => $userIdData->gst_pan ?? "",
                    'pan_card' => $userIdData->pan_card ?? "",
                    'user_name' => $userIdData->name ?? "",
                    'total_gst' => (string)round($total_gst, 0),
                    'pickup' => isset($list->pickup) ? $list->pickup : "",
                    'round_off' => isset($list->round_off) ? $list->round_off : "",
                    'total_margin' => isset($list->margin) ? $list->margin : "",
                    'total_net_rate' => isset($list->net_rate) ? $list->net_rate : "",
                  	'loyalti_point' => isset($list->today_loylti_point) ? $list->today_loylti_point : "",
                    'discount_amount' => isset($list->total_discount) ? number_format($list->total_discount, 0, '', ',') : "",
                    'customer_name' => isset($list->getUserName->name) ? $list->getUserName->name . ' ' . $list->getUserName->last_name : '',
                    'customer_address' => isset($list->getUserName->address) ? $list->getUserName->address : '',
                    'mobile_numbr' => isset($list->getUserName->phone_number) ? $list->getUserName->phone_number : '',
                    'doctor_name' => isset($list->getDoctor->name) ? $list->getDoctor->name . ' ' . $list->getDoctor->last_name : '',
                    'doctor_mobile_numbr' => isset($list->getDoctor->phone_number) ? $list->getDoctor->phone_number : '',
                    'dicount' => isset($list->dicount) ? $list->dicount : '',
                    'other_amount' => isset($list->adjustment) ? (string)round($list->adjustment, 2) : '0',
                    'owner_name' => isset($list->owner_name) ? $list->owner_name : '',
                    'bill_date' => isset($list->created_at) ? $list->created_at : '',
                    'customer_id' => isset($list->customer_id) ? $list->customer_id : '',
                    'doctor_id' => isset($list->doctor_id) ? $list->doctor_id : '',
                    'payment_id' => isset($list->payment_name) ? $list->payment_name : '',
                    'payment_name' => isset($bankName->bank_name) ? $bankName->bank_name : $list->payment_name,
                    'bill_no' => isset($list->bill_no) ? $list->bill_no : '',
                    'pdf' => isset($list->pdf) ? asset('/public/pdf/' . $list->pdf) : '',
                    'net_amount' => isset($list->net_amt) ? (string) 'Rs. ' . number_format($list->net_amt, 0, '', ',') : '',
                    'total_amount' => isset($list->mrp_total) ? number_format($list->mrp_total, 0, '', ',') : '',
                    'total_discount' => isset($list->dicount) ? $list->dicount : '',
                    'adjustment' => isset($list->adjustment) ? $list->adjustment : '',
                    'pickup' => isset($list->pickup) ? $list->pickup : '',
                    'total_base' => isset($list->total_base) ? $list->total_base : '',
                    'given_amount' => isset($list->given_amount) ? $list->given_amount : '',
                    'due_amount' => isset($list->due_amount) ? (string)round($list->due_amount, 2) : '',
                    'sgst' => isset($list->sgst) ? $list->sgst : '',
                    'cgst' => isset($list->cgst) ? $list->cgst : '',
                    'igst' => isset($list->igst) ? $list->igst : '',
                    'status' => isset($list->status) ? $list->status : '',
                    'sales_item' => $salesItemsDetails,
                ];
            }

            return view('sales_multiple_pdf', compact('saleDetails'));
        } catch (\Exception $e) {
            Log::info("sale bill multiple pdf download api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
