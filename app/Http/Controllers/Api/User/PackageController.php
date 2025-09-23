<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\Package;
use App\Models\UniteTable;
use App\Models\LogsModel;

class PackageController extends ResponseController
{
    //this function use create package
    public function createPackage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'packging_name' => 'required',
            ], [
                'packging_name.required' => 'Enter Package Name'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $package = new Package;
            $package->packging_name = $request->packging_name;
            if (!empty($request->package_image)) {
                $package_image = $request->package_image;
                $filename = time() . $package_image->getClientOriginalName();
                $package_image->move(public_path('package_image'), $filename);
                $package->package_image =  $filename;
            }
            $package->save();
            
            if(isset($request->unit))
            {
                 foreach($request->unit as $unit)
                 {
                    $uniteData = new UniteTable;
                    $uniteData->name = $unit;
                    $uniteData->package_id = $package->id;
                    $uniteData->save();
                 }
            }
                $userLogs = new LogsModel;
                $userLogs->message = 'Package Added';
                $userLogs->user_id = auth()->user()->id;
                $userLogs->date_time = date('Y-m-d H:i a');
                $userLogs->save();
            return $this->sendResponse('', 'Package Added Successfully');
        } catch (\Exception $e) {
            Log::info("Create Package api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use list package
    public function listPackage(Request $request)
    {
        try {
            $packageList = Package::orderBy('id', 'ASC')->get();
            //     $limit = 10;
            //     $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            //     $offset = ($page - 1) * $limit;
            // $packageList = $packageList->offset($offset)->limit($limit)->

            $packageListArray = [];
            if (isset($packageList)) {
                foreach ($packageList as $key => $value) {
                    $packageListArray[$key]['id'] = isset($value->id) ? $value->id : '';
                    $packageListArray[$key]['count'] = count($packageList);
                    $packageListArray[$key]['packging_name'] = isset($value->packging_name) ? $value->packging_name : '';
                    $packageListArray[$key]['package_image'] = isset($value->package_image) ? asset('/public/package_image/'.$value->package_image)  : '';
                     $unit = UniteTable::where('package_id',$value->id)->get();
                     $packageListArray[$key]['unit'] = [];
                     if(isset($unit))
                     {
                           foreach($unit as $l => $list)
                           {
                              $packageListArray[$key]['unit'][$l]['id'] = isset($list->id) ? $list->id : '';
                              $packageListArray[$key]['unit'][$l]['name'] = isset($list->name) ? $list->name : '';
                           }
                     }
                }
            }
            return $this->sendResponse($packageListArray, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("List Package api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use edit package
    public function editPackage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => "Enter Package Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $packageEdit = Package::where('id', $request->id)->first();

            if (empty($packageEdit)) {
                return $this->sendError('Data Not Found');
            }

            $uniteDetails = UniteTable::where('package_id',$packageEdit->id)->first();
            $packageEditData = [];
            $packageEditData['id'] = isset($packageEdit->id) ? $packageEdit->id : '';
            $packageEditData['packging_name'] = isset($packageEdit->packging_name) ? $packageEdit->packging_name : '';
            $packageEditData['unit'] = isset($packageEdit->unit) ? $packageEdit->unit : '';
            $packageEditData['package_image'] = isset($packageEdit->package_image) ? asset('/public/package_image/'.$packageEdit->package_image)  : '';

            return $this->sendResponse($packageEditData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Edit Package api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use update package
    public function updatePackage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'packging_name' => 'required',
            ], [
                'id.required' => 'Enter Item Package Id',
                'packging_name.required' => 'Enter Package Name'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $packageUpdate = Package::find($request->id);
            $packageUpdate->packging_name = $request->packging_name;
            if (!empty($request->package_image)) {
                $package_image = $request->package_image;
                $filename = time() . $package_image->getClientOriginalName();
                $package_image->move(public_path('package_image'), $filename);
                $packageUpdate->package_image = $filename;
            }
            $packageUpdate->update();
             if(isset($request->unit))
             {
                $uniteDetails = UniteTable::where('package_id',$packageUpdate->id)->get();
               
                if(isset($uniteDetails))
                {
                    foreach($uniteDetails as $list)
                    {
                      $list->delete();
                        
                    }
                }
                 
                if(isset($request->unit))
                {
                     foreach($request->unit as $unit)
                     {
                        $uniteData = new UniteTable;
                        $uniteData->name = $unit;
                        $uniteData->package_id = $packageUpdate->id;
                        $uniteData->save();
                     }
                }
             }
             
               $userLogs = new LogsModel;
                $userLogs->message = 'Package Updated';
                $userLogs->user_id = auth()->user()->id;
                $userLogs->date_time = date('Y-m-d H:i a');
                $userLogs->save();
            return $this->sendResponse('', 'Package Updated Successfully');
        } catch (\Exception $e) {
            Log::info("Update Package api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use delete package
    public function deletePackage(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Enter Package Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $packageDelete = Package::where('id', $request->id)->first();
            if(isset($packageDelete))
            {
                $packageDelete->delete();
                 $userLogs = new LogsModel;
                $userLogs->message = 'Package Deleted';
                $userLogs->user_id = auth()->user()->id;
                $userLogs->date_time = date('Y-m-d H:i a');
                $userLogs->save();
            }
            return $this->sendResponse('', 'Package Deleted Successfully');
        }catch (\Exception $e) {
            Log::info("Delete Package api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
