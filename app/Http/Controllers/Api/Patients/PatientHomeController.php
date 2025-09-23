<?php

namespace App\Http\Controllers\Api\Patients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\ApiToken;
use App\Models\IteamsModel;
use App\Models\BatchModel;
use App\Models\CompanyModel;
use App\Models\iteamPurches;
use App\Models\UniteTable;
use Carbon\Carbon;
use App\Models\Package;
use App\Models\Distributer;
use App\Models\GstModel;
use App\Models\User;
use App\Models\ItemCategory;
use App\Models\PurchesDetails;
use App\Models\PurchesReturnDetails;
use App\Models\LedgerModel;
use Illuminate\Support\Arr;
use App\Models\salesDetails;
use App\Models\SalesModel;
use App\Models\SalesReturn;
use App\Models\SalesReturnDetails;
use App\Models\CustomerModel;
use App\Models\LogsModel;
use App\Models\DrugGroup;
use App\Models\OnlineOrder;
use App\Models\ItemLocation;
use App\Models\SalesIteam;
use App\Models\QrCode;
use App\Models\AddCart;
use Illuminate\Support\Facades\Auth;
use App\Models\PrescrptionModel;
use App\Models\PatientsAddress;
use App\Models\PatientsFamilyModel;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use App\Models\PatientsModel;
use App\Models\RecentIteamModel;
use App\Models\PatientsOrder;
use App\Models\BloodGroup;
use App\Models\PatientFamilyRelation;

class PatientHomeController extends ResponseController
{
  public function patientAddCart(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'iteam_id' => 'required',
    ], [
      'iteam_id.required' => 'Enter Item Id',
    ]);

    if ($validator->fails()) {
      $error = $validator->getMessageBag();
      return $this->sendError($error->first());
    }

    $iteamList = IteamsModel::where('id', $request->iteam_id)->first();
    $itemPurchaseData = iteamPurches::where('item_id', $request->iteam_id)->orderBy('id', 'DESC')->first();

    // Check if the item is already in the cart for the patient
    $checkCart = AddCart::where('iteam_id', $request->iteam_id)
      ->where('patient', auth()->user()->id)
      ->first();

    if (isset($checkCart)) {
      $priceData = $checkCart->qty + 1;
      $iteamMrp = isset($iteamList->mrp) ? $iteamList->mrp : 0;
      // Update existing cart entry
      $checkCart->qty = $priceData;
      $checkCart->chemist = isset($request->chemist_id) ? $request->chemist_id : auth()->user()->your_chemist;
      $checkCart->price = $iteamMrp;
      $checkCart->total = $checkCart->price * $checkCart->qty;
      $checkCart->update();
    } else {
      // Create a new cart entry
      $addToCart = new AddCart;
      $addToCart->iteam_id = $request->iteam_id;
      $addToCart->qty = 1;
      // $addToCart->price = isset($iteamList->mrp) ? $iteamList->mrp : 0;
      $addToCart->price = isset($itemPurchaseData->mrp) ? $itemPurchaseData->mrp : 0;
      $addToCart->total = 1 * $itemPurchaseData->mrp;
      $addToCart->patient = auth()->user()->id;
      $addToCart->chemist = isset($request->chemist_id) ? $request->chemist_id : (isset(auth()->user()->your_chemist) ? auth()->user()->your_chemist : "");
      $addToCart->save();
    }

    return $this->sendResponse([], 'Item Added to Cart Successfully.');
  }

  public function patientCartList(Request $request)
  {
    $patientDataCart = AddCart::where('patient', auth()->user()->id)->get();

    $patientDetails = AddCart::where('patient', auth()->user()->id)->sum('price');
    
    $patientImages = PrescrptionModel::where('user_id',auth()->user()->id)->where('status',1)->get();

    $iteamCartList['cart_list'] = [];
    if (isset($patientDataCart)) {
      foreach ($patientDataCart as $key => $listData) {
        $chemistData = User::where('id', $listData->chemist)->first();
        $iteamList = IteamsModel::where('id', $listData->iteam_id)->first();
        $uniteData = UniteTable::where('id', $iteamList->old_unit)->first();
        $drugGroupData = DrugGroup::find($iteamList->drug_group);

        $iteamCartList['cart_list'][$key]['id'] = isset($listData->id) ? $listData->id : "";
        $iteamCartList['cart_list'][$key]['chemist_name'] = isset($chemistData->name) ? $chemistData->name : "";
        $iteamCartList['cart_list'][$key]['iteam_name'] = isset($iteamList->iteam_name) ? $iteamList->iteam_name : "";
        $iteamCartList['cart_list'][$key]['iteam_id'] = isset($listData->iteam_id) ? $listData->iteam_id : "";
        $iteamCartList['cart_list'][$key]['price'] = isset($listData->price) ? $listData->price : "";
        $iteamCartList['cart_list'][$key]['qty'] = isset($listData->qty) ? $listData->qty : "";
        $iteamCartList['cart_list'][$key]['old_unit'] = isset($iteamList->old_unit) ? $iteamList->old_unit : "";
        $iteamCartList['cart_list'][$key]['unit'] = isset($iteamList->unit) ? $iteamList->unit : "";
        $iteamCartList['cart_list'][$key]['drug_group_id'] = isset($iteamList->drug_group) ? $iteamList->drug_group : "";
        $iteamCartList['cart_list'][$key]['drug_group_name'] = isset($drugGroupData->name) ? $drugGroupData->name : "";
        $iteamCartList['cart_list'][$key]['packing_size'] = isset($iteamList->packing_size) ? $iteamList->packing_size : "";
        $iteamCartList['cart_list'][$key]['front_photo'] = isset($iteamList->front_photo) ? asset('/public/front_photo/' . $iteamList->front_photo) : "";
      }
    }
    
    $iteamCartList['image_list'] = [];
    if(isset($patientImages))
    {
    	foreach($patientImages as $key1 => $list)
        {
          	$iteamCartList['image_list'][$key1]['image_id'] = isset($list->id) ? $list->id : "";
          	$iteamCartList['image_list'][$key1]['image'] = isset($list->images) ? asset('/public/license_image/'.$list->images) : "";
        }
    }

    $iteamCartList['total_amount'] = isset($patientDetails) ? (string)$patientDetails : "";

    $patientOrderData = PatientsOrder::whereNotNull('iteam_id')->where('patient_id', auth()->user()->id)->pluck('iteam_id')->toArray();

    $iteamModelData = IteamsModel::whereIn('id', $patientOrderData)->get();

    $iteamCartList['last_order_list'] = [];
    if (isset($iteamModelData)) {
      foreach ($iteamModelData as $dataKey => $listDetails) {
        $uniteData = UniteTable::where('id', $listDetails->old_unit)->first();
        $drugGroupData = DrugGroup::where('id',$iteamList->drug_group)->first();
        
        $iteamCartList['last_order_list'][$dataKey]['id'] = isset($listDetails->id) ? $listDetails->id : "";
        $iteamCartList['last_order_list'][$dataKey]['iteam_name'] = isset($listDetails->iteam_name) ? $listDetails->iteam_name : "";
        $iteamCartList['last_order_list'][$dataKey]['iteam_id'] = isset($listDetails->id) ? (string)$listDetails->id : "";
        $iteamCartList['last_order_list'][$dataKey]['price'] = isset($listDetails->mrp) ? $listDetails->mrp : "";
        $iteamCartList['last_order_list'][$dataKey]['old_unit'] = isset($listDetails->old_unit) ? $listDetails->old_unit : "";
        $iteamCartList['last_order_list'][$dataKey]['unit'] = isset($listDetails->unit) ? $listDetails->unit : "";
        $iteamCartList['last_order_list'][$dataKey]['drug_group_id'] = isset($listDetails->drug_group) ? $listDetails->drug_group : "";
        $iteamCartList['last_order_list'][$dataKey]['drug_group_name'] = isset($drugGroupData->name) ? $drugGroupData->name : "";
        $iteamCartList['last_order_list'][$dataKey]['packing_size'] = isset($listDetails->packing_size) ? $listDetails->packing_size : "";
        $iteamCartList['last_order_list'][$dataKey]['front_photo'] = isset($listDetails->front_photo) ? asset('/public/front_photo/' . $listDetails->front_photo) : "";

        $PatientsChemistList['recent_iteam'][$dataKey]['preferred_chemist'] = [];
        if (empty(auth()->user()->your_chemist)) {
          $latitude = auth()->user()->latitude;
          $longitude = auth()->user()->longitude;
          $radius = 10; // 10 km
          $batchDeatils = BatchModel::where('item_id', $listDetails->id)->pluck('user_id')->toArray();

          $usersChemist = DB::table('users')
            ->select(
              'users.*',
              DB::raw("(6371 * acos(cos(radians(?)) 
                                                      * cos(radians(latitude)) 
                                                      * cos(radians(longitude) - radians(?)) 
                                                      + sin(radians(?)) 
                                                      * sin(radians(latitude)))) AS distance")
            )
            ->setBindings([$latitude, $longitude, $latitude])
            ->whereIn('id', $batchDeatils)
            ->having('distance', '<', $radius)
            ->orderBy('distance', 'asc')
            ->get();

          $PatientsChemistList['last_order_list'][$dataKey]['preferred_chemist'] = [];
          foreach ($usersChemist as $keys => $listUserDetails) {
            $iteamCartList['last_order_list'][$dataKey]['preferred_chemist'][$keys]['id'] = isset($listUserDetails->id) ? $listUserDetails->id : "";
            $iteamCartList['last_order_list'][$dataKey]['preferred_chemist'][$keys]['pharmacy_logo'] = isset($listUserDetails->pharmacy_logo) ? asset('/pharmacy_logo/' . $listUserDetails->pharmacy_logo) : "";
            $iteamCartList['last_order_list'][$dataKey]['preferred_chemist'][$keys]['owner_name'] = isset($listUserDetails->owner_name) ? $listUserDetails->owner_name : "";
            $iteamCartList['last_order_list'][$dataKey]['preferred_chemist'][$keys]['name'] = isset($listUserDetails->name) ? $listUserDetails->name : "";
            $iteamCartList['last_order_list'][$dataKey]['preferred_chemist'][$keys]['address'] = isset($listUserDetails->address) ? $listUserDetails->address : "";
          }
        }
      }
    }

    return $this->sendResponse($iteamCartList, 'Cart List Data Fetch Successfully.');
  }

  public function patientCartDelete(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required',
    ], [
      'id.required' => 'Enter Id',
    ]);

    if ($validator->fails()) {
      $error = $validator->getMessageBag();
      return $this->sendError($error->first());
    }

    $patientDataCart = AddCart::where('id', $request->id)->first();
    if (isset($patientDataCart)) {
      $patientDataCart->delete();
    }

    return $this->sendResponse([], 'Cart Item Deleted Successfully.');
  }

  public function patientChekoutDetails(Request $request)
  {
    $cartList = json_decode($request->cart_list);
    if (!empty($cartList)) {
        foreach ($cartList as $listData) {
            $patientDataCart = AddCart::where('iteam_id', $listData->iteam_id)->where('patient', auth()->user()->id)->first();
            if (isset($patientDataCart)) {
              $patientDataCart->qty =  $listData->qty;
              $patientDataCart->price =  $listData->price;
              $patientDataCart->total =  $listData->qty * $listData->price;
              $patientDataCart->update();
            }
        }

      	return $this->sendResponse([], 'Checkout Successfully.');
    } else {
    	return $this->sendError('Please select at least one item for checkout.');
    }

    // if ($request->allFiles()) {
    //   $prescrptionData = PrescrptionModel::where('user_id', auth()->user()->id)->get();
    //   if (isset($prescrptionData)) {
    //     foreach ($prescrptionData as $listDatas) {
    //       $listDatas->delete();
    //     }
    //   }
    //   foreach ($request->allFiles() as $key => $file) {
    //     if (str_starts_with($key, 'image')) { // Ensures processing only relevant images

    //       $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
    //       $file->move(public_path('/license_image'), $filename);

    //       $newDetails = new PrescrptionModel;
    //       $newDetails->images =  $filename;
    //       $newDetails->user_id = auth()->user()->id;
    //       $newDetails->save();
    //     }
    //   }
    // }

    // return $this->sendResponse([], 'Checkout Successfully.');
  }

  public function patientOrderSummary(Request $request)
  {
    $userData = auth()->user();
    $patienstAddress = PatientsAddress::where('patient_id', auth()->user()->id)->get();

    $orderSummry['address'] = [];
    if (isset($patienstAddress)) {
      foreach ($patienstAddress as $key => $listDetails) {
        $type = null;
        if($listDetails->type == '0')
        {
          $type = 'Home';
        }else{
          $type = 'Work';
        }
        $orderSummry['address'][$key]['id'] = isset($listDetails->id) ? $listDetails->id : "";
        $orderSummry['address'][$key]['address'] = isset($listDetails->address) ? $listDetails->address : "";
        $orderSummry['address'][$key]['type'] = $type;
        $orderSummry['address'][$key]['area_landmark'] = isset($listDetails->area_landmark) ? $listDetails->area_landmark : "";
        $orderSummry['address'][$key]['city'] = isset($listDetails->city) ? $listDetails->city : "";
        $orderSummry['address'][$key]['pincode'] = isset($listDetails->pincode) ? $listDetails->pincode : "";
      }
    }

    $orderSummry['family_member'] = [];
    
    $userBloodGroupData = BloodGroup::where('id',$userData->blood_group)->first();
    $selfPatienstDetails = [
      'id' => 'Self',
      'relation_id' => '',
      'relation_name' => 'Self',
      'first_name' => isset($userData->first_name) ? $userData->first_name :'',
      'last_name' => isset($userData->last_name) ? $userData->last_name :'',
      'mobile_number' => isset($userData->mobile_number) ? $userData->mobile_number :'',
      'blood_group_id' => isset($userData->blood_group) ? $userData->blood_group :'',
      'blood_group' => isset($userBloodGroupData->name) ? $userBloodGroupData->name :'',
      'date_of_birth' => isset($userData->date_of_birth) ? $userData->date_of_birth :'',
      'image' => isset($userData->profile_image) ? asset('public/image/'.$userData->profile_image) :'',
    ];
    
    $patientFamliyMember = PatientsFamilyModel::where('patients_id', auth()->user()->id)->get();

    if ($patientFamliyMember->isNotEmpty()) {
      foreach ($patientFamliyMember as $fkey => $listFamliyDetails) {
        $patientRelationData = PatientFamilyRelation::where('id',$listFamliyDetails->relation_name)->first();
        $bloodGroupData = BloodGroup::where('id',$listFamliyDetails->blood_group)->first();
        
        $orderSummry['family_member'][$fkey]['id'] = (string) $listFamliyDetails->id ?? "";
        $orderSummry['family_member'][$fkey]['relation_id'] = $listFamliyDetails->relation_name ?? "";
        $orderSummry['family_member'][$fkey]['relation_name'] = $patientRelationData->name ?? "";
        $orderSummry['family_member'][$fkey]['first_name'] = $listFamliyDetails->first_name ?? "";
        $orderSummry['family_member'][$fkey]['last_name'] = $listFamliyDetails->last_name ?? "";
        $orderSummry['family_member'][$fkey]['mobile_number'] = $listFamliyDetails->mobile_number ?? "";
        $orderSummry['family_member'][$fkey]['blood_group_id'] = $listFamliyDetails->blood_group ?? "";
        $orderSummry['family_member'][$fkey]['blood_group'] = $bloodGroupData->name ?? "";
        $orderSummry['family_member'][$fkey]['date_of_birth'] = $listFamliyDetails->date_of_birth ?? "";
        $orderSummry['family_member'][$fkey]['image'] = !empty($listFamliyDetails->images)
          ? asset('public/image/' . $listFamliyDetails->images)
          : '';
      }
    }
    $orderSummry['family_member'] = array_merge([$selfPatienstDetails], $orderSummry['family_member']);

    $orderSummry['cart_list'] = [];
    $patientCartDetails = AddCart::where('patient', auth()->user()->id)->get();
    if (isset($patientCartDetails)) {
      foreach ($patientCartDetails as $keyDetails => $list) {
        $iteamList = IteamsModel::where('id', $list->iteam_id)->first();
        $uniteData = UniteTable::where('id', $iteamList->old_unit)->first();
        $usersChemist = User::where('id', $list->chemist)->first();
        $drugGroupData = DrugGroup::find($iteamList->drug_group);
        
        $finalAmount = $list->price * $list->qty;

        $orderSummry['cart_list'][$keyDetails]['id'] = isset($list->id) ? $list->id : "";
        $orderSummry['cart_list'][$keyDetails]['iteam_name'] = isset($iteamList->iteam_name) ? $iteamList->iteam_name : "";
        $orderSummry['cart_list'][$keyDetails]['iteam_id'] = isset($list->iteam_id) ? $list->iteam_id : "";
        $orderSummry['cart_list'][$keyDetails]['price'] = isset($list->price) ? $list->price : "";
        $orderSummry['cart_list'][$keyDetails]['qty'] = isset($list->qty) ? $list->qty : "";
        $orderSummry['cart_list'][$keyDetails]['old_unit'] = isset($iteamList->old_unit) ? $iteamList->old_unit : "";
        $orderSummry['cart_list'][$keyDetails]['unit'] = isset($iteamList->unit) ? $iteamList->unit : "";
        $orderSummry['cart_list'][$keyDetails]['drug_group_id'] = isset($iteamList->drug_group) ? $iteamList->drug_group : "";
        $orderSummry['cart_list'][$keyDetails]['drug_group_name'] = isset($drugGroupData->name) ? $drugGroupData->name : "";
        $orderSummry['cart_list'][$keyDetails]['packing_size'] = isset($iteamList->packing_size) ? $iteamList->packing_size : "";
        $orderSummry['cart_list'][$keyDetails]['front_photo'] = isset($iteamList->front_photo) ? asset('/public/front_photo/' . $iteamList->front_photo) : "";
        $orderSummry['cart_list'][$keyDetails]['chemist_id'] = isset($usersChemist->id) ? (string)$usersChemist->id : "";
        $orderSummry['cart_list'][$keyDetails]['pharmacy_logo'] = isset($usersChemist->pharmacy_logo) ? asset('/pharmacy_logo/' . $usersChemist->pharmacy_logo) : "";
        $orderSummry['cart_list'][$keyDetails]['owner_name'] = isset($usersChemist->owner_name) ? $usersChemist->owner_name : "";
        $orderSummry['cart_list'][$keyDetails]['name'] = isset($usersChemist->name) ? $usersChemist->name : "";
        $orderSummry['cart_list'][$keyDetails]['address'] = isset($usersChemist->address) ? $usersChemist->address : "";
        $orderSummry['cart_list'][$keyDetails]['final_amount'] = (string) $finalAmount ? : "0" ;
      }
    }

    $orderSummry['prescrption_list'] = [];
    $prescrptionImages = PrescrptionModel::where('user_id', auth()->user()->id)->whereNull('order_id')->get();
    if (isset($prescrptionImages)) {
      foreach ($prescrptionImages as $keys => $listDetailsPrescrption) {
        $orderSummry['prescrption_list'][$keys]['id'] = isset($listDetailsPrescrption->id) ? $listDetailsPrescrption->id : "";
        $orderSummry['prescrption_list'][$keys]['image'] = isset($listDetailsPrescrption->images) ? asset('public/license_image/' . $listDetailsPrescrption->images) : "";
      }
    }
    
    $orderSummry['total_amount'] = 0;

    foreach ($patientCartDetails as $keyDetails => $list) {
        $finalAmount = $list->price * $list->qty;
        $orderSummry['total_amount'] += $finalAmount;
    }
    $orderSummry['total_amount'] = isset($orderSummry['total_amount']) ? (string)$orderSummry['total_amount'] : "0";
    
    $originalAmount = $orderSummry['total_amount'];
    // $originalAmount = "1600.70";
    $roundedAmount = round($originalAmount);
    $rounding_off = $roundedAmount - $originalAmount;
    $rounding_off = number_format($rounding_off, 2, '.', '');
    $net_amount = $originalAmount + $rounding_off;
    
    // Store values in the summary
    $orderSummry['total_amount'] = number_format((float)$originalAmount, 2, '.', ''); 
    $orderSummry['round_off'] = (string)$rounding_off;
    $orderSummry['net_amount'] = (string)number_format((float)$net_amount, 2, '.', '');

    // $patientCartAmount = AddCart::where('patient', auth()->user()->id)->sum('price');
    // $orderSummry['total_amount'] = isset($patientCartAmount) ? (string)$patientCartAmount : "";
    
    return $this->sendResponse($orderSummry, 'Order Summary Data Fetched Successfully.');
  }

  public function patientHome(Request $request)
  {
    $PatientsChemistList = [
      'chemist_list' => [],
      'selected_preferred_pharmacy' => [],
      'recent_iteam' => [],
      'recommended_iteam' => [],
      'top_chemist_list' => [],
    ];

    $user = auth()->user();

    if (empty($user->your_chemist)) {
      $latitude = $user->latitude;
      $longitude = $user->longitude;
      $radius = 10; // 10 km

      $usersChemist = DB::table('users')
        ->select(
          'users.*',
          DB::raw("(6371 * acos(cos(radians(?)) 
                        * cos(radians(latitude)) 
                        * cos(radians(longitude) - radians(?)) 
                        + sin(radians(?)) 
                        * sin(radians(latitude)))) AS distance")
        )
        ->setBindings([$latitude, $longitude, $latitude])
        ->having('distance', '<', $radius)
        ->orderBy('distance', 'asc')
        ->get();

      if ($usersChemist->isNotEmpty()) {
        foreach ($usersChemist as $key => $listData) {
          $PatientsChemistList['chemist_list'][$key] = [
            'id' => $listData->id ?? "",
            'pharmacy_logo' => $listData->pharmacy_logo ? asset('/pharmacy_logo/' . $listData->pharmacy_logo) : "",
            'owner_name' => $listData->owner_name ?? "",
            'name' => $listData->name ?? "",
          ];
        }
      }
    }

    $PatientsChemistList['is_preferred_pharmacy'] = !empty($user->your_chemist);
    $PatientsChemistList['total_chemist'] = (string) DB::table('users')->count();

    $selectedPreferredPharmacyData = User::find($user->your_chemist);

    if ($selectedPreferredPharmacyData) {
      $whatsappNumber = $selectedPreferredPharmacyData->phone_number ?? '';
      $whatsappLink = "https://api.whatsapp.com/send?phone=$whatsappNumber&text=Hello";

      $PatientsChemistList['selected_preferred_pharmacy'][] = [
        'name' => $selectedPreferredPharmacyData->name ?? '',
        'phone_number' => $selectedPreferredPharmacyData->phone_number ?? '',
        'address' => $selectedPreferredPharmacyData->address ?? '',
        'latitude' => $selectedPreferredPharmacyData->latitude ?? '',
        'longitude' => $selectedPreferredPharmacyData->longitude ?? '',
        'image' => $selectedPreferredPharmacyData->pharmacy_logo ? asset('pharmacy_logo/' . $selectedPreferredPharmacyData->pharmacy_logo) : "",
        'whatsapp_number' => $whatsappLink ?? "",
      ];
    }

    $settingData = Setting::first();
    $PatientsChemistList['referring_image'] = $settingData->reference_image ? asset('uploads/students/' . $settingData->reference_image) : "";

    // Recent Items
    $recentIteamData = RecentIteamModel::where('user_id', $user->id)->pluck('iteam_id')->toArray();
    $iteamModelData = IteamsModel::whereIn('id', $recentIteamData)->get();

    foreach ($iteamModelData as $dataKey => $listDetails) {
      $uniteData = UniteTable::find($listDetails->old_unit);
      $drugGroupData = DrugGroup::find($listDetails->drug_group);
      $itemPurchaseData = iteamPurches::where('item_id', $listDetails->id)->orderBy('id', 'DESC')->first();
      $PatientsChemistList['recent_iteam'][$dataKey] = [
        'id' => $listDetails->id ?? "",
        'iteam_name' => $listDetails->iteam_name ?? "",
        'iteam_id' => (string)($listDetails->id ?? ""),
        // 'price' => $listDetails->mrp ?? "",
        'price' => $itemPurchaseData->mrp ?? "",
        // 'old_unit' => $listDetails->old_unit ?? "",
        'old_unit' => $itemPurchaseData->unit ?? "",
        'drug_group_id' => $listDetails->drug_group ?? "",
        'drug_group_name' => $drugGroupData->name ?? "",
        'unit' => $itemPurchaseData->unit ?? "",
        'packing_size' => $listDetails->packing_size ?? "",
        'front_photo' => $listDetails->front_photo ? asset('/public/front_photo/' . $listDetails->front_photo) : "",
        'preferred_chemist' => [],
      ];

      if (empty($user->your_chemist)) {
        $batchDeatils = BatchModel::where('item_id', $listDetails->id)->pluck('user_id')->toArray();
        $usersChemist = DB::table('users')
          ->select(
            'users.*',
            DB::raw("(6371 * acos(cos(radians(?)) 
                            * cos(radians(latitude)) 
                            * cos(radians(longitude) - radians(?)) 
                            + sin(radians(?)) 
                            * sin(radians(latitude)))) AS distance")
          )
          ->setBindings([$latitude, $longitude, $latitude])
          ->whereIn('id', $batchDeatils)
          ->having('distance', '<', $radius)
          ->orderBy('distance', 'asc')
          ->get();

        foreach ($usersChemist as $key => $listUserDetails) {
          $PatientsChemistList['recent_iteam'][$dataKey]['preferred_chemist'][$key] = [
            'id' => $listUserDetails->id ?? "",
            'pharmacy_logo' => $listUserDetails->pharmacy_logo ? asset('/pharmacy_logo/' . $listUserDetails->pharmacy_logo) : "",
            'owner_name' => $listUserDetails->owner_name ?? "",
            'name' => $listUserDetails->name ?? "",
            'address' => $listUserDetails->address ?? "",
          ];
        }
      }
    }

    // Recommended Items
    $iteamModelDetails = IteamsModel::where('status', '1')->take(6)->get();
    foreach ($iteamModelDetails as $keyIteam => $listDataReccomand) {
      $uniteData = UniteTable::find($listDataReccomand->old_unit);
      $drugGroupData = DrugGroup::find($listDataReccomand->drug_group);
      $PatientsChemistList['recommended_iteam'][$keyIteam] = [
        'id' => $listDataReccomand->id ?? "",
        'iteam_name' => $listDataReccomand->iteam_name ?? "",
        'iteam_id' => (string)($listDataReccomand->id ?? ""),
        'price' => $listDataReccomand->mrp ?? "",
        'old_unit' => $listDataReccomand->old_unit ?? "",
        'unit' => $listDataReccomand->unit ?? "",
        'drug_group_id' => $listDataReccomand->drug_group ?? "",
        'drug_group_name' => $drugGroupData->name ?? "",
        'packing_size' => $listDataReccomand->packing_size ?? "",
        'front_photo' => $listDataReccomand->front_photo ? asset('/public/front_photo/' . $listDataReccomand->front_photo) : "",
        'preferred_chemist' => [],
      ];

      if (empty($user->your_chemist)) {
        $batchDeatils = BatchModel::where('item_id', $listDataReccomand->id)->pluck('user_id')->toArray();
        $usersChemist = DB::table('users')
          ->select(
            'users.*',
            DB::raw("(6371 * acos(cos(radians(?)) 
                            * cos(radians(latitude)) 
                            * cos(radians(longitude) - radians(?)) 
                            + sin(radians(?)) 
                            * sin(radians(latitude)))) AS distance")
          )
          ->setBindings([$latitude, $longitude, $latitude])
          ->whereIn('id', $batchDeatils)
          ->having('distance', '<', $radius)
          ->orderBy('distance', 'asc')
          ->get();

        foreach ($usersChemist as $key => $listUserDetails) {
          $PatientsChemistList['recommended_iteam'][$keyIteam]['preferred_chemist'][$key] = [
            'id' => $listUserDetails->id ?? "",
            'pharmacy_logo' => $listUserDetails->pharmacy_logo ? asset('/pharmacy_logo/' . $listUserDetails->pharmacy_logo) : "",
            'owner_name' => $listUserDetails->owner_name ?? "",
            'name' => $listUserDetails->name ?? "",
            'address' => $listUserDetails->address ?? "",
          ];
        }
      }
    }

    $PatientsChemistList['top_chemist_list'] = [
      [
        'title' => 'Check Alternative of Medicines',
        'image' => asset('/pharmacy_logo/top_1.jpeg'),
      ],
      [
        'title' => 'Order History',
        'image' => asset('/pharmacy_logo/top_2.jpg'),
      ],
      [
        'title' => 'Order from Local Pharmacy',
        'image' => asset('/pharmacy_logo/top_3.jpg'),
      ],
    ];

    return $this->sendResponse($PatientsChemistList, 'Data Fetch Successfully.');
  }

  public function patientChemistSearch(Request $request)
  {
    if ($request->latitude && $request->longitude) {
      $latitude = $request->latitude;
      $longitude = $request->longitude;
    } else {
      $latitude = auth()->user()->latitude;
      $longitude = auth()->user()->longitude;
    }

    $radius = 10; // 10 km
    $search = $request->input('search');

    $usersChemist = DB::table('users')
      ->select(
        'users.*',
        DB::raw("(6371 * acos(cos(radians(?)) 
                                * cos(radians(latitude)) 
                                * cos(radians(longitude) - radians(?)) 
                                + sin(radians(?)) 
                                * sin(radians(latitude)))) AS distance")
      )
      ->setBindings([$latitude, $longitude, $latitude])
      ->when($search, function ($query, $search) {
        return $query->where('users.name', 'LIKE', "%$search%");
      })
      ->having('distance', '<', $radius)
      ->orderBy('distance', 'asc');

    $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
    $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
    $offset = ($page - 1) * $limit;

    $usersChemist = $usersChemist->limit($limit)->offset($offset)->get();

    $PatientsChemistList = [];
    if (isset($usersChemist)) {
      foreach ($usersChemist as $key => $listData) {
        $PatientsChemistList[$key]['id'] = isset($listData->id) ? $listData->id : "";
        $PatientsChemistList[$key]['pharmacy_logo'] = isset($listData->pharmacy_logo) ? asset('/pharmacy_logo/' . $listData->pharmacy_logo) : "";
        $PatientsChemistList[$key]['owner_name'] = isset($listData->owner_name) ? $listData->owner_name : "";
        $PatientsChemistList[$key]['name'] = isset($listData->name) ? $listData->name : "";
        $PatientsChemistList[$key]['address'] = isset($listData->address) ? $listData->address : "";
      }
    }

    return $this->sendResponse($PatientsChemistList, 'Data Fetch Successfully');
  }

  public function patientChemistDetails(Request $request)
  {
    $userDetails = User::where('id', $request->id)->first();
    if (empty($userDetails)) {
      return $this->sendError('Please Enter Valid Id');
    }

    $PatientsChemistList = [];
    $PatientsChemistList['id'] = isset($userDetails->id) ? (string)$userDetails->id : "";
    $PatientsChemistList['name'] = isset($userDetails->name) ? (string)$userDetails->name : "";
    $PatientsChemistList['start_time'] = isset($userDetails->start_time) ? (string)$userDetails->start_time : "";
    $PatientsChemistList['end_time'] = isset($userDetails->end_time) ? (string)$userDetails->end_time : "";
    $PatientsChemistList['owner_name'] = isset($userDetails->owner_name) ? (string)$userDetails->owner_name : "";
    $PatientsChemistList['rating'] = "";
    $PatientsChemistList['phone_number'] = isset($userDetails->phone_number) ? (string)$userDetails->phone_number : "";
    $PatientsChemistList['pharmacy_whatsapp'] = isset($userDetails->pharmacy_whatsapp) ? (string)$userDetails->pharmacy_whatsapp : "";
    $PatientsChemistList['email'] = isset($userDetails->email) ? (string)$userDetails->email : "";
    $PatientsChemistList['address'] = isset($userDetails->address) ? (string)$userDetails->address : "";
    $PatientsChemistList['zip_code'] = isset($userDetails->zip_code) ? (string)$userDetails->zip_code : "";
    $PatientsChemistList['latitude'] = isset($userDetails->latitude) ? (string)$userDetails->latitude : "";
    $PatientsChemistList['longitude'] = isset($userDetails->longitude) ? (string)$userDetails->longitude : "";
    $PatientsChemistList['pharmacy_logo'] = isset($userDetails->pharmacy_logo) ? asset('/pharmacy_logo/' . $userDetails->pharmacy_logo) : "";
    return $this->sendResponse($PatientsChemistList, 'Data Updated Successfully');
  }

  public function patientPreferredChemist(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required',
    ], [
      'id.required' => 'Enter Id',
    ]);

    if ($validator->fails()) {
      $error = $validator->getMessageBag();
      return $this->sendError($error->first());
    }

    $userDetails = PatientsModel::where('id', auth()->user()->id)->first();

    if (isset($userDetails)) {
      $userDetails->your_chemist = $request->id;
      $userDetails->update();
    }

    return $this->sendResponse([], 'Data Updated Successfully');
  }
  
  public function singleImageAdd(Request $request)
  {
      $image_store_data = new PrescrptionModel();
      if (!empty($request->image)) {
      	$image_store_data->user_id = auth()->user()->id;
        $filename = '';

        $image = $request->image;
        $originalName = str_replace(' ', '_', $image->getClientOriginalName());
        $filename = time() . '_' . $originalName;
        $image->move(public_path('/license_image'), $filename);

        $image_store_data->images = $filename;
      	$image_store_data->save();
      }
    
      return $this->sendResponse([], 'Image Upload Successfully.');
  }
  
  public function singleImageDelete(Request $request)
  {
      $image_delete_data = PrescrptionModel::where('id',$request->image_id)->first();
      if(isset($image_delete_data))
      {
        $image_delete_data->delete();
      }else
      {
        return $this->sendError('Data not found.');
      }
    
      return $this->sendResponse([], 'Image Deleted Successfully.');
  }
}
