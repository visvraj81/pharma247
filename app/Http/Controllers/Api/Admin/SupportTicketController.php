<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\ResponseController;
use App\Models\SupportTicket;

class SupportTicketController extends ResponseController
{
    //this function use support ticket
    public function addTicket(Request $request)
    {
            try{

                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required',
                    'title' => 'required',
                    'content' => 'required',
                ], [
                    'email.required' => 'Enter Email',
                    'name.required' => 'Enter Name',
                    'title.required' => 'Enter Title',
                    'content.required'=>'Enter Content',
                ]);
    
                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return $this->sendError($error->first());
                }

                $supportTicket = new SupportTicket;
                $supportTicket->email = $request->email;
                $supportTicket->name = $request->name;
                $supportTicket->title = $request->title;
                $supportTicket->content = $request->content;
                $supportTicket->status = '0';
                if (!empty($request->attachments)) {
                    $image    = $request->attachments;
                    $filename = time() . $image->getClientOriginalName();
                    $image->move(public_path('attachments'), $filename);
                    $supportTicket->attachments = $filename;
                }
                // if (!empty($request->attachments)) {
                //     $base64Image = $request->input('attachments');
                //     $binaryImage = base64_decode($base64Image);
                //     $filename = 'image_' . time() . '.png';
                //     $path = public_path('attachments/' . $filename);
                //     file_put_contents($path, $binaryImage);
                //     $supportTicket->attachments = $filename;
                // }
                $supportTicket->save();

                return $this->sendResponse('', 'Support Ticket Added Successfully');

           } catch (\Exception $e) {
            Log::info("support Ticket Added api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //thsi function use ticket list
    public function listTicket(Request $request)
    {
          try{
                $supportTikect = SupportTicket::get();
                $supportDetails = [];
                if(isset($supportTikect))
                {
                      foreach($supportTikect as $key => $list)
                      {
                        $status = null;
                        if($list->status == '0')
                        {
                            $status = 'Open';
                        }
                        else
                        {
                            $status = 'Close';
                        }
                        $supportDetails[$key]['id'] = isset($list->id) ? $list->id :"";
                        $supportDetails[$key]['name'] = isset($list->name) ? $list->name :"";
                        $supportDetails[$key]['email'] = isset($list->email) ? $list->email :"";
                        $supportDetails[$key]['title'] = isset($list->title) ? $list->title :"";
                        $supportDetails[$key]['content'] = isset($list->content) ? $list->content :"";
                        $supportDetails[$key]['status'] = isset($status) ?  $status :"";
                      }
                }
                return $this->sendResponse($supportDetails, 'Data Fetch Successfully');
  
          } catch (\Exception $e) {
            Log::info("support Ticket List api" . $e->getMessage());
            return $e->getMessage();
         }
    }

    //this function use delete 
    public function deleteTicket(Request $request)
    {
              try{

                $validator = Validator::make($request->all(), [
                    'id' => 'required'
                ], [
                    'id.required' => 'Enter Id'
                ]);
    
                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return $this->sendError($error->first());
                }

               $supportTicket = SupportTicket::where('id',$request->id)->first();
               if(isset($supportTicket))
               {
                 $supportTicket->delete();
               }
               return $this->sendResponse("", 'Support Ticket Deleted Successfully');

               } catch (\Exception $e) {
                Log::info("support Ticket List api" . $e->getMessage());
                return $e->getMessage();
            }
    }

    //this function use ticket support edit
    public function editTicket(Request $request)
    {
           try{
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => 'Enter Id'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $supportTicket = SupportTicket::where('id',$request->id)->first();

            $supportDetails = [];
            $supportDetails['id'] = isset($supportTicket->id) ? $supportTicket->id :"";
            $supportDetails['name'] = isset($supportTicket->name) ? $supportTicket->name :"";
            $supportDetails['email'] = isset($supportTicket->email) ? $supportTicket->email :"";
            $supportDetails['status'] = isset($supportTicket->status) ? $supportTicket->status :"";
            $supportDetails['title'] = isset($supportTicket->title) ? $supportTicket->title :"";
            $supportDetails['content'] = isset($supportTicket->content) ? $supportTicket->content :"";
            $supportDetails['attachments'] = isset($supportTicket->attachments) ? asset('/public/attachments/'.$supportTicket->attachments) :"";

            return $this->sendResponse($supportDetails, 'Data Fetch Successfully');
          } catch (\Exception $e) {
                Log::info("support Ticket Edit api" . $e->getMessage());
                return $e->getMessage();
            }
    }

    //thsi function use ticket update
    public function updateTicket(Request $request)
    {
                try{
                    
                    $validator = Validator::make($request->all(), [
                        'id'=>'required',
                        'name' => 'required',
                        'email' => 'required',
                        'title' => 'required',
                        'content' => 'required',
                    ], [
                        'id.required' => 'Enter Id',
                        'email.required' => 'Enter Email',
                        'name.required' => 'Enter Name',
                        'title.required' => 'Enter Title',
                        'content.required'=>'Enter Content',
                    ]);
        
                    if ($validator->fails()) {
                        $error = $validator->getMessageBag();
                        return $this->sendError($error->first());
                    }

                    $supportTicket = SupportTicket::find($request->id);
                    $supportTicket->email = $request->email;
                    $supportTicket->name = $request->name;
                    $supportTicket->title = $request->title;
                    $supportTicket->content = $request->content;
                    $supportTicket->status = $request->status;
                    // if (!empty($request->attachments)) {
                    //     $image    = $request->attachments;
                    //     $filename = time() . $image->getClientOriginalName();
                    //     $image->move(public_path('attachments'), $filename);
                    //     $supportTicket->attachments = $filename;
                    // }
                    if (!empty($request->attachments)) {
                        $base64Image = $request->input('attachments');
                        $binaryImage = base64_decode($base64Image);
                        $filename = 'image_' . time() . '.png';
                        $path = public_path('attachments/' . $filename);
                        file_put_contents($path, $binaryImage);
                        $supportTicket->attachments = $filename;
                    }
                    $supportTicket->update();

                return $this->sendResponse('', 'Support Ticket Updated Successfully');

                } catch (\Exception $e) {
                Log::info("support Ticket Update api" . $e->getMessage());
                return $e->getMessage();
            }
    }
}
