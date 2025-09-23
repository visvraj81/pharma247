<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Setting;

class ProfileController extends Controller
{
    //this function profile index page
    public function profileIndex($id)
    {
        try {
            // $url = url('/') . '/api/super-admin-edit';
            // $data = [
            //     'id' => $id,
            // ];

            // // Make the HTTP POST request
            // $response = Http::post($url, $data);

            // $responseData = $response->json();
            $superAdminEdit = User::where('id', $id)->first();
            $editDetails = [];

            if (empty($superAdminEdit)) {
                return $this->sendError('Data Not Found');
            }

            $editDetails = [];
            $status = null;
            if ($superAdminEdit->status == '1') {
                $status = 'Enabled';
            } else {
                $status = 'Disabled';
            }

            $editDetails['id'] = isset($superAdminEdit->id) ? $superAdminEdit->id : '';
            $editDetails['name'] = isset($superAdminEdit->name) ? $superAdminEdit->name : '';
            $editDetails['password'] = isset($superAdminEdit->new_password) ? $superAdminEdit->new_password : '';
            $editDetails['phone_number'] = isset($superAdminEdit->phone_number) ? $superAdminEdit->phone_number : '';
            $editDetails['email'] = isset($superAdminEdit->email) ? $superAdminEdit->email : '';
            $editDetails['address'] = isset($superAdminEdit->address) ? $superAdminEdit->address : '';
            $editDetails['status'] = isset($status) ? $status : '';
            $editDetails['admin_status'] = isset($superAdminEdit->status) ? $superAdminEdit->status : '';
            $editDetails['image'] = isset($superAdminEdit->image) ? asset('/public/image/' . $superAdminEdit->image) : '';
            // if (isset($responseData['data'])) {
            //     $editDetails = $responseData['data'];
            // }
            // dD($editDetails);
            return view('admin.profile.update', compact('editDetails'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use profile update
    public function profileUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'name' => 'required',
                'number' => 'required',
                'address' => 'required',
            ], [
                'email.required' => 'Enter Email',
                'name.required' => 'Enter Name',
                'number.required' => 'Enter Phone Number',
                'address.required' => 'Enter Address',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return redirect()->back()->with('error', $error->first());
            }

            $profileData = User::where('id', $request->id)->first();
            $profileData->name = $request->name;
            $profileData->email = $request->email;
            if(isset($request->password))
            {
                $profileData->password = $request->password;
            }
            if (!empty($request->image)) {
                $image    = $request->image;
                $filename = time() . $image->getClientOriginalName();
                $image->move(public_path('image'), $filename);
                $profileData->image = $filename;
            }
            if(isset($request->address))
            {
                $profileData->address = $request->address;
            }
            if(isset($request->number))
            {
                $profileData->phone_number = $request->number;
            }
            $profileData->update();

            
            return redirect()->back()->with('success', 'Profile Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function referenceIndex(Request $request)
    {
        $settingData = Setting::first();
        return view('admin.refrence.index', compact('settingData'));
    }

    public function refrenceUpdate(Request $request)
    {

     
        $settingData = Setting::first(); // Retrieve the first setting record

        if (!$settingData) {
            $settingData = new Setting(); // Initialize a new Setting model if none exists
        }

        if (isset($request->video)) {
            // Set the image attribute of $settingData
            $settingData->video = $request->video;
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/students/', $filename);

            // Set the image attribute of $settingData
            $settingData->image = $filename;
        }

         if ($request->hasFile('reference_image')) {
            $file = $request->file('reference_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/students/', $filename);

            // Set the image attribute of $settingData
            $settingData->reference_image = $filename;
        }

        $settingData->save(); // Save the changes to the database

        return redirect()->back()->with('success', 'Data Updated Successfully');
    }
}
