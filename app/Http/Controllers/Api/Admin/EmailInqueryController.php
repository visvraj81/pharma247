<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\EmailInquery;
use App\Mail\EmailSend;
use Illuminate\Support\Facades\Mail;

class EmailInqueryController extends ResponseController
{
   //this function use email inquery list
   public function emailInuqery(Request $request)
   {
      try {
         $emailInquery = EmailInquery::orderBy('id', 'DESC');
         if (isset($request->status)) {
            $emailInquery->where('replied', $request->status);
         }
         $emailInquery = $emailInquery->get();
         $arrayData = [];
         if (isset($emailInquery)) {
            foreach ($emailInquery as $key => $list) {
               if ($list->replied == '0') {
                  $replied = 'No';
               } else {
                  $replied = 'Yes';
               }
               $arrayData[$key]['id'] =  isset($list->id) ? $list->id : "";
               $arrayData[$key]['date_time'] =  isset($list->date_time) ? $list->date_time : "";
               $arrayData[$key]['name'] =  isset($list->name) ? $list->name : "";
               $arrayData[$key]['email'] =  isset($list->email) ? $list->email : "";
               $arrayData[$key]['message'] =  isset($list->message) ? $list->message : "";
               $arrayData[$key]['replied'] =  isset($replied) ? $replied : "";
            }
         }
         return $this->sendResponse($arrayData, 'Data Fetch Successfully');
      } catch (\Exception $e) {
         Log::info("Email inquery list api" . $e->getMessage());
         return $e->getMessage();
      }
   }

   //this function use email reply
   public function emailIReplay(Request $request)
   {
      try {

         $validator = Validator::make($request->all(), [
            'id' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'body' => 'required'
         ], [
            'id.required' => "Enter Id",
            'email.required' => 'Enter Email',
            'subject.required' => 'Enter Subject',
            'body.required' => 'Enter Body'
         ]);

         if ($validator->fails()) {
            $error = $validator->getMessageBag();
            return $this->sendError($error->first());
         }


         $emailInquery = EmailInquery::where('id', $request->id)->first();
         if (empty($emailInquery)) {
            return $this->sendError('Id Not Found');
         }
         $emailInquery->email = $request->email;
         $emailInquery->subject = $request->subject;
         $emailInquery->message = $request->body;
         $emailInquery->replied = '1';
         $emailInquery->save();

         $details = [
            'body' => $request->body,
         ];

         Mail::to( $request->email)->send(new \App\Mail\EmailSend($details));


         return $this->sendResponse('', 'Email Replied Successfully');
      } catch (\Exception $e) {
         Log::info("Email Replay inquery list api" . $e->getMessage());
         return $e->getMessage();
      }
   }
}
