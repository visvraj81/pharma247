<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AgentPlan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\SubscriptionPlan;

class AgentController extends Controller
{
  //thsi function use agent index
  public function AgentIndex(Request $request)
  {
    try {

      $url = url('/') . '/api/agent-list';
      $response = Http::get($url);
      $data = $response->json();
      $detailsList = [];
      if (isset($data['data'])) {
        $detailsList = $data['data'];
      }

      return view('admin.agent.index', compact('detailsList'));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  //this function use agent create
  public function AgentCreate(Request $request)
  {
    try {
      $subcriptionPlan = SubscriptionPlan::get();
      return view('admin.agent.create', compact('subcriptionPlan'));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  //thsi function use agent store
  public function AgentStore(Request $request)
  {
    try {
      // dd($request->all());

      $validator = Validator::make($request->all(), [

        'name' => 'required',
        'email' => 'required',
        'password' => 'required',
        'phone_number' => 'required',
        // 'commission' => 'required',
        // 'image' => 'required',
      ], [
        'name.required' => 'Enter Name',
        'email.required' => 'Enter Email',
        'password.required' => 'Enter Password',
        'phone_number.required' => 'Enter Phone Number',
        // 'plan.required' => 'Enter Plan',
        // 'commission.required' => 'Enter Commission',
        'image.required' => 'select Image'
      ]);

      if ($validator->fails()) {
        $error = $validator->getMessageBag();
        return redirect()->back()->with('error', $error->first());
      }
      $userEmail = User::where('email', $request->email)->first();
      if (!empty($userEmail)) {
        return redirect()->back()->with('error', 'Email Already Exist');
      }

      $darkLogo = null;
      if (isset($request->image)) {
        $image = $request->file('image');
        $imageContent = file_get_contents($image->getRealPath());
        $darkLogo = base64_encode($imageContent);
      }

      $url = url('/') . '/api/agent-create';
      $data = [
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
        'phone_number' => $request->phone_number,
        'image' =>  $darkLogo,
        'plan' => json_encode($request->plan),
        'commission' => json_encode($request->commission),
      ];
      // Make the HTTP POST request
      $response = Http::post($url, $data);

      $responseData = $response->json();

      return redirect()->route('agent.index')->with('success', 'Agent Added Successfully');
    } catch (\Exception $e) {

      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  //this function use delete agent
  public function agentDelete($id)
  {
    try {
      $url = url('/') . '/api/agent-delete';
      $data = [
        'id' => $id,
      ];

      // Make the HTTP POST request
      $response = Http::post($url, $data);

      $responseData = $response->json();

      return redirect()->route('agent.index')->with('success', 'Agent Deleted Successfully');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  //thsi function use agent edit 
  public function agentEdit($id)
  {
    try {
      $subcriptionPlan = SubscriptionPlan::get();

      $url = url('/') . '/api/agent-edit';
      $data = [
        'id' => $id,
      ];

      // Make the HTTP POST request
      $response = Http::post($url, $data);

      $responseData = $response->json();
      $detailsGet = [];
      if (isset($responseData['data'])) {
        $detailsGet = $responseData['data'];
      }

      return view('admin.agent.edit', compact('subcriptionPlan', 'detailsGet'));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  //this function use agent Update
  public function agentUpdate(Request $request)
  {
    try {

      $validator = Validator::make($request->all(), [

        'name' => 'required',
        'email' => 'required',
        // 'password' => 'required',
        'phone_number' => 'required',
        // 'plan' => 'required',
        // 'commission' => 'required',
      ], [
        'name.required' => 'Enter Name',
        'email.required' => 'Enter Email',
        'password.required' => 'Enter Password',
        'phone_number.required' => 'Enter Phone Number',
        // 'plan.required' => 'Enter Plan',
        // 'commission.required' => 'Enter Commission',
      ]);

      if ($validator->fails()) {
        $error = $validator->getMessageBag();
        return redirect()->back()->with('error', $error->first());
      }

      $darkLogo = null;
      if (isset($request->image)) {
        $image = $request->file('image');
        $imageContent = file_get_contents($image->getRealPath());
        $darkLogo = base64_encode($imageContent);
      }

      $url = url('/') . '/api/agent-update';
      $data = [
        'id' => $request->id,
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
        'phone_number' => $request->phone_number,
        'image' =>  $darkLogo,
        'plan' => json_encode($request->plan),
        'commission' => json_encode($request->commission),
      ];
      // Make the HTTP POST request
      $response = Http::post($url, $data);

      $responseData = $response->json();

      return redirect()->route('agent.index')->with('success', 'Agent Updated Successfully');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
