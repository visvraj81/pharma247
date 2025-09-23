<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\Transcations;

class TranscationsController extends ResponseController
{
    //this function use notification List
    public function transcationsList(Request $request)
    {
          try{
               $transctionData = Transcations::orderBy('id', 'DESC')->get();
               $dataList = [];
               if(isset($transctionData))
               {
                   foreach($transctionData as $key => $listData)
                   {
                            $dataList[$key]['id'] = isset($listData->id) ? $listData->id :'';
                            $dataList[$key]['date'] = isset($listData->date) ? $listData->date :'';
                            $dataList[$key]['pharma_name'] = isset($listData->getPharmaPlan->pharma_name) ? $listData->getPharmaPlan->pharma_name :'';
                            $dataList[$key]['transcation_id'] = isset($listData->transcation_id) ? $listData->transcation_id :'';
                            $dataList[$key]['next_payment_date'] = isset($listData->next_payment_date) ? $listData->next_payment_date :'';
                            $dataList[$key]['payment_method'] = isset($listData->payment_method) ? $listData->payment_method :'';
                            $dataList[$key]['amount'] = isset($listData->amount) ? $listData->amount :'';
                            $dataList[$key]['payment_type'] = isset($listData->payment_type) ? $listData->payment_type :"";
                            $dataList[$key]['agent_name'] = isset($listData->getPharmaPlan->getAgent->name) ? $listData->getPharmaPlan->getAgent->name :"";
                   }
               }
               return $this->sendResponse($dataList, 'Data Fetch Successfully');
           } catch (\Exception $e) {
            Log::info("Notification List api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
