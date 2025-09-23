<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    //this function use super admin index
    public function superAdminIndex(Request $request)
    {
        //this function use is super admin index
        try {
            $url = url('/') . '/api/super-admin-list';
            $response = Http::get($url);
            $data = $response->json();
            $detailsList = [];
            if (isset($data['data'])) {
                $detailsList = $data['data'];
            }

            return view('admin.superadmin.index', compact('detailsList'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use super admin create
    public function superAdminCreate(Request $request)
    {
        try {
            $roles = Role::pluck('name', 'name')->all();
            return view('admin.superadmin.create', compact('roles'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use super admin store
    public function superAdminStore(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'image' => 'required',
                'number' => 'required',
                'password' => 'required',
                'status' => 'required',
            ], [
                'email.required' => 'Enter Email',
                'image.required' => 'Select Profile Image',
                'name.required' => 'Enter Name',
                'number.required' => 'Enter Phone Number',
                'password.required' => 'Enter Password',
                'status.required' => 'Select Status',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return redirect()->back()->with('error', $error->first());
            }

            $userEmail = User::where('email', $request->email)->first();
            if (!empty($userEmail)) {
                return redirect()->back()->with('error', 'Email Already Exist.');
            }
            // dd($request->all());
            $image = $request->file('image');

            $superadminimage = file_get_contents($image->getRealPath());

            $filename = base64_encode($superadminimage);

            $url = url('/') . '/api/create-super-admin';
            $data = [
                'name' => $request->name,
                'roles' => json_encode($request->roles),
                'email' => $request->email,
                'phone_number' => $request->number,
                'password' => $request->password,
                'status' => $request->status,
                'image' => $filename,
            ];

            // Make the HTTP POST request
            $response = Http::post($url, $data);

            $responseData = $response->json();



            return redirect()->route('superadmin.index')->with('success', 'Super Admin Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use super admin delete
    public function superAdminDelete($id)
    {
        try {
            $url = url('/') . '/api/super-admin-delete';
            $data = [
                'id' => $id,
            ];

            $response = Http::post($url, $data);

            $responseData = $response->json();

            return redirect()->route('superadmin.index')->with('success', 'Super Admin Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use super admin edit
    public function superAdminEdit($id)
    {
        try {

            $url = url('/') . '/api/super-admin-edit';
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
            $userData  = User::where('id', $id)->first();
            $roles = Role::pluck('name', 'name')->all();

            $userRole = $userData->roles->pluck('name', 'name')->all();

            return view('admin.superadmin.edit', compact('editDetails', 'roles', 'userRole'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use super admin update
    public function superAdminUpdate(Request $request)
    {
        try {


            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'name' => 'required',
                'number' => 'required',
                'status' => 'required',
            ], [
                'email.required' => 'Enter Email',
                'name.required' => 'Enter Name',
                'number.required' => 'Enter Phone Number',
                'status.required' => 'Select Status',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return redirect()->back()->with('error', $error->first());
            }

            $filename = null;
            if (isset($request->image)) {
                $image = $request->file('image');

                $superadminimage = file_get_contents($image->getRealPath());

                $filename = base64_encode($superadminimage);
            }

            $url = url('/') . '/api/super-admin-update';
            $data = [
                'id' => $request->id,
                'name' => $request->name,
                'roles' => json_encode($request->roles),
                'email' => $request->email,
                'phone_number' => $request->number,
                'password' => $request->password,
                'status' => $request->status,
                'image' => $filename,
            ];

            // Make the HTTP POST request
            $response = Http::post($url, $data);

            $responseData = $response->json();

            $userData  = User::where('id', $request->id)->first();
            DB::table('model_has_roles')->where('model_id', $userData->id)->delete();
            $roles = $request->input('roles');
            $userData->assignRole($roles);

            return redirect()->route('superadmin.index')->with('success', 'Super Admin Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
