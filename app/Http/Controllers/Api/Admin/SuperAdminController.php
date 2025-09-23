<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\ResponseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\AdminMail;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends ResponseController
{
    //this function use create super admin
    public function superAdmin(Request $request)
    {
        try {
            // $validator = Validator::make($request->all(), [
            //     'email' => 'required',
            //     'image' => 'required',
            //     'name' => 'required',
            //     'phone_number' => 'required',
            //     'password' => 'required',
            //     'status' => 'required',
            // ], [
            //     'email.required' => 'Enter Email',
            //     'image.required' => 'Select Profile Image',
            //     'name.required' => 'Enter Name',
            //     'phone_number.required' => 'Enter Phone Number',
            //     'password.required' => 'Enter Password',
            //     'status.required' => 'Select Status',
            // ]);

            // if ($validator->fails()) {
            //     $error = $validator->getMessageBag();
            //     return $this->sendError($error->first());
            // }

            // $userEmail = User::where('email', $request->email)->first();
            // if (!empty($userEmail)) {
            //     return $this->sendError('Email Already Exist');
            // }

            $superAdmin = new User;
            $superAdmin->name = $request->name;
            $superAdmin->email = $request->email;
            $superAdmin->password = Hash::make($request->password);
            $superAdmin->new_password = $request->password;
            $superAdmin->phone_number = $request->phone_number;
            $superAdmin->status = $request->status;
            $superAdmin->role = '0';
          
            if (!empty($request->image)) {
                $base64Image = $request->input('image');
                $binaryImage = base64_decode($base64Image);
                $filename = 'image_' . time() . '.png';
                $path = public_path('image/' . $filename);
                file_put_contents($path, $binaryImage);
                $superAdmin->image = $filename;
            }
            $superAdmin->save();
             $roles = json_decode($request->input('roles'));
            $superAdmin->assignRole($roles);

            $details = [
                'email' => $request->email,
                'password' => $request->password
            ];
           
            Mail::to($request->email)->send(new \App\Mail\AdminMail($details));
        

            return $this->sendResponse('', 'Super Admin Added Successfully');
        } catch (\Exception $e) {
            Log::info("create super admin api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    //this function use super admin list
    public function superAdminList(Request $request)
    {
        try {
            $superAdminList = User::orderBy('id', 'DESC')->where('role',"!=",'0')->whereNull('user_id')->get();

            $listData = [];
            if (isset($superAdminList)) {
                foreach ($superAdminList as $key => $list) {
                    $status = null;
                    if ($list->status == '1') {
                        $status = 'Enabled';
                    } else {
                        $status = 'Disabled';
                    }
                    $listData[$key]['id'] = isset($list->id) ? $list->id : '';
                    $listData[$key]['name'] = isset($list->name) ? $list->name : '';
                    $listData[$key]['email'] = isset($list->email) ? $list->email : '';
                    $listData[$key]['status'] = isset($status) ? $status : '';
                    $listData[$key]['image'] = isset($list->image) ? asset('/public/image/' . $list->image) : '';
                }
            }
            return $this->sendResponse($listData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("super admin list api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    //this function use delete super admin
    public function superAdminDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => "Enter Super Admin Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $superAdminDelete = User::where('id', $request->id)->first();

            if (isset($superAdminDelete)) {
                $superAdminDelete->delete();
            }
            return $this->sendResponse('', 'Super Admin Deleted Successfully');
        } catch (\Exception $e) {
            Log::info("super admin delete api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    //this function use edit super admin
    public function superAdminEdit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => "Enter Super Admin Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $superAdminEdit = User::where('id', $request->id)->first();

            if (empty($superAdminEdit)) {
                return $this->sendError('Data Not Found');
            }

            $listData = [];
            $status = null;
            if ($superAdminEdit->status == '1') {
                $status = 'Enabled';
            } else {
                $status = 'Disabled';
            }
            
            $listData['id'] = isset($superAdminEdit->id) ? $superAdminEdit->id : '';
            $listData['name'] = isset($superAdminEdit->name) ? $superAdminEdit->name : '';
            $listData['password'] = isset($superAdminEdit->new_password) ? $superAdminEdit->new_password : '';
            
            $listData['phone_number'] = isset($superAdminEdit->phone_number) ? $superAdminEdit->phone_number : '';
            $listData['email'] = isset($superAdminEdit->email) ? $superAdminEdit->email : '';
            $listData['address'] = isset($superAdminEdit->address) ? $superAdminEdit->address : '';
            $listData['status'] = isset($status) ? $status : '';
            $listData['admin_status'] = isset($superAdminEdit->status) ? $superAdminEdit->status : '';
            $listData['image'] = isset($superAdminEdit->image) ? asset('/public/image/' . $superAdminEdit->image) : '';
            return $this->sendResponse($listData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("super admin edit api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    //this function use update super admin
    public function superAdminUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'email' => 'required',
                // 'image' => 'required',
                'name' => 'required',
                'phone_number' => 'required',
                'status' => 'required',
            ], [
                'id.required' => 'Enter User Id',
                'email.required' => 'Enter Email',
                'image.required' => 'Select Profile Image',
                'name.required' => 'Enter Name',
                'phone_number.required' => 'Enter Phone Number',
                'status.required' => 'Select Status',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $superAdmin = User::where('id', $request->id)->first();
            if (empty($superAdmin)) {
                return $this->sendError('Data Not Found');
            }
            $superAdmin->name = $request->name;
            $superAdmin->email = $request->email;
            if (isset($request->password)) {
                $superAdmin->password = Hash::make($request->password);
                $superAdmin->new_password = $request->password;
                 
                $details = [
                    'email' => $request->email,
                    'password' => $request->password
                ];
            
                Mail::to($request->email)->send(new \App\Mail\AdminMail($details));
            }
            $superAdmin->phone_number = $request->phone_number;
            $superAdmin->status = $request->status;
            if (!empty($request->image)) {
                $base64Image = $request->input('image');
                $binaryImage = base64_decode($base64Image);
                $filename = 'image_' . time() . '.png';
                $path = public_path('image/' . $filename);
                file_put_contents($path, $binaryImage);
                $superAdmin->image = $filename;
            }
            $superAdmin->update();
            

            return $this->sendResponse('', 'Super Admin Updated Successfully');
        } catch (\Exception $e) {
            Log::info("super admin update api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
