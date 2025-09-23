<?php

namespace App\Http\Controllers\Api\Patients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientsModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use Illuminate\Support\Facades\Http;
use App\Models\OTP;
use App\Models\LogsModel;
use Illuminate\Support\Facades\Hash;
use App\Models\ApiToken;
use App\Models\User;
use App\Models\BloodGroup;
use App\Models\CustomerModel;
use App\Models\SalesModel;
use Carbon\Carbon;

class PatientsEditProfileController extends ResponseController
{
    public function patientEditProfile(Request $request)
    {
       try {
            $validator = Validator::make($request->all(), [
                  'first_name' => 'required',
                  'last_name' => 'required',
                  'mobile_number' => 'required',
                  'date_of_birth' => 'required',
                  'gender' => 'required',
                  'blood_group' => 'required',
              ], [
                  'first_name.required' => 'Enter First Name',
                  'last_name.required' => 'Enter Last Name',
                  'mobile_number.required' => 'Enter Mobile Number',
                  'date_of_birth.required' => 'Enter Date Of Birth',
                  'gender.required' => 'Select Gender',
                  'blood_group.required' => 'Select Blood Group',
              ]);

              if ($validator->fails()) {
                  $error = $validator->getMessageBag();
                  return $this->sendError($error->first());
              }
         
               $patienstNew = PatientsModel::where('id',auth()->user()->id)->first();
               if(isset($patienstNew))
               {
                    $patienstNew->first_name = $request->first_name;
                    $patienstNew->last_name = $request->last_name;
                    $patienstNew->mobile_number = $request->mobile_number;
                    if (!empty($request->image)) {
                        $image    = $request->image;
                        $filename = time() . $image->getClientOriginalName();
                        $image->move(public_path('image'), $filename);
                        $patienstNew->profile_image = $filename;
                    }
                    $patienstNew->date_of_birth = $request->date_of_birth;
                    $patienstNew->gender = $request->gender;
                    $patienstNew->blood_group = $request->blood_group;
                    $patienstNew->save();
               }
         
               $dataDetails = [];
               $dataDetails['id'] = isset($patienstNew->id) ?$patienstNew->id :"";
               $dataDetails['first_name'] = isset($patienstNew->first_name) ?$patienstNew->first_name :"";
               $dataDetails['last_name'] = isset($patienstNew->last_name) ?$patienstNew->last_name :"";
               $dataDetails['mobile_number'] = isset($patienstNew->mobile_number) ?$patienstNew->mobile_number :"";
               $dataDetails['date_of_birth'] = isset($patienstNew->date_of_birth) ?$patienstNew->date_of_birth :"";
               $dataDetails['gender'] = isset($patienstNew->gender) ?$patienstNew->gender :"";    
               $dataDetails['blood_group'] = isset($patienstNew->blood_group) ?$patienstNew->blood_group :"";
               $dataDetails['image'] = isset($patienstNew->profile_image) ? asset('public/image/'.$patienstNew->profile_image) :"";

               return $this->sendResponse($dataDetails,'Patient Profile Updated Successfully.');
          } catch (\Exception $e) {
            Log::info("Register api" . $e->getMessage());
            return $e->getMessage();
        }
    }
  
    public function patientMyProfile(Request $request)
    {
      try {
        $patienstNew = PatientsModel::where('id',auth()->user()->id)->first();
        
        $dataDetails = [];
        if(isset($patienstNew)) {
          	if($patienstNew->gender == '0') {
              $gender = 'Male';
            } elseif($patienstNew->gender == '1'){
              $gender = 'Female';
            } else {
              $gender = '';
            }
            $bloodGroupData = BloodGroup::where('id',$patienstNew->blood_group)->first();
          
            $dataDetails['id'] = isset($patienstNew->id) ?$patienstNew->id :"";
            $dataDetails['first_name'] = isset($patienstNew->first_name) ?$patienstNew->first_name :"";
            $dataDetails['last_name'] = isset($patienstNew->last_name) ?$patienstNew->last_name :"";
            $dataDetails['mobile_number'] = isset($patienstNew->mobile_number) ?$patienstNew->mobile_number :"";
            $dataDetails['date_of_birth'] = isset($patienstNew->date_of_birth) ?$patienstNew->date_of_birth :"";
            $dataDetails['gender'] = isset($gender) ? $gender : "";
          	$dataDetails['blood_group_id'] = isset($patienstNew->blood_group) ? $patienstNew->blood_group : "";
            $dataDetails['blood_group'] = isset($bloodGroupData->name) ? $bloodGroupData->name : "";
            $dataDetails['image'] = isset($patienstNew->profile_image) ? asset('public/image/'.$patienstNew->profile_image) :"";
          
            $userChemist = User::where('id', $patienstNew->your_chemist)->first();
          
            $dataDetails['chemist_name'] = isset($userChemist->name) ?$userChemist->name :"";
            $dataDetails['chemist_address'] = isset($userChemist->address) ?$userChemist->address :"";
            $dataDetails['chemist_phone_number'] = isset($userChemist->phone_number) ?$userChemist->phone_number :"";
        }
        return $this->sendResponse($dataDetails,'Patient Profile Updated Successfully.');
      } catch (\Exception $e) {
            Log::info("my Profile api" . $e->getMessage());
            return $e->getMessage();
       }
    }
  
    public function patientChangePassword(Request $request)
    {
        try {
             $validator = Validator::make($request->all(), [
                   'current_password' => 'required',
                   'new_password' => 'required|confirmed', // Ensures new_password_confirmation exists
                   'new_password_confirmation' => 'required' // Explicitly making it required
              ]);
           
              if ($validator->fails()) {
                  $error = $validator->getMessageBag();
                  return $this->sendError($error->first());
              }

            $user = PatientsModel::where('id',auth()->user()->id)->first();

            // Check if current password is correct
            if (!Hash::check($request->current_password, $user->password)) {
               return $this->sendError('Current password is incorrect');
            }

            // Update password
            $user->password = Hash::make($request->new_password);
            $user->save();
          
          	return $this->sendResponse([], 'Password Updated Successfully.');
          
         } catch (\Exception $e) {
            Log::info("my Patient Change password api" . $e->getMessage());
            return $e->getMessage();
        }
    }
  
  	public function patientPharmaCoinList()
    {
    	try {
          	$userId = auth()->user()->id;
          	$patientPhoneNumber = auth()->user()->mobile_number;
          
          	$customerIds = CustomerModel::where('patient_id',$userId)->pluck('id')->toArray();
          	$customerPharmacyIds = CustomerModel::where('patient_id',$userId)->pluck('user_id')->toArray();
          	$pharmacyList = User::whereIn('id',$customerPharmacyIds)->get();
          
          	$pharmacyDetailsList = [];

            if (!empty($pharmacyList)) {
                foreach ($pharmacyList as $key => $list) {
                    $customerDetails = CustomerModel::whereIn('id',$customerIds)
                        ->where('user_id',$list->id)
                        ->first();

                    $saleDataList = SalesModel::where('customer_id', $customerDetails->id)
                        ->where('user_id', $list->id)
                        ->where(function($q) {
                            $q->where('roylti_point', '>', 0)
                              ->orWhere('today_loylti_point', '>', 0);
                        })->orderBy('id','DESC')->get();

                    $pointTotal = SalesModel::where('customer_id',$customerDetails->id)
                        ->where('user_id',$list->id)
                        ->sum('today_loylti_point');

                    $userPointTotal = SalesModel::where('customer_id',$customerDetails->id)
                        ->where('user_id',$list->id)
                        ->sum('roylti_point');

                    $total = $pointTotal - $userPointTotal;

                    $pharmacyDetailsList[$key]['id'] = $list->id ?? "";
                    $pharmacyDetailsList[$key]['pharmacy_name'] = $list->name ?? "";
                    $pharmacyDetailsList[$key]['title'] = ($list->name ?? "") . ' - Transaction History';
                    $pharmacyDetailsList[$key]['total'] = (string)$total ?? "0";

                    $pharmacyDetailsList[$key]['point_details'] = [];
                    $rowNewKey = 0;

                    if ($saleDataList) {
                        foreach ($saleDataList as $new_list) {

                            // Earned points data get
                            if ($new_list->today_loylti_point > 0) {
                                $pharmacyDetailsList[$key]['point_details'][$rowNewKey]['id'] = $new_list->id ?? "";
                                $pharmacyDetailsList[$key]['point_details'][$rowNewKey]['date'] = !empty($new_list->bill_date) ? Carbon::parse($new_list->bill_date)->format('Y-m-d') : "";
                                $pharmacyDetailsList[$key]['point_details'][$rowNewKey]['title'] = "Loyalty Points Earned";
                                $pharmacyDetailsList[$key]['point_details'][$rowNewKey]['type'] = 0;
                                $pharmacyDetailsList[$key]['point_details'][$rowNewKey]['point'] = $new_list->today_loylti_point ?? "";
                                $rowNewKey++;
                            }

                            // Redeemed points data get
                            if ($new_list->roylti_point > 0) {
                                $pharmacyDetailsList[$key]['point_details'][$rowNewKey]['id'] = $new_list->id ?? "";
                                $pharmacyDetailsList[$key]['point_details'][$rowNewKey]['date'] = !empty($new_list->bill_date) ? Carbon::parse($new_list->bill_date)->format('Y-m-d') : "";
                                $pharmacyDetailsList[$key]['point_details'][$rowNewKey]['title'] = "Loyalty Points Redeemed";
                                $pharmacyDetailsList[$key]['point_details'][$rowNewKey]['type'] = 1;
                                $pharmacyDetailsList[$key]['point_details'][$rowNewKey]['point'] = $new_list->roylti_point ?? "";
                                $rowNewKey++;
                            }
                        }
                    }
                }
            }
          
          	return $this->sendResponse($pharmacyDetailsList, 'Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("my Patient Change password api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}