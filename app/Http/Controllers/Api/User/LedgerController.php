<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;

class LedgerController extends ResponseController
{
    //this function use leader list
    public function ledgerlist(Request $request)
    {
       try{

            $validator = Validator::make($request->all(), [
                'customer_id' => 'required'
            ], [
                'customer_id.required' => "Enter Customer Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $ledgerData = LedgerModel::where('owner_id',$request->customer_id)->get();

            $ledgerDetails = [];
            if(isset($ledgerData))
            {
                   foreach($ledgerData as $key => $list)
                   {
                        $ledgerDetails[$key]['id'] = isset($list->id) ? $list->id :"";
                        $ledgerDetails[$key]['entry_date'] = isset($list->entry_date) ? $list->entry_date :"";          
                        $ledgerDetails[$key]['transction'] = isset($list->transction) ? $list->transction :"";          
                        $ledgerDetails[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no :"";          
                        $ledgerDetails[$key]['credit'] = isset($list->credit) ? $list->credit :"";          
                        $ledgerDetails[$key]['debit'] = isset($list->debit) ? $list->debit :"";          
                        $ledgerDetails[$key]['balance'] = isset($list->balance) ? $list->balance :"";          
                   }
            }

            return $this->sendResponse( $ledgerDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Create Item Category api" . $e->getMessage());
            return $e->getMessage();
        }
    }


    //this function use purches ledger
    public function purchesLedger(Request $request)
    {
             try{


                $validator = Validator::make($request->all(), [
                    'distributor_id' => 'required'
                ], [
                    'distributor_id.required' => "Enter distributor Id",
                ]);
    
                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return $this->sendError($error->first());
                }

                $ledgerData = LedgerModel::where('owner_id',$request->distributor_id)->get();

                $dataDetails = [];
                if(isset($ledgerData))
                {
                    foreach($ledgerData as $key => $list)
                    {
                        $dataDetails[$key]['id'] = isset($list->id) ? $list->id :"";
                        $dataDetails[$key]['entry_date'] = isset($list->entry_date) ? $list->entry_date :"";          
                        $dataDetails[$key]['transction'] = isset($list->transction) ? $list->transction :"";          
                        $dataDetails[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no :"";          
                        $dataDetails[$key]['credit'] = isset($list->credit) ? $list->credit :"";          
                        $dataDetails[$key]['debit'] = isset($list->debit) ? $list->debit :"";          
                        $dataDetails[$key]['balance'] = isset($list->balance) ? $list->balance :"";       
                    }
                }
                return $this->sendResponse( $dataDetails, 'Data Fetch Successfully');
              } catch (\Exception $e) {
                Log::info("Purches Legder Api" . $e->getMessage());
                return $e->getMessage();
            }
    } 
}
