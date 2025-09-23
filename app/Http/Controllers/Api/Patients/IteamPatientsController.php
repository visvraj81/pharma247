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
use App\Models\UniteTable;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ItemCategory;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerModel;
use App\Models\LogsModel;
use App\Models\DrugGroup;
use App\Models\OnlineOrder;
use App\Models\ItemLocation;
use App\Models\SalesIteam;
use App\Models\QrCode;
use Illuminate\Support\Facades\Auth;
use App\Models\RecentIteamModel;
use App\Models\PatientsModel;
use App\Models\PatientsOrder;
use App\Models\AddCart;
use App\Models\PatientsFamilyModel;
use App\Models\PatientsAddress;
use App\Models\GstModel;
use App\Models\iteamPurches;
use App\Models\PatientsDeviceToken;

class IteamPatientsController extends ResponseController
{
  public function patientIteamList(Request $request)
  {
    $purchaseItemData = iteamPurches::pluck('item_id')->toArray();
    $limit = 12;
    $iteamsData = IteamsModel::with('getDrugGroup')->whereIn('id', $purchaseItemData)->orderBy('id', 'ASC')->limit($limit);
    // $iteamsData = IteamsModel::with('getDrugGroup')->orderBy('id', 'ASC')->limit($limit);
    if ($request->filled('search')) {
      $searchTerm = $request->input('search'); // Get search term
      $iteamsData->where(function ($query) use ($searchTerm) {
        $query->where('iteam_name', 'like', "%{$searchTerm}%")
          ->orWhereHas('getDrugGroup', function ($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%");
          });
      });
    }
    $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
    $offset = ($page - 1) * $limit;
    $iteamsData->offset($offset);
    $iteamsData =  $iteamsData->get();

    $listDetails = [];
    if (isset($iteamsData)) {
      foreach ($iteamsData as $key => $listData) {
        $uniteData = UniteTable::where('id', $listData->old_unit)->first();
        $company = CompanyModel::where('id', $listData->pharma_shop)->first();
        $gstData = GstModel::where('id', $listData->gst)->first();
        $drugGroupData = DrugGroup::where('id', $listData->drug_group)->first();

        $itemPurchaseData = iteamPurches::where('item_id', $listData->id)->orderBy('id', 'DESC')->first();

        $listDetails[$key]['id'] = isset($listData->id) ? $listData->id : "";
        $listDetails[$key]['iteam_name'] = isset($listData->iteam_name) ? $listData->iteam_name : "";
        $listDetails[$key]['company'] = isset($company->company_name) ? $company->company_name : "";
        // $listDetails[$key]['unit'] = isset($listData->unit) ? $listData->unit : "";
        $listDetails[$key]['unit'] = isset($itemPurchaseData->unit) ? $itemPurchaseData->unit : "";
        $listDetails[$key]['drug_group_id'] = isset($listData->drug_group) ? $listData->drug_group : "";
        $listDetails[$key]['drug_group_name'] = isset($drugGroupData->name) ? $drugGroupData->name : "";
        // $listDetails[$key]['mrp'] = isset($listData->mrp) ? $listData->mrp : "";
        $listDetails[$key]['mrp'] = isset($itemPurchaseData->mrp) ? $itemPurchaseData->mrp : "";
        $listDetails[$key]['front_photo'] = isset($listData->front_photo) ? asset('/public/front_photo/' . $listData->front_photo) : "";
        $listDetails[$key]['hsn_code'] = isset($listData->hsn_code) ? $listData->hsn_code : "";
        $listDetails[$key]['gst'] = isset($listData->gst) ? $listData->gst : "";
        $listDetails[$key]['gst_name'] = isset($gstData->name) ? $gstData->name : "";

        $listDetails[$key]['preferred_chemist'] = [];
        if (empty(auth()->user()->your_chemist)) {
          $latitude = auth()->user()->latitude;
          $longitude = auth()->user()->longitude;
          $radius = 10; // 10 km
          $batchDeatils = BatchModel::where('item_id', $listData->id)->pluck('user_id')->toArray();

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

          $listDetails[$key]['preferred_chemist'] = [];
          foreach ($usersChemist as $keyDeatils => $listUserDetails) {
              $listDetails[$key]['preferred_chemist'][$keyDeatils]['id'] = isset($listUserDetails->id) ? $listUserDetails->id : "";
              $listDetails[$key]['preferred_chemist'][$keyDeatils]['pharmacy_logo'] = isset($listUserDetails->pharmacy_logo) ? asset('/pharmacy_logo/' . $listUserDetails->pharmacy_logo) : "";
              $listDetails[$key]['preferred_chemist'][$keyDeatils]['owner_name'] = isset($listUserDetails->owner_name) ? $listUserDetails->owner_name : "";
              $listDetails[$key]['preferred_chemist'][$keyDeatils]['name'] = isset($listUserDetails->name) ? $listUserDetails->name : "";
              $listDetails[$key]['preferred_chemist'][$keyDeatils]['address'] = isset($listUserDetails->address) ? $listUserDetails->address : "";
          }
        }
      }
    }

    return $this->sendResponse($listDetails, 'Item Data Fetch Successfully.');
  }

  public function patientIteamDetails(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'iteam_id' => 'required',
    ], [
      'iteam_id.required' => 'Enter Iteam Id',
    ]);

    if ($validator->fails()) {
      $error = $validator->getMessageBag();
      return $this->sendError($error->first());
    }

    $iteamData = IteamsModel::where('id', $request->iteam_id)->first();
    $itemPurchaseData = iteamPurches::where('item_id',$request->iteam_id)->orderBy('id', 'DESC')->first();

    $recentDatas = RecentIteamModel::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->take(7)->pluck('id');
    $recentIteamsData =  RecentIteamModel::where('user_id', auth()->user()->id)->whereNotIn('id', $recentDatas)->delete();

    $iteamDetails = [];
    if (isset($iteamData)) {
      $recentModelData = new RecentIteamModel;
      $recentModelData->user_id = auth()->user()->id;
      $recentModelData->iteam_id = $request->iteam_id;
      $recentModelData->save();

      $company = CompanyModel::where('id', $iteamData->pharma_shop)->first();
      $iteamDetails['id'] = isset($iteamData->id) ? $iteamData->id : "";
      $iteamDetails['iteam_name'] = isset($iteamData->iteam_name) ? $iteamData->iteam_name : "";
      $iteamDetails['old_unit'] = isset($iteamData->old_unit) ? $iteamData->old_unit : "";
      $iteamDetails['unit'] = isset($itemPurchaseData->unit) ? $itemPurchaseData->unit : "";
      // $iteamDetails['unit'] = isset($iteamData->unit) ? $iteamData->unit : "";
      $iteamDetails['mrp'] = isset($itemPurchaseData->mrp) ? $itemPurchaseData->mrp : "";
      // $iteamDetails['mrp'] = isset($iteamData->mrp) ? $iteamData->mrp : "";
      $iteamDetails['packing_size'] = isset($iteamData->packing_size) ? $iteamData->packing_size : "";
      $iteamDetails['front_photo'] = isset($iteamData->front_photo) ? asset('/public/front_photo/' . $iteamData->front_photo) : "";
      $iteamDetails['back_photo'] = isset($iteamData->back_photo) ? asset('/public/back_photo/' . $iteamData->back_photo) : "";
      $iteamDetails['mrp_photo'] = isset($iteamData->mrp_photo) ? asset('/public/mrp_photo/' . $iteamData->mrp_photo) : "";
      $images = [];
      if (!empty($iteamData->front_photo)) {
        $images[] = asset('/public/front_photo/' . $iteamData->front_photo);
      }
      if (!empty($iteamData->back_photo)) {
        $images[] = asset('/public/back_photo/' . $iteamData->back_photo);
      }
      if (!empty($iteamData->mrp_photo)) {
        $images[] = asset('/public/mrp_photo/' . $iteamData->mrp_photo);
      }
      $iteamDetails['images'] = $images;
      $iteamDetails['manufacturer'] = isset($company->company_name) ? $company->company_name : "";
      $iteamDetails['is_preferred_pharmacy'] = empty(auth()->user()->your_chemist) ? false : true;
      $iteamDetails['preferred_chemist'] = [];

      if (empty(auth()->user()->your_chemist)) {
        $latitude = auth()->user()->latitude;
        $longitude = auth()->user()->longitude;
        $radius = 10; // 10 km
        $batchDeatils = BatchModel::where('item_id', $iteamData->id)->pluck('user_id')->toArray();

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

        $iteamDetails['preferred_chemist'] = [];
        foreach ($usersChemist as $key => $listUserDetails) {
          $iteamDetails['preferred_chemist'][$key]['id'] = isset($listUserDetails->id) ? $listUserDetails->id : "";
          $iteamDetails['preferred_chemist'][$key]['pharmacy_logo'] = isset($listUserDetails->pharmacy_logo) ? asset('/pharmacy_logo/' . $listUserDetails->pharmacy_logo) : "";
          $iteamDetails['preferred_chemist'][$key]['owner_name'] = isset($listUserDetails->owner_name) ? $listUserDetails->owner_name : "";
          $iteamDetails['preferred_chemist'][$key]['name'] = isset($listUserDetails->name) ? $listUserDetails->name : "";
          $iteamDetails['preferred_chemist'][$key]['address'] = isset($listUserDetails->address) ? $listUserDetails->address : "";
        }
      }
    }
    return $this->sendResponse($iteamDetails, 'Data Fetch Successfully');
  }

  public function patientDeleteAccount(Request $request)
  {
    $user_data = auth()->user();
    $patientDeleteUser = PatientsModel::where('id', $user_data->id)->first();
    if (isset($patientDeleteUser)) {

      $pationOrders = PatientsOrder::where('patient_id', $user_data->id)->get();
      if (isset($pationOrders)) {
        foreach ($pationOrders as $lists) {
          $lists->delete();
        }
      }

      $pationOrdersCart = AddCart::where('patient', $user_data->id)->get();
      if (isset($pationOrdersCart)) {
        foreach ($pationOrdersCart as $lists) {
          $lists->delete();
        }
      }

      $pationOrdersCartFmaliy = PatientsFamilyModel::where('patients_id', $user_data->id)->get();
      if (isset($pationOrdersCartFmaliy)) {
        foreach ($pationOrdersCartFmaliy as $listsFamliy) {
          $listsFamliy->delete();
        }
      }

      $pationOrdersAddress = PatientsAddress::where('patient_id', $user_data->id)->get();
      if (isset($pationOrdersAddress)) {
        foreach ($pationOrdersAddress as $listsAddress) {
          $listsAddress->delete();
        }
      }

      $tokenCheck = ApiToken::where('tokenable_id', $patientDeleteUser->id)->get();
      if (isset($tokenCheck)) {
        foreach ($tokenCheck as $listData) {
          $listData->delete();
        }
      }

      $patientDeleteUser->delete();
    }
    return $this->sendResponse([], 'Account Deleted Successfully.');
  }

  public function patientLogOut(Request $request)
  {
    $user_data = auth()->user();
    $patientDeleteUser = PatientsModel::where('id', $user_data->id)->first();

    PatientsDeviceToken::where('user_id', $user_data->id)->where('token_id', $request->token)->delete();
    // if(isset($patientDeleteUser))
    // {
    // $tokenCheck = ApiToken::where('tokenable_id', $patientDeleteUser->id)->get();
    // if(isset($tokenCheck))
    // {
    // foreach($tokenCheck as $listData)
    // {
    // $listData->delete();
    // }
    // }
    // }
    return $this->sendResponse([], 'Logout Successfully.');
  }
}
