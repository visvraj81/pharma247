<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\Distributer;
use App\Models\User;
use App\Models\PurchesModel;
use App\Models\LedgerModel;
use App\Models\PurchesPaymentDetails;
use App\Models\PurchesPayment;
use App\Models\LogsModel;
use App\Models\CustomerModel;
use App\Models\PurchesReturn;
use App\Models\DistributorPrchesReturnTable;
use App\Models\PurchesDetails;
use App\Models\PurchesReturnDetails;
use App\Models\BankAccount;
use App\Models\IteamsModel;
use App\Models\CompanyModel;

class DistributerController extends ResponseController
{
    // this function use create distributor
    public function createDistributer(Request $request)
    {
        try {
            if (isset($request->email)) {
                $distributorEmail = Distributer::where('user_id',auth()->user()->id)->where('email', $request->email)->first();
                if (!empty($distributorEmail)) {
                    return $this->sendError('Distributor Email Already Exist.');
                }
            }
          	if (isset($request->mobile_no)) {
                $distributorMobileNo = Distributer::where('user_id',auth()->user()->id)->where('phone_number', $request->mobile_no)->first();
                if (!empty($distributorMobileNo)) {
                    return $this->sendError('Distributor Mobile Number Already Exist.');
                }
            }
          	if (isset($request->gst_number)) {
                $distributorGst = Distributer::where('user_id',auth()->user()->id)->where('gst', $request->gst_number)->first();
                if (!empty($distributorGst)) {
                    return $this->sendError('Distributor GST Already Exist.');
                }
            }
            $distributer_details = new Distributer;
            $distributer_details->name = $request->distributor_name;
            $distributer_details->email = $request->email;
            $distributer_details->phone_number = $request->mobile_no;
            $distributer_details->address = $request->address;
            $distributer_details->state = $request->state;
            $distributer_details->user_id = auth()->user()->id;
            $distributer_details->balance = '0';
            $distributer_details->status = '1';
            $distributer_details->role = '4';
            $distributer_details->gst = $request->gst_number;
            $distributer_details->area_number = $request->area;
            $distributer_details->pincode = $request->pincode;
            $distributer_details->bank_name = $request->bank_name;
            $distributer_details->whatsapp_number = $request->whatsapp;
            $distributer_details->account_no = $request->account_no;
            $distributer_details->phone_number = $request->mobile_no;
            $distributer_details->ifsc_code = $request->ifsc_code;
            $distributer_details->food_licence_number = $request->food_licence_no;
            $distributer_details->distributer_drug_licence_no = $request->distributor_durg_distributor;
            $distributer_details->payment_drug_days = $request->payment_due_days;
            $distributer_details->save();

            $leaderData = new LedgerModel;
            $leaderData->owner_id = $distributer_details->id;
            $leaderData->entry_date = date('Y-m-d');
            $leaderData->transction = 'Opening Balance';
            $leaderData->voucher = 'Opening Balance';
            $leaderData->bill_no = '#';
            $leaderData->save();

            $userLogs = new LogsModel;
            $userLogs->message = 'Distributor Added';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $dataDetails['id'] = isset($distributer_details->id) ? $distributer_details->id : "";
            $dataDetails['name'] = isset($distributer_details->name) ? $distributer_details->name : "";
          	$dataDetails['phone_number'] = isset($distributer_details->phone_number) ? $distributer_details->phone_number : "";

            return $this->sendResponse($dataDetails, 'Distributor Added Successfully.');
        } catch (\Exception $e) {
            dd($e);
            Log::info("Create Distributer api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function distributerCompanyList(Request $request)
    {
        $distributorData = Distributer::where('id', $request->id)->first();

        if (isset($distributorData)) {
            $purchaesDatas = PurchesModel::where('distributor_id', $distributorData->id)->pluck('id')->toArray();
            $purchaesDetailsId = PurchesDetails::whereIn('purches_id', $purchaesDatas)->pluck('iteam_id')->toArray();
            $iteamModelData = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->whereIn('id', $purchaesDetailsId)->pluck('pharma_shop')->toArray();

            $companyList = CompanyModel::whereIn('id', $iteamModelData)->pluck('company_name')->toArray();

            $companyList = array_map(function ($company) {
                return trim(str_replace(["\r", "\n"], '', $company));
            }, $companyList);

            $dataDetails['name'] =  $distributorData->name;
            $dataDetails['company_list'] = $companyList;
          	
            return $this->sendResponse($dataDetails, 'Distributor Company List Fetch Successfully.');
        }
    }

    public function editDistributer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => "Enter Distributer Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $distributer_details = Distributer::where('id', $request->id)->first();
            $distributer_details->name = $request->distributor_name;
            $distributer_details->email = $request->email;
            $distributer_details->phone_number = $request->mobile_no;
            $distributer_details->address = $request->address;
            $distributer_details->state = $request->state;
            $distributer_details->user_id = auth()->user()->id;
            $distributer_details->balance = '0';
            // $distributer_details->password = '';
            $distributer_details->status = '1';
            $distributer_details->role = '4';
            $distributer_details->distributer_id = $request->id;
            $distributer_details->gst = $request->gst_number;
            $distributer_details->area_number = $request->area;
            $distributer_details->pincode = $request->pincode;
            $distributer_details->bank_name = $request->bank_name;
            $distributer_details->whatsapp_number = $request->whatsapp;
            $distributer_details->account_no = $request->account_no;
            // $distributer_details->phone_number = $request->phone_number;
            $distributer_details->ifsc_code = $request->ifsc_code;
            $distributer_details->food_licence_number = $request->food_licence_no;
            $distributer_details->distributer_drug_licence_no = $request->distributor_durg_distributor;
            $distributer_details->payment_drug_days = $request->payment_due_days;
            $distributer_details->update();

            $userLogs = new LogsModel;
            $userLogs->message = 'Distributer Updated';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $dataDetails['id'] = isset($distributer->id) ? $distributer->id : "";
            $dataDetails['name'] = isset($distributer->name) ? $distributer->name : "";

            return $this->sendResponse($dataDetails, 'Distributor Updated Successfully.');
        } catch (\Exception $e) {
            dd($e);
            Log::info("Create Distributer api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use list distributer
    public function listDistributer(Request $request)
    {
        try {
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);

            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $limit = 10;

            $distributerList = Distributer::where('role', '4')
              ->whereIn('user_id', $allUserId)
              ->whereNotNull('name')
              ->orderByRaw("CASE WHEN name = 'OPENING DISTRIBUTOR' THEN 0 ELSE 1 END, id DESC");

            if (empty($request->iss_value)) {
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
                $distributerList->limit($limit)->offset($offset);
            }
            if (isset($request->search_name)) {
                $distributerList->where('name', 'like', '%' . $request->search_name . '%');
            }
          	if (isset($request->search_email)) {
                $distributerList->where('email', 'like', '%' . $request->search_email . '%');
            }
          	if (isset($request->search_gst)) {
                $distributerList->where('gst', 'like', '%' . $request->search_gst . '%');
            }
          	if (isset($request->search_phone_number)) {
                $distributerList->where('phone_number', 'like', '%' . $request->search_phone_number . '%');
            }
            if (isset($request->name_mobile_gst_search)) {
                $distributerList->orWhere('name', 'LIKE', '%' . $request->name_mobile_gst_search . '%')
                    ->orWhere('phone_number', 'LIKE', '%' . $request->name_mobile_gst_search . '%')
                  	->orWhere('email', 'LIKE', '%' . $request->name_mobile_gst_search . '%')
                    ->orWhere('gst', 'LIKE', '%' . $request->name_mobile_gst_search . '%');
            }

            $distributerList = $distributerList->get();
          	$distributerTotalCount = Distributer::where('role', '4')->whereIn('user_id', $allUserId)->whereNotNull('name')->count();

            $distributerListArray = [];
            if (isset($distributerList)) {
                foreach ($distributerList as $key => $value) {
                    $purchesData = PurchesModel::where('distributor_id', $value->id)->count();
                    $purchesDataTotal = PurchesModel::where('distributor_id', $value->id)->sum('net_amount');

                    $purchesReturnAmount = PurchesReturn::where('distributor_id', $value->id)->sum('net_amount');
                    $distributorAmount = DistributorPrchesReturnTable::where('distributor_id', $value->id)->sum('amount');
                    $purchesPendingAmount = $purchesReturnAmount - $distributorAmount;

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);

                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $purchesDuePayment = PurchesPaymentDetails::where('distributor_name', $value->id)->whereIn('user_id', $allUserId)->sum('due_amount');


                    $status = null;
                    if ($value->status == '0') {
                        $status = 'Deactive';
                    } else {
                        $status = 'Active';
                    }

                    $distributerListArray[$key]['id'] = isset($value->id) ? $value->id : '';
                    $distributerListArray[$key]['name'] = isset($value->name) ? $value->name : '';
                    $distributerListArray[$key]['phone_number'] = isset($value->phone_number) ? $value->phone_number : '';
                    $distributerListArray[$key]['email'] = isset($value->email) ? $value->email : '';
                    $distributerListArray[$key]['gst'] = isset($value->gst) ? $value->gst : "";
                    $distributerListArray[$key]['address'] = isset($value->address) ? $value->address : '';
                    $distributerListArray[$key]['state'] = isset($value->state) ? $value->state : '';
                    $distributerListArray[$key]['purches_return_bill_amount'] = isset($purchesPendingAmount) ? (string)$purchesPendingAmount : '';
                    $distributerListArray[$key]['due_amount'] = isset($purchesDuePayment) ? (string)$purchesDuePayment : '';
                    $distributerListArray[$key]['area'] = isset($value->area_number) ? $value->area_number : "";
                    $distributerListArray[$key]['pincode'] = isset($value->pincode) ? $value->pincode : "";
                    $distributerListArray[$key]['bank_name'] = isset($value->bank_name) ? $value->bank_name : "";
                    $distributerListArray[$key]['whatsapp_number'] = isset($value->whatsapp_number) ? $value->whatsapp_number : "";
                    $distributerListArray[$key]['account_no'] = isset($value->account_no) ? $value->account_no : "";
                    $distributerListArray[$key]['ifsc_code'] = isset($value->ifsc_code) ? $value->ifsc_code : "";
                    $distributerListArray[$key]['food_licence_number'] = isset($value->food_licence_number) ? $value->food_licence_number : "";
                    $distributerListArray[$key]['distributer_drug_licence_no'] = isset($value->distributer_drug_licence_no) ? $value->distributer_drug_licence_no : "";
                    $distributerListArray[$key]['payment_drug_days'] = isset($value->payment_drug_days) ? $value->payment_drug_days : "";
                    $distributerListArray[$key]['status'] = isset($status) ? $status : '';
                    $distributerListArray[$key]['city'] = isset($value->city) ? $value->city : '';
                    $distributerListArray[$key]['total_order'] = isset($purchesData) ? $purchesData : '';
                    $distributerListArray[$key]['total_amount'] = isset($purchesDataTotal) ? (string)$purchesDataTotal : '';
                    $distributerListArray[$key]['opening_balance'] = isset($value->balance) ? (string)$value->balance : '';
                }
            }
          	
          	$response = [
              'status' => 200,
              'count' => !empty($request->page) ? $distributerList->count() : $distributerTotalCount,
              'total_records' => $distributerTotalCount,
              'data'   => $distributerListArray,
              'message' => 'Distributor Data Fetch Successfully.',
            ];
          
          	return response()->json($response, 200);
            // return $this->sendResponse($distributerListArray, 'Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("List Distributer api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use list distributer
    public function listsDistributer(Request $request)
    {
        try {

            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);

            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $distributerList = Distributer::orderBy('id', 'DESC');
            if (isset($request->search)) {
                $distributerList->where('name', $request->search);
            }
          	
            // $distributerList = $distributerList->where('role', '4')->whereIn('user_id', $allUserId)->get();
          	$distributerList = Distributer::where('role', '4')
              ->whereIn('user_id', $allUserId)
              ->whereNotNull('name')
              ->orderByRaw("CASE WHEN name = 'OPENING DISTRIBUTOR' THEN 0 ELSE 1 END, id DESC")->get();

            $distributerListArray = [];
            if (isset($distributerList)) {
                foreach ($distributerList as $key => $value) {

                    $purchesData = PurchesModel::where('distributor_id', $value->id)->count();
                    $purchesDataTotal = PurchesModel::where('distributor_id', $value->id)->sum('net_amount');
                    $purchesReturnAmount = PurchesReturn::where('distributor_id', $value->id)->sum('net_amount');
                    $distributorAmount = DistributorPrchesReturnTable::where('distributor_id', $value->id)->sum('amount');
                    $purchesPendingAmount = $purchesReturnAmount - $distributorAmount;

                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);

                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $purchesDuePayment = PurchesPaymentDetails::where('distributor_name', $value->id)->whereIn('user_id', $allUserId)->sum('due_amount');

                    $status = null;
                    if ($value->status == '0') {
                        $status = 'Deactive';
                    } else {
                        $status = 'Active';
                    }

                    $distributerListArray[$key]['id'] = isset($value->id) ? $value->id : '';
                    $distributerListArray[$key]['name'] = isset($value->name) ? $value->name : '';
                    $distributerListArray[$key]['state'] = isset($value->state) ? $value->state : '';
                    $distributerListArray[$key]['purches_return_bill_amount'] = isset($purchesPendingAmount) ? (string)$purchesPendingAmount : '';
                    // $distributerListArray[$key]['id'] = isset($value->id) ? $value->id : '';
                    $distributerListArray[$key]['email'] = isset($value->email) ? $value->email : '';
                    $distributerListArray[$key]['due_amount'] = isset($purchesDuePayment) ? $purchesDuePayment : '';
                    $distributerListArray[$key]['phone_number'] = isset($value->phone_number) ? $value->phone_number : '';
                    $distributerListArray[$key]['status'] = isset($status) ? $status : '';
                    $distributerListArray[$key]['city'] = isset($value->city) ? $value->city : '';
                    $distributerListArray[$key]['total_order'] = isset($purchesData) ? $purchesData : '';
                    $distributerListArray[$key]['total_amount'] = isset($purchesDataTotal) ? $purchesDataTotal : '';
                    $distributerListArray[$key]['opening_balance'] = isset($value->balance) ? $value->balance : '';
                }
            }
            return $this->sendResponse($distributerListArray, 'Distributor Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("List Distributer api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use edit distributor
    public function viewDistributer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => "Enter Distributer Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $distributerEdit = Distributer::where('id', $request->id)->first();

            if (empty($distributerEdit)) {
                return $this->sendError('Data Not Found');
            }

            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);

            $allUserId = array_merge($staffGetData, $ownerGet, $userId);
          	
          	$totalBillAmount = PurchesPaymentDetails::where('distributor_name', $distributerEdit->id)->orderBy('id', 'DESC')->whereIn('user_id', $allUserId)->sum('bill_amount');
          	$totalPaidAmount = PurchesPaymentDetails::where('distributor_name', $distributerEdit->id)->orderBy('id', 'DESC')->whereIn('user_id', $allUserId)->sum('paid_amount');
          	$totalDueAmount = PurchesPaymentDetails::where('distributor_name', $distributerEdit->id)->orderBy('id', 'DESC')->whereIn('user_id', $allUserId)->sum('due_amount');
          
          	$purchesReturnAmount = PurchesReturn::where('distributor_id', $distributerEdit->id)->sum('net_amount');
            $distributorAmount = DistributorPrchesReturnTable::where('distributor_id', $distributerEdit->id)->sum('amount');
            $purchesPendingAmount = $purchesReturnAmount - $distributorAmount;

            $distributerEditData = [];
            $distributerEditData['id'] = isset($distributerEdit->id) ? $distributerEdit->id : '';
            $distributerEditData['state'] = isset($distributerEdit->state) ? $distributerEdit->state : '';
            $distributerEditData['name'] = isset($distributerEdit->name) ? $distributerEdit->name : '';
            $distributerEditData['gst_number'] = isset($distributerEdit->gst) ? $distributerEdit->gst : '';
            $distributerEditData['email'] = isset($distributerEdit->email) ? $distributerEdit->email : '';
            $distributerEditData['phone_number'] = isset($distributerEdit->phone_number) ? $distributerEdit->phone_number : '';
            $distributerEditData['address'] = isset($distributerEdit->address) ? $distributerEdit->address : '';
            $distributerEditData['account_no'] = isset($distributerEdit->account_no) ? $distributerEdit->account_no : '';
            $distributerEditData['ifsc_code'] = isset($distributerEdit->ifsc_code) ? $distributerEdit->ifsc_code : '';
            $distributerEditData['credit_due_days'] = isset($distributerEdit->payment_drug_days) ? $distributerEdit->payment_drug_days : '';
            $distributerEditData['food_licence_number'] = isset($distributerEdit->food_licence_number) ? $distributerEdit->food_licence_number : '';
            $distributerEditData['bank_name'] = isset($distributerEdit->bank_name) ? $distributerEdit->bank_name : '';
          	$distributerEditData['total_bill_amount'] = isset($totalBillAmount) ? (string)$totalBillAmount : '0';
          	$distributerEditData['total_paid_amount'] = isset($totalPaidAmount) ? (string)$totalPaidAmount : '0';
          	$distributerEditData['total_due_amount'] = isset($totalDueAmount) ? (string)$totalDueAmount : '0';
            $distributerEditData['total_cn_amount'] = isset($purchesPendingAmount) ? (string)$purchesPendingAmount : '0';

            $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
            $purchesPayment = PurchesPaymentDetails::where('distributor_name', $distributerEdit->id)->orderBy('id', 'DESC')->whereIn('user_id', $allUserId);
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $purchesPayment->limit($limit)->offset($offset);
            $purchesPayment = $purchesPayment->get();

            $distributerEditData['payment_list'] = [];
            if (isset($purchesPayment)) {
                foreach ($purchesPayment as $key => $list) {
                    $nameData = Distributer::where('id', $list->distributor_name)->first();
                    $note = PurchesPayment::where('id', $list->payment_id)->first();
                    $purchesPaymentCount = PurchesPaymentDetails::where('distributor_name', $distributerEdit->id)->orderBy('id', 'DESC')->whereIn('user_id', $allUserId)->count();
                    $purchesId = PurchesModel::where('bill_no', $list->bill_no)->first();
                    $distributerEditData['payment_list'][$key]['id'] = isset($list->id) ? $list->id : "";
                    $distributerEditData['payment_list'][$key]['purches_id'] = isset($purchesId->id) ? $purchesId->id : "";
                    $distributerEditData['payment_list'][$key]['distributor_name'] = isset($nameData->name) ? $nameData->name : "";
                    $distributerEditData['payment_list'][$key]['note'] = isset($note->note) ? $note->note : "";
                    $distributerEditData['payment_list'][$key]['payment_mode'] = isset($list->payment_mode) ? $list->payment_mode : "";
                    $distributerEditData['payment_list'][$key]['payment_date'] = isset($list->payment_date) ? date("d-m-Y", strtotime($list->payment_date)) : "";
                    $distributerEditData['payment_list'][$key]['paid_amount'] = isset($list->paid_amount) ? $list->paid_amount : "";
                    $distributerEditData['payment_list'][$key]['due_amount'] = isset($list->due_amount) ? $list->due_amount : "";
                    $distributerEditData['payment_list'][$key]['bill_amount'] = isset($list->bill_amount) ? $list->bill_amount : "";
                    $distributerEditData['payment_list'][$key]['status'] = isset($list->status) ? $list->status : "";
                    $distributerEditData['payment_list'][$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                    $distributerEditData['payment_list'][$key]['count'] = isset($purchesPaymentCount) ? $purchesPaymentCount : "";
                }
            }
            $data = auth()->user();

            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $purchesDetails = PurchesModel::where('distributor_id',$request->id)->whereIn('user_id', $allUserId);
            $purchesDetailsCount = $purchesDetails->count();
            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $purchesDetails->offset($offset)->limit($limit);
            $purchesDetails = $purchesDetails->get();

            $distributerEditData['purchase'] = [];
            if ($purchesDetails->count() > 0) { // Checking if there are any results
                foreach ($purchesDetails as $keys => $list) {
                    $distributorData = Distributer::where('id', $list->distributor_id)->first();

                    $TotalAmount = PurchesDetails::where('purches_id', $list->id)->sum('amount');
                    $TotalQty = PurchesDetails::where('purches_id', $list->id)->sum('qty');
                    $TotalFreeQty = PurchesDetails::where('purches_id', $list->id)->sum('fr_qty');
                    $totalStock = $TotalQty + $TotalFreeQty;

                    $bankAccount = BankAccount::where('id', $list->payment_type)->first();

                    $distributerEditData['purchase'][$keys]['id'] = $list->id;
                    $distributerEditData['purchase'][$keys]['bill_no'] = $list->bill_no;
                    $distributerEditData['purchase'][$keys]['bill_date'] = $list->bill_date;
                    $distributerEditData['purchase'][$keys]['payment_mode'] = isset($bankAccount->bank_name) ? $bankAccount->bank_name : $list->payment_type;
                    $distributerEditData['purchase'][$keys]['qty'] = (string)$totalStock;
                    $distributerEditData['purchase'][$keys]['count'] = $purchesDetailsCount;
                    $distributerEditData['purchase'][$keys]['total_amount'] = (string)round($list->net_amount, 2);
                }

                $data = auth()->user();

                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $purchesDetails = PurchesReturn::whereIn('user_id', $allUserId);
                $purchesDetailsCount = $purchesDetails->count();
                $limit = 10;
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
                $purchesDetails->offset($offset)->limit($limit);
                $purchesDetails = $purchesDetails->orderBy('id', 'DESC')->get(); // Executing the query here
                $distributerEditData['purchase_return'] = [];

                if ($purchesDetails->count() > 0) { // Checking if there are any results
                    foreach ($purchesDetails as $key => $list) {
                        $distributorData = Distributer::where('id', $list->distributor_id)->first();
                        $userIdData = User::where('id', $list->user_id)->first();
                        $TotalAmount = PurchesReturnDetails::where('purches_id', $list->id)->sum('amount');
                        $cnAmount = DistributorPrchesReturnTable::where('purches_return_bill_id', $list->id)->sum('amount');
                        $bankAccount = BankAccount::where('id', $list->payment_type)->first();

                        $TotalQty = PurchesReturnDetails::where('purches_id', $list->id)->sum('qty');
                        $TotalFreeQty = PurchesReturnDetails::where('purches_id', $list->id)->sum('fr_qty');
                        $totalStock = $TotalQty + $TotalFreeQty;

                        $distributerEditData['purchase_return'][$key]['id'] = $list->id;
                        $distributerEditData['purchase_return'][$key]['bill_no'] = $list->bill_no;
                        $distributerEditData['purchase_return'][$key]['bill_date'] = $list->select_date;
                        $distributerEditData['purchase_return'][$key]['payment_mode'] = isset($bankAccount->bank_name) ? $bankAccount->bank_name : $list->payment_type;
                        $distributerEditData['purchase_return'][$key]['qty'] = (string)$totalStock;
                        $distributerEditData['purchase_return'][$key]['count'] = $purchesDetailsCount;
                        $distributerEditData['purchase_return'][$key]['total_amount'] = (string)round($TotalAmount, 2);
                    }
                }
            }

            return $this->sendResponse($distributerEditData, 'Distributor Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("Edit Distributer api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use update distributor
    public function updateDistributer(Request $request)
    {
        try {
            $distributer_details = Distributer::where('id', $request->id)->first();
            $distributer_details->name = $request->distributor_name;
            $distributer_details->email = $request->email;
            $distributer_details->state = $request->state;
            $distributer_details->phone_number = $request->mobile_no;
            $distributer_details->address = $request->address;
            $distributer_details->distributer_id = $request->id;
            $distributer_details->gst = $request->gst_number;
            $distributer_details->area_number = $request->area;
            $distributer_details->pincode = $request->pincode;
            $distributer_details->bank_name = $request->bank_name;
            $distributer_details->whatsapp_number = $request->whatsapp;
            $distributer_details->account_no = $request->account_no;
            $distributer_details->ifsc_code = $request->ifsc_code;
            $distributer_details->food_licence_number = $request->food_licence_no;
            $distributer_details->distributer_drug_licence_no = $request->distributor_durg_distributor;
            $distributer_details->payment_drug_days = $request->payment_due_days;
            $distributer_details->update();

            $userLogs = new LogsModel;
            $userLogs->message = 'Distributer Updated';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            return $this->sendResponse('', 'Distributor Updated Successfully.');
        } catch (\Exception $e) {
            Log::info("Update Distributer api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use delete distributor
    public function deleteDistributer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Enter Distributer Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $distributer_details_delete = Distributer::where('id', $request->id)->first();
            $distributerDelete = User::where('id', $request->id)->first();
            if (isset($distributerDelete) && isset($distributer_details_delete)) {
                $distributer_details_delete->delete();
                $distributerDelete->delete();
            }
            $userLogs = new LogsModel;
            $userLogs->message = 'Distributer Deleted';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
            return $this->sendResponse('', 'Distributer Deleted Successfully.');
        } catch (\Exception $e) {
            Log::info("Delete Distributer api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function importDistributer(Request $request)
    {
        try {
            $file = $request->file;
            $filePath = $file->getRealPath();

            $data = array_map('str_getcsv', file($filePath));
            array_shift($data);

            if (isset($data)) {
                foreach ($data as $list) {
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();

                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $customerName =  CustomerModel::where('name', 'Direct Customers')->first();
                    $customerIds = isset($customerName->id) ? $customerName->id : "";
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                    // $userEmail = Distributer::whereIn('user_id',$allUserId)->where('email', $list[1])->first();
                    //if (empty($userEmail)) {
                    $distributer_details = new Distributer;
                    $distributer_details->name = isset($list[0]) ? $list[0] : "";
                    $distributer_details->email = isset($list[1]) ? $list[1] : "";
                    $distributer_details->phone_number = isset($list[2]) ? $list[2] : "";
                    $distributer_details->address = isset($list[3]) ? $list[3] : "";
                    $distributer_details->user_id = auth()->user()->id;
                    $distributer_details->balance = '0';
                    $distributer_details->status = '1';
                    $distributer_details->role = '4';
                    $distributer_details->gst = isset($list[4]) ? $list[4] : "";
                    $distributer_details->area_number = isset($list[5]) ? $list[5] : "";
                    $distributer_details->pincode = isset($list[6]) ? $list[6] : "";
                    $distributer_details->bank_name = isset($list[7]) ? $list[7] : "";
                    $distributer_details->whatsapp_number = isset($list[8]) ? $list[8] : "";
                    $distributer_details->account_no = isset($list[9]) ? $list[9] : "";
                    $distributer_details->ifsc_code = isset($list[10]) ? $list[10] : "";
                    $distributer_details->food_licence_number = isset($list[11]) ? $list[11] : "";
                    $distributer_details->distributer_drug_licence_no = isset($list[12]) ? $list[12] : "";
                    $distributer_details->payment_drug_days = isset($list[13]) ? $list[13] : "";
                    $distributer_details->state = isset($list[14]) ? $list[14] : "";
                    $distributer_details->save();
                    // }
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Distributer Import';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            return $this->sendResponse("", 'Distributer Import Successfully.');
        } catch (\Exception $e) {
            Log::info("Customer Import API: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use distributer list iteam
    public function distributerPurchesList(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'distributer_id' => 'required',
            ], [
                'distributer_id.required' => 'Enter Distributer Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $distributer = Distributer::where('id', $request->distributer_id)->first();

            $distributerData  = [];
            if (isset($distributer)) {
                $distributerData['id'] = isset($distributer->id) ? $distributer->id : "";
                $distributerData['state'] = isset($distributer->state) ? $distributer->state : "";
                $distributerData['name'] = isset($distributer->name) ? $distributer->name : "";
                $distributerData['phone_number'] = isset($distributer->phone_number) ? $distributer->phone_number : "";
                $distributerData['email'] = isset($distributer->email) ? $distributer->email : "";
                $distributerData['gst_pan'] = isset($distributer->gst_pan) ? $distributer->gst_pan : "";
                $distributerData['address'] = isset($distributer->address) ? $distributer->address : "";
                $distributerData['purches_item'] = [];
                if (isset($distributer->getPurches)) {
                    foreach ($distributer->getPurches as $key => $list) {
                        $distributerData['purches_item'][$key]['id'] = isset($list->id) ? $list->id : "";
                        $distributerData['purches_item'][$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                        $distributerData['purches_item'][$key]['entry_date'] = isset($list->created_at) ? date("d-m-Y", strtotime($list->created_at))  : "";
                        $distributerData['purches_item'][$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date : "";
                        $distributerData['purches_item'][$key]['due_date'] = isset($list->due_date) ? $list->due_date : "";
                        $distributerData['purches_item'][$key]['net_amount'] = isset($list->net_amount) ? $list->net_amount : "";
                    }
                }
            }

            return $this->sendResponse($distributerData, 'Distributer List Successfully');
        } catch (\Exception $e) {
            Log::info("Delete Distributer api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function distributer purches details  
    public function purchesDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'purches_id' => 'required',
            ], [
                'purches_id.required' => 'Enter Purches Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $purchesData = PurchesModel::where('id', $request->purches_id)->first();
            $purchesDetails = [];
            if (isset($purchesData)) {
                $purchesDetails['id'] = isset($purchesData->id) ? $purchesData->id : "";
                $purchesDetails['bill_no'] = isset($purchesData->bill_no) ? $purchesData->bill_no : "";
                $purchesDetails['bill_date'] = isset($purchesData->bill_date) ? $purchesData->bill_date : "";
                $purchesDetails['entry_date'] = isset($purchesData->created_at) ? date('d-m-Y H:i a', strtotime($purchesData->created_at))  : "";
                $purchesDetails['due_date'] = isset($purchesData->due_date) ? $purchesData->due_date : "";
                $purchesDetails['ptr_total'] = isset($purchesData->ptr_total) ? $purchesData->ptr_total : "";
                $purchesDetails['ptr_discount'] = isset($purchesData->ptr_discount) ? $purchesData->ptr_discount : "";
                $purchesDetails['cess'] = isset($purchesData->cess) ? $purchesData->cess : "";
                $purchesDetails['tcs'] = isset($purchesData->tcs) ? $purchesData->tcs : "";
                $purchesDetails['extra_charge'] = isset($purchesData->extra_charge) ? $purchesData->extra_charge : "";
                $purchesDetails['adjustment_amoount'] = isset($purchesData->adjustment_amoount) ? $purchesData->adjustment_amoount : "";
                $purchesDetails['round_off'] = isset($purchesData->round_off) ? $purchesData->round_off : "";
                $purchesDetails['net_amount'] = isset($purchesData->net_amount) ? $purchesData->net_amount : "";

                $purchesDetails['purches_details'] = [];

                if (isset($purchesData->getPurchesDetails)) {
                    foreach ($purchesData->getPurchesDetails as $key => $list) {
                        $purchesDetails['purches_details'][$key]['id'] = isset($list->id) ? $list->id : "";
                        $purchesDetails['purches_details'][$key]['iteam_name'] = isset($list->getIteam) ? $list->getIteam->iteam_name : "";
                        $purchesDetails['purches_details'][$key]['batch'] = isset($list->batch) ? $list->batch : "";
                        $purchesDetails['purches_details'][$key]['exp_dt'] = isset($list->exp_dt) ? $list->exp_dt : "";
                        $purchesDetails['purches_details'][$key]['mrp'] = isset($list->mrp) ? $list->mrp : "";
                        $purchesDetails['purches_details'][$key]['ptr'] = isset($list->ptr) ? $list->ptr : "";
                        $purchesDetails['purches_details'][$key]['d_percent'] = isset($list->d_percent) ? $list->d_percent : "";
                        $purchesDetails['purches_details'][$key]['qty'] = isset($list->qty) ? $list->qty : "";
                        $purchesDetails['purches_details'][$key]['fr_qty'] = isset($list->fr_qty) ? $list->fr_qty : "";
                        $purchesDetails['purches_details'][$key]['gst'] = isset($list->gst) ? $list->gst : "";
                        $purchesDetails['purches_details'][$key]['amount'] = isset($list->amount) ? $list->amount : "";
                    }
                }
            }

            return $this->sendResponse($purchesDetails, 'Purchase Details List Successfully.');
        } catch (\Exception $e) {
            Log::info("Purches Distributer api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
