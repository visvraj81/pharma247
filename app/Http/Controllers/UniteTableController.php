<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UniteTable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;

class UniteTableController extends ResponseController
{
    //
    public function uniteStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'package_id' => 'required',
            ], [
                'name.required' => 'Enter unit Name',
                'package_id.required' => 'Please Enter Package',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $package = new UniteTable;
            $package->package_id = $request->package_id;
            $package->name = $request->name;
            $package->save();

            return $this->sendResponse('', 'unit Added Successfully');
        } catch (\Exception $e) {
            Log::info("Create unit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use list package
    public function listUnite(Request $request)
    {
        try {
            $packageList = UniteTable::orderBy('id', 'DESC');
             $limit = 10;
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
            $packageList = $packageList->offset($offset)->limit($limit)->get();

            $packageListArray = [];
            if (isset($packageList)) {
                foreach ($packageList as $key => $value) {
                    $packageListArray[$key]['id'] = isset($value->id) ? $value->id : '';
                    $packageListArray[$key]['count'] = count($packageList);
                    $packageListArray[$key]['name'] = isset($value->name) ? $value->name : '';
                    $packageListArray[$key]['pakcgae'] = isset($value->getPakcgae->packging_name) ? $value->getPakcgae->packging_name : '';
                }
            }
            return $this->sendResponse($packageListArray, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("List unit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use edit package
    public function editUnite(Request $request)
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

            $packageEdit = UniteTable::where('id', $request->id)->first();

            if (empty($packageEdit)) {
                return $this->sendError('Data Not Found');
            }

            $packageEditData = [];
            $packageEditData['id'] = isset($packageEdit->id) ? $packageEdit->id : '';
            $packageEditData['name'] = isset($packageEdit->name) ? $packageEdit->name : '';
            $packageEditData['pakcgae'] = isset($packageEdit->getPakcgae->packging_name) ? $packageEdit->getPakcgae->packging_name : '';

            return $this->sendResponse($packageEditData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Edit unit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use update package
    public function updateUnite(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'package_id' => 'required',
            ], [
                'id.required' => 'Enter Item unit Id',
                'name.required' => 'Enter unit Name',
                'package_id.required' => 'Please Enter Pakcgae',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $packageUpdate = UniteTable::find($request->id);
            if(isset($packageUpdate))
            {
                $packageUpdate->package_id = $request->package_id;
                $packageUpdate->name = $request->name;
                $packageUpdate->update();
            }

            return $this->sendResponse('', 'unit Updated Successfully');
        } catch (\Exception $e) {
            Log::info("Update unit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use delete package
    public function deleteUnite(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Enter Package Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $packageDelete = UniteTable::where('id', $request->id)->first();
            if (isset($packageDelete)) {
                $packageDelete->delete();
            }
            return $this->sendResponse('', 'unit Deleted Successfully');
        } catch (\Exception $e) {
            Log::info("Delete unit api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
