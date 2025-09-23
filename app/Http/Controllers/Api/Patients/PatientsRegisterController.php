<?php

namespace App\Http\Controllers\Api\Patients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientsModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use Illuminate\Support\Facades\Http;
use App\Models\OTP;
use App\Models\LogsModel;
use Illuminate\Support\Facades\Hash;
use App\Models\ApiToken;
use App\Models\PatientsOrder;
use App\Models\AddCart;
use App\Models\PatientsFamilyModel;
use App\Models\PatientsAddress;
use App\Models\PatientsDeviceToken;
use App\Models\WebSettings;
use App\Models\User;

class PatientsRegisterController extends ResponseController
{
   	public function patientRegister(Request $request)
    {
        try {
            $message = null;
            $patientNew = null;

            if (isset($request->type) && $request->type == '0') {
                $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'mobile_number' => 'required',
                    'pin_code' => 'required',
                    'password' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',
                ], [
                    'first_name.required' => 'Enter First Name',
                    'last_name.required' => 'Enter Last Name',
                    'mobile_number.required' => 'Enter Mobile Number',
                    'pin_code.required' => 'Enter Pin Code',
                    'password.required' => 'Enter Password',
                    'latitude.required' => 'Enter Latitude',
                    'longitude.required' => 'Enter Longitude',
                ]);

                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first());
                }

                $patientNew = PatientsModel::onlyTrashed()
                    ->where('mobile_number', $request->mobile_number)
                    ->first();

                if ($patientNew) {
                    // Restore soft-deleted patient
                  	$patientNew->first_name = $request->first_name;
                    $patientNew->last_name = $request->last_name;
                    $patientNew->mobile_number = $request->mobile_number;
                    $patientNew->pin_code = $request->pin_code;
                  	if ($request->password) {
                  		$patientNew->user_password = $request->password;
                        $patientNew->password = Hash::make($request->password);
                    }
                  	$patientNew->referral_code = $request->referral_code;
                    $patientNew->latitude = $request->latitude;
                    $patientNew->longitude = $request->longitude;
                  	$patientNew->deleted_at = null;
                    $patientNew->update();
                  
                  	if ($request->referral_code) {
                        $log = new LogsModel;
                        $log->message = 'Referral Code ' . $request->referral_code . ' used by ' . $request->first_name;
                        $log->user_id = $patientNew->id;
                        $log->date_time = date('Y-m-d H:i a');
                        $log->save();
                    }
                  
                  	$otpData = new OTP;
                    $otpData->user_id = $patientNew->id;
                    $otpData->otp = rand(100000, 999999);
                    $otpData->status = '0';
                    $otpData->save();

                    // Send OTP
                    $payload = [
                        'app-key' => 'db8ce965-029b-4f74-aade-04d137663b12',
                        'auth-key' => '039d46d11eab7e7863eb651db09f8eac63198154bf41302430',
                        'destination_number' => $request->mobile_number,
                        'template_id' => '1104412667789956',
                        'device_id' => '6747f73e1bcbc646dbdc8c5f',
                        'variables' => [
                            $patientNew->first_name,
                            $otpData->otp
                        ],
                        'media' => '',
                        'message' => '',
                    ];

                    Http::post('https://web.wabridge.com/api/createmessage', $payload);

                    // Restore related data
                  	
                    // PatientsOrder::onlyTrashed()->where('patient_id', $patientNew->id)->restore();
                    // AddCart::onlyTrashed()->where('patient', $patientNew->id)->restore();
                    // PatientsFamilyModel::onlyTrashed()->where('patients_id', $patientNew->id)->restore();
                    // PatientsAddress::onlyTrashed()->where('patient_id', $patientNew->id)->restore();

                    $message = 'Registration Successfully.';
                } else {
                    // New Registration
                    $existingPatient = PatientsModel::where('mobile_number', $request->mobile_number)->first();
                    if ($existingPatient) {
                        return $this->sendError('Mobile Number Already Exist.');
                    }

                    $patientNew = new PatientsModel;
                    $patientNew->first_name = $request->first_name;
                    $patientNew->last_name = $request->last_name;
                    $patientNew->mobile_number = $request->mobile_number;
                    $patientNew->pin_code = $request->pin_code;
                  	$patientNew->user_password = $request->password;
                    $patientNew->password = Hash::make($request->password);
                  	if(isset($request->referral_code)) {
                    	$patientNew->referral_code = $request->referral_code;
                      	$chemistData = User::where('user_referral_code',$request->referral_code)->first();
                      	if(isset($chemistData)) {
                        	$patientNew->your_chemist = $chemistData->id;
                        } else {
                        	$patientNew->your_chemist = null;
                        }
                    } else {
                    	$patientNew->referral_code = null;
                    }
                    $patientNew->latitude = $request->latitude;
                    $patientNew->longitude = $request->longitude;
                    $patientNew->save();

                    if ($request->referral_code) {
                        $log = new LogsModel;
                        $log->message = 'Referral Code ' . $request->referral_code . ' used by ' . $request->first_name;
                        $log->user_id = $patientNew->id;
                        $log->date_time = date('Y-m-d H:i a');
                        $log->save();
                    }

                    // Create OTP
                    $otpData = new OTP;
                    $otpData->user_id = $patientNew->id;
                    $otpData->otp = rand(100000, 999999);
                    $otpData->status = '0';
                    $otpData->save();

                    // Send OTP
                    $payload = [
                        'app-key' => 'db8ce965-029b-4f74-aade-04d137663b12',
                        'auth-key' => '039d46d11eab7e7863eb651db09f8eac63198154bf41302430',
                        'destination_number' => $request->mobile_number,
                        'template_id' => '1104412667789956',
                        'device_id' => '6747f73e1bcbc646dbdc8c5f',
                        'variables' => [
                            $patientNew->first_name,
                            $otpData->otp
                        ],
                        'media' => '',
                        'message' => '',
                    ];

                    Http::post('https://web.wabridge.com/api/createmessage', $payload);
                    $message = 'OTP Sent Successfully.';
                }
            }

            if (isset($request->type) && $request->type == '1') {
                // Type 1: OTP Verification

                $validator = Validator::make($request->all(), [
                    'user_id' => 'required',
                    'otp' => 'required',
                ], [
                    'user_id.required' => 'Enter User Id',
                    'otp.required' => 'Enter OTP',
                ]);

                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first());
                }

                $patientNew = PatientsModel::find($request->user_id);
                if (!$patientNew) {
                    return $this->sendError('Please Enter Valid User Id');
                }

                $otpRecord = OTP::where('user_id', $request->user_id)
                    ->where('otp', $request->otp)
                    ->first();

                if (!$otpRecord) {
                    return $this->sendError('Please Enter Valid OTP');
                }

                $otpRecord->status = '1';
                $otpRecord->save();

                $message = 'Registration Successfully.';
            }

            // Prepare response
            $userData = [
                'id' => $patientNew->id ?? '',
                'first_name' => $patientNew->first_name ?? '',
                'last_name' => $patientNew->last_name ?? '',
                'mobile_number' => $patientNew->mobile_number ?? '',
                'pin_code' => $patientNew->pin_code ?? '',
            ];

            return $this->sendResponse($userData, $message);
        } catch (\Exception $e) {
            Log::error("Register API Error: " . $e->getMessage());
            return $this->sendError("Something went wrong, please try again.");
        }
    }
  
    public function patientResendOtp(Request $request)
    {
          try{
            $userData = PatientsModel::where('mobile_number', $request->mobile_number)->first();
            if(empty($userData))
            {
              return $this->sendError('Invalid Mobile Number');
            }
            $otpData = OTP::where('user_id',$request->user_id)->first();
            if(isset($otpData))
            {
              $otpData->delete();
            }

            $otpData = new OTP;
            $otpData->user_id =  $userData->id;
            $otpData->otp =  rand(100000, 999999);
            $otpData->status =  '0';
            $otpData->save();

            $url = 'https://web.wabridge.com/api/createmessage';

            $payload = [
              'app-key'       => 'db8ce965-029b-4f74-aade-04d137663b12',
              'auth-key'      => '039d46d11eab7e7863eb651db09f8eac63198154bf41302430',
              'destination_number' => $request->mobile_number, 
              'template_id'   => '1104412667789956', 
              'device_id'     => '6747f73e1bcbc646dbdc8c5f', 
              'variables'     => [
                $userData->first_name, 
                $otpData->otp                                 
              ],
              'media'         => '',
              'message'       => '', // Leave blank if using a template message
            ];

            // Send the request using Laravel HTTP client
            $response = Http::post($url, $payload);

            $message = 'OTP Send Successfully';

            $userDataDetails  = [];
            $userDataDetails['user_id'] = isset($userData->id) ?  $userData->id : "";
            $userDataDetails['otp'] = isset($otpData->otp) ?  $otpData->otp : "";
            return $this->sendResponse($userDataDetails, $message);
            
           } catch (\Exception $e) {
            Log::info("Resend OTO api" . $e->getMessage());
            return $e->getMessage();
        }
    }
  
    public function patientforgotPassword(Request $request)
    {
      try{
          $message = '';
          if((isset($request->type)) && ($request->type == '0'))
                {
                    $validator = Validator::make($request->all(), [
                        'mobile_number' => 'required'
                    ], [
                        'mobile_number.required' => 'Enter Mobile Number'
                    ]);
        
                    if ($validator->fails()) {
                        $error = $validator->getMessageBag();
                        return $this->sendError($error->first());
                    }

                    $userData = PatientsModel::where('mobile_number', $request->mobile_number)->first();
                    if (empty($userData)) {
                        return $this->sendError('Please Enter Valid Mobile Number');
                    }

                    $otpData = new OTP;
                    $otpData->user_id =  $userData->id;
                    $otpData->otp =  rand(100000, 999999);
                    $otpData->status =  '0';
                    $otpData->save();

                    $url = 'https://web.wabridge.com/api/createmessage';

                    $payload = [
                        'app-key'       => 'db8ce965-029b-4f74-aade-04d137663b12',
                        'auth-key'      => '039d46d11eab7e7863eb651db09f8eac63198154bf41302430',
                        'destination_number' => $request->mobile_number, 
                        'template_id'   => '1104412667789956', 
                        'device_id'     => '6747f73e1bcbc646dbdc8c5f', 
                        'variables'     => [
                            $userData->first_name, 
                            $otpData->otp                                 
                        ],
                        'media'         => '',
                        'message'       => '', // Leave blank if using a template message
                    ];

                    // Send the request using Laravel HTTP client
                    $response = Http::post($url, $payload);
    
                    $message = 'OTP Send Successfully.';
                }
                if((isset($request->type)) && ($request->type == '1'))
                {
                    $validator = Validator::make($request->all(), [
                        'user_id'=>'required',
                        'otp' => 'required',
                        'password'=>'required'
                    ], [
                        'user_id.required' => 'Enter User Id',
                        'otp.required' => 'Enter OTP',
                        'password.required'=>'Enter Passsword'
                    ]);
        
                    if ($validator->fails()) {
                        $error = $validator->getMessageBag();
                        return $this->sendError($error->first());
                    }

                    $userData = PatientsModel::where('id', $request->user_id)->first();
                    if (empty($userData)) {
                        return $this->sendError('Please Enter User Id');
                    }
                    $userData->password = Hash::make($request->password);
                    $userData->update();

                    $otpData = OTP::where('user_id',$request->user_id)->where('otp',$request->otp)->first();
                    if(empty($otpData))
                    {
                      return $this->sendError('Please Enter Valid OTP');
                    }
                    $otpData->status = '1';
                    $otpData->update();

                    $message = 'Password Forgot Successfully.';
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
  
    public function patientLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
              'mobile_number' => 'required',
              'password' => 'required'
            ], [
              'mobile_number.required' => 'Enter Mobile Number',
              'password.required' => 'Enter Password'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }
          
           	$userData = PatientsModel::where('mobile_number', $request->mobile_number)->first();
            if (empty($userData)) {
                return $this->sendError('Mobile Number invalid');
            }
            
            // $userData = PatientsModel::where('mobile_number', $request->mobile_number)->first();
         
            if (empty($userData)) {
                return $this->sendError('You Are Unauthorized');
            }

            if (Hash::check($request->password, $userData->password)) {
              	if ($request->token_id) {
                	$patientToken = PatientsDeviceToken::where('user_id', $userData->id)->where('token_id', $request->token_id)->first();
                  	if (!$patientToken)
                    {
                 		$patientDeviceTokenStoreData = new PatientsDeviceToken();
                        $patientDeviceTokenStoreData->user_id = $userData->id;
                        $patientDeviceTokenStoreData->token_id = $request->token_id;
                        $patientDeviceTokenStoreData->firebase_type = $request->firebase_type;
                        $patientDeviceTokenStoreData->save();
                    }
                }
              
              	$userData->user_password = $request->password;
              	$userData->update();
              
                // $tokenCheck = ApiToken::where('tokenable_id', $userData->id)->first();
             
                $token = $userData->createToken($userData->first_name)->plainTextToken;
             
                $userDetails = [];
                $userDetails['id'] = isset($userData->id) ?  $userData->id : "";
                $userDetails['first_name'] = isset($userData->first_name) ?  $userData->first_name : "";
                $userDetails['last_name'] = isset($userData->last_name) ?  $userData->last_name : "";
                $userDetails['mobile_number'] = isset($userData->mobile_number) ?  $userData->mobile_number : "";
                $userDetails['pin_code'] = isset($userData->pin_code) ?  $userData->pin_code : "";
                $userDetails['token'] = isset($token) ?  $token : "";
              
                return $this->sendResponse($userDetails, 'Login Successfully.');
            } else {
                return $this->sendError('Please Enter Valid Password');
            }
          
           } catch (\Exception $e) {
            Log::info("Patient Login api" . $e->getMessage());
            return $e->getMessage();
      	}
    }
  
  	public function patientVersionCodeData(Request $request)
    {
    	$web_settings_data = WebSettings::where('type', 2)->first();

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
  
  	public function patientDeleteAccountData()
    {
    	$user_data = auth()->user();
		
      	$user_data->delete();

        return $this->sendResponse([], 'Patient Deleted Successfully.');
    }
}
