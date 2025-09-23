<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\EmailInquery;

class EmailQueriesController extends Controller
{
    //this function use email queries data
    public function emailQueriesData(Request $request)
    {
        try {
            $url = url('/') . '/api/email-inuqery';
            $data = [
                'status' =>  '0',
            ];

            // Make the HTTP POST request
            $response = Http::post($url, $data);
            $responseData = $response->json();
            $detailsList = [];
            if (isset($responseData['data'])) {
                $detailsList = $responseData['data'];
            }

            return view('admin.emailqueries.index', compact('detailsList'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use email queries replied
    public function emailQueriesReplied(Request $request)
    {
        try {
            $url = url('/') . '/api/email-inuqery';
            $data = [
                'status' =>  '1',
            ];

            // Make the HTTP POST request
            $response = Http::post($url, $data);
            $responseData = $response->json();
            $detailsList = [];
            if (isset($responseData['data'])) {
                $detailsList = $responseData['data'];
            }

            return view('admin.emailqueries.index', compact('detailsList'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use send email
    public function sendEmail($id)
    {
        try {
            $email_data = EmailInquery::find($id);
            return view('admin.emailqueries.sendemail', compact('email_data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use send email store
    public function sendEmailStore(Request $request)
    {
        try {
            $url = url('/') . '/api/email-replay';
            $data = [
                'id' => $request->id,
                'email' => $request->email,
                'subject' => $request->subject,
                'body' => $request->body,
            ];
            // Make the HTTP POST request
            $response = Http::post($url, $data);

            $responseData = $response->json();
            // dd($responseData);

            return redirect()->route('emailqueries.index')->with('success', 'Email Sending Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
