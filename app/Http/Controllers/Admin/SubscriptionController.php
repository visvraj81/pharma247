<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanFeatures;
use App\Models\LogsModel;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Transcations;

class SubscriptionController extends Controller
{
    //this function use subscription index page
    public function subscriptionIndex(Request $request)
    {
        try {
            $url = url('/') . '/api/plan-list';
            $response = Http::get($url);
            $data = $response->json();
            $detailsList = [];
            if (isset($data['data'])) {
                $detailsList = $data['data'];
            }
            return view('admin.subscription.index', compact('detailsList'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use pahrma create
    public function subscriptionCreate(Request $request)
    {
        try {
            return view('admin.subscription.create');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use subscription store
    public function subscriptionStore(Request $request)
    {
        try {
          
            $validator = Validator::make($request->all(), [
                'plan_name' => 'required',
            ], [
                'plan_name.required' => 'Enter Name',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();

                return redirect()->back()->with('error', $error->first());
            }
   
                //   dD($request->all());
                $subscriptionPlan = new SubscriptionPlan;
                $subscriptionPlan->name = $request->plan_name;
                $subscriptionPlan->max_product = $request->max_product;
                $subscriptionPlan->description = $request->description;
                $subscriptionPlan->re_newal = $request->re_newal;
                $subscriptionPlan->is_popular = $request->is_popular;
                $subscriptionPlan->monthly_price = $request->monthly_price;
                $subscriptionPlan->annual_price = $request->annual_price;
                $subscriptionPlan->percentage = $request->percentage;
                $subscriptionPlan->enable_modules = implode(',',$request->enable_module);
                $subscriptionPlan->save();
    
                if (isset($request->features)) {
                    foreach ($request->features as $list) {
                        $subscriptionFeature = new SubscriptionPlanFeatures;
                        $subscriptionFeature->subscription_plan_id = $subscriptionPlan->id;
                        $subscriptionFeature->features_name = $list;
                        $subscriptionFeature->save();
                    }
                }

            return redirect()->route('subscription.index')->with('success', 'Subscription Plan Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use subscription delete
    public function subscriptionDelete($id)
    {
        try {
            $url = url('/') . '/api/plan-delete';
            $data = [
                'id' => $id,
            ];

            $response = Http::post($url, $data);

            $responseData = $response->json();

            return redirect()->route('admin.subscription.index')->with('success', 'Subscription Plan Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use subscription edit
    public function subscriptionEdit($id)
    {
        try {
            $url = url('/') . '/api/edit-plan';
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
      
            return view('admin.subscription.edit', compact('editDetails'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use subscription update
    public function subscriptionUpdate(Request $request)
    {
        try {
           
            $validator = Validator::make($request->all(), [
                'plan_name' => 'required',
            ], [
                'plan_name.required' => 'Enter Name',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();

                return redirect()->back()->with('error', $error->first());
            }

            $subscriptionPlan = SubscriptionPlan::where('id', $request->id)->first();
            $subscriptionPlan->name = $request->plan_name;
            $subscriptionPlan->max_product = $request->max_product;
            $subscriptionPlan->description = $request->description;
            $subscriptionPlan->is_popular = $request->is_popular;
            $subscriptionPlan->re_newal = $request->re_newal;
            $subscriptionPlan->monthly_price = $request->monthly_price;
            $subscriptionPlan->annual_price = $request->annual_price;
            $subscriptionPlan->percentage = $request->percentage;
            $subscriptionPlan->enable_modules = implode(',',$request->enable_module);
            $subscriptionPlan->update();


            if (isset($request->features)) {
                $subcriptionPlan = SubscriptionPlanFeatures::where('subscription_plan_id', $subscriptionPlan->id)->get();
                if (isset($subcriptionPlan)) {
                    foreach ($subcriptionPlan as $listDelete) {
                        $listDelete->delete();
                    }
                }
                foreach ($request->features as $list) {
                    if (isset($list)) {
                        $subscriptionFeature = new SubscriptionPlanFeatures;
                        $subscriptionFeature->subscription_plan_id = $subscriptionPlan->id;
                        $subscriptionFeature->features_name = $list;
                        $subscriptionFeature->save();
                    }
                }
            }

            return redirect()->route('subscription.index')->with('success', 'Subscription Plan Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function plan feature delete
    public function planFeatureDelete($id)
    {
     
        try {
            $url = url('/') . '/api/plan-feature-delete';
            $data = [
                'id' => $id,
            ];

            $response = Http::post($url, $data);

            $responseData = $response->json();

            return redirect()->back()->with('success', 'Subscription Plan Feature Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
  
    public function subscriptionCron(Request $request)
    {
        $logsDetails = LogsModel::whereDate('created_at', '>', now()->subDays(7))->where('status','0')->whereNotNull('referral_user_id')->get();
      
        if(isset($logsDetails))
        {
             foreach($logsDetails as $listDetails)
             {
                $listDetails->status = '1';
                $listDetails->save();
               
                $userData = User::where('id',$listDetails->referral_user_id)->first();
               if(isset($userData))
               {
                 $userData->referral_amount = $userData->referral_amount + $listDetails->amount;
                 $userData->update();
               }
             }
        }
      
        return  true;
    }

   public function freeTrylCron(Request $request)
   {
     
   }
}
