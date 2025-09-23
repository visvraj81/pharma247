<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\LogsModel;

class SupportController extends Controller
{
    //this function use support data
    public function supportIndex(Request $request)
    {
        try {
            $url = url('/') . '/api/list-ticket';
            $response = Http::get($url);
            $data = $response->json();
            $detailsList = [];
            if (isset($data['data'])) {
                $detailsList = $data['data'];
            }
            return view('admin.support.index', compact('detailsList'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
  
    public function logsIndex(Request $request)
    {
       $logsData = LogsModel::orderBy('created_at', 'desc')->get();

       return view('admin.logs.index', compact('logsData'));
    }

    //this function use delete ticket
    public function supportDelete($id)
    {
        try {
            $url = url('/') . '/api/delete-ticket';
            $data = [
                'id' => $id,
            ];

            $response = Http::post($url, $data);

            $responseData = $response->json();

            return redirect()->back()->with('success', 'Support Ticket Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use edit ticket
      public function supportEdit($id)
      {
        
            try {
                $url = url('/') . '/api/edit-ticket';
                $data = [
                    'id' => $id,
                ];
    
                // Make the HTTP POST request
                $response = Http::post($url, $data);
    
                $responseData = $response->json();
                $editDetails = [];
                if (isset($responseData['data'])) {
                    $editDetails = $responseData['data'];
                }
                return view('admin.support.edit', compact('editDetails'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
      }

      //thsi function use update support ticket 
      public function supportUpdate(Request $request)
      {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'title' => 'required',
                'content' => 'required',
                 'status'=>'required'
            ], [
                'name.required'=>'Enter Name',
                'email.required'=>'Enter Email',
                'title.required'=>'Enter Title',
                'content.required'=>'Enter Content',
                 'status.required'=>'Enter Status'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return redirect()->back()->with('error', $error->first());
            }

            $filename = null;
            if(isset($request->attachments))
            {
                $image = $request->file('attachments');
    
                $superadminimage = file_get_contents($image->getRealPath());

                $filename = base64_encode($superadminimage);
            }

                $url = url('/') . '/api/update-ticket';
                $data = [
                    'id' => $request->id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'title' => $request->title,
                    'content' => $request->content,
                    'status' => $request->status,
                    'attachments' => $filename
                ];
            
            // Make the HTTP POST request
            $response = Http::post($url, $data);
            
            $responseData = $response->json();
            return redirect()->route('support.index')->with('success', 'Support Ticket Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
      }
}
