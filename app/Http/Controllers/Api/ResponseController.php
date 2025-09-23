<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiToken;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResponseController extends Controller
{
    public function sendResponse($result, $message)
    {
    	$response = [
            'status'=> 200,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error)
    {
    	$response = [
            'status'=> 400,
            'message' => $error,
        ];

        return response()->json($response, 400);
    }
  
  	public function patient_notification($title, $message, $userId)
    {
        $user = DB::table('patients_device_token')
            ->select('*')
            ->where('token_id', '!=', '')
            ->where('user_id', $userId)
            ->latest('id') // Assuming `id` is an auto-increment column
            ->first();

        $this->createToken();

        $token = DB::table('api_token')->where('id', 1)->first();

        // Convert the token to an array if it's not null
        $tokenArray = $token ? (array) $token : [];
        $url = 'https://fcm.googleapis.com/v1/projects/' . $tokenArray['project_id'] . '/messages:send';
        $accessToken = $tokenArray['token'];

        //foreach ($users as $user) {
        if ($user->token_id) {

            $data = [
                'message' => [
                    'token' => $user->token_id,
                    'notification' => [
                        'title' => $title,
                        'body' => $message,
                    ],
                ],
            ];

            $jsonData = json_encode($data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
        }
        // }

        return true;
    }

    function createToken()
    {
        // Path to your service account key file
        $serviceAccountPath = '{
            "type": "service_account",
            "project_id": "pharmapatientapp",
            "private_key_id": "109232149c34045ba5ae71095673252f4a3e9cd2",
            "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCPvdZ6c+QfO0yd\nkKKjj+gjaum1txFPmRZJvCoO9nVH9EFZptdmy0rKl+/7K+p8BJ9ta+jkKisaTi1C\nRBoeVCMjh2NX/mM9SX/eJj54Ar9hnWbNPAd5YdpJ+K/bV3Bce9m4ZsW3Ttbp0jZb\nPfcywJGYrIxbNzu3tlnO5rOqxBq9xUZFQDm9wB00QbK6avuW9BDZGMWcdUqaWOVi\nsuF6U1UjZarbpfRhn/ZDItCBzn0JI7tQe77AlPxBUAF8MB2MeWvF1Hmp8Mlxnvbj\ntU9Hx7/Q9Muz3E2R+zNUdHBi/KpoJGNM5qsgvi3By7nOypsYhgs8QFRS1sNfA2av\nd5eIWmh/AgMBAAECggEAIG7py6SFpyRms7DUecp5SzCO/mEJx2eX91g9NzYWHX+4\nFSYHuVqKjC7/R7HPjV8vp+1u1bjME/nqzWZ3bDt42EXuvQaZ1tTURMhlVQftrfVp\nokp2t0VEj2dNKtVOdZe+dxS9bRCdfpHfkPPo6Ks5zvS0EMrkLx42onA8vv3TuNjJ\nYnUejMxWjeoFXbp/9TkBvtubfvo/g9FqDHekWzEJ5V5f198gdhIv1Rbk9jdRDJlQ\nF1uBhd+fJzjpIFv5vGfD/1Qc3/giprASNT3v7qf3/zs0i5AvmflpqGpTqDf0YzUA\neaqQGYwQ5IGLy4cZQxFwrvGqSP18/kLmESJkFfR1FQKBgQC/66ZNbBh83PojnEeX\nsXSQbKeRBSbszki6EsBas149FMgTwZKwo0NHpgjR+EuMhdcZB9U5TjsF3W9GuNu2\ndbeUZRvsgDL6m3SyvW1gfYI8lCiyfctBDLH5drp2HBbnaWMrhvMkExI46AYQ8u11\nqn6MVMiMYE/p6lQ51nk5BiSb3QKBgQC/vBsjn5GrMO6swFylaxGbLECNhVl0bmpi\nR5mHaXUhpA5ZBpUiBD0xgRqvlZB9egXF38gm2QjY1S7prHNCcQ5ezqz3WlpmnP0X\nsh2RRzafKrPq5+QAHSeT+kYrFk0PYZTuSjazrEfXuNz7ca7bl3HNybYZmKodNdgI\nxxXOQn4uCwKBgAHUjHx2iHFAZj5KskAXGCZ8csimRO3DNdxYa28yALcSaKoPkYeP\ntweK5NCjzoyjhh6d/YGTZmqy5unEv4uo6bxHjpGd016iduVxewaNi91qCE1Td8nC\nBjx7zmFr+Sfx0FlM6yqv59+VDuHAf1U88AIEmPkFvh1b97upePR7Q4QdAoGAInFr\nOqoGpAclSRlBS2IBhqubaRDkcfsH12HfXaj/Jqzu+uUo0zSJvaPgpFws2a2a7eH2\ndawVX8ZkKAwXpH7kwvlDryenB7n56VDQ6PEvCcbFDVTc63xRSM7z3feinjm8ZFYm\nn346ZbFK2UyhycbZ5crvkIeRP7AVf/Yrn6LoQeMCgYB3LQN6wh4rJsI5QlUWnjHx\n6tuaLeRHseamaeszwkgOqE+bRBF3b4wAIefOl82z3nthSKdDte3hC/lNaiPztcQT\noJyHNBHXHFqPt3BZFT+0byAI8IHGVH3JFDJITCuMERmNvHVnOafxLy9JQxYs+G5Y\n4jAMO4j3nQQENPgv14Lowg==\n-----END PRIVATE KEY-----\n",
            "client_email": "firebase-adminsdk-fbsvc@pharmapatientapp.iam.gserviceaccount.com",
            "client_id": "101483382114899757353",
            "auth_uri": "https://accounts.google.com/o/oauth2/auth",
            "token_uri": "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fbsvc%40pharmapatientapp.iam.gserviceaccount.com",
            "universe_domain": "googleapis.com"
            }';

        // Define the required scopes
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        try {
            $jwt = $this->createJwt($serviceAccountPath, $scopes);
            $accessToken = $this->getAccessToken($jwt);

            // Update token
            date_default_timezone_set("Asia/Kolkata");

            $currentTime = Carbon::now();
            $apiToken = ApiToken::where('id',1)->first();
            $apiToken->token = $accessToken;
            $apiToken->time = $currentTime->format('H:i:s');
            $apiToken->update();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        }
    }
  
  	public function chemist_notification($title, $message, $userId)
    {
        $user = DB::table('users_device_token')
            ->select('*')
            ->where('token_id', '!=', '')
            ->where('user_id', $userId)
            ->latest('id') // Assuming `id` is an auto-increment column
            ->first();

        $this->createChemistToken();

        $token = DB::table('api_token')->where('id', 2)->first();

        // Convert the token to an array if it's not null
        $tokenArray = $token ? (array) $token : [];
        $url = 'https://fcm.googleapis.com/v1/projects/' . $tokenArray['project_id'] . '/messages:send';
        $accessToken = $tokenArray['token'];
        //foreach ($users as $user) {
        if ($user->token_id) {

            $data = [
                'message' => [
                    'token' => $user->token_id,
                    'notification' => [
                        'title' => $title,
                        'body' => $message,
                    ],
                ],
            ];

            $jsonData = json_encode($data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
        }
        // }

        return true;
    }
  
  	function createChemistToken()
    {
        // Path to your service account key file
        $serviceAccountPath = '{
            "type": "service_account",
            "project_id": "pharmachemistapp",
            "private_key_id": "659cb08467e6476fdab5803b01fae30e1fb864f7",
            "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCkfexEXK44e1Em\nX/hRmL52FJalJJH8HWutp/9HHkYAQIIF5mVRmSMNri4HR/NkWtGEpOYJwFObYhav\no3f9cAnIuykb+WbBWub1EElyfW14AL0HZ7HhennioP7wwMsRj7rMHM7f2TcgtnF/\nbyv63M0V55e1kEJfqHB0bOZFkF1VETP0D4FSUywPMzsR97vrBWyZGVNpyUXvX0Nu\nr8h2JuANuPTPeUbk/T49ynU6n8e6Hrhh7ZHNik3JQPhRygvjOO9ccodiAMw/inxu\n6ZNfJ9htNaKAPYC9qqjyvHkmAHnnDOIrYSPNKoiEvIWEl5QKRqa1FtMkBcMD2MAl\nKcTxFp+VAgMBAAECggEAAbkJgg3JpVdlTWMwu2oMh9NFxwtpaM9frInxGoWVDIIG\n2pj3JrIIs/P6RDx0ron+kWBIwhr8xS8NBC0dUwI0mAc3ZWho5k2IoOul8uHT0hXv\n5OWPiPttzpNxGB4GaNpG7CxaqyC5rkk64+LK/lQIGEEJgO7p3Kae+hkwSdPUczee\nmccYuoygoZOrTF/4Cqp3NIA5NW1dmuApfTJBR2RdaIs0fwm80R/FYbFpFNYLqKY8\nBd/1FnE72aMrps3+amrtyXsA+CAFIJ2lmESp9Fi7otCy3LHsI7gRshKEHHezwnTG\nFFlYLl/FMt39FSsYEDRdeDSZ+0TXqOWKHUjMN8BYYQKBgQDa2VBCQuzxEpe4WqV4\nl4xLruIYe5Gvok8s7JJvJZSAV2lwSddn7GwQTdWrn4MDZxlbuzjRGrTxvBPL8ZOC\nmXQRkKGjGJbAB1FN0ot/48dVV+Kq8sAuZ6ImtLvJHCleY/U2q5CzHzk3wArc7MSZ\naASgdMKYtgwF9CBx0gk9GVJeSwKBgQDAamAG8yUxgw8/3XzGOODcFDNPf8F+8DU/\neY6zZ4eh6M3vsdpREucR8QDhiFeow7Hdzcy19Rm53Jv75nY69lI3c8z+kdjfOiwf\n342VMZKk96kqpo7pl+USc1Togr/AAM4SUOMKJU7dNhQjiW/CgPqvz17AJZy/seIl\n4wxHMMTNnwKBgA0hVKtlEIo2L1wkNCFUFlrEF1SYtVjwSAfN+cspWguJMw5Fn+Lg\nUGt6AAPYAihGG0NhrDr/ezO1sQpfaBZB1rDnGIuMgh7rUXl11GDL7b21FChlgBpI\npXXlCg863VCIdsHc8UWN7gTmRMCUhAcgMobd+n7nDTmeOAQcMem0hziZAoGACSkH\nSU1qB+FlaHTy67zpd5C+oJpEa/ajklIHKSNUobCjnTWqXxG3TnNax2HmSnKGlTim\n9kKk5uRJyMQ//K6dW2Z36+alnHr4fsv1wZSKlq/zrcQgKZ7BmQ9Ei40t88YWCWkw\nZdtA1ectLkPsviXFyXjl2d/whm/BhGw6uWjo9aECgYAuFopuEDBZZXbm5TzgcvYu\nKk2LBU+P0v+NrUdxf0PWTOeEv3gbcwvThM0qkvEeP/77+a6B+VlSdbNumlfjgEYO\nHU3lEZCyP3VLZ3Wq7XyBN/eTP8rScJmsvWjtOmnSj/F9Jk0q9+QsFTvJPN+rGSlo\nPkrV8KsHMzo53eVuKr1s7g==\n-----END PRIVATE KEY-----\n",
            "client_email": "firebase-adminsdk-fbsvc@pharmachemistapp.iam.gserviceaccount.com",
            "client_id": "103929778407895521361",
            "auth_uri": "https://accounts.google.com/o/oauth2/auth",
            "token_uri": "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fbsvc%40pharmachemistapp.iam.gserviceaccount.com",
            "universe_domain": "googleapis.com"
        }';

        // Define the required scopes
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        try {
            $jwt = $this->createJwt($serviceAccountPath, $scopes);
            $accessToken = $this->getAccessToken($jwt);

            // Update token
            date_default_timezone_set("Asia/Kolkata");

            $currentTime = Carbon::now();
            $apiToken = ApiToken::where('id',2)->first();
            $apiToken->token = $accessToken;
            $apiToken->time = $currentTime->format('H:i:s');
            $apiToken->update();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        }
    }

    function createJwt($serviceAccountPath, $scopes)
    {
        $serviceAccount = json_decode($serviceAccountPath, true);
        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        $now = time();
        $expires = $now + 3600; // Token valid for 1 hour

        $claims = [
            'iss' => $serviceAccount['client_email'],
            'scope' => implode(' ', $scopes),
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $expires
        ];

        $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
        $base64UrlPayload = $this->base64UrlEncode(json_encode($claims));

        $signatureInput = $base64UrlHeader . '.' . $base64UrlPayload;

        // Sign the token
        $signature = '';
        openssl_sign($signatureInput, $signature, $serviceAccount['private_key'], 'sha256WithRSAEncryption');
        $base64UrlSignature = $this->base64UrlEncode($signature);

        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
    }

    function getAccessToken($jwt)
    {
        $url = 'https://oauth2.googleapis.com/token';
        $postFields = http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        $response = json_decode($result, true);

        if (isset($response['access_token'])) {
            return $response['access_token'];
        } else {
            throw new Exception('Error fetching access token: ' . $response['error']);
        }
    }

    function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
