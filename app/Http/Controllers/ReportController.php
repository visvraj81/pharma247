<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchesModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\ResponseController;
use App\Models\PurchesReturn;
use App\Models\PurchesReturnDetails;
use App\Models\PurchesDetails;
use App\Models\PurchesPaymentDetails;
use App\Models\User;
use App\Models\PurchesPayment;
use App\Models\PaymentDetails;
use App\Models\SalesModel;
use App\Models\GstModel;
use Carbon\Carbon;
use App\Models\SalesReturnDetails;
use App\Models\UniteTable;
use App\Models\CompanyModel;
use App\Models\SalesReturn;
use App\Models\salesDetails;
use App\Models\IteamsModel;
use App\Models\BankAccount;
use DateTime;
use DatePeriod;
use DateInterval;
use App\Models\Distributer;
use App\Models\CustomerModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReportController extends ResponseController
{
    //this function use response purches
    public function resportPurches(Request $request)
    {
        try {
            if ((isset($request->type)) && ($request->type == '0')) {
                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $purchesData = PurchesModel::orderBy('id', 'DESC')->whereIn('user_id', $allUserId);

                if (isset($request->month_year)) {
                    $monthYear = $request->month_year;
                    $year = Carbon::createFromFormat('m-Y', $monthYear)->year;
                    $month = Carbon::createFromFormat('m-Y', $monthYear)->month;

                    $purchesData->whereMonth('bill_date', $month)
                        ->whereYear('bill_date', $year);
                }

                if ((isset($request->purches_type)) && ($request->purches_type == '0')) {
                    $purchesData->whereHas('getPurchesDetails', function ($query) {
                        $query->where('gst', '!=', '0');
                    });
                }

                if ((isset($request->purches_type)) && ($request->purches_type == '1')) {
                    $purchesData->whereHas('getPurchesDetails', function ($query) {
                        $query->where('gst', '0');
                    });
                }
                $limit = 10;
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
                $purchesData->offset($offset)->limit($limit);
                $purchesData =  $purchesData->get();

                $NetAmount =  $purchesData->sum('net_amount');
            } elseif ((isset($request->type)) && ($request->type == '1')) {
                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $purchesData = PurchesReturn::orderBy('id', 'DESC')->whereIn('user_id', $allUserId);


                if (isset($request->month_year)) {
                    $monthYear = $request->month_year;
                    $year = Carbon::createFromFormat('m-Y', $monthYear)->year;
                    $month = Carbon::createFromFormat('m-Y', $monthYear)->month;
                    $purchesData->whereMonth('select_date', $month)
                        ->whereYear('select_date', $year);
                }

                if ((isset($request->purches_type)) && ($request->purches_type == '0')) {
                    $purchesData->whereHas('getPurchesReturn', function ($query) {
                        $query->where('gst', '!=', '0');
                    });
                }

                if ((isset($request->purches_type)) && ($request->purches_type == '1')) {
                    $purchesData->whereHas('getPurchesReturn', function ($query) {
                        $query->where('gst', '0');
                    });
                }
                $limit = 10;
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
                $purchesData->offset($offset)->limit($limit);
                $purchesData =  $purchesData->get();

                $NetAmount =  $purchesData->sum('net_amount');
            }
			// dd($purchesData);
          	
            $dataDetails = [];
            if (isset($purchesData)) {
                $dataDetails['purches'] = [];
                foreach ($purchesData as $key => $list) {
                    $distributorData = Distributer::where('id', $list->distributor_id)->first();
                    $dataDetails['purches'][$key]['id'] = isset($list->id) ? $list->id : "";
                    $dataDetails['purches'][$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                    $dataDetails['purches'][$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date :  $list->select_date;
                    $dataDetails['purches'][$key]['distributor'] = isset($distributorData->name) ? $distributorData->name : "";
                    $dataDetails['purches'][$key]['sgst'] = isset($list->sgst) ? $list->sgst : "";
                    $dataDetails['purches'][$key]['cgst'] = isset($list->cgst) ? $list->cgst : "";
                    $dataDetails['purches'][$key]['net_amount'] = isset($list->net_amount) ? round($list->net_amount, 2) : "";
                }

                $dataDetails['net_amount'] = isset($NetAmount) ? (string)round($NetAmount, 2) : "";
            }
            return $this->sendResponse($dataDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Create Customer api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function dayViseSummry(Request $request)
    {
        try {

            $monthYear = $request->month_year;
            $date = Carbon::createFromFormat('m-Y', $monthYear);
            $year = $date->year;
            $month = $date->month;

            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $models = [
                '0' => ['model' => PurchesModel::class,  'date_column' => 'bill_date', 'amount_column' => 'net_amount'],
                '1' => ['model' => PurchesReturn::class,  'date_column' => 'select_date', 'amount_column' => 'net_amount'],
                '2' => ['model' => SalesModel::class,  'date_column' => 'bill_date', 'amount_column' => 'mrp_total'],
                '3' => ['model' => SalesReturn::class,  'date_column' => 'date', 'amount_column' => 'net_amount']
            ];

            if (!array_key_exists($request->type, $models)) {
                return $this->sendResponse([], 'Invalid Type');
            }

            $model = $models[$request->type]['model'];
            $dateColumn = $models[$request->type]['date_column'];

            $salesData = $model::whereIn('user_id', $allUserId)
                ->whereYear($dateColumn, $year)
                ->whereMonth($dateColumn, $month)
                ->get();


            $groupedData = $salesData->groupBy(function ($date) use ($dateColumn) {
                return Carbon::parse($date[$dateColumn])->format('Y-m-d');
            });

            $dataDetails = [];
            $total = [];
            $i = 1;
            foreach ($groupedData as $date => $items) {

                $dayTotalCgst = $items->sum('cgst');
                $dayTotalSgst = $items->sum('sgst');
                if ($items->sum('total_amount')) {
                    $total_amount = $items->sum('total_amount');
                } else if ($items->sum('net_amt')) {
                    $total_amount = $items->sum('net_amt');
                } else {
                    $total_amount = $items->sum('net_amount');
                }
                if (isset($items[0]->distributor_id)) {
                    $distributor =  Distributer::where('id', $items[0]->distributor_id)->first();

                    $customerName  = $distributor->name;
                }
                if (isset($items[0]->customer_id)) {
                    $customer =  CustomerModel::where('id', $items[0]->customer_id)->first();

                    $customerName  = $customer->name;
                }

                $firstBillNo = $items->first()->bill_no; // Get the first bill_no
                $lastBillNo = $items->last()->bill_no;   // Get the last bill_no

                $dataDetails[] = [
                    'id' => (string)$i,
                    'bill_no' => $firstBillNo . ' - ' . $lastBillNo,
                    'bill_date' => date("d-m-Y", strtotime($date)),
                    'name' => $customerName,
                    'cgst' => (string)$dayTotalCgst,
                    'sgst' => (string)$dayTotalSgst,
                    'total_amount' => (string)$total_amount
                ];
                $i++;
                array_push($total, $total_amount);
            }
            $listDetails['bill_list'] = $dataDetails;
            $listDetails['total'] = (string)array_sum($total);
            return $this->sendResponse($listDetails, 'Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("Day Summary api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function reportGstSales(Request $request)
    {
        try {

            if ((isset($request->type)) && ($request->type == '0')) {

                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $purchesData = SalesModel::orderBy('id', 'DESC')->whereIn('user_id', $allUserId);

                if (isset($request->month_year)) {
                    $monthYear = $request->month_year;
                    $year = Carbon::createFromFormat('m-Y', $monthYear)->year;
                    $month = Carbon::createFromFormat('m-Y', $monthYear)->month;

                    $purchesData->whereMonth('bill_date', $month)
                        ->whereYear('bill_date', $year);
                }


                if (isset($request->payment_mode)) {
                    $purchesData->where('payment_name', $request->payment_mode);
                }
                $limit = 10;
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
                $purchesData->offset($offset)->limit($limit);
                $purchesData =  $purchesData->get();

                $NetAmount =  $purchesData->sum('mrp_total');
            } elseif ((isset($request->type)) && ($request->type == '1')) {
                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $purchesData = SalesReturn::orderBy('id', 'DESC')->whereIn('user_id', $allUserId);

                if (isset($request->month_year)) {
                    $monthYear = $request->month_year;
                    $year = Carbon::createFromFormat('m-Y', $monthYear)->year;
                    $month = Carbon::createFromFormat('m-Y', $monthYear)->month;

                    $purchesData->whereMonth('date', $month)
                        ->whereYear('date', $year);
                }

                if (isset($request->payment_mode)) {
                    $purchesData->where('payment_name', $request->payment_mode);
                }
                $limit = 10;
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
                $purchesData->offset($offset)->limit($limit);
                $purchesData =  $purchesData->get();


                $NetAmount =  $purchesData->sum('net_amount');
            }

            $dataDetails = [];
            if (isset($purchesData)) {
                $dataDetails['sales'] = [];
                foreach ($purchesData as $key => $list) {
                    if ($list->payment_name == 'cash') {
                        $payment = 'cash';
                    } else if ($list->payment_name == 'credit') {
                        $payment = 'credit';
                    } else {

                        $bankData = BankAccount::where('id', $list->payment_name)->first();

                        $payment = '';
                        if (isset($bankData->bank_name)) {
                            $payment = $bankData->bank_name;
                        }
                    }

                    $dataDetails['sales'][$key]['id'] = isset($list->id) ? $list->id : "";
                    $dataDetails['sales'][$key]['payment_type'] = isset($payment) ? $payment : "";
                    $dataDetails['sales'][$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                    $dataDetails['sales'][$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date :  $list->date;
                    $dataDetails['sales'][$key]['customer'] = isset($list->getUserName) ? $list->getUserName->name : "";
                    $dataDetails['sales'][$key]['sgst'] = isset($list->sgst) ? $list->sgst : "";
                    $dataDetails['sales'][$key]['cgst'] = isset($list->cgst) ? $list->cgst : "";
                    $dataDetails['sales'][$key]['igst'] = isset($list->igst) ? $list->igst : "";
                    $dataDetails['sales'][$key]['net_amount'] = isset($list->net_amount) ? $list->net_amount : $list->mrp_total;
                }

                $dataDetails['net_amount'] = isset($NetAmount) ? (string)round($NetAmount, 2) : "";
            }
            return $this->sendResponse($dataDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Return Gst api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesPaymentSummary(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $userId = auth()->user()->id;
          	$purchesPaymentTotalCount = PurchesPaymentDetails::whereIn('user_id', $allUserId)->count();
            $purchesPayment = PurchesPaymentDetails::whereIn('user_id', $allUserId);

            if ($request->status == 'Paid') {
                $purchesPayment->where('status', 'Paid');
            }
            if ($request->status == 'Partially_Paid') {
                $purchesPayment->where('status', 'Partially Paid');
            }
            // Date filter on relation
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $purchesPayment->whereHas('getPurches', function ($query) use ($request) {
                    $query->whereBetween('payment_date', [$request->start_date, $request->end_date]);
                });
            }
            // Search filter
            if (!empty($request->search)) {
              $nameData = Distributer::where('name', 'like', '%' . $request->search . '%')
                ->pluck('id')->toArray();

              $purchesPayment->where(function ($q) use ($request, $nameData) {
                $q->where('bill_no', $request->search)
                  ->orWhereIn('distributor_name', $nameData);
              });
            }

            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $purchesPayment = $purchesPayment->offset($offset)->limit($limit)->orderBy('id', 'DESC')->get();
            // $purchesPayment = $purchesPayment->orderBy('id', 'DESC')->get();

            $paymentData = [];
            if (isset($purchesPayment)) {
                foreach ($purchesPayment as $key => $list) {
                    $nameData = Distributer::where('id', $list->distributor_name)->first();
                    $note = PurchesPayment::where('id', $list->payment_id)->first();
                    $paymentData[$key]['id'] = isset($list->id) ? $list->id : "";
                    $paymentData[$key]['distributor_name'] = isset($nameData->name) ? $nameData->name : "";
                    $paymentData[$key]['payment_mode'] = isset($list->payment_mode) ? $list->payment_mode : "";
                    $paymentData[$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date : "";
                    $paymentData[$key]['payment_date'] = isset($list->getPurches->payment_date) ? date("d-m-Y", strtotime($list->getPurches->payment_date)) : "";
                    $paymentData[$key]['total'] = isset($note->total) ? $note->total : "";
                    $paymentData[$key]['due_amount'] = isset($list->due_amount) ? (string) round($list->due_amount, 2) : "";
                    $paymentData[$key]['status'] = isset($list->status) ? $list->status : "";
                    $paymentData[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                }
            }

            $reversedPaymentData = array_reverse($paymentData);
          
          	$response = [
              'status' => 200,
              'count' => !empty($request->page) ? $purchesPayment->count() : $purchesPaymentTotalCount,
              'total_records' => $purchesPaymentTotalCount,
              'data'   => $reversedPaymentData,
              'message' => 'Purchase Payment Data Fetch Successfully.',
            ];
            return response()->json($response, 200);
          
            // return $this->sendResponse($reversedPaymentData, 'Purchase Payment Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("report Purches Payment api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // itemWiseDoctor
    public function itemWiseDoctor(Request $request)
    {
        try {
            if ($request->type == '0') {
                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $limit = 10;
                $salesData = salesDetails::orderBy('id', 'DESC')->with('getSales.getDoctor')->whereIn('user_id', $allUserId);

                if ($request->start_date) {
                    $startDate = $request->start_date;
                    $endDate = $request->end_date;
                    $salesData = $salesData->whereHas('getSales', function ($query) use ($startDate, $endDate, $allUserId) {
                        $query->whereBetween('bill_date', [$startDate, $endDate])
                            ->whereIn('user_id', $allUserId);
                    });
                }
                if (isset($request->doctor_name)) {
                    $doctorName = $request->doctor_name;
                    $salesData = $salesData->whereHas('getSales', function ($query) use ($doctorName) {
                        $query->whereHas('getDoctor', function ($q) use ($doctorName) {
                            $q->where('name', 'like', '%' . $doctorName . '%');
                        });
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
                if (isset($request->doctor_name)) {
                    $doctorName = $request->doctor_name;

                    $salesData = $salesData->whereHas('getSales', function ($query) use ($doctorName) {
                        $query->whereHas('getDoctor', function ($q) use ($doctorName) {
                            $q->where('name', 'like', '%' . $doctorName . '%');
                        });
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

            $dataSalesReprot = [];
            $QtyData = [];
            $amountData = [];
            $netProfit = [];
            if (isset($salesData)) {
                if ($request->type == '0') {
                    foreach ($salesData as $key => $list) {
                        $dataSalesReprot[$key]['id'] = isset($list->id) ? $list->id : "";
                        $dataSalesReprot[$key]['bill_no'] = isset($list->getSales->bill_no) ? $list->getSales->bill_no : "";
                        $dataSalesReprot[$key]['bill_date'] = isset($list->getSales->bill_date) ? $list->getSales->bill_date : "";
                        $dataSalesReprot[$key]['patient_name'] = isset($list->getSales->getUserName->name) ? $list->getSales->getUserName->name : "";
                        $dataSalesReprot[$key]['phone_number'] = isset($list->getSales->getUserName->phone_number) ? $list->getSales->getUserName->phone_number : "";
                        $dataSalesReprot[$key]['doctor_name'] = isset($list->getSales->getDoctor->name) ? $list->getSales->getDoctor->name : "";
                        $dataSalesReprot[$key]['item_name'] = isset($list->getIteam->iteam_name) ? $list->getIteam->iteam_name : "";
                        $dataSalesReprot[$key]['company_name'] = isset($list->getIteam->getPharma->company_name) ? $list->getIteam->getPharma->company_name : "";
                        $dataSalesReprot[$key]['packing_size'] = isset($list->getIteam->packing_size) ? $list->getIteam->packing_size : "";
                        $dataSalesReprot[$key]['unit'] = isset($list->unit) ? $list->unit : "";
                        $dataSalesReprot[$key]['exp'] = isset($list->exp) ? $list->exp : "";
                        $dataSalesReprot[$key]['qty'] = isset($list->qty) ? $list->qty : "";
                        $dataSalesReprot[$key]['sales_rate'] = isset($list->mrp) ? $list->mrp : "";
                        $amount = $list->qty * $list->mrp;
                        $dataSalesReprot[$key]['amount'] = isset($amount) ? (string)round($amount, 2) : "";
                        $dataSalesReprot[$key]['net_profit'] = $list->amt != 'Infinity' ? $list->amt : "0";
                        array_push($QtyData, $list->qty);
                        array_push($amountData, $amount);
                        array_push($netProfit, $dataSalesReprot[$key]['net_profit']);
                    }
                }

                if ($request->type == '1') {
                    foreach ($salesData as $key => $list) {
                        $dataSalesReprot[$key]['id'] = isset($list->id) ? $list->id : "";
                        $dataSalesReprot[$key]['bill_no'] = isset($list->getSales->bill_no) ? $list->getSales->bill_no : "";
                        $dataSalesReprot[$key]['bill_date'] = isset($list->getSales->date) ? $list->getSales->date : "";
                        $dataSalesReprot[$key]['patient_name'] = isset($list->getSales->getUserName->name) ? $list->getSales->getUserName->name : "";
                        $dataSalesReprot[$key]['phone_number'] = isset($list->getSales->getUserName->phone_number) ? $list->getSales->getUserName->phone_number : "";
                        $dataSalesReprot[$key]['doctor_name'] = isset($list->getSales->getDoctor->name) ? $list->getSales->getDoctor->name : "";
                        $dataSalesReprot[$key]['item_name'] = isset($list->getIteamName->iteam_name) ? $list->getIteamName->iteam_name : "";
                        $dataSalesReprot[$key]['company_name'] = isset($list->getIteamName->getPharma->company_name) ? $list->getIteamName->getPharma->company_name : "";
                        $dataSalesReprot[$key]['packing_size'] = isset($list->getIteamName->packing_size) ? $list->getIteamName->packing_size : "";
                        $dataSalesReprot[$key]['unit'] = isset($list->unit) ? $list->unit : "";
                        $dataSalesReprot[$key]['exp'] = isset($list->exp) ? $list->exp : "";
                        $dataSalesReprot[$key]['qty'] = isset($list->qty) ? $list->qty : "";
                        $dataSalesReprot[$key]['sales_rate'] = isset($list->mrp) ? $list->mrp : "";
                        $amount = $list->qty * $list->mrp;
                        $dataSalesReprot[$key]['amount'] = isset($amount) ? (string)round($amount, 2) : "";
                        $dataSalesReprot[$key]['net_profit'] = $list->net_rate != 'Infinity' ? $list->net_rate : "0";
                        array_push($QtyData, $list->qty);
                        array_push($amountData, $amount);
                        array_push($netProfit, $dataSalesReprot[$key]['net_profit']);
                    }
                }
            }

            $salesDetailsData['doctor_report'] = $dataSalesReprot;
            $salesDetailsData['total_qty'] = (string)array_sum($QtyData);
            $salesDetailsData['total_amount'] = (string)array_sum($amountData);
            $salesDetailsData['total_net_profite'] = (string)array_sum($netProfit);
            return $this->sendResponse($salesDetailsData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("report Purches Payment api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function companyItemsAnalysisreport(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $iteamData = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->whereHas('getPharma', function ($query) use ($request) {
                $query->where('company_name',   'like', '%' . $request->company_name . '%');
            })->pluck('id')->toArray();

            $purchesData = PurchesDetails::whereIn('user_id', $allUserId);
            if ((isset($request->start_date)) && (isset($request->end_date))) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;

                $purchesData->whereHas('getpurches', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('bill_date', [$startDate, $endDate]);
                });
            }
            $purchesData = $purchesData->whereIn('iteam_id', $iteamData)->get();

            $purchesDetails = [];
            $netRate = [];
            if (isset($purchesData)) {
                foreach ($purchesData as $key => $list) {
                    $purchesDetails[$key]['id'] = isset($list->id) ? $list->id : "";
                    $purchesDetails[$key]['iteam_name'] = isset($list->getIteam->iteam_name) ? $list->getIteam->iteam_name : "";
                    $purchesDetails[$key]['unit'] = isset($list->unit) ? $list->unit : "";
                    $purchesDetails[$key]['bill_no'] = isset($list->getpurches->bill_no) ? $list->getpurches->bill_no : "";
                    $purchesDetails[$key]['bill_date'] = isset($list->getpurches->bill_date) ? $list->getpurches->bill_date : "";
                    $purchesDetails[$key]['batch'] = isset($list->batch) ? $list->batch : "";
                    $purchesDetails[$key]['exp_dt'] = isset($list->exp_dt) ? $list->exp_dt : "";
                    $purchesDetails[$key]['qty'] = isset($list->qty) ? $list->qty : "";
                    $purchesDetails[$key]['free_qty'] = isset($list->fr_qty) ? $list->fr_qty : "";
                    $purchesDetails[$key]['net_rate'] = isset($list->net_rate) ? $list->net_rate : "";

                    array_push($netRate, $list->net_rate);
                }
            }

            $companyData['item_list'] = $purchesDetails;
            $companyData['total'] =  (string)array_sum($netRate);

            return $this->sendResponse($companyData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("report Company Iteam Analysis api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function saleSummary(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $salesData = SalesModel::orderBy('id', 'DESC');
            if (isset($request->start_date) && isset($request->end_date)) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                $salesData->whereBetween('bill_date', [$startDate, $endDate]);
            }
            if (isset($request->payment_mode)) {
                $paymentData = explode(',', $request->payment_mode);
                $salesData->whereIn('payment_name', $paymentData);
            }
            $salesData = $salesData->whereIn('user_id', $allUserId)->get();

            $salesDetails = [];
            $dataDetails = [];
            $totalCash = 0;
            $totalCredit = 0;
            $totalAmount = 0;
            $paymentModeTotals = []; // Array to hold total amounts for each payment mode
            $paymentModeMargins = []; // Array to hold margin totals for each payment mode

            $startDate = Carbon::parse($request->start_date); // Replace with your start date
            $endDate = Carbon::parse($request->end_date); // Replace with your end date

            if ($request->select_data == 'total_sales') {
                for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                    $dateFormatted = $date->format('Y-m-d');
                    $dailySales = ['date' => $dateFormatted];

                    // Initialize payment keys with zero values
                    foreach ($paymentData as $paymentMode) {
                        $paymentKey = '';
                        if ($paymentMode === 'cash') {
                            $paymentKey = 'cash';
                        } elseif ($paymentMode === 'credit') {
                            $paymentKey = 'credit';
                        } else {
                            $bankData = BankAccount::where('id', $paymentMode)->first();
                            $paymentKey = $bankData->bank_name;
                        }
                        $dailySales[$paymentKey] = "0";
                        if (!isset($paymentModeTotals[$paymentKey])) {
                            $paymentModeTotals[$paymentKey] = 0; // Initialize total for this payment mode
                        }
                        if (!isset($paymentModeMargins[$paymentKey])) {
                            $paymentModeMargins[$paymentKey] = 0; // Initialize margin total for this payment mode
                        }
                    }

                    $dailyTotalSales = 0;

                    foreach ($salesData as $sale) {
                        if (in_array($sale->payment_name, $paymentData) && $sale->bill_date == $dateFormatted) {
                            $paymentKey = '';
                            if ($sale->payment_name === 'cash') {
                                $paymentKey = 'cash';
                                $dailySales[$paymentKey] = (string)((float)$dailySales[$paymentKey] + $sale->net_amt);
                                $totalCash += $sale->net_amt; // Update total cash
                            } elseif ($sale->payment_name === 'credit') {
                                $paymentKey = 'credit';
                                $dailySales[$paymentKey] = (string)((float)$dailySales[$paymentKey] + $sale->net_amt);
                                $totalCredit += $sale->net_amt; // Update total credit
                            } else {
                                $bankData = BankAccount::where('id', $sale->payment_name)->first();
                                $paymentKey = $bankData->bank_name;
                                $dailySales[$paymentKey] = (string)((float)$dailySales[$paymentKey] + $sale->net_amt);
                            }

                            $paymentModeTotals[$paymentKey] += $sale->net_amt; // Update total for this payment mode
                            // $dailyTotalSales += $sale->net_amt; // Update daily total sales
                          	$dailyTotalSales += $sale->mrp_total; // Update daily total sales
                        }
                    }

                    $dailySales['total_sales'] = (string)$dailyTotalSales;
                    $totalAmount += $dailyTotalSales; // Update total amount by adding daily total sales
                    $salesDetails[] = $dailySales;
                }

                // Add the total amounts for each payment mode to the response
                $dataDetails['sales'] = $salesDetails;
                $dataDetails['total_cash'] = (string)$totalCash;
                $dataDetails['total_credit'] = (string)$totalCredit;
                $dataDetails['total_amount'] = (string)$totalAmount;

                // Add totals for each payment mode (including banks) to the response
                foreach ($paymentModeTotals as $paymentMode => $total) {
                    $dataDetails['total_' . $paymentMode] = (string)$total;
                }
            } else if ($request->select_data == 'total_margin') {
                for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                    $dateFormatted = $date->format('Y-m-d');
                    $dailySales = ['date' => $dateFormatted];

                    // Initialize payment keys with zero values
                    foreach ($paymentData as $paymentMode) {
                        $paymentKey = '';
                        if ($paymentMode === 'cash') {
                            $paymentKey = 'cash';
                        } elseif ($paymentMode === 'credit') {
                            $paymentKey = 'credit';
                        } else {
                            $bankData = BankAccount::where('id', $paymentMode)->first();
                            $paymentKey = $bankData->bank_name;
                        }
                        $dailySales[$paymentKey] = "0";
                        if (!isset($paymentModeTotals[$paymentKey])) {
                            $paymentModeTotals[$paymentKey] = 0; // Initialize total for this payment mode
                        }
                        if (!isset($paymentModeMargins[$paymentKey])) {
                            $paymentModeMargins[$paymentKey] = 0; // Initialize margin total for this payment mode
                        }
                    }

                    $dailyTotalSales = 0;

                    $totalFinalAmounts = [];

                    foreach ($salesData as $sale) {
                        $userid = auth()->user();
                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = array(auth()->user()->id);
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                        $salesBatchGet = salesDetails::where('sales_id', $sale->id)->whereIn('user_id', $allUserId)->pluck('batch')->toArray();

                        $purchesIdGet = PurchesModel::whereDate('bill_date', $dateFormatted)->whereIn('user_id', $allUserId)->pluck('id')->toArray();

                        $purchesBatchData = PurchesDetails::whereIn('purches_id', $purchesIdGet)->whereIn('batch', $salesBatchGet)->get();

                        $purchesMargin = [];
                        if (isset($purchesBatchData)) {
                            foreach ($purchesBatchData as $listBatch) {
                                $TotalQty = $listBatch->qty + $listBatch->fr_qty;
                                $purchesDataList = $listBatch->net_rate / $TotalQty;
                                array_push($purchesMargin, $purchesDataList);
                            }
                        }
                        $purchesMarginAmount = array_sum($purchesMargin);

                        $salesIdGet = SalesModel::whereDate('bill_date', $dateFormatted)->whereIn('user_id', $allUserId)->pluck('id')->toArray();

                        $salesBatchData = salesDetails::whereIn('sales_id', $salesIdGet)->whereIn('batch', $salesBatchGet)->get();
                        $salesMargin = [];
                        if (isset($salesBatchData)) {
                            foreach ($salesBatchData as $listSales) {
                                $qtySales = $listSales->qty;
                                if ($listSales->amt != 'Infinity') {
                                    $salesDataList = $listSales->amt / $qtySales;
                                } else {
                                    $salesDataList = '0';
                                }
                                array_push($salesMargin, $salesDataList);
                            }
                        }
                        $salesMarginAmount = array_sum($salesMargin);

                        $totalSalesMargin = $salesMarginAmount - $purchesMarginAmount;

                        $salesQtyData = salesDetails::whereIn('sales_id', $salesIdGet)->whereIn('batch', $salesBatchGet)->sum('qty');

                        $salesFinalMargin = $totalSalesMargin * $salesQtyData;

                        array_push($totalFinalAmounts, $salesFinalMargin);

                        // Assign the margin to the correct payment method
                        if (in_array($sale->payment_name, $paymentData) && $sale->bill_date == $dateFormatted) {
                            $paymentKey = '';
                            if ($sale->payment_name === 'cash') {
                                $paymentKey = 'cash';
                            } elseif ($sale->payment_name === 'credit') {
                                $paymentKey = 'credit';
                            } else {
                                $bankData = BankAccount::where('id', $sale->payment_name)->first();
                                $paymentKey = $bankData->bank_name;
                            }
                            $paymentModeMargins[$paymentKey] += $salesFinalMargin; // Update margin total for this payment mode
                            $dailySales[$paymentKey] = (string)$paymentModeMargins[$paymentKey]; // Update daily sales for this payment mode
                        }
                    }

                    $totalAmount += $dailyTotalSales; // Update total amount by adding daily total sales
                    $salesDetails[] = $dailySales;
                }

                // Add the total amounts for each payment mode to the response
                $dataDetails['sales'] = $salesDetails;

                // Add margins for each payment mode (including banks) to the response
                foreach ($paymentModeMargins as $paymentMode => $margin) {
                    $dataDetails['margin_' . $paymentMode] = (string)$margin;
                }
            }

            return $this->sendResponse($dataDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("report Company Item Analysis api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function gstOneReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
        ], [
            'date.required' => 'Enter date'
        ]);

        if ($validator->fails()) {
            $error = $validator->getMessageBag();
            return $this->sendError($error->first());
        }

        $monthYear = $request->date;
        $date = Carbon::createFromFormat('m-Y', $monthYear);
        $year = $date->year;
        $month = $date->month;
        $userId = auth()->user()->id;
        $userid = auth()->user();
        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
        $userId = array(auth()->user()->id);
        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

        if ((isset($request->type)) && ($request->type == '0')) {
            $sellerCreate = SalesModel::whereIn('user_id', $allUserId)->whereYear('bill_date', $year)
                ->whereMonth('bill_date', $month)->get();
        } elseif ((isset($request->type)) && ($request->type == '1')) {
            $sellerCreate = SalesReturn::whereIn('user_id', $allUserId)->whereYear('date', $year)
                ->whereMonth('date', $month)->get();
        }

        $detailsList = [];
        if (isset($sellerCreate)) {
            foreach ($sellerCreate as $key => $list) {


                $customer =  CustomerModel::where('id', $list->customer_id)->first();
                if (isset($list->salesReturnGet)) {

                    $sgstData = $list->sum('sgst');
                    $cgstData = $list->sum('cgst');
                    $IgstData = $list->sum('igst');
                    $textbleValue = $list->salesReturnGet->sum('taxable_value');
                } else {

                    $sgstData = $list->sum('sgst');
                    $cgstData = $list->sum('cgst');
                    $IgstData = $list->sum('igst');
                    $textbleValue = $list->getSales->sum('taxable_value');
                }

                $detailsList['id'] = isset($list->id) ? $list->id : "";
                $detailsList['case_amount'] = "0";
                $detailsList['taxable_value'] = isset($textbleValue) ? (string)$textbleValue : "";
                $detailsList['sgst'] = isset($sgstData) ? (string)$sgstData : "";
                $detailsList['cgst'] = isset($cgstData) ? (string)$cgstData : "";
                $detailsList['igst'] = isset($gstTotal) ? (string)$gstTotal : "";
                $detailsList['cash_rate'] = "0";
                $detailsList['state'] = isset($customer->state) ? $customer->state : "";
                $detailsList['bill_no'] = isset($list->bill_no) ? $list->bill_no : $list->bill_no;
                $detailsList['bill_date'] = isset($list->bill_date) ? $list->bill_date : $list->date;
                $detailsList['bill_amount'] = isset($list->net_amount) ? (string)$list->net_amount : (string)$list->net_amt;
                $detailsList['bill_net_rate'] = isset($list->net_rate) ? (string)$list->net_rate : "";
                $detailsList['cash_rate'] =  "0";
                $detailsList['customer_name'] = isset($list->getUserName->name) ? $list->getUserName->name : "";
            }
        }
        return $this->sendResponse($detailsList, 'Data Fetch Successfully');
    }

    public function gsttwoReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
        ], [
            'date.required' => 'Enter date'
        ]);

        if ($validator->fails()) {
            $error = $validator->getMessageBag();
            return $this->sendError($error->first());
        }

        $monthYear = $request->date;
        $date = Carbon::createFromFormat('m-Y', $monthYear);
        $year = $date->year;
        $month = $date->month;

        $userId = auth()->user()->id;
        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
        $userId = array(auth()->user()->id);
        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

        if ((isset($request->type)) && ($request->type == '0')) {
            $sellerCreate = PurchesModel::whereIn('user_id', $allUserId)->whereYear('bill_date', $year)
                ->whereMonth('bill_date', $month)->get();
        } elseif ((isset($request->type)) && ($request->type == '1')) {
            $sellerCreate = PurchesReturn::whereIn('user_id', $allUserId)->whereYear('select_date', $year)
                ->whereMonth('select_date', $month)->get();
        }

        $detailsList = [];
        if (isset($sellerCreate)) {
            foreach ($sellerCreate as $key => $list) {

                $customer =  Distributer::where('id', $list->distributor_id)->first();
                if ((isset($request->type)) && ($request->type == '0')) {
                    $sgstData = $list->sum('sgst');
                    $cgstData = $list->sum('cgst');
                    $IgstData = $list->sum('igst');
                    $netRate = $list->getPurchesDetails->sum('net_rate');
                    $textbleValue = $list->getPurchesDetails->sum('taxable_value');
                } else {
                    $sgstData = $list->sum('sgst');
                    $cgstData = $list->sum('cgst');
                    $IgstData = $list->sum('igst');
                    $textbleValue = $list->getPurchesReturn->sum('taxable_value');
                    $netRate = isset($list->net_rate) ? $list->net_rate : "";
                }

                $detailsList['id'] = isset($list->id) ? $list->id : "";
                $detailsList['case_amount'] = "0";
                $detailsList['taxable_value'] = isset($textbleValue) ? (string)$textbleValue : "";
                $detailsList['sgst'] = isset($sgstData) ? (string)$sgstData : "";
                $detailsList['cgst'] = isset($cgstData) ? (string)$cgstData : "";
                $detailsList['igst'] = isset($gstTotal) ? (string)$gstTotal : "";
                $detailsList['cash_rate'] = "0";
                $detailsList['state'] = isset($customer->state) ? $customer->state : "";
                $detailsList['bill_no'] = isset($list->bill_no) ? $list->bill_no : $list->bill_no;
                $detailsList['bill_date'] = isset($list->select_date) ? $list->select_date : $list->bill_date;
                $detailsList['bill_amount'] = isset($list->net_amount) ? (string)$list->net_amount : (string)$list->net_amount;
                $detailsList['bill_net_rate'] = isset($netRate) ? (string)$netRate : "";
                $detailsList['cash_rate'] =  "0";
                $detailsList['distributor_name'] = isset($customer->name) ? $customer->name : "";
            }
        }
        return $this->sendResponse($detailsList, 'Data Fetch Successfully');
    }

    public function gstThreereport(Request $request)
    {

        try {

            $userId = auth()->user()->id;
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $validator = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required',
            ], [
                'start_date.required' => 'Enter Start Date',
                'end_date.required' => 'Enter End Date',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Sales query with date range filter
            $sales = SalesModel::with('getSales')
                ->whereBetween('bill_date', [$startDate, $endDate]) // Filter between start and end date
                ->whereIn('user_id', $allUserId)
                ->select('cgst', 'sgst', 'igst', 'id')
                ->get();

            // Sales Returns query with date range filter
            $salesReturns = SalesReturn::whereIn('user_id', $allUserId)
                ->whereBetween('date', [$startDate, $endDate]) // Assuming 'created_at' for sales return date
                ->select('cgst', 'sgst', 'igst', 'id')
                ->get();

            // Purchases query with date range filter
            $purchases = PurchesModel::whereIn('user_id', $allUserId)
                ->whereBetween('bill_date', [$startDate, $endDate]) // Assuming 'created_at' for purchase date
                ->select('cgst', 'sgst', 'igst', 'id')
                ->get();

            // Purchase Returns query with date range filter
            $purchaseReturns = PurchesReturn::whereIn('user_id', $allUserId)
                ->whereBetween('select_date', [$startDate, $endDate]) // Assuming 'created_at' for purchase return date
                ->select('cgst', 'sgst', 'igst', 'id')
                ->get();

            $totalSales = salesDetails::whereIn('sales_id', $sales->pluck('id')->toArray())->sum('taxable_value');

            $totalSalesCGST = $sales->sum('cgst');
            $totalSalesSGST = $sales->sum('sgst');
            $totalSalesIGST = $sales->sum('igst');

            // Calculate totals for sales returns
            $totalSalesReturns = SalesReturnDetails::whereIn('sales_id', $salesReturns->pluck('id')->toArray())->sum('taxable_value');
            $totalSalesReturnsCGST = $salesReturns->sum('cgst');
            $totalSalesReturnsSGST = $salesReturns->sum('sgst');
            $totalSalesReturnsIGST = $salesReturns->sum('igst');

            // Net sales
            $netSales = (int)$totalSales - (int)$totalSalesReturns;

            $netSalesCGST = (int)$totalSalesCGST - (int)$totalSalesReturnsCGST;
            $netSalesSGST = (int)$totalSalesSGST - (int)$totalSalesReturnsSGST;
            $netSalesIGST = (int)$totalSalesIGST - (int)$totalSalesReturnsIGST;

            // Calculate totals for purchases
            $totalPurchases = PurchesDetails::whereIn('purches_id', $purchases->pluck('id')->toArray())->sum('taxable_value');
            $totalPurchasesCGST = $purchases->sum('cgst');
            $totalPurchasesSGST = $purchases->sum('sgst');
            $totalPurchasesIGST = $purchases->sum('igst');

            // Calculate totals for purchase returns
            $totalPurchaseReturns = PurchesReturnDetails::whereIn('purches_id', $purchases->pluck('id')->toArray())->sum('taxable_value');
            $totalPurchaseReturnsCGST = $purchaseReturns->sum('cgst');
            $totalPurchaseReturnsSGST = $purchaseReturns->sum('sgst');
            $totalPurchaseReturnsIGST = $purchaseReturns->sum('igst');

            // Net purchases
            $netPurchases = (int)$totalPurchases - (int)$totalPurchaseReturns;
            $netPurchasesCGST = (int)$totalPurchasesCGST - (int)$totalPurchaseReturnsCGST;
            $netPurchasesSGST = (int)$totalPurchasesSGST - (int)$totalPurchaseReturnsSGST;
            $netPurchasesIGST = (int)$totalPurchasesIGST - (int)$totalPurchaseReturnsIGST;

            // Net GST liability
            $netPayableCGST = (int)$netSalesCGST - (int)$netPurchasesCGST;
            $netPayableSGST = (int)$netSalesSGST - (int)$netPurchasesSGST;
            $netPayableIGST = (int)$netSalesIGST - (int)$netPurchasesIGST;

            $gstLiability = (int)$netPayableCGST + (int)$netPayableSGST + (int)$netPayableIGST;
            $response = [
                'invoice_details' => [
                    'sales' => [
                        'total' => (string)$totalSales,
                        'cgst' => (string)$totalSalesCGST,
                        'sgst' => (string)$totalSalesSGST,
                        'igst' => (string)$totalSalesIGST,
                    ],
                    'sales_returns' => [
                        'total' => (string)$totalSalesReturns,
                        'cgst' => (string)$totalSalesReturnsCGST,
                        'sgst' => (string)$totalSalesReturnsSGST,
                        'igst' => (string)$totalSalesReturnsIGST,
                    ],
                    'purchases' => [
                        'total' => (string) $totalPurchases,
                        'cgst' => (string)$totalPurchasesCGST,
                        'sgst' => (string)$totalPurchasesSGST,
                        'igst' => (string)$totalPurchasesIGST,
                    ],
                    'purchase_returns' => [
                        'total' => (string)$totalPurchaseReturns,
                        'cgst' => (string)$totalPurchaseReturnsCGST,
                        'sgst' => (string)$totalPurchaseReturnsSGST,
                        'igst' => (string)$totalPurchaseReturnsIGST,
                    ],
                ],
                'summary' => [
                    'net_sales' => [
                        'taxable_amount' => (string)$netSales,
                        'cgst' => (string)$netSalesCGST,
                        'sgst' => (string)$netSalesSGST,
                        'igst' => (string)$netSalesIGST,
                    ],
                    'net_purchases' => [
                        'taxable_amount' => (string)$netPurchases,
                        'cgst' => (string)$netPurchasesCGST,
                        'sgst' => (string)$netPurchasesSGST,
                        'igst' => (string)$netPurchasesIGST,
                    ],
                ],
                'gst_liability' => [
                    'cgst' => (string)$netPayableCGST,
                    'sgst' => (string)$netPayableSGST,
                    'igst' => (string)$netPayableIGST,
                    'total' => (string)$gstLiability,
                ],
            ];

            return $this->sendResponse($response, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            dD($e);
            Log::info("report Company Item Analysis api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
