<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DrugGroup;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\ResponseController;
use Illuminate\Support\Facades\Validator;
use App\Models\LogsModel;
use App\Models\User;
use App\Models\IteamsModel;
use App\Models\BatchModel;

class DrugGroupController extends ResponseController
{
    // this function use drug group list
    public function drugList(Request $request)
    {
         try{
           $drugGroupList = DrugGroup::orderBy('id', 'DESC');
           if ($request->search) {
                $drugGroupList->where('name', 'like', '%' . $request->search . '%');
           }
           $limit = 10;
           $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
           $offset = ($page - 1) * $limit;
           $drugGroupList = $drugGroupList->offset($offset)->limit($limit)->get();
           $drugGroupTotalCount = DrugGroup::count();

            $drugListArray = [];
            if (isset($drugGroupList)) {
                foreach ($drugGroupList as $key => $value) {
                  	$drugGroupItemCount = IteamsModel::where('drug_group',$value->id)->count();
                  
                    $drugListArray[$key]['id'] = isset($value->id) ? $value->id : '';
                    $drugListArray[$key]['name'] = isset($value->name) ? $value->name : '';
                    // $drugListArray[$key]['count'] = (string)count($drugGroupList);
                  	$drugListArray[$key]['count'] = (string)$drugGroupItemCount;
                }
            }
           	$response = [
              'status' => 200,
              'count' => !empty($request->page) ? $drugGroupList->count() : $drugGroupTotalCount,
              'total_records' => $drugGroupTotalCount,
              'data'   => $drugListArray,
              'message' => 'Drug Group Data Fetch Successfully.',
            ];
            return response()->json($response, 200);
           
            // return $this->sendResponse($drugListArray, 'Data Fetch Successfully.');
         } catch (\Exception $e) {
            Log::info("Drug Group List Api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    public function logsActivity(Request $request)
    {
            $userid = auth()->user(); 
            $staffGetData = User::where('create_by',auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id',auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData,$ownerGet,$userId);
             
            $companyList = LogsModel::whereIn('user_id',$allUserId)->orderBy('id', 'DESC');
            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $companyList = $companyList->offset($offset)->limit($limit)->get();

            $companyListArray = [];
            if (isset($companyList)) {
                foreach ($companyList as $key => $value) {
                    $userName  = User::where('id',$value->user_id)->first();
                    $companyListArray[$key]['id'] = isset($value->id) ? $value->id : '';
                    $companyListArray[$key]['count'] = (string)count($companyList);
                    $companyListArray[$key]['message'] = isset($value->message) ? $value->message : '';
                    $companyListArray[$key]['user_name'] = isset($userName->name) ? $userName->name : '';
                    $companyListArray[$key]['date_time'] = isset($value->date_time) ? $value->date_time : '';
                }
            }
            return $this->sendResponse($companyListArray, 'Logs Activity Data Fetch Successfully.');
    }

    public function DrugGroupStore(Request $request)
    {
         try{
           //   $validator = Validator::make($request->all(), [
           //     'name' => 'required',
           //  ], [
           //     'name.required' => "Enter Name",
           //  ]);

           //  if ($validator->fails()) {
           //     $error = $validator->getMessageBag();
           //     return $this->sendError($error->first());
           //  }

           if(isset($request->name))
           {
              $drugStore = new DrugGroup;
              $drugStore->name = $request->name;
              $drugStore->save();
            
              $userLogs = new LogsModel;
              $userLogs->message = 'Drug Group Added';
              $userLogs->user_id = auth()->user()->id;
              $userLogs->date_time = date('Y-m-d H:i a');
              $userLogs->save();
           }

            return $this->sendResponse('', 'Drug Group Added Successfully.');
          } catch (\Exception $e) {
            Log::info("Drug Group Store Api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function drugGroupUpdate(Request $request)
     {
          try {
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'name' => 'required',
                ], [
                    'id.required' => 'Enter Id',
                    'name.required' => 'Enter Name'
                ]);
    
                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return $this->sendError($error->first());
                }
    
               $drugListArray = DrugGroup::find($request->id);
              
               if(isset($drugListArray))
               {
                    $drugListArray->name = $request->name;
                    $drugListArray->update();
                   
                    $userLogs = new LogsModel;
                    $userLogs->message = 'Drug Group Updated';
                    $userLogs->user_id = auth()->user()->id;
                    $userLogs->date_time = date('Y-m-d H:i a');
                    $userLogs->save();
                    return $this->sendResponse($drugListArray, 'Drug Group Updated Successfully.');
               } else{
                	return $this->sendResponse("", 'Drug Group Updated Successfully.');
               }
            } catch (\Exception $e) { 
            Log::info("Drug Group Update Api" . $e->getMessage());
            return $e->getMessage();
        }
     }

     public function drugGroupEdit(Request $request)
     {
        try
        {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => "Enter Drug Group Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $packageEdit = DrugGroup::where('id', $request->id)->first();

            if (empty($packageEdit)) {
                return $this->sendError('Data Not Found');
            }

            $packageEditData = [];
            $packageEditData['id'] = isset($packageEdit->id) ? $packageEdit->id : '';
            $packageEditData['name'] = isset($packageEdit->name) ? $packageEdit->name : '';

            return $this->sendResponse($packageEditData, 'Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("Drug Group Edit api" . $e->getMessage());
            return $e->getMessage();
        }
     }

     public function drugGroupDelete(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
            'id.required' => 'Enter Drug Group Id',
        ]);

        if ($validator->fails()) {
            $error = $validator->getMessageBag();
            return $this->sendError($error->first());
        }

        $packageDelete = DrugGroup::where('id', $request->id)->first();
        if(isset($packageDelete))
        {
             $packageDelete->delete();
        
             $userLogs = new LogsModel;
             $userLogs->message = 'Drug Group Deleted Successfully.';
             $userLogs->user_id = auth()->user()->id;
             $userLogs->date_time = date('Y-m-d H:i a');
             $userLogs->save();
        }
        
        return $this->sendResponse('', 'Drug Group Deleted Successfully.');
     }
  
  public function drugItem(Request $request)
  {
      try{
        // $iteamMatster = IteamsModel::whereNull('user_id')->orWhere('user_id',auth()->user()->id)->where('drug_group',$request->id)->get();
        $iteamMatster = IteamsModel::where('drug_group', $request->id)->get();
        // ->where('user_id', auth()->user()->id)
        if(empty($iteamMatster))
        {
            return $this->sendResponse("", 'Data Not Found');
        }
        
        $drugGroupData = DrugGroup::where('id', $request->id)->first();
        
        $drugGroupName = isset($drugGroupData) ? $drugGroupData->name : "";
        
        $iteamList = [];
        if(isset($iteamMatster))
        {
           foreach($iteamMatster as $key => $list)
           {
               $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
               $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
               $userId = array(auth()->user()->id);

               $allUserId = array_merge($staffGetData, $ownerGet, $userId);
               $totalStock = BatchModel::where('item_id', $list->id)->whereIn('user_id', $allUserId)->sum('total_qty');
               
               $iteamList[$key]['id'] = isset($list->id) ? $list->id : '';
               $iteamList[$key]['Item_name'] = isset($list->iteam_name) ? $list->iteam_name : '';
               $iteamList[$key]['company_name'] = isset($list->getPharma->company_name) ? $list->getPharma->company_name : '';
               $iteamList[$key]['stock'] = isset($totalStock) ? (string)$totalStock : '';
           }
        }
        
        $message = 'Drug Group Wise Item Data Fetch Successfully.';
        
        $response = [
            'status'=> 200,
          	'drug_group_name' => $drugGroupName,
            'data'    => $iteamList,
            'message' => $message,
        ];
        return response()->json($response, 200);
        
        // return $this->sendResponse($iteamList, 'Drug Group Wise Item Data Fetch Successfully.');
      } catch (\Exception $e) {
           Log::info("Drug Group Iteam api" . $e->getMessage());
           return $e->getMessage();
      }
   }
}
