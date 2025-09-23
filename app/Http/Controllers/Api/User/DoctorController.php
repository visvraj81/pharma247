<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoctorModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\SalesModel;
use App\Models\SalesReturn;
use App\Models\salesDetails;
use App\Models\SalesReturnDetails;
use App\Models\CustomerModel;
use App\Models\User;
use App\Models\LogsModel;
use App\Models\BankAccount;

class DoctorController extends ResponseController
{
    // this function use create doctor
    public function doctorCreate(Request $request)
    {
       try {
           if($request->default_doctor == 1)
           {
           		$doctorDataStatusUpdate = DoctorModel::where('user_id', auth()->user()->id)->get();
             	if(isset($doctorDataStatusUpdate))
                {
                	foreach($doctorDataStatusUpdate as $list)
                    {
                    	$list->by_default_check = 0;
                      	$list->update();
                    }
                }
           }
         
           if(isset($request->email))
           {
               $emailCheck = DoctorModel::where('email',$request->email)->first();
               if(isset($emailCheck))
               {
                 return $this->sendError("Email Already Exist");
               }
           }
           $doctorData  = new DoctorModel;
           $doctorData->name = $request->name;
           $doctorData->email = $request->email;
           $doctorData->phone_number = $request->mobile;
           $doctorData->license = $request->license;
           $doctorData->address = $request->address;
           $doctorData->clinic = $request->clinic;
           $doctorData->role = '5';
           $doctorData->status = '1';
           $doctorData->user_id = auth()->user()->id;
           $doctorData->by_default_check = $request->default_doctor ?? 0;
           $doctorData->save();
            
           $userLogs = new LogsModel;
           $userLogs->message = 'Doctor Added';
           $userLogs->user_id = auth()->user()->id;
           $userLogs->date_time = date('Y-m-d H:i a');
           $userLogs->save();

           return $this->sendResponse('','Doctor Create Successfully.');
          } catch (\Exception $e) {
            Log::info("Create Iteams api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    
   	public function importDoctor(Request $request)
    {
        try {   
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

                  
                        $doctorData = new DoctorModel;
                        $doctorData->name = isset($list[0]) ? $list[0] :"";
                        $doctorData->email = isset($list[1]) ? $list[1] :"";
                        $doctorData->phone_number = isset($list[2]) ? $list[2] :"";
                        $doctorData->license = isset($list[3]) ? $list[3] :"";
                        $doctorData->address = isset($list[4]) ? $list[4] :"";
                        $doctorData->clinic = isset($list[5]) ? $list[5] :"";
                        $doctorData->user_id = auth()->user()->id;
                        $doctorData->role = '5';
                        $doctorData->status = '1';
                        $doctorData->save();
                }
            }
            
            $userLogs = new LogsModel;
            $userLogs->message = 'Doctor Import';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
          
            return $this->sendResponse("", 'Doctor Data Import Successfully.'); 
        } catch (\Exception $e) {
            Log::info("Customer Import api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    public function doctorUpdate(Request $request)
    {
        try {
          	if($request->default_doctor == 1)
            {
                  $doctorDataStatusUpdate = DoctorModel::where('user_id',auth()->user()->id)->get();
                  if(isset($doctorDataStatusUpdate))
                  {
                      foreach($doctorDataStatusUpdate as $list)
                      {
                          $list->by_default_check = 0;
                          $list->update();
                      }
                  }
            }
          
            $doctorData  = DoctorModel::find($request->id);
            $doctorData->name = $request->name;
            $doctorData->email = $request->email;
            $doctorData->phone_number = $request->mobile;
            $doctorData->license = $request->license;
            $doctorData->address = $request->address;
            $doctorData->clinic = $request->clinic;
            $doctorData->by_default_check = $request->default_doctor;
            $doctorData->save();
             
            $userLogs = new LogsModel;
            $userLogs->message = 'Doctor Update';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
          
            return $this->sendResponse('', 'Doctor Updated Successfully.');
          } catch (\Exception $e) {
            Log::info("Create Iteams api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    public function doctorView(Request $request)
    {
       try {
            $doctorList = DoctorModel::where('id',$request->id)->where('role','5')->first();

            $customerDetails = [];
            if(isset($doctorList))
            {
              		if($doctorList->by_default_check == 0) {
                     	$byDefaultCheckStatus = 'No';
                    } else {
                     	$byDefaultCheckStatus = 'Yes';
                    }
              
                    $customerDetails['id'] = isset($doctorList->id) ? $doctorList->id :"";
                    $customerDetails['name'] = isset($doctorList->name) ? $doctorList->name  :"";
                    $customerDetails['phone_number'] = isset($doctorList->phone_number) ? $doctorList->phone_number  :"";
                    $customerDetails['email'] = isset($doctorList->email) ? $doctorList->email  :"";
                    $customerDetails['clinic'] = isset($doctorList->clinic) ? $doctorList->clinic :"";
                    $customerDetails['license'] = isset($doctorList->license) ? $doctorList->license :"";
                    $customerDetails['address'] = isset($doctorList->address) ? $doctorList->address :"";
              		$customerDetails['default_doctor'] = isset($byDefaultCheckStatus) ? $byDefaultCheckStatus :"";
                     
                    $salesModelData = SalesModel::where('doctor_id', $doctorList->id)->pluck('id')->toArray();
                    $salesDetails = salesDetails::whereIn('sales_id', $salesModelData)->orderBy('id', 'DESC');
                     
                    $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                    $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
                    
                    $offset = ($page - 1) * $limit;
                    $salesDetails->offset($offset)->limit($limit);
                    
                    $salesDetails = $salesDetails->get();
              
                    $salesDetailsTotal = salesDetails::whereIn('sales_id', $salesModelData)->sum('amt');
              
                    $customerDetails['sales'] = [];
                    $qty_total_sales = 0;
                    if(isset($salesDetails))
                    {
                          foreach($salesDetails as $s => $listSales)
                          { 
                                 $customerData = SalesModel::where('id',$listSales->sales_id)->first();
                                 $customerName  = CustomerModel::where('id',$customerData->customer_id)->first();
                                 $salesDetailsCount = salesDetails::whereIn('sales_id', $salesModelData)->orderBy('id', 'DESC')->count();
                                 $bankPayment = BankAccount::where('id',$listSales->getSales->payment_name)->first();
                          
                                 $customerDetails['sales'][$s]['id'] = isset($listSales->id) ? $listSales->id : "";
                                 $customerDetails['sales'][$s]['sales_id'] = isset($listSales->getSales->id) ? $listSales->getSales->id : "";
                                 $customerDetails['sales'][$s]['bill_no'] = isset($listSales->getSales->bill_no) ? $listSales->getSales->bill_no : "";
                                 $customerDetails['sales'][$s]['customer_name'] = isset($customerName->name) ? $customerName->name : "";
                                 $customerDetails['sales'][$s]['phone_number'] = isset($customerName->phone_number) ? $customerName->phone_number : "";
                                 $customerDetails['sales'][$s]['bill_date'] = isset($listSales->getSales->bill_date) ?  date("d-m-Y", strtotime($listSales->getSales->bill_date)) : "";
                                 $customerDetails['sales'][$s]['qty'] = isset($listSales->qty) ? $listSales->qty : "";
                                 $customerDetails['sales'][$s]['amt'] = isset($listSales->amt) ? $listSales->amt: "";
                                 $customerDetails['sales'][$s]['payment_id'] = isset($listSales->getSales->payment_name) ?$listSales->getSales->payment_name: "";
                                 $customerDetails['sales'][$s]['payment_name'] = isset($bankPayment->bank_name) ? $bankPayment->bank_name: $listSales->getSales->payment_name;
                                 $customerDetails['sales'][$s]['total_amount'] = isset($salesDetailsTotal) ? (string)$salesDetailsTotal : "";
                                 $customerDetails['sales'][$s]['count'] = isset($salesDetailsCount) ? $salesDetailsCount: "";
                          }
                    }
                    
                    $customerDetails['sales_return'] = [];
                    $salesReturnId = SalesReturn::where('doctor_id',$doctorList->id)->pluck('id')->toArray();
                    $salesreturnDetails = SalesReturnDetails::whereIn('sales_id',$salesReturnId)->orderBy('id', 'DESC');
                    $totalCount = $salesreturnDetails->count();
                    $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                    $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
                    $offset = ($page - 1) * $limit;
                    $salesreturnDetails = $salesreturnDetails->limit($limit)->offset($offset)->get();
              
                    $salesreturnDetailsTotal = SalesReturnDetails::whereIn('sales_id', $salesReturnId)->sum('net_rate');
              
                    if(isset($salesreturnDetails))
                    {
                        foreach($salesreturnDetails as $sr => $listData)
                        {
                              $salesReturnData = SalesReturn::where('id',$listData->sales_id )->first();
                              $customerName  = CustomerModel::where('id',$salesReturnData->customer_id)->first();
                              $bankPayment = BankAccount::where('id',$listData->getSales->payment_name)->first();
                          
                              $customerDetails['sales_return'][$sr]['id'] = isset($listData->id) ? $listData->id : "";
                              $customerDetails['sales_return'][$sr]['sales_id'] = isset($listData->sales_id) ? $listData->sales_id : "";
                              $customerDetails['sales_return'][$sr]['bill_no'] = isset($salesReturnData->bill_no) ? $salesReturnData->bill_no : "";
                              $customerDetails['sales_return'][$sr]['customer_name'] = isset($customerName->name) ? $customerName->name : "";
                              $customerDetails['sales_return'][$sr]['phone_number'] = isset($customerName->phone_number) ? $customerName->phone_number : "";
                              $customerDetails['sales_return'][$sr]['bill_date'] = isset($salesReturnData->date) ? date("d-m-Y", strtotime($salesReturnData->date)) : "";
                              $customerDetails['sales_return'][$sr]['qty'] = isset($listData->qty) ? $listData->qty : "";
                              $customerDetails['sales_return'][$sr]['amt'] = isset($listData->net_rate) ? $listData->net_rate: "";
                              $customerDetails['sales_return'][$sr]['count'] = isset($totalCount) ? $totalCount: "";
                              $customerDetails['sales_return'][$sr]['payment_id'] = isset($listData->getSales->payment_name) ?$listData->getSales->payment_name: "";
                              $customerDetails['sales_return'][$sr]['payment_name'] = isset($bankPayment->bank_name) ? $bankPayment->bank_name: $listData->getSales->payment_name;
                              $customerDetails['sales_return'][$sr]['total_amount'] = isset($salesreturnDetailsTotal) ? (string)$salesreturnDetailsTotal: "";
                        }
                    }
            }
         	
            return $this->sendResponse($customerDetails, 'Doctor Data Fetch Successfully.');
          } catch (\Exception $e) {
            Log::info("Create Iteams api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    public function doctorDelete(Request $request)
    {
       try{
            $doctorData  = DoctorModel::where('id',$request->id)->first();
            if(isset($doctorData))
            {
                $doctorData->delete();
              
                $userLogs = new LogsModel;
                $userLogs->message = 'Doctor Delete';
                $userLogs->user_id = auth()->user()->id;
                $userLogs->date_time = date('Y-m-d H:i a');
                $userLogs->save();
            }
            return $this->sendResponse('', 'Doctor Deleted Successfully.');

          } catch (\Exception $e) {
            Log::info("Create Iteams api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use doctor list api
    public function doctorList(Request $request)
    {
        try{
              $staffGetData = User::where('create_by',auth()->user()->id)->pluck('id')->toArray();
            
              $ownerGet = User::where('id',auth()->user()->id)->pluck('create_by')->toArray();
              $userId = array(auth()->user()->id);
             
              $allUserId = array_merge($staffGetData,$ownerGet,$userId);
              
              // $limit = 10;
              $doctorList = DoctorModel::where('role','5')->whereIn('user_id',$allUserId)->orderBy('id', 'DESC');
      
              if($request->search)
              {
                  $searchTerm = '%' . $request->search . '%';
                  $doctorList->where(function($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm)
                      ->orWhere('clinic', 'like', $searchTerm);
                  });
              }
          	  if(!empty($request->name)) {
              	$doctorList = $doctorList->where('name', 'like', '%'.$request->name.'%');
              } elseif(!empty($request->phone_number)) {
              	$doctorList = $doctorList->where('phone_number', 'like', '%'.$request->phone_number.'%');
              } elseif(!empty($request->email)) {
              	$doctorList = $doctorList->where('email', 'like', '%'.$request->email.'%');
              } elseif(!empty($request->clinic_name)) {
              	$doctorList = $doctorList->where('clinic', 'like', '%'.$request->clinic_name.'%');
              }
            
             // Handle pagination
             if(!empty($request->iss_value))
             {
               	 $doctorList = $doctorList->whereIn('user_id',$allUserId)->orderBy('id', 'DESC')->get();
             } else{
               	 $limit = 10;
                 $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                 $offset = ($page - 1) * $limit;
                 $doctorList = $doctorList->offset($offset)->limit($limit)->get();
             }
          	$doctorTotalCount = DoctorModel::where('role','5')->whereIn('user_id',$allUserId)->count();

            $listData = [];
            if(isset($doctorList))
            {
                   foreach($doctorList as $key => $list)
                   {
                       if($list->by_default_check == 0) {
                          $byDefaultCheckStatus = "0";
                       } else {
                          $byDefaultCheckStatus = "1";
                       }

                       $listData[$key]['id'] = isset($list->id) ? $list->id :"";
                       $listData[$key]['name'] = isset($list->name) ? $list->name :"";
                       $listData[$key]['phone_number'] = isset($list->phone_number) ? $list->phone_number  :"";
                       $listData[$key]['email'] = isset($list->email) ? $list->email  :"";
                       $listData[$key]['clinic'] = isset($list->clinic) ? $list->clinic :"";
                       $listData[$key]['license'] = isset($list->license) ? $list->license :"";
                       $listData[$key]['address'] = isset($list->address) ? $list->address :"";
                       $listData[$key]['default_doctor'] = isset($byDefaultCheckStatus) ? $byDefaultCheckStatus : "0";
                   }
            }
          	$response = [
              'status' => 200,
              'count' => !empty($request->page) ? $doctorList->count() : $doctorTotalCount,
              'total_records' => $doctorTotalCount,
              'data'   => $listData,
              'message' => 'Doctor Data Fetch Successfully.',
            ];
            return response()->json($response, 200);
          	
            // return $this->sendResponse($listData, 'Doctor Data Fetch Successfully.');
           } catch (\Exception $e) {
            Log::info("doctor list api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this funcion use doctor list
    public function doctorSalesList(Request $request)
    { 
        try{
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required',
            ], [
                'doctor_id.required'=>'Please Enter Doctor Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }
            
            $doctorName =  DoctorModel::where('role','5')->where('id',$request->doctor_id)->first();
            $doctorDetails = [];
            if(isset($doctorName))
            {
                    $doctorDetails['id'] = isset($doctorName->id) ? $doctorName->id : "";
                    $doctorDetails['doctor_name'] = isset($doctorName->name) ? $doctorName->name : "";
                    $doctorDetails['mobile_number'] = isset($doctorName->phone_number) ? $doctorName->phone_number : "";
                    $doctorDetails['address'] = isset($doctorName->address) ? $doctorName->address : "";
                    $doctorDetails['doctor_details'] = [];
                   
                    foreach($doctorName->getSalesList as $key => $list)
                    {
                        $doctorDetails['doctor_details'][$key]['id'] = isset($list->id) ? $list->id :"";
                        $doctorDetails['doctor_details'][$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no :"";
                        $doctorDetails['doctor_details'][$key]['date'] =  $list->created_at;
                        $doctorDetails['doctor_details'][$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date :"";
                        $doctorDetails['doctor_details'][$key]['customer'] = isset($list->getUserName) ? $list->getUserName->name :"";
                        $doctorDetails['doctor_details'][$key]['bill_amount'] = isset($list->bill_amount) ? $list->bill_amount :"";
                    }
            }
 
            return $this->sendResponse($doctorDetails, 'Doctor Sales Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("doctor sales api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use doctor report
    public function doctorReport(Request $request)
    {
            try{
                  $staffGetData = User::where('create_by',auth()->user()->id)->pluck('id')->toArray();
                
                  $ownerGet = User::where('id',auth()->user()->id)->pluck('create_by')->toArray();
                  $userId = array(auth()->user()->id);
                 
                  $allUserId = array_merge($staffGetData,$ownerGet,$userId);
              
                     
                if($request->type == '0')
                {
                      $salesDetails = SalesModel::orderBy('id', 'DESC');
                      if(isset($request->start_date))
                      {
                        $start_date = date('Y-m-d', strtotime($request->start_date));
                        $end_date = date('Y-m-d', strtotime($request->end_date));
                        $salesDetails->whereBetween('created_at', [$start_date, $end_date]);
                      }
                      $salesDetails = $salesDetails->whereIn('user_id',$allUserId)->get();
                }

                if($request->type == '1')
                {
                    $salesDetails = SalesReturn::orderBy('id', 'DESC');
                    if(isset($request->start_date))
                    {
                      $start_date = date('Y-m-d', strtotime($request->start_date));
                      $end_date = date('Y-m-d', strtotime($request->end_date));
                      $salesDetails->whereBetween('created_at', [$start_date, $end_date]);
                    }
                    $salesDetails = $salesDetails->whereIn('user_id',$allUserId)->get();
                }

                $dataDetails = [];
                if(isset($salesDetails))
                {
                     if($request->type == '0')
                     {
                         foreach($salesDetails as $key => $list)
                         {
                            $dataDetails[$key]['id'] = isset($list->id) ?$list->id:"";
                            $dataDetails[$key]['bill_no'] = isset($list->bill_no) ?$list->bill_no:"";
                            $dataDetails[$key]['bill_date'] = isset($list->bill_date) ?$list->bill_date:"";
                            $dataDetails[$key]['patient_name'] = isset($list->getUserName) ?$list->getUserName->name :"";
                            $dataDetails[$key]['mobile'] = isset($list->getUserName) ?$list->getUserName->phone_number :"";
                            $dataDetails[$key]['doctor_name'] = isset($list->getDoctor) ?$list->getDoctor->name :"";
                            $dataDetails[$key]['item_name'] = isset($list->getSalesDetails->getIteam) ? $list->getSalesDetails->getIteam->iteam_name :"";
                            $dataDetails[$key]['unit'] = isset($list->getSalesDetails->unit) ? $list->getSalesDetails->unit :"";
                            $dataDetails[$key]['exp'] = isset($list->getSalesDetails->exp) ? $list->getSalesDetails->exp :"";
                            $dataDetails[$key]['qty'] = isset($list->getSalesDetails->qty) ? $list->getSalesDetails->qty :"";
                            $dataDetails[$key]['net_amt'] = isset($list->net_amt) ? $list->net_amt :"";
                         }
                     }
                     
                     if($request->type == '1')
                     {
                         foreach($salesDetails as $key => $list)
                         {
                            $dataDetails[$key]['id'] = isset($list->id) ?$list->id:"";
                            $dataDetails[$key]['bill_no'] = isset($list->bill_no) ?$list->bill_no:"";
                            $dataDetails[$key]['bill_date'] = isset($list->date) ?$list->date:"";
                            $dataDetails[$key]['patient_name'] = isset($list->getSales->getUserName) ? $list->getSales->getUserName->name :"";
                            $dataDetails[$key]['mobile'] = isset($list->getSales->getUserName) ? $list->getSales->getUserName->phone_number :"";
                            $dataDetails[$key]['doctor_name'] = isset($list->getSales->getDoctor) ?$list->getSales->getDoctor->name :"";
                            $dataDetails[$key]['item_name'] = isset($list->getSales->getSalesDetails->getIteam) ? $list->getSales->getSalesDetails->getIteam->iteam_name :"";
                            $dataDetails[$key]['unit'] = "";
                            $dataDetails[$key]['exp'] = "";
                            $dataDetails[$key]['qty'] = "";
                            $dataDetails[$key]['net_amt'] = isset($list->net_amount) ? $list->net_amount :"";
                         }
                     }
                }
                return $this->sendResponse($dataDetails, 'Data Fetch Successfully.');
              } catch (\Exception $e) {
                Log::info("doctor report api" . $e->getMessage());
                return $e->getMessage();
            }
    }
}
