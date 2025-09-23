<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\PharmaShop;
use App\Models\PharmaPlan;
use Illuminate\Support\Facades\Mail;
use App\Models\Transcations;
use App\Models\ShopPlan;
use App\Models\LogsModel;

class PharmaShopController extends ResponseController
{
  //this function use pharma shop create api
  public function createPharma(Request $request)
  {
    try {
      // $validator = Validator::make($request->all(), [
      //   'pharma_name' => 'required',
      //   'pharma_short_name' => 'required',
      //   'pharma_email' => 'required',
      //   'pharma_phone_number' => 'required',
      //   // 'pharma_timezone' => 'required',
      //   'pharma_address' => 'required',
      //   'city' => 'required',
      //   'dark_logo' => 'required',
      //   'light_logo' => 'required',
      //   'small_dark_logo' => 'required',
      //   'small_light_logo' => 'required',
      //   'email' => 'required',
      //   'password' => 'required'
      // ], [
      //   'pharma_name.required' => "Enter Pharma Name",
      //   'pharma_short_name.required' => 'Enter Pharma Short Name',
      //   'pharma_email.required' => 'Enter Pharma Email',
      //   'pharma_phone_number.required' => 'Enter Pharma Phone Number',
      //   // 'pharma_timezone.required' => 'Select Pharma TimeZone',
      //   'pharma_address.required' => 'Enter Pharma Address',
      //   'city.required' => 'Enter City',
      //   'dark_logo.required' => 'Select Dark Logo',
      //   'light_logo.required' => 'Select Light Logo',
      //   'small_dark_logo.required' => 'Select Small Dark Logo',
      //   'small_light_logo.required' => 'Select Small Light Logo',
      //   'email.required' => 'Enter Email',
      //   'password.required' => 'Enter Password',
      // ]);

      // if ($validator->fails()) {
      //   $error = $validator->getMessageBag();
      //   return $this->sendError($error->first());
      // }

      // $userEmail = User::where('email', $request->email)->first();
      // if (!empty($userEmail)) {
      //   return $this->sendError('Email Already Exist');
      // }

      $pharmaShop = new User;
      $pharmaShop->email = $request->email;
      $pharmaShop->name = $request->pharma_name;
      $pharmaShop->password = Hash::make($request->password);
      $pharmaShop->new_password = $request->password;
      if(isset($request->pharma_phone_number))
      {
      $pharmaShop->phone_number = $request->pharma_phone;  
      }
      $pharmaShop->city = $request->city;
      $pharmaShop->status = '1';
      $pharmaShop->role = '1';
      $pharmaShop->save();

      $details = [
        'email' => $request->email,
        'password' => $request->password
      ];

      Mail::to($request->pharma_email)->send(new \App\Mail\MyTestMail($details));

      $pharmaUser = new PharmaShop;
      $pharmaUser->user_id = $pharmaShop->id;
      $pharmaUser->pharma_name = $request->pharma_name;
      $pharmaUser->pharma_short_name = $request->pharma_short_name;
      $pharmaUser->pharma_email = $request->pharma_email;
       if(isset($request->pharma_phone_number))
      {
      $pharmaShop->pharma_phone_number = $request->pharma_phone;  
      }
      $pharmaUser->agent_id = $request->agent_id;
      $pharmaUser->pharma_address = $request->pharma_address;
      $pharmaUser->pharma_status = $request->pharma_status;
      $pharmaUser->total_user = '1';

      if (!empty($request->dark_logo)) {

        $base64Image = $request->input('dark_logo');
        $binaryImage = base64_decode($base64Image);
        $filename = 'image_' . time() . '.png';
        $path = public_path('dark_logo/' . $filename);
        file_put_contents($path, $binaryImage);

        $pharmaUser->dark_logo = $filename;
      }
      if (!empty($request->light_logo)) {
        $base64Image = $request->input('light_logo');
        $binaryImage = base64_decode($base64Image);
        $filename = 'image_' . time() . '.png';
        $path = public_path('light_logo/' . $filename);
        file_put_contents($path, $binaryImage);
        $pharmaUser->light_logo = $filename;
      }
      if (!empty($request->small_dark_logo)) {
        $base64Image = $request->input('small_dark_logo');
        $binaryImage = base64_decode($base64Image);
        $filename = 'image_' . time() . '.png';
        $path = public_path('small_dark_logo/' . $filename);
        file_put_contents($path, $binaryImage);
        $pharmaUser->small_dark_logo = $filename;
      }
      if (!empty($request->small_light_logo)) {
        $base64Image = $request->input('small_light_logo');
        $binaryImage = base64_decode($base64Image);
        $filename = 'image_' . time() . '.png';
        $path = public_path('small_light_logo/' . $filename);
        file_put_contents($path, $binaryImage);
        $pharmaUser->small_light_logo = $filename;
      }
      $pharmaUser->city = $request->city;
      $pharmaUser->save();
      
      if(isset($request->plan) && (isset($request->commission)))
      {
             $agentPlan = json_decode($request->plan, true);
             $agentCommission = json_decode($request->commission, true);
             $combinedArray = array_map(null, $agentPlan, $agentCommission);
             foreach ($combinedArray as $combined) {
                 $planStore = new ShopPlan;
                 $planStore->shop_id = $pharmaUser->id;
                 $planStore->plan = isset($combined[0]) ? $combined[0] : "";
                 $planStore->commission = isset($combined[1]) ? $combined[1] : "";
                 $planStore->save();
             }
        }

      return $this->sendResponse('', 'Pharma Shop Added Successfully');
    } catch (\Exception $e) {
      Log::info("create pharma shop api" . $e->getMessage());
      return $e->getMessage();
    }
  }

  //this function use get pharma list api
  public function pharmaList(Request $request)
  {
    try {
      $pharmaList = User::orderBy('id', 'DESC')->where('role','!=','10')->get();

      $listData = [];
      if (isset($pharmaList)) {
        foreach ($pharmaList as $key => $list) {
       
          $status = null;
          if ($list->status == '0') {
            $status = 'Pending';
          } elseif ($list->status == '1') {
            $status = 'Active';
          } else {
            $status = 'Inactive';
          }
          $listData[$key]['id'] = isset($list->id) ? $list->id : '';
          $listData[$key]['light_logo'] = isset($list->pharmacy_logo)  
          ? asset('pharmacy_logo/' . $list->pharmacy_logo)
          : asset('public/landing_desgin/assets/images/logo/logo.png');
          $listData[$key]['pharma_name'] = isset($list->name) ? $list->name : '';
          $listData[$key]['pharma_email'] = isset($list->email) ? $list->email : '';
          $listData[$key]['pharma_status'] = isset($status) ? $status : '';
          $listData[$key]['register_date'] = isset($list->created_at) ? date("d-m-Y", strtotime($list->created_at)) : '';
          $listData[$key]['total_user'] = isset($list->total_user) ?  $list->total_user : '';
        }
      }
      return $this->sendResponse($listData, 'Data Fetch Successfully');
    } catch (\Exception $e) {
      Log::info("create pharma shop api" . $e->getMessage());
      return $e->getMessage();
    }
  }

  //this function use delete pharma
  public function pharmaDelete(Request $request)
  {
    try {

      $validator = Validator::make($request->all(), [
        'id' => 'required'
      ], [
        'id.required' => "Enter Pharma Id",
      ]);

      if ($validator->fails()) {
        $error = $validator->getMessageBag();
        return $this->sendError($error->first());
      }

      $pharmaDelete = PharmaShop::where('id', $request->id)->first();
      $pharmaDelete->delete();
      if (isset($pharmaDelete)) {
        $userData = User::where('id', $pharmaDelete->user_id)->first();
        if (isset($userData)) {
          $userData->delete();
          $planDelete = PharmaPlan::where('pharma_plan', $request->id)->delete();
        }
        $planDelete = PharmaPlan::where('pharma_plan', $request->id)->delete();
      }

      return $this->sendResponse('', 'Pharma Shop Deleted Successfully');
    } catch (\Exception $e) {
      Log::info("create pharma shop api" . $e->getMessage());
      return $e->getMessage();
    }
  }

  //this function use edit pharma details
  public function pharmaEdit(Request $request)
  {
    try {

      $validator = Validator::make($request->all(), [
        'id' => 'required'
      ], [
        'id.required' => "Enter Pharma Id",
      ]);

      if ($validator->fails()) {
        $error = $validator->getMessageBag();
        return $this->sendError($error->first());
      }

      $pharmaEdit = PharmaShop::where('user_id', $request->id)->first();
     
      // dD($pharmaEdit);
      if (empty($pharmaEdit)) {
        return $this->sendError('Data Not Found');
      }
      $pahrmaShop = User::where('id',$pharmaEdit->user_id)->first();

      $listData = [];
      if ($pharmaEdit->pharma_status == '0') {
        $status = 'Pending';
      } elseif ($pharmaEdit->pharma_status == '1') {
        $status = 'Active';
      } else {
        $status = 'Inactive';
      }
    
      $listData['id'] = isset($pharmaEdit->user_id) ? $pharmaEdit->user_id : '';
      $listData['shop_id'] = isset($pharmaEdit->id) ? $pharmaEdit->id : '';
      $listData['remark'] = isset($pahrmaShop->remark) ? $pahrmaShop->remark : '';
      
      $listData['agent_id'] = isset($pharmaEdit->agent_id) ? $pharmaEdit->agent_id : '';
      $listData['pharma_name'] = isset($pharmaEdit->pharma_name) ? $pharmaEdit->pharma_name : '';
      $listData['pharma_short_name'] = isset($pharmaEdit->pharma_short_name) ? $pharmaEdit->pharma_short_name : '';
      $listData['pharma_email'] = isset($pharmaEdit->pharma_email) ? $pharmaEdit->pharma_email : '';
       $listData['password'] = isset($pahrmaShop->new_password) ? $pahrmaShop->new_password : '';
       $listData['referral_amount'] = isset($pahrmaShop->referral_amount) ? $pahrmaShop->referral_amount : '';
      
      $listData['pharma_phone_number'] = isset($pharmaEdit->pharma_phone_number) ? $pharmaEdit->pharma_phone_number : '';
      $listData['pharma_timezone'] = isset($pharmaEdit->pharma_timezone) ? $pharmaEdit->pharma_timezone : '';
      $listData['pharma_status'] = isset($status) ? $pharmaEdit->pharma_status : '';
      $listData['pharma_address'] = isset($pharmaEdit->pharma_address) ? $pharmaEdit->pharma_address : '';
      $listData['city'] = isset($pharmaEdit->city) ? $pharmaEdit->city : '';
      $listData['email'] = isset($pharmaEdit->getUser->email) ? $pharmaEdit->getUser->email : '';
      $listData['dark_logo'] = isset($pharmaEdit->dark_logo) ? asset('/public/dark_logo/' . $pharmaEdit->dark_logo) : '';
      $listData['light_logo'] = isset($pharmaEdit->light_logo) ? asset('/public/light_logo/' . $pharmaEdit->light_logo) : '';
      $listData['small_dark_logo'] = isset($pharmaEdit->small_dark_logo) ? asset('/public/small_dark_logo/' . $pharmaEdit->small_dark_logo) : '';
      $listData['small_light_logo'] = isset($pharmaEdit->small_light_logo) ? asset('/public/small_light_logo/' . $pharmaEdit->small_light_logo) : '';
      return $this->sendResponse($listData, 'Data Fetch Successfully');
    } catch (\Exception $e) {
      Log::info("edit pharma shop api" . $e->getMessage());
      return $e->getMessage();
    }
  }

  //this function use update pharma records 
  public function pharmaUpdate(Request $request)
  {
    try {
           
      // $validator = Validator::make($request->all(), [
      //   'user_id' => 'required',
      //   'pharma_name' => 'required',
      //   'pharma_short_name' => 'required',
      //   'pharma_email' => 'required',
      //   'pharma_phone_number' => 'required',
      //   // 'pharma_timezone' => 'required',
      //   'pharma_address' => 'required',
      //   'city' => 'required',
      // ], [
      //   'user_id.required' => 'Enter User Id',
      //   'pharma_name.required' => "Enter Pharma Name",
      //   'pharma_short_name.required' => 'Enter Pharma Short Name',
      //   'pharma_email.required' => 'Enter Pharma Email',
      //   'pharma_phone_number.required' => 'Enter Pharma Phone Number',
      //   'pharma_timezone.required' => 'Enter Pharma TimeZone',
      //   'pharma_address.required' => 'Enter Pharma Address',
      //   'city.required' => 'Enter City',
      // ]);

      // if ($validator->fails()) {
      //   $error = $validator->getMessageBag();
      //   return $this->sendError($error->first());
      // }

      // // dD($request->all());

      $pharmaShop = User::where('id', $request->user_id)->first();

      if (empty($pharmaShop)) {
        return $this->sendError('Data Not Found');
      }
      // $checkEmail = User::where('id', '!=', $request->user_id)->where('email', $request->email)->first();
      // if (!empty($checkEmail)) {
      //   return $this->sendError('Email Already Exist');
      // }
      $pharmaShop->email = $request->email;
      $pharmaShop->name = $request->pharma_name;
      
      $pharmaShop->city = $request->city;
      if (isset($request->password)) {
           $pharmaShop->password = Hash::make($request->password);
           $pharmaShop->new_password = $request->password;
        $details = [
          'email' => $request->email,
          'password' => $request->password
        ];

        // Mail::to($request->pharma_email)->send(new \App\Mail\MyTestMail($details));
      }
      $pharmaShop->phone_number = $request->pharma_phone;
      if(isset($request->updated_balance))
      {
         $pharmaShop->referral_amount = $request->updated_balance;
      }
      $pharmaShop->update();
      
     
      $pharmaUser = PharmaShop::where('user_id', $request->user_id)->first();
      if (empty($pharmaUser)) {
        return $this->sendError('Data Not Found');
      }
      $pharmaUser->pharma_name = $request->pharma_name;
      $pharmaUser->pharma_short_name = $request->pharma_short_name;
      $pharmaUser->pharma_email = $request->pharma_email;
      $pharmaUser->pharma_phone_number = $request->pharma_phone;
        $pharmaUser->city = $request->city;
      $pharmaUser->pharma_address = $request->pharma_address;
      
      $pharmaUser->pharma_status = $request->pharma_status;
      $pharmaUser->agent_id = $request->agent_id;
      $pharmaUser->total_user = '1';
      if (!empty($request->dark_logo)) {

        $base64Image = $request->input('dark_logo');
        $binaryImage = base64_decode($base64Image);
        $filename = 'image_' . time() . '.png';
        $path = public_path('dark_logo/' . $filename);
        file_put_contents($path, $binaryImage);

        $pharmaUser->dark_logo = $filename;
      }
      if (!empty($request->light_logo)) {
        $base64Image = $request->input('light_logo');
        $binaryImage = base64_decode($base64Image);
        $filename = 'image_' . time() . '.png';
        $path = public_path('light_logo/' . $filename);
        file_put_contents($path, $binaryImage);
        $pharmaUser->light_logo = $filename;
      }
      if (!empty($request->small_dark_logo)) {
        $base64Image = $request->input('small_dark_logo');
        $binaryImage = base64_decode($base64Image);
        $filename = 'image_' . time() . '.png';
        $path = public_path('small_dark_logo/' . $filename);
        file_put_contents($path, $binaryImage);
        $pharmaUser->small_dark_logo = $filename;
      }
      if (!empty($request->small_light_logo)) {
        $base64Image = $request->input('small_light_logo');
        $binaryImage = base64_decode($base64Image);
        $filename = 'image_' . time() . '.png';
        $path = public_path('small_light_logo/' . $filename);
        file_put_contents($path, $binaryImage);
        $pharmaUser->small_light_logo = $filename;
      }
      $pharmaUser->update();
      
      
      if(isset($request->plan) && ($request->commission))
      {
                  $agentPlan = json_decode($request->plan, true);
                  $agentCommission = json_decode($request->commission, true);
                  $combinedArray = array_map(null, $agentPlan, $agentCommission);
                  $shopData = ShopPlan::where('shop_id',$pharmaUser->id)->get();
                  if(isset( $shopData))
                  {
                      foreach($shopData as $list)
                      {
                         $list->delete();
                      }
                  }
                
                foreach ($combinedArray as $combined) {
                    $planStore = new ShopPlan;
                    $planStore->shop_id = $pharmaUser->id;
                    $planStore->plan = isset($combined[0]) ? $combined[0] : "";
                    $planStore->commission = isset($combined[1]) ? $combined[1] : "";
                    $planStore->save();
                }
            }
            

      return $this->sendResponse('', 'Pharma Shop Updated Successfully');
    } catch (\Exception $e) {
      Log::info("update pharma shop api" . $e->getMessage());
      return $e->getMessage();
    }
  }

  //this function use pharma plan chnage
  public function pharmaPlan(Request $request)
  {
    try {

      $validator = Validator::make($request->all(), [
        'pharma_id' => 'required',
        'subscription_plan_id' => 'required',
        'plan_type' => 'required',
        'payment_mode' => 'required',
        'amount' => 'required',
        'payment_date' => 'required',
        'license_will_expire_on' => 'required',
        'next_payment_date' => 'required',
      ], [
        'pharma_id.required' => 'Enter Pharma Id',
        'subscription_plan_id.required' => 'Enter Subscription Plan Id',
        'plan_type.required' => 'Enter Plan Type',
        'payment_mode.required' => 'Enter Payment Mode',
        'amount.required' => 'Enter Amount',
        'payment_date.required' => 'Enter Payment Date',
        'license_will_expire_on.required' => 'Enter License Will Expire On',
        'next_payment_date.required' => 'Enter Next Payment Date',
      ]);

      if ($validator->fails()) {
        $error = $validator->getMessageBag();
        return $this->sendError($error->first());
      }

      $pahrmaData = PharmaPlan::where('pharma_plan', $request->pharma_id)->first();
      if (empty($pahrmaData)) {
        $pahrmaData = new PharmaPlan;
      }
      $pahrmaData->pharma_plan = $request->pharma_id;
      $pahrmaData->subscription_plan_id = $request->subscription_plan_id;
      $pahrmaData->plan_type = $request->plan_type;
      $pahrmaData->payment_mode = $request->payment_mode;
      $pahrmaData->amount = $request->amount;
      $pahrmaData->payment_date = $request->payment_date;
      $pahrmaData->license_will_expire_on = $request->license_will_expire_on;
      $pahrmaData->next_payment_date = $request->next_payment_date;
      $pahrmaData->save();

      $transction = new Transcations;
      $transction->date = $request->payment_date;
      $transction->next_payment_date = $request->next_payment_date;
      $transction->transcation_id = rand(11111, 99999);
      $transction->payment_method = $request->payment_mode;
      $transction->pharma_name = $request->pharma_id;
      $transction->amount = $request->amount;
      $transction->payment_type = $request->plan_type;
      $transction->save();

      return $this->sendResponse('', 'Pharma Shop Updated Successfully');
    } catch (\Exception $e) {
      Log::info("pharma shop Plan api" . $e->getMessage());
      return $e->getMessage();
    }
  }

  //this function use pharma plan details
  public function pharmaPlanDetails(Request $request)
  {
    try {

      $validator = Validator::make($request->all(), [
        'id' => 'required'
      ], [
        'id.required' => "Enter Pharma Id",
      ]);

      if ($validator->fails()) {
        $error = $validator->getMessageBag();
        return $this->sendError($error->first());
      }

      $pahrmaData = PharmaPlan::where('pharma_plan', $request->id)->first();

      $planDetails = [];
      $planDetails['id'] = isset($pahrmaData->id) ? $pahrmaData->id : '';
      $planDetails['subscription_plan'] = isset($pahrmaData->subscription_plan_id) ? $pahrmaData->subscription_plan_id : '';
      $planDetails['plan_type'] = isset($pahrmaData->plan_type) ? $pahrmaData->plan_type : '';
      $planDetails['payment_mode'] = isset($pahrmaData->payment_mode) ? $pahrmaData->payment_mode : '';
      $planDetails['amount'] = isset($pahrmaData->amount) ? $pahrmaData->amount : '';
      $planDetails['license_will_expire_on'] = isset($pahrmaData->license_will_expire_on) ? $pahrmaData->license_will_expire_on : '';
      $planDetails['next_payment_date'] = isset($pahrmaData->next_payment_date) ? $pahrmaData->next_payment_date : '';
      $planDetails['payment_date'] = isset($pahrmaData->payment_date) ? $pahrmaData->payment_date : '';

      return $this->sendResponse($planDetails, 'Pharma Shop Plan Details Successfully');
    } catch (\Exception $e) {
      Log::info("pharma shop Plan api" . $e->getMessage());
      return $e->getMessage();
    }
  }
}
