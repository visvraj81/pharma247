<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\ResponseController;
use App\Models\User;

class ProfileController extends ResponseController
{
    //this function use create profile
    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'=>'required',
                'name' => 'required',
                'email' => 'required',
                'phone_number' => 'required',
                // 'image' => 'required',
                'address' => 'required',
            ], [
                'id.required'=>'Enter Profile Id',
                'email.required' => 'Enter Email',
                'image.required' => 'Select Profile Image',
                'name.required' => 'Enter Name',
                'phone_number.required' => 'Enter Phone Number',
                'password.required' => 'Enter Password',
                'address.required' => 'Enter Address',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }
            $profileData = User::where('id', $request->id)->first();
      
            if (empty($profileData)) {
                return $this->sendError('Data Not Found');
            }
      
            $profileData->name = $request->name;
            $profileData->email = $request->email;
            if(isset($request->password))
            {
                $profileData->password = $request->password;
            }
            // if (!empty($request->image)) {
            //     $image    = $request->image;
            //     $filename = time() . $image->getClientOriginalName();
            //     $image->move(public_path('image'), $filename);
            //     $profileData->image = $filename;
            // }
            if (!empty($request->image)) {
                $base64Image = $request->input('image');
                $binaryImage = base64_decode($base64Image);
                $filename = 'image_' . time() . '.png';
                $path = public_path('image/' . $filename);
                file_put_contents($path, $binaryImage);
                $profileData->image = $filename;
            }
            $profileData->address = $request->address;
            $profileData->phone_number = $request->phone_number;
            $profileData->update();
            return $this->sendResponse('', 'Profile Updated Successfully');
        } catch (\Exception $e) {
            Log::info("update profile api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
