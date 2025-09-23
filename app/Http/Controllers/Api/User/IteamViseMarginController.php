<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use Illuminate\Support\Facades\Mail;
use App\Models\IteamsModel;
use App\Models\salesDetails;
use App\Models\BatchModel;
use App\Models\SalesModel;
use App\Models\PurchesModel;
use App\Models\PurchesDetails;
use App\Models\GstModel;
use Carbon\Carbon;
use App\Models\SalesReturnDetails;
use App\Models\SalesReturn;
use App\Models\User;

class IteamViseMarginController extends ResponseController
{
    public function iteamViseReport(Request $request)
    {
        try {


            $limit = 10;
            $iteamViseData = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->orderBy('id', 'DESC');
            if (isset($request->item_name)) {
                $iteamViseData->where('iteam_name', 'like', '%' . $request->item_name . '%');
            }

            if ((isset($request->search)) && ($request->search == '0') && (isset($request->start_date)) && (isset($request->end_date))) {
                $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date);
                $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date);

                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $salesItems = SalesDetails::whereHas('getSales', function ($query) use ($startDate, $endDate, $allUserId) {
                    $query->whereBetween('bill_date', [$startDate, $endDate])
                        ->whereIn('user_id', $allUserId);
                })->pluck('iteam_id')->toArray();

                $iteamViseData->whereIn('id', $salesItems);
            }

            if ((isset($request->search)) && ($request->search == '1') && (isset($request->start_date)) && (isset($request->end_date))) {
                $startDate = $request->start_date;
                $endDate =  $request->end_date;

                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $salesData = SalesReturnDetails::whereHas('getSales', function ($query) use ($startDate, $endDate, $allUserId) {

                    $query->whereBetween('date', [$startDate, $endDate])
                        ->whereIn('user_id', $allUserId);
                })->pluck('iteam_id')->toArray();

                $iteamViseData->whereIn('id', $salesData);
            }

            if ((isset($request->search)) && ($request->search == '0')) {
                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $salesIteam = salesDetails::whereIn('user_id', $allUserId)->pluck('iteam_id')->toArray();
                $iteamViseData->whereIn('id', $salesIteam);
            }
            if ((isset($request->search)) && ($request->search == '1')) {
                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $salesIteamReturn = SalesReturnDetails::whereIn('user_id', $allUserId)->pluck('iteam_id')->toArray();
                $iteamViseData->whereIn('id', $salesIteamReturn);
            }
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $iteamViseData->limit($limit)->offset($offset);
            $iteamViseData = $iteamViseData->get();

            $totalSales = [];
            $totalPurches = [];
            $iteamDetails = [];
            $totalNetGst = [];
            $totalProfit = [];
            if ($iteamViseData) {
                foreach ($iteamViseData as $key => $listData) {
                    $iteamDetails[$key]['id'] = $listData->id ?? "";
                    $iteamDetails[$key]['name'] = $listData->iteam_name ?? "";
                    $iteamDetails[$key]['category'] = $listData->getCategory->category_name ?? "";
                    $iteamDetails[$key]['unit'] = $listData->packing_size ?? "";
                    $iteamDetails[$key]['company'] = $listData->getPharma->company_name ?? "";

                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $salesData = salesDetails::where('iteam_id', $listData->id)->whereIn('user_id', $allUserId)->count();
                    $iteamDetails[$key]['sales_count'] = (string)$salesData;
                    $userId = auth()->user()->id;

                    $totalStock = BatchModel::where('item_id', $listData->id)->whereIn('user_id', $allUserId)->sum('total_qty');
                    $iteamDetails[$key]['stock'] = (string)$totalStock;
                    $iteamDetails[$key]['mrp'] = $listData->mrp ? (string)$listData->mrp : "";

                    $salesAmount = salesDetails::where('iteam_id', $listData->id)->whereIn('user_id', $allUserId)->pluck('sales_id')->toArray();
                    $salesAmountData = SalesModel::whereIn('id', $salesAmount)->sum('net_amt');
                    $iteamDetails[$key]['sales_amount'] = (string)round($salesAmountData, 2);

                    $purchesData = PurchesDetails::where('user_id', auth()->user()->id)->where('iteam_id', $listData->id)->pluck('purches_id')->toArray();
                    $purchesAmountData = PurchesModel::whereIn('id', $purchesData)->sum('net_amount');
                    $iteamDetails[$key]['purches_amount'] = (string)round($purchesAmountData, 2);

                    $purchesGstData = PurchesDetails::where('user_id', auth()->user()->id)->where('iteam_id', $listData->id)->pluck('gst')->toArray();
                    $gst = [];
                    if (!empty($purchesGstData)) {
                        foreach ($purchesGstData as $gstId) {
                            $gstData = GstModel::where('id', $gstId)->first();
                            if ($gstData) {
                                $gst[] = $gstData->name; // Assuming 'rate' is the field storing GST value
                            }
                        }
                    }
                    $Gstsum = array_sum($gst);

                    $salesAmountGst = salesDetails::where('iteam_id', $listData->id)->where('user_id', auth()->user()->id)->sum('gst');

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

            $dataMarginReport['iteam_margin_report'] = $iteamDetails;
            $dataMarginReport['total_sales'] = array_sum($totalSales);
            $dataMarginReport['total_purches'] = array_sum($totalPurches);
            $dataMarginReport['total_net_gst'] = array_sum($totalNetGst);
            $dataMarginReport['total_net_profit'] = array_sum($totalProfit);

            return $this->sendResponse($dataMarginReport, 'Item Vise Report Successfully');
        } catch (\Exception $e) {
            Log::info("Iteam Vise Report api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
