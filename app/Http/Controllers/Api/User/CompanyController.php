<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\ResponseController;
use Illuminate\Support\Facades\Validator;
use App\Models\LogsModel;

class CompanyController extends ResponseController
{
    //this company table
    public function companyStore(Request $request)
    {
           try{

            $companyStore = new CompanyModel;
            $companyStore->company_name = $request->company_name;
            $companyStore->save();
            
              $userLogs = new LogsModel;
                    $userLogs->message = 'Company Added';
                    $userLogs->user_id = auth()->user()->id;
                    $userLogs->date_time = date('Y-m-d H:i a');
                    $userLogs->save();

            return $this->sendResponse('', 'Company Added Successfully.');

          } catch (\Exception $e) {
            Log::info("Create company store api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    public function agentPlan(Request $request)
    {
        dD($request->all());
    }

        public function checkEmail(Request $request)
        {
            $emailExists = User::where('email', $request->email)->exists(); // Adjust the model and field as necessary
            return response()->json(['exists' => $emailExists]);
        }

    public function companyList(Request $request)
    {
       try {
            $companyList = CompanyModel::orderBy('id', 'DESC');
         	if(!empty($request->name)) {
            	$companyList = $companyList->where('company_name','LIKE','%'.$request->name.'%');
            }
            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $companyList = $companyList->offset($offset)->limit($limit)->get();
         	$companyTotalCount = CompanyModel::count();
            
            $companyListArray = [];
            if (isset($companyList)) {
                foreach ($companyList as $key => $value) {
                    $companyListArray[$key]['id'] = isset($value->id) ? $value->id : '';
                    $companyListArray[$key]['count'] = count($companyList);
                    
                    $companyListArray[$key]['company_name'] = isset($value->company_name) ? $value->company_name : '';
                }
            }
         
         	$response = [
              'status' => 200,
              'count' => !empty($request->page) ? $companyList->count() : $companyTotalCount,
              'total_records' => $companyTotalCount,
              'data'   => $companyListArray,
              'message' => 'Company Data Fetch Successfully.',
            ];
            return response()->json($response, 200);
         	
            // return $this->sendResponse($companyListArray, 'Company Data Fetch Successfully.');
         } catch (\Exception $e) {
            Log::info("Create company list api" . $e->getMessage());
            return $e->getMessage();
        }
    }

     public function companyUpdate(Request $request)
     {
            try{
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'company_name' => 'required',
                ], [
                    'id.required' => 'Enter Id',
                    'company_name.required' => 'Enter Company Name'
                ]);
    
                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return $this->sendError($error->first());
                }
    
                $companyListArray = CompanyModel::find($request->id);
                $companyListArray->company_name = $request->company_name;
                $companyListArray->update();
                
                  $userLogs = new LogsModel;
                    $userLogs->message = 'Company Updated';
                    $userLogs->user_id = auth()->user()->id;
                    $userLogs->date_time = date('Y-m-d H:i a');
                    $userLogs->save();

                return $this->sendResponse($companyListArray, 'Company Updated Successfully.');
              } catch (\Exception $e) {
            Log::info("Create company update api" . $e->getMessage());
            return $e->getMessage();
        }
     }

     public function companyEdit(Request $request)
     {
        try
        {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => "Enter company Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $packageEdit = CompanyModel::where('id', $request->id)->first();

            if (empty($packageEdit)) {
                return $this->sendError('Data Not Found');
            }

            $packageEditData = [];
            $packageEditData['id'] = isset($packageEdit->id) ? $packageEdit->id : '';
            $packageEditData['name'] = isset($packageEdit->company_name) ? $packageEdit->company_name : '';

            return $this->sendResponse($packageEditData, 'Company Data Fetch Successfully.');
        }catch (\Exception $e) {
            Log::info("Delete unit api" . $e->getMessage());
            return $e->getMessage();
        }
     }

     public function companyDelete(Request $request)
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

        $packageDelete = CompanyModel::where('id', $request->id)->first();
        if(isset($packageDelete))
        {
            $packageDelete->delete();
            
              $userLogs = new LogsModel;
                    $userLogs->message = 'Company Deleted';
                    $userLogs->user_id = auth()->user()->id;
                    $userLogs->date_time = date('Y-m-d H:i a');
                    $userLogs->save();
        }
        return $this->sendResponse('', 'Company Deleted Successfully.');
     }
}
