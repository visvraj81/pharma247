<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\CustomerModel;
use App\Models\SalesModel;
use App\Models\LedgerModel;
use App\Models\salesDetails;
use App\Models\SalesReturnDetails;
use App\Models\SalesReturn;
use App\Models\User;
use App\Models\LogsModel;
use App\Models\BankAccount;
use App\Models\CashManagement;
use App\Models\PassBook;
use App\Models\RoyaltyPoint;
use App\Models\PatientsModel;

class CustomerController extends ResponseController
{
    // this function use create customer 
    public function createCustomer(Request $request)
    {
        try {
            if(isset($request->email)) {
                $userEmail = CustomerModel::where('email', $request->email)->where('user_id',auth()->user()->id)->first();
                if (!empty($userEmail)) {
                    return $this->sendError('Email Already Exist');
                }
            }
          
          	$patientData = PatientsModel::where('mobile_number',$request->mobile_no)->first();

            $customer = new CustomerModel;
          	if(isset($patientData) && !empty($patientData)) {
            	$customer->patient_id = $patientData->id;
            } else {
            	$customer->patient_id = null;
            }
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone_number = $request->mobile_no;
            $customer->state = $request->state;
            $customer->city = $request->city;
            $customer->address = $request->area;
            $customer->address_line_two = $request->address;
            $customer->zip_code = $request->pin_code;
            // $customer->password = '';
            $customer->status = '1';
            $customer->role = '3';
            $customer->user_id = auth()->user()->id;
            $customer->balance = $request->amount;
            $customer->save();

            $customerlefger = new LedgerModel;
            $customerlefger->owner_id = $customer->id;
            $customerlefger->entry_date = date('Y-m-d');
            $customerlefger->transction = 'Opening Balance';
            $customerlefger->voucher = '0';
            $customerlefger->bill_no = '#';
            $customerlefger->credit = '0';
            $customerlefger->debit = null;
            $customerlefger->balance = $request->amount;
            $customerlefger->save();
            
            $userLogs = new LogsModel;
            $userLogs->message = 'Customer Added';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $dataDetails = [];
            return $this->sendResponse('', 'Customer Added Successfully.');
        } catch (\Exception $e) {
            Log::info("Create Customer api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    public function customerView(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required'=>'Please Enter Customer Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }
            
           $userData = CustomerModel::where('id',$request->id)->first();
         
           $customerDetails = [];
           if(isset( $userData))
           {
               $staffGetData = User::where('create_by',auth()->user()->id)->pluck('id')->toArray();
            
               $ownerGet = User::where('id',auth()->user()->id)->pluck('create_by')->toArray();
            
               $userId = array(auth()->user()->id);
               $customerName =  CustomerModel::where('name','Direct Customers')->first();
               $customerIds = isset($customerName->id) ? $customerName->id :"";
               $allUserId = array_merge($staffGetData,$ownerGet,$userId);
             
               $salesAmountPoint = SalesModel::where('customer_id', $request->id)
                            ->whereIn('user_id', $allUserId)
                            ->get();

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
               
               $royaltiPoint = SalesModel::where('customer_id',$request->id)->whereIn('user_id',$allUserId)->sum('roylti_point');
             
               $customerLoyaltiPoint = SalesModel::where('customer_id', $request->id)
                ->whereIn('user_id', $allUserId)
                ->orderBy('id', 'DESC')->first();
             	if($userData->name == 'Direct Customers')
                {
                	$customerTotalLoyaltiPoint = "0";
                }else
                {
                	$customerTotalLoyaltiPoint = (string)($customerLoyaltiPoint ? round($customerLoyaltiPoint->today_loylti_point + $customerLoyaltiPoint->previous_loylti_point) : 0);
                }
          
               $salesReturnAmount = SalesReturn::whereIn('user_id',$allUserId)->where('customer_id',$request->id)->sum('net_amount');
               $customerDetails['id'] = isset($userData->id) ? $userData->id :"";
               $customerDetails['name'] = isset($userData->name) ? $userData->name :"";
               $customerDetails['email'] = isset($userData->email) ? $userData->email :"";
               $customerDetails['phone_number'] = isset($userData->phone_number) ? $userData->phone_number :"";
               $customerDetails['state'] = isset($userData->state) ? $userData->state :"";
               // $customerDetails['roylti_point'] = isset($totalPercent) ? (string) (round($totalPercent) - $royaltiPoint) : "";
               $customerDetails['roylti_point'] = $customerTotalLoyaltiPoint;
               $customerDetails['city'] = isset($userData->city) ? $userData->city :"";
               $customerDetails['sales_amount'] = isset($salesAmount) ? (string)$salesAmount :"";
               $customerDetails['sales_return_amount'] = isset($salesReturnAmount) ? (string)$salesReturnAmount :"";
               $customerDetails['area'] = isset($userData->address) ? $userData->address :"";
               $customerDetails['address'] = isset($userData->address_line_two) ? $userData->address_line_two :"";
               $customerDetails['pin_code'] = isset($userData->zip_code) ? $userData->zip_code :"";
            
                    $staffGetData = User::where('create_by',auth()->user()->id)->pluck('id')->toArray();
            
                    $ownerGet = User::where('id',auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $customerName =  CustomerModel::where('name','Direct Customers')->first();
                    $customerIds = isset($customerName->id) ? $customerName->id :"";
                    $allUserId = array_merge($staffGetData,$ownerGet,$userId);
              
                    $salesDetails = SalesModel::where('customer_id',$userData->id)->whereIn('user_id',$allUserId)->orderBy('id', 'DESC');
                    
                    // $salesDetails = salesDetails::whereIn('user_id',$allUserId)->whereIn('sales_id',$salesId)->orderBy('id', 'DESC');
                    $totalCount = $salesDetails->count();
                   
                    $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                    $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
                    $offset = ($page - 1) * $limit;
                    $salesDetails = $salesDetails->limit($limit)->offset($offset)->get();
                    
                    $customerDetails['sales'] = [];
                    $qty_total_sales = 0;
                    if(isset($salesDetails))
                    {
                        foreach($salesDetails as $s => $listSales)
                        {
                                  $bankName  = BankAccount::where('id',$listSales->payment_name)->first();
                                  $salesDetailsTotal = salesDetails::where('sales_id',$listSales->id)->sum('qty');
                                  $customerDetails['sales'][$s]['id'] = isset($listSales->id) ? $listSales->id : "";
                                  $customerDetails['sales'][$s]['roylti_point'] = isset($listSales->roylti_point) ? $listSales->roylti_point : "";
                                  $customerDetails['sales'][$s]['sales_id'] = isset($listSales->id) ? $listSales->id : "";
                                  $customerDetails['sales'][$s]['bill_no'] = isset($listSales->bill_no) ? $listSales->bill_no : "";
                                  $customerDetails['sales'][$s]['bill_date'] = isset($listSales->bill_date) ? date("d-m-Y", strtotime($listSales->bill_date))  : "";
                                  $customerDetails['sales'][$s]['qty'] = isset($salesDetailsTotal) ? (string)$salesDetailsTotal : "";
                          		  $customerDetails['sales'][$s]['amt'] = isset($listSales->mrp_total) ? (string)$listSales->mrp_total: "";
                                  // $customerDetails['sales'][$s]['amt'] = isset($listSales->net_amt) ? (string)$listSales->net_amt: "";
                                  $customerDetails['sales'][$s]['count'] = isset($totalCount) ? $totalCount: ""; 
                                  $customerDetails['sales'][$s]['payment_id'] = isset($listSales->payment_name) ?$listSales->payment_name: "";
                                  $customerDetails['sales'][$s]['payment_mode'] = isset( $bankName->bank_name) ? $bankName->bank_name : $listSales->payment_name;
                                  if($listSales->payment_name == 'credit')
                                  {
                                    $customerDetails['sales'][$s]['status'] = "due"; 
                                  }else{
                                    $customerDetails['sales'][$s]['status'] = "Paid";
                                  }
                         }
                    }
                    
                    $customerDetails['sales_return'] = [];
                    $salesreturnDetails = SalesReturn::whereIn('user_id',$allUserId)->where('customer_id',$userData->id)->orderBy('id', 'DESC');
                 
                    //$salesreturnDetails = SalesReturnDetails::whereIn('sales_id',$salesReturnId)->orderBy('id', 'DESC');
                    $totalSalesCount = $salesreturnDetails->count();
                    $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                    $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
                    $offset = ($page - 1) * $limit;
                    $salesreturnDetails = $salesreturnDetails->limit($limit)->offset($offset)->get();
                    if(isset($salesreturnDetails))
                    {
                        // $salesreturnTotals = SalesReturnDetails::where('sales_id',$salesReturnId)->orderBy('id', 'DESC')->count();
                        foreach($salesreturnDetails as $sr => $listData)
                        {
                              $bankName = BankAccount::where('id',$listData->payment_name)->first();
                              $salesDetailsQty = SalesReturnDetails::where('sales_id',$listSales->id)->sum('qty');
                              $customerDetails['sales_return'][$sr]['id'] = isset($listData->id) ? $listData->id : "";
                              $customerDetails['sales_return'][$sr]['sales_id'] = isset($listData->id) ? (string)$listData->id : "";
                              $customerDetails['sales_return'][$sr]['bill_no'] = isset($listData->bill_no) ? $listData->bill_no : "";
                              $customerDetails['sales_return'][$sr]['bill_date'] = isset($listData->date) ?  date("d-m-Y", strtotime($listData->date)) : "";
                              $customerDetails['sales_return'][$sr]['qty'] = isset($salesDetailsQty) ? (string)$salesDetailsQty : "";
                              $customerDetails['sales_return'][$sr]['amt'] = isset($listData->net_amount) ? $listData->net_amount: "";
                              $customerDetails['sales_return'][$sr]['count'] = isset( $totalSalesCount) ?  $totalSalesCount: "";
                              $customerDetails['sales_return'][$sr]['payment_id'] = isset($listData->payment_name) ?$listData->payment_name: "";
                              $customerDetails['sales_return'][$sr]['payment_name'] = isset($bankName->bank_name) ? $bankName->bank_name : $listData->payment_name;
                              if($listData->payment_name == 'credit')
                              {
                                  $customerDetails['sales'][$s]['status'] = "due"; 
                              } else{
                                  $customerDetails['sales'][$s]['status'] = "Paid";
                              }
                         }
                    }
                    $staffGetData = User::where('create_by',auth()->user()->id)->pluck('id')->toArray();
            
                    $ownerGet = User::where('id',auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $customerName =  CustomerModel::where('name','Direct Customers')->first();
                    $customerIds = isset($customerName->id) ? $customerName->id :"";
                    $allUserId = array_merge($staffGetData,$ownerGet,$userId);
                      
                    // $salesDetailsTotal = SalesModel::whereIn('user_id',$allUserId)->where('customer_id',$userData->id)->sum('net_amt');
             		$salesDetailsTotal = SalesModel::whereIn('user_id',$allUserId)->where('customer_id',$userData->id)->sum('mrp_total');
                    $salesreturnDetailsTotal = SalesReturn::whereIn('user_id',$allUserId)->where('customer_id',$userData->id)->sum('net_amount');
             	
                    $customerDetails['balance'] = (string)round($salesDetailsTotal,2) - (string)round($salesreturnDetailsTotal,2);
                    $customerDetails['sales_amount'] = (string)$salesDetailsTotal;
                    $customerDetails['return_sales_amount'] = (string)$salesreturnDetailsTotal;
            }
            return $this->sendResponse($customerDetails, 'Customer Details Get Successfully.');
        }  catch (\Exception $e) {
            Log::info("Create Customer view api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use in customer list
    public function listCustomer(Request $request)
    {
        try {
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = [auth()->user()->id];
            $allUserId = array_unique(array_merge($staffGetData, $ownerGet, $userId));

            // Get Direct Customer record
            $directCustomerRecord = CustomerModel::where('name', 'Direct Customers')->first();
            $directCustomerId = $directCustomerRecord->id ?? null;

            // Base customer query
            $customerQuery = CustomerModel::query()
                ->where('role', '3')
                ->where(function ($query) use ($allUserId, $directCustomerId) {
                    $query->whereIn('user_id', $allUserId);
                    if ($directCustomerId) {
                        $query->orWhere('id', $directCustomerId);
                    }
                });

            // Apply filters
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start_date = date('Y-m-d', strtotime($request->start_date));
                $end_date = date('Y-m-d', strtotime($request->end_date));
                $customerQuery->whereBetween('created_at', [$start_date, $end_date]);
            }

            if ($request->filled('customer_name')) {
                $customerQuery->where('name', 'LIKE', '%' . $request->customer_name . '%');
            }

            if ($request->filled('mobile_number')) {
                $customerQuery->where('phone_number', 'LIKE', '%' . $request->mobile_number . '%');
            }

            if ($request->filled('email')) {
                $customerQuery->where('email', 'LIKE', '%' . $request->email . '%');
            }

            if ($request->filled('area')) {
                $customerQuery->where('address', 'LIKE', '%' . $request->area . '%');
            }

            if ($request->filled('amount')) {
                $customerQuery->where('balance', 'LIKE', '%' . $request->amount . '%');
            }

            if ($request->filled('state')) {
                $customerQuery->where('state', 'LIKE', '%' . $request->state . '%');
            }

            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $customerQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', $searchTerm)
                          ->orWhere('phone_number', 'LIKE', $searchTerm);
                });
            }

            if ($request->filled('due_only')) {
                $creditCustomerIds = SalesModel::where('payment_name', 'credit')->pluck('customer_id')->toArray();
                $customerQuery->whereIn('id', $creditCustomerIds);
            }

            // Pagination
            if($request->page == 1) {
                $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
            } else {
                $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
            }
            // $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 9;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;

            // Get paginated data (excluding "Direct Customers" if not on page 1)
            $paginatedCustomers = $customerQuery->orderBy('id', 'DESC')->paginate($limit, ['*'], 'page', $page);

            $customerListArray = [];

            // Add "Direct Customers" on Page 1
            if ($page == 1 && $directCustomerRecord) {
                $customerListArray[] = $this->processCustomer($directCustomerRecord);
            }

            // Add paginated customer records (excluding "Direct Customers" if already added)
            foreach ($paginatedCustomers as $customer) {
                if (!$directCustomerRecord || $customer->id !== $directCustomerRecord->id) {
                    $customerListArray[] = $this->processCustomer($customer);
                }
            }

            $customerTotalCount = CustomerModel::where('role', '3')->whereIn('user_id', $allUserId)->count();
            $totalWithDirect = $directCustomerRecord ? $customerTotalCount + 1 : $customerTotalCount;

            $paginatedCustomersCount = 0;
            if($request->page == 1) {
                $paginatedCustomersCount = $paginatedCustomers->count() + 1;
            }else {
                $paginatedCustomersCount = $paginatedCustomers->count();
            }

            return response()->json([
                'status' => 200,
                'count' => $paginatedCustomersCount,
                'total_records' => $totalWithDirect,
                'data' => $customerListArray,
                'message' => 'Customer Data Fetch Successfully.'
            ]);

        } catch (\Exception $e) {
            Log::info("List Customer API error: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
  
    function processCustomer($customer)
    {
          $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();

          $ownerGet = User::where('id',auth()->user()->id)->pluck('create_by')->toArray();
          $userId = array(auth()->user()->id);
          $customerName =  CustomerModel::where('name','Direct Customers')->first();
          $customerIds = isset($customerName->id) ? $customerName->id :"";
          $allUserId = array_merge($staffGetData,$ownerGet,$userId);

          $status = ($customer->status == '0') ? 'Deactivate' : 'Active';

          $salesTotal = SalesModel::where('customer_id', $customer->id)
            ->where('payment_name', 'credit')
            ->whereIn('user_id',$allUserId)
            ->sum('net_amt');

          $salesData = SalesModel::where('customer_id', $customer->id)
            ->orderBy('created_at', 'DESC')
            ->whereIn('user_id',$allUserId)
          ->first();

         $salesCount = SalesModel::where('customer_id', $customer->id)->whereIn('user_id',$allUserId)->count();

         $salesSum = SalesModel::where('customer_id', $customer->id)->whereIn('user_id',$allUserId)->sum('net_amt');
         $salesReturnSum = SalesReturn::where('customer_id', $customer->id)->whereIn('user_id',$allUserId)->sum('net_amount');
         $totalSales =  $salesSum - $salesReturnSum;

         $statusText = $salesTotal != 0 ? 'due' : '';

         $salesAmountPoint = SalesModel::where('customer_id', $customer->id)
           ->whereIn('user_id', $allUserId)
           ->orderBy('id','DESC')
           ->get();
          $customerLoyaltiPoint = SalesModel::where('customer_id', $customer->id)
           ->whereIn('user_id', $allUserId)
           ->orderBy('id','DESC')->first();
          // dd($customerLoyaltiPoint,$customerLoyaltiPoint->today_loylti_point + $customerLoyaltiPoint->previous_loylti_point);

         $totalPercent = 0;
         if ($salesAmountPoint->isNotEmpty()) {
              foreach ($salesAmountPoint as $sales) {
                 $royaltyPoints = RoyaltyPoint::where('user_id', auth()->user()->id)->get();

                 foreach ($royaltyPoints as $royalty) {
                    $salesAmount = $sales->net_amt;

                    if ($salesAmount >= $royalty->minimum && $salesAmount <= $royalty->maximum) {
                      $totalPercent += $royalty->percent;
                    }
                 }
              }
          }

          $royaltiPoint = SalesModel::where('customer_id',$customer->id)->whereIn('user_id',$allUserId)->sum('roylti_point');

          if($customer->name == 'Direct Customers') {
              $royaltiPoint = "0";
          } else {
              $royaltiPoint = (string)($customerLoyaltiPoint ? round($customerLoyaltiPoint->today_loylti_point + $customerLoyaltiPoint->previous_loylti_point) : 0);
          }
      
      	// $salesDetailsTotal = SalesModel::whereIn('user_id',$allUserId)->where('customer_id',$customer->id)->sum('net_amt');
      	$salesDetailsTotal = SalesModel::whereIn('user_id',$allUserId)->where('customer_id',$customer->id)->sum('mrp_total');
        $salesreturnDetailsTotal = SalesReturn::whereIn('user_id',$allUserId)->where('customer_id',$customer->id)->sum('net_amount');

          return [
                'id' => $customer->id ?? '',
                // 'roylti_point' => (string)(round($totalPercent) - $royaltiPoint) ?? '',
                'roylti_point' => $royaltiPoint,
                'register' => isset($customer->created_at) ? date("d-m-Y H:i a", strtotime($customer->created_at)) : '',
                'name' => $customer->name ?? '',
                'state' => $customer->state ?? '',
                'email' => $customer->email ?? '',
                'phone_number' => $customer->phone_number ?? '',
                'due_amount' => isset($salesTotal) ? (string)$salesTotal : '',
                'pin_code' => $customer->zip_code ?? '',
                'city' => $customer->city ?? '',
                'balance' => $customer->balance ?? '',
                'area' => $customer->address ?? '',
                'address' => $customer->address_line_two ?? '',
                'last_order_date' => isset($salesData->created_at) ? date("d-m-Y H:i a", strtotime($salesData->created_at)) : date("d-m-Y H:i a"),
                'total_order' => $salesCount ?? '',
            	'total_amount' => (string) (round($salesDetailsTotal, 2) - round($salesreturnDetailsTotal, 2)),
                // 'total_amount' => (isset($salesDetailsTotal, $salesreturnDetailsTotal) && (round($salesDetailsTotal, 2) - round($salesreturnDetailsTotal, 2)) > 0) ? (string)(round($salesDetailsTotal, 2) - round($salesreturnDetailsTotal, 2)) 
            	//	: (string)($customer?->balance ?? 0),
                'status' => $statusText,
          ];
      }

    // this function use edit customer
    public function editCustomer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => "Enter Customer Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $customerEdit = CustomerModel::where('id', $request->id)->first();

            if (empty($customerEdit)) {
                return $this->sendError('Data Not Found');
            }

            $customerEditData = [];
            $customerEditData['id'] = isset($customerEdit->id) ? $customerEdit->id : '';
            $customerDetails['state'] = isset($customerEdit->state) ? $customerEdit->state :""; 
            $customerEditData['name'] = isset($customerEdit->name) ? $customerEdit->name : '';
            $customerEditData['email'] = isset($customerEdit->email) ? $customerEdit->email : '';
            $customerEditData['phone_number'] = isset($customerEdit->phone_number) ? $customerEdit->phone_number : '';
            $customerEditData['status'] = isset($customerEdit->status) ? $customerEdit->status : '';
            $customerEditData['city'] = isset($customerEdit->city) ? $customerEdit->city : '';
            // $customerEditData['set_default_discount'] = isset($customerEdit->set_default_discount) ? $customerEdit->set_default_discount : '';
            $customerEditData['address'] = isset($customerEdit->address_line_two) ? $customerEdit->address_line_two : '';
            $customerEditData['area'] = isset($customerEdit->address) ? $customerEdit->address : '';
            // $customerEditData['zip_code'] = isset($customerEdit->zip_code) ? $customerEdit->zip_code : '';
            // $customerEditData['gst_pan'] = isset($customerEdit->gst_pan) ? $customerEdit->gst_pan : '';
            $customerEditData['amount'] = isset($customerEdit->balance) ? $customerEdit->balance : '';
          
            return $this->sendResponse($customerEditData, 'Edit Time Customer Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("Edit Customer api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use update customer
    public function updateCustomer(Request $request)
    {
        try {

            // $validator = Validator::make($request->all(), [
            //     'id' => 'required',
            //     'first_name' => 'required',
            //     'last_name' => 'required',
            //     'mobile_no' => 'required',
            //     'email' => 'required',
            //     'city'=>'required',
            // ], [
            //     'id.required'=>'please Enter Id',
            //     'first_name.required'=>'Please Enter First Name',
            //     'last_name.required'=>'Please Enter Last Name',
            //     'mobile_no.required'=>'Please Enter Mobile Number',
            //     'email.required'=>'Please Enter Email',
            //     'city'.'required'=>'Please Enter City',
               
            // ]);

            // if ($validator->fails()) {
            //     $error = $validator->getMessageBag();
            //     return $this->sendError($error->first());
            // }

            $customerUpdate = CustomerModel::find($request->id);
            $customerUpdate->name = $request->name;
            $customerUpdate->email = $request->email;
            $customerUpdate->state = $request->state;
            $customerUpdate->phone_number = $request->mobile_no;
            $customerUpdate->city = $request->city;
            $customerUpdate->address = $request->area;
            $customerUpdate->address_line_two = $request->address;
            $customerUpdate->zip_code = $request->pin_code;
            // $customerUpdate->password = '';
            $customerUpdate->status = '1';
            $customerUpdate->role = '3';
            $customerUpdate->balance = $request->amount;
            $customerUpdate->update();
            
            $userLogs = new LogsModel;
            $userLogs->message = 'Customer Updated';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            return $this->sendResponse('', 'Customer Updated Successfully.');
        } catch (\Exception $e) {
            Log::info("Update Customer api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use delete customer
    public function deleteCustomer(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Enter Customer Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $customerDelete = CustomerModel::where('id', $request->id)->first();
            if (isset($customerDelete)) {
                $customerDelete->delete();
            }
            $userLogs = new LogsModel;
            $userLogs->message = 'Customer Deleted';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
          
            return $this->sendResponse('', 'Customer Deleted Successfully.');
        } catch (\Exception $e) {
            Log::info("Delete Customer api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use customer list
    public function customerListSearch(Request $request)
    {
           try{

           $userData = CustomerModel::orderBy('id', 'DESC')->where('role','3');
           if(isset($request->name))
           {
            $userData->where('name','like', '%'.$request->name.'%');
           }
           $userData = $userData->get();

            $listData = [];
            if(isset($userData))
            {
                 foreach($userData as $key => $list)
                 {
                    $listData[$key]['id'] = isset($list->id) ? $list->id :"";
                    $listData[$key]['name'] = isset($list->name) ? $list->name .''.$list->last_name :"";
                    $listData[$key]['phone_number'] = isset($list->phone_number) ? $list->phone_number :"";
                 }
            }

            return $this->sendResponse($listData, 'Customer List Get Successfully.');
           } catch (\Exception $e) {
            Log::info("Customer list Search api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    public function importCustomer(Request $request)
    {
        try{
            $file = $request->file;
            $filePath = $file->getRealPath();
            
            $data = array_map('str_getcsv', file($filePath));
            array_shift($data);

            if(isset($data))
            {
                foreach($data as $list)
                {
                  $staffGetData = User::where('create_by',auth()->user()->id)->pluck('id')->toArray();
            
                  $ownerGet = User::where('id',auth()->user()->id)->pluck('create_by')->toArray();
                  $userId = array(auth()->user()->id);
                  $customerName =  CustomerModel::where('name','Direct Customers')->first();
                  $customerIds = isset($customerName->id) ? $customerName->id :"";
                  $allUserId = array_merge($staffGetData,$ownerGet,$userId);
                    //$userEmail = CustomerModel::whereIn('user_id',$allUserId)->where('email', $list[2])->first();
                    //if(empty($userEmail))
                    //{
                        $customer = new CustomerModel;
                        $customer->name = isset($list[0]) ? $list[0] :"";
                        $customer->phone_number = isset($list[1]) ? $list[1] :"";
                        $customer->email = isset($list[2]) ? $list[2] :"";
                        $customer->balance =  isset($list[3]) ? $list[3] :"";
                        $customer->address = isset($list[4]) ? $list[4] :"";
                        $customer->city = isset($list[5]) ? $list[5] :"";
                        $customer->address_line_two = isset($list[6]) ? $list[6] :"";
                        $customer->state = isset($list[7]) ? $list[7] :"";
                        $customer->user_id = auth()->user()->id;
                        // $customer->password = '';
                        $customer->status = '1';
                        $customer->role = '3';
                        $customer->save();
                    //}
                    
                }
            }
            $userLogs = new LogsModel;
            $userLogs->message = 'Customer Import';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
          
            return $this->sendResponse("", 'Customer Import Successfully.'); 
        } catch (\Exception $e) {
                    dD($e);
            Log::info("Customer Import api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    // this function use customer list
    public function customerListView(Request $request)
    {
         try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Enter Customer Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }
            
            $salesData = SalesModel::where('customer_id',$request->id);
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
            $offset = ($page - 1) * $limit;
            $salesData->limit($limit)->offset($offset);
            $salesData = $salesData->get();
            $salesDetails = [];
            if(isset($salesData))
            {
              foreach($salesData as $key => $list)
              {
                $salesDetails[$key]['id'] = isset($list->id) ? $list->id :"";
                $salesDetails[$key]['state'] = isset($list->state) ? $list->state :"";
                $salesDetails[$key]['customer_id'] = isset($list->customer_id) ? $list->customer_id :"";
                $salesDetails[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no :"";
                $salesDetails[$key]['entry_date'] = isset($list->created_at) ? date("d-m-Y h:i a", strtotime($list->created_at)) :"";
                $salesDetails[$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date :"";
                $salesDetails[$key]['bill_amount'] = isset($list->net_amt) ? $list->net_amt :"";
              }
            }

            return $this->sendResponse($salesDetails, 'Customer List Get Successfully.');
           } catch (\Exception $e) {
            Log::info("Customer list view api" . $e->getMessage());
            return $e->getMessage();
        }
   }
  
  public function salesBillStatus(Request $request)
  {
     try{
       
          $customerSales = SalesModel::where('id',$request->id)->first();
          
          if(isset($customerSales))
          {
            $customerSales->payment_name = $request->payment_name;
            $customerSales->update();
            
             if($request->payment_name == 'cash')
               {
                $cashManage = CashManagement::where('user_id',auth()->user()->id)->orderBy('id', 'DESC')->first();
              
                if(isset($cashManage))
                {
                   
                   $previewData = CashManagement::where('user_id',auth()->user()->id)->where('id',$cashManage->id)->where('description','Purchase')->orderBy('id', 'DESC')->first();
                   if(isset($previewData))
                   {
                       $amountData =  $cashManage->opining_balance - $customerSales->net_amt;
                       $amount = abs($amountData);
                   }else 
                   {
                    $amountData =  $cashManage->opining_balance + $customerSales->net_amt;
                      $amount = abs($amountData);
                   }
                    
                  $cashAdd = new CashManagement;
                  $cashAdd->date = $customerSales->bill_date;
                  $cashAdd->description = 'Sales Manage';
                  $cashAdd->type = 'credit';
                  $cashAdd->amount = round($customerSales->net_amt,2);
                  $cashAdd->reference_no = $customerSales->bill_no;
                  $cashAdd->voucher	 = 'sales';
                  $cashAdd->user_id = auth()->user()->id;
                  $cashAdd->opining_balance = round($amount,2);
                  $cashAdd->save();
                }else{
                  $cashAdd = new CashManagement;
                  $cashAdd->date = $customerSales->bill_date;
                  $cashAdd->description = 'Sales Manage';
                  $cashAdd->type = 'credit';
                  $cashAdd->amount = round($customerSales->net_amt,2);
                  $cashAdd->user_id = auth()->user()->id;
                  $cashAdd->reference_no = $customerSales->bill_no;
                  $cashAdd->voucher	 = 'sales';
                  $cashAdd->opining_balance = round($customerSales->net_amt,2);
                  $cashAdd->save();
                }
               }else{
                 $passBook =  PassBook::where('bank_id',$customerSales->payment_name)->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                 if(isset($passBook))
                 {
                    $amount =  $passBook->balance + $customerSales->net_amt;
            
                    $customerName  = CustomerModel::where('id',$customerSales->customer_id)->first();
                    $passbook = new  PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $customerSales->bill_date;
                    $passbook->party_name = $customerName->name;
                    $passbook->bank_id = $customerSales->payment_name;
                    $passbook->deposit = round($customerSales->net_amt,2);
                    $passbook->withdraw	 = "";
                    $passbook->balance = round($amount,2);
                    $passbook->mode = "";
                    $passbook->save();
                 }else{
                    $customerName  = CustomerModel::where('id',$customerSales->customer_id)->first();
                    $passbook = new  PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $customerSales->bill_date;
                    $passbook->party_name = $customerName->name;
                    $passbook->bank_id = $customerSales->payment_name;
                    $passbook->deposit = round($customerSales->net_amt,2);
                    $passbook->withdraw	= "";
                    $passbook->balance = round($customerSales->net_amt,2);
                    $passbook->mode = "";
                    $passbook->save();
                 }
               }
          }
       
         return $this->sendResponse("", 'Sales Bill Status Updated Successfully.');
        } catch (\Exception $e) {
            Log::info("Sales Bill Status api" . $e->getMessage());
            return $e->getMessage();
        }
    }
  
    public function royaltiPointAdd(Request $request)
    {
        $storeRoyeal = new RoyaltyPoint;
        $storeRoyeal->minimum = $request->minimum;
        $storeRoyeal->maximum = $request->maximum;
        $storeRoyeal->percent = $request->percent;
        $storeRoyeal->user_id = auth()->user()->id;
        $storeRoyeal->save();
      
        return $this->sendResponse("", 'Loyalty Point Added Successfully.');
    }
  
    public function LoyaltiPointUpdate(Request $request)
    {
        $storeRoyeal = RoyaltyPoint::find($request->id);
        if(isset($storeRoyeal))
        {
            $storeRoyeal->minimum = $request->minimum;
            $storeRoyeal->maximum = $request->maximum;
            $storeRoyeal->percent = $request->percent;
            $storeRoyeal->update();
        }

        return $this->sendResponse("", 'Loyalty Point Update Successfully.');
    }
  
    public function loyaltiPointList(Request $request)
    {
        $royalTiPoint = RoyaltyPoint::where('user_id',auth()->user()->id);
      	$limit = 10;
        $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
        $offset = ($page - 1) * $limit;
        $royalTiPoint = $royalTiPoint->offset($offset)->limit($limit)->get();
      
      	$loyaltiPointCount = RoyaltyPoint::where('user_id',auth()->user()->id)->count();

        $salesDetailsPoint = [];
        if(isset($royalTiPoint))
        {
          foreach($royalTiPoint as $key => $list)
          {
             $salesDetailsPoint[$key]['id'] = isset($list->id) ? $list->id :"";
             $salesDetailsPoint[$key]['minimum'] = isset($list->minimum) ? $list->minimum : "" ;
             $salesDetailsPoint[$key]['maximum'] = isset($list->maximum) ? $list->maximum : "" ;
             $salesDetailsPoint[$key]['percent'] = isset($list->percent) ? $list->percent : "" ;
          }
        }
      
      	$response = [
              'status' => 200,
              'count' => !empty($request->page) ? $royalTiPoint->count() : $loyaltiPointCount,
              'total_records' => $loyaltiPointCount,
              'data'   => $salesDetailsPoint,
              'message' => 'Loyalty Point Data Fetch Successfully.',
            ];
            return response()->json($response, 200);

        // return $this->sendResponse($salesDetailsPoint, 'Loyalty Point Data Get Successfully.');
    }
  
   public function loyaltiPointDelete(Request $request)
   {
        $royalTiPoint = RoyaltyPoint::where('id',$request->id)->first();
        if(isset($royalTiPoint))
        {
           $royalTiPoint->delete();
        }
     	
        return $this->sendResponse([], 'Loyalti Point Data Delete Successfully.');
   }
}
