<?php

namespace App\Http\Controllers\Api\Patients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientsAddress;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use Illuminate\Support\Facades\Http;
use App\Models\OTP;
use App\Models\LogsModel;
use Illuminate\Support\Facades\Hash;
use App\Models\ApiToken;
use App\Models\User;

class PatientsAddressController extends ResponseController
{
    public function patientAddressAdd(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'address' => 'required',
                'area_landmark' => 'required',
                'city' => 'required',
                'pincode' => 'required',
                'type' => 'required',
            ], [
                'address.required' => 'Enter Address',
                'area_landmark.required' => 'Enter Area Landmark',
                'city.required' => 'Enter City',
                'pincode.required' => 'Enter Pin Code',
                'type.required' => 'Enter Type',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $addressData = PatientsAddress::where('id', $request->id)->first();
            if(empty($addressData))
            {
              $addressData = new PatientsAddress;
            }
            $addressData->patient_id = auth()->user()->id;
            $addressData->address = $request->address;
            $addressData->area_landmark = $request->area_landmark;
            $addressData->city = $request->city;
            $addressData->pincode = $request->pincode;
            $addressData->type = $request->type;
            $addressData->save();

      		if($request->id) {
            	return $this->sendResponse([],'Patient Address Updated Successfully.');
            } else {
            	return $this->sendResponse([],'Patient Address Added Successfully.');
            }
    }

     public function patientAddressList(Request $request)
     {
          $limit = 12;
          $patientsData = PatientsAddress::where('patient_id',auth()->user()->id);
          $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
          $offset = ($page - 1) * $limit;
          $patientsData->offset($offset);
          $patientsData = $patientsData->orderBy('id', 'DESC')->limit($limit)->get();
           
          $listData = [];
          if(isset($patientsData))
          {
             foreach($patientsData as $key => $listDetails)
             {
                $type = null;
                if($listDetails->type == '0')
                {
                   $type = 'Home';
                }else{
                   $type = 'Work';
                }
                $listData[$key]['id'] = isset($listDetails->id) ? $listDetails->id :"";
                $listData[$key]['address'] = isset($listDetails->address) ? $listDetails->address :"";
                $listData[$key]['area_landmark'] = isset($listDetails->area_landmark) ? $listDetails->area_landmark :"";
                $listData[$key]['city'] = isset($listDetails->city) ? $listDetails->city :"";
                $listData[$key]['pincode'] = isset($listDetails->pincode) ? $listDetails->pincode :"";
                $listData[$key]['type'] = isset($type) ? $type : "";
             }
          }

         return $this->sendResponse($listData,'Patient Address Data Fetch Successfully.');
     }

     public function patientAddressDelete(Request $request)
     {
         $patientsData = PatientsAddress::where('id',$request->id)->first();
         if(isset($patientsData))
         {
           $patientsData->delete();
         }

         return $this->sendResponse([],'Patient Address Data Deleted Successfully.');
     }
}
