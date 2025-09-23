<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\LicenseModel;
use App\Models\frontRolePermissions;
use App\Models\FrontPermissions;
use App\Models\LogsModel;
use App\Models\Transcations;
use Carbon\Carbon;
use App\Models\SubscriptionPlan;
use App\Models\PatientsOrder;
use App\Models\PatientOrderItem;
use App\Models\NotificationModel;
use App\Models\SalesModel;
use App\Models\salesDetails;
use App\Models\CustomerModel;
use App\Models\PatientsModel;
use  App\Models\CashManagement;
use App\Models\BatchModel;
use App\Models\SalesFinalIteam;
use App\Models\LedgerModel;
use App\Models\FinalPurchesItem;
use App\Models\FinalIteamId;
use App\Models\SalesIteam;
use App\Models\PurchesDetails;
use App\Models\ChemistNotificationModel;
use App\Models\RoyaltyPoint;

class AboutController extends ResponseController
{
  public function updatePassword(Request $request)
  {
      try {
        $validator = Validator::make($request->all(), [
          'password' => 'required|confirmed',
          'password_confirmation' => 'required'
        ], [
          'password.required' => 'Please Enter Password',
          'password_confirmation.required' => 'Please Enter Password Confirmation',
        ]);

        if ($validator->fails()) {
          $error = $validator->getMessageBag();
          return $this->sendError($error->first());
        }

        $userData = User::where('id', auth()->user()->id)->first();
        $userData->name = isset($request->name) ? $request->name : "";
        $userData->password = Hash::make($request->password);
        $userData->new_password = $request->password;
        $userData->update();

        $userLogs = new LogsModel;
        $userLogs->message = 'Password Updated';
        $userLogs->user_id = auth()->user()->id;
        $userLogs->date_time = date('Y-m-d H:i a');
        $userLogs->save();

        return $this->sendResponse([], 'Password Updated Successfully.');
      } catch (\Exception $e) {
        Log::info("Update Password api" . $e->getMessage());
        return $e->getMessage();
      }
  }

  public function licenseStore(Request $request)
  {
    try {

      $newData = LicenseModel::where('user_id', auth()->user()->id)->first();
      if (empty($newData)) {
        $newData = new LicenseModel;
      }

      $newData->license_name = $request->license_name;
      $newData->license_no = $request->license_no;
      $newData->license_expiry_date = $request->license_expiry_date;
      $newData->user_id = auth()->user()->id;

      if ($request->file('license_image')) {
        $front_photo = $request->file('license_image');
        $extension = $front_photo->getClientOriginalExtension();
        $filename = time() . '_first_.' . $extension;
        $front_photo->move(public_path('/license_image'), $filename);
        $newData->license_image =  $filename;
      }
      $newData->license_name_two = $request->license_name_two;
      $newData->license_no_two = $request->license_no_two;
      $newData->license_expiry_date_two = $request->license_expiry_date_two;
      if ($request->file('license_image_two')) {
        $front_photo = $request->file('license_image_two');
        $extension = $front_photo->getClientOriginalExtension();
        $filename = time() . '_two_.' . $extension;
        $front_photo->move(public_path('/license_image'), $filename);
        $newData->license_image_two =  $filename;
      }
      $newData->license_name_three = $request->license_name_three;
      $newData->license_no_three = $request->license_no_three;
      $newData->license_expiry_date_three = $request->license_expiry_date_three;
      if ($request->file('license_image_three')) {
        $front_photo = $request->file('license_image_three');
        $extension = $front_photo->getClientOriginalExtension();
        $filename = time() . '_three_.' . $extension;
        $front_photo->move(public_path('/license_image'), $filename);
        $newData->license_image_three =  $filename;
      }
      $newData->license_name_four = $request->license_name_four;
      $newData->license_no_four = $request->license_no_four;
      $newData->license_expiry_date_four = $request->license_expiry_date_four;
      if ($request->file('license_image_four')) {
        $front_photo = $request->file('license_image_four');
        $extension = $front_photo->getClientOriginalExtension();
        $filename = time() . '_four_.' . $extension;
        $front_photo->move(public_path('/license_image'), $filename);
        $newData->license_image_four =  $filename;
      }
      $newData->save();

      $userLogs = new LogsModel;
      $userLogs->message = 'License Added';
      $userLogs->user_id = auth()->user()->id;
      $userLogs->date_time = date('Y-m-d H:i a');
      $userLogs->save();

      $licenseData =  LicenseModel::where('user_id', auth()->user()->id)->first();
      $listDetails = [];
      if (isset($licenseData)) {
        $listDetails['id'] = isset($licenseData->id) ? $licenseData->id : "";
        $listDetails['license_name'] = isset($licenseData->license_name) ? $licenseData->license_name : "";
        $listDetails['license_no'] = isset($licenseData->license_no) ? $licenseData->license_no : "";
        $listDetails['license_image'] = isset($licenseData->license_image) ? asset('/public/license_image/' . $licenseData->license_image) : "";
        $listDetails['license_expiry_date'] = isset($licenseData->license_expiry_date) ? $licenseData->license_expiry_date : "";
        $listDetails['license_name_two'] = isset($licenseData->license_name_two) ? $licenseData->license_name_two : "";
        $listDetails['license_no_two'] = isset($licenseData->license_no_two) ? $licenseData->license_no_two : "";
        $listDetails['license_image_two'] = isset($licenseData->license_image_two) ? asset('/public/license_image/' . $licenseData->license_image_two) : "";
        $listDetails['license_expiry_date_two'] = isset($licenseData->license_expiry_date_two) ? $licenseData->license_expiry_date_two : "";
        $listDetails['license_name_three'] = isset($licenseData->license_name_three) ? $licenseData->license_name_three : "";
        $listDetails['license_no_three'] = isset($licenseData->license_no_three) ? $licenseData->license_no_three : "";
        $listDetails['license_image_three'] = isset($licenseData->license_image_three) ? asset('/public/license_image/' . $licenseData->license_image_three) : "";
        $listDetails['license_expiry_date_three'] = isset($licenseData->license_expiry_date_three) ? $licenseData->license_expiry_date_three : "";
        $listDetails['license_name_four'] = isset($licenseData->license_name_four) ? $licenseData->license_name_four : "";
        $listDetails['license_no_four'] = isset($licenseData->license_no_four) ? $licenseData->license_no_four : "";
        $listDetails['license_image_four'] = isset($licenseData->license_image_four) ? asset('/public/license_image/' . $licenseData->license_image_four) : "";
        $listDetails['license_expiry_date_four'] = isset($licenseData->license_expiry_date_four) ? $licenseData->license_expiry_date_four : "";
      }

      return $this->sendResponse($listDetails, 'License Added Successfully');
    } catch (\Exception $e) {
      Log::info("license Store api" . $e->getMessage());
      return $e->getMessage();
    }
  }

  public function licenseList(Request $request)
  {
    try {
      $licenseData =  LicenseModel::where('user_id', auth()->user()->id)->first();
      $listDetails = [];
      if (isset($licenseData)) {
        $listDetails['id'] = isset($licenseData->id) ? (string)$licenseData->id : "";
        $listDetails['license_name'] = isset($licenseData->license_name) ? $licenseData->license_name : "";
        $listDetails['license_no'] = isset($licenseData->license_no) ? $licenseData->license_no : "";
        $listDetails['license_image'] = isset($licenseData->license_image) ? asset('/public/license_image/' . $licenseData->license_image) : "";
        $listDetails['license_expiry_date'] = isset($licenseData->license_expiry_date) ? $licenseData->license_expiry_date : "";
        $listDetails['license_name_two'] = isset($licenseData->license_name_two) ? $licenseData->license_name_two : "";
        $listDetails['license_no_two'] = isset($licenseData->license_no_two) ? $licenseData->license_no_two : "";
        $listDetails['license_image_two'] = isset($licenseData->license_image_two) ? asset('/public/license_image/' . $licenseData->license_image_two) : "";
        $listDetails['license_expiry_date_two'] = isset($licenseData->license_expiry_date_two) ? $licenseData->license_expiry_date_two : "";
        $listDetails['license_name_three'] = isset($licenseData->license_name_three) ? $licenseData->license_name_three : "";
        $listDetails['license_no_three'] = isset($licenseData->license_no_three) ? $licenseData->license_no_three : "";
        $listDetails['license_image_three'] = isset($licenseData->license_image_three) ? asset('/public/license_image/' . $licenseData->license_image_three) : "";
        $listDetails['license_expiry_date_three'] = isset($licenseData->license_expiry_date_three) ? $licenseData->license_expiry_date_three : "";
        $listDetails['license_name_four'] = isset($licenseData->license_name_four) ? $licenseData->license_name_four : "";
        $listDetails['license_no_four'] = isset($licenseData->license_no_four) ? $licenseData->license_no_four : "";
        $listDetails['license_image_four'] = isset($licenseData->license_image_four) ? asset('/public/license_image/' . $licenseData->license_image_four) : "";
        $listDetails['license_expiry_date_four'] = isset($licenseData->license_expiry_date_four) ? $licenseData->license_expiry_date_four : "";
      } else {
        $listDetails['id'] = isset($licenseData->id) ? (string)$licenseData->id : "";
        $listDetails['license_name'] = isset($licenseData->license_name) ? $licenseData->license_name : "";
        $listDetails['license_no'] = isset($licenseData->license_no) ? $licenseData->license_no : "";
        $listDetails['license_image'] = isset($licenseData->license_image) ? asset('/public/license_image/' . $licenseData->license_image) : "";
        $listDetails['license_expiry_date'] = isset($licenseData->license_expiry_date) ? $licenseData->license_expiry_date : "";
        $listDetails['license_name_two'] = isset($licenseData->license_name_two) ? $licenseData->license_name_two : "";
        $listDetails['license_no_two'] = isset($licenseData->license_no_two) ? $licenseData->license_no_two : "";
        $listDetails['license_image_two'] = isset($licenseData->license_image_two) ? asset('/public/license_image/' . $licenseData->license_image_two) : "";
        $listDetails['license_expiry_date_two'] = isset($licenseData->license_expiry_date_two) ? $licenseData->license_expiry_date_two : "";
        $listDetails['license_name_three'] = isset($licenseData->license_name_three) ? $licenseData->license_name_three : "";
        $listDetails['license_no_three'] = isset($licenseData->license_no_three) ? $licenseData->license_no_three : "";
        $listDetails['license_image_three'] = isset($licenseData->license_image_three) ? asset('/public/license_image/' . $licenseData->license_image_three) : "";
        $listDetails['license_expiry_date_three'] = isset($licenseData->license_expiry_date_three) ? $licenseData->license_expiry_date_three : "";
        $listDetails['license_name_four'] = isset($licenseData->license_name_four) ? $licenseData->license_name_four : "";
        $listDetails['license_no_four'] = isset($licenseData->license_no_four) ? $licenseData->license_no_four : "";
        $listDetails['license_image_four'] = isset($licenseData->license_image_four) ? asset('/public/license_image/' . $licenseData->license_image_four) : "";
        $listDetails['license_expiry_date_four'] = isset($licenseData->license_expiry_date_four) ? $licenseData->license_expiry_date_four : "";
      }
      return $this->sendResponse($listDetails, 'Data Fetch Successfully');
    } catch (\Exception $e) {
      Log::info("license List api" . $e->getMessage());
      return $e->getMessage();
    }
  }

  public function aboutPharmacy(Request $request)
  {
    $aboutData  = User::where('id', auth()->user()->id)->first();
    $aboutData->name = $request->pharmacy_name;
    $aboutData->pharmacist_name = $request->pharmacist_name;
    $aboutData->pharmacist_number = $request->pharmacist_number;
    if ($request->hasFile('pharmacy_logo')) {
      $file = $request->file('pharmacy_logo');
      $extension = $file->getClientOriginalExtension();
      $filename = time() . '.' . $extension;
      $file->move('pharmacy_logo/', $filename);
      $aboutData->pharmacy_logo = $filename;
    }
    $aboutData->owner_name = $request->owner_name;
    $aboutData->gst_pan = $request->gst;
    $aboutData->pan_card = $request->pan_card;
    $aboutData->phone_number = $request->phone_number;
    $aboutData->email = $request->email;
    $aboutData->address = $request->address;
    $aboutData->zip_code = $request->pin_code;
    $aboutData->address_line_two = $request->area;
    $aboutData->city = $request->city;
    $aboutData->state = $request->state;
    $aboutData->update();

    $dataDetails = [];
    $dataDetails['id'] = isset($aboutData->id) ? $aboutData->id : "";
    $dataDetails['name'] = isset($aboutData->name) ? $aboutData->name : "";
    $dataDetails['referral_code'] = isset($aboutData->referral_code) ? $aboutData->referral_code : "";
    $dataDetails['login_user_referral_code'] = isset($aboutData->user_referral_code) ? $aboutData->user_referral_code : "";
    $dataDetails['owner_name'] = isset($aboutData->owner_name) ? $aboutData->owner_name : "";
    $dataDetails['gst_pan'] = isset($aboutData->gst_pan) ? $aboutData->gst_pan : "";
    $dataDetails['phone_number'] = isset($aboutData->phone_number) ? $aboutData->phone_number : "";
    $dataDetails['email'] = isset($aboutData->email) ? $aboutData->email : "";
    $dataDetails['address'] = isset($aboutData->address) ? $aboutData->address : "";
    $dataDetails['zip_code'] = isset($aboutData->zip_code) ? $aboutData->zip_code : "";
    $dataDetails['address_line_two'] = isset($aboutData->address_line_two) ? $aboutData->address_line_two : "";
    $dataDetails['city'] = isset($aboutData->city) ? $aboutData->city : "";
    $dataDetails['state'] = isset($aboutData->state) ? $aboutData->state : "";
    $dataDetails['pharmacy_logo'] = isset($aboutData->pharmacy_logo) ? asset('/pharmacy_logo/' . $aboutData->pharmacy_logo) : "";

    return $this->sendResponse($dataDetails, 'Profile Updated Successfully.');
  }

  public function getAbout(Request $request)
  {
    $aboutData  = User::where('id', auth()->user()->id)->first();

    $dataDetails = [];
    $dataDetails['id'] = isset($aboutData->id) ? $aboutData->id : "";
    $dataDetails['name'] = isset($aboutData->name) ? $aboutData->name : "";
    $dataDetails['accept_online_orders'] = isset($aboutData->accept_online_orders) ? $aboutData->accept_online_orders : "";
    $dataDetails['delivery_online_orders'] = isset($aboutData->home_delivery_online_orders) ? $aboutData->home_delivery_online_orders : "";
    $dataDetails['pickup_online_orders'] = isset($aboutData->home_pickup_online_orders) ? $aboutData->home_pickup_online_orders : "";
    $dataDetails['minimum_order_amount'] = isset($aboutData->minimum_order_amount) ? $aboutData->minimum_order_amount : "";
    $dataDetails['order_shipping_price'] = isset($aboutData->order_shipping_price) ? $aboutData->order_shipping_price : "";
    $dataDetails['delivery_estimated_time'] = isset($aboutData->delivery_estimated_time) ? $aboutData->delivery_estimated_time : "";
    $dataDetails['order_manager'] = isset($aboutData->order_manager) ? $aboutData->order_manager : "";
    $dataDetails['google_location_link'] = isset($aboutData->google_location_link) ? $aboutData->google_location_link : "";
    $dataDetails['delivery_start_time'] = isset($aboutData->start_time) ? $aboutData->start_time : "";
    $dataDetails['delivery_end_time'] = isset($aboutData->end_time) ? $aboutData->end_time : "";
    $dataDetails['delivery_executive'] = isset($aboutData->delivery_executive) ? $aboutData->delivery_executive : "";
    $dataDetails['pharmacy_whatsapp'] = isset($aboutData->pharmacy_whatsapp) ? $aboutData->pharmacy_whatsapp : "";

    $dataDetails['referral_code'] = isset($aboutData->referral_code) ? $aboutData->referral_code : "";
    $dataDetails['login_user_referral_code'] = isset($aboutData->user_referral_code) ? $aboutData->user_referral_code : "";
    $dataDetails['pharmacy_logo'] = isset($aboutData->pharmacy_logo) ? asset('/pharmacy_logo/' . $aboutData->pharmacy_logo) : "";
    $dataDetails['pharmacist_name'] = isset($aboutData->pharmacist_name) ? $aboutData->pharmacist_name : "";
    $dataDetails['pharmacist_number'] = isset($aboutData->pharmacist_number) ? $aboutData->pharmacist_number : "";
    $dataDetails['pan_card'] = isset($aboutData->pan_card) ? $aboutData->pan_card : "";
    $dataDetails['password'] = isset($aboutData->new_password) ? $aboutData->new_password : "";
    $dataDetails['owner_name'] = isset($aboutData->owner_name) ? $aboutData->owner_name : "";
    $dataDetails['gst_pan'] = isset($aboutData->gst_pan) ? $aboutData->gst_pan : "";
    $dataDetails['phone_number'] = isset($aboutData->phone_number) ? $aboutData->phone_number : "";
    $dataDetails['email'] = isset($aboutData->email) ? $aboutData->email : "";
    $dataDetails['address'] = isset($aboutData->address) ? $aboutData->address : "";
    $dataDetails['zip_code'] = isset($aboutData->zip_code) ? $aboutData->zip_code : "";
    $dataDetails['address_line_two'] = isset($aboutData->address_line_two) ? $aboutData->address_line_two : "";
    $dataDetails['city'] = isset($aboutData->city) ? $aboutData->city : "";
    $dataDetails['state'] = isset($aboutData->state) ? $aboutData->state : "";
    $dataDetails['referral_balance'] = isset($aboutData->referral_amount) ? (string)$aboutData->referral_amount : "";

    $userDetailsReffral = User::where('referral_code', $aboutData->user_referral_code)->get();
    $dataDetails['referral_list'] = [];
    if (isset($userDetailsReffral)) {
      foreach ($userDetailsReffral as $key => $listDetails) {
        $pharmaData = Transcations::where('pharma_name', $listDetails->id)->first();

        $dataDetails['referral_list'][$key]['name'] = isset($listDetails->name) ? $listDetails->name : '';
        $dataDetails['referral_list'][$key]['mobile_number'] = isset($listDetails->phone_number) ? $listDetails->phone_number : '';
        $dataDetails['referral_list'][$key]['registration_date'] = isset($listDetails->created_at) ? date("d M Y", strtotime($listDetails->created_at)) : '';
        $dataDetails['referral_list'][$key]['referral_user_plan'] = isset($pharmaData) ? 'User has purchase plan.' : 'User has not purchase plan.';
      }
    }
    return $this->sendResponse($dataDetails, 'Data Updated Successfully.');
  }

  public function getUserPermission(Request $request)
  {
    try {
      $userPermission = frontRolePermissions::where('role_id', auth()->user()->assgin_role)->pluck('permissions_id')->toArray();

      $Permission = FrontPermissions::pluck('permissions')->toArray();

      if ((!isset(auth()->user()->assgin_role)) && (!isset(auth()->user()->create_by))) {
        $result = [];
        // Iterate through each available permission
        foreach ($Permission as $key => $permission) {
          $permission = str_replace("_", " ", $permission);
          // Check if the permission exists in the user's permissions
          $result[$key][$permission] = true;
        }
      } else {
        $result = [];
        // Iterate through each available permission
        foreach ($Permission as $key => $permission) {
          $permission = str_replace("_", " ", $permission);
          // Check if the permission exists in the user's permissions
          $result[$key][$permission] = in_array($permission, $userPermission);
        }
      }

      return $this->sendResponse($result, 'User Permission Get Successfully.');
    } catch (\Exception $e) {
      Log::info("user Permmison List api" . $e->getMessage());
      return $e->getMessage();
    }
  }

  public function royltiPoint(Request $request)
  {
    $userData  = User::where('id', auth()->user()->id)->first();
    $userData->royalti_amount = isset($request->royalti_amount) ? $request->royalti_amount : "";
    $userData->royalti_point = isset($request->royalti_point) ? $request->royalti_point : "";
    $userData->update();

    return $this->sendResponse([], 'Data Fetch Successfully.');
  }

  public function chemistStoreDetails(Request $request)
  {
    $userData = User::where('id', auth()->user()->id)->first();
    if (isset($userData)) {
      $userData->delivery_executive = $request->delivery_executive;
      $userData->accept_online_orders = $request->accept_online_orders;
      $userData->email = $request->email;
      $userData->pharmacy_whatsapp = $request->pharmacy_whatsapp;
      $userData->home_delivery_online_orders = $request->delivery_online_orders;
      $userData->home_pickup_online_orders   = $request->pickup_online_orders;
      $userData->minimum_order_amount = $request->minimum_order_amount;
      $userData->order_shipping_price = $request->order_shipping_price;
      $userData->delivery_estimated_time = $request->delivery_estimated_time;
      $userData->order_manager = $request->order_manager;
      $userData->google_location_link = $request->google_location_link;
      $userData->start_time = $request->delivery_start_time;
      $userData->end_time = $request->delivery_end_time;
      $userData->save();
    }

    $dataDetails = [];
    if (isset($userData)) {
      $dataDetails['id'] = isset($userData->id) ? $userData->id : "";
      $dataDetails['delivery_executive'] = isset($userData->delivery_executive) ? $userData->delivery_executive : "";
      $dataDetails['accept_online_orders'] = isset($userData->accept_online_orders) ? $userData->accept_online_orders : "";
      $dataDetails['pharmacy_mobile'] = isset($userData->phone_number) ? $userData->phone_number : "";
      $dataDetails['pharmacy_email'] = isset($userData->email) ? $userData->email : "";
      $dataDetails['pharmacy_whatsapp'] = isset($userData->pharmacy_whatsapp) ? $userData->pharmacy_whatsapp : "";
      $dataDetails['delivery_online_orders'] = isset($userData->home_delivery_online_orders) ? $userData->home_delivery_online_orders : "";
      $dataDetails['pickup_online_orders'] = isset($userData->home_pickup_online_orders) ? $userData->home_pickup_online_orders : "";
      $dataDetails['minimum_order_amount'] = isset($userData->minimum_order_amount) ? $userData->minimum_order_amount : "";
      $dataDetails['order_shipping_price'] = isset($userData->order_shipping_price) ? $userData->order_shipping_price : "";
      $dataDetails['delivery_estimated_time'] = isset($userData->delivery_estimated_time) ? $userData->delivery_estimated_time : "";
      $dataDetails['order_manager'] = isset($userData->order_manager) ? $userData->order_manager : "";
      $dataDetails['google_location_link'] = isset($userData->google_location_link) ? $userData->google_location_link : "";
      $dataDetails['start_time'] = isset($userData->start_time) ? $userData->start_time : "";
      $dataDetails['end_time'] = isset($userData->end_time) ? $userData->end_time : "";
    }
    return $this->sendResponse($dataDetails, 'Online Order Setting Updated Successfully.');
  }
  
  public function chemistOrderStatus(Request $request)
  {
      $patientsOrders = PatientsOrder::find($request->order_id);
      if (!$patientsOrders) {
          return $this->sendError('Order data not found.');
      }
      if (!$request->status) {
          return $this->sendError('Please select status.');
      }

      // Update order status
      $patientsOrders->order_status = $request->status;
      $user_data = User::where('id', $patientsOrders->chemist_id)->first();
      $orderId = $patientsOrders->order_id;

      if ($request->status == '4') {
          $patientData = PatientsModel::find($patientsOrders->patient_id);

          if (!$patientData) {
              return $this->sendError('Patient data not found.');
          }

          $notification_data = new NotificationModel();
          $notification_data->title = '✅ Order Accepted';
          $notification_data->description = 'Your order ' . $orderId . ' has been accepted by the ' . ($user_data->name ?? 'Chemist') . '.';
          $notification_data->user_id = $patientsOrders->patient_id;
          $notification_data->order_id = $orderId;
          $notification_data->is_send = 2;
          $notification_data->save();

          $this->patient_notification($notification_data->title, $notification_data->description, $patientsOrders->patient_id);

          // Customer creation/fetch
          $customerDetails = null;
          $userEmail = CustomerModel::where('phone_number', $patientData->mobile_number)->where('user_id', auth()->user()->id)->first();

          if (!empty($userEmail)) {
              $customerDetails = $userEmail->id;
          } else {
              $customer = new CustomerModel;
              $customer->name = $patientData->first_name;
              $customer->phone_number = $patientData->mobile_number;
              $customer->status = '1';
              $customer->role = '3';
              $customer->user_id = auth()->user()->id;
              $customer->save();

              $customerDetails = $customer->id;
          }

          $salesModelCount = SalesModel::where('user_id', auth()->user()->id)->count();
          $status = $patientsOrders->delivery_status == '0' ? 'Pickup' : 'Delivery';

      	  $salesAmount = $patientsOrders->new_amount;

      	  $royaltyPoints = RoyaltyPoint::where('user_id', auth()->id())->get();
        
          $loyaltyPointsEarned = 0;
          $matched = false;

          foreach ($royaltyPoints as $royalty) {
              if ($salesAmount >= $royalty->minimum && $salesAmount <= $royalty->maximum) {
                  $loyaltyPointsEarned = ($salesAmount * $royalty->percent) / 100;
                  $matched = true;
                  break;
              }
          }

          if (!$matched) {
            	$loyaltyPointsEarned = 0;
          }

          // Create Sales Entry
          $salesData = new SalesModel;
          $salesData->bill_date = date('Y-m-d');
          $salesData->customer_id = $customerDetails ?? "";
          $salesData->bill_no = $salesModelCount + 1;
          $salesData->net_rate = $patientsOrders->new_amount;
          $salesData->payment_name = 'cash';
          $salesData->mrp_total = $patientsOrders->total_amount;
          $salesData->round_off = $patientsOrders->round_off;
          $salesData->net_amt = round($patientsOrders->new_amount, 2);
          $salesData->owner_name = auth()->user()->name;
          $salesData->user_id = auth()->user()->id;
          $salesData->pickup = $status;
          $salesData->given_amount = 0;
      	  $salesData->today_loylti_point = (string)round($loyaltyPointsEarned, 2);
          $salesData->save();

          // Cash Management Entry
          $cashAdd = new CashManagement;
          $cashAdd->date = date('Y-m-d');
          $cashAdd->description = 'Sales Manage';
          $cashAdd->type = 'credit';
          $cashAdd->amount = round($patientsOrders->new_amount, 2);
          $cashAdd->user_id = auth()->user()->id;
          $cashAdd->reference_no = $salesModelCount + 1;
          $cashAdd->voucher = 'sales';
          $cashAdd->save();

          $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
          $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
          $allUserId = array_merge($staffGetData, $ownerGet, [auth()->user()->id]);

          $patientOrderItemDetails = PatientOrderItem::where('patient_order_id', $request->order_id)->get();

          if ($patientOrderItemDetails->count()) {
              foreach ($patientOrderItemDetails as $list) {
                  $batchData = BatchModel::where('item_id', $list->iteam_id)
                      ->whereIn('user_id', $allUserId)
                      ->orderBy('created_at', 'desc')
                      ->first();

                  // Safe defaults if batch not found
                  $item_id = $batchData->item_id ?? $list->iteam_id;
                  $unit = $batchData->unit ?? '';
                  $batch_name = $batchData->batch_name ?? '';
                  $exp_date = $batchData->expiry_date ?? null;
                  $base = $batchData->base ?? 0;
                  $mrp = $batchData->mrp ?? 0;
                  $gst = $batchData->gst ?? 0;
                  $discount = $batchData->discount ?? 0;
                  $ptr = $batchData->ptr ?? 0;
                  $location = $batchData->location ?? '';

                  $textbleVlaue = ($list->qty ?? 0) * $base;
                  $userId = auth()->user()->id;

                  // ---- SalesDetails ----
                  $salesDetails = new SalesDetails;
                  $salesDetails->sales_id = $salesData->id;
                  $salesDetails->random_number = rand(10000, 99999);
                  $salesDetails->taxable_value = $textbleVlaue;
                  $salesDetails->iteam_id = $item_id;
                  $salesDetails->unit = $unit;
                  $salesDetails->batch = $batch_name;
                  $salesDetails->exp = $exp_date;
                  $salesDetails->base = $base;
                  $salesDetails->mrp = $mrp;
                  $salesDetails->gst = $gst;
                  $salesDetails->discount = $discount;
                  $salesDetails->ptr = $ptr;
                  $salesDetails->qty = $list->qty ?? 0;
                  $salesDetails->amt = $list->sub_amount ?? 0;
                  $salesDetails->user_id = $userId;
                  $salesDetails->location = $location;
                  $salesDetails->save();

                  // ---- SalesFinalIteam ----
                  $salesFinalData = new SalesFinalIteam;
                  $salesFinalData->sales_id = $salesData->id;
                  $salesFinalData->random_number = $salesDetails->random_number;
                  $salesFinalData->user_id = $userId;
                  $salesFinalData->item_id = $item_id;
                  $salesFinalData->qty = $list->qty ?? 0;
                  $salesFinalData->exp = $exp_date;
                  $salesFinalData->gst = $gst;
                  $salesFinalData->mrp = $mrp;
                  $salesFinalData->amt = $list->sub_amount ?? 0;
                  $salesFinalData->unit = $unit;
                  $salesFinalData->batch = $batch_name;
                  $salesFinalData->base = $base;
                  $salesFinalData->location = $location;
                  $salesFinalData->net_rate = $list->sub_amount ?? 0;
                  $salesFinalData->status = '0';
                  $salesFinalData->save();

                  // ---- SalesIteam ----
                  $salesIteam = new SalesIteam;
                  $salesIteam->random_number = $salesDetails->random_number;
                  $salesIteam->item_id = $item_id;
                  $salesIteam->user_id = $userId;
                  $salesIteam->qty = $list->qty ?? 0;
                  $salesIteam->exp = $exp_date;
                  $salesIteam->gst = $gst;
                  $salesIteam->mrp = $mrp;
                  $salesIteam->amt = $list->sub_amount ?? 0;
                  $salesIteam->unit = $unit;
                  $salesIteam->batch = $batch_name;
                  $salesIteam->base = $base;
                  $salesIteam->location = $location;
                  $salesIteam->ptr = $ptr;
                  $salesIteam->net_rate = $list->sub_amount ?? 0;
                  $salesIteam->save();

                  // ---- Ledger ----
                  $userName = CustomerModel::find($customerDetails);

                  $leaderData = new LedgerModel;
                  $leaderData->owner_id = $userName->id ?? "";
                  $leaderData->entry_date = date('Y-m-d');
                  $leaderData->transction = 'Sales Invoice';
                  $leaderData->voucher = 'Sales Invoice';
                  $leaderData->bill_no = '#' . ($salesModelCount + 1);
                  $leaderData->puches_id = $salesData->id;
                  $leaderData->batch = $batch_name;
                  $leaderData->bill_date = date('Y-m-d');
                  $leaderData->name = $userName->name ?? '';
                  $leaderData->user_id = $userId;
                  $leaderData->iteam_id = $item_id;
                  $leaderData->out = $list->qty ?? 0;

                  $ledgers = LedgerModel::where('iteam_id', $item_id)
                      ->where('user_id', $userId)
                      ->orderBy('id', 'DESC')
                      ->first();

                  if ($ledgers) {
                      $balance = ($list->qty - $ledgers->balance_stock);
                      $leaderData->balance_stock = abs($balance);
                  } else {
                      $leaderData->balance_stock = $list->qty ?? 0;
                  }

                  $ledgers = LedgerModel::where('owner_id', $userName->id ?? 0)
                      ->orderBy('id', 'DESC')
                      ->first();

                  if ($ledgers) {
                      $total = $ledgers->balance + ($list->sub_amount ?? 0);
                      $leaderData->credit = $list->sub_amount ?? 0;
                      $leaderData->balance = round($total, 2);
                  } else {
                      $leaderData->credit = $list->sub_amount ?? 0;
                      $leaderData->balance = $list->sub_amount ?? 0;
                  }
                  $leaderData->save();

                  // ---- Update Batch & Final Sales ----
                  if ($batchData) {
                      $finalSalesData = FinalPurchesItem::where('batch', $batch_name)
                          ->where('iteam_id', $item_id)
                          ->whereIn('user_id', $allUserId)
                          ->first();

                      if ($finalSalesData) {
                          $finalData = FinalIteamId::where('final_item_id', $finalSalesData->id)->first();
                          $purchaseData = PurchesDetails::where('purches_id', $finalData->purchase_id ?? 0)
                              ->whereIn('user_id', $allUserId)
                              ->first();

                          if ($purchaseData) {
                              $purchase_qty = (int)$batchData->purches_qty;
                              $free_qty = (int)$batchData->purches_free_qty;
                              $sales_qty = (int)$list->qty / (int)($batchData->unit ?: 1);

                              if ($sales_qty <= $free_qty) {
                                  $free_qty -= $sales_qty;
                              } else {
                                  $sales_qty -= $free_qty;
                                  $free_qty = 0;
                                  $purchase_qty -= $sales_qty;
                              }

                              $finalSalesData->qty = abs($purchase_qty);
                              $finalSalesData->fr_qty = abs($free_qty);
                              $finalSalesData->update();
                          }
                      }

                      $batchData->purches_qty = $finalSalesData->qty ?? 0;
                      $batchData->purches_free_qty = $finalSalesData->fr_qty ?? 0;
                      $batchData->sales_qty = ($batchData->sales_qty ?? 0) + ($list->qty ?? 0);

                      $ledgers = LedgerModel::where('iteam_id', $item_id)
                          ->where('user_id', $userId)
                          ->orderBy('id', 'DESC')
                          ->first();

                      $batchData->total_qty = $ledgers->balance_stock ?? 0;
                      $batchData->total_mrp = $mrp * ($list->qty ?? 0);
                      $batchData->total_ptr = $base * ($list->qty ?? 0);
                      $batchData->update();
                  }
              }
          }
        
          $patientsOrders->sale_id = $salesData->id;

          $patientsOrders->save();

          return $this->sendResponse([], 'Order Accepted Successfully.');
      } else {
          // Handle order rejection
          if ($request->filled('reason')) {
              $chemist = User::find($patientsOrders->chemist_id);

              if ($chemist) {
                  $patientsOrders->reason = $request->reason;

                  $notification_data = new NotificationModel();
                  $notification_data->title = '❌ Order Rejected';
                  $notification_data->description = 'Sorry, your order ' . $orderId . ' was rejected by the ' . ($user_data->name ?? 'Chemist') . '. Please choose a different pharmacy to proceed.';
                  $notification_data->user_id = $patientsOrders->patient_id;
                  $notification_data->order_id = $orderId;
                  $notification_data->is_send = 2;
                  $notification_data->save();

                  $this->patient_notification($notification_data->title, $notification_data->description, $patientsOrders->patient_id);
              }

              $patientsOrders->save();

              return $this->sendResponse([], 'Order Rejected Successfully.');
          }
      }
  }

  // public function chemistOrderStatusTesting(Request $request)
  // {
  //   $patientsOrders = PatientsOrder::find($request->order_id);
  //   if (!$patientsOrders) {
  //     	return $this->sendError('Order data not found.');
  //   }
  //   if(!$request->status) {
  //   	return $this->sendError('Please select status.');
  //   }

  //   // Update order status
  //   $patientsOrders->order_status = $request->status;
  //   $user_data = User::where('id', $patientsOrders->chemist_id)->first();
  //   $orderId = $patientsOrders->order_id;
    
  //   if ($request->status == '4') {
  //     $patientData = PatientsModel::find($patientsOrders->patient_id);

  //     if (!$patientData) {
  //       return $this->sendError('Patient data not found.');
  //     }

  //     $notification_data = new NotificationModel();
  //     $notification_data->title = '✅ Order Accepted';
  //     $notification_data->description = 'Your order ' . $orderId . ' has been accepted by the ' . $user_data->name . '.';
  //     $notification_data->user_id = $patientsOrders->patient_id;
  //     $notification_data->order_id = $orderId;
  //     $notification_data->is_send = 2;
  //     $notification_data->save();

  //     $title = $notification_data->title;
  //     $message = $notification_data->description;
  //     $userId = $patientsOrders->patient_id;

  //     $this->patient_notification($title, $message, $userId);

  //     $customerDetails = null;
  //     $userEmail = CustomerModel::where('phone_number', $patientData->mobile_number)->where('user_id',auth()->user()->id)->first();
  //     if (!empty($userEmail)) {
  //       $customerDetails = $userEmail->id;
  //     } else {
  //       $customer = new CustomerModel;
  //       $customer->name = $patientData->first_name;
  //       $customer->phone_number = $patientData->mobile_number;
  //       $customer->status = '1';
  //       $customer->role = '3';
  //       $customer->user_id = auth()->user()->id;
  //       $customer->save();

  //       $customerDetails = $customer->id;
  //     }

  //     $salesModelCount = SalesModel::where('user_id', auth()->user()->id)->count();
  //     $status = $patientsOrders->delivery_status == '0' ? 'Pickup' : 'Delivery';

  //     // Create Sales Entry
  //     $salesData = new SalesModel;
  //     $salesData->bill_date = date('Y-m-d');
  //     $salesData->customer_id = isset($customerDetails) ? $customerDetails : "";
  //     $salesData->bill_no = $salesModelCount + 1;
  //     $salesData->net_rate = $patientsOrders->new_amount;
  //     $salesData->payment_name = 'cash';
  //     $salesData->mrp_total = $patientsOrders->total_amount;
  //     $salesData->round_off = $patientsOrders->round_off;
  //     $salesData->net_amt = round($patientsOrders->new_amount, 2);
  //     $salesData->owner_name = auth()->user()->name;
  //     $salesData->user_id = auth()->user()->id;
  //     $salesData->pickup = $status;
  //     $salesData->given_amount = 0;
  //     $salesData->save();

  //     // Cash Management Entry
  //     $cashAdd = new CashManagement;
  //     $cashAdd->date = date('Y-m-d');
  //     $cashAdd->description = 'Sales Manage';
  //     $cashAdd->type = 'credit';
  //     $cashAdd->amount = round($patientsOrders->new_amount, 2);
  //     $cashAdd->user_id = auth()->user()->id;
  //     $cashAdd->reference_no = $salesModelCount + 1;
  //     $cashAdd->voucher = 'sales';
  //     $cashAdd->save();

  //     $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
  //     $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
  //     $allUserId = array_merge($staffGetData, $ownerGet, [auth()->user()->id]);

  //     $patientOrderItemDetails = PatientOrderItem::where('patient_order_id', $request->order_id)->get();

  //     if (isset($patientOrderItemDetails)) {
  //       foreach ($patientOrderItemDetails as $list) {
  //         $batchData = BatchModel::where('item_id', $list->iteam_id)->whereIn('user_id', $allUserId)->orderBy('created_at', 'desc')->first();

  //         $textbleVlaue = ($list->qty ?? 0) * ($batchData->base ?? 0);
  //         $userId = auth()->user()->id;
  //         $salesDetails = new salesDetails;
  //         $salesDetails->sales_id = $salesData->id;
  //         $salesDetails->random_number = rand(10000, 99999);
  //         $salesDetails->taxable_value = $textbleVlaue;
  //         $salesDetails->iteam_id = isset($batchData->item_id) ? $batchData->item_id : "";
  //         $salesDetails->unit = isset($batchData->unit) ? $batchData->unit : "";
  //         $salesDetails->batch = isset($batchData->batch_name) ? $batchData->batch_name : "";
  //         $salesDetails->exp = isset($batchData->expiry_date) ? $batchData->expiry_date : "";
  //         $salesDetails->base = isset($batchData->base) ? $batchData->base : "";
  //         $salesDetails->mrp = isset($batchData->mrp) ? $batchData->mrp : "";
  //         $salesDetails->gst = isset($batchData->gst) ? $batchData->gst : "";
  //         $salesDetails->discount = isset($batchData->discount) ? $batchData->discount : "";
  //         $salesDetails->ptr = isset($batchData->ptr) ? $batchData->ptr : "";
  //         $salesDetails->qty = isset($list->qty) ? $list->qty : "";
  //         $salesDetails->amt = isset($list->sub_amount) ? $list->sub_amount : "";
  //         $salesDetails->user_id = $userId;
  //         $salesDetails->location = isset($batchData->location) ? $batchData->location : "";
  //         $salesDetails->save();

  //         $salesFinalData = new SalesFinalIteam;
  //         $salesFinalData->sales_id = $salesData->id;
  //         $salesFinalData->random_number = $salesDetails->random_number;
  //         $salesFinalData->user_id = $userId;
  //         $salesFinalData->item_id = isset($batchData->item_id) ? $batchData->item_id : "";
  //         $salesFinalData->qty = isset($list->qty) ? $list->qty : "";
  //         $salesFinalData->exp = isset($batchData->expiry_date) ? $batchData->expiry_date : "";
  //         $salesFinalData->gst =  isset($batchData->gst) ? $batchData->gst : "";
  //         $salesFinalData->mrp = isset($batchData->mrp) ? $batchData->mrp : "";
  //         $salesFinalData->amt = isset($list->sub_amount) ? $list->sub_amount : "";
  //         $salesFinalData->unit = isset($batchData->unit) ? $batchData->unit : "";
  //         $salesFinalData->batch = isset($batchData->batch_name) ? $batchData->batch_name : "";
  //         $salesFinalData->base = isset($batchData->base) ? $batchData->base : "";
  //         $salesFinalData->location = isset($batchData->location) ? $batchData->location : "";
  //         $salesFinalData->net_rate = isset($list->sub_amount) ? $list->sub_amount : "";
  //         $salesFinalData->status = '0';
  //         $salesFinalData->save();

  //         $userId = auth()->user()->id;
  //         $salesIteam = new SalesIteam;
  //         $salesIteam->random_number = $salesDetails->random_number;
  //         $salesIteam->item_id = isset($batchData->item_id) ? $batchData->item_id : "";
  //         $salesIteam->user_id = $userId;
  //         $salesIteam->qty = isset($list->qty) ? $list->qty : "";
  //         $salesIteam->exp =  isset($batchData->expiry_date) ? $batchData->expiry_date : "";
  //         $salesIteam->gst = isset($batchData->gst) ? $batchData->gst : "";
  //         $salesIteam->mrp = isset($batchData->mrp) ? $batchData->mrp : "";
  //         $salesIteam->amt = isset($list->sub_amount) ? $list->sub_amount : "";
  //         $salesIteam->unit = isset($batchData->unit) ? $batchData->unit : "";
  //         $salesIteam->batch = isset($batchData->base) ? $batchData->base : "";
  //         $salesIteam->base = isset($batchData->base) ? $batchData->base : "";
  //         $salesIteam->location = isset($batchData->location) ? $batchData->location : "";
  //         if (isset($batchData->ptr)) {
  //           $salesIteam->ptr = $batchData->ptr;
  //         }
  //         $salesIteam->net_rate = isset($list->sub_amount) ? $list->sub_amount : "";
  //         $salesIteam->save();

  //         $userName = CustomerModel::where('id', $customerDetails)->first();

  //         $leaderData = new LedgerModel;
  //         $leaderData->owner_id = isset($userName->id) ? $userName->id : "";
  //         $leaderData->entry_date = date('Y-m-d');
  //         $leaderData->transction = 'Sales Invoice';
  //         $leaderData->voucher = 'Sales Invoice';
  //         $leaderData->bill_no = '#' . $salesModelCount + 1;;
  //         $leaderData->puches_id = $salesData->id;
  //         $leaderData->batch = isset($batchData->batch_name) ? $batchData->batch_name : "";
  //         $leaderData->bill_date =  date('Y-m-d');
  //         $leaderData->name = $userName->name;
  //         $leaderData->user_id = auth()->user()->id;
  //         $leaderData->iteam_id = isset($batchData->item_id) ? $batchData->item_id : "";
  //         $ledgers = LedgerModel::where('iteam_id', $list->qty)->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
  //         if (isset($ledgers)) {
  //           $balance = ($list->qty - $ledgers->balance_stock);
  //           $leaderData->out = $list->qty;
  //           $leaderData->balance_stock = abs($balance);
  //         } else {
  //           $leaderData->out = $list->qty;
  //           $leaderData->balance_stock = $list->qty;
  //         }
  //         $ledgers = LedgerModel::where('owner_id', $userName->id)->orderBy('id', 'DESC')->first();
  //         if (isset($ledgers)) {
  //           $total = $ledgers->balance + isset($list->sub_amount) ? $list->sub_amount : "";
  //           $leaderData->credit = isset($batchData->total_mrp) ? $batchData->total_mrp : "";
  //           $leaderData->balance = round($total, 2);
  //         } else {
  //           $leaderData->credit = isset($list->sub_amount) ? $list->sub_amount : "";
  //           $leaderData->balance = isset($list->sub_amount) ? $list->sub_amount : "";
  //         }
  //         $leaderData->save();

  //         $batchData = BatchModel::where('batch_number', $batchData->batch_name)->where('item_id', $batchData->item_id)->whereIn('user_id', $allUserId)->first();

  //         $finalSalesData = FinalPurchesItem::where('batch', $batchData->batch_name)->where('iteam_id', $batchData->item_id)->whereIn('user_id', $allUserId)->first();
  //         if (isset($finalSalesData)) {

  //           $finalData = FinalIteamId::where('final_item_id', $finalSalesData->id)->first();

  //           $purchaseData = PurchesDetails::where('purches_id', $finalData->purchase_id)->whereIn('user_id', $allUserId)->first();
  //           if (isset($purchaseData)) {
  //             $salesQty = salesDetails::where('batch', $batchData->batch_name)->where('iteam_id', $batchData->item_id)->whereIn('user_id', $allUserId)->sum('qty');
  //             $purchase_qty =  (int)$batchData->purches_qty;
  //             $free_qty =  (int)$batchData->purches_free_qty;
  //             $sales_qty = (int)$list->qty / (int)$batchData->unit;
  //             Log::info("unit less qty" . $sales_qty);
  //             // First, deduct from the free_qty
  //             if ($sales_qty <= $free_qty) {
  //               $free_qty -= $sales_qty;
  //             } else {
  //               $sales_qty -= $free_qty;
  //               $free_qty = 0;
  //               $purchase_qty -= $sales_qty;
  //             }
  //             $finalSalesData->qty = abs($purchase_qty);
  //             $finalSalesData->fr_qty = abs($free_qty);

  //             $finalSalesData->update();
  //             Log::info("create sales api purchaes qty" . $purchase_qty . '=' . $sales_qty);
  //             Log::info("create sales api purchaes Free qty" . $free_qty . '=' . $sales_qty);
  //           }
  //         }
  //         if (isset($batchData)) {
  //           $batchData->item_id = isset($batchData->item_id) ? $batchData->item_id : "";
  //           $batchData->unit = isset($batchData->unit) ? $batchData->unit : "";
  //           $batchData->qty = isset($list->qty) ? $list->qty : "";
  //           $batchData->purches_qty = abs($finalSalesData->qty);
  //           $batchData->purches_free_qty = abs($finalSalesData->fr_qty);
  //           $batchData->gst = isset($batchData->gst) ? $batchData->gst : "";
  //           $batchData->expiry_date = isset($batchData->expiry_date) ? $batchData->expiry_date : "";
  //           $batchData->mrp = isset($batchData->mrp) ? $batchData->mrp : "";
  //           $batchData->location = isset($batchData->location) ? $batchData->location : "";
  //           $batchData->sales_qty = $batchData->sales_qty + $list->qty;
  //           $ledgers = LedgerModel::where('iteam_id', $batchData->item_id)->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
  //           $batchData->total_qty = isset($ledgers->balance_stock) ? $ledgers->balance_stock : "";
  //           // $batchData->total_qty = ((int)abs($finalSalesData->qty) + (int)abs($finalSalesData->fr_qty)) * $list['unit'];
  //           $batchData->total_mrp = $batchData->mrp * $list->qty;
  //           $batchData->total_ptr = $batchData->base * $list->qty;
  //           $batchData->update();
  //         }
  //       }
  //     }
  //   } else {
  //   	// Handle order rejection reason
  //       if ($request->filled('reason')) {
  //         $chemist = User::find($patientsOrders->chemist_id);

  //         if ($chemist) {
  //           $patientsOrders->reason = $request->reason;

  //           $notification_data = new NotificationModel();
  //           $notification_data->title = '❌ Order Rejected';
  //           $notification_data->description = 'Sorry, your order ' . $orderId . ' was rejected by the ' . $user_data->name . '. Please choose a different pharmacy to proceed.';
  //           $notification_data->user_id = $patientsOrders->patient_id;
  //           $notification_data->order_id = $orderId;
  //           $notification_data->is_send = 2;
  //           $notification_data->save();

  //           $title = $notification_data->title;
  //           $message = $notification_data->description;
  //           $userId = $patientsOrders->patient_id;

  //           $this->patient_notification($title, $message, $userId);

  //           // $notificationData = new NotificationModel;
  //           // $notificationData->title = "Order Rejected";
  //           // $notificationData->description = "Your order {$patientsOrders->id} has been rejected by {$chemist->name}. Please choose a different pharmacy to proceed.";
  //           // $notificationData->user_id = $patientsOrders->patient_id;
  //           // $notificationData->save();
  //         }
  //       }
  //   }

  //   $patientsOrders->save();

  //   return $this->sendResponse([], 'Status Updated Successfully.');
  // }

  public function chemistNotificationList(Request $request)
  {
    $notificationDataSet = ChemistNotificationModel::where('user_id', auth()->user()->id)->orderBy('id','DESC')->get();
    $notificationList = [];
    if (isset($notificationDataSet)) {
      foreach ($notificationDataSet as $key => $listDetails) {
        $notificationList[$key]['id'] = isset($listDetails->id) ? $listDetails->id : "";
        $notificationList[$key]['order_id'] = isset($listDetails->order_id) ? $listDetails->order_id : "";
        $notificationList[$key]['title'] = isset($listDetails->title) ? $listDetails->title : "";
        $notificationList[$key]['description'] = isset($listDetails->description) ? $listDetails->description : "";
        $notificationList[$key]['date'] = isset($listDetails->created_at) ? Carbon::parse($listDetails->created_at)->format('d M Y g:i A') : "";
        $notificationList[$key]['type'] = "1";
      }
    }

    return $this->sendResponse($notificationList, 'Notification Data Fetch Successfully.');
  }
}
