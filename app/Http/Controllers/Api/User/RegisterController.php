<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\PharmaShop;
use App\Models\OTP;
use Illuminate\Support\Facades\Http;
use App\Models\LogsModel;
use App\Models\Distributer;
use App\Models\SwitchAccount;
use Illuminate\Support\Str;

class RegisterController extends ResponseController
{
    // this function use regitsre api
    public function regsiterCreate(Request $request)
    {
        try {
            $message = null;
            if ((isset($request->type)) && ($request->type == '0')) {
                $validator = Validator::make($request->all(), [
                    'pharmacy_name' => 'required',
                    'mobile_number' => 'required',
                    'email' => 'required',
                    'zip_code' => 'required'
                ], [
                    'pharmacy_name.required' => 'Enter Pharmacy Name',
                    'mobile_number.required' => 'Enter Mobile Number',
                    'email.required' => 'Enter Email',
                    'zip_code.required' => 'Enter Zip Code'
                ]);

                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return $this->sendError($error->first());
                }

                //  $mobileNumberCheck = User::where('phone_number', $request->mobile_number)->first();
                //  if (!empty($mobileNumberCheck)) {
                //    	return $this->sendError('Mobile Number Already Exist.');
                //  }
                $pharmaUser = User::withTrashed()->where('phone_number', $request->mobile_number)->first();
                if ($pharmaUser) {
                    $pharmaUser->name = $request->pharmacy_name;
                    $pharmaUser->email = $request->email;
                    $pharmaUser->phone_number = $request->mobile_number;
                    $pharmaUser->zip_code = $request->zip_code;
                    $pharmaUser->deleted_at = null;
                    $pharmaUser->update();

                    $pharmaUserData = PharmaShop::withTrashed()->where('user_id', $pharmaUser->id)->first();
                    $pharmaUserData->pharma_name = $request->pharmacy_name;
                    $pharmaUserData->pharma_short_name = $request->pharmacy_name;
                    $pharmaUserData->pharma_email = $request->email;
                    $pharmaUserData->pharma_phone_number = $request->mobile_number;
                    $pharmaUserData->deleted_at = null;
                    $pharmaUserData->update();

                    $otpData = new OTP;
                    $otpData->user_id = $pharmaUser->id;
                    $otpData->otp =  rand(100000, 999999);
                    $otpData->status = '0';
                    $otpData->save();
                } else {
                    $emailCheck = User::where('email', $request->email)->first();
                    if (!empty($emailCheck)) {
                        return $this->sendError('Email Already Exist.');
                    }

                    $pharmaUser = new User;
                    $pharmaUser->email = $request->email;
                    $pharmaUser->name = $request->pharmacy_name;
                    $pharmaUser->password = "";
                    $pharmaUser->phone_number = $request->mobile_number;
                    $pharmaUser->referral_code = $request->referral_code;
                    $pharmaUser->zip_code = $request->zip_code;
                    $pharmaUser->latitude = $request->latitude;
                    $pharmaUser->longitude = $request->longitude;
                    $pharmaUser->status = '1';
                    $pharmaUser->role = '1';
                    $pharmaUser->save();

                    $pharmaUserData = new PharmaShop;
                    $pharmaUserData->user_id = $pharmaUser->id;
                    $pharmaUserData->pharma_name = $request->pharmacy_name;
                    $pharmaUserData->pharma_short_name = $request->pharmacy_name;
                    $pharmaUserData->pharma_email = $request->email;
                    $pharmaUserData->pharma_phone_number = $request->mobile_number;
                    $pharmaUserData->total_user = '1';
                    $pharmaUserData->save();
                  
                  	$distributerAlreadyExist = Distributer::where('user_id',$pharmaUser->id)->where('name','OPENING DISTRIBUTOR')->where('phone_number','4242424242')->first();
                  	if(empty($distributerAlreadyExist)) {
                    	$distributer_details = new Distributer;
                        $distributer_details->name = 'OPENING DISTRIBUTOR';
                        $distributer_details->email = 'opening@gmail.com';
                        $distributer_details->phone_number = '4242424242';
                        $distributer_details->user_id = $pharmaUser->id;
                        $distributer_details->status = '1';
                        $distributer_details->role = '4';
                        $distributer_details->save();
                    }

                    $otpData = new OTP;
                    $otpData->user_id =  $pharmaUser->id;
                    $otpData->otp = rand(100000, 999999);
                    $otpData->status =  '0';
                    $otpData->save();
                }

                $url = 'https://web.wabridge.com/api/createmessage';

                $payload = [
                    'app-key'       => 'db8ce965-029b-4f74-aade-04d137663b12',
                    'auth-key'      => '039d46d11eab7e7863eb651db09f8eac63198154bf41302430',
                    'destination_number' => $request->mobile_number,
                    'template_id'   => '1104412667789956',
                    'device_id'     => '6747f73e1bcbc646dbdc8c5f',
                    'variables'     => [
                        $pharmaUserData->pharma_name,
                        $otpData->otp
                    ],
                    'media'         => '',
                    'message'       => '', // Leave blank if using a template message
                ];

                // Send the request using Laravel HTTP client
                $response = Http::post($url, $payload);

                // https://www.bulksmsplans.com/api/send_sms?api_id=APIJ965GnH0110933&api_password=OCDrtiv2&sms_type=Transactional&sms_encoding=text&sender=HDTSMS&number=7082439354&message=THIS%20IS%20TEST%20MESSAGE%20TO%20START%20BULK%20SMS%20SERVICE%20WITH%20{5080}%20HENCE%20DIGITAL&template_id=161086
                // $url = 'https://www.bulksmsplans.com/api/send_sms';

                // $response = Http::post($url, [
                // 'api_id' => 'APIJ965GnH0110933',
                // 'api_password' => 'OCDrtiv2',
                //'sms_type' => 'Transactional',
                //'sms_encoding' => 'text',
                //'sender' => 'HDTSMS',
                //'number' => $request->mobile_number,
                //'message' => 'THIS IS TEST MESSAGE TO START BULK SMS SERVICE WITH {'.$otpData->otp.'} HENCE DIGITAL',
                //'template_id' => '161086',
                // ]);

                $message = 'OTP Send Successfully.';
            }

            if ((isset($request->type)) && ($request->type == '1')) {
                if (empty($request->password)) {
                    $userData = User::where('id', $request->user_id)->first();
                    if (isset($userData)) {
                        $userData->delete();
                    }
                }

                $validator = Validator::make($request->all(), [
                    'user_id' => 'required',
                    'otp' => 'required',
                    'password' => 'required'
                ], [
                    'user_id.required' => 'Enter User Id',
                    'otp.required' => 'Enter OTP',
                    'password.required' => 'Enter Password'
                ]);

                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return $this->sendError($error->first());
                }

                $userCheck  = User::where('id', $request->user_id)->first();
                if (empty($userCheck)) {
                    return $this->sendError('Please Enter Valid User Id');
                }

                $userId = OTP::where('otp', $request->otp)->where('user_id', $request->user_id)->first();
                if (empty($userId)) {
                    return $this->sendError('Please Enter Valid OTP');
                }
                $userId->status = '1';
                $userId->update();

                $userData = User::find($request->user_id);

                // $userData->user_referral_code = substr(md5(mt_rand()), 0, 7);
              	$userData->user_referral_code = Str::upper(Str::random(3)) . rand(100, 999);
                $userData->password = Hash::make($request->password);
                $userData->update();

                if (isset($request->referral_code)) {
                    $userLogs = new LogsModel;
                    $userLogs->message = 'Referral Code' . $request->referral_code . ' use And Regsiter' . $request->pharmacy_name;
                    $userLogs->user_id = auth()->user()->id;
                    $userLogs->date_time = date('Y-m-d H:i a');
                    $userLogs->save();
                }

                $pharmaUser = User::where('id', $request->user_id)->first();
                $message = 'Registration Successfully.';
            }

            $userData  = [];
            $userData['id'] = isset($pharmaUser->id) ? $pharmaUser->id : "";
            $userData['name'] = isset($pharmaUser->name) ?  $pharmaUser->name : "";
            // $userData['otp'] = isset( $otpData->otp) ?  $otpData->otp : "";
            $userData['email'] = isset($pharmaUser->email) ?  $pharmaUser->email : "";
            $userData['phone_number'] = isset($pharmaUser->phone_number) ?  $pharmaUser->phone_number : "";
            $userData['zip_code'] = isset($pharmaUser->zip_code) ?  $pharmaUser->zip_code : "";
          	
            return $this->sendResponse($userData, $message);
        } catch (\Exception $e) {
            Log::info("Register api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function otpResend(Request $request)
    {
        try {
            $userData = User::where('phone_number', $request->mobile_number)->first();
            if (empty($userData)) {
                return $this->sendError('Invalid Mobile Number');
            }
            $otpData = OTP::where('user_id', $request->user_id)->first();
            if (isset($otpData)) {
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
                    $userData->name,
                    $otpData->otp
                ],
                'media'         => '',
                'message'       => '', // Leave blank if using a template message
            ];

            // Send the request using Laravel HTTP client
            $response = Http::post($url, $payload);

            // $url = 'https://www.bulksmsplans.com/api/send_sms';

            // $response = Http::post($url, [
            //    'api_id' => 'APIJ965GnH0110933',
            //   'api_password' => 'OCDrtiv2',
            //  'sms_type' => 'Transactional',
            //   'sms_encoding' => 'text',
            //   'sender' => 'HDTSMS',
            //   'number' => $userData->phone_number,
            //   'message' => 'THIS IS TEST MESSAGE TO START BULK SMS SERVICE WITH {'.$otpData->otp.'} HENCE DIGITAL',
            // 'template_id' => '161086',
            //  ]);

            $message = 'OTP Send Successfully';

            $userDataDetails  = [];
            $userDataDetails['user_id'] = isset($userData->id) ?  $userData->id : "";
            $userDataDetails['otp'] = isset($otpData->otp) ?  $otpData->otp : "";
            return $this->sendResponse($userDataDetails, $message);
        } catch (\Exception $e) {
            Log::info("Register api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
