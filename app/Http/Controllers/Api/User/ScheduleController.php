<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\SalesModel;
use App\Models\PurchesDetails;
use App\Models\PurchesReturnDetails;
use App\Models\User;
use App\Models\IteamsModel;
use App\Models\salesDetails;
use App\Models\SalesReturnDetails;
use App\Models\BatchModel;


class ScheduleController extends ResponseController
{
    //this function use schedule list
    public function scheduleList(Request $request)
    {
         try{
                if($request->type == '0')
                {
                        $dataDetails = SalesModel::orderBy('id', 'DESC');
                        if($request->start_date)
                        {
                            $start_date = date('Y-m-d', strtotime($request->start_date));
                            $end_date = date('Y-m-d', strtotime($request->end_date));
                            $dataDetails->whereBetween('created_at', [$start_date, $end_date]);
                        }
                        $dataDetails =  $dataDetails->get();
                }
                if($request->type == '1')
                {
                    $dataDetails = PurchesModel::orderBy('id', 'DESC');
                    if($request->start_date)
                    {
                        $start_date = date('Y-m-d', strtotime($request->start_date));
                        $end_date = date('Y-m-d', strtotime($request->end_date));
                        $dataDetails->whereBetween('created_at', [$start_date, $end_date]);
                    }
                    $dataDetails =  $dataDetails->get();
                }

                $itemDetails = [];
                if(isset($dataDetails))
                {
                    if($request->type == '0')
                    {
                        foreach($dataDetails as $key => $list)
                        {
                            $itemDetails[$key]['id'] = isset($list->id) ? $list->id :"";
                            $itemDetails[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no :"";
                            $itemDetails[$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date :"";
                            $itemDetails[$key]['patient_name'] = isset($list->getUserName) ? $list->getUserName->name :"";
                            $itemDetails[$key]['doctor_name'] = isset($list->getDoctor) ? $list->getDoctor->name :"";
                            $itemDetails[$key]['Item _name'] = isset($list->getSalesDetails->getIteam) ? $list->getSalesDetails->getIteam->iteam_name :"";
                            $itemDetails[$key]['batch'] = isset($list->getSalesDetails->batch) ? $list->getSalesDetails->batch :"";
                            $itemDetails[$key]['exp'] = isset($list->getSalesDetails->exp) ? $list->getSalesDetails->exp :"";
                            $itemDetails[$key]['unit'] = isset($list->getSalesDetails->unit) ? $list->getSalesDetails->unit:"";
                        }
                    }

                    if($request->type == '1')
                    {
                        foreach($dataDetails as $key => $list)
                        {
                            $itemDetails[$key]['id'] = isset($list->id) ? $list->id :"";
                            $itemDetails[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no :"";
                            $itemDetails[$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date :"";
                            $itemDetails[$key]['distributor_name'] = isset($list->getUser) ? $list->getUser->name :"";
                            $itemDetails[$key]['Item_name'] = isset($list->getPurches->getIteam) ? $list->getPurches->getIteam->iteam_name :"";
                            $itemDetails[$key]['batch'] = isset($list->getPurches->batch) ? $list->getPurches->batch :"";
                            $itemDetails[$key]['exp'] = isset($list->getPurches->exp_dt) ? $list->getPurches->exp_dt:"";
                            $itemDetails[$key]['unit'] = isset($list->getPurches->unit) ? $list->getPurches->unit:"";
                        }
                    }
                }

                return $this->sendResponse($itemDetails, 'Data Fetch Successfully');
           } catch (\Exception $e) {
            Log::info("schedule List api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    public function staffActivity(Request $request)
    {
        $userId = auth()->user()->id;
        
        $userData = User::where('create_by',$userId)->pluck('id')->toArray();
       
        if($request->type == '1')
        {
            $billDetails = PurchesDetails::whereIn('user_id',$userData);
           if (isset($request->start_date) && isset($request->end_date)) {
               
                $from_date = $request->start_date;
                $to_date = $request->end_date;
                
                $billDetails->whereHas('getpurches', function ($query) use ($from_date, $to_date) {
                    $query->whereBetween('bill_date', [$from_date, $to_date]);
                });
            }
            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $billDetails->offset($offset)->limit($limit);
            $billDetails = $billDetails->get(); 
        }
        if($request->type == '2')
        {
            $billDetails = PurchesReturnDetails::whereIn('user_id',$userData);
             if (isset($request->start_date) && isset($request->end_date)) {
               
                $from_date = $request->start_date;
                $to_date = $request->end_date;
                
                $billDetails->whereHas('getPurchesReturn', function ($query) use ($from_date, $to_date) {
                    $query->whereBetween('select_date', [$from_date, $to_date]);
                });
            }
            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $billDetails->offset($offset)->limit($limit);
            $billDetails = $billDetails->get(); // Executing the query here
        }
        
          if($request->type == '3')
        {
            $billDetails = salesDetails::whereIn('user_id',$userData);
             if (isset($request->start_date) && isset($request->end_date)) {
                $from_date = $request->start_date;
                $to_date = $request->end_date;
                
                $billDetails->whereHas('getSales', function ($query) use ($from_date, $to_date) {
                    $query->whereBetween('bill_date', [$from_date, $to_date]);
                });
            }
            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $billDetails->offset($offset)->limit($limit);
            $billDetails = $billDetails->get(); // Executing the query here
        }
        
        
          if($request->type == '4')
        {
            $billDetails =  SalesReturnDetails::whereIn('user_id',$userData);
             if (isset($request->start_date) && isset($request->end_date)) {
                $from_date = $request->start_date;
                $to_date = $request->end_date;
                
                $billDetails->whereHas('getSales', function ($query) use ($from_date, $to_date) {
                    $query->whereBetween('date', [$from_date, $to_date]);
                });
            }
            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $billDetails->offset($offset)->limit($limit);
            $billDetails = $billDetails->get(); // Executing the query here
        }
      
       
         $billDatas = [];
         $amount = [];
         if(isset($billDetails))
         {
             foreach($billDetails as $key => $list)
             {
                  $billNo = '';
                 if(isset($list->getpurches->bill_no))
                 {
                     $billNo = $list->getpurches->bill_no;
                 }elseif(isset($list->getPurchesReturn->bill_no))
                 {
                     $billNo = $list->getPurchesReturn->bill_no;
                     
                 }elseif(isset($list->getSales->bill_no))
                 {
                     $billNo = $list->getSales->bill_no;
                 }
                 
                 $billDate = '';
                  if(isset($list->getpurches->bill_date))
                 {
                     $billDate = $list->getpurches->bill_date;
                 }elseif(isset($list->getPurchesReturn->select_date))
                 {
                     $billDate = $list->getPurchesReturn->select_date;
                     
                 }elseif(isset($list->getSales->bill_date))
                 {
                     $billDate = $list->getSales->bill_date;
                 }
                 elseif(isset($list->getSales->date))
                 {
                     $billDate = $list->getSales->date;
                 }
                 
                 $amountData = 0;
                  if(isset($list->net_rate))
                 {
                     $amountData = $list->net_rate;
                 }elseif(isset($list->amount))
                 {
                     $amountData = $list->amount;
                 }elseif(isset($list->amt))
                 {
                     $amountData = $list->amt;
                 }
                 
                 
                 $iteamName  = IteamsModel::where('id',$list->iteam_id)->first();
          
                 $batchModel = BatchModel::where('batch_name',$list->batch)->orderBy('id', 'DESC')->first();
                 
                 $userData = User::where('id',$list->user_id)->first();
                 
                 $billDatas[$key]['id'] = isset($list->id) ? $list->id :"";
                 $billDatas[$key]['iteam_name'] = isset($iteamName->iteam_name) ? $iteamName->iteam_name :"";
                 $billDatas[$key]['unit'] = isset($list->unit) ? $list->unit :"";
                 $billDatas[$key]['bill_no'] = isset($billNo) ? $billNo :"";
                 $billDatas[$key]['bill_date'] = isset($billDate) ? $billDate :"";
                 $billDatas[$key]['batch'] = isset($list->batch) ? $list->batch :"";
                 $billDatas[$key]['exp_dt'] = isset($list->exp_dt) ? $list->exp_dt : $list->exp;
                 $billDatas[$key]['qty'] = isset($batchModel->total_qty) ? $batchModel->total_qty :"";
                 $billDatas[$key]['staff_name'] = isset($userData->name) ? $userData->name :"";
                 $billDatas[$key]['amount'] = isset($amountData) ? (string)round($amountData) :"";
                 array_push($amount,$amountData);
             }
         }
         
         $totalData = [];
         $totalData['bil_list'] = $billDatas;
         $totalData['bil_total'] = (string)round(array_sum($amount), 2);
         return $this->sendResponse($totalData, 'Data Fetch Successfully');
        
    }

}
