<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\OfflineRequestsModel;

class OfflineRequestController extends Controller
{
    //this function use offline request data
    public function offlinerequestData(Request $request)
    {
      
        try {

            $status = null;
            if (isset($request->status)) {
                $status = $request->status;
            } else {
                $status = '0';
            }

            $offlineRequest =  OfflineRequestsModel::orderBy('id', 'DESC');
            if (isset($requests->status)) {
                $offlineRequest->where('status', $requests->status);
            }
            $offlineRequest = $offlineRequest->get();

            return view('admin.offlinerequest.index', compact('offlineRequest'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use offline request approve
    public function offlineRequestApprove(Request $request)
    {
        try {

            $offlineRequest =  OfflineRequestsModel::orderBy('id', 'DESC');
            $offlineRequest->where('status', '1');
            $offlineRequest = $offlineRequest->get();

            return view('admin.offlinerequest.index', compact('offlineRequest'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use offline request reject
    public function offlineRequestReject(Request $request)
    {
        try {

            $offlineRequest =  OfflineRequestsModel::orderBy('id', 'DESC');
            $offlineRequest->where('status', '2');
            $offlineRequest = $offlineRequest->get();

            return view('admin.offlinerequest.index', compact('offlineRequest'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function leadStore(Request $request)
    {

        $oflineData  = new OfflineRequestsModel;
        $oflineData->pharma_id = $request->pharma_name;
        $oflineData->reason = $request->reason;
        $oflineData->subscription_plan = $request->subscription_plan;
        $oflineData->plan_type = $request->plan_type;
        $oflineData->payment_method = $request->payment_method;
        $oflineData->status = $request->status;
        $oflineData->submitted_by = auth()->user()->id;
        $oflineData->submitted_on = date('Y-m-d');
        $oflineData->save();

        return redirect()->route('offlinerequest.index')->with('success', 'Lead Add Successfully');
    }

    //this function use status chnage
    public function offlineRequestStatus(Request $request)
    {
        try {

            $url = url('/') . '/api/offline-request-resonse';
            $data = [
                'id' => $request->id,
                'status' => $request->status,
                'reason' => $request->reason,
            ];

            // Make the HTTP POST request
            $response = Http::post($url, $data);
            $responseData = $response->json();
            $detailsList = [];
            if (isset($responseData['data'])) {
                $detailsList = $responseData['data'];
            }

            return redirect()->back()->with('success', 'status Change Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function addLead(Request $request)
    {
        return view('admin.offlinerequest.lead');
    }
}
