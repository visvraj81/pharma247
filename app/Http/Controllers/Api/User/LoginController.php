<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\ApiToken;
use App\Models\PharmaShop;
use App\Models\OTP;
use Illuminate\Support\Facades\Http;
use App\Models\FrontPermissions;
use App\Models\PrivacyPolicy;
use App\Models\NotificationModel;
use App\Models\ReconciliationIteam;
use Carbon\Carbon;
use App\Models\Transcations;
use App\Models\UsersDeviceToken;
use App\Models\WebSettings;
use App\Models\BankAccount;
use App\Models\SwitchAccount;
use App\Models\Distributer;
use Illuminate\Support\Str;
use App\Models\PatientsModel;

class LoginController extends ResponseController
{
    // this function use user login
    public function userLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile_number' => 'required',
                'password' => 'required',
            ], [
                'mobile_number.required' => 'Enter Mobile Number',
                'password.required' => 'Enter Password',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            // $userData = User::where('phone_number', $request->mobile_number)->where('status','1')->first();
          	
            $userData = User::where('phone_number', $request->mobile_number)->first();
            if (empty($userData)) {
                return $this->sendError('Mobile number invalid.');
            }

            $userData = User::where('phone_number', $request->mobile_number)->where('status', '1')->first();
            if (empty($userData)) {
                return $this->sendError('You Are Unauthorized.');
            }
          	
          	$switchAccountUserData = SwitchAccount::where('user_id', $userData->id)->where('device_id',$request->device_id)->first();
          
          	$passwordValid = false;
          
          	if ($switchAccountUserData) {
                // Both passwords must match
                if (Hash::check($request->password, $userData->password) && Hash::check($request->password, $switchAccountUserData->password)) {
                    $passwordValid = true;
                }
            } else {
                // Only userData password check
                if (Hash::check($request->password, $userData->password)) {
                    $passwordValid = true;
                }
            }
           
            if ($passwordValid) {
              	$switchAccountData = SwitchAccount::where('device_id', $request->device_id)->where('user_phone_number',$request->mobile_number)->first();
                if(empty($switchAccountData) && isset($request->type) && $request->type == 1) {
                    $switch_account_store_data = new SwitchAccount();
                    $switch_account_store_data->user_id = isset($request->user_id) ? $request->user_id : $userData->id;
                    $switch_account_store_data->device_id = isset($request->device_id) ? $request->device_id : null;
                    $switch_account_store_data->name = isset($userData->name) ? $userData->name : null;
                    $switch_account_store_data->image = isset($userData->pharmacy_logo) ? $userData->pharmacy_logo : null;
                    $switch_account_store_data->user_phone_number = $request->mobile_number;
                    $switch_account_store_data->user_password = $request->password;
                    $switch_account_store_data->password = Hash::make($request->password);
                    // $switch_account_store_data->login_type = 1;
                    $switch_account_store_data->save();
                }
              
				SwitchAccount::where('device_id',$request->device_id)->update(['login_type' => 2]);
              
              	SwitchAccount::where('device_id',$request->device_id)->where('user_phone_number',$request->mobile_number)->update(['login_type' => 1]);
              	
              	if (isset($request->token_id)) {
                	$userToken = UsersDeviceToken::where('user_id', $userData->id)->where('token_id', $request->token_id)->first();
                  	if (!$userToken) {
                 		$user_device_token_store_data = new UsersDeviceToken();
                        $user_device_token_store_data->user_id = $userData->id;
                        $user_device_token_store_data->token_id = $request->token_id;
                        $user_device_token_store_data->firebase_type = $request->firebase_type;
                        $user_device_token_store_data->save();
                    }
                }
              
              	$userData->user_password = $request->password;
              	if(isset($request->type) && $request->type == 1) {
                	$userData->device_id = $request->device_id;
                }
              	// $userData->user_referral_code = Str::upper(Str::random(3)) . rand(100, 999);
              	$userData->update();

                // $tokenCheck = ApiToken::where('tokenable_id', $userData->id)->first();

                $token = $userData->createToken($userData->name)->plainTextToken;

                $todayDate = Carbon::today();
                $reconcilationCount = ReconciliationIteam::whereDate('created_at', $todayDate)->where('reported_by', $userData->id)->exists();

                $planData = false;
                $registeredMoreThan7Days = Carbon::parse($userData->created_at)->diffInDays($todayDate) > 7;
                if ($registeredMoreThan7Days == true) {
                    $planData = false;
                    $paymentCheck = Transcations::where('pharma_name', $userData->id)->first();
                    if (isset($paymentCheck)) {
                        $planData = true;
                    }
                }
              
              	$distributerAlreadyExist = Distributer::where('user_id',$userData->id)->where('name','OPENING DISTRIBUTOR')->where('phone_number','4242424242')->first();
                if(empty($distributerAlreadyExist)) {
                    $distributer_details = new Distributer;
                    $distributer_details->name = 'OPENING DISTRIBUTOR';
                    $distributer_details->email = 'opening@gmail.com';
                    $distributer_details->phone_number = '4242424242';
                    $distributer_details->user_id = $userData->id;
                    $distributer_details->status = '1';
                    $distributer_details->role = '4';
                    $distributer_details->save();
                }

                $ownerData = User::where('id', $userData->create_by)->first();

                $userDetails = [];
                $userDetails['id'] = isset($userData->id) ? $userData->id : "";
              	$userDetails['main_user_id'] = isset($request->main_user_id) ? (int)$request->main_user_id : $userData->id;
                $userDetails['plan'] = isset($planData) ? $planData : "";
                $userDetails['name'] = isset($userData->name) ?  $userData->name : "";
                $userDetails['register_date'] = isset($userData->created_at) ? date("d-m-Y", strtotime($userData->created_at)) : "";
                $userDetails['iss_audit'] = isset($ownerData->iss_audit) ?  $ownerData->iss_audit : $userData->iss_audit;
                $userDetails['iteam_count'] = isset($ownerData->iteam_count) ?  $ownerData->iteam_count : $userData->iteam_count;
                $userDetails['royalti_amount'] = isset($userData->royalti_amount) ?  $userData->royalti_amount : "";
                $userDetails['royalti_point'] = isset($userData->royalti_point) ?  $userData->royalti_point : "";
                $userDetails['role'] = !empty($userData->create_by) ? 'Staff' : 'Owner';
                $userDetails['email'] = isset($userData->email) ?  $userData->email : "";
                $userDetails['phone_number'] = isset($userData->phone_number) ?  $userData->phone_number : "";
                $userDetails['zip_code'] = isset($userData->zip_code) ?  $userData->zip_code : "";
                $userDetails['referral_code'] = isset($userData->referral_code) ?  $userData->referral_code : "";
                $userDetails['token'] = isset($token) ? $token : "";
                $userDetails['status'] = $reconcilationCount;

                return $this->sendResponse($userDetails, 'Login Successfully.');
            } else {
                return $this->sendError('Please Enter Valid Password');
            }
        } catch (\Exception $e) {
            Log::info("Login api" . $e->getMessage());
            return $e->getMessage();
        }
    }
  
  	public function switchAccountUserList(Request $request)
    {
    	try {
          	$userData = auth()->user();
        	$switch_account_user_list = SwitchAccount::where('device_id',$request->device_id)->get();
          
          	$switchAccountUserDetailList = [];
          	$loginUserCheck = false;
          	if(isset($switch_account_user_list)) {
            	foreach($switch_account_user_list as $key => $list) {
                  	$userList = User::where('phone_number',$list->user_phone_number)->first();
                  	if($list->login_type == 1) {
                    	$loginUserCheck = true;
                    } else {
                    	$loginUserCheck = false;
                    }
                    $switchAccountUserDetailList[$key]['id'] = isset($list->id) ? $list->id : "";
                  	$switchAccountUserDetailList[$key]['name'] = isset($userList->name) ? $userList->name : "";
                  	$switchAccountUserDetailList[$key]['mobile_number'] = isset($list->user_phone_number) ? $list->user_phone_number : "";
                  	$switchAccountUserDetailList[$key]['password'] = isset($list->user_password) ? $list->user_password : "";
                  	$switchAccountUserDetailList[$key]['image'] = isset($userList->pharmacy_logo) ? asset('/pharmacy_logo/'.$userList->pharmacy_logo) : "";
                  	$switchAccountUserDetailList[$key]['login_user_check'] = $loginUserCheck;
                }
            }
          
          	return $this->sendResponse($switchAccountUserDetailList, 'Switch Account Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("Login api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use forgot password
    public function forgotPassword(Request $request)
    {
        try {
            if ((isset($request->type)) && ($request->type == '0')) {

                $validator = Validator::make($request->all(), [
                    'mobile_number' => 'required'
                ], [
                    'mobile_number.required' => 'Enter Mobile Number'
                ]);

                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return $this->sendError($error->first());
                }

                $userData = User::where('phone_number', $request->mobile_number)->first();
                if (empty($userData)) {
                    return $this->sendError('Please Enter Valid Mobile Number');
                }

                $otpData = new OTP;
                $otpData->user_id =  $userData->id;
                $otpData->otp =  rand(100000, 999999);
                $otpData->status =  '0';
                $otpData->save();

                $url = 'https://www.bulksmsplans.com/api/send_sms';

                $response = Http::post($url, [
                    'api_id' => 'APIJ965GnH0110933',
                    'api_password' => 'OCDrtiv2',
                    'sms_type' => 'Transactional',
                    'sms_encoding' => 'text',
                    'sender' => 'HDTSMS',
                    'number' => $userData->phone_number,
                    'message' => 'THIS IS TEST MESSAGE TO START BULK SMS SERVICE WITH {' . $otpData->otp . '} HENCE DIGITAL',
                    'template_id' => '161086',
                ]);

                $message = 'OTP Send Successfully.';
            }
            if ((isset($request->type)) && ($request->type == '1')) {
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required',
                    'otp' => 'required',
                    'password' => 'required'
                ], [
                    'user_id.required' => 'Enter User Id',
                    'otp.required' => 'Enter OTP',
                    'password.required' => 'Enter Passsword'
                ]);

                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return $this->sendError($error->first());
                }

                $userData = User::where('id', $request->user_id)->first();
                if (empty($userData)) {
                    return $this->sendError('Please Enter User Id');
                }
                $userData->password = Hash::make($request->password);
                $userData->update();

                $otpData = OTP::where('user_id', $request->user_id)->where('otp', $request->otp)->first();
                if (empty($otpData)) {
                    return $this->sendError('Please Enter Valid OTP');
                }
                $otpData->status = '1';
                $otpData->update();

                $message = 'Password Forgot Successfully';
            }

            $userDataDetails  = [];
            $userDataDetails['user_id'] = isset($userData->id) ?  $userData->id : "";
            $userDataDetails['otp'] = isset($otpData->otp) ?  $otpData->otp : "";
            return $this->sendResponse($userDataDetails, $message);
        } catch (\Exception $e) {

            Log::info("Forgot Password api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function userLogout(Request $request)
    {
        try {
            $userData = User::where('id', auth()->user()->id)->first();
            if (empty($userData)) {
                return $this->sendError('User not found.');
            }
          
          	if(isset($request->type) && $request->type == 1) {
            	SwitchAccount::where('device_id',$userData->device_id)->update(['login_type' => 2]);
            }

          	$userDeviceToken = UsersDeviceToken::where('user_id',auth()->user()->id)->orderBy('id','DESC')->first();
          	if(isset($userDeviceToken)) {
            	$userDeviceToken->delete();
            }
            // $tokenCheck = ApiToken::where('tokenable_id', $userData->id)->first();

            // if (isset($tokenCheck)) {
            //  	$tokenCheck->delete();
            // }

            return $this->sendResponse([], 'LogOut Successfully.');
        } catch (\Exception $e) {
            Log::info("User Logout api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function privacyPolicy(Request $request)
    {
        $pivacyList = PrivacyPolicy::first();

        $privacyData = [];
        $privacyData['privacy_policy'] = isset($pivacyList->description) ? $pivacyList->description : "";
        return $this->sendResponse($privacyData, 'Data Fetch Successfully');
    }

    // this function use logout
    public function logout(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ], [
                'user_id.required' => 'Enter User Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $userData = User::where('id', $request->user_id)->first();
            if (empty($userData)) {
                return $this->sendError('Please Enter User Id');
            }
          
          	$userDeviceToken = UsersDeviceToken::where('user_id',$request->user_id)->orderBy('id','DESC')->first();
          	if(isset($userDeviceToken)) {
            	$userDeviceToken->delete();
            }
            // $tokenCheck = ApiToken::where('tokenable_id', $userData->id)->first();
            // if (isset($tokenCheck)) {
            //    $tokenCheck->delete();
            // }

            return $this->sendResponse([], 'Log Out Successfully');
        } catch (\Exception $e) {
            Log::info("Forgot Password api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function permissionList(Request $request)
    {
        try {
            $frontPermissions = FrontPermissions::get();
            $permissionData = [];

            if (isset($frontPermissions)) {
                foreach ($frontPermissions as $listData) {
                    $permission = isset($listData->permissions) ? $listData->permissions : "";

                    // Extract the key part before the last underscore
                    $key = substr($permission, 0, strrpos($permission, '_'));
                    $key = str_replace('_', ' ', $key);
                    if (!isset($permissionData[$key])) {
                        $permissionData[$key] = [];
                    }
                    $permission = str_replace('_', ' ', $permission);
                    $permissionData[$key][] = $permission;
                }
            }

            return $this->sendResponse($permissionData, 'Log Out Successfully');
        } catch (\Exception $e) {
            Log::info("Permission API: " . $e->getMessage());
            return $e->getMessage();
        }
    }
  
  	public function versionCodeData(Request $request)
    {
    	$web_settings_data = WebSettings::where('type',1)->first();

        $webSettingsData = [];

        if (isset($web_settings_data)) {
          $webSettingsData = [
            'android_version_type' => $web_settings_data->android_version_type,
            'ios_version_type' => $web_settings_data->ios_version_type,
            'android_version_code' => $web_settings_data->android_version_code,
            'ios_version_code' => $web_settings_data->ios_version_code,
            'playstore_link' => $web_settings_data->playstore_link,
            'appstore_link' => $web_settings_data->appstore_link,
          ];
          return $this->sendResponse([$webSettingsData], 'Version Code Data Fetch Successfully.');
        }
    }
  
  	public function deleteAccountData()
    {
    	$user_data = auth()->user();
      	$pharma_shop_data = PharmaShop::where('user_id',auth()->user()->id)->first();
      	if(isset($pharma_shop_data)) {
        	$pharma_shop_data->delete();
        }
      	$switch_account_data = SwitchAccount::where('user_id',auth()->user()->id)->where('device_id',auth()->user()->device_id)->first();
      	if(isset($switch_account_data)) {
        	$switch_account_data->delete();
        }
        $user_data->delete();

        return $this->sendResponse([], 'Account Deleted Successfully.');
    }

    public function notificationData(Request $request)
    {
        $notification = NotificationModel::where('user_id', auth()->user()->id)->get();

        $userDetails = [];
        if (isset($notification)) {
            foreach ($notification as $key => $list) {
                $userDetails[$key]['id'] = isset($list->id) ? $list->id : "";
                $userDetails[$key]['title'] = isset($list->title) ?  $list->title : "";
                $userDetails[$key]['description'] = isset($list->description) ?  $list->description : "";
                $userDetails[$key]['date'] = isset($list->date) ?  $list->date : "";
            }
        }
        return $this->sendResponse($userDetails, 'Data Fetch Successfully.');
    }
  
  	public function chemistReferralList()
    {
      	$chemistReferralCode = auth()->user()->user_referral_code;
        $userReferralList = User::where('referral_code', $chemistReferralCode)->get();

        $userReferralDetails = [];
        $userReferralDetails['referral_code'] = $chemistReferralCode;
      	$userReferralDetails['refer_image'] = asset('/public/chemist-refer-image.png');
      	$userReferralDetails['message'] = "🎉 Invite Your Chemist Friends!
Earn PharmaCoins every time you refer.
✅ You: Earn 50% PharmaCoins
✅ Your Friend: Gets 15% PharmaCoins
💡 More chemist friends = More PharmaCoins = More Savings!
👉 Download now with my link: 'https://play.google.com/store/apps/details?id=com.pharma.chemistapp&hl=en_IN'

🔑 Use Your Referral Code: $chemistReferralCode

📲 Start inviting today & grow your PharmaCoin wallet!";
        $userReferralDetails['referral_list'] = [];

        if ($userReferralList->isNotEmpty()) {
            foreach ($userReferralList as $list) {
                $userReferralDetails['referral_list'][] = [
                    'id' => isset($list->id) ? (string) $list->id : "",
                    'name' => isset($list->name) ? $list->name : "",
                  	'phone_number' => isset($list->phone_number) ? $list->phone_number : "",
                  	'created_at' => isset($list->created_at) ? Carbon::parse($list->created_at)->format('d M Y') : "",
                ];
            }
        }

        return $this->sendResponse($userReferralDetails, 'Chemist Referral Data Fetch Successfully.');
    }
  
  	public function customerReferralList()
    {
    	$chemistReferralCode = auth()->user()->user_referral_code;
      	$customerReferralList = PatientsModel::where('referral_code',auth()->user()->user_referral_code)->get();
      
      	$customerReferralDetails = [];
      	$customerReferralDetails['referral_code'] = $chemistReferralCode;
      	$customerReferralDetails['refer_image'] = asset('/public/customer-refer-image.jpg');
      	$customerReferralDetails['message'] = "📢 Download Patient App Today!
Use my link & set me as your preferred pharmacy 🏪
✅ Pill Reminders – Never miss a dose
✅ Refill Reminders – Get alerts before medicines finish
✅ Easy Reorder – 1-Click repeat orders
✅ Home Delivery – Fast & reliable

📲 Available on Android & iOS.
👉 Download now with my link: 'https://play.google.com/store/apps/details?id=com.pharma.patientapp&hl=en_IN'
🔑 Use Referral Code: $chemistReferralCode

Stay healthy, stay connected with your trusted chemist 💊.";
      	$customerReferralDetails['referral_list'] = [];
      
      	if ($customerReferralList->isNotEmpty()) {
        	foreach($customerReferralList as $list) {
            	$customerReferralDetails['referral_list'][] = [
                	'id' => isset($list->id) ? (string) $list->id : "",
                  	'name' => isset($list->first_name) ? $list->first_name : "",
                  	'phone_number' => isset($list->mobile_number) ? $list->mobile_number : "",
                  	'created_at' => isset($list->created_at) ? Carbon::parse($list->created_at)->format('d M Y') : "",
                ];
            }
        }
      
      	return $this->sendResponse($customerReferralDetails, 'Customer Referral Data Fetch Successfully.');
    }
}
