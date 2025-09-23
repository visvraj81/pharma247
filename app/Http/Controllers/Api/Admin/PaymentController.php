<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\PaymentModel;

class PaymentController extends ResponseController
{
    //this function use Payment
    public function paymentCreate(Request $request)
    {
           try{

            $validator = Validator::make($request->all(), [
                'payment_name' => 'required',
                // 'payment_image'=>'required'
              ], [
                'payment_name.required' => "Enter Payemnt Name",
                'payment_image.required'=>'Enter Payment Image'
              ]);
        
              if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
              }

              $paymentNew = new PaymentModel;
              $paymentNew->payment_method = $request->payment_name;
              if (!empty($request->payment_image)) {
                $dark_logo    = $request->payment_image;
                $filename = time() . $dark_logo->getClientOriginalName();
                $dark_logo->move(public_path('payment_image'), $filename);
                $paymentNew->icon = $filename;
            }
            $paymentNew->save();

            return $this->sendResponse('', 'Payemnt Added Successfully');

           } catch (\Exception $e) {
            Log::info("payment Method api" . $e->getMessage());
            return $e->getMessage();
            }
    } 

    //this function use payment list
    public function paymentList(Request $request)
    {
             try{

               $paymentMethod = PaymentModel::get();

               $paymentData = [];
               if(isset($paymentMethod))
               {
                   foreach($paymentMethod as $key => $list)
                   {
                        $paymentData[$key]['id'] = isset($list->id) ? $list->id :"";
                        $paymentData[$key]['payment_method'] = isset($list->payment_method) ? $list->payment_method :"";
                        $paymentData[$key]['icon'] = isset($list->icon) ? asset('/public/payment_image/'.$list->icon) :"";
                   }
               }
               return $this->sendResponse($paymentData, 'Payemnt Get Successfully');
            } catch (\Exception $e) {
            Log::info("payment Method List api" . $e->getMessage());
            return $e->getMessage();
            }
    }

    //this function payment Update
    public function paymentUpdate(Request $request)
    {
          try{

             $validator = Validator::make($request->all(), [
                'id'=>'required',
                'payment_name' => 'required'
              ], [
                'id.required'=>'Please Enter Id',
                'payment_name.required' => "Enter Payemnt Name"
              ]);
        
              if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
              }

             $paymentData = PaymentModel::find($request->id);
             $paymentData->payment_method = $request->payment_name;
             if (!empty($request->payment_image)) {
                $base64Image = $request->input('payment_image');
                $binaryImage = base64_decode($base64Image);
                $filename = 'image_' . time() . '.png';
                $path = public_path('payment_image/' . $filename);
                file_put_contents($path, $binaryImage);
                $paymentData->icon = $filename;
            }
             $paymentData->update();

            return $this->sendResponse('', 'Payemnt Update Successfully');
           } catch (\Exception $e) {
                Log::info("payment Method Update api" . $e->getMessage());
                return $e->getMessage();
            }
    } 

    //this function use payment delete
    public function paymentDelete(Request $request)
    {
          try{

             $validator = Validator::make($request->all(), [
                'id'=>'required',
              ], [
                'id.required'=>'Please Enter Id',
              ]);
        
              if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
              }

              $paymentData = PaymentModel::find($request->id);
              if(!empty($paymentData))
              {
                $paymentData->delete();
              }
              return $this->sendResponse('', 'Payemnt Deleted Successfully');
        } catch (\Exception $e) {
            Log::info("payment Method Delete api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use edit payment
    public function paymentEdit(Request $request)
    {
           try{

              $validator = Validator::make($request->all(), [
                'id'=>'required',
              ], [
                'id.required'=>'Please Enter Id',
              ]);
        
              if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
              }

             $paymentEdit = PaymentModel::find($request->id);
             $dataPayment = [];
             $dataPayment['id'] = isset($paymentEdit->id) ? $paymentEdit->id :"";
             $dataPayment['payment_method'] = isset($paymentEdit->payment_method) ? $paymentEdit->payment_method :"";
             $dataPayment['icon'] = isset($paymentEdit->icon) ? asset('/public/payment_image/'.$paymentEdit->icon) :"";

             return $this->sendResponse($dataPayment, 'Payemnt Updated Successfully');
             } catch (\Exception $e) {
            Log::info("payment Method Edit api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
