<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FrontRole;
use App\Models\FrontPermissions;
use App\Models\frontRolePermissions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\User;
use App\Models\StaffModel;
use Illuminate\Support\Facades\Hash;

class ManageStaffController extends ResponseController
{
    //
     public function mangeStaff(Request $request){
         try{

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'mobile_no' => 'required',
                'assgin_role' => 'required',
                'email_id' => 'required',
                'assgin_role'=>'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ], [
                'name.required'=>'Please Enter Name',
                'mobile_no.required'=>'Please Enter Mobile Number',
                'assgin_role.required'=>'Please Enter Assgin Role',
                'email_id.required'=>'Please Enter Email',
                'assgin_role.required'=>'Please Enter Assgin Role',
                'password.required'=>'Please Enter Password',
                'password_confirmation.required'=>'Please Enter Password Confirmation',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $emailCheck = User::where('email', $request->email_id)->first();
            if (!empty($emailCheck)) {
                return $this->sendError('Email Already Exist');
            }

            $emailCheck = User::where('phone_number', $request->mobile_no)->first();
            if (!empty($emailCheck)) {
                return $this->sendError('Mobile Number Already Exist');
            }

            $userStaff = new User;
            $userStaff->name = $request->name;
            $userStaff->create_by = auth()->user()->id;
            $userStaff->phone_number = $request->mobile_no;
            $userStaff->email = $request->email_id;
            $userStaff->assgin_role = $request->assgin_role;
            $userStaff->status = '1';
            $userStaff->password = Hash::make($request->password);
            $userStaff->new_password = $request->password;
            $userStaff->save();

            return $this->sendResponse( [], 'Staff Added Successfully');

          } catch (\Exception $e) {
            Log::info("Manage Staff api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function managelist(Request $request)
    {
            try{

               $staffData = User::where('create_by',auth()->user()->id)->get();

               $dataStaff = [];
               if(isset( $staffData))
               {
                      foreach($staffData as $key => $listData)
                      {
                        $role = FrontRole::where('id',$listData->assgin_role)->first();
                        $dataStaff[$key]['id'] = isset($listData->id) ? $listData->id :"";
                        $dataStaff[$key]['name'] = isset($listData->name) ? $listData->name :"";
                        $dataStaff[$key]['mobile_number'] = isset($listData->phone_number) ? $listData->phone_number :"";
                        $dataStaff[$key]['status'] = isset($listData->status) ? $listData->status :"";
                        
                        $dataStaff[$key]['password'] = isset($listData->new_password) ? $listData->new_password :"";
                        $dataStaff[$key]['role_name'] = isset($role->role) ? $role->role :"";
                        $dataStaff[$key]['email_id'] = isset($listData->email) ? $listData->email :"";
                        $dataStaff[$key]['role_id'] = isset($listData->assgin_role) ? $listData->assgin_role :"";
                        $dataStaff[$key]['created_at'] = date("d-m-Y", strtotime($listData->created_at));
                      }
               }
               return $this->sendResponse( $dataStaff, 'Data Fetch Successfully');
           } catch (\Exception $e) {
            Log::info("Manage Staff List api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function manageEdit(Request $request)
    {
           try{

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required'=>'Please Enter Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $staffData = User::where('id',$request->id)->first();
        
            $dataStaff = [];
            if(isset($staffData))
            {
                $role = FrontRole::where('id',$staffData->assgin_role)->first();
                $dataStaff['id'] = isset($staffData->id) ? $staffData->id :"";
                $dataStaff['name'] = isset($staffData->name) ? $staffData->name :"";
                $dataStaff['mobile_number'] = isset($staffData->phone_number) ? $staffData->phone_number :"";
                $dataStaff['email_id'] = isset($staffData->email) ? $staffData->email :"";
                $dataStaff['role_name'] = isset($role->role) ? $role->role :"";
                $dataStaff['created_at'] = date("d-m-Y", strtotime($staffData->created_at));
            }

            return $this->sendResponse( $dataStaff, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Manage Staff List api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    public function statusChange(Request $request)
    {
         try{
             
            $userData = User::where('id',$request->id)->first();
            if(isset( $userData))
            {
                if( $userData->status == '1')
                {
                    $userData->status = '0';
                }else{
                    $userData->status = '1';
                }
                $userData->update();
            }
             return $this->sendResponse( [], 'Status Change Successfully');
         } catch (\Exception $e) {
            Log::info("Manage Staff Status Change api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function manageUpdate(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'mobile_no' => 'required',
                'assgin_role' => 'required',
                'email_id' => 'required',
                'assgin_role'=>'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ], [
                'id.required'=>'Please Enter Id',
                'name.required'=>'Please Enter Name',
                'mobile_no.required'=>'Please Enter Mobile Number',
                'assgin_role.required'=>'Please Enter Assgin Role',
                'email_id.required'=>'Please Enter Email',
                'assgin_role.required'=>'Please Enter Assgin Role',
                'password.required'=>'Please Enter Password',
                'password_confirmation.required'=>'Please Enter Password Confirmation',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $userStaff = User::where('id',$request->id)->first();
          
            if(isset($userStaff))
            {
                $userStaff->name = $request->name;
                $userStaff->create_by = auth()->user()->id;
                $userStaff->phone_number = $request->mobile_no;
                $userStaff->email = $request->email_id;
                $userStaff->assgin_role = $request->assgin_role;
                $userStaff->password = Hash::make($request->password);
                $userStaff->new_password = $request->password;
                $userStaff->update();
            }

            return $this->sendResponse( [], 'Staff Updated Successfully');

          } catch (\Exception $e) {
            Log::info("Manage Staff api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
