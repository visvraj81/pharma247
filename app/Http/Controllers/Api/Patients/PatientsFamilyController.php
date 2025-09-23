<?php

namespace App\Http\Controllers\Api\Patients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientsFamilyModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use Illuminate\Support\Facades\Http;
use App\Models\OTP;
use App\Models\LogsModel;
use Illuminate\Support\Facades\Hash;
use App\Models\ApiToken;
use App\Models\User;
use App\Models\PatientFamilyRelation;
use App\Models\BloodGroup;

class PatientsFamilyController extends ResponseController
{
    //
    public function patientFamilyAdd(Request $request)
    {
       try {
         
             $validator = Validator::make($request->all(), [
                  'relation_name' => 'required',
                  'first_name' => 'required',
                  'last_name' => 'required',
                  'mobile_number' => 'required',
                  'blood_group' => 'required',
                  'date_of_birth' => 'required',
              ], [
                  'relation_name.required' => 'Enter Relation Name',
                  'first_name.required' => 'Enter First Number',
                  'last_name.required' => 'Enter Last Name',
                  'mobile_number.required' => 'Enter Mobile Number',
                  'blood_group.required' => 'Select Blood Group',
                  'date_of_birth.required'=>'Enter Date Of Birth'
              ]);

              if ($validator->fails()) {
                  $error = $validator->getMessageBag();
                  return $this->sendError($error->first());
              }
         
            $message = 'Patient Family Updated Successfully';
             $famliyStore = PatientsFamilyModel::where('id',$request->id)->first();
             if(empty($famliyStore))
             {
               $famliyStore = new PatientsFamilyModel; 
               $message = 'Patient Family Added Successfully';
             }
             $famliyStore->patients_id = auth()->user()->id;
             $famliyStore->relation_name = $request->relation_name;
             $famliyStore->first_name = $request->first_name;
             $famliyStore->last_name = $request->last_name;
             $famliyStore->mobile_number = $request->mobile_number;
             $famliyStore->blood_group = $request->blood_group;
             $famliyStore->date_of_birth = $request->date_of_birth;
              if (!empty($request->image)) {
                $image    = $request->image;
                $filename = time() . $image->getClientOriginalName();
                $image->move(public_path('image'), $filename);
                $famliyStore->images = $filename;
            }
             $famliyStore->save();
         
            return $this->sendResponse([],$message);

       } catch (\Exception $e) {
            Log::info("patients Famliy api" . $e->getMessage());
            return $e->getMessage();
        }
    }
  
  	public function patientFamilyRelationList()
    {
    	$patientFamilyRelationList = PatientFamilyRelation::get();

        $patientFamilyRelationListDetails = [];
        if (isset($patientFamilyRelationList)) {
          foreach ($patientFamilyRelationList as $key => $list) {
            $patientFamilyRelationListDetails[$key]['id'] = isset($list->id) ? $list->id : "";
            $patientFamilyRelationListDetails[$key]['name'] = isset($list->name) ? $list->name : "";
          }
        }
        return $this->sendResponse($patientFamilyRelationListDetails, 'Patient Family Relation Data Fetch Successfully.');
    }
  
  	public function bloodGroupList()
    {
    	$bloodGroupList = BloodGroup::get();

        $bloodGroupListDetails = [];
        if (isset($bloodGroupList)) {
          foreach ($bloodGroupList as $key => $list) {
            $bloodGroupListDetails[$key]['id'] = isset($list->id) ? $list->id : "";
            $bloodGroupListDetails[$key]['name'] = isset($list->name) ? $list->name : "";
          }
        }
        return $this->sendResponse($bloodGroupListDetails, 'Blood Group Data Fetch Successfully.');
    }
  
   public function patientFamilyList(Request $request)
   {
      $userData = auth()->user();
      $limit = 12;
      $patinetionName = PatientsFamilyModel::where('patients_id',auth()->user()->id);
      $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
      $offset = ($page - 1) * $limit;
      $patinetionName->offset($offset);
      $patinetionName = $patinetionName->orderBy('id', 'DESC')->limit($limit)->get();
     
       $patienstDetails = [];
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
       if(isset($patinetionName))
       {
             foreach($patinetionName as $key => $listData)
             {
               $patientRelationData = PatientFamilyRelation::where('id',$listData->relation_name)->first();
               $bloodGroupData = BloodGroup::where('id',$listData->blood_group)->first();
               
                $patienstDetails[$key]['id'] = isset($listData->id) ? (string) $listData->id : '';
               	$patienstDetails[$key]['relation_id'] = isset($listData->relation_name) ? $listData->relation_name :'';
                $patienstDetails[$key]['relation_name'] = isset($patientRelationData->name) ? $patientRelationData->name :'';
                $patienstDetails[$key]['first_name'] = isset($listData->first_name) ? $listData->first_name :'';
                $patienstDetails[$key]['last_name'] = isset($listData->last_name) ? $listData->last_name :'';
                $patienstDetails[$key]['mobile_number'] = isset($listData->mobile_number) ? $listData->mobile_number :'';
                $patienstDetails[$key]['blood_group_id'] = isset($listData->blood_group) ? $listData->blood_group : '';
                $patienstDetails[$key]['blood_group'] = isset($bloodGroupData->name) ? $bloodGroupData->name :'';
                $patienstDetails[$key]['date_of_birth'] = isset($listData->date_of_birth) ? $listData->date_of_birth :'';
                $patienstDetails[$key]['image'] = isset($listData->images) ? asset('public/image/'.$listData->images) :'';
             }
       }
       $patienstDetails = array_merge([$selfPatienstDetails], $patienstDetails);
     
       return $this->sendResponse($patienstDetails,'Patient Family Data Fetch Successfully.');
   }
  
   public function patientFamilyDelete(Request $request)
   {
          $patinetionName = PatientsFamilyModel::where('id',$request->id)->first();
         if(isset($patinetionName))
         {
             $patinetionName->delete();
         }

        return $this->sendResponse([],'Patient Family Deleted Successfully');
   }
}
