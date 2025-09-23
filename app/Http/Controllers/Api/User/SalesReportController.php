<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\SalesModel;
use App\Models\salesDetails;
use App\Models\SalesReturn;
use App\Models\SalesReturnDetails;
use App\Models\IteamsModel;
use Carbon\Carbon;
use App\Models\PurchesModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerModel;
use App\Models\Distributer;

class SalesReportController extends ResponseController
{
    //this function use sales report genrate
    public function salesReport(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'type' => 'required'
            ], [
                'type.required' => "Enter Type",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            if ((isset($request->type)) && ($request->type == '0')) {
                $salesReturn = SalesModel::orderBy('id', 'DESC');
                if ($request->date) {
                    $salesReturn->whereDate('created_at', $request->date);
                }
                if ($request->tcs) {
                    $salesReturn->where('tcs', $request->tcs);
                }
                $salesReturn =  $salesReturn->get();
                $cessTotal  = SalesModel::sum('cess');
                $mrpTotal  = SalesModel::sum('mrp_total');
            } elseif ((isset($request->type)) && ($request->type == '1')) {
                $salesReturn = SalesReturn::orderBy('id', 'DESC');
                if ($request->date) {
                    $salesReturn->whereDate('created_at', $request->date);
                }
                if ($request->tcs) {
                    $salesReturn->where('tcs', $request->tcs);
                }
                $salesReturn =  $salesReturn->get();
                $cessTotal  = SalesReturn::sum('cess');
                $mrpTotal  = SalesReturn::sum('net_amount');
            }

            $dataDetails = [];
            if (isset($salesReturn)) {
                $dataDetails['sales'] = [];
                foreach ($salesReturn as $key => $list) {
                    $curtomerData = SalesModel::where('bill_no', $list->bill_no)->first();

                    $dataDetails['sales'][$key]['id'] = isset($list->id) ? $list->id : "";
                    $dataDetails['sales'][$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                    $dataDetails['sales'][$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date : $list->date;
                    if (isset($curtomerData->getUserName->name)) {
                        $dataDetails['sales'][$key]['customer_name'] = isset($curtomerData->getUserName) ? $curtomerData->getUserName->name : "";
                    } else {
                        $dataDetails['sales'][$key]['customer_name'] = isset($list->getUserName) ? $list->getUserName->name : "";
                    }
                    $dataDetails['sales'][$key]['cess'] = isset($list->cess) ? $list->cess : "";
                    $dataDetails['sales'][$key]['mrp_total'] = isset($list->mrp_total) ? round($list->mrp_total, 2) : "";
                }
                $dataDetails['cess_total'] = isset($cessTotal) ? (string)round($cessTotal, 2) : "";
                $dataDetails['net_amount'] = isset($mrpTotal) ? (string)round($mrpTotal, 2) : "";
            }
            return $this->sendResponse($dataDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Sales Report api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use sales bill report
    public function salesBillReport(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'type' => 'required'
            ], [
                'type.required' => "Enter Type",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            if ((isset($request->type)) && ($request->type == '0')) {
                $salesReturn = SalesModel::orderBy('id', 'DESC');
                if ($request->date) {
                    $salesReturn->whereDate('created_at', $request->date);
                }
                if ($request->tcs) {
                    $salesReturn->where('tcs', $request->tcs);
                }
                $salesReturn =  $salesReturn->get();
            } elseif ((isset($request->type)) && ($request->type == '1')) {
                $salesReturn = SalesReturn::orderBy('id', 'DESC');
                if ($request->date) {
                    $salesReturn->whereDate('created_at', $request->date);
                }
                if ($request->tcs) {
                    $salesReturn->where('tcs', $request->tcs);
                }
                $salesReturn =  $salesReturn->get();
            }

            $dataDetails = [];
            if (isset($salesReturn)) {
                $dataDetails['sales'] = [];
                foreach ($salesReturn as $key => $list) {
                    $curtomerData = SalesModel::where('bill_no', $list->bill_no)->first();

                    $dataDetails['sales'][$key]['id'] = isset($list->id) ? $list->id : "";
                    $dataDetails['sales'][$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                    $dataDetails['sales'][$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date : $list->date;
                    if (isset($curtomerData->getUserName->name)) {
                        $dataDetails['sales'][$key]['customer_name'] = isset($curtomerData->getUserName) ? $curtomerData->getUserName->name : "";
                    } else {
                        $dataDetails['sales'][$key]['customer_name'] = isset($list->getUserName) ? $list->getUserName->name : "";
                    }
                    $dataDetails['sales'][$key]['cess'] = isset($list->cess) ? $list->cess : "";

                    $dataDetails['sales'][$key]['sales_details'] = [];
                    if ($request->type == '0') {
                        // getSales
                        $sumTotal = $list->getSales->sum('amt');
                        foreach ($list->getSales as $keyData => $dataSales) {
                            $dataDetails['sales'][$key]['sales_details'][$keyData]['id'] = isset($dataSales->id) ?  $dataSales->id : "";
                            $dataDetails['sales'][$key]['sales_details'][$keyData]['iteam_name'] = isset($dataSales->getIteam) ? $dataSales->getIteam->iteam_name : "";
                            $dataDetails['sales'][$key]['sales_details'][$keyData]['amt'] = isset($dataSales->amt) ? $dataSales->amt : "";
                        }
                    } elseif ($request->type == '1') {
                        // salesReturnGet
                        $sumTotal = $list->getSales->sum('amount');
                        foreach ($list->salesReturnGet as $keyData => $dataSales) {
                            $dataDetails['sales'][$key]['sales_details'][$keyData]['id'] = isset($dataSales->id) ?  $dataSales->id : "";
                            $dataDetails['sales'][$key]['sales_details'][$keyData]['iteam_name'] = isset($dataSales->getIteamName) ? $dataSales->getIteamName->iteam_name : "";
                            $dataDetails['sales'][$key]['sales_details'][$keyData]['amount'] = isset($dataSales->amount) ? round($dataSales->amount, 2) : "";
                        }
                    }
                    $dataDetails['sales'][$key]['sum_total'] = isset($sumTotal) ? (string)$sumTotal : "";
                }
            }
            return $this->sendResponse($dataDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Sales Bill Report api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function monthlySalesOverview(Request $request)
    {
        try {

            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            // Validate the input format (YYYY-MM)
            $request->validate([
                'month_year' => 'required|date_format:m-Y',
            ]);

            // Extract year and month from the combined parameter
            $monthYear = $request->month_year;
            $year = Carbon::createFromFormat('m-Y', $monthYear)->year;
            $month = Carbon::createFromFormat('m-Y', $monthYear)->month;

            // Query to get the total sales amount
            $monthSale = SalesModel::whereMonth('bill_date', $month)
                ->whereYear('bill_date', $year)
                ->whereIn('user_id', $allUserId)
                ->sum('mrp_total');

            $salesMonthData = [];
            if ($monthYear) {
                // Query to get the total discount
                $salesTotalDiscount = SalesModel::whereMonth('bill_date', $month)
                    ->whereYear('bill_date', $year)
                    ->whereIn('user_id', $allUserId)
                    ->sum('dicount');

                // Query to get the total count of sales
                $totalCount = SalesModel::whereMonth('bill_date', $month)
                    ->whereYear('bill_date', $year)
                    ->whereIn('user_id', $allUserId)
                    ->count();

                // Create a Carbon instance from the month and year
                $date = Carbon::createFromDate($year, $month, 1);

                // Prepare the response data
                $salesMonthData['duration'] = $date->format('F-Y');
                $salesMonthData['total_amount'] = (string)$monthSale;
                $salesMonthData['total_discount'] = (string)$salesTotalDiscount;
                $netSales = $monthSale - $salesTotalDiscount;
                $salesMonthData['net_sales'] = (string)$netSales;
                $salesMonthData['count'] = (string)$totalCount;
            }

            // Send the response
            return $this->sendResponse($salesMonthData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Sales Bill Report api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function topSellingItems(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);


            $salesModel = salesDetails::orderBy('id', 'DESC');

            // Check if both start_date and end_date are set in the request
            if (isset($request->start_date) && isset($request->end_date)) {
                $startDate = $request->start_date;
                $endDate = $request->end_date; // Fixed this line
                $salesModel->whereHas('getSales', function ($query) use ($startDate, $endDate, $allUserId) {
                    $query->whereBetween('bill_date', [$startDate, $endDate])
                        ->whereIn('user_id', $allUserId);
                });
            }

            if (isset($request->company_name)) {
                $companyName = $request->company_name;
                $salesModel->whereHas('getIteam', function ($q) use ($companyName) {
                    $q->where('pharma_shop', $companyName);
                });
            }

            $QtyStock = salesDetails::select('qty')
                ->selectRaw('COUNT(*) as record_count')
                ->groupBy('qty')
                ->orderByDesc('record_count')
                ->pluck('qty')
                ->toArray();
            $salesModel->whereIn('qty', $QtyStock);

            //   $limit = '10';
            //   $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            //   $offset = ($page - 1) * $limit;
            //  $salesModel->limit($limit)->offset($offset);

            // Apply filter for the authenticated user's records and maximum qty

            $salesModel = $salesModel->whereIn('user_id', $allUserId)->pluck('iteam_id')->toArray();

            $iteamData = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->whereIn('id', $salesModel);

            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;

            $iteamData = $iteamData->limit($limit)->offset($offset)->get();

            $salesDetails = [];
            if (isset($iteamData)) {
                foreach ($iteamData as $key => $list) {
                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                    $SalesData = salesDetails::whereIn('user_id', $allUserId)->where('iteam_id', $list->id)->sum('qty');
                    $SalesDataAmount = salesDetails::whereIn('user_id', $allUserId)->where('iteam_id', $list->id)->sum('amt');
                    $salesDetails[$key]['id'] = isset($list->id) ?  $list->id : "";
                    $salesDetails[$key]['iteam_name'] = isset($list->iteam_name) ?  $list->iteam_name : "";
                    $salesDetails[$key]['company_name'] = isset($list->getPharma->company_name) ?  $list->getPharma->company_name : "";
                    $salesDetails[$key]['qty'] = isset($SalesData) ?  (string)$SalesData : "";
                    $salesDetails[$key]['amt'] = isset($SalesDataAmount) ?  (string)round($SalesDataAmount, 2) : "";
                    $salesDetails[$key]['uniqe_order'] = "";
                }
            }
            return $this->sendResponse($salesDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Top Sales Bill Itam Report api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function topCustomer(Request $request)
    {
        try {

            $userId = auth()->user()->id;
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $purchesModel = CustomerModel::whereIn('user_id', $allUserId)->pluck('id')->toArray();

            $salesQuery = SalesModel::whereIn('customer_id', $purchesModel);

            // Apply date filter if provided
            if (isset($request->start_date) && isset($request->end_date)) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                $salesQuery->whereBetween('bill_date', [$startDate, $endDate]);
            }

            // Group by distributor_id and get the total sum of net_amount for each group
            $topSales = $salesQuery->select('customer_id', DB::raw('SUM(net_amt) as total_net_amount'))
                ->whereIn('user_id', $allUserId)
                ->groupBy('customer_id')
                ->orderBy('total_net_amount', 'DESC')
                ->limit(10)
                ->get();


            $salesDetails = [];
            if (isset($topSales)) {

                foreach ($topSales as $key => $listData) {
                    $salesModelCount = SalesModel::orderBy('id', 'DESC')->where('user_id', auth()->user()->id)->where('customer_id', $listData->customer_id)->count();
                    $userData = CustomerModel::where('id', $listData->customer_id)->first();
                    $salesDetails[$key]['id'] = isset($userData->id) ?  $userData->id : "";
                    $salesDetails[$key]['customer_name'] = isset($userData->name) ?  $userData->name : "";
                    $salesDetails[$key]['customer_mobile'] = isset($userData->phone_number) ?  $userData->phone_number : "";
                    $salesDetails[$key]['net_amt'] = isset($listData->total_net_amount) ?  (string)round($listData->total_net_amount, 2) : "";
                    $salesDetails[$key]['order_count'] = isset($salesModelCount) ?  (string)$salesModelCount : "";
                    $salesDetails[$key]['uniqe_medicine'] = "";
                }
            }

            return $this->sendResponse($salesDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Top Sales Customer Report api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function topDistributor(Request $request)
    {
        try {

            $userId = auth()->user()->id;
            $userId = auth()->user()->id;
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $purchesModel = Distributer::whereIn('user_id', $allUserId)->pluck('id')->toArray();


            $salesQuery = PurchesModel::whereIn('distributor_id', $purchesModel);

            // Apply date filter if provided
            if (isset($request->start_date) && isset($request->end_date)) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                $salesQuery->whereBetween('bill_date', [$startDate, $endDate]);
            }

            // Group by distributor_id and get the total sum of net_amount for each group
            $topSales = $salesQuery->select('distributor_id', DB::raw('SUM(net_amount) as total_net_amount'))
                ->whereIn('user_id', $allUserId)
                ->groupBy('distributor_id')
                ->orderBy('total_net_amount', 'DESC')
                ->limit(5)
                ->get();

            $purchesData = [];
            if (isset($topSales)) {
                foreach ($topSales as $key => $list) {
                    $purchesCount = PurchesModel::where('distributor_id', $list->distributor_id)->where('user_id', auth()->user()->id)->count();

                    $userData = Distributer::where('id', $list->distributor_id)->first();
                    $purchesData[$key]['id'] = isset($userData->id) ? $userData->id : "";
                    $purchesData[$key]['distributor_name'] = isset($userData->name) ? $userData->name : "";
                    $purchesData[$key]['gst_in'] = isset($userData->gst) ? $userData->gst : "";
                    $purchesData[$key]['total'] = isset($list->total_net_amount) ? (string)round($list->total_net_amount, 2) : "";
                    $purchesData[$key]['count'] = isset($purchesCount) ? (string)$purchesCount : "";
                    $purchesData[$key]['uniqe_medicine'] = "";
                }
            }
            return $this->sendResponse($purchesData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Top Sales Customer Report api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
