<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\SalesModel;
use App\Models\SalesReturn;
use App\Models\salesDetails;
use App\Models\SalesReturnDetails;
use App\Models\PurchesModel;
use Carbon\Carbon;
use App\Models\Distributer;
use App\Models\BatchModel;
use App\Models\PurchesReturn;
use App\Models\PurchesPaymentDetails;
use App\Models\PurchesDetails;
use App\Models\Banner;
use App\Models\IteamsModel;
use App\Models\Setting;
use App\Models\CustomerModel;
use App\Models\RoyaltyPoint;
use App\Models\PatientsOrder;

class DashboardController extends ResponseController
{
    public function dashbordData(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $endDate = Carbon::now()->format('Y-m-d');
            $userId = auth()->user()->id;

            $dataDetails['chart'] = [];

            // Today's data
            $todayStartDate = $endDate;
            $dataDetails['chart'][] = array_merge(
                ['title' => 'Today'],
                $this->getDataForPeriod($todayStartDate, $endDate, $userId)
            );

            // Last week's data
            $lastWeekStartDate = Carbon::now()->subDays(7)->format('Y-m-d');
            $dataDetails['chart'][] = array_merge(
                ['title' => 'Last Week'],
                $this->getDataForPeriod($lastWeekStartDate, $endDate, $userId)
            );

            // Last month's data
            $lastMonthStartDate = Carbon::now()->subDays(30)->format('Y-m-d');
            $dataDetails['chart'][] = array_merge(
                ['title' => 'Last Month'],
                $this->getDataForPeriod($lastMonthStartDate, $endDate, $userId)
            );

            // Last two months' data
            $lastTwoMonthStartDate = Carbon::now()->subDays(60)->format('Y-m-d');
            $dataDetails['chart'][] = array_merge(
                ['title' => 'Last Two Month'],
                $this->getDataForPeriod($lastTwoMonthStartDate, $endDate, $userId)
            );

            $distributorId = PurchesModel::whereIn('user_id', $allUserId)
                ->orderBy('total_amount', 'DESC')
                ->pluck('distributor_id')
                ->toArray();

            $distributerList = Distributer::whereIn('id', $distributorId)
                ->where('role', '4')
                ->orderBy('id', 'DESC')
                ->take(5)
                ->get();

            $dataDetails['top_distributor'] = [];

            if (isset($distributerList)) {
                foreach ($distributerList as $key => $list) {
                    $purchesData = PurchesModel::where('distributor_id', $list->id)->sum('total_amount');

                    $dataDetails['top_distributor'][$key]['id'] = $list->id ?? "";
                    $dataDetails['top_distributor'][$key]['name'] = $list->name ?? "";
                    $dataDetails['top_distributor'][$key]['gst_number'] = $list->gst ?? "";
                    $dataDetails['top_distributor'][$key]['due_amount'] = (float) $purchesData;
                }

                // Sort by due_amount descending
                usort($dataDetails['top_distributor'], function ($a, $b) {
                    return $b['due_amount'] <=> $a['due_amount'];  // Descending order
                });

                // Optional: Convert due_amount to string if needed
                foreach ($dataDetails['top_distributor'] as $k => $v) {
                    $dataDetails['top_distributor'][$k]['due_amount'] = (string) $v['due_amount'];
                }
            }

            $salesDetailsTotal  = SalesModel::whereIn('user_id', $allUserId)->orderBy('net_amt', 'DESC')->pluck('customer_id')->toArray();

            $customerList = CustomerModel::orderBy('id', 'DESC')
                ->whereIn('id', $salesDetailsTotal)
                ->take(3)
                ->get();

            $dataDetails['top_customer'] = [];
            if (isset($customerList)) {
                foreach ($customerList as $key => $listData) {
                    $salesDetailsTotal = SalesModel::where('customer_id', $listData->id)->sum('mrp_total');
                    $salesreturnDetailsTotal = SalesReturn::where('customer_id', $listData->id)->sum('net_amount');

                    $dataDetails['top_customer'][$key]['id'] = $listData->id ?? "";
                    $dataDetails['top_customer'][$key]['name'] = $listData->name ?? "";
                    $dataDetails['top_customer'][$key]['mobile'] = $listData->phone_number ?? "";
                    $dataDetails['top_customer'][$key]['balance'] = (float) ($salesDetailsTotal - $salesreturnDetailsTotal);
                }

                // Sort by balance descending
                usort($dataDetails['top_customer'], function ($a, $b) {
                    return $b['balance'] <=> $a['balance']; // Descending order
                });

                // Optional: Convert balance back to string if needed
                foreach ($dataDetails['top_customer'] as $k => $v) {
                    $dataDetails['top_customer'][$k]['balance'] = (string) $v['balance'];
                }
            }

            $dataDetails['purches'] = [];
            if ($request->type == '0') {
                $userId = auth()->user()->id;
                $endDate = Carbon::now()->format('Y-m-d');
                $purchesData  = PurchesModel::whereIn('user_id', $allUserId)->take(5)->orderBy('id', 'DESC');
                if ($request->bill_day == 'today') {
                    $startDate = $endDate;
                    $purchesData->whereBetween('bill_date', [$startDate, $endDate])->whereIn('user_id', $allUserId);
                }
                if ($request->bill_day == 'yesterday') {
                    $startDate = Carbon::now()->subDays(1)->format('Y-m-d');
                    $purchesData->whereBetween('bill_date', [$startDate, $endDate])->whereIn('user_id', $allUserId);
                }
                if ($request->bill_day == '7_day') {
                    $startDate = Carbon::now()->subDays(7)->format('Y-m-d');
                    $purchesData->whereBetween('bill_date', [$startDate, $endDate])->whereIn('user_id', $allUserId);
                }
                if ($request->bill_day == '30_day') {
                    $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
                    $purchesData->whereBetween('bill_date', [$startDate, $endDate])->whereIn('user_id', $allUserId);
                }
                $purchesData = $purchesData->get();

                $dataDetails['purches'] = [];
                if (isset($purchesData)) { // Checking if there are any results
                    foreach ($purchesData as $key => $list) {
                        $distributorData = Distributer::where('id', $list->distributor_id)->first();
                        $userIdData = User::where('id', $list->user_id)->first();
                        $TotalAmount = PurchesDetails::where('purches_id', $list->id)->sum('amount');
                        $dataDetails['purches'][$key]['id'] = $list->id;
                        $dataDetails['purches'][$key]['total_amount'] = (string)$TotalAmount;
                        $dataDetails['purches'][$key]['name'] = isset($distributorData->name) ? $distributorData->name : "";
                        $dataDetails['purches'][$key]['phone_number'] = isset($distributorData->phone_number) ? $distributorData->phone_number : "";
                    }
                }
            }

            $dataDetails['sales'] = [];
            if ($request->type == '1') {
                $userId = auth()->user()->id;
                $endDate = Carbon::now()->format('Y-m-d');
                $salesData = SalesModel::whereIn('user_id', $allUserId)->take(5)->orderBy('id', 'DESC');
                if ($request->bill_day == 'today') {
                    $startDate = $endDate;
                    $salesData->whereBetween('bill_date', [$startDate, $endDate])->whereIn('user_id', $allUserId);
                }
                if ($request->bill_day == 'yesterday') {
                    $startDate = Carbon::now()->subDays(1)->format('Y-m-d');
                    $salesData->whereBetween('bill_date', [$startDate, $endDate])->whereIn('user_id', $allUserId);
                }
                if ($request->bill_day == '7_day') {
                    $startDate = Carbon::now()->subDays(7)->format('Y-m-d');
                    $salesData->whereBetween('bill_date', [$startDate, $endDate])->whereIn('user_id', $allUserId);
                }
                if ($request->bill_day == '30_day') {
                    $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
                    $salesData->whereBetween('bill_date', [$startDate, $endDate])->whereIn('user_id', $allUserId);
                }
                $salesData = $salesData->get();

                $dataDetails['sales'] = [];
                if (isset($salesData)) {
                    foreach ($salesData as $key => $list) {
                        $dataDetails['sales'][$key]['id'] = isset($list->id) ? $list->id : '';
                        $dataDetails['sales'][$key]['name'] = isset($list->getUserName->name) ? $list->getUserName->name : '';
                        $dataDetails['sales'][$key]['phone_number'] = isset($list->getUserName->phone_number) ? $list->getUserName->phone_number : '';
                        $dataDetails['sales'][$key]['total_amount'] = isset($list->mrp_total) ? $list->mrp_total : '';
                      	// $dataDetails['sales'][$key]['total_amount'] = isset($list->net_amt) ? $list->net_amt : '';
                    }
                }
            }
          
          	// $patientOrderPickupData = PatientsOrder::where('chemist_id', auth()->user()->id)->where('delivery_status','0')->latest()->take(3)->get();
            // dd($patientOrderPickupData);

            $salesDataPikup = SalesModel::whereIn('user_id', $allUserId)
                ->where('pickup', 'Pickup')
                ->orderBy('id', 'DESC')
                ->take(3)
                ->get();

            // If the first set is empty, fetch the second set
            if ($salesDataPikup->isEmpty()) {
                $salesDataPikup = SalesModel::whereIn('user_id', $allUserId)
                    ->where('pickup', '1')
                    ->orderBy('id', 'DESC')
                    ->take(3)
                    ->get();
            }

            $dataDetails['pickup'] = [];
            if (isset($salesDataPikup)) {
                foreach ($salesDataPikup as $key => $list) {
                    $dataDetails['pickup'][$key]['id'] = isset($list->id) ? $list->id : '';
                    $dataDetails['pickup'][$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : '';
                    $dataDetails['pickup'][$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date : '';
                    $dataDetails['pickup'][$key]['status'] = isset($list->status) ? $list->status : '';
                    $dataDetails['pickup'][$key]['name'] = isset($list->getUserName->name) ? $list->getUserName->name . ' ' . $list->getUserName->last_name : '';
                    $dataDetails['pickup'][$key]['mobile_numbr'] = isset($list->getUserName->phone_number) ? $list->getUserName->phone_number : '';
                    $dataDetails['pickup'][$key]['net_amt'] = isset($list->net_amt) ? (string)$list->net_amt : '';
                }
            }

            $salesDataPikup = SalesModel::whereIn('user_id', $allUserId)
                ->where('pickup', 'Delivery')
                ->orderBy('id', 'DESC')
                ->take(3)
                ->get();

            // If the first set is empty, fetch the second set
            if ($salesDataPikup->isEmpty()) {
                $salesDataPikup = SalesModel::whereIn('user_id', $allUserId)
                    ->where('pickup', '2')
                    ->orderBy('id', 'DESC')
                    ->take(3)
                    ->get();
            }

            $dataDetails['delivery'] = [];
            if (isset($salesDataPikup)) {
                foreach ($salesDataPikup as $key => $list) {
                    $dataDetails['delivery'][$key]['id'] = isset($list->id) ? $list->id : '';
                    $dataDetails['delivery'][$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : '';
                    $dataDetails['delivery'][$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date : '';
                    $dataDetails['delivery'][$key]['status'] = isset($list->status) ? $list->status : '';
                    $dataDetails['delivery'][$key]['name'] = isset($list->getUserName->name) ? $list->getUserName->name . ' ' . $list->getUserName->last_name : '';
                    $dataDetails['delivery'][$key]['mobile_numbr'] = isset($list->getUserName->phone_number) ? $list->getUserName->phone_number : '';
                    $dataDetails['delivery'][$key]['net_amt'] = isset($list->net_amt) ? (string)$list->net_amt : '';
                }
            }

            $dataDetails['banner'] = [];
            $bannerData  = Banner::get();
            if (isset($bannerData)) {
                foreach ($bannerData  as $key => $list) {
                    $dataDetails['banner'][$key] = isset($list->banner) ? asset('/public/image/' . $list->banner)  : '';
                }
            }

            $iteamData = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->orderBy('id', 'DESC');
            if ($request->expired == 'expired') {
                $currentDate = Carbon::now();
              	$expireDateFormat = $currentDate->format('d/y');

                $batchData = BatchModel::whereRaw("STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') < ?", [$currentDate])
                    ->whereIn('user_id', $allUserId)
                    ->whereNull('deleted_at')
                    ->pluck('item_id')
                    ->toArray();

                $iteamData->whereIn('id', $batchData);
              	// dd($currentDate->format('d/y'),$iteamData->get());
            }
            if ($request->expired == 'next_month') {
                $currentDate = Carbon::now();
                $startOfNextMonth = $currentDate->copy()->addMonth()->startOfMonth();

                $endOfNextMonth = $startOfNextMonth->copy()->endOfMonth();

                $batchData = BatchModel::whereRaw("STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') >= ? AND STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') <= ?", [$startOfNextMonth, $endOfNextMonth])
                    ->whereIn('user_id', $allUserId)

                    ->whereNull('deleted_at')
                    ->pluck('item_id')
                    ->toArray();

                $iteamData->whereIn('id', $batchData);
            }
            if ($request->expired == 'next_two_month') {
                $currentDate = Carbon::now();

                $startOfNextMonth = $currentDate->copy()->addMonth()->startOfMonth();

                $endOfTwoMonthsLater = $currentDate->copy()->addMonths(2)->endOfMonth();

                $batchData = BatchModel::select('item_id')
                    ->whereRaw("STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') >= ? AND STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') <= ?", [$startOfNextMonth, $endOfTwoMonthsLater])
                    ->whereIn('user_id', $allUserId)
                    ->whereNull('deleted_at')
                    ->groupBy('item_id')

                    ->pluck('item_id')
                    ->toArray();
                $iteamData->whereIn('id', $batchData);
            }

            if ($request->expired == 'next_three_month') {
                $currentDate = Carbon::now();

                $startOfNextMonth = $currentDate->copy()->addMonth()->startOfMonth();

                $endOfThreeMonthsLater = $currentDate->copy()->addMonths(3)->endOfMonth();

                $batchData = BatchModel::whereRaw("STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') >= ? AND STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') <= ?", [$startOfNextMonth, $endOfThreeMonthsLater])
                    ->whereIn('user_id', $allUserId)
                    ->whereNull('deleted_at')
                    ->pluck('item_id')
                    ->toArray();

                $iteamData->whereIn('id', $batchData);
            }
            $iteamData = $iteamData->take(4)->get();

            $dataDetails['expiring_iteam'] = [];

            if (isset($iteamData)) {
                foreach ($iteamData as $key => $list) {
                    $earliestBatch = BatchModel::whereIn('user_id', $allUserId)
                        ->where('item_id', $list->id)
                        ->orderByRaw("STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y')")
                        ->first();

                    $dataDetails['expiring_iteam'][$key]['id'] = isset($list->id) ? $list->id : '';
                    $dataDetails['expiring_iteam'][$key]['name'] = isset($list->iteam_name) ? $list->iteam_name : '';
                    $dataDetails['expiring_iteam'][$key]['qty'] = isset($earliestBatch->total_qty) ? (string)$earliestBatch->total_qty : '';
                    $dataDetails['expiring_iteam'][$key]['expiry'] = isset($earliestBatch->expiry_date) ? (string)$earliestBatch->expiry_date : '';
                }
            }

            $setting = Setting::first();
            $dataDetails['refer'] = [];
            $dataDetails['refer'] = isset($setting->image) ? asset('/public/uploads/students/' . $setting->image) : "";

            $totalPtr = BatchModel::whereIn('user_id', $allUserId)->sum('total_ptr');
            $totalMrp = BatchModel::whereIn('user_id', $allUserId)->sum('total_mrp');
          	// $totalPtrSum = PurchesDetails::whereIn('user_id', $allUserId)->sum('ptr');
          	$totals = PurchesDetails::whereIn('user_id', $allUserId)
              ->selectRaw('
                  SUM(COALESCE(qty, 0) * COALESCE(ptr, 0)) as totalSumPTR, 
                  SUM(COALESCE(qty, 0) * COALESCE(mrp, 0)) as totalSumMRP
              ')
              ->first();

            $totalSumPTR = $totals->totalSumPTR ?? "0";
            $totalSumMRP = $totals->totalSumMRP ?? "0";

          	$salesTotalAmount = SalesModel::whereIn('user_id', $allUserId)->sum('mrp_total');
          	$purchaseTotalAmount = PurchesModel::whereIn('user_id', $allUserId)->sum('net_amount');
          	$totalCustomers = CustomerModel::whereIn('user_id',$allUserId)->count();
          	$totalDistributors = Distributer::whereIn('user_id',$allUserId)->count();
          	$totalDistributorsShowCount = $totalDistributors - 1;
            $dataDetails['total_ptr'] = isset($totalSumPTR) ? (string)round($totalSumPTR, 2) : "";
            $dataDetails['total_mrp'] = isset($totalSumMRP) ? (string)round($totalSumMRP, 2) : "";
          	$dataDetails['total_sales'] = isset($salesTotalAmount) ? (string)round($salesTotalAmount, 2) : "";
          	$dataDetails['total_purchase'] = isset($purchaseTotalAmount) ? (string)round($purchaseTotalAmount, 2) : "";
          	$dataDetails['total_customers'] = isset($totalCustomers) ? (string)$totalCustomers : "";
          	$dataDetails['total_distributors'] = isset($totalDistributorsShowCount) ? (string)$totalDistributorsShowCount : "";

            $salesAmountPoint = SalesModel::whereIn('user_id', $allUserId)->get();

            $totalPercent = 0;
            if ($salesAmountPoint->isNotEmpty()) {
                foreach ($salesAmountPoint as $sales) {
                    // Loop through the RoyaltyPoint records to check if sales amount falls within the range
                    $royaltyPoints = RoyaltyPoint::where('user_id', auth()->user()->id)->get();

                    foreach ($royaltyPoints as $royalty) {
                        $salesAmount = $sales->net_amt;  // Assuming 'net_amt' is the sales bill amount

                        // Check if the sales amount is within the RoyaltyPoint range
                        if ($salesAmount >= $royalty->minimum && $salesAmount <= $royalty->maximum) {
                            Log::info("sales amount" . $salesAmount);
                            Log::info("sales minimum" . $royalty->minimum);
                            Log::info("sales maximum" . $royalty->maximum);
                            Log::info("sales percent" . $royalty->percent);
                            $totalPercent += $royalty->percent;
                        }
                    }
                }
            }

            $royaltiPoint = SalesModel::whereIn('user_id', $allUserId)->sum('roylti_point');
          	$todayLoyaltiPointTotal = SalesModel::whereIn('user_id', $allUserId)->where('bill_date',$endDate)->sum('today_loylti_point');
          	$allCustomerLoyaltiPointTotal = SalesModel::whereIn('user_id', $allUserId)->sum('today_loylti_point');

            // $dataDetails['loyalti_point_all_customer'] = isset($totalPercent)  ? (string) (round($totalPercent) - $royaltiPoint) : "";
          	$dataDetails['loyalti_point_all_customer'] = isset($allCustomerLoyaltiPointTotal) ? (string)($allCustomerLoyaltiPointTotal) : "";
            $dataDetails['loyalti_point_use_all_customer'] = isset($royaltiPoint) ? (string)round($royaltiPoint, 2) : "";
          	$dataDetails['today_loyalti_point_total'] = isset($todayLoyaltiPointTotal) ? (string)round($todayLoyaltiPointTotal, 2) : "";

            $userDataCount = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();

            $userStaffList = User::where('create_by', auth()->user()->id)->get();
          	// $userStaffList = User::whereIn('create_by', $userDataCount)->get();
          	// dd($userDataCount,$userStaffList);
            if (count($userStaffList) == 0) {
                $userStaffList = User::where('create_by', auth()->user()->id)->get();
            }
            $dataDetails['staff_overview'] = [];
            if (isset($userStaffList)) {
                foreach ($userStaffList as $key => $listData) {
                    if ($request->staff_overview_count == 0) {
                        $endDate = Carbon::now()->format('Y-m-d');
                        $countData = PurchesModel::where('user_id', $listData->id)->orderBy('id', 'DESC');
                        if ($request->staff_bill_day == 'today') {
                            $startDate = $endDate;
                            $countData->whereBetween('bill_date', [$startDate, $endDate])->where('user_id', $listData->id);
                        }
                        if ($request->staff_bill_day == 'yesterday') {
                            $startDate = Carbon::now()->subDays(1)->format('Y-m-d');
                            $countData->whereBetween('bill_date', [$startDate, $endDate])->where('user_id', $listData->id);
                        }
                        if ($request->staff_bill_day == '7_day') {
                            $startDate = Carbon::now()->subDays(7)->format('Y-m-d');
                            $countData->whereBetween('bill_date', [$startDate, $endDate])->where('user_id', $listData->id);
                        }
                        if ($request->staff_bill_day == '30_day') {
                            $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
                            $countData->whereBetween('bill_date', [$startDate, $endDate])->where('user_id', $listData->id);
                        }
                      	$countData = $countData->sum('net_amount');
                      	// $countData = $countData->get();
                      	// $countData = $countData->count();
                    } else if ($request->staff_overview_count == 1) {
                        $endDate = Carbon::now()->format('Y-m-d');
                        $countData = SalesModel::where('user_id', $listData->id)->orderBy('id', 'DESC');
                        if ($request->staff_bill_day == 'today') {
                            $startDate = $endDate;
                            $countData->whereBetween('bill_date', [$startDate, $endDate])->where('user_id', $listData->id);
                        }
                        if ($request->staff_bill_day == 'yesterday') {
                            $startDate = Carbon::now()->subDays(1)->format('Y-m-d');
                            $countData->whereBetween('bill_date', [$startDate, $endDate])->where('user_id', $listData->id);
                        }
                        if ($request->staff_bill_day == '7_day') {
                            $startDate = Carbon::now()->subDays(7)->format('Y-m-d');
                            $countData->whereBetween('bill_date', [$startDate, $endDate])->where('user_id', $listData->id);
                        }
                        if ($request->staff_bill_day == '30_day') {
                            $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
                            $countData->whereBetween('bill_date', [$startDate, $endDate])->where('user_id', $listData->id);
                        }
                      	$countData = $countData->sum('net_amt');
                      	// $countData = $countData->get();
                      	// $countData = $countData->count();
                    }

                    $dataDetails['staff_overview'][$key]['lable'] = isset($listData->name) ? $listData->name : "";
                    $dataDetails['staff_overview'][$key]['value'] = isset($countData) ? (string)$countData : "0";
                }
            }


            return $this->sendResponse($dataDetails, 'Dashboard Data Fetch Successfully.');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Dashboard Data API Error: " . $e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }

    public function getTotalsAndCounts($model, $dateField, $startDate, $endDate, $userId, $sumField)
    {

        $userid = auth()->user();
        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
        $userId = array(auth()->user()->id);
        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

        $total = $model::whereBetween($dateField, [$startDate, $endDate])
            ->whereIn('user_id', $allUserId)
            ->sum($sumField);

        $count = $model::whereBetween($dateField, [$startDate, $endDate])
            ->whereIn('user_id', $allUserId)
            ->count();

        return [
            'total' => (string)$total,
            'count' => (string)$count,
        ];
    }

    public function getDataForPeriod($startDate, $endDate, $userId)
    {
        $purchase = $this->getTotalsAndCounts(PurchesModel::class, 'bill_date', $startDate, $endDate, $userId, 'net_amount'); // Ensure 'net_amount' is correct
        $purchaseReturn = $this->getTotalsAndCounts(PurchesReturn::class, 'select_date', $startDate, $endDate, $userId, 'net_amount'); // Ensure 'net_amount' is correct
        $sales = $this->getTotalsAndCounts(SalesModel::class, 'bill_date', $startDate, $endDate, $userId, 'net_amt'); // Ensure 'net_amt' is correct
        $salesReturn = $this->getTotalsAndCounts(SalesReturn::class, 'date', $startDate, $endDate, $userId, 'net_amount'); // Ensure 'net_amount' is correct

        return [
            'purchase_total' => $purchase['total'],
            'purchase_count' => $purchase['count'],
            'purchase_return_total' => $purchaseReturn['total'],
            'purchase_return_count' => $purchaseReturn['count'],
            'sales_total' => $sales['total'],
            'sales_count' => $sales['count'],
            'sales_return_total' => $salesReturn['total'],
            'sales_return_count' => $salesReturn['count'],
        ];
    }
  
  	public function testingLoyaltiPoints()
    {
    	$totalLoyaltiPoints = SalesModel::where('user_id',auth()->user()->id)->sum('today_loylti_point');
      	$redeemLoyaltiPoints = SalesModel::where('user_id',auth()->user()->id)->sum('roylti_point');
      
      	$loyaltiPointsDetails = [];
      	$loyaltiPointsDetails['total_loyalti_points'] = isset($totalLoyaltiPoints) ? (string)$totalLoyaltiPoints : "0";
      	$loyaltiPointsDetails['total_redeem_loyalti_points'] = isset($redeemLoyaltiPoints) ? (string)$redeemLoyaltiPoints : "0";
      	
      	return $this->sendResponse($loyaltiPointsDetails, 'Loyalti Points Data Fetch Successfully.');
    }
}
