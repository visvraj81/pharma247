<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\AgentPlan;
use App\Models\PharmaShop;
use App\Models\FrontPermissions;
use App\Models\LogsModel;
use Illuminate\Support\Facades\Auth;


class CompanyController extends Controller
{
  // this function use company list
  public function pharmaIndex(Request $request)
  {
      try {
        $url = url('/') . '/api/pharma-list';
        $response = Http::get($url);
        $data = $response->json();
        $detailsList = [];
        if (isset($data['data'])) {
          $detailsList = $data['data'];
        }
        return view('admin.company.index', compact('detailsList'));
      } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
      }
  }

  public function agentPlan(Request $request)
  {
      $agendData = AgentPlan::where('agent_id', $request->data)->get();
      $planData  = '';

      if (isset($agendData)) {
        foreach ($agendData as $list) {
          $planData  .= '<div class="row mt-3">    
                  <div class="col-md-6">
                      <label for="">Plan</label>
                      <input type="text"  placeholder="Enter Plan" class="form-control" value="' . $list->getagent->name . '" readonly>
                      <input type="hidden" name="plan[]"  value="' . $list->plan_name . '" />
                  </div>
                  <div class="col-md-6">
                      <label for="">Commission</label>
                      <input type="text" name="commission[]" placeholder="Enter Commission" value="' . $list->commision . '" class="form-control">
                  </div>
             </div>';
          }
      }
      return response()->json(['planData' => $planData]);
  }

  // this function use pharma create
  public function pharmaCreate(Request $request)
  {
      try {
        $agent = User::where('role', '2')->get();
        return view('admin.company.create', compact('agent'));
      } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
      }
  }

  public function checkEmail(Request $request)
  {
      $emailExists = User::where('email', $request->email)->exists(); // Adjust the model and field as necessary
      return response()->json(['exists' => $emailExists]);
  }

  // this function use pharma store
  public function pharmaStore(Request $request)
  {
    try {
      $image = $request->file('dark_logo');

      $imageContent = file_get_contents($image->getRealPath());

      $darkLogo = base64_encode($imageContent);

      $ligthLogo = $request->file('light_logo');

      $imageContentLogo = file_get_contents($ligthLogo->getRealPath());

      $ligthLogoImage = base64_encode($imageContentLogo);

      $smallDarkLogo = $request->file('small_dark_logo');

      $imageSmallLogo = file_get_contents($smallDarkLogo->getRealPath());

      $smallDarkLogos = base64_encode($imageSmallLogo);

      $imagesmallLogos = $request->file('small_light_logo');

      $imageContentLogos = file_get_contents($imagesmallLogos->getRealPath());

      $smallLightLogos = base64_encode($imageContentLogos);

      $url = url('/') . '/api/create-pharma-shop';
      $data = [
        'pharma_name' => $request->pharma_name,
        'plan' => json_encode($request->plan),
        'commission' => json_encode($request->commission),
        'pharma_short_name' => $request->pharma_short_name,
        'pharma_email' => $request->pharma_email,
        'pharma_phone_number' => $request->pharma_phone,
        // 'pharma_timezone' => $request->default_timezone,
        'pharma_status' => $request->status,
        'pharma_address' => $request->pharma_address,
        'city' => $request->city,
        'email' => $request->email,
        'agent_id' => $request->agent_id,
        'password' => $request->password,
        'dark_logo' => $darkLogo,
        'light_logo' => $ligthLogoImage,
        'small_dark_logo' => $smallDarkLogos,
        'small_light_logo' => $smallLightLogos,
      ];

      // Make the HTTP POST request
      $response = Http::post($url, $data);

      $responseData = $response->json();

      return redirect()->route('pharma.index')->with('success', 'Pharma Added Successfully');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  // this function use delete pharma 
  public function pharmaDelete($id)
  {
      try {
        $url = url('/') . '/api/pharma-shop-delete';
        $data = [
          'id' => $id,
        ];

        // Make the HTTP POST request
        $response = Http::post($url, $data);

        $responseData = $response->json();

        return redirect()->route('pharma.index')->with('success', 'Pharma Deleted Successfully');
      } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
      }
  }

  // this function use edit pharma page
  public function pharmaEdit($id)
  {
    try {
      $url = url('/') . '/api/pharma-shop-edit';
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
      $agent = User::where('role', '2')->get();
      return view('admin.company.edit', compact('editDetails', 'agent'));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  //this function use update Pharma
  public function pharmaUpdate(Request $request)
  {

    try {
         
      $darkLogo = null;
      if (isset($request->dark_logo)) {
        $image = $request->file('dark_logo');
        $imageContent = file_get_contents($image->getRealPath());
        $darkLogo = base64_encode($imageContent);
      }

      $ligthLogoImage = null;
      if (isset($request->light_logo)) {
        $ligthLogo = $request->file('light_logo');
        $imageContentLogo = file_get_contents($ligthLogo->getRealPath());
        $ligthLogoImage = base64_encode($imageContentLogo);
      }

      $smallDarkLogos = null;
      if (isset($request->small_dark_logo)) {
        $smallDarkLogo = $request->file('small_dark_logo');
        $imageSmallLogo = file_get_contents($smallDarkLogo->getRealPath());
        $smallDarkLogos = base64_encode($imageSmallLogo);
      }

      $smallLightLogos = null;
      if (isset($request->small_light_logo)) {
        $imagesmallLogos = $request->file('small_light_logo');
        $imageContentLogos = file_get_contents($imagesmallLogos->getRealPath());
        $smallLightLogos = base64_encode($imageContentLogos);
      }

      $url = url('/') . '/api/update-pharma-shop';
      $data = [
        'user_id' => $request->id,
        'remark' => $request->remark,
        'pharma_name' => $request->pharma_name,
        'pharma_short_name' => $request->pharma_short_name,
        'pharma_email' => $request->pharma_email,
        'plan' => json_encode($request->plan),
        'commission' => json_encode($request->commission),
        'operation' => $request->operation,
        'referral_balance'=>$request->referral_balance,
        'amount' => $request->amount,
        'updated_balance' => $request->updated_balance,
        'pharma_phone' => $request->pharma_phone,
        'pharma_status' => $request->status,
        'pharma_address' => $request->pharma_address,
        'city' => $request->city,
        'email' => $request->email,
        'agent_id' => $request->agent_id,
        'password' => $request->password,
        'dark_logo' => $darkLogo,
        'light_logo' => $ligthLogoImage,
        'small_dark_logo' => $smallDarkLogos,
        'small_light_logo' => $smallLightLogos,
      ];
      
      if(isset($request->updated_balance))
      {

       $userDatas = User::where('id',$request->id)->first();
       $userDatasReffral = null;
       if(isset($userDatas->referral_code))
       {
         $userDatasReffral = User::where('user_referral_code',$userDatas->referral_code)->first();
       }
          if($request->operation == "+")
      {

          $message =  $request->referral_balance .' Referral Balance Add Amount '.$request->amount .'And Remark :'.$request->remark;
          $userLogs = new LogsModel;
          $userLogs->message = $message;
          $userLogs->amount = $request->amount;
          $userLogs->referral_user_id = isset($userDatasReffral->id) ? $userDatasReffral->id :'';
          $userLogs->user_id =  Auth::user()->id;
          $userLogs->date_time = date('Y-m-d H:i a');
          $userLogs->save();
        
      }else if($request->operation == "-"){
         $message =  $request->referral_balance .' Referral Balance Lees Amount '.$request->amount .'And Remark :'.$request->remark;
        
          $userLogs = new LogsModel;
          $userLogs->message = $message;
          $userLogs->lees_amount = $request->amount;
          $userLogs->referral_user_id = isset($userDatasReffral->id) ? $userDatasReffral->id :'';
          $userLogs->user_id =  Auth::user()->id;
          $userLogs->date_time = date('Y-m-d H:i a');
          $userLogs->save();
      }
      }
   
      
      // Make the HTTP POST request
      $response = Http::post($url, $data);

      $responseData = $response->json();
      
      
      $userDetails =  User::where('id',$request->id)->first();
      
      if(isset($userDetails ))
      {
        $userDetails->remark = $request->remark;
        $userDetails->save();
      }
      // dD($responseData);
      return redirect()->route('pharma.index')->with('success', 'Pharma Updated Successfully');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  //this function use pharma subscription
  public function pharmaSubcription($id)
  {
    try {
      $subscrptionPlan = SubscriptionPlan::get();
      $dataId = $id;
      $url = url('/') . '/api/pharma-plan-details';
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
      return view('admin.company.subscription', compact('subscrptionPlan', 'dataId', 'editDetails'));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  //this function use update in plan
  public function pharmaSubscriptionUpdate(Request $request)
  {
    try {

      $validator = Validator::make($request->all(), [

        'plan' => 'required',
        'plan_type' => 'required',
        'payment_mode' => 'required',
        'amount' => 'required',
        'payment_date' => 'required',
        'expire_date' => 'required'
      ], [
        'plan.required' => 'Enter Plan',
        'plan_type.required' => 'Enter Plan Type',
        'payment_mode.required' => 'Enter Payment Mode',
        'amount.required' => 'Enter Amount',
        'payment_date.required' => 'Enter Payment Date',
        'expire_date.required' => 'Select Expire date'
      ]);

      if ($validator->fails()) {
        $error = $validator->getMessageBag();
        return redirect()->back()->with('error', $error->first());
      }

      $url = url('/') . '/api/pharma-plan';
      $data = [
        'pharma_id' => $request->id,
        'subscription_plan_id' => $request->plan,
        'plan_type' => $request->plan_type,
        'payment_mode' => $request->payment_mode,
        'amount' => $request->amount,
        'payment_date' => $request->payment_date,
        'license_will_expire_on' => $request->expire_date,
        'next_payment_date' => $request->expire_date,
      ];

      // Make the HTTP POST request
      $response = Http::post($url, $data);

      $responseData = $response->json();

      return redirect()->route('pharma.index')->with('success', 'Pharma Plan Updated Successfully');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function permissionsIndex(Request $request)
  {
    try {

      $persmmionData  = FrontPermissions::get();

      return view('admin.permissions.index', compact('persmmionData'));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function permissionsDelete($id)
  {
    $persmmionData  = FrontPermissions::find($id);
    if (isset($persmmionData)) {
      $persmmionData->delete();
    }
    return response()->json(['true' => true]);
  }

  public function permissionsEdit($id)
  {
    $persmmionData  = FrontPermissions::find($id);
    return view('admin.permissions.edit', compact('persmmionData'));
  }

  public function permissionsCreate(Request $request)
  {
    return view('admin.permissions.create');
  }

  public function permissionsStore(Request $request)
  {
    $frontPermmison = new FrontPermissions;
    $frontPermmison->permissions = $request->permissions;
    $frontPermmison->save();

    return redirect()->route('permissions.index')->with('success', 'Permissions Added Successfully');
  }

  public function permissionsUpdate(Request $request)
  {
    $updateData  = FrontPermissions::find($request->id);
    $updateData->permissions = $request->permissions;
    $updateData->update();

    return redirect()->route('permissions.index')->with('success', 'Permissions Updated Successfully');
  }
}
