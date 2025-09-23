<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanFeatures;
use Illuminate\Support\Facades\Http;
use App\Models\Transcations;
use App\Models\LogsModel;
  
class SubscriptionPlanController extends ResponseController
{
    //this function use create plan
    public function createPlan(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'max_product' => 'required',
                'description' => 'required',
                

            ], [
                'name.required' => 'Enter Name',
                'max_product.required' => 'Enter Max Product',
                'description.required' => 'Enter Description',
                
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            //   dD($request->all());
            $subscriptionPlan = new SubscriptionPlan;
            $subscriptionPlan->name = $request->name;
            $subscriptionPlan->max_product = $request->max_product;
            $subscriptionPlan->description = $request->description;
            $subscriptionPlan->is_popular = $request->is_popular;
            $subscriptionPlan->monthly_price = $request->monthly_price;
            $subscriptionPlan->annual_price = $request->annual_price;
            $subscriptionPlan->percentage = $request->percentage;
            $subscriptionPlan->enable_modules = $request->enable_modules;
            $subscriptionPlan->save();

            $featurePlan = json_decode($request->features_plan, true);
            if (isset($featurePlan)) {
                foreach ($featurePlan as $list) {
                    $subscriptionFeature = new SubscriptionPlanFeatures;
                    $subscriptionFeature->subscription_plan_id = $subscriptionPlan->id;
                    $subscriptionFeature->features_name = $list;
                    $subscriptionFeature->save();
                }
            }

            return $this->sendResponse('', 'Subscription Plan Added Successfully');
        } catch (\Exception $e) {
            Log::info("create Subscription Plan api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use plan list
    public function planList(Request $request)
    {
        try {
            $subscriptionList = SubscriptionPlan::orderBy('id', 'DESC')->get();

            $listArray = [];
            if (isset($subscriptionList)) {
                foreach ($subscriptionList as $key => $listData) {
                    $listArray[$key]['id'] = isset($listData->id) ? $listData->id : '';
                    $listArray[$key]['name'] = isset($listData->id) ? $listData->name : '';
                    $listArray[$key]['percentage'] = isset($listData->percentage) ? $listData->percentage : '';
                    $listArray[$key]['monthly_price'] = isset($listData->monthly_price) ? $listData->monthly_price : '';
                    $listArray[$key]['annual_price'] = isset($listData->annual_price) ? $listData->annual_price : '';
                    $listArray[$key]['re_newal'] = isset($listData->re_newal) ? $listData->re_newal : '';
                    $listArray[$key]['max_product'] = isset($listData->max_product) ? $listData->max_product : '';
                    $listArray[$key]['enable_modules'] = isset($listData->enable_modules) ? json_decode($listData->enable_modules)  : '';
                }
            }
            return $this->sendResponse($listArray, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("list Get Subscription Plan api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use delete plan 
    public function planDelete(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => 'Enter Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $deletePlan = SubscriptionPlan::where('id', $request->id)->first();

            if (!empty($deletePlan)) {
                $subcriptionPlan = SubscriptionPlanFeatures::where('subscription_plan_id', $deletePlan->id)->get();
                if (isset($subcriptionPlan)) {
                    foreach ($subcriptionPlan as $list) {
                        $list->delete();
                    }
                }
                $deletePlan->delete();
            }
            return $this->sendResponse('', 'Subscription Plan Deleted Successfully');
        } catch (\Exception $e) {
            Log::info("delete Subscription Plan api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use edit subcription plan
    public function planUpdate(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',

            ], [
                'id' => 'required',
                'name.required' => 'Enter Name',
               
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            //   dD($request->all());
            $subscriptionPlan = SubscriptionPlan::where('id', $request->id)->first();
            if (empty($subscriptionPlan)) {
                return $this->sendError('Data Not Found');
            }
            $subscriptionPlan->name = $request->name;
            $subscriptionPlan->max_product = $request->max_product;
            $subscriptionPlan->description = $request->description;
            $subscriptionPlan->percentage = $request->percentage;
            $subscriptionPlan->is_popular = $request->is_popular;
            $subscriptionPlan->monthly_price = $request->monthly_price;
            $subscriptionPlan->annual_price = $request->annual_price;
            $subscriptionPlan->enable_modules = $request->enable_modules;
            $subscriptionPlan->update();


            $featurePlan = json_decode($request->features_plan, true);
            if (isset($featurePlan)) {
                $subcriptionPlan = SubscriptionPlanFeatures::where('subscription_plan_id', $subscriptionPlan->id)->get();
                if (isset($subcriptionPlan)) {
                    foreach ($subcriptionPlan as $listDelete) {
                        $listDelete->delete();
                    }
                }
                foreach ($featurePlan as $list) {
                    if (isset($list)) {
                        $subscriptionFeature = new SubscriptionPlanFeatures;
                        $subscriptionFeature->subscription_plan_id = $subscriptionPlan->id;
                        $subscriptionFeature->features_name = $list;
                        $subscriptionFeature->save();
                    }
                }
            }

            return $this->sendResponse('', 'Subscription Plan Updated Successfully');
        } catch (\Exception $e) {
            Log::info("Update Subscription Plan api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use edit plan
    public function editPlan(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => 'Enter Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $subscriptionPlan = SubscriptionPlan::where('id', $request->id)->first();
            if (empty($subscriptionPlan)) {
                return $this->sendError('Data Not Found');
            }

            $planDetaiils = [];
            $planDetaiils['id'] = isset($subscriptionPlan->id) ? $subscriptionPlan->id : '';
            $planDetaiils['name'] = isset($subscriptionPlan->name) ? $subscriptionPlan->name : '';
            $planDetaiils['percentage'] = isset($subscriptionPlan->percentage) ? $subscriptionPlan->percentage : '';
            $planDetaiils['max_product'] = isset($subscriptionPlan->max_product) ? $subscriptionPlan->max_product : '';
            $planDetaiils['description'] = isset($subscriptionPlan->description) ? $subscriptionPlan->description : '';
            $planDetaiils['is_popular'] = isset($subscriptionPlan->is_popular) ? $subscriptionPlan->is_popular : '';
            $planDetaiils['monthly_price'] = isset($subscriptionPlan->monthly_price) ? $subscriptionPlan->monthly_price : '';
            $planDetaiils['annual_price'] = isset($subscriptionPlan->annual_price) ? $subscriptionPlan->annual_price : '';
            $planDetaiils['re_newal'] = isset($subscriptionPlan->re_newal) ? $subscriptionPlan->re_newal : '';
            $planDetaiils['enable_modules'] = isset($subscriptionPlan->enable_modules) ? $subscriptionPlan->enable_modules : '';
            $arrayData = [];
            if (isset($subscriptionPlan->getPlanFeature)) {
                foreach ($subscriptionPlan->getPlanFeature as $listData) {
                    $dataDetaiils['id'] = isset($listData->id) ? $listData->id : '';
                    $dataDetaiils['features_name'] = isset($listData->features_name) ? $listData->features_name : '';
                    array_push($arrayData, $dataDetaiils);
                }
            }
            $planDetaiils['featuer'] = $arrayData;
            return $this->sendResponse($planDetaiils, 'Subscription Plan Edit Successfully');
        } catch (\Exception $e) {
            Log::info("edit Subscription Plan api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use plan feature delete api
    public function planFeatureDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => 'Enter Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $subcriptionPlan = SubscriptionPlanFeatures::where('id', $request->id)->first();
            if (isset($subcriptionPlan)) {
                    $subcriptionPlan->delete();
            }

            return $this->sendResponse('', 'Subscription Plan Feature Deleted Successfully');
        } catch (\Exception $e) {
            Log::info("Subscription Plan Feature Delete api" . $e->getMessage());
            return $e->getMessage();
        }
    }
  
    public function listPlan(Request $request)
    {
          $subscriptionList = SubscriptionPlan::get();

            $listArray = [];
            if (isset($subscriptionList)) {
                foreach ($subscriptionList as $key => $listData) {
                  
                    $listArray[$key]['id'] = isset($listData->id) ? $listData->id : '';
                    $listArray[$key]['name'] = isset($listData->id) ? $listData->name : '';
                    $listArray[$key]['referral_balance'] = isset(auth()->user()->referral_amount) ? (string)auth()->user()->referral_amount :"";
                    $transactionData = Transcations::where('pharma_name',auth()->user()->id)->first();
                    if(isset($transactionData))
                    {
                    $listArray[$key]['annual_price'] = isset($listData->re_newal) ? $listData->re_newal : '';
                    }else{
                     $listArray[$key]['annual_price'] = isset($listData->annual_price) ? $listData->annual_price : '';
                    }
                    $listArray[$key]['enable_modules'] = isset($listData->enable_modules) ? explode(',',$listData->enable_modules) : '';
                   
                }
            }
            return $this->sendResponse($listArray, 'Data Fetch Successfully');
    }
  
   public function paymentDetailsStore(Request $request)
   {
             $paymentId = isset($request->payment_id) ? $request->payment_id : "";
     
             $keyId = 'rzp_test_qp5ViSvdWQsuNd';
             $keySecret = 'IguZZNtejoSeqMzoAPa365gD';

             $url = "https://api.razorpay.com/v1/payments/$paymentId";

            // Initialize cURL
            $ch = curl_init();

            // Set cURL options
             curl_setopt($ch, CURLOPT_URL, $url);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Set HTTP Basic Authentication with API credentials
             curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);

            // Execute the cURL request
             $response = curl_exec($ch);

            $data = json_decode($response, true);
          
            if((isset($data['amount'])) && (isset($data['currency'])))
            {
               $urlCatch = "https://api.razorpay.com/v1/payments/$paymentId/capture";

                // Initialize cURL
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $urlCatch);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Set HTTP Basic Authentication with API credentials
                curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);

                // Set the HTTP method to POST
                curl_setopt($ch, CURLOPT_POST, true);

                // Set the POST data in JSON format
                $postData = json_encode([
                    'amount' => $data['amount'],  // Amount to be captured (in paise)
                    'currency' => $data['currency'] // Currency code
                ]);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

                // Set content type to JSON
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json'
                ]);

                // Execute the cURL request
                $response = curl_exec($ch);
                $dataData = json_decode($response, true);
            }
           
     
         if((isset($dataData['status'])) && ($dataData['status'] == 'captured'))
         {
            
             $transactionId = $request->payment_id;
             $transactionData  = new Transcations;
             $transactionData->date = $request->payment_date;
             $transactionData->transcation_id = $transactionId;
             $transactionData->next_payment_date = $request->expiry_date;
             $transactionData->pharma_name = $request->user_id;
             $transactionData->plan_name = $request->plan_name;
             $transactionData->entity = isset($dataData['entity']) ? $dataData['entity'] :"";
             $transactionData->status = isset($dataData['status']) ? $dataData['status'] :"";
             $transactionData->order_id = isset($dataData['order_id']) ? $dataData['order_id'] :"";
             $transactionData->invoice_id = isset($dataData['invoice_id']) ? $dataData['invoice_id'] :"";
             $transactionData->method = isset($dataData['method']) ? $dataData['method'] :"";
             $transactionData->wallet = isset($dataData['wallet']) ? $dataData['wallet'] :"";
             $transactionData->amount = isset($dataData['amount']) ? $dataData['amount'] :"";
             $transactionData->save();
           
           $planDetails = SubscriptionPlan::where('id',$listData->plan_name)->first();
           if(isset($planDetails))
           {
             $userRefferalAmount = User::where('user_referral_code',auth()->user()->referral_code)->first();
             if(isset($userRefferalAmount))
             {
                if ($planDetails && isset($planDetails->percentage) && is_numeric($planDetails->percentage) && is_numeric($planDetails)) {
                    $calculatedAmount = ($planDetails->annual_price * (int)$planDetails->percentage) / 100;
                } else {
                    $calculatedAmount = 0; // Default to 0 if any value is missing or invalid
                }
               
                $userRefferalAmount->referral_pending_amount = $userRefferalAmount->referral_pending_amount + $calculatedAmount;
                $userRefferalAmount->update();
               
                $precentDetails = isset($planDetails->percentage) ? $planDetails->percentage :'';
               
                $logData = new LogsModel;
                $logData->amount = $calculatedAmount;
                $logData->referral_user_id = $userRefferalAmount->id;
                $logData->message = 'Referral: ' . $precentDetails . '% and Amount Received: ' . $calculatedAmount;
                $logData->user_id = auth()->user()->id;
                $logData->save();
             }
           }
         
           $status = 'Payment Successfully';
         }else{
           $status = 'Payemnt Filed Successfully';
         }
          
      
        return $this->sendResponse([], $status);
   }
  
   public function paymentHistory(Request $request)
   {
      $transactionHistory = Transcations::where('pharma_name',auth()->user()->id)->get();
     
      $listArray = [];
      if(isset($transactionHistory))
      {
         foreach($transactionHistory as $key => $listData)
         {
            $planDetails = SubscriptionPlan::where('id',$listData->plan_name)->first();
            $userName = User::where('id',$listData->pharma_name)->first();
           
             $keyId = 'rzp_test_qp5ViSvdWQsuNd';
             $keySecret = 'IguZZNtejoSeqMzoAPa365gD';

            $paymentId = isset($listData->transcation_id) ? $listData->transcation_id : "";
            $url = "https://api.razorpay.com/v1/payments/$paymentId";

            // Initialize cURL
            $ch = curl_init();

            // Set cURL options
             curl_setopt($ch, CURLOPT_URL, $url);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Set HTTP Basic Authentication with API credentials
             curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);

            // Execute the cURL request
             $response = curl_exec($ch);

            $data = json_decode($response, true);
            
           $listArray[$key]['id'] = isset($listData->id) ? $listData->id : '';
           $listArray[$key]['payment_date'] = isset($listData->date) ? $listData->date .' to '.$listData->next_payment_date : '';
           $listArray[$key]['plan_name'] = isset($planDetails->name) ? $planDetails->name : '';
           $listArray[$key]['expiry_date'] = isset($listData->next_payment_date) ? $listData->next_payment_date : '';
           $listArray[$key]['paid_on'] = isset($listData->created_at) ? date('Y-m-d h:i A', strtotime($listData->created_at)) : '';
           $listArray[$key]['payment_id'] = isset($paymentId) ? $paymentId : '';
           $listArray[$key]['entity'] = isset($data['entity']) ? $data['entity'] : '';
           $listArray[$key]['amount'] = isset($data['amount']) ? $data['amount'] : '';
           $listArray[$key]['currency'] = isset($data['currency']) ? $data['currency'] : '';
           $listArray[$key]['status'] = isset($data['status']) ? $data['status'] : '';
           $listArray[$key]['order_id'] = isset($data['order_id']) ? $data['order_id'] : '';
           $listArray[$key]['invoice_id'] = isset($data['invoice_id']) ? $data['invoice_id'] : '';
           $listArray[$key]['method'] = isset($data['method']) ? $data['method'] : '';
           $listArray[$key]['amount_refunded'] = isset($data['amount_refunded']) ? $data['amount_refunded'] : '';
           $listArray[$key]['refund_status'] = isset($data['refund_status']) ? $data['refund_status'] : '';
           $listArray[$key]['captured'] = isset($data['captured']) ? $data['captured'] : '';
           $listArray[$key]['description'] = isset($data['description']) ? $data['description'] : '';
           $listArray[$key]['card_id'] = isset($data['card_id']) ? $data['card_id'] : '';
           $listArray[$key]['bank'] = isset($data['bank']) ? $data['bank'] : '';
           $listArray[$key]['wallet'] = isset($data['wallet']) ? $data['wallet'] : '';
           $listArray[$key]['vpa'] = isset($data['vpa']) ? $data['vpa'] : '';
           $listArray[$key]['email'] = isset($data['email']) ? $data['email'] : '';
           $listArray[$key]['contact'] = isset($data['contact']) ? $data['contact'] : '';
           $listArray[$key]['fee'] = isset($data['fee']) ? $data['fee'] : '';
           $listArray[$key]['tax'] = isset($data['tax']) ? $data['tax'] : '';
           $listArray[$key]['acquirer_data'] = isset($data['acquirer_data']) ? $data['acquirer_data'] : '';
           $listArray[$key]['created_at'] = isset($data['created_at']) ? $data['created_at'] : '';
           
         }
      }
      return $this->sendResponse($listArray, 'Data Fetch Successfully');
   }
  
  public function referralLogs(Request $request)
  {
     $logsData = LogsModel::where('referral_user_id',auth()->user()->id)->get();
    $listDetails = [];
    if(isset($logsData))
     {
      foreach($logsData as $key => $list)
      {
         $userDetails = User::where('id',$list->user_id)->first();
        $listDetails[$key]['add_amount'] = isset($list->amount) ? (string)$list->amount :"";
         $listDetails[$key]['remark'] = isset($list->message) ? (string)$list->message :"";
        
        $listDetails[$key]['lees_amount'] = isset($list->lees_amount) ? (string)$list->lees_amount :"";
        $listDetails[$key]['name'] = isset($userDetails->name) ? $userDetails->name :"";
      }
     }
       return $this->sendResponse($listDetails, 'Data Fetch Successfully');
  }
  
}
