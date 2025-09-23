<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\ResponseController;
use App\Models\ReconciliationIteam;
use App\Models\IteamsModel;
use App\Models\UniteTable;
use App\Models\CompanyModel;
use App\Models\BatchModel;
use App\Models\PurchesDetails;
use App\Models\OnlineOrder;
use App\Models\ItemLocation;
use Carbon\Carbon;

class ReconciliationController extends ResponseController
{

    public function reconciliationList(Request $request)
    {
        $userData = User::where('id', auth()->user()->id)->first();


        if (isset($userData)) {
            if (isset($request->iss_audit)) {
                $userData->iss_audit = isset($request->iss_audit) ? $request->iss_audit : "";
            }
            if (isset($request->iteam_count)) {
                $userData->iteam_count = isset($request->iteam_count) ? $request->iteam_count : "";
            }
            $userData->update();
        }

        $data = [];
        $data['iss_audit'] = isset($userData->iss_audit) ? $userData->iss_audit : "";
        $data['iteam_count'] = isset($userData->iteam_count) ? $userData->iteam_count : "";
        return $this->sendResponse($data, 'Reconciliation Updated Successfully');
    }

    public function reconciliationIteamList(Request $request)
    {
        $reconciliationList = ReconciliationIteam::where('owner_id', auth()->user()->create_by)->pluck('iteam_id')->toArray();

        $userData = User::where('id', auth()->user()->create_by)->first();

        $iteamData = [];
        if (isset($userData)) {
            $iteamData = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->create_by)->whereNotIn('id', $reconciliationList)
                ->take($userData->iteam_count)->inRandomOrder()->get();
        }

        $iteamDetails = [];

        $todayDate = Carbon::today();
        $reconcilationCount = ReconciliationIteam::whereDate('created_at', $todayDate)->where('reported_by', auth()->user()->id)->exists();

        foreach ($iteamData as $key => $listData) {
            $uniteData = UniteTable::where('id', $listData->old_unit)->first();
            $company = CompanyModel::where('id', $listData->pharma_shop)->first();
            $iteamDataCount = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->get();

            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);


            $totalStockTotal = BatchModel::whereIn('user_id', $allUserId)->where('item_id', $listData->id)->sum('total_qty');

            $totalStockTotal = $totalStockTotal;

            $iteamName = IteamsModel::find($listData->id);
            if (isset($iteamName)) {
                $iteamName->stock = $totalStockTotal;
                $iteamName->update();
            }


            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $iteamDetails[$key]['id'] = isset($listData->id) ? $listData->id : "";
            $iteamDetails[$key]['iteam_name'] = isset($listData->iteam_name) ? $listData->iteam_name : "";
            $iteamDetails[$key]['company'] = isset($company->company_name) ? $company->company_name : "";
            $iteamDetails[$key]['stock'] = isset($totalStockTotal) ? $totalStockTotal : "";
            $totalIteam = PurchesDetails::whereIn('user_id', $allUserId)->where('iteam_id', $listData->id)->orderBy('id', 'DESC')->first();
            $iteamDetails[$key]['weightage'] = isset($totalIteam->weightage) ? $totalIteam->weightage : $listData->weightage;

            $onlineData =  OnlineOrder::where('item_id', $listData->id)->whereIn('user_id', $allUserId)->first();

            if (isset($onlineData)) {
                $iteamDetails[$key]['is_order'] = true;
            } else {
                $iteamDetails[$key]['is_order'] = false;
            }

            $earliestBatch = BatchModel::whereIn('user_id', $allUserId)
                ->where('item_id', $listData->id)
                ->orderByRaw("STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y')")
                ->first();

            $iteamDetails[$key]['unit'] = isset($uniteData->name) ? $uniteData->name : "";
            $iteamDetails[$key]['expiry'] = isset($earliestBatch->expiry_date) ? (string)$earliestBatch->expiry_date : '';
            $iteamDetails[$key]['unit_id'] = isset($listData->old_unit) ? $listData->old_unit : "";
            $iteamDetails[$key]['pack'] = isset($listData->packing_size) ? $listData->packing_size : "";
            $iteamDetails[$key]['gst'] = isset($listData->gst) ? $listData->gst : "";
            $iteamDetails[$key]['packing_type'] = isset($listData->getPackage->packging_name) ? $listData->getPackage->packging_name : "";
            $iteamDetails[$key]['pharma_shop'] = isset($company->company_name) ? $company->company_name : "";
            $itemLocation = ItemLocation::where('item_id', $listData->id)->whereIn('user_id', $allUserId)->first();
            $iteamDetails[$key]['location'] = isset($itemLocation->location) ? $itemLocation->location : "";
            $iteamDetails[$key]['distributer'] = isset($listData->getDistibuter->name) ? $listData->getDistibuter->name : "";
            $iteamDetails[$key]['distributer_id'] = isset($listData->distributer_id) ? $listData->distributer_id : "";
            $iteamDetails[$key]['drug_group'] = isset($listData->drug_group) ? $listData->drug_group : "";
            $iteamDetails[$key]['barcode'] = isset($listData->barcode) ? $listData->barcode : "";
            $iteamDetails[$key]['schedule'] = isset($listData->schedule) ? $listData->schedule : "";
            $iteamDetails[$key]['tax'] = isset($listData->tax) ? $listData->tax : "";
            $iteamDetails[$key]['discount'] = isset($listData->discount) ? $listData->discount : "";
            $iteamDetails[$key]['margin'] = isset($listData->margin) ? $listData->margin : "";
            $iteamDetails[$key]['tax_not_applied'] = isset($listData->tax_not_applied) ? $listData->tax_not_applied : "";
            $iteamDetails[$key]['item_type'] = isset($listData->item_type) ? $listData->item_type : "";
            $iteamDetails[$key]['mrp'] = isset($listData->mrp) ? $listData->mrp : "";
            $iteamDetails[$key]['minimum'] = isset($listData->minimum) ? $listData->minimum : "";
            $iteamDetails[$key]['maximum'] = isset($listData->maximum) ? $listData->maximum : "";
            $iteamDetails[$key]['item_category_id'] = isset($listData->getCategory->category_name) ? $listData->getCategory->category_name : "";
            $iteamDetails[$key]['packaging_id'] = isset($listData->getPackage->packging_name) ? $listData->getPackage->packging_name : "";
            $iteamDetails[$key]['front_photo'] = isset($listData->front_photo) ? asset('/public/ront_photo/' . $listData->front_photo) : "";
            $iteamDetails[$key]['back_photo'] = isset($listData->back_photo) ? asset('/public/back_photo/' . $listData->back_photo) : "";
            $iteamDetails[$key]['mrp_photo'] = isset($listData->mrp_photo) ? asset('/public/mrp_photo/' . $listData->mrp_photo) : "";
            $iteamDetails[$key]['count'] =  $iteamDataCount->count();
        }

        $staffCheck = User::where('id', auth()->user()->id)->whereNotNull('create_by')->first();

        if (isset($staffCheck)) {
            $userDetails = User::where('id', auth()->user()->id)->first();
            $ownerData = User::where('id', $userDetails->create_by)->first();
            $dataList['iss_audit'] = isset($ownerData->iss_audit) ?  $ownerData->iss_audit : "";
            $dataList['iteam_count'] = isset($ownerData->iteam_count) ?  $ownerData->iteam_count : "";
        } else {

            $dataList['iss_audit'] = isset(auth()->user()->iss_audit) ?  auth()->user()->iss_audit : "";
            $dataList['iteam_count'] = isset(auth()->user()->iteam_count) ?  auth()->user()->iteam_count : "";
        }
        $dataList['data'] = $iteamDetails;
        $dataList['status'] = $reconcilationCount;

        //  $dataList['count'] = isset($iteamData) ? count($iteamData) :'';
        return $this->sendResponse($dataList, 'Data Fetch Successfully');
    }

    public function reconciliationIteamStore(Request $request)
    {
        $stockDetails = json_decode($request->iteam_data, true);

        if (isset($stockDetails)) {
            foreach ($stockDetails as $list) {
                $reconcilationData = new ReconciliationIteam;
                $reconcilationData->owner_id = isset(auth()->user()->create_by) ? auth()->user()->create_by : "";
                $reconcilationData->iteam_id = isset($list['iteam_id']) ? $list['iteam_id'] : "";
                $reconcilationData->physical_stock = isset($list['stock']) ? $list['stock'] : "";
                $reconcilationData->reported_by = isset(auth()->user()->id) ? auth()->user()->id : "";
                $reconcilationData->save();
            }
            return $this->sendResponse([], 'Reconciliation Stock Adjusted Successfully');
        }

        return $this->sendResponse([], 'No Stock Details Provided');
    }

    public function reconciliationRestart(Request $request)
    {
        $reconilationData = ReconciliationIteam::where('owner_id', auth()->user()->id)->get();
        if (isset($reconilationData)) {
            foreach ($reconilationData as $list) {
                $list->delete();
            }
        }

        return $this->sendResponse([], 'Reconciliation  Restart Succesfully');
    }

    public function reconciliationReport(Request $request)
    {
        $startDate = Carbon::parse($request->start_date); // Parse start date
        $endDate = Carbon::parse($request->end_date);     // Parse end date

        // Apply the filter to the query
        $reconciliationData = ReconciliationIteam::where('owner_id', auth()->user()->id);

        if (!empty($startDate) && !empty($endDate)) {
            $reconciliationData->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        $batchCount = $reconciliationData->count();
        $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
        $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
        $offset = ($page - 1) * $limit;
        $reconciliationData = $reconciliationData->offset($offset)->limit($limit)->get();

        $itemDetails = [];
        if ($reconciliationData->isNotEmpty()) { // Check if data exists
            foreach ($reconciliationData as $list) {
                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $totalStockTotal = BatchModel::whereIn('user_id', $allUserId)
                    ->where('item_id', $list->iteam_id)
                    ->sum('total_qty');

                $iteamName = IteamsModel::where('id', $list->iteam_id)->first();
                $staffGetName = User::where('id', $list->reported_by)->first();
                $location = ItemLocation::where('user_id', auth()->user()->id)->where('item_id', $list->iteam_id)->first();

                $companyName = CompanyModel::where('id', $iteamName->pharma_shop)->first();

                $itemData = [
                    'id' => (string) $list->id ?? "",
                    'iteam_name' => (string) $iteamName->iteam_name ?? "",
                    'iteam_id' => (string) $list->iteam_id ?? "",
                    'physical_stock' => (string) $list->physical_stock ?? "",
                    'current_stock' => (string) $totalStockTotal ?? "",
                    'reported_by' => (string) $staffGetName->name ?? "",
                    'location' => (string) ($location->location ?? ""),
                    'unit' => (string) $iteamName->old_unit ?? "",
                    'mrp' => (string) $iteamName->mrp ?? "",
                    'company_name' => isset($companyName->company_name) ? $companyName->company_name : ""
                ];

                // Check status conditions
                if ($request->status == '0') {
                    $itemDetails[] = $itemData; // Add item to the array
                } elseif ($request->status == '1' && (int) $totalStockTotal == (int) $list->physical_stock) {
                    $itemDetails[] = $itemData; // Add item to the array
                } elseif ($request->status == '2' && (int) $totalStockTotal != (int) $list->physical_stock) {
                    $itemDetails[] = $itemData; // Add item to the array
                }
            }
        }

        return $this->sendResponse($itemDetails, 'Data fetched successfully.');
    }
}
