<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\AgentPlan;
use App\Mail\AgentMail;
use Illuminate\Support\Facades\Mail;

class AgentPlanController extends ResponseController
{
    //this function use create agent 
    public function agentCreate(Request $request)
    {
        try {
            // dD($request->all());
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                // 'image' => 'required',
                'name' => 'required',
                'phone_number' => 'required',
                'password' => 'required',
                // 'plan_commison' => 'required',
            ], [
                'email.required' => 'Enter Email',
                'image.required' => 'Select Profile Image',
                'name.required' => 'Enter Name',
                'phone_number.required' => 'Enter Phone Number',
                'password.required' => 'Enter Password',
                // 'plan_commison.required' => 'Enter Plan Commsion',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $userEmail = User::where('email', $request->email)->first();
            if (!empty($userEmail)) {
                return $this->sendError('Email Already Exist');
            }
            // dd($request->all());

            $agentUser = new User;
            $agentUser->name = $request->name;
            $agentUser->email = $request->email;
            $agentUser->password = Hash::make($request->password);
            $agentUser->new_password = $request->password;
            $agentUser->phone_number = $request->phone_number;
            $agentUser->status = '1';
            $agentUser->role = '2';
            if (!empty($request->image)) {
                $base64Image = $request->input('image');
                $binaryImage = base64_decode($base64Image);
                $filename = 'image_' . time() . '.png';
                $path = public_path('image/' . $filename);
                file_put_contents($path, $binaryImage);
                $agentUser->image = $filename;
            }
            $agentUser->save();

            $details = [
                'email' => $request->email,
                'password' => $request->password
            ];

            Mail::to($request->email)->send(new \App\Mail\AgentMail($details));

            $agentPlan = json_decode($request->plan, true);
            $agentCommission = json_decode($request->commission, true);
            $combinedArray = array_map(null, $agentPlan, $agentCommission);

            foreach ($combinedArray as $combined) {
                $planStore = new AgentPlan;
                $planStore->agent_id = $agentUser->id;
                $planStore->plan_name = $combined[0];
                $planStore->commision = $combined[1];
                $planStore->save();
            }


            // $agentPlan = json_decode($request->plan_commison, true);
            // if (isset($agentPlan)) {
            //     foreach ($agentPlan as $list) {
            //         $planStore = new AgentPlan;
            //         $planStore->agent_id = $agentUser->id;
            //         $planStore->plan_name = $list['plan'];
            //         $planStore->commision = $list['commision'];
            //         $planStore->save();
            //     }
            // }

            return $this->sendResponse('', 'Agent Plan Added Successfully');
        } catch (\Exception $e) {
            Log::info("Agent Create api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    //this function use agent list
    public function agentList(Request $request)
    {
        try {
            $agentList = User::orderBy('id', 'DESC')->where('role', '2')->get();

            $agentlistArray = [];
            if (isset($agentList)) {
                foreach ($agentList as $key => $value) {
                    $agentlistArray[$key]['id'] = isset($value->id) ? $value->id : '';
                    $agentlistArray[$key]['name'] = isset($value->name) ? $value->name : '';
                    $agentlistArray[$key]['email'] = isset($value->email) ? $value->email : '';
                    $agentlistArray[$key]['image'] = isset($value->image) ? asset('/public/image/' . $value->image) : '';
                }
            }
            return $this->sendResponse($agentlistArray, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Agent List api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use agent edit
    public function agentEdit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => "Enter Agent Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $agentEdit = User::where('id', $request->id)->first();

            if (empty($agentEdit)) {
                return $this->sendError('Data Not Found');
            }

            $agentlistData = [];
            $agentlistData['id'] = isset($agentEdit->id) ? $agentEdit->id : '';
            $agentlistData['name'] = isset($agentEdit->name) ? $agentEdit->name : '';
            $agentlistData['email'] = isset($agentEdit->email) ? $agentEdit->email : '';
            $agentlistData['phone_number'] = isset($agentEdit->phone_number) ? $agentEdit->phone_number : '';
            $agentlistData['password'] = isset($agentEdit->new_password) ? $agentEdit->new_password : '';
            $agentlistData['image'] = isset($agentEdit->image) ? asset('/public/image/' . $agentEdit->image) : '';
            $agentPlan = [];
            if (isset($agentEdit->getplan)) {
                foreach ($agentEdit->getplan as $key => $value) {
                    $planData['id'] = isset($value->id) ? $value->id : '';
                    $planData['name'] = isset($value->getagent->name) ? $value->getagent->name : '';
                    $planData['plan_name'] = isset($value->plan_name) ? $value->plan_name : '';
                    $planData['commission'] = isset($value->commision) ? $value->commision : '';
                    array_push($agentPlan, $planData);
                }
            }
            $agentlistData['plan'] = $agentPlan;
            return $this->sendResponse($agentlistData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Agent Edit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use agent delete
    public function agentDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => "Enter Agent Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $agentDelete = User::where('id', $request->id)->first();
            if (isset($agentDelete)) {
                $agent = AgentPlan::where('agent_id', $request->id)->get();
                if (isset($agent)) {
                    foreach ($agent as $value) {
                        $value->delete();
                    }
                }
                $agentDelete->delete();
            }
            return $this->sendResponse('', 'Agent Deleted Successfully');
        } catch (\Exception $e) {
            Log::info("Agent Delete api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    //this function use agent update
    public function agentUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'email' => 'required',
                'name' => 'required',
                'phone_number' => 'required',
                // 'plan_commison' => 'required',
            ], [
                'id.required' => 'Enter Id',
                'email.required' => 'Enter Email',
                'name.required' => 'Enter Name',
                'phone_number.required' => 'Enter Phone Number',
                'plan_commison.required' => 'Enter Plan Commsion',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $agentUser = User::find($request->id);
            $agentUser->name = $request->name;
            $agentUser->email = $request->email;
            if (isset($request->password)) {
                $agentUser->password = Hash::make($request->password);
                  $agentUser->new_password = $request->password;
            }
            $agentUser->phone_number = $request->phone_number;
            if (!empty($request->image)) {
              
                $base64Image = $request->input('image');
                $binaryImage = base64_decode($base64Image);
                $filename = 'image_' . time() . '.png';
                $path = public_path('image/' . $filename);
                file_put_contents($path, $binaryImage);
                $agentUser->image = $filename;
            }
            $agentUser->update();

            $agentPlan = json_decode($request->plan, true);
            $agentCommission = json_decode($request->commission, true);
            $combinedArray = array_map(null, $agentPlan, $agentCommission);

            if (isset($combinedArray)) {
                $agentList =  AgentPlan::where('agent_id', $request->id)->get();

                if (isset($agentList)) {
                    foreach ($agentList as $agentData) {
                        $agentData->delete();
                    }
                }
                foreach ($combinedArray as $combined) {
                    $planStore = new AgentPlan;
                    $planStore->agent_id = $agentUser->id;
                    $planStore->plan_name = $combined[0];
                    $planStore->commision = $combined[1];
                    $planStore->save();
                }
            }

            return $this->sendResponse('', 'Agent Plan Added Successfully');
        } catch (\Exception $e) {
            Log::info("Agent Update api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
