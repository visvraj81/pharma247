<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\OfflineRequestsModel;

class OfflineRequestController extends ResponseController
{
  //this function use offline request list get
  public function offlineRequest(Request $requests)
  {
    
    try {

      $offlineRequest =  OfflineRequestsModel::orderBy('id', 'DESC');
      if (isset($requests->status)) {
        $offlineRequest->where('status', $requests->status);
      }
      $offlineRequest = $offlineRequest->get();

      $listDetails = [];
      if (isset($offlineRequest)) {
        foreach ($offlineRequest as $key => $listData) {
          $status = [];
        //   if ($listData->status == '0') {
        //     $status = 'In Progress';
        //   } elseif ($listData->status == '1') {
        //     $status = 'Complete';
        //   } else {
        //     $status = 'Rejected';
        //   }
             $status = $listData->status;
          $listDetails[$key]['id'] = isset($listData->id) ? $listData->id : "";
          $listDetails[$key]['pharma'] = isset($listData->getPharma->pharma_name) ? $listData->getPharma->pharma_name : "";
          $listDetails[$key]['submitted'] = isset($listData->getUser->name) ? $listData->getUser->name : "";
          $listDetails[$key]['subscription_plan'] = isset($listData->getPlan->name) ? $listData->getPlan->name : "";
          $listDetails[$key]['plan_type'] = isset($listData->plan_type) ? $listData->plan_type : "";
          $listDetails[$key]['payment_method'] = isset($listData->payment_method) ? $listData->payment_method : "";
          $listDetails[$key]['submitted_on'] = isset($listData->submitted_on) ? $listData->submitted_on : "";
          $listDetails[$key]['reasone'] = isset($listData->reason) ? $listData->reason : "";
          $listDetails[$key]['status'] = isset($status) ? $status : "";
        }
      }
      return $this->sendResponse($listDetails, 'Data Fetch Successfully');
    } catch (\Exception $e) {
      Log::info("offline Request list api" . $e->getMessage());
      return $e->getMessage();
    }
  }

  //this function is use offline request delete
  public function offlineRequestDelete(Request $request)
  {
    try {
      // dd('okay');
      $validator = Validator::make($request->all(), [
        'id' => 'required'
      ], [
        'id.required' => "Enter Offline Request Id",
      ]);

      if ($validator->fails()) {
        $error = $validator->getMessageBag();
        return $this->sendError($error->first());
      }

      $offlineRequestDelete = OfflineRequestsModel::where('id',$request->id)->first();
      if(isset($offlineRequestDelete))
      {
        $offlineRequestDelete->delete();
      }
      return $this->sendResponse('', 'Offline Request Deleted Successfully');

    } catch (\Exception $e) {
      Log::info("offline Request list api" . $e->getMessage());
      return $e->getMessage();
    }
  }

  //this function use reasone api
  public function offlineReasone(Request $request)
  {
         try{

                $validator = Validator::make($request->all(), [
                  'id' => 'required',
                  'status'=>'required'
                ], [
                  'id.required' => "Enter Offline Request Id",
                  'status.required' => "Select Status",
                ]);
          
                if ($validator->fails()) {
                  $error = $validator->getMessageBag();
                  return $this->sendError($error->first());
                }
    
                $offlineRequestDelete = OfflineRequestsModel::where('id',$request->id)->first();
                if(!empty( $offlineRequestDelete))
                {
                  $offlineRequestDelete->status = $request->status;
                  $offlineRequestDelete->reason = $request->reason;
                  $offlineRequestDelete->update();
                }
                return $this->sendResponse('', 'Status Updated Successfully');
           } catch (\Exception $e) {
              Log::info("offline Reasone list api" . $e->getMessage());
              return $e->getMessage();
            }
  }
}
