<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesReturn;
use App\Models\SalesReturnDetails;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\IteamsModel;
use App\Models\LedgerModel;
use App\Models\SalesModel;
use App\Models\SalesFinalIteam;
use App\Models\User;
use App\Models\BatchModel;
use App\Models\SalesIteam;
use App\Models\GstModel;
use App\Models\SalesReturnEdit;
use  App\Models\CashManagement;
use App\Models\CashCategory;
use App\Models\BankAccount;
use App\Models\PassBook;
use App\Models\salesDetails;
use Carbon\Carbon;
use App\Models\CustomerModel;
use App\Models\DoctorModel;
use App\Models\LogsModel;
use App\Models\CompanyModel;
use App\Models\ItemLocation;
use App\Models\FinalPurchesItem;
use App\Models\FinalIteamId;
use App\Models\PurchesDetails;
use App\Models\LicenseModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

class SalesReturnController extends ResponseController
{
    // this function use create sales
    public function salesReturnCreate(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            $salesReturn = new SalesReturn;
            $salesReturn->date = $request->bill_date;
            $salesReturn->net_rate = $request->net_rate;
            $salesReturn->draft_save = $request->draft_save;
            $salesReturn->customer_id = $request->customer_id;
            $salesReturn->bill_no = $request->bill_no;
            $salesReturn->margin = $request->margin;
            $salesReturn->customer_address = $request->customer_address;
            $salesReturn->doctor_id = $request->doctor_id;
            $salesReturn->mrp_total = $request->mrp_total;
            $salesReturn->total_gst = $request->total_gst;
            $salesReturn->round_off = $request->round_off;
            $salesReturn->margin_net_profit = $request->margin_net_profit;
            $salesReturn->total_discount = $request->total_discount;
            $salesReturn->adjustment_amount = isset($request->other_amount) ? (string)round($request->other_amount, 2) : "0";
            $salesReturn->net_amount = (string)round($request->net_amount, 2);
            $salesReturn->owner_name = $request->owner_name;
            $salesReturn->pickup = $request->pickup;
            $salesReturn->start_date = $request->start_date;
            $salesReturn->end_date = $request->end_date;
            $salesReturn->total_base = $request->total_base;
            $salesReturn->given_amount = round($request->given_amount, 2);
            $salesReturn->due_amount = round($request->due_amount, 2);
            $salesReturn->sgst = $request->sgst;
            $salesReturn->cgst = $request->cgst;
            $salesReturn->igst = $request->igst;
            $salesReturn->user_id = $userId;
            $salesReturn->payment_name = $request->payment_name;
            if ($request->payment_name == 'cash') {
                $cashManage = CashManagement::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();

                if (isset($cashManage)) {
                    $amount = $cashManage->opining_balance -  $request->net_amount;
                    $cashAdd = new CashManagement;
                    $cashAdd->date = $request->bill_date;
                    // $cashAdd->category = $request->category;
                    $cashAdd->description = 'Sales Return Manage';
                    $cashAdd->type = 'debit';
                    $cashAdd->amount = round($request->net_amount, 2);
                    $cashAdd->user_id = auth()->user()->id;
                    $cashAdd->reference_no = $request->bill_no;
                    $cashAdd->voucher     = 'sales Return';
                    $cashAdd->opining_balance = round($amount, 2);
                    $cashAdd->save();
                } else {
                    $cashAdd = new CashManagement;
                    $cashAdd->date = $request->bill_date;
                    // $cashAdd->category = $request->category;
                    $cashAdd->description = 'Sales Return Manage';
                    $cashAdd->type = 'debit';
                    $cashAdd->amount = round($request->net_amount, 2);
                    $cashAdd->user_id = auth()->user()->id;
                    $cashAdd->reference_no = $request->bill_no;
                    $cashAdd->voucher     = 'sales Return';
                    $cashAdd->opining_balance = round($request->net_amount, 2);
                    $cashAdd->save();
                }
            } else {
                $passBook =  PassBook::where('bank_id', $request->payment_name)->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                if (isset($passBook)) {
                    $amount =  $passBook->balance - $request->net_amount;

                    $passbook = new PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $request->bill_date;
                    // $passbook->party_name = $request->party;
                    $passbook->bank_id = $request->payment_name;
                    $passbook->deposit = "";
                    $passbook->withdraw     = round($request->net_amount, 2);
                    $passbook->balance = round($amount, 2);
                    $passbook->mode = "";
                    // $passbook->remark = $request->remark;
                    $passbook->save();
                } else {
                    $passbook = new PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $request->bill_date;
                    // $passbook->party_name = $request->party;
                    $passbook->bank_id = $request->payment_name;
                    $passbook->deposit = "";
                    $passbook->withdraw    = round($request->net_amount, 2);
                    $passbook->balance = round($request->net_amount, 2);
                    $passbook->mode = "";
                    // $passbook->remark = $request->remark;
                    $passbook->save();
                }
            }
            $distributorData = CustomerModel::where('id', $request->customer_id)->first();
            if (isset($distributorData)) {
                $totalGst = isset($request->total_gst) ? $request->total_gst : 0;
                if (isset($distributor->state) && strtolower($distributor->state) === strtolower($distributorData->state)) {
                    $salesReturn->sgst = $totalGst != 0 ? $totalGst / 2 : "";
                    $salesReturn->cgst =  $totalGst != 0 ? $totalGst / 2 : "";
                } else {
                    $salesReturn->igst = $totalGst;
                }
            }
            $salesReturn->save();

            $purchesTrueValue = json_decode($request->product_list, true);

            $productData = array_filter($purchesTrueValue, function ($item) {
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

                    $purchesEdit = salesDetails::whereIn('user_id', $allUserId)->where('iteam_id', $listData['item_id'])->where('batch',  $listData['batch'])->where('random_number', $listData['random_number'])->orderBy('id', 'DESC')->first();

                    if (isset($purchesEdit)) {
                        $salesFinalData = SalesFinalIteam::where('id', $listData['id'])->first();

                        if (isset($salesFinalData)) {
                            $salesFinalData->item_id = $purchesEdit->iteam_id;
                            $salesFinalData->qty = $purchesEdit->iteam_id;
                            $salesFinalData->exp = $purchesEdit->exp;
                            $salesFinalData->gst = $purchesEdit->gst;
                            $salesFinalData->mrp = $purchesEdit->mrp;
                            $salesFinalData->amt = $purchesEdit->amt;
                            $salesFinalData->unit = $purchesEdit->unit;
                            $salesFinalData->batch = $purchesEdit->batch;
                            $salesFinalData->base = $purchesEdit->base;
                            $salesFinalData->order = $purchesEdit->order;
                            $salesFinalData->location = $purchesEdit->location;
                            $salesFinalData->iss_check = '0';
                            $salesFinalData->status = '0';
                            $salesFinalData->update();
                        }
                    }
                }
            }

            if ((isset($productData)) && ($salesReturn->draft_save != '0')) {
                foreach ($productData as $list) {
                    $textbleVlaue = ($list['qty'] ?? 0) * ($list['base'] ?? 0);
                    $details = new SalesReturnDetails;
                    $details->sales_id = $salesReturn->id;
                    $details->taxable_value = $textbleVlaue;
                    $details->iteam_id = $list['item_id'];
                    $details->user_id = auth()->user()->id;
                    $details->qty = $list['qty'];
                    $details->exp = $list['exp'];
                    $details->mrp = $list['mrp'];
                    $details->random_number = $list['random_number'];
                    $details->gst = $list['gst'];
                    $details->net_rate = $list['net_rate'];
                    $details->unit = $list['unit'];
                    $details->batch = $list['batch'];
                    $details->base = $list['base'];
                    $details->location = $list['location'];
                    $details->save();

                    $detailsSales = new SalesReturnEdit;
                    $detailsSales->sales_id = $salesReturn->id;
                    $detailsSales->iteam_id = $list['item_id'];
                    $detailsSales->user_id = auth()->user()->id;
                    $detailsSales->qty = $list['qty'];
                    $detailsSales->exp = $list['exp'];
                    $detailsSales->mrp = $list['mrp'];
                    $detailsSales->random_number = $list['random_number'];
                    $detailsSales->gst = $list['gst'];
                    $detailsSales->net_rate = $list['net_rate'];
                    $detailsSales->unit = $list['unit'];
                    $detailsSales->batch = $list['batch'];
                    $detailsSales->base = $list['base'];
                    $detailsSales->location = $list['location'];
                    $detailsSales->save();

                    $userName = CustomerModel::where('id', $request->customer_id)->first();

                    $leaderData = new LedgerModel;
                    $leaderData->owner_id = $request->customer_id;
                    $leaderData->entry_date = $request->bill_date;
                    $leaderData->transction = 'Sales Return Invoice';
                    $leaderData->voucher = 'Sales Return Invoice';
                    $leaderData->bill_no = '#' . $request->bill_no;
                    $leaderData->puches_id = $salesReturn->id;
                    $leaderData->batch = $list['batch'];
                    $leaderData->bill_date = $request->bill_date;
                    $leaderData->name = $userName->name;
                    $leaderData->user_id = auth()->user()->id;
                    $leaderData->iteam_id =  $list['item_id'];
                    $ledgers = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                    if (isset($ledgers)) {
                        $balance = (int)$list['qty'] + $ledgers->balance_stock;
                        $leaderData->in = (int)$list['qty'];
                        $leaderData->balance_stock = $balance;
                    } else {
                        $leaderData->in = (int)$list['qty'];
                        $leaderData->balance_stock = (int)$list['qty'];
                    }

                    $ledgers = LedgerModel::where('owner_id', $request->customer_id)->orderBy('id', 'DESC')->first();
                    if (isset($ledgers)) {

                        $total = $ledgers->balance + $request->net_amount;
                        $leaderData->debit = round($request->net_amount, 2);
                        $leaderData->balance = round($total, 2);
                    } else {
                        $leaderData->debit = round($request->net_amount, 2);
                        $leaderData->balance = round($request->net_amount, 2);
                    }
                    $leaderData->save();


                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $batchData = BatchModel::where('batch_number', $list['batch'])->whereIn('user_id', $allUserId)->first();
                    if (isset($batchData)) {
                        $finalPurchesData = SalesFinalIteam::where('id', $list['id'])->first();
                        if (isset($finalPurchesData)) {
                            if (($batchData->qty > 0) || ($batchData->qty != $list['qty'])) {
                                $qtyData = abs($batchData->sales_qty) - abs($list['qty']);

                                if ($qtyData == '0') {
                                    $finalPurchesData->status = "1";
                                }
                                $finalPurchesData->qty = abs($qtyData);
                                $finalPurchesData->update();
                                $batchData->sales_qty = $qtyData;
                            } else {
                                $finalPurchesData->status = "1";
                                $finalPurchesData->update();
                            }

                            // puraches retrun get data issue
                            $finalSalesDataSales = FinalPurchesItem::where('batch', $list['batch'])->where('iteam_id', $list['item_id'])->whereIn('user_id', $allUserId)->first();
                            if (isset($finalSalesDataSales)) {
                                $unit = (int)$finalSalesDataSales->unit;
                                // Prevent division by zero
                                $sales_qty_total = ($unit > 0) ? ((int)$list['qty'] / $unit) : (int)$list['qty'];

                                // Safely update qty
                                $finalSalesDataSales->qty = abs((int)$finalSalesDataSales->qty) + (int)$list['qty'];
                                $finalSalesDataSales->update();
                            }
                            // puraches retrun get data issue
                            $sales_qty_total = (int)$list['qty'] / (int)$batchData->unit;

                            $qtyData = (int)$list['qty'];
                            $finalQty = (int)$batchData->total_qty + (int)$list['qty'];
                            $batchData->item_id = $list['item_id'];
                            $batchData->qty = $list['qty'];
                            $batchData->location =  $list['location'];
                            $batchData->unit = $list['unit'];
                            $batchData->stock = '0';
                            $batchData->gst = isset($list['gst']) ? $list['gst'] : $list['gst'];
                            $batchData->batch_name = $list['batch'];
                            $batchData->expiry_date = $list['exp'];
                            $batchData->mrp = $list['mrp'];
                            $batchData->total_mrp = $list['mrp'] * $list['qty'];
                            $batchData->purches_qty = $batchData->purches_qty + $sales_qty_total;
                            $batchData->total_qty = abs($finalQty);
                            $batchData->update();
                        }
                    }
                }
            } else {
                if (isset($productData)) {
                    foreach ($productData as $list) {
                        $textbleVlaue = ($list['qty'] ?? 0) * ($list['base'] ?? 0);
                        $details = new SalesReturnDetails;
                        $details->sales_id = $salesReturn->id;
                        $details->taxable_value = $textbleVlaue;
                        $details->iteam_id = $list['item_id'];
                        $details->user_id = auth()->user()->id;
                        $details->qty = $list['qty'];
                        $details->exp = $list['exp'];
                        $details->mrp = $list['mrp'];
                        $details->random_number = $list['random_number'];
                        $details->gst = $list['gst'];
                        $details->net_rate = $list['net_rate'];
                        $details->unit = $list['unit'];
                        $details->batch = $list['batch'];
                        $details->base = $list['base'];
                        $details->location = $list['location'];
                        $details->save();

                        $detailsSales = new SalesReturnEdit;
                        $detailsSales->sales_id = $salesReturn->id;
                        $detailsSales->iteam_id = $list['item_id'];
                        $detailsSales->user_id = auth()->user()->id;
                        $detailsSales->qty = $list['qty'];
                        $detailsSales->exp = $list['exp'];
                        $detailsSales->mrp = $list['mrp'];
                        $detailsSales->random_number = $list['random_number'];
                        $detailsSales->gst = $list['gst'];
                        $detailsSales->net_rate = $list['net_rate'];
                        $detailsSales->unit = $list['unit'];
                        $detailsSales->batch = $list['batch'];
                        $detailsSales->base = $list['base'];
                        $detailsSales->location = $list['location'];
                        $detailsSales->save();
                    }
                }
            }
            $userLogs = new LogsModel;
            $userLogs->message = 'Sales Return Bill Create';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $dataDetails = [];
            $dataDetails['id'] = $salesReturn->id;
            return $this->sendResponse($dataDetails, 'Sales Return Create Successfully');
        } catch (\Exception $e) {
            Log::info("sales Return api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesReturnDelete(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Please Enter Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $salesData = SalesReturn::where('id', $request->id)->first();
            if (isset($salesData)) {
                $salesData->delete();
            }

            $salesIteamData = SalesReturnEdit::where('sales_id', $request->id)->get();
            if (isset($salesIteamData)) {
                foreach ($salesIteamData  as $listData) {
                    $listData->delete();
                }
            }

            $salesDatas = SalesReturnDetails::where('sales_id',  $request->id)->get();
            if (isset($salesDatas)) {
                foreach ($salesDatas as $list) {

                    $batchNumber = BatchModel::where('batch_name', $list->batch)->first();
                    if (isset($batchNumber)) {
                        $qty = (int)$batchNumber->qty + (int)$list->qty;
                        $qtyDatas = (int)$list->qty * $list->unit;
                        $totalQty = (int)$batchNumber->total_qty + $qtyDatas;
                        $batchNumber->qty = abs($qty);
                        $batchNumber->total_qty = abs($totalQty);
                        $batchNumber->update();
                    }

                    $legaderData  = LedgerModel::where('iteam_id', $list->iteam_id)->where('batch', $list->batch)->where('user_id', auth()->user()->id)->where('transction', 'Sales Return Invoice')->orderBy('id')->first();

                    if (isset($legaderData)) {
                        $legaderData->delete();
                    }

                    $legaderData  = LedgerModel::where('iteam_id', $list->iteam_id)->where('user_id', auth()->user()->id)->orderBy('id')->get();

                    if (isset($legaderData)) {
                        $prevStock = null;
                        foreach ($legaderData as $ListData) {
                            if ($prevStock !== null) {
                                if ($prevStock->in) {
                                    $amount = $prevStock->balance_stock + $ListData->in;
                                    $ListData->balance_stock = $amount;
                                } else {
                                    $amount = $prevStock->balance_stock + $ListData->out;
                                    $ListData->balance_stock = $amount;
                                }
                            } else {
                                $ListData->balance_stock = $ListData->out ?? 0;
                            }
                            $ListData->update();
                            $prevStock = $ListData;
                        }
                    }

                    $list->delete();
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Sales Return Bill Delete';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
            return $this->sendResponse('', 'Sales Return Bill Delete Successfully');
        } catch (\Exception $e) {
            Log::info("sales Return api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesReturnEditIteam(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Please Enter Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $editSalesReturn = SalesFinalIteam::where('id', $request->id)->first();

            $editSalesReturn->item_id = $request->item_id;
            $editSalesReturn->qty = $request->qty;
            $editSalesReturn->exp = $request->exp;
            $editSalesReturn->mrp = $request->mrp;
            $editSalesReturn->random_number = $request->random_number;
            $editSalesReturn->gst = $request->gst;
            $editSalesReturn->net_rate = $request->net_rate;
            $editSalesReturn->unit = $request->unit;
            $editSalesReturn->batch = $request->batch;
            $editSalesReturn->base = $request->base;
            $editSalesReturn->location = $request->location;
            $editSalesReturn->update();

            $userLogs = new LogsModel;
            $userLogs->message = 'Sales Return Bill Iteam Update';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            return $this->sendResponse('', 'Sales Return Iteam Update Successfully');
        } catch (\Exception $e) {

            Log::info("sales Return Edit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesReturnEditIteamSecond(Request $request)
    {

        try {

            $editSalesReturn = SalesReturnEdit::find($request->id);
            if (isset($editSalesReturn)) {
                $editSalesReturn->iteam_id = $request->item_id;
                $editSalesReturn->user_id = auth()->user()->id;
                $editSalesReturn->qty = $request->qty;
                $editSalesReturn->exp = $request->exp;
                $editSalesReturn->mrp = $request->mrp;
                $editSalesReturn->gst = $request->gst;
                $editSalesReturn->net_rate = $request->net_rate;
                $editSalesReturn->unit = $request->unit;
                $editSalesReturn->batch = $request->batch;
                $editSalesReturn->base = $request->base;
                $editSalesReturn->location = $request->location;
                $editSalesReturn->save();
            }
            $userLogs = new LogsModel;
            $userLogs->message = 'Sales Return Bill Iteam Update';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
            return $this->sendResponse('', 'Sales Return Iteam Update Successfully');
        } catch (\Exception $e) {

            Log::info("sales Return Edit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesReturnDeleteIteam(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Please Enter Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $editSalesReturn = SalesFinalIteam::find($request->id);

            if ($editSalesReturn) {

                $editSalesReturn->delete();

                $userLogs = new LogsModel;
                $userLogs->message = 'Sales Return Bill Item Deleted';
                $userLogs->user_id = auth()->user()->id;
                $userLogs->date_time = date('Y-m-d H:i a');
                $userLogs->save();

                return $this->sendResponse('', 'Sales Return Item Deleted Successfully');
            } else {
                return $this->sendError('Sales Return Item Not Found');
            }
        } catch (\Exception $e) {
            Log::error("Sales Return Delete Item API Error: " . $e->getMessage());
            return $this->sendError('An error occurred while trying to delete the sales return item.');
        }
    }

    public function salesReturnUpdate(Request $request)
    {
        try {

            $productData = json_decode($request->product_list, true);
            $userId = auth()->user()->id;
            $salesReturn = SalesReturn::find($request->id);
            $salesReturn->date = $request->bill_date;
            $salesReturn->margin = $request->margin;
            $salesReturn->draft_save = $request->draft_save;
            $salesReturn->net_rate = $request->net_rate;
            $salesReturn->customer_id = $request->customer_id;
            $salesReturn->bill_no = $request->bill_no;
            $salesReturn->customer_address = $request->customer_address;
            $salesReturn->doctor_id = $request->doctor_id;
            $salesReturn->margin_net_profit = $request->margin_net_profit;
            $salesReturn->mrp_total = $request->mrp_total;
            $salesReturn->start_date = $request->start_date;
            $salesReturn->end_date = $request->end_date;
            $salesReturn->total_gst = $request->total_gst;
            $salesReturn->total_discount = $request->total_discount;
            $salesReturn->adjustment_amount = isset($request->other_amount) ? round($request->other_amount, 2) : "0";
            $salesReturn->net_amount = round($request->net_amount, 2);
            $salesReturn->owner_name = $request->owner_name;
            $salesReturn->pickup = $request->pickup;
            $salesReturn->total_base = $request->total_base;
            $salesReturn->round_off = $request->round_off;
            $salesReturn->given_amount = round($request->given_amount, 2);
            $salesReturn->due_amount = round($request->due_amount, 2);
            $salesReturn->sgst = $request->sgst;
            $salesReturn->cgst = $request->cgst;
            $salesReturn->igst = $request->igst;
            $salesReturn->user_id = $userId;
            $salesReturn->payment_name = $request->payment_name;
            if ($request->payment_name == 'cash') {
                $cashManage = CashManagement::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();

                if (isset($cashManage)) {
                    $amount =  $cashManage->opining_balance - $request->net_amount;
                    $cashAdd = new CashManagement;
                    $cashAdd->date = $request->bill_date;
                    //   $cashAdd->category = $request->category;
                    $cashAdd->description = 'Sales Return Manage';
                    $cashAdd->type = 'debit';
                    $cashAdd->amount = round($request->net_amount, 2);
                    $cashAdd->user_id = auth()->user()->id;
                    $cashAdd->reference_no = $request->bill_no;
                    $cashAdd->voucher     = 'sales Return';
                    $cashAdd->opining_balance = $amount;
                    $cashAdd->save();
                } else {

                    $cashAdd = new CashManagement;
                    $cashAdd->date = $request->bill_date;
                    //   $cashAdd->category = $request->category;
                    $cashAdd->description = 'Sales Return Manage';
                    $cashAdd->type = 'debit';
                    $cashAdd->amount = round($request->net_amount, 2);
                    $cashAdd->user_id = auth()->user()->id;
                    $cashAdd->reference_no = $request->bill_no;
                    $cashAdd->voucher     = 'sales Return';
                    $cashAdd->opining_balance = round($request->net_amount, 2);
                    $cashAdd->save();
                }
            } else {
                $passBook =  PassBook::where('bank_id', $request->payment_name)->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                if (isset($passBook)) {
                    $amount = $passBook->balance - $request->net_amount;

                    $passbook = new  PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $request->bill_date;
                    // $passbook->party_name = $request->party;
                    $passbook->bank_id = $request->payment_name;
                    $passbook->deposit = "";
                    $passbook->withdraw     = round($request->net_amount, 2);
                    $passbook->balance = round($amount, 2);
                    $passbook->mode = "";
                    // $passbook->remark = $request->remark;
                    $passbook->save();
                } else {
                    $passbook = new  PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $request->bill_date;
                    // $passbook->party_name = $request->party;
                    $passbook->bank_id = $request->payment_name;
                    $passbook->deposit = "";
                    $passbook->withdraw    = round($request->net_amount, 2);
                    $passbook->balance = round($request->net_amount, 2);
                    $passbook->mode = "";
                    // $passbook->remark = $request->remark;
                    $passbook->save();
                }
            }
            $distributorData = CustomerModel::where('id', $request->customer_id)->first();
            if (isset($distributorData)) {
                $totalGst = isset($request->total_gst) ? $request->total_gst : 0;
                if (isset($distributor->state) && strtolower($distributor->state) === strtolower($distributorData->state)) {
                    $salesReturn->sgst = $totalGst != 0 ? $totalGst / 2 : "";
                    $salesReturn->cgst =  $totalGst != 0 ? $totalGst / 2 : "";
                } else {
                    $salesReturn->igst = $totalGst;
                }
            }
            $salesReturn->save();

            $purchesTrueValue = json_decode($request->product_list, true);

            $productData = array_filter($purchesTrueValue, function ($item) {
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

                    $salesReturnData = SalesReturnDetails::where('sales_id', $salesReturn->id)->whereIn('user_id', $allUserId)->where('iteam_id', $listData['item_id'])->where('batch',  $listData['batch'])->orderBy('id', 'DESC')->first();

                    if (isset($purchesEdit)) {
                        $detailsSales = SalesReturnEdit::where('sales_id', $salesReturn->id)->whereIn('user_id', $allUserId)->where('iteam_id', $listData['item_id'])->where('batch',  $listData['batch'])->orderBy('id', 'DESC')->first();

                        if (isset($salesFinalData)) {
                            $detailsSales->iteam_id = $salesReturnData->iteam_id;
                            $detailsSales->qty = $salesReturnData->qty;
                            $detailsSales->exp = $salesReturnData->exp;
                            $detailsSales->mrp = $salesReturnData->mrp;
                            $detailsSales->gst = $salesReturnData->gst;
                            $detailsSales->net_rate = $salesReturnData->net_rate;
                            $detailsSales->unit = $salesReturnData->unit;
                            $detailsSales->batch = $salesReturnData->batch;
                            $detailsSales->base = $salesReturnData->base;
                            $detailsSales->location = $salesReturnData->location;
                            $detailsSales->isse_check = '0';
                            $detailsSales->update();
                        }
                    }
                }
            }

            if ((isset($productData)) && ($salesReturn->draft_save != '0')) {
                $salesReturnData = SalesReturnDetails::where('sales_id', $salesReturn->id)->get();
                if (isset($salesReturn)) {
                    foreach ($salesReturnData as $List) {
                        $List->delete();
                    }
                }

                $salesReturns = SalesReturnEdit::where('sales_id', $salesReturn->id)->get();
                if (isset($salesReturns)) {
                    foreach ($salesReturns as $List) {
                        $List->delete();
                    }
                }

                foreach ($productData as $list) {
                    $textbleVlaue = ($list['qty'] ?? 0) * ($list['base'] ?? 0);
                    $details = new SalesReturnDetails;
                    $details->sales_id = $salesReturn->id;
                    $details->taxable_value = $textbleVlaue;
                    $details->iteam_id = $list['item_id'];
                    $details->user_id = auth()->user()->id;
                    $details->qty = $list['qty'];
                    $details->exp = $list['exp'];
                    $details->mrp = $list['mrp'];
                    $details->random_number = $list['random_number'];
                    $details->gst = $list['gst'];
                    $details->net_rate = $list['net_rate'];
                    $details->unit = $list['unit'];
                    $details->batch = $list['batch'];
                    $details->base = $list['base'];
                    $details->location = $list['location'];
                    $details->save();

                    $detailsSales = new SalesReturnEdit;
                    $detailsSales->sales_id = $salesReturn->id;
                    $detailsSales->iteam_id = $list['item_id'];
                    $detailsSales->user_id = auth()->user()->id;
                    $detailsSales->qty = $list['qty'];
                    $detailsSales->exp = $list['exp'];
                    $detailsSales->mrp = $list['mrp'];
                    $detailsSales->random_number = $list['random_number'];
                    $detailsSales->gst = $list['gst'];
                    $detailsSales->net_rate = $list['net_rate'];
                    $detailsSales->unit = $list['unit'];
                    $detailsSales->batch = $list['batch'];
                    $detailsSales->base = $list['base'];
                    $detailsSales->location = $list['location'];
                    $detailsSales->save();

                    $LeagerDelete = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->where('transction', 'Sales Return Invoice')->where('batch', $list['batch'])->first();
                    if (isset($LeagerDelete)) {
                        $LeagerDelete->in = (int)$list['qty'];
                        $LeagerDelete->update();
                    } else {
                        $userName = CustomerModel::where('id', $request->customer_id)->first();

                        $leaderData = new LedgerModel;
                        $leaderData->owner_id = $request->customer_id;
                        $leaderData->entry_date = $request->bill_date;
                        $leaderData->transction = 'Sales Return Invoice';
                        $leaderData->voucher = 'Sales Return Invoice';
                        $leaderData->bill_no = '#' . $request->bill_no;
                        $leaderData->puches_id = $salesReturn->id;
                        $leaderData->batch = $list['batch'];
                        $leaderData->bill_date = $request->bill_date;
                        $leaderData->name = $userName->name;
                        $leaderData->user_id = auth()->user()->id;
                        $leaderData->iteam_id = $list['item_id'];
                        $ledgers = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                        if (isset($ledgers)) {
                            $balance = (int)$list['qty'] + $ledgers->balance_stock;
                            $leaderData->in = (int)$list['qty'];
                            $leaderData->balance_stock = $balance;
                        } else {
                            $leaderData->in = (int)$list['qty'];
                            $leaderData->balance_stock = (int)$list['qty'];
                        }

                        $leaderData->save();
                    }


                    $legaderData  = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id')->get();

                    if (isset($legaderData)) {
                        $prevStock = null;
                        foreach ($legaderData as $ListData) {
                            if ($prevStock !== null) {
                                if ((isset($prevStock->out)) && ($ListData->in)) {
                                    $ListData->balance_stock = $prevStock->balance_stock + $ListData->in;
                                } else {
                                    $ListData->balance_stock = $prevStock->balance_stock - $ListData->out;
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

                    $batchData = BatchModel::where('batch_number', $list['batch'])->whereIn('user_id', $allUserId)->first();
                    if (isset($batchData)) {
                        $finalPurchesData = SalesFinalIteam::whereIn('user_id', $allUserId)->where('batch', $list['batch'])->where('item_id', $list['item_id'])->first();
                        if (isset($finalPurchesData)) {
                            $salesDetails = salesDetails::where('batch', $list['batch'])->whereIn('user_id', $allUserId)->where('iteam_id', $list['item_id'])->sum('qty');
                            if (($batchData->qty > 0) || ($batchData->qty != $list['qty'])) {
                                $qtyData = abs($salesDetails) - abs($list['qty']);
                                if ($qtyData == '0') {
                                    $finalPurchesData->status = "1";
                                }
                                $finalPurchesData->status = "0";
                                $finalPurchesData->qty = abs($qtyData);
                                $finalPurchesData->update();
                                $batchData->sales_qty = $qtyData;
                            } else {
                                $finalPurchesData->status = "1";
                                $finalPurchesData->update();
                            }

                            $finalSalesDataSales = FinalPurchesItem::where('batch', $list['batch'])->where('iteam_id', $list['item_id'])->whereIn('user_id', $allUserId)->first();
                            if (isset($finalSalesDataSales)) {
                                $sales_qty_total = (int)$list['qty'] / (int)$finalSalesDataSales->unit;
                                Log::info("sales Return unit Vlaue last" .   $sales_qty_total);
                                $finalSalesDataSales->qty = abs($finalSalesDataSales->qty) + $sales_qty_total;
                                Log::info("sales Return api" . $finalSalesDataSales->qty);
                                $finalSalesDataSales->update();
                            }

                            $sales_qty_total = (int)$list['qty'] / (int)$batchData->unit;
                            Log::info("sales Return last" .   $sales_qty_total);
                            $qtyData = (int)$list['qty'];

                            $ledgers = LedgerModel::where('iteam_id', $list['item_id'])->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();

                            $batchData->item_id = $list['item_id'];
                            $batchData->qty = $list['qty'];
                            $batchData->location =  $list['location'];
                            $batchData->unit = $list['unit'];
                            $batchData->stock = '0';
                            $batchData->gst = isset($list['gst']) ? $list['gst'] : $list['gst'];
                            $batchData->batch_name = $list['batch'];
                            $batchData->expiry_date = $list['exp'];
                            $batchData->mrp = $list['mrp'];
                            $batchData->total_mrp = $list['mrp'] * $list['qty'];
                            $batchData->purches_qty = $batchData->purches_qty + $sales_qty_total;
                            $batchData->total_qty = isset($ledgers->balance_stock) ? abs($ledgers->balance_stock) : "";
                            $batchData->update();
                        }
                    }
                }
            } else {
                $salesReturnData = SalesReturnDetails::where('sales_id', $salesReturn->id)->get();
                if (isset($salesReturn)) {
                    foreach ($salesReturnData as $List) {
                        $List->delete();
                    }
                }

                $salesReturns = SalesReturnEdit::where('sales_id', $salesReturn->id)->get();
                if (isset($salesReturns)) {
                    foreach ($salesReturns as $List) {
                        $List->delete();
                    }
                }

                if (isset($productData)) {
                    foreach ($productData as $list) {
                        $textbleVlaue = ($list['qty'] ?? 0) * ($list['base'] ?? 0);
                        $details = new SalesReturnDetails;
                        $details->sales_id = $salesReturn->id;
                        $details->taxable_value = $textbleVlaue;
                        $details->iteam_id = $list['item_id'];
                        $details->user_id = auth()->user()->id;
                        $details->qty = $list['qty'];
                        $details->exp = $list['exp'];
                        $details->mrp = $list['mrp'];
                        $details->random_number = $list['random_number'];
                        $details->gst = $list['gst'];
                        $details->net_rate = $list['net_rate'];
                        $details->unit = $list['unit'];
                        $details->batch = $list['batch'];
                        $details->base = $list['base'];
                        $details->location = $list['location'];
                        $details->save();

                        $detailsSales = new SalesReturnEdit;
                        $detailsSales->sales_id = $salesReturn->id;
                        $detailsSales->iteam_id = $list['item_id'];
                        $detailsSales->user_id = auth()->user()->id;
                        $detailsSales->qty = $list['qty'];
                        $detailsSales->exp = $list['exp'];
                        $detailsSales->mrp = $list['mrp'];
                        $detailsSales->random_number = $list['random_number'];
                        $detailsSales->gst = $list['gst'];
                        $detailsSales->net_rate = $list['net_rate'];
                        $detailsSales->unit = $list['unit'];
                        $detailsSales->batch = $list['batch'];
                        $detailsSales->base = $list['base'];
                        $detailsSales->location = $list['location'];
                        $detailsSales->save();
                    }
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Sales Return Bill Update';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $salesReturn = [];
            $salesReturn['id'] = $request->id;
            return $this->sendResponse([], 'Sales Update Create Successfully');
        } catch (\Exception $e) {
            Log::info("sales Return api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesReturnList(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $salesData = SalesReturn::orderBy('id', 'DESC')->whereIn('user_id', $allUserId);
            if (isset($request->bill_no)) {
                $salesData->where('bill_no', 'LIKE', '%' . $request->bill_no . '%');
            }
            if (isset($request->bill_amount)) {
                $salesData->where('net_amount', 'LIKE', '%' . $request->bill_amount . '%');
            }
            if (($request->start_date) && ($request->end_date)) {
                $start_date = date('Y-m-d', strtotime($request->start_date));
                $end_date = date('Y-m-d', strtotime($request->end_date));
                $salesData->whereBetween('created_at', [$start_date, $end_date]);
            }
            if ($request->customer_name) {
                $name = $request->customer_name;
                // $userData  = User::where('name', 'like', '%' . $name . '%')->pluck('id')->toArray();

                // $salesData->whereIn('customer_id',$userData ); 
                $salesData->whereHas('getUserName', function ($q) use ($name) {
                    $q->where('name', 'like', '%' . $name . '%')->orWhere('phone_number', 'LIKE', '%' . $name . '%');
                });
            }
            if ($request->filled('search')) {
                // Handle alphabetical search
                if ($request->search == 'Bill No. - A to Z') {
                    $salesData->orderBy('bill_no', 'asc');
                } elseif ($request->search == 'Bill No. - Z to A') {
                    $salesData->orderBy('bill_no', 'desc');
                } elseif ($request->search == 'Bill Date. - New to Old') {
                    $salesData->orderBy('date', 'asc');
                } elseif ($request->search == 'Bill Date. - Old to New') {
                    $salesData->orderBy('date', 'desc');
                } elseif ($request->search == 'Amount - 1 to 9') {
                    $salesData->orderBy('net_amount', 'asc');
                } elseif ($request->search == 'Amount - 9 to 1') {
                    $salesData->orderBy('net_amount', 'desc');
                }
            }
            if ($request->payment_name) {
                $salesData->where('payment_name', $request->payment_name);
            }
            if (isset($request->staff) && ($request->staff != 'All')) {
                if ($request->staff == 'owner') {
                    $salesData->where('user_id', auth()->user()->id);
                } else {
                    $salesData->where('user_id', $request->staff);
                }
            }
            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $salesData->offset($offset)->limit($limit);

            $salesData = $salesData->get();

            $salesReturnTotalCount = SalesReturn::whereIn('user_id', $allUserId)->count();

            $salesDetails = [];
            if (isset($salesData)) {
                foreach ($salesData as $key => $list) {
                    $bankName = BankAccount::where('id', $list->payment_name)->first();
                    $customerName = CustomerModel::where('id', $list->customer_id)->first();
                    $userData = User::where('id', $list->user_id)->first();

                    $salesDetails[$key]['id'] = isset($list->id) ? $list->id : "";
                    $salesDetails[$key]['draft_save'] = isset($list->draft_save) ? $list->draft_save : "";

                    $salesDetails[$key]['customer_name'] = isset($customerName->name) ? $customerName->name : "";
                    $salesDetails[$key]['phone_number'] = isset($customerName->phone_number) ? $customerName->phone_number : "";
                    $salesDetails[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                    $salesDetails[$key]['name'] = isset($userData->name) ? $userData->name : "";
                    $salesDetails[$key]['entry_date'] = isset($list->created_at) ? $list->created_at : "";
                    $salesDetails[$key]['payment_name'] = isset($bankName->bank_name) ? $bankName->bank_name : $list->payment_name;
                    $salesDetails[$key]['bill_date'] = isset($list->date) ? $list->date : "";
                    $salesDetails[$key]['net_amount'] = isset($list->net_amount) ? (string)round($list->net_amount, 2) : "";
                    $salesDetails[$key]['count'] = $salesData->count();
                    $salesDetails[$key]['bill_create_date_time'] = isset($list->created_at) ? date("Y-m-d h:i", strtotime($list->created_at)) : "";
                    $salesDetails[$key]['pdf_url'] = "";
                }
            }

            $response = [
                'status' => 200,
                'count' => !empty($request->page) ? $salesData->count() : $salesReturnTotalCount,
                'total_records' => $salesReturnTotalCount,
                'data'   => $salesDetails,
                'message' => 'Sales Return List Successfully.',
            ];
            return response()->json($response, 200);

            // return $this->sendResponse($salesDetails, 'Sales Return List Successfully.');
        } catch (\Exception $e) {
            Log::info("sales Return api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesReturnViewDetails(Request $request)
    {
        try {


            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Please Enter Sales No'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $salesData = SalesReturn::where('id', $request->id)->first();

            $salesDetails = [];
            $iteamQty = [];
            $iteamGst = [];
            $totalBase = [];
            if (isset($salesData)) {
                $bankName = BankAccount::where('id', $salesData->payment_name)->first();

                $customerName = CustomerModel::where('id', $salesData->customer_id)->first();
                $doctoerName = DoctorModel::where('id', $salesData->doctor_id)->first();
                $salesDetails['id'] = isset($salesData->id) ? $salesData->id : "";
                $salesDetails['margin_net_profit'] = isset($salesData->margin_net_profit) ? $salesData->margin_net_profit : "";
                $salesDetails['total_margin'] = isset($salesData->margin) ? $salesData->margin : "";
                $salesDetails['total_net_rate'] = isset($salesData->net_rate) ? $salesData->net_rate : "";
                $salesDetails['payment_id'] = isset($salesData->payment_name) ? $salesData->payment_name : "";
                $salesDetails['draft_save'] = isset($salesData->draft_save) ? $salesData->draft_save : "";
                $salesDetails['total_gst'] = isset($salesData->total_gst) ? $salesData->total_gst : "";
                $salesDetails['start_date'] = isset($salesData->start_date) ? $salesData->start_date : "";
                $salesDetails['end_date'] = isset($salesData->end_date) ? $salesData->end_date : "";
                $salesDetails['payment_name'] = isset($bankName->bank_name) ? $bankName->bank_name : $salesData->payment_name;
                $salesDetails['round_off'] = isset($salesData->round_off) ? $salesData->round_off : "";
                $salesDetails['bill_date'] = isset($salesData->date) ? $salesData->date : "";
                $salesDetails['customer_id'] = isset($salesData->customer_id) ? $salesData->customer_id : "";
                $salesDetails['doctor_id'] = isset($salesData->doctor_id) ? $salesData->doctor_id : "";
                $salesDetails['bill_no'] = isset($salesData->bill_no) ? $salesData->bill_no : "";
                $salesDetails['customer_name'] = isset($customerName->name) ? $customerName->name : "";
                $salesDetails['customer_number'] = isset($customerName->phone_number) ? $customerName->phone_number : "";
                $salesDetails['doctor_name'] = isset($doctoerName->name) ? $doctoerName->name : "";
                $salesDetails['mrp_total'] = isset($salesData->mrp_total) ? $salesData->mrp_total : "";
                $salesDetails['customer_address'] = isset($salesData->customer_address) ? $salesData->customer_address : "";
                $salesDetails['total_discount'] = isset($salesData->total_discount) ? $salesData->total_discount : "";
                $salesDetails['other_amount'] = isset($salesData->adjustment_amount) ? (string)round($salesData->adjustment_amount, 2) : "";
                $salesDetails['net_amount'] = isset($salesData->net_amount) ? (string)round($salesData->net_amount, 2) : "";
                $salesDetails['owner_name'] = isset($salesData->owner_name) ? $salesData->owner_name : "";
                $salesDetails['pickup'] = isset($salesData->pickup) ? $salesData->pickup : "";
                $salesDetails['total_base'] = isset($salesData->total_base) ? $salesData->total_base : "";
                $salesDetails['given_amount'] = isset($salesData->given_amount) ? (string)round($salesData->given_amount, 2) : "";
                $salesDetails['due_amount'] = isset($salesData->due_amount) ? (string)round($salesData->due_amount, 2) : "";
                $salesDetails['sgst'] = isset($salesData->sgst) ? $salesData->sgst : "";
                $salesDetails['cgst'] = isset($salesData->cgst) ? $salesData->cgst : "";
                $salesDetails['igst'] = isset($salesData->igst) ? $salesData->igst : "";

                $salesDetails['sales_retur_view'] = [];
                if ($salesData->salesReturnGet) {
                    foreach ($salesData->salesReturnGet as $key => $list) {

                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = array(auth()->user()->id);
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                        $purchaesNetRate = PurchesDetails::whereIn('user_id', $allUserId)->where('iteam_id', $list->iteam_id)->where('batch', $list->batch)->sum('net_rate');
                        $gstValue = GstModel::where('id', $list->gst)->first();
                        $salesDetails['sales_retur_view'][$key]['id'] = isset($list->id) ? $list->id : "";
                        $salesDetails['sales_retur_view'][$key]['iteam_name'] = isset($list->getIteamName) ? $list->getIteamName->iteam_name : "";
                        $salesDetails['sales_retur_view'][$key]['front_photo'] =  isset($list->getIteamName->front_photo) ? asset('/public/front_photo/' . $list->getIteamName->front_photo) : "";
                        $salesDetails['sales_retur_view'][$key]['qty'] = isset($list->qty) ? $list->qty : "";
                        $salesDetails['sales_retur_view'][$key]['exp'] = isset($list->exp) ? $list->exp : "";
                        $salesDetails['sales_retur_view'][$key]['mrp'] = isset($list->mrp) ? $list->mrp : "";
                        $salesDetails['sales_retur_view'][$key]['random_number'] = isset($list->random_number) ? $list->random_number : "";
                        $salesDetails['sales_retur_view'][$key]['gst'] = isset($gstValue->name) ? $gstValue->name : $list->gst;
                        $salesDetails['sales_retur_view'][$key]['net_rate'] = isset($list->net_rate) ? $list->net_rate : "";
                        $salesDetails['sales_retur_view'][$key]['unit'] = isset($list->unit) ? $list->unit : "";
                        $salesDetails['sales_retur_view'][$key]['batch'] = isset($list->batch) ? $list->batch : "";
                        $salesDetails['sales_retur_view'][$key]['base'] = isset($list->base) ? $list->base : "";
                        $salesDetails['sales_retur_view'][$key]['location'] = isset($list->location) ? $list->location : "";

                        $resultGst = isset($gstValue->name) ? $gstValue->name : 0;
                        $resultQty = isset($list->qty) ? $list->qty : 0;
                        $resultBase = isset($list->base) ? $list->base : 0;

                        array_push($iteamQty, $resultQty);
                        array_push($iteamGst, $resultGst);
                        array_push($totalBase, $resultBase);
                    }
                }
                if (isset($salesData->salesReturnGet)) {

                    $countRecords  = $salesData->salesReturnGet->count();

                    $totalBase = (int)array_sum($totalBase);
                    $gstData = $countRecords > 0 ? array_sum($iteamGst) / $countRecords : 0;
                    $totalGst = $totalBase * $gstData / 100;

                    $salesDetails['total_qty'] = (string)array_sum($iteamQty);
                    $salesDetails['total_gst'] = isset($salesData->total_gst) ? $salesData->total_gst : "";
                } else {
                    $salesDetails['total_qty'] = "";
                    $salesDetails['total_gst'] = "";
                }
            }

            return $this->sendResponse($salesDetails, 'Sales Return List Successfully');
        } catch (\Exception $e) {
            Log::info("sales Return list Details api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesReturnEditDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Please Enter Id'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $salesData = SalesReturn::where('id', $request->id)->first();
            $salesDetails = [];
            $netRate  = [];
            $baseTotal = [];
            $withoutGst = [];
            $iteamQty = [];
            $iteamGst = [];
            $marginData = [];
            $netRateNew = [];
            $iteamMrp = [];
            if (isset($salesData)) {
                $bankName = BankAccount::where('id', $salesData->payment_name)->first();
                $customerName = CustomerModel::where('id', $salesData->customer_id)->first();
                $doctoerName = DoctorModel::where('id', $salesData->doctor_id)->first();
                $salesDetails['id'] = isset($salesData->id) ? $salesData->id : "";
                $salesDetails['draft_save'] = isset($salesData->id) ? $salesData->draft_save : "";

                $salesDetails['start_date'] = isset($salesData->start_date) ? $salesData->start_date : "";
                $salesDetails['end_date'] = isset($salesData->end_date) ? $salesData->end_date : "";
                $salesDetails['total_gst'] = isset($salesData->total_gst) ? $salesData->total_gst : "";
                $salesDetails['payment_name'] = isset($bankName->bank_name) ? $bankName->bank_name : $salesData->payment_name;
                $salesDetails['customer_id'] = isset($salesData->customer_id) ? $salesData->customer_id : "";
                $salesDetails['doctor_id'] = isset($salesData->doctor_id) ? $salesData->doctor_id : "";
                $salesDetails['bill_date'] = isset($salesData->date) ? $salesData->date : "";
                $salesDetails['bill_no'] = isset($salesData->bill_no) ? $salesData->bill_no : "";
                $salesDetails['customer_id'] = isset($salesData->customer_id) ? $salesData->customer_id : "";
                $salesDetails['doctor_id'] = isset($salesData->doctor_id) ? $salesData->doctor_id : "";
                $salesDetails['customer_name'] = isset($customerName->name) ? $customerName->name : "";
                $salesDetails['customer_number'] = isset($customerName->phone_number) ? $customerName->phone_number : "";
                $salesDetails['doctor_name'] = isset($doctoerName->name) ? $doctoerName->name : "";
                $salesDetails['mrp_total'] = isset($salesData->mrp_total) ? $salesData->mrp_total : "";
                $salesDetails['customer_address'] = isset($salesData->customer_address) ? $salesData->customer_address : "";
                $salesDetails['total_discount'] = isset($salesData->total_discount) ? $salesData->total_discount : "";
                $salesDetails['other_amount'] = isset($salesData->adjustment_amount) ? (string)round($salesData->adjustment_amount, 2) : "";
                $salesDetails['net_amount'] = isset($salesData->net_amount) ? (string)round($salesData->net_amount, 2) : "";
                $salesDetails['owner_name'] = isset($salesData->owner_name) ? $salesData->owner_name : "";
                $salesDetails['pickup'] = isset($salesData->pickup) ? $salesData->pickup : "";
                $salesDetails['total_base'] = isset($salesData->total_base) ? $salesData->total_base : "";
                $salesDetails['given_amount'] = isset($salesData->given_amount) ? (string)round($salesData->given_amount, 2) : "";
                $salesDetails['due_amount'] = isset($salesData->due_amount) ? (string)round($salesData->due_amount, 2) : "";
                // $salesDetails['sgst'] = isset($salesData->sgst) ? $salesData->sgst :"";
                // $salesDetails['cgst'] = isset($salesData->cgst) ? $salesData->cgst :"";
                $salesDetails['igst'] = isset($salesData->igst) ? $salesData->igst : "";

                $salesReturnEdit = SalesReturnEdit::where('sales_id', $salesData->id);
                if (isset($request->search)) {
                    $searchTerm = '%' . $request->search . '%';

                    // Fetch related company IDs based on the search term
                    $companyIds = CompanyModel::where('company_name', 'like', $searchTerm)->pluck('id');

                    // Fetch item IDs related to location data
                    $locationItemIds = ItemLocation::where('location', 'like', $searchTerm)->pluck('item_id');

                    // Fetch item IDs based on item name, MRP, pharma shop, or location-based item IDs
                    $itemIds = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->where(function ($query) use ($searchTerm, $locationItemIds) {
                        $query->where('iteam_name', 'like', $searchTerm)
                            ->orWhere('mrp', 'like', $searchTerm)
                            ->orWhere('pharma_shop', 'like', $searchTerm)
                            ->orWhereIn('id', $locationItemIds);
                    })->pluck('id');

                    // Filter sales items using the collected item IDs
                    $salesReturnEdit->whereIn('iteam_id', $itemIds);
                }
                $salesReturnEdit = $salesReturnEdit->orderBy('id', 'DESC')->get();
                $salesDetails['sales_iteam'] = [];
                if (isset($salesReturnEdit)) {
                    foreach ($salesReturnEdit as $key => $list) {
                        if ($list->isse_check == '0') {
                            $status = true;
                        } else {
                            $status = false;
                        }

                        $iteamName  = IteamsModel::where('id', $list->iteam_id)->first();
                        $gstName  = GstModel::where('id', $list->gst)->first();

                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = array(auth()->user()->id);
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                        $batchNumber = BatchModel::where('batch_name', $list->batch)->where('item_id', $list->iteam_id)->whereIn('user_id', $allUserId)->sum('sales_qty');
                        $totalStock = (int)$batchNumber + (int)$list->qty;
                        $purchaesMargin = PurchesDetails::whereIn('user_id', $allUserId)->where('iteam_id', $list->iteam_id)->where('batch', $list->batch)->sum('margin');

                        $purchaesNetRate = PurchesDetails::whereIn('user_id', $allUserId)->where('iteam_id', $list->iteam_id)->where('batch', $list->batch)->sum('net_rate');

                        $salesDetails['sales_iteam'][$key]['id'] = isset($list->id) ? $list->id : "";
                        $salesDetails['sales_iteam'][$key]['total_stock'] = isset($totalStock) ? (string)$totalStock : "";
                        $salesDetails['sales_iteam'][$key]['item_id'] = isset($list->iteam_id) ? $list->iteam_id : "";
                        $salesDetails['sales_iteam'][$key]['iteam_name'] = isset($iteamName->iteam_name) ? $iteamName->iteam_name : "";
                        $salesDetails['sales_iteam'][$key]['front_photo'] = isset($iteamName->front_photo) ? asset('/public/front_photo/' . $iteamName->front_photo) : "";
                        $salesDetails['sales_iteam'][$key]['user_id'] = isset($list->user_id) ? $list->user_id : "";
                        $salesDetails['sales_iteam'][$key]['qty'] = isset($list->qty) ? (string)$list->qty : "";
                        $salesDetails['sales_iteam'][$key]['exp'] = isset($list->exp) ? $list->exp : "";
                        $salesDetails['sales_iteam'][$key]['mrp'] = isset($list->mrp) ? $list->mrp : "";
                        $salesDetails['sales_iteam'][$key]['random_number'] = isset($list->random_number) ? $list->random_number : "";
                        $salesDetails['sales_iteam'][$key]['gst'] = isset($list->gst) ? $list->gst : "";
                        $salesDetails['sales_iteam'][$key]['gst_name'] = isset($gstName->name) ? $gstName->name : "";
                        $salesDetails['sales_iteam'][$key]['net_rate'] = isset($list->net_rate) ? $list->net_rate : "";
                        $salesDetails['sales_iteam'][$key]['unit'] = isset($list->unit) ? $list->unit : "";
                        $salesDetails['sales_iteam'][$key]['batch'] = isset($list->batch) ? $list->batch : "";
                        $salesDetails['sales_iteam'][$key]['base'] = isset($list->base) ? $list->base : "";
                        $salesDetails['sales_iteam'][$key]['iss_check'] = isset($status) ? $status : "";
                        $salesDetails['sales_iteam'][$key]['location'] = isset($list->location) ? $list->location : "";

                        if ($list->isse_check == '0') {
                            $totalAmount = isset($list->net_rate) ? $list->net_rate : 0;
                            $gstRate = isset($list->gst) ? $list->gst : "";
                            $gstTotal = (int)($totalAmount * (int)$gstRate) / 100;
                            $gstAmount = (int)$totalAmount - (int)$gstTotal;
                            $totalGst =   (int)$totalAmount - (int)$gstAmount;

                            $resultGst = isset($list->gst) ? $list->gst : 0;
                            $resultQty = isset($list->qty) ? $list->qty : 0;

                            array_push($iteamQty, $resultQty);
                            array_push($iteamGst, $gstTotal);
                            array_push($marginData, $purchaesMargin);
                            array_push($iteamMrp, $list->mrp);
                            array_push($netRate, $purchaesNetRate);
                            array_push($withoutGst, abs($totalGst));
                            array_push($netRateNew, $list->net_rate);
                            array_push($baseTotal, $list->base);
                        }
                    }
                }
                if ($salesReturnEdit->count() > 0) {
                    $countRecords  = $salesReturnEdit->count();

                    $totalBase = (int)array_sum($baseTotal);
                    $gstData = $countRecords > 0 ? array_sum($iteamGst) / $countRecords : 0;
                    $totalGst = $totalBase * $gstData / 100;

                    $marginAmount = (int)array_sum($iteamMrp) - (int)array_sum($netRate);

                    $marginPrecent = $countRecords > 0 ? array_sum($marginData) / $countRecords : 0;
                    $salesDetails['total_qty'] = (string)array_sum($iteamQty);
                    $salesDetails['total_gst'] = (string)round(array_sum($iteamGst), 0);
                    $salesDetails['total_margin'] = (string)$marginPrecent;
                    $salesDetails['margin_net_profit'] = (string)$marginAmount;
                } else {
                    $salesDetails['total_qty'] = "";
                    $salesDetails['total_gst'] = "";
                    $salesDetails['total_margin'] = "";
                    $salesDetails['margin_net_profit'] = "";
                }

                $salesDetails['sales_amount'] = (string)array_sum($netRateNew);
                $salesDetails['total_base'] = (string)array_sum($baseTotal);

                $salesDetails['total_net_rate'] = (string)array_sum($netRate);

                $salesSgst = (string)array_sum($withoutGst);
                $salesDetails['sgst'] = round($salesSgst / 2);
                $salesDetails['cgst'] = round($salesSgst / 2);
            }
            return $this->sendResponse($salesDetails, 'Sales Return List Successfully');
        } catch (\Exception $e) {
            Log::info("sales Return list Details api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // SalesReturnEdit
    public function salesReturnEditIteamDelete(Request $request)
    {
        try {
            $salesEditData = SalesReturnEdit::where('id', $request->id)->first();
            if (isset($salesEditData)) {

                $legaderData  = LedgerModel::where('iteam_id', $salesEditData->iteam_id)->where('batch', $salesEditData->batch)->where('transction', 'Sales Return Invoice')->where('user_id', auth()->user()->id)->orderBy('id')->first();

                if (isset($legaderData)) {
                    $legaderData->delete();
                }

                $legaderData  = LedgerModel::where('iteam_id', $salesEditData->iteam_id)->where('user_id', auth()->user()->id)->orderBy('id')->get();

                if (isset($legaderData)) {
                    $prevStock = null;
                    foreach ($legaderData as $ListData) {
                        if ($prevStock !== null) {
                            if ($prevStock->in) {
                                $amount = $prevStock->balance_stock + $ListData->in;
                                $ListData->balance_stock = $amount;
                            } else {
                                $amount = $prevStock->balance_stock + $ListData->out;
                                $ListData->balance_stock = $amount;
                            }
                        } else {
                            $ListData->balance_stock = $ListData->out ?? 0;
                        }
                        $ListData->update();
                        $prevStock = $ListData;
                    }
                }

                $salesEditData->delete();
            }
            $userLogs = new LogsModel;
            $userLogs->message = 'Sales Return Bill Iteam Edit';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
            return $this->sendResponse([], 'Sales Return Iteam Edit Successfully');
        } catch (\Exception $e) {
            Log::info("sales Return Edit Iteam Delete Details api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesReturnIteamAmount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required',
            ], [
                'customer_id.required' => 'Please Enter Customer Id'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Check if start_date and end_date are present in the request
            if (!empty($startDate) && !empty($endDate)) {
                $start_date = date('Y-m-d', strtotime($request->start_date));
                $end_date = date('Y-m-d', strtotime($request->end_date));

                //  IteamsModel::where('')->pluck('id')->toArray();

                $salesModel = SalesModel::where('customer_id', $request->customer_id)
                    ->whereBetween('bill_date', [$start_date, $end_date])
                    ->pluck('id')
                    ->toArray();
            } else {
                $salesModel = SalesModel::where('customer_id', $request->customer_id)->pluck('id')->toArray();
            }

            $userId = auth()->user()->id;
            $salesIteamData = SalesFinalIteam::whereIn('sales_id', $salesModel)->where('qty', '!=', '0')->where('user_id', $userId)->where('status', '0')->orderBy('id', 'DESC');
            if (isset($request->search)) {
                $searchTerm = '%' . $request->search . '%';

                // Fetch related company IDs based on the search term
                $companyIds = CompanyModel::where('company_name', 'like', $searchTerm)->pluck('id');

                // Fetch item IDs related to location data
                $locationItemIds = ItemLocation::where('location', 'like', $searchTerm)->pluck('item_id');

                // Fetch item IDs based on item name, MRP, pharma shop, or location-based item IDs
                $itemIds = IteamsModel::where(function ($query) use ($searchTerm, $locationItemIds) {
                    $query->where('iteam_name', 'like', $searchTerm)
                        ->orWhere('mrp', 'like', $searchTerm)
                        ->orWhere('pharma_shop', 'like', $searchTerm)
                        ->orWhereIn('id', $locationItemIds);
                })->pluck('id');

                // Filter sales items using the collected item IDs
                $salesIteamData->whereIn('item_id', $itemIds);
            }
            $salesIteamData = $salesIteamData->get();

            $salesDetails = [];
            $netRate  = [];
            $baseTotal = [];
            $withoutGst = [];
            $qtyData = [];
            $TotalGstData = [];
            $margin = [];
            $netRateMargin = [];
            $IteamMrp = [];
            $countData = [];
            if (isset($salesIteamData)) {

                foreach ($salesIteamData as $key => $list) {
                    $status = "";
                    if ($list->iss_check == '0') {
                        $status = false;
                    } else {
                        $status = true;
                    }

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $batchStock = BatchModel::where('batch_name', $list->batch)->where('item_id', $list->item_id)->whereIn('user_id', $allUserId)->sum('sales_qty');

                    $iteamName  = IteamsModel::where('id', $list->item_id)->first();
                    $gstName  = GstModel::where('id', $list->gst)->first();

                    $purchaesMargin = PurchesDetails::whereIn('user_id', $allUserId)->where('iteam_id', $list->item_id)->where('batch', $list->batch)->sum('margin');
                    $purchaesNetRate = PurchesDetails::whereIn('user_id', $allUserId)->where('iteam_id', $list->item_id)->where('batch', $list->batch)->sum('net_rate');

                    $salesDetails[$key]['id'] = isset($list->id) ? $list->id : "";
                    $salesDetails[$key]['iss_check'] = isset($status) ? $status : "";
                    $salesDetails[$key]['total_stock'] = isset($batchStock) ? (string)$batchStock : '';
                    $salesDetails[$key]['item_id'] = isset($list->item_id) ? $list->item_id : "";
                    $salesDetails[$key]['iteam_name'] = isset($iteamName->iteam_name) ? $iteamName->iteam_name : "";
                    $salesDetails[$key]['front_photo'] =  isset($iteamName->front_photo) ? asset('/public/front_photo/' . $iteamName->front_photo) : "";
                    $salesDetails[$key]['user_id'] = isset($list->user_id) ? $list->user_id : "";
                    $salesDetails[$key]['qty'] = isset($list->qty) ? $list->qty : "";
                    $salesDetails[$key]['exp'] = isset($list->exp) ? $list->exp : "";
                    $salesDetails[$key]['mrp'] = isset($list->mrp) ? $list->mrp : "";
                    $salesDetails[$key]['random_number'] = isset($list->random_number) ? $list->random_number : "";
                    $salesDetails[$key]['gst'] = isset($list->gst) ? $list->gst : "";
                    $salesDetails[$key]['gst_name'] = isset($gstName->name) ? $gstName->name : "";
                    $salesDetails[$key]['net_rate'] = isset($list->net_rate) ? $list->net_rate : "";
                    $salesDetails[$key]['unit'] = isset($list->unit) ? $list->unit : "";
                    $salesDetails[$key]['batch'] = isset($list->batch) ? $list->batch : "";
                    $salesDetails[$key]['base'] = isset($list->base) ? $list->base : "";
                    $salesDetails[$key]['order'] = isset($list->order) ? $list->order : "";
                    $salesDetails[$key]['location'] = isset($list->location) ? $list->location : "";

                    if ($list->iss_check == '1') {
                        $totalAmount = isset($list->net_rate) ? $list->net_rate : 0;
                        $gstRate = isset($list->gst) ? $list->gst : "";
                        $gstTotal = ($totalAmount * $gstRate) / 100;
                        $gstAmount = (int)$totalAmount - (int)$gstTotal;
                        $totalGst =   (int)$totalAmount - (int)$gstAmount;
                        $resultGst = isset($gstName->name) ? $gstName->name : $list->gst;

                        $totalQty = isset($list->qty) ? $list->qty : "";
                        array_push($qtyData, $totalQty);
                        array_push($margin, $purchaesMargin);
                        array_push($withoutGst, abs($totalGst));
                        array_push($TotalGstData, $gstTotal);
                        array_push($netRate, $list->net_rate);
                        array_push($baseTotal, $list->base);
                        array_push($netRateMargin, $purchaesNetRate);
                        array_push($IteamMrp, $list->mrp);
                        array_push($countData, 1);
                    }
                }
            }

            $dataList['sales_item'] = $salesDetails;

            $dataList['sales_amount'] = (string)array_sum($netRate);
            $dataList['total_base'] = (string)array_sum($baseTotal);
            $dataList['total_net_rate'] = (string)array_sum($netRateMargin);


            $salesSgst = (string)array_sum($withoutGst);
            $dataList['sgst'] = round($salesSgst / 2);
            $dataList['cgst'] = round($salesSgst / 2);

            $totalRecordCount =  array_sum($countData) > 0 ? array_sum($countData) : 0;
            if ($totalRecordCount > 0) {

                $totalBase = (int)array_sum($baseTotal);
                $gstData = $totalRecordCount > 0 ? array_sum($TotalGstData) / $totalRecordCount : 0;

                $totalGst = $totalBase * $gstData / 100;

                $IteamMarginAmount = $totalRecordCount > 0 ? array_sum($margin) / $totalRecordCount : 0;

                $marginProfit = (int)array_sum($IteamMrp) - (int)array_sum($netRateMargin);

                $dataList['total_margin'] = (string)$IteamMarginAmount;
                $dataList['margin_net_profit'] = (string)$marginProfit;
                $dataList['total_qty'] = isset($qtyData) ? (string)array_sum($qtyData) : "";
                $dataList['total_gst'] = (string)round(array_push($TotalGstData), 0);
            } else {
                $dataList['total_qty'] = "";
                $dataList['margin_net_profit'] = "";
                $dataList['total_margin'] = "";
                $dataList['total_gst'] = "";
            }

            return $this->sendResponse($dataList, 'Sales Item Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("sales Return Amount Details api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function  salesDeletesHistory(Request $request)
    {
        try {

            $start_date = date('Y-m-d', strtotime($request->start_date));
            $end_date = date('Y-m-d', strtotime($request->end_date));

            //  IteamsModel::where('')->pluck('id')->toArray();

            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $salesModel = SalesModel::where('customer_id', $request->customer_id)
                ->whereBetween('bill_date', [$start_date, $end_date])
                ->whereIn('user_id', $allUserId)
                ->pluck('id')
                ->toArray();

            $deleteIteam = SalesFinalIteam::withTrashed()->whereIn('sales_id', $salesModel)->get();
            if (isset($deleteIteam)) {
                foreach ($deleteIteam as $listData) {
                    $listData->forceDelete();
                }
            }

            $salesDetails =  salesDetails::whereIn('sales_id', $salesModel)->get();

            if (isset($salesDetails)) {
                foreach ($salesDetails as $listData) {

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);


                    $batchData = BatchModel::where('batch_number', $listData->batch)->whereIn('user_id', $allUserId)->where('item_id', $listData->iteam_id)->first();

                    $editSalesReturn = new SalesFinalIteam;
                    $editSalesReturn->sales_id = $listData->sales_id;
                    $editSalesReturn->item_id = $listData->iteam_id;
                    $editSalesReturn->qty =  $listData->qty;
                    $editSalesReturn->exp = $listData->exp;
                    $editSalesReturn->gst = $listData->gst;
                    $editSalesReturn->amt = $listData->amt;
                    $editSalesReturn->mrp = $listData->mrp;
                    $editSalesReturn->unit = $listData->unit;
                    $editSalesReturn->batch = $listData->batch;
                    $editSalesReturn->base = $listData->base;
                    $editSalesReturn->order = $listData->order;
                    $editSalesReturn->location = $listData->location;
                    $editSalesReturn->net_rate = $listData->amt;
                    $editSalesReturn->random_number = $listData->random_number;
                    $editSalesReturn->status = '0';
                    $editSalesReturn->iss_check = '0';
                    $editSalesReturn->user_id = auth()->user()->id;
                    $editSalesReturn->save();
                }
            }

            return $this->sendResponse([], 'Sales Item History Successfully.');
        } catch (\Exception $e) {

            Log::info("sales Return Amount Details api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesReturnEditHistory(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Please Enter Random Number'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $iteamSalesData =  SalesReturnEdit::where('sales_id', $request->id)->get();
            if (isset($iteamSalesData)) {
                foreach ($iteamSalesData as $iteamSales) {
                    $iteamSales->delete();
                }
            }

            $salesDetailsData = SalesReturnDetails::where('sales_id', $request->id)->get();
            if (isset($salesDetailsData)) {
                foreach ($salesDetailsData as $listData) {
                    $userId = auth()->user()->id;
                    $salesIteam = new SalesReturnEdit;
                    $salesIteam->sales_id = $listData['sales_id'];
                    $salesIteam->random_number = $listData['random_number'];
                    $salesIteam->iteam_id = $listData['iteam_id'];
                    $salesIteam->user_id = $userId;
                    $salesIteam->qty = $listData['qty'];
                    $salesIteam->exp = $listData['exp'];
                    $salesIteam->gst = $listData['gst'];
                    $salesIteam->mrp = $listData['mrp'];
                    $salesIteam->unit = $listData['unit'];
                    $salesIteam->batch = $listData['batch'];
                    $salesIteam->base = $listData['base'];
                    $salesIteam->location = $listData['location'];
                    $salesIteam->net_rate = $listData['net_rate'];
                    $salesIteam->save();
                }
            }

            return $this->sendResponse('', 'Sale History Successfully');
        } catch (\Exception $e) {
            Log::info("sales Return Edit Details api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesReturnPdfDownloads(Request $request)
    {
        try {
            $html_url = route('generate.pdf.sales.retrun', $request->id);

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

            return $this->sendResponse(['pdf_url' => $pdfPublicUrl], 'PDF generated successfully');
        } catch (\Exception $e) {
            Log::info("Purchase Return update api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function generatePdfSalesRetrun(Request $request)
    {
        $salesData = SalesReturn::where('id', $request->id)->first();

        if (empty($salesData)) {
            return $this->sendError('Data Not Found');
        }

        $userIdData = User::find($salesData->user_id);
        $licenseData = LicenseModel::where('user_id', $userIdData->id)->first();
        $bankName = BankAccount::find($salesData->payment_name);
        $customerName = CustomerModel::find($salesData->customer_id);
        $doctorName = DoctorModel::find($salesData->doctor_id);
        $totalQty = $salesData->salesReturnGet->sum('qty');
        $totalCount = $salesData->salesReturnGet->count();

        $salesDetails = [
            'id' => $salesData->id ?? "",
            'license_20' => isset($licenseData->license_no) ? $licenseData->license_no : "",
            'license_21' => isset($licenseData->license_no_two) ? $licenseData->license_no_two : "",
            'fssai_no' => isset($licenseData->license_no_three) ? $licenseData->license_no_three : "",
            'phone_number' => $userIdData->phone_number ?? "",
            'logo' => isset($userIdData->pharmacy_logo) ? asset('/pharmacy_logo/' . $userIdData->pharmacy_logo) : '',
            'address' => $userIdData->address ?? "",
            'gst_pan' => $userIdData->gst_pan ?? "",
            'pan_card' => $userIdData->pan_card ?? "",
            'user_name' => $userIdData->name ?? "",
            'total_margin' => $salesData->margin ?? "",
            'total_net_rate' => $salesData->net_rate ?? "",
            'payment_id' => $salesData->payment_name ?? "",
            'total_gst' => $salesData->total_gst ?? "",
            'start_date' => $salesData->start_date ?? "",
            'end_date' => $salesData->end_date ?? "",
            'payment_name' => $bankName->bank_name ?? $salesData->payment_name,
            'round_off' => $salesData->round_off ?? "",
            'bill_date' => $salesData->date ?? "",
            'customer_id' => $salesData->customer_id ?? "",
            'doctor_id' => $salesData->doctor_id ?? "",
            'bill_no' => $salesData->bill_no ?? "",
            'customer_name' => $customerName->name ?? "",
            'customer_number' => $customerName->phone_number ?? "",
            'doctor_name' => $doctorName->name ?? "",
            'doctor_phone_number' => $doctorName->phone_number ?? "",
            'mrp_total' => $salesData->mrp_total ?? "",
            'customer_address' => $customerName->address ?? "-",
            'total_discount' => $salesData->total_discount ?? "",
            'net_amount' => isset($salesData->net_amount) ? 'Rs. ' . (string)round($salesData->net_amount, 2) : "",
            'owner_name' => $salesData->owner_name ?? "",
            'pickup' => $salesData->pickup ?? "",
            'given_amount' => isset($salesData->given_amount) ? (string)round($salesData->given_amount, 2) : "",
            'due_amount' => isset($salesData->due_amount) ? (string)round($salesData->due_amount, 2) : "",
            'sgst' => $salesData->sgst ?? "",
            'cgst' => $salesData->cgst ?? "",
            'igst' => $salesData->igst ?? "",
            'total_qty' => $totalQty,
            'total_gst' =>  $salesData->total_gst ?? "",
            'total_base' =>  $salesData->total_base ?? "",
            'other_amount' =>  $salesData->adjustment_amount ?? "",
            'total_iteam' => $totalCount,
        ];

        $iteamQty = [];
        $iteamGst = [];
        $totalBase = [];

        if ($salesData->salesReturnGet) {
            foreach ($salesData->salesReturnGet as $key => $list) {

                $salesDetails['sales_item'][$key] = [
                    'id' => $list->id ?? "",
                    'iteam_name' => $list->getIteamName->iteam_name ?? "",
                    'front_photo' => isset($list->getIteamName->front_photo) ? asset('/public//front_photo/' . $list->getIteamName->front_photo) : "",
                    'user_id' => $list->user_id ?? "",
                    'mrp' => isset($list->mrp) ? (string)round($list->mrp, 2) : "",
                    'net_rate' => number_format($list->net_rate, 0, '', ',') ?? "",
                    'unit' => $list->unit ?? "",
                    'batch' => $list->batch ?? "",
                    'base' => $list->base ?? "",
                    'location' => $list->location ?? "",
                    'exp' => $list->exp ?? "",
                    'qty' => $list->qty ?? "",
                    'gst' => $list->gst ?? "",
                    'random_number' => $list->random_number ?? "",
                ];

                $iteamQty[] = $list->qty ?? 0;
                $iteamGst[] = $list->gst ?? 0;
                $totalBase[] = $list->base ?? 0;
            }
        }

        return view('sales_return', compact('salesDetails')); // Render the view into HTML
    }

    public function salesReturnIteamSelect(Request $request)
    {
        try {

            if ($request->type == '0') {
                $salesData = SalesFinalIteam::where('id', $request->id)->first();
                if (isset($salesData)) {
                    if ($salesData->iss_check == '0') {
                        $salesData->iss_check = '1';
                    } else if ($salesData->iss_check == '1') {
                        $salesData->iss_check = '0';
                    }
                    $salesData->update();
                }
            } else {

                $salesData = SalesReturnEdit::where('id', $request->id)->first();
                if (isset($salesData)) {
                    if ($salesData->isse_check == '0') {
                        $salesData->isse_check = '1';
                    } else if ($salesData->isse_check == '1') {
                        $salesData->isse_check = '0';
                    }
                    $salesData->update();
                }
            }
            return $this->sendResponse('', 'Sales Return Seleted Successfully');
        } catch (\Exception $e) {
            Log::info("sales Return Selected Details api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesReturnMultiplePdfDownloads(Request $request)
    {
        try {
            $html_url = route('multple.pdf.sales.retrun.dwonalod', ['user_id' => auth()->user()->id, 'start_date' => $request->start_date, 'end_date' => $request->end_date]);

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
            Log::info("Purchase Return Multiple PDF Generate" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function getSalesretrunMultpleGenratePdf($userId, $startDate, $endDate)
    {
        $staffGetData = User::where('create_by', $userId)->pluck('id')->toArray();
        $ownerGet = User::where('id', $userId)->pluck('create_by')->toArray();
        $userId = array($userId);
        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

        $salesReturnData = SalesReturn::whereBetween('date', [$startDate, $endDate])->whereIn('user_id', $allUserId)->orderBy('id', 'DESC')->get();

        $saleReturnDetails = [];

        foreach ($salesReturnData as $list) {
            $userIdData = User::find($list->user_id);
            $licenseData = LicenseModel::where('user_id', $userIdData->id)->first();
            $bankName = BankAccount::find($list->payment_name);
            $customerName = CustomerModel::find($list->customer_id);
            $doctorName = DoctorModel::find($list->doctor_id);
            $totalQty = $list->salesReturnGet->sum('qty');
            $totalCount = $list->salesReturnGet->count();

            $iteamQty = [];
            $iteamGst = [];
            $totalBase = [];

            if ($list->salesReturnGet) {
                foreach ($list->salesReturnGet as $key => $itemList) {
                    $salesReturnItemsDetails[$key] = [
                        'id' => $itemList->id ?? "",
                        'iteam_name' => $itemList->getIteamName->iteam_name ?? "",
                        'front_photo' => isset($itemList->getIteamName->front_photo) ? asset('/public/front_photo/' . $itemList->getIteamName->front_photo) : "",
                        'user_id' => $itemList->user_id ?? "",
                        'mrp' => isset($itemList->mrp) ? (string)round($itemList->mrp, 2) : "",
                        'net_rate' => $itemList->net_rate != 'Infinity' ? number_format($itemList->net_rate, 0, '', ',') : "",
                        'unit' => $itemList->unit ?? "",
                        'batch' => $itemList->batch ?? "",
                        'base' => $itemList->base ?? "",
                        'location' => $itemList->location ?? "",
                        'exp' => $itemList->exp ?? "",
                        'qty' => $itemList->qty ?? "",
                        'gst' => $itemList->gst ?? "",
                        'random_number' => $itemList->random_number ?? "",
                    ];

                    $iteamQty[] = $itemList->qty ?? 0;
                    $iteamGst[] = $itemList->gst ?? 0;
                    $totalBase[] = $itemList->base ?? 0;
                }
            }

            $saleReturnDetails[] = [
                'id' => $list->id ?? "",
                'license_20' => isset($licenseData->license_no) ? $licenseData->license_no : "",
                'license_21' => isset($licenseData->license_no_two) ? $licenseData->license_no_two : "",
                'fssai_no' => isset($licenseData->license_no_three) ? $licenseData->license_no_three : "",
                'phone_number' => $userIdData->phone_number ?? "",
                'logo' => isset($userIdData->pharmacy_logo) ? asset('/pharmacy_logo/' . $userIdData->pharmacy_logo) : '',
                'address' => $userIdData->address ?? "",
                'gst_pan' => $userIdData->gst_pan ?? "",
                'pan_card' => $userIdData->pan_card ?? "",
                'user_name' => $userIdData->name ?? "",
                'total_margin' => $list->margin ?? "",
                'total_net_rate' => $list->net_rate ?? "",
                'payment_id' => $list->payment_name ?? "",
                'total_gst' => $list->total_gst ?? "",
                'start_date' => $list->start_date ?? "",
                'end_date' => $list->end_date ?? "",
                'payment_name' => $bankName->bank_name ?? $list->payment_name,
                'round_off' => $list->round_off ?? "",
                'bill_date' => $list->date ?? "",
                'customer_id' => $list->customer_id ?? "",
                'doctor_id' => $list->doctor_id ?? "",
                'bill_no' => $list->bill_no ?? "",
                'customer_name' => $customerName->name ?? "",
                'customer_number' => $customerName->phone_number ?? "",
                'doctor_name' => $doctorName->name ?? "",
                'doctor_phone_number' => $doctorName->phone_number ?? "",
                'mrp_total' => $list->mrp_total ?? "",
                'customer_address' => $customerName->address ?? "",
                'total_discount' => $list->total_discount ?? "",
                'net_amount' => isset($list->net_amount) ? (string)round($list->net_amount, 2) : "",
                'owner_name' => $list->owner_name ?? "",
                'pickup' => $list->pickup ?? "",
                'given_amount' => isset($list->given_amount) ? (string)round($list->given_amount, 2) : "",
                'due_amount' => isset($list->due_amount) ? (string)round($list->due_amount, 2) : "",
                'sgst' => $list->sgst ?? "",
                'cgst' => $list->cgst ?? "",
                'igst' => $list->igst ?? "",
                'total_qty' => $totalQty,
                'total_gst' =>  $list->total_gst ?? "",
                'total_base' =>  $list->total_base ?? "",
                'other_amount' =>  $list->adjustment_amount ?? "",
                'total_iteam' => $totalCount,
                'sales_return_items' => $salesReturnItemsDetails,
            ];
        }

        return view('sales_return_multiple_pdf', compact('saleReturnDetails'));
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
