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
use App\Models\LogsModel;

class FrontRoleController extends ResponseController
{
    //
    public function roleCreate(Request $request)
    {
        try {

            $role = new FrontRole;
            $role->role = $request->role;
            $role->status = '1';
            $role->user_id = auth()->user()->id;
            $role->save();

            $roleData = $request->permissions;
            $newData = explode(",", $roleData);
            if (isset($newData)) {
                foreach ($newData as $listData) {
                    $roleData = new frontRolePermissions;
                    $roleData->role_id    =  $role->id;
                    $roleData->permissions_id = $listData;
                    $roleData->save();
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Role Added';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
            return $this->sendResponse([], 'Role Added Successfully');
        } catch (\Exception $e) {
            Log::info("Create role api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function roleList(Request $request)
    {
        try {

            $roleData  = FrontRole::where('user_id', auth()->user()->id)->get();

            $dataRole = [];
            if (isset($roleData)) {
                foreach ($roleData as $key => $list) {
                    $dataRole[$key]['id'] = isset($list->id) ? $list->id : "";
                    $dataRole[$key]['role'] = isset($list->role) ? $list->role : "";
                    $dataRole[$key]['status'] = isset($list->status) ? $list->status : "";
                }
            }
            return $this->sendResponse($dataRole, 'Role List Successfully');
        } catch (\Exception $e) {
            Log::info("role list api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function roleStatus(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Enter Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $roleData  = FrontRole::where('id', $request->id)->first();
            if (isset($roleData)) {
                if ($roleData->status == '1') {
                    $roleData->status = '0';
                } else {
                    $roleData->status = '1';
                }
                $roleData->update();
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Role Status';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
            return $this->sendResponse([], 'Role Status Successfully');
        } catch (\Exception $e) {
            Log::info("role status api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function roleEdit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Enter Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $role = FrontRole::where('id', $request->id)->first();
            $role->role = $request->role;
            $role->user_id = auth()->user()->id;
            $role->update();

            if (isset($request->permissions)) {
                $frontData = frontRolePermissions::where('role_id', $role->id)->get();
                if (isset($frontData)) {
                    foreach ($frontData as $listDatas) {
                        $listDatas->delete();
                    }
                }

                $roleData = $request->permissions;
                $newData = explode(",", $roleData);
                if (isset($newData)) {
                    foreach ($newData as $listData) {
                        $roleData = new frontRolePermissions;
                        $roleData->role_id    =  $role->id;
                        $roleData->permissions_id = $listData;
                        $roleData->save();
                    }
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Role Updated';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
            return $this->sendResponse([], 'Role Updated Successfully');
        } catch (\Exception $e) {
            Log::info("role edit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function roleView(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Enter Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $role = FrontRole::where('id', $request->id)->first();
            $listData = [];
            if (isset($role)) {
                $listData['id'] = $role->id ?? "";
                $listData['role'] = $role->role ?? "";

                $frontData = frontRolePermissions::where('role_id', $role->id)->pluck('permissions_id')->toArray();
                $permissionData = [];
                if (!empty($frontData)) {
                    foreach ($frontData as $permission) {
                        // Extract the key part before the last space
                        $key = substr($permission, 0, strrpos($permission, ' '));
                        if (!isset($permissionData[$key])) {
                            $permissionData[$key] = [];
                        }
                        $permissionData[$key][] = $permission;
                    }
                    $listData['permission'] = $permissionData;
                }
            }
            return $this->sendResponse($listData, 'Role Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("role view api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
