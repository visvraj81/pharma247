<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\IteamsModel;
use App\Models\salesDetails;
use App\Models\BatchModel;
use App\Models\SalesModel;
use App\Models\PurchesModel;
use App\Models\PurchesDetails;
use App\Models\GstModel;
use Carbon\Carbon;
use App\Models\SalesReturnDetails;
use App\Models\UniteTable;
use App\Models\CompanyModel;
use App\Models\User;
use App\Models\SalesReturn;
use App\Models\CustomerModel;
use App\Models\Distributer;

class IteamMarginReportController extends ResponseController
{

    //this function use iteam report
    public function iteamBillMargin(Request $request)
    {
        try {
            if ($request->type == '0') {
                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $limit = 10;
                $salesData = salesDetails::orderBy('id', 'DESC')->whereIn('user_id', $allUserId);

                if ($request->start_date) {
                    $startDate = $request->start_date;
                    $endDate = $request->end_date;
                    $salesData = $salesData->whereHas('getSales', function ($query) use ($startDate, $endDate, $allUserId) {
                        $query->whereBetween('bill_date', [$startDate, $endDate])
                            ->whereIn('user_id', $allUserId);
                    });
                }
                if (isset($request->item_name)) {
                    $salesData->whereHas('getIteam', function ($query) use ($request) {
                        $query->where('iteam_name', 'like', '%' . $request->item_name . '%');
                    });
                }
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
                $salesData->limit($limit)->offset($offset);
                $salesData = $salesData->get();
            }

            if ($request->type == '1') {
                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $limit = 10;
                $salesData = SalesReturnDetails::orderBy('id', 'DESC')->whereIn('user_id', $allUserId);
                if ($request->start_date) {
                    $startDate = $request->start_date;
                    $endDate = $request->end_date;
                    $salesData->whereHas('getSales', function ($query) use ($startDate, $endDate, $allUserId) {
                        $query->whereBetween('date', [$startDate, $endDate])
                            ->whereIn('user_id', $allUserId);
                    });
                }
                if (isset($request->item_name)) {
                    $salesData->whereHas('getIteamName', function ($query) use ($request) {
                        $query->where('iteam_name', 'like', '%' . $request->item_name . '%');
                    });
                }
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
                $salesData->limit($limit)->offset($offset);
                $salesData = $salesData->get();
            }

            $totalSales = [];
            $totalPurches = [];
            $iteamDetails = [];
            $totalNetGst = [];
            $totalProfit = [];
            if (isset($salesData)) {
                if ($request->type == '0') {
                    foreach ($salesData as $key => $listData) {
                        $unit = UniteTable::where('id', $listData->unit)->first();
                        $userName  = User::where('id', $listData->user_id)->first();
                        $iteamDetails[$key]['id'] = $listData->id ?? "";
                        $iteamDetails[$key]['entry_by'] = $userName->name ?? "";
                        $iteamDetails[$key]['bill_no'] = isset($listData->getSales) ? $listData->getSales->bill_no : "";
                        $iteamDetails[$key]['bill_date'] = isset($listData->getSales) ? $listData->getSales->bill_date : "";
                        $iteamDetails[$key]['name'] = isset($listData->getIteam) ? $listData->getIteam->iteam_name : "";
                        $iteamDetails[$key]['category'] = isset($listData->getIteam->getCategory) ? $listData->getIteam->getCategory->category_name : "";
                        $iteamDetails[$key]['unit'] =  $listData->getIteam->packing_size ?? "";
                        $iteamDetails[$key]['company'] = $listData->getIteam->getPharma->company_name ?? "";
                        $iteamDetails[$key]['patient_name'] = isset($listData->getSales->getUserName) ? $listData->getSales->getUserName->name : "";
                        $salesData = salesDetails::where('iteam_id', $listData->iteam_id)->where('user_id', auth()->user()->id)->count();
                        $iteamDetails[$key]['sales_count'] = (string)$salesData;
                        $userId = auth()->user()->id;

                        $totalStock = BatchModel::where('item_id', $listData->iteam_id)->where('user_id', $userId)->sum('total_qty');
                        $iteamDetails[$key]['stock'] = (string)$totalStock;
                        $iteamDetails[$key]['mrp'] = $listData->mrp ? (string)$listData->mrp : "";

                        $salesAmount = salesDetails::where('iteam_id', $listData->iteam_id)->where('user_id', auth()->user()->id)->pluck('sales_id')->toArray();
                        $salesAmountData = SalesModel::whereIn('id', $salesAmount)->sum('net_amt');
                        $iteamDetails[$key]['sales_amount'] = (string)round($salesAmountData, 2);

                        $purchesData = PurchesDetails::where('user_id', auth()->user()->id)->where('iteam_id', $listData->iteam_id)->pluck('purches_id')->toArray();
                        $purchesAmountData = PurchesModel::whereIn('id', $purchesData)->sum('net_amount');
                        $iteamDetails[$key]['purches_amount'] = (string)round($purchesAmountData, 2);

                        $purchesGstData = PurchesDetails::where('user_id', auth()->user()->id)->where('iteam_id', $listData->iteam_id)->pluck('gst')->toArray();
                        $gst = [];
                        if (!empty($purchesGstData)) {
                            foreach ($purchesGstData as $gstId) {
                                $gstData = GstModel::where('id', $gstId)->first();
                                if ($gstData) {
                                    $gst[] = $gstData->rate; // Assuming 'rate' is the field storing GST value
                                }
                            }
                        }
                        $Gstsum = array_sum($gst);

                        $salesAmountGst = salesDetails::where('iteam_id', $listData->iteam_id)->where('user_id', auth()->user()->id)->sum('gst');

                        $netGstData = abs($salesAmountGst) - abs($Gstsum);
                        $amountData = $salesAmountData - $purchesAmountData;
                        $netProfit = $amountData - $netGstData;

                        $iteamDetails[$key]['net_gst'] =   (string)$netGstData;
                        $iteamDetails[$key]['net_profit'] =  (string)$netProfit;
                        array_push($totalSales, $salesAmountData);
                        array_push($totalPurches, $purchesAmountData);
                        array_push($totalNetGst, $netGstData);
                        array_push($totalProfit, $netProfit);
                    }
                }

                if ($request->type == '1') {
                    foreach ($salesData as $key => $listData) {
                        $unit = UniteTable::where('id', $listData->unit)->first();
                        $userName  = User::where('id', $listData->user_id)->first();

                        $customerName  = CustomerModel::where('id', $listData->getSales->customer_id)->first();

                        $iteamDetails[$key]['id'] = $listData->id ?? "";
                        $iteamDetails[$key]['entry_by'] = $userName->name ?? "";
                        $iteamDetails[$key]['bill_no'] = isset($listData->getSales) ? $listData->getSales->bill_no : "";
                        $iteamDetails[$key]['name'] = $listData->getIteamName->iteam_name ?? "";
                        $iteamDetails[$key]['category'] = $listData->getIteamName->getCategory->category_name ?? "";

                        $iteamDetails[$key]['unit'] =  $listData->getIteamName->packing_size ?? "";
                        $iteamDetails[$key]['company'] = $listData->getIteamName->getPharma->company_name ?? "";
                        $iteamDetails[$key]['bill_date'] = isset($listData->getSales) ? $listData->getSales->date : "";
                        $iteamDetails[$key]['patient_name'] = isset($customerName->name) ? $customerName->name : "";
                        $salesData = salesDetails::where('iteam_id', $listData->iteam_id)->where('user_id', auth()->user()->id)->count();
                        $iteamDetails[$key]['sales_count'] = (string)$salesData;
                        $userId = auth()->user()->id;

                        $totalStock = BatchModel::where('item_id', $listData->iteam_id)->where('user_id', $userId)->sum('total_qty');
                        $iteamDetails[$key]['stock'] = (string)$totalStock;
                        $iteamDetails[$key]['mrp'] = $listData->mrp ? (string)$listData->mrp : "";

                        $salesAmount = salesDetails::where('iteam_id', $listData->iteam_id)->where('user_id', auth()->user()->id)->pluck('sales_id')->toArray();
                        $salesAmountData = SalesModel::whereIn('id', $salesAmount)->sum('net_amt');
                        $iteamDetails[$key]['sales_amount'] = (string)round($salesAmountData, 2);

                        $purchesData = PurchesDetails::where('user_id', auth()->user()->id)->where('iteam_id', $listData->iteam_id)->pluck('purches_id')->toArray();
                        $purchesAmountData = PurchesModel::whereIn('id', $purchesData)->sum('net_amount');
                        $iteamDetails[$key]['purches_amount'] = (string)round($purchesAmountData, 2);

                        $purchesGstData = PurchesDetails::where('user_id', auth()->user()->id)->where('iteam_id', $listData->iteam_id)->pluck('gst')->toArray();
                        $gst = [];
                        if (!empty($purchesGstData)) {
                            foreach ($purchesGstData as $gstId) {
                                $gstData = GstModel::where('id', $gstId)->first();
                                if ($gstData) {
                                    $gst[] = $gstData->rate; // Assuming 'rate' is the field storing GST value
                                }
                            }
                        }
                        $Gstsum = array_sum($gst);

                        $salesAmountGst = salesDetails::where('iteam_id', $listData->iteam_id)->where('user_id', auth()->user()->id)->sum('gst');

                        $netGstData = abs($salesAmountGst) - abs($Gstsum);
                        $amountData = $salesAmountData - $purchesAmountData;
                        $netProfit = $amountData - $netGstData;

                        $iteamDetails[$key]['net_gst'] =   (string)$netGstData;
                        $iteamDetails[$key]['net_profit'] =  (string)$netProfit;
                        array_push($totalSales, $salesAmountData);
                        array_push($totalPurches, $purchesAmountData);
                        array_push($totalNetGst, $netGstData);
                        array_push($totalProfit, $netProfit);
                    }
                }
            }

            $dataMarginReport['bill_margin_report'] = $iteamDetails;
            $dataMarginReport['total_sales'] = array_sum($totalSales);
            $dataMarginReport['total_purches'] = array_sum($totalPurches);
            $dataMarginReport['total_net_gst'] = array_sum($totalNetGst);
            $dataMarginReport['total_net_profit'] = array_sum($totalProfit);

            return $this->sendResponse($dataMarginReport, 'Item Vise Report Successfully');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Sales Iteam Bill Report api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function salesRegister(Request $request)
    {
        try {


            if ($request->type == '0') {
                $limit = 10;
                $salesData = SalesModel::orderBy('id', 'DESC');
                if ($request->date) {

                    $salesData->where('bill_date', 'like', '%' . $request->date . '%');
                }
                if ($request->payment_name) {

                    $salesData->where('payment_name', 'like', '%' . $request->payment_name . '%');
                }

                if (isset($request->search)) {
                    $search  = $request->search;
                    $salesData->whereHas('getUserName', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
                }

                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
                $salesData->limit($limit)->offset($offset);
                $salesData = $salesData->where('user_id', auth()->user()->id)->get();
            }

            if ($request->type == '1') {
                $limit = 10;
                $salesData = SalesReturn::orderBy('id', 'DESC');
                if ($request->date) {

                    $salesData->where('date', 'like', '%' . $request->date . '%');
                }
                if ($request->payment_name) {

                    $salesData->where('payment_name', 'like', '%' . $request->payment_name . '%');
                }
                if (isset($request->search)) {
                    $search  = $request->search;
                    $salesData->whereHas('getUserName', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
                }

                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
                $salesData->limit($limit)->offset($offset);
                $salesData = $salesData->where('user_id', auth()->user()->id)->get();
            }

            $salesDetiles = [];
            $totalAmount = [];
            $totalDisocunt = [];
            $totalsagst = [];
            $totalcgst = [];
            $totaligst = [];
            $totalNetAmount = [];
            if (isset($salesData)) {
                if ($request->type == '0') {
                    foreach ($salesData as $key => $list) {
                        $customerName  = CustomerModel::where('id', $list->customer_id)->first();
                        $salesDetiles[$key]['id'] = isset($list->id) ? $list->id : "";
                        $salesDetiles[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                        $salesDetiles[$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date : "";
                        $salesDetiles[$key]['name'] = isset($customerName->name) ? $customerName->name : "";
                        $salesDetiles[$key]['phone_number'] = isset($customerName->phone_number) ? $customerName->phone_number : "";
                        $salesDetiles[$key]['gross'] = isset($list->mrp_total) ? $list->mrp_total : "";
                        $salesDetiles[$key]['disocunt'] = isset($list->dicount) ? $list->dicount : "";
                        $salesDetiles[$key]['sgst'] = isset($list->sgst) ? $list->sgst : "";
                        $salesDetiles[$key]['cgst'] = isset($list->cgst) ? $list->cgst : "";
                        $salesDetiles[$key]['igst'] =  $list->igst != 'undefined' ? $list->igst : "";
                        $salesDetiles[$key]['net_amt'] =  $list->net_amt != null ? $list->net_amt : "";
                        array_push($totalAmount, $list->mrp_total);
                        array_push($totalDisocunt, $list->dicount);
                        array_push($totalsagst, $list->sgst);
                        array_push($totalcgst, $list->cgst);
                        array_push($totaligst, $list->igst);
                        array_push($totalNetAmount, $list->net_amt);
                    }
                }

                if ($request->type == '1') {
                    foreach ($salesData as $key => $list) {
                        $customerName  = CustomerModel::where('id', $list->customer_id)->first();
                        $salesDetiles[$key]['id'] = isset($list->id) ? $list->id : "";
                        $salesDetiles[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                        $salesDetiles[$key]['bill_date'] = isset($list->date) ? $list->date : "";
                        $salesDetiles[$key]['name'] = isset($customerName->name) ? $customerName->name : "";
                        $salesDetiles[$key]['phone_number'] = isset($customerName->phone_number) ? $customerName->phone_number : "";
                        $salesDetiles[$key]['gross'] = isset($list->mrp_total) ? $list->mrp_total : "";
                        $salesDetiles[$key]['disocunt'] = isset($list->total_discount) ? $list->total_discount : "";
                        $salesDetiles[$key]['sgst'] = isset($list->sgst) ? $list->sgst : "";
                        $salesDetiles[$key]['cgst'] = isset($list->cgst) ? $list->cgst : "";
                        $salesDetiles[$key]['igst'] =  $list->igst != 'undefined' ? $list->igst : "";
                        $salesDetiles[$key]['net_amt'] =  $list->net_amount != null ? round($list->net_amount, 2) : "";
                        array_push($totalAmount, $list->mrp_total);
                        array_push($totalDisocunt, $list->dicount);
                        array_push($totalsagst, $list->sgst);
                        array_push($totalcgst, $list->cgst);
                        array_push($totaligst, $list->igst);
                        array_push($totalNetAmount, $list->net_amount);
                    }
                }
            }

            $dataDetails['sales_registere'] = $salesDetiles;
            $dataDetails['gross_total'] = array_sum($totalAmount);
            $dataDetails['discount'] = array_sum($totalDisocunt);
            $dataDetails['sgst'] = array_sum($totalsagst);
            $dataDetails['cgst'] = array_sum($totalcgst);
            $dataDetails['igst'] = array_sum($totaligst);
            $dataDetails['net_amount'] = array_sum($totalNetAmount);

            return $this->sendResponse($dataDetails, 'Sales Resgiter Successfully');
        } catch (\Exception $e) {
            Log::info("GST Sales Regsiter api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // //this function use expiry report
    // public function expiryReport(Request $request)
    // {
    //      try{

    //         $purchesData = PurchesModel::orderBy('id', 'DESC');
    //         if(isset($request->start_date))
    //         {
    //             $start_date = $request->start_date;
    //             $end_date = $request->end_date;
    //             $purchesData->whereBetween('created_at', [$start_date, $end_date]);
    //         }
    //         $purchesData = $purchesData->get();

    //         $dataPurches = [];
    //         if(isset($purchesData))
    //         {
    //                 foreach($purchesData as $keyValue => $list)
    //                 {
    //                     $purcheQty = PurchesDetails::where('purches_id',$list->id)->sum('qty');
    //                     $dataPurches[$keyValue]['id'] = isset($list->id) ? $list->id :"";
    //                     $dataPurches[$keyValue]['distributor'] = isset($list->getUser) ? $list->getUser->name :"";
    //                     $dataPurches[$keyValue]['total_items'] = $list->getPurchesDetails->count();
    //                     $dataPurches[$keyValue]['total_qty'] = (string)$purcheQty;
    //                     $dataPurches[$keyValue]['ptr_total'] = isset($list->ptr_total) ? $list->ptr_total :"";
    //                 }
    //         }
    //         return $this->sendResponse($dataPurches, 'Data Fetch Successfully');
    //      } catch (\Exception $e) {
    //         Log::info("Sales Iteam Expiry Report api" . $e->getMessage());
    //         return $e->getMessage();
    //     }
    // }

    // //this function use expiry iteam
    // public function expiryIteamReport(Request $request)
    // {
    //     try{

    //         $purchesData = IteamsModel::orderBy('id', 'DESC');
    //         if(isset($request->start_date))
    //         {
    //             $start_date = $request->start_date;
    //             $end_date = $request->end_date;
    //             $purchesData->whereBetween('created_at', [$start_date, $end_date]);
    //         }
    //         $purchesData = $purchesData->get();

    //         $purchesDetails = [];
    //         if(isset($purchesData))
    //         {
    //                 foreach($purchesData as $key => $list)
    //                 {
    //                     $purchesDetails[$key]['id'] = isset($list->id) ? $list->id :"";
    //                     $purchesDetails[$key]['iteam_name'] = isset($list->iteam_name) ? $list->iteam_name :"";
    //                     $purchesDetails[$key]['category'] = isset($list->getCategory) ? $list->getCategory->category_name :"";
    //                     $purchesDetails[$key]['stock'] = isset($list->stock) ? $list->stock :"";
    //                     $purchesDetails[$key]['location'] = isset($list->location) ? $list->location :"";
    //                 }
    //         }
    //         return $this->sendResponse($purchesDetails, 'Data Fetch Successfully');
    //     } catch (\Exception $e) {
    //         Log::info("Expiry Report Iteam api" . $e->getMessage());
    //         return $e->getMessage();
    //     }
    // }
}
