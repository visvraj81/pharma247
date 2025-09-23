<?php

namespace App\Http\Controllers\Api\User;

use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\IteamsModel;
use App\Models\BatchModel;
use App\Models\CompanyModel;
use App\Models\UniteTable;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Package;
use App\Models\QrCode;
use App\Models\Distributer;
use App\Models\GstModel;
use App\Models\ItemCategory;
use App\Models\PurchesDetails;
use App\Models\PurchesReturnDetails;
use App\Models\LedgerModel;
use Illuminate\Support\Arr;
use App\Models\salesDetails;
use App\Models\SalesModel;
use App\Models\SalesReturn;
use App\Models\SalesReturnDetails;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerModel;
use App\Models\LogsModel;
use App\Models\DrugGroup;
use App\Models\OnlineOrder;
use App\Models\ItemLocation;
use App\Models\SalesIteam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class IteamController extends ResponseController
{
    //this function use iteam create
    public function itemsCreate(Request $request)
    {
        try {
            $iteamsData = IteamsModel::where('iteam_name', $request->item_name)->first();
            $message = 'Item Update Successfully.';
            if (empty($iteamsData)) {
                $iteamsData = new IteamsModel();
                $message = 'Item Added Successfully.';
            }

            $iteamsData->iteam_name = isset($request->item_name) ? $request->item_name : null;
            $iteamsData->old_unit = isset($request->old_unit) ? $request->old_unit : null;
          	$iteamsData->unit = isset($request->unit) ? $request->unit : null;
            // $iteamsData->unit = (isset($request->unit) && is_numeric($request->unit) && (int)$request->unit > 0) 
            // ? (int)$request->unit 
            // : 1;
            $iteamsData->packing_size = isset($request->pack) ?  $request->pack : null;
            $iteamsData->pharma_shop = isset($request->pahrma) ? $request->pahrma : null;
            $iteamsData->distributer_id = isset($request->distributer) ? $request->distributer : null;
            $iteamsData->gst = isset($request->gst) ? $request->gst : null;
            $iteamsData->item_category_id = isset($request->item_category_id) ? $request->item_category_id : null;
            $iteamsData->packing_type = isset($request->packing_type) ? $request->packing_type : null;
            $iteamsData->drug_group = isset($request->drug_group) ? $request->drug_group : null;
            $iteamsData->location = isset($request->location) ? $request->location : null;
            $iteamsData->item_type = isset($request->item_type) ? $request->item_type : null;
            $iteamsData->schedule = isset($request->schedule) ? $request->schedule : null;
            $iteamsData->user_id = auth()->user()->id;
            $iteamsData->tax_not_applied = isset($request->tax_not_applied) ? $request->tax_not_applied : null;
            $iteamsData->tax = isset($request->tax) ? $request->tax : null;
            $iteamsData->barcode = isset($request->barcode) ? $request->barcode : null;
            $iteamsData->minimum = isset($request->minimum) ? $request->minimum : null;
            $iteamsData->maximum = isset($request->maximum) ? $request->maximum : null;
            $iteamsData->discount = isset($request->discount) ? $request->discount : null;
            $iteamsData->margin = isset($request->margin) ? $request->margin : null;
            $iteamsData->hsn_code = isset($request->hsn_code) ? $request->hsn_code : null;
            $iteamsData->stock = '0';
          	$iteamsData->loaction = $request->location;
            if ($request->file('front_photo')) {
                $front_photo = $request->file('front_photo');
                $filename = time() . $front_photo->getClientOriginalName();
                $front_photo->move(public_path('front_photo'), $filename);
                $iteamsData->front_photo =  $filename;
            }
            if ($request->file('back_photo')) {
                $back_photo = $request->file('back_photo');
                $filenamebackside = time() . $back_photo->getClientOriginalName();
                $back_photo->move(public_path('back_photo'), $filenamebackside);
                $iteamsData->back_photo = $filenamebackside;
            }
            if ($request->file('mrp_photo')) {
                $slat_compostion = $request->file('mrp_photo');
                $filenamemrp_photo = time() . $slat_compostion->getClientOriginalName();
                $slat_compostion->move(public_path('mrp_photo'), $filenamemrp_photo);
                $iteamsData->mrp_photo = $filenamemrp_photo;
            }
            $iteamsData->message = isset($request->message) ? $request->message : null;
            $iteamsData->packaging_id = isset($request->packaging_id) ? $request->packaging_id : null;
            $iteamsData->mrp = isset($request->mrp) ? $request->mrp : null;
            $iteamsData->save();

            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $itemLocation = ItemLocation::where('item_id', $iteamsData->id)->whereIn('user_id', $allUserId)->first();

            if (isset($itemLocation)) {
                if (isset($request->location)) {
                    $itemLocation->location = $request->location;
                    $itemLocation->update();
                }
            } else {
                if (isset($request->location)) {
                    $itemLocation = new ItemLocation;
                    $itemLocation->user_id = auth()->user()->id;
                    $itemLocation->item_id =  $iteamsData->id;
                    $itemLocation->location = $request->location;
                    $itemLocation->save();
                }
            }

            $userLogs = new LogsModel;
            $userLogs->message = $message;
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            $dataDetails = [];
            $dataDetails['id'] = isset($iteamsData->id) ? $iteamsData->id : null;
            return $this->sendResponse($dataDetails, $message);
        } catch (\Exception $e) {
            Log::info("Create Iteams api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function barcodeBatchList(Request $request)
    {
        $qrCodes = QrCode::where('qr_code', $request->barcode)->first();
        if ($qrCodes) {
            $allUserId = $this->getUserIds();
            $userid = auth()->user();

            $batchNumber = BatchModel::where('item_id', $qrCodes->item_id)->where('total_qty', '>', '0')
                ->where('batch_name', $qrCodes->batch_number)
                ->whereIn('user_id', $allUserId)
                ->orderBy('id', 'DESC')
                ->get();

            return $this->prepareResponseData($batchNumber, "");
        } else {
            $barcodeData = IteamsModel::where('barcode', $request->barcode)->first();

            if ($barcodeData) {
                $allUserId = $this->getUserIds();

                $batchNumber = BatchModel::where('item_id', $barcodeData->id)->where('total_qty', '>', '0')
                    ->whereIn('user_id', $allUserId)
                    ->orderBy('id', 'DESC');

                // Pagination
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
                $offset = ($page - 1) * $limit;
                $batchNumber = $batchNumber->offset($offset)->limit($limit)->get();

                return $this->prepareResponseData($batchNumber, $barcodeData->id);
            } else {
                return $this->sendResponse([], 'Data Not Found.');
            }
        }
    }
  
  	public function itemMultipleBatchViewList(Request $request)
    {
      	if(!empty($request->item_id))
        {
        	$itemIds = json_decode($request->item_id);
            $item_batch_data_list = BatchModel::whereIn('item_id',$itemIds)->where('user_id',auth()->user()->id)->get();
          
          	$itemBatchDataDetailsList = [];
          	if(isset($item_batch_data_list))
            {
            	foreach($item_batch_data_list as $key => $list)
                {
                	$itemBatchDataDetailsList[$key]['id'] = isset($list->id) ? $list->id : "";
                  	$itemBatchDataDetailsList[$key]['item_id'] = isset($list->item_id) ? $list->item_id : "";
                  	$itemBatchDataDetailsList[$key]['item_name'] = isset($list->getIteam->iteam_name) ? $list->getIteam->iteam_name : "";
                  	$itemBatchDataDetailsList[$key]['unit'] = isset($list->unit) ? $list->unit : "";
                  	$itemBatchDataDetailsList[$key]['batch'] = isset($list->batch_name) ? $list->batch_name : "";
                  	$itemBatchDataDetailsList[$key]['item_id'] = isset($list->item_id) ? $list->item_id : "";
                }
            }

            return $this->sendResponse($itemBatchDataDetailsList,'Multiple Item Batch Data Get Successfully.');
        } else {
          	return $this->sendError('Please Enter Item Id');
        }
    }
  
    public function itemBulkQrCodeData(Request $request)
    {
      	$request->validate([
            'data' => 'required|array',
            'data.*.item_id' => 'required|integer|exists:iteams,id',
            'data.*.batch_id' => 'required|integer|exists:batch,id',
            'data.*.qty' => 'required|numeric|min:1'
        ]);

        $itemIds = collect($request->data)->pluck('item_id')->unique()->toArray();
        $batchIds = collect($request->data)->pluck('batch_id')->unique()->toArray();

        // Fetch items
        $items = IteamsModel::whereIn('id', $itemIds)->get()->keyBy('id');

        // Fetch batch data with item relation
        $batches = BatchModel::with('getIteam') // assuming `getIteam` is the relation
            ->whereIn('id', $batchIds)
            ->get()
            ->keyBy('id');

        $qrList = [];

        foreach ($request->data as $row) {
            $itemId = $row['item_id'];
            $batchId = $row['batch_id'];
            $qty = $row['qty'];

            if (!isset($items[$itemId]) || !isset($batches[$batchId])) continue;

            $item = $items[$itemId];
            $batch = $batches[$batchId];

            for ($i = 0; $i < $qty; $i++) {
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($item->barcode);
                $qrList[] = [
                    'item_id' => $item->iteam_name,
                    'batch_number' => $batch->batch_name,
                    'unit' => $batch->unit,
                  	'mrp' => $batch->mrp,
                    'expiry' => $batch->expiry_date,
                    'qty' => $qty,
                    'barcode' => $item->barcode,
                    'qr_code_url' => $qrUrl
                ];
            }
        }

        // Generate a unique filename
        $fileName = 'qr_codes_' . now()->format('YmdHis') . '_' . Str::random(5) . '.pdf';
        $filePath = public_path('qr_pdfs/' . $fileName);

        // Ensure directory exists
        if (!file_exists(public_path('qr_pdfs'))) {
            mkdir(public_path('qr_pdfs'), 0777, true);
        }

        // Save the PDF
        PDF::loadView('item_bulk_qr_code', compact('qrList'))
            ->setPaper('a4', 'portrait')
            ->save($filePath);

        // Generate URL
        $fileUrl = asset('public/qr_pdfs/' . $fileName);

        return response()->json([
            'status' => 200,
            'message' => 'QR Code PDF Generated Successfully.',
            'pdf_url' => $fileUrl
        ]);
    }

    public function qrCodeList(Request $request)
    {
        try {
            $jsonDecode = $request->qr_code_details;
            $jsonData = json_decode($jsonDecode, true);
            $dataFetch = [];
            if (isset($jsonData)) {
                foreach ($jsonData as $listData) {
                    $qrCode = new QrCode;
                    $qrCode->user_id = Auth::user()->id;
                    $qrCode->item_id = isset($listData['item_id']) ? $listData['item_id'] : "";
                    $qrCode->batch_number = isset($listData['batch_number']) ? $listData['batch_number'] : "";
                    $qrCode->qr_code = isset($listData['qr_code']) ? $listData['qr_code'] : "";
                    $qrCode->qr_code_link = isset($listData['qr_code_link']) ? $listData['qr_code_link'] : "";
                    $qrCode->save();
                }
            }
            return $this->sendResponse($dataFetch, 'Qr Code Store Successfully');
        } catch (\Exception $e) {
            Log::info("Iteams qr code api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    private function getUserIds()
    {
        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
        $userId = [auth()->user()->id];
        return array_merge($staffGetData, $ownerGet, $userId);
    }

    private function prepareResponseData($batchNumber, $iteams)
    {
        $barcodeCreate = IteamsModel::where('id', $iteams)->first();
        $companyName = null;
        $gstTotal = null;

        if (isset($barcodeCreate->pharma_shop)) {
            $companyName = CompanyModel::where('id', $barcodeCreate->pharma_shop)->first();
            $gstTotal = GstModel::where('id', $barcodeCreate->gst)->first();
        }

        $dataItems = [
            "id" => isset($barcodeCreate->id) ? $barcodeCreate->id : "",
            "iteam_name" => isset($barcodeCreate->iteam_name) ? $barcodeCreate->iteam_name : "",
            "company_id" => isset($barcodeCreate->pharma_shop) ? $barcodeCreate->pharma_shop : "",
            "company_name" => isset($companyName->company_name) ? $companyName->company_name : "",
            "old_unit" => isset($barcodeCreate->old_unit) ? $barcodeCreate->old_unit : "",
            "gst" => isset($gstTotal->name) ? $gstTotal->name : "",
            "gst_id" => isset($barcodeCreate->gst) ? $barcodeCreate->gst : "",
            "mrp" => isset($barcodeCreate->mrp) ? $barcodeCreate->mrp : "",
            "front_photo" => isset($barcodeCreate->front_photo) ? asset('/public/front_photo/' . $barcodeCreate->front_photo) : "",
            "batch_list" => []
        ];

        if ($batchNumber->isEmpty()) {
              return $this->sendResponse([], 'Data Not Found.'); // Wrap in an array
        }

        foreach ($batchNumber as $key => $list) {
            $allUserId = $this->getUserIds();
            $userid = auth()->user();

            $gstTotal = GstModel::where('id', $list->gst)->first();
            $iteamModel = IteamsModel::where('id', $list->item_id)->first();
            $companyName = CompanyModel::where('id', $iteamModel->pharma_shop)->first();

            $NetAmount = PurchesDetails::where('batch', $list->batch_name)->whereIn('user_id', $allUserId)->orderBy('id', 'DESC')->first();

            $dataItems['batch_list'][] = [
                'id' => $list->id ?? "",
                'iteam_name' => $iteamModel->iteam_name ?? "",
                'company_id' => $iteamModel->pharma_shop ?? "",
                'total_amount' => $NetAmount->amount ?? "",
                'net_rate' => $NetAmount->net_rate,
                'scheme_account' => $NetAmount->scheme_account,
                'company_name' => $companyName->company_name ?? "",
                'item_id' => $list->item_id ?? "",
                'qty' => $list->total_qty ?? "",
                'purchase_qty' => $list->qty ?? "",
                'purchase_free_qty' => $list->free_qty ?? "",
                'batch_number' => $list->batch_number ?? "",
                'lp' => $list->lp ?? "",
                'ptr' => $list->ptr ?? "",
                'margin' => $list->margin ?? "",
                'base' => $list->base,
                'total_mrp' => $list->total_mrp ?? "",
                'total_ptr' => $list->total_ptr ?? "",
                'discount' => $list->discount ?? "",
                'gst' => $list->gst ?? "",
                'gst_name' => $gstTotal->name ?? "",
                'batch_name' => $list->batch_name ?? "",
                'expiry_date' => $list->expiry_date ?? "",
                'mrp' => $list->mrp ?? "",
                'stock' => $list->total_qty ?? "",
                'old_unit' => $list->old_unit ?? "",
                'location' => $list->location ?? "",
            ];
        }

        return $this->sendResponse([$dataItems], 'Data Fetch Successfully.'); // Wrap in an array
    }

    public function iteamBatchList(Request $request)
    {
        try {
            $iteamId = $request->item_id;
            $dataExport = explode(',', $iteamId);

            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $batchNumber = BatchModel::whereIn('item_id', $dataExport)->whereIn('user_id', $allUserId)->orderBy('id', 'DESC');

            $batchCount = $batchNumber->count();
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
            $offset = ($page - 1) * $limit;
            $batchNumber = $batchNumber->offset($offset)->limit($limit)->get();

            return $this->prepareResponseData($batchNumber, $request->item_id);
        } catch (\Exception $e) {
            Log::info("Iteams Batch api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function bulkEdit(Request $request)
    {
        $itemIds = $request->item_id;
        $iteamData = explode(",", $itemIds);

        if (isset($iteamData)) {
            foreach ($iteamData as $list) {
                $iteamUpdate = IteamsModel::where('id', $list)->first();
                if (isset($iteamUpdate)) {
                    if (isset($request->location) && ($request->location != 'undefined')) {
                        //$iteamUpdate->location = $request->location;

                        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                        $userId = array(auth()->user()->id);
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                        $itemLocation = ItemLocation::where('item_id', $iteamUpdate->id)->whereIn('user_id', $allUserId)->first();
                        if (isset($itemLocation)) {
                            $itemLocation->location = $request->location;
                            $itemLocation->update();
                        } else {
                            $itemLocation = new ItemLocation;
                            $itemLocation->user_id = auth()->user()->id;
                            $itemLocation->item_id =  $iteamUpdate->id;
                            $itemLocation->location = $request->location;
                            $itemLocation->save();
                        }
                    }
                    if (isset($request->barcode) && ($request->barcode != 'undefined')) {
                        $iteamUpdate->barcode = $request->barcode;
                    }
                    $iteamUpdate->update();
                }
            }
        }
        return $this->sendResponse([], 'Item Bulk Updated Successfully.');
    }

    public function logsActivity(Request $request) {
        // dd('test');
    }

    public function ItemView(Request $request)
    {
        try {
            $iteamData = IteamsModel::where('id', $request->id)->first();
            $dataIteams = [];
            if (isset($iteamData)) {
                $getData = GstModel::where('id', $iteamData->gst)->first();
                $categoryData = ItemCategory::where('id', $iteamData->item_category_id)->first();
                $gstNumber = Distributer::where('id', $iteamData->distributer_id)->first();
                $company = CompanyModel::where('id', $iteamData->pharma_shop)->first();
                $drugGroup = DrugGroup::where('id', $iteamData->drug_group)->first();

                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                $onlineData =  OnlineOrder::where('item_id', $iteamData->id)->whereIn('user_id', $allUserId)->first();

                $dataIteams['id'] =  asset($iteamData->id) ? $iteamData->id : "";
                $dataIteams['iteam_name'] =  isset($iteamData->iteam_name) ? $iteamData->iteam_name : "";
                if (isset($onlineData)) {
                    $dataIteams['is_order'] = true;
                } else {
                    $dataIteams['is_order'] = false;
                }
                $dataIteams['company'] = isset($company->company_name) ? $company->company_name : "";
                $dataIteams['drug_group'] = isset($drugGroup->name) ? $drugGroup->name : "";
                $dataIteams['company_id'] = isset($iteamData->pharma_shop) ? $iteamData->pharma_shop : "";
                $dataIteams['mrp'] = isset($iteamData->mrp) ? $iteamData->mrp : "";
                $dataIteams['category_id'] = isset($iteamData->item_category_id) ? $iteamData->item_category_id : "";
                $dataIteams['packaging_id'] = isset($iteamData->packaging_id) ? $iteamData->packaging_id : "";
                $dataIteams['druggroup_id'] = isset($iteamData->drug_group) ? $iteamData->drug_group : "";
                $dataIteams['front_photo'] =  isset($iteamData->front_photo) ? asset('/public/front_photo/' . $iteamData->front_photo) : "";
                $dataIteams['distributor_name'] =  isset($iteamData->getDistibuter->name) ? $iteamData->getDistibuter->name : "";
                $dataIteams['pack'] =  isset($iteamData->packing_size) ? $iteamData->packing_size : "";
                $dataIteams['discount'] = isset($iteamData->discount) ? $iteamData->discount : "0";
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                $itemLocation = ItemLocation::where('item_id', $iteamData->id)->whereIn('user_id', $allUserId)->first();

                $dataIteams['location'] = isset($itemLocation->location) ? $itemLocation->location : "";

                if (isset($iteamData->maximum)) {
                    $dataIteams['maximum'] =  $iteamData->maximum != null ? $iteamData->maximum : "";
                } else {
                    $dataIteams['maximum'] = "";
                }
                if (isset($iteamData->minimum)) {
                    $dataIteams['minimum'] =  $iteamData->minimum != null ? $iteamData->minimum : "";
                } else {
                    $dataIteams['minimum'] = "";
                }

                $dataIteams['gst_number'] =  isset($gstNumber->gst) ? $gstNumber->gst : "";
                $dataIteams['gst'] =  isset($getData->name) ? $getData->name : "";
                $dataIteams['hsn_code'] = $iteamData->hsn_code != null ? $iteamData->hsn_code : "";
                $dataIteams['category_name'] = isset($categoryData->category_name) ? $categoryData->category_name : "";

                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);

                $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                $totalStock = BatchModel::where('item_id', $iteamData->id)->whereIn('user_id', $allUserId)->sum('total_qty');
              	// dd($totalStock);

                $dataIteams['stock'] =  isset($totalStock) ? abs($totalStock) : "";
                $dataIteams['status'] = isset($totalStock) && $totalStock != "0" ? "Available" : "Not Available";
            }
            return $this->sendResponse($dataIteams, 'Iteam Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Iteams view api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function BatchList(Request $request)
    {
        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
        $userId = array(auth()->user()->id);
        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

        $batchNumber = BatchModel::where('item_id', $request->id)->whereIn('user_id', $allUserId)->orderBy('id', 'DESC');

        $batchCount = $batchNumber->count();
        $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
        $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
        $offset = ($page - 1) * $limit;
        $batchNumber = $batchNumber->offset($offset)->limit($limit)->get();

        $dataIteams = [];
        if (isset($batchNumber)) {
            foreach ($batchNumber as $key =>  $list) {
                $userName = User::where('id', $list->id)->first();
                $EntryName = User::where('id', $list->user_id)->first();
                $dataIteams[$key]['id'] =  isset($list->id) ? $list->id : "";
                $dataIteams[$key]['name'] =  isset($userName->name) ? $userName->name : "";
                $dataIteams[$key]['entry_by'] =  isset($EntryName->name) ? $EntryName->name : "";
                $dataIteams[$key]['batch_name'] =  isset($list->batch_name) ? $list->batch_name : "";
                $dataIteams[$key]['qty'] =  isset($list->total_qty) ? (string)$list->total_qty  : "";
                $dataIteams[$key]['expiry_date'] =  isset($list->expiry_date) ? $list->expiry_date : "";
                $dataIteams[$key]['mrp'] =  isset($list->mrp) ? $list->mrp : "";
                $dataIteams[$key]['ptr'] =  isset($list->ptr) ? $list->ptr : "";
                $dataIteams[$key]['discount'] =  $list->discount != null ? $list->discount : "0";
                $dataIteams[$key]['lp'] =  isset($list->lp) ? $list->lp : "";
                $dataIteams[$key]['margin'] =  isset($list->margin) ? $list->margin : "";
                $dataIteams[$key]['total_mrp'] =  isset($list->total_mrp) ? $list->total_mrp : "";
                $dataIteams[$key]['location'] =  isset($list->location) ? $list->location : "";
                $dataIteams[$key]['total_ptr'] =  isset($list->total_ptr) ? $list->total_ptr : "";
                $dataIteams[$key]['count'] =  isset($batchCount) ? $batchCount : "";
            }
        }
        return $this->sendResponse($dataIteams, 'Batch Data Fetch Successfully.');
    }

    public function PurchesIteams(Request $request)
    {
        $userid = auth()->user();
        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
        $userId = array(auth()->user()->id);
        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

        $purchesData = PurchesDetails::where('iteam_id', $request->id)->whereIn('user_id', $allUserId);
        if ((isset($request->start_date)) && (isset($request->end_date))) {
            $purchesData->whereHas('getpurches', function ($q) use ($request) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                $q->whereDate('bill_date', '>=', $startDate)->whereDate('bill_date', '<=', $endDate);
            });
        }
        if (isset($request->distributor_id)) {
            $purchesData->whereHas('getpurches', function ($q) use ($request) {

                $q->where('distributor_id', $request->distributor_id);
            });
        }

        if (isset($request->staff) && ($request->staff != 'All')) {
            if ($request->staff == 'owner') {
                $purchesData->where('user_id', auth()->user()->id);
            } else {
                $purchesData->where('user_id', $request->staff);
            }
        }
        if ($request->gst == 'true') {
            $purchesData->where('gst', '0');
        }
        if ($request->gst == 'false') {
            $purchesData->where('gst', '!=', '0');
        }
        $purchesCount = $purchesData->count();
        $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
        $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
        $offset = ($page - 1) * $limit;
        $purchesData = $purchesData->orderBy('id', 'DESC')->offset($offset)->limit($limit)->get();
        $dataIteams = [];
        $qty = [];
        $fr_qty_total = 0;
        $qty_total = 0;
        if (isset($purchesData)) {
            foreach ($purchesData as $p => $purchesList) {
                $EntryName = User::where('id', $purchesList->user_id)->first();
                $dataIteams[$p]['id'] = isset($purchesList->id) ? $purchesList->id : "";
                $dataIteams[$p]['entry_by'] = isset($EntryName->name) ? $EntryName->name : "";
                $dataIteams[$p]['purchase_id'] = isset($purchesList->getpurches->id) ? $purchesList->getpurches->id : "";
                $dataIteams[$p]['fr_qty'] = isset($purchesList->fr_qty) ? $purchesList->fr_qty : "";
                $dataIteams[$p]['bill_no'] = isset($purchesList->getpurches->bill_no) ? $purchesList->getpurches->bill_no : "";
                $dataIteams[$p]['bill_date'] = isset($purchesList->getpurches->bill_date) ? $purchesList->getpurches->bill_date : "";

                $distributorName = Distributer::where('id', $purchesList->getpurches->distributor_id)->first();
                $dataIteams[$p]['distributor_name'] = isset($distributorName->name) ? $distributorName->name : "";
                $dataIteams[$p]['qty'] = isset($purchesList->qty) ? $purchesList->qty : "";
                $dataIteams[$p]['amount'] = isset($purchesList->amount) ? (string)round($purchesList->amount, 2) : "";
                $dataIteams[$p]['payment_type'] = isset($purchesList->payment_type) ? $purchesList->payment_type : "";
                $dataIteams[$p]['count'] =  isset($purchesCount) ? $purchesCount : "";

                $fr_qty_total += isset($purchesList->fr_qty) ? (int)$purchesList->fr_qty : 0;
                $qty_total += isset($purchesList->qty) ? (int)$purchesList->qty : 0;
                $purches_Id = isset($purchesList->getpurches->id) ? $purchesList->getpurches->id : "";
            }
        }
        return $this->sendResponse($dataIteams, ' Data Fetch Successfully');
    }

    public function purcheReturnItem(Request $request)
    {
        $userid = auth()->user();
        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
        $userId = array(auth()->user()->id);
        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

        $purchesDetails = PurchesReturnDetails::where('iteam_id', $request->id)->whereIn('user_id', $allUserId);
        if ((isset($request->start_date)) && (isset($request->end_date))) {
            $purchesDetails->whereHas('getPurchesReturn', function ($q) use ($request) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                $q->whereDate('select_date', '>=', $startDate)->whereDate('select_date', '<=', $endDate);
            });
        }
        if (isset($request->distributor_id)) {
            $purchesDetails->whereHas('getPurchesReturn', function ($q) use ($request) {
                $q->where('distributor_id', $request->distributor_id);
            });
        }

        if (isset($request->staff) && ($request->staff != 'All')) {
            if ($request->staff == 'owner') {
                $purchesDetails->where('user_id', auth()->user()->id);
            } else {
                $purchesDetails->where('user_id', $request->staff);
            }
        }
        if ($request->gst == 'true') {
            $purchesDetails->where('gst', '0');
        }
        if ($request->gst == 'false') {
            $purchesDetails->where('gst', '!=', '0');
        }
        $purchesDetailsCount = $purchesDetails->count();
        $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
        $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
        $offset = ($page - 1) * $limit;
        $purchesDetails = $purchesDetails->orderBy('id', 'DESC')->offset($offset)->limit($limit)->get();


        $dataIteams = [];
        $qty_total_return = 0;
        $fr_qty_total_return = 0;
        $purchesreturnId = [];
        if (isset($purchesDetails)) {
            foreach ($purchesDetails as $pr => $purchesListData) {
                $EntryName = User::where('id', $purchesListData->user_id)->first();
                $distributorName  = Distributer::where('id', $purchesListData->getPurchesReturn->distributor_id)->first();
                $dataIteams[$pr]['id'] = isset($purchesListData->id) ? $purchesListData->id : "";
                $dataIteams[$pr]['entry_by'] = isset($EntryName->name) ? $EntryName->name : "";
                $dataIteams[$pr]['purchase_return_id'] = isset($purchesListData->getPurchesReturn->id) ? $purchesListData->getPurchesReturn->id : "";
                $dataIteams[$pr]['bill_no'] = isset($purchesListData->getPurchesReturn->bill_no) ? $purchesListData->getPurchesReturn->bill_no : "";
                $dataIteams[$pr]['bill_date'] = isset($purchesListData->getPurchesReturn->select_date) ? $purchesListData->getPurchesReturn->select_date : "";
                $dataIteams[$pr]['distributor_name'] = isset($distributorName->name) ? $distributorName->name : "";
                $dataIteams[$pr]['qty'] = isset($purchesListData->qty) ? $purchesListData->qty : "";
                $dataIteams[$pr]['fr_qty'] = isset($purchesListData->fr_qty) ? $purchesListData->fr_qty : "";
                $dataIteams[$pr]['amount'] = isset($purchesListData->amount) ? round($purchesListData->amount, 2) : "";
                $dataIteams[$pr]['count'] = isset($purchesDetailsCount) ? $purchesDetailsCount : "";

                // Sum the qty and fr_qty
                $qty_total_return += isset($purchesListData->qty) ? $purchesListData->qty : 0;
                $fr_qty_total_return += isset($purchesListData->fr_qty) ? $purchesListData->fr_qty : 0;
                array_push($purchesreturnId, $purchesListData->getPurchesReturn->id);
            }
        }

        return $this->sendResponse($dataIteams, ' Data Fetch Successfully');
    }

    public function salesData(Request $request)
    {
        $userid = auth()->user();
        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
        $userId = array(auth()->user()->id);
        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

        $salesDetails = salesDetails::where('iteam_id', $request->id)->whereIn('user_id', $allUserId);
        if ((isset($request->start_date)) && (isset($request->end_date))) {
            $salesDetails->whereHas('getSales', function ($q) use ($request) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                $q->whereDate('bill_date', '>=', $startDate)->whereDate('bill_date', '<=', $endDate);
            });
        }

        if (isset($request->staff) && ($request->staff != 'All')) {
            if ($request->staff == 'owner') {
                $salesDetails->where('user_id', auth()->user()->id);
            } else {
                $salesDetails->where('user_id', $request->staff);
            }
        }
        if (isset($request->customer_mobileNo)) {
            $salesDetails->whereHas('getSales', function ($q) use ($request) {
                //$customerName  = CustomerModel::orWhere('name', 'like', '%' . $request->customer_mobileNo . '%')->
                //    orWhere('phone_number', 'like', '%' . $request->customer_mobileNo . '%')->pluck('id')->ToArray();
                $q->where('customer_id', $request->customer_mobileNo);
            });
        }
        $salesDetailsCount = $salesDetails->count();
        $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
        $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
        $offset = ($page - 1) * $limit;
        $salesDetails = $salesDetails->orderBy('id', 'DESC')->offset($offset)->limit($limit)->get();

        $dataIteams = [];
        $qty_total_sales = 0;
        $salesId = [];
        if (isset($salesDetails)) {
            foreach ($salesDetails as $s => $listSales) {
                $salesData = SalesModel::where('id', $listSales->sales_id)->first();
                $customerId = isset($salesData->customer_id) ? $salesData->customer_id : "0";
                $customerName  = CustomerModel::where('id', $customerId)->first();
                $EntryName = User::where('id', $listSales->user_id)->first();
                if (isset($customerName)) {
                    $dataIteams[$s]['id'] = isset($listSales->id) ? $listSales->id : "";
                    $dataIteams[$s]['entry_by'] = isset($EntryName->name) ? $EntryName->name : "";
                    $dataIteams[$s]['sales_id'] = isset($listSales->getSales->id) ? $listSales->getSales->id : "";
                    $dataIteams[$s]['bill_no'] = isset($listSales->getSales->bill_no) ? $listSales->getSales->bill_no : "";
                    $dataIteams[$s]['bill_date'] = isset($listSales->getSales->bill_date) ? $listSales->getSales->bill_date : "";
                    $dataIteams[$s]['customer_name'] = isset($customerName->name) ? $customerName->name : "";
                    $dataIteams[$s]['customer_number'] = isset($customerName->phone_number) ? $customerName->phone_number : "";
                    $dataIteams[$s]['qty'] = isset($listSales->qty) ? $listSales->qty : "";
                    $dataIteams[$s]['amt'] = isset($listSales->amt) ? (string)round($listSales->amt, 2) : "";
                    $dataIteams[$s]['count'] = isset($salesDetailsCount) ? $salesDetailsCount : "";

                    $qty_total_sales += isset($listSales->qty) ? $listSales->qty : 0;

                    array_push($salesId, $listSales->getSales->id);
                }
            }
        }
        return $this->sendResponse($dataIteams, ' Data Fetch Successfully');
    }

    public function salesReturnInvetory(Request $request)
    {
        $dataIteams = [];
        $salesreturnId = [];

        $userid = auth()->user();
        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
        $userId = array(auth()->user()->id);
        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

        $salesreturnDetails = SalesReturnDetails::where('iteam_id', $request->id)->whereIn('user_id', $allUserId);
        if ((isset($request->start_date)) && (isset($request->end_date))) {
            $salesreturnDetails->whereHas('getSales', function ($q) use ($request) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                $q->whereDate('date', '>=', $startDate)->whereDate('date', '<=', $endDate);
            });
        }

        if (isset($request->staff) && ($request->staff != 'All')) {
            if ($request->staff == 'owner') {
                $salesreturnDetails->where('user_id', auth()->user()->id);
            } else {
                $salesreturnDetails->where('user_id', $request->staff);
            }
        }
        if (isset($request->customer_mobileNo)) {

            $salesreturnDetails->whereHas('getSales', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_mobileNo);
                // $customerName  = CustomerModel::orWhere('name', 'like', '%' . $request->customer_mobileNo . '%')->
                // orWhere('phone_number', 'like', '%' . $request->customer_mobileNo . '%')->pluck('id')->ToArray();
                //$q->whereIn('customer_id',$customerName);
            });
        }
        $salesCount = $salesreturnDetails->count();
        $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
        $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
        $offset = ($page - 1) * $limit;
        $salesreturnDetails = $salesreturnDetails->orderBy('id', 'DESC')->offset($offset)->limit($limit)->get();

        if (isset($salesreturnDetails)) {
            foreach ($salesreturnDetails as $sr => $listData) {
                $salesReturnData = SalesReturn::where('id', $listData->sales_id)->first();

                $customerName = isset($salesReturnData->customer_id) ? $salesReturnData->customer_id : "";
                $EntryName = User::where('id', $listData->user_id)->first();
                $customerDetails  = CustomerModel::where('id', $customerName)->first();
                $dataIteams[$sr]['id'] = isset($listData->id) ? $listData->id : "";
                $dataIteams[$sr]['entry_by'] = isset($EntryName->name) ? $EntryName->name : "";
                $dataIteams[$sr]['sales_id'] = isset($listData->sales_id) ? $listData->sales_id : "";
                $dataIteams[$sr]['bill_no'] = isset($salesReturnData->bill_no) ? $salesReturnData->bill_no : "";
                $dataIteams[$sr]['bill_date'] = isset($salesReturnData->date) ? $salesReturnData->date : "";
                $dataIteams[$sr]['customer_name'] = isset($customerDetails->name) ? $customerDetails->name : "";
                $dataIteams[$sr]['customer_number'] = isset($customerDetails->phone_number) ? $customerDetails->phone_number : "";
                $dataIteams[$sr]['qty'] = isset($listData->qty) ? $listData->qty : "";
                $dataIteams[$sr]['amt'] = isset($listData->net_rate) ? $listData->net_rate : "";
                $dataIteams[$sr]['count'] = isset($salesCount) ? $salesCount : "";

                array_push($salesreturnId, $listData->sales_id);
            }
        }
        return $this->sendResponse($dataIteams, ' Data Fetch Successfully');
    }

    public function ledgerInvetory(Request $request)
    {
        $legerModelData  = LedgerModel::where('user_id', auth()->user()->id)->where('iteam_id', $request->id);
        $legerCount = $legerModelData->count();
        $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
        $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
        $offset = ($page - 1) * $limit;
        $legerModelData = $legerModelData->orderBy('id', 'DESC')->offset($offset)->limit($limit)->get();

        $dataIteams = [];
        if (isset($legerModelData)) {
            foreach ($legerModelData  as $leger => $listLegder) {
                $userData = User::where('id', auth()->user()->id)->first();
                $EntryName = User::where('id', $listLegder->user_id)->first();
                $dataIteams[$leger]['id'] = isset($listLegder->id) ? $listLegder->id : "";
                $dataIteams[$leger]['entry_by'] = isset($EntryName->name) ? $EntryName->name : "";
                $dataIteams[$leger]['transction'] = isset($listLegder->transction) ? $listLegder->transction : "";
                $dataIteams[$leger]['bill_date'] = isset($listLegder->bill_date) ?  date("d-m-Y", strtotime($listLegder->bill_date)) : "";
                $dataIteams[$leger]['batch'] = isset($listLegder->batch) ? $listLegder->batch : "";
                $dataIteams[$leger]['name'] = isset($listLegder->name) ? $listLegder->name : "";
                $dataIteams[$leger]['create_by'] = isset($userData->name) ? $userData->name : "";
                $dataIteams[$leger]['bill_no'] = isset($listLegder->bill_no) ? $listLegder->bill_no : "";
                $dataIteams[$leger]['credit'] = isset($listLegder->in) ? $listLegder->in : "";
                $dataIteams[$leger]['debit'] = isset($listLegder->out) ? $listLegder->out : "";
                $dataIteams[$leger]['balance'] = isset($listLegder->balance_stock) ? $listLegder->balance_stock : "";
                $dataIteams[$leger]['count'] = isset($legerCount) ? $legerCount : "";
            }
        }
        return $this->sendResponse($dataIteams, ' Data Fetch Successfully');
    }

    //thsi function use list iteams Data
    public function itemsList(Request $request)
    {
        try {

            $limit = 12;
            $iteamData = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->limit($limit);

            // Handle pagination
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $iteamData->offset($offset);

            // Handle other search/filter criteria
            if ($request->filled('search')) {
                // Handle alphabetical search
                if ($request->search == 'Name - A to Z') {
                    $iteamData->orderBy('iteam_name', 'asc');
                } elseif ($request->search == 'Name - Z to A') {
                    $iteamData->orderBy('iteam_name', 'desc');
                } elseif ($request->search == 'Manufacturer - A to Z') {
                    $iteamData->with(['getPharma' => function ($query) {
                        $query->orderBy('company_name', 'asc');
                    }]);
                } elseif ($request->search == 'Manufacturer - Z to A') {
                    $iteamData->with(['getPharma' => function ($query) {
                        $query->orderBy('company_name', 'desc');
                    }]);
                } elseif ($request->search == 'Entry Date - New to Old') {
                    $iteamData->orderBy('created_at', 'desc');
                } elseif ($request->search == 'Entry Date - Old to New') {
                    $iteamData->orderBy('created_at', 'asc');
                }
            }

            if ($request->medicine_name) {
                $medicineName = $request->medicine_name;
                // Capitalize the first letter
                $medicineName = ucfirst($medicineName);
                $iteamData->where('iteam_name', 'LIKE', '%' . $medicineName . '%');
            }
            if ($request->location) {
                //$iteamData->where('location', 'LIKE', '%' . $request->location . '%');
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $itemLocationItem = ItemLocation::where('location', 'like', '%' . $request->location . '%')->whereIn('user_id', $allUserId)->pluck('item_id')->toArray();
                $iteamData->whereIn('id', $itemLocationItem);
            }
            if ($request->manufacturer_name) {
                $manufacturerName = $request->manufacturer_name;
                $iteamData->where('pharma_shop', $manufacturerName);
            }

            $currentUser = auth()->user();
            $staffGetData = User::where('create_by', $currentUser->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', $currentUser->id)->pluck('create_by')->toArray();
            $userId = [$currentUser->id]; // Create an array with current user ID
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $subQuery = DB::table('batch')
                ->select('item_id', DB::raw('SUM(total_qty) as total_qty'))
                ->whereIn('user_id', $allUserId)
                ->whereNull('deleted_at')
                ->groupBy('item_id');

            $iteamData->leftJoinSub($subQuery, 'batch_totals', function ($join) {
                $join->on('iteams.id', '=', 'batch_totals.item_id');
            })
                ->select('iteams.*', DB::raw('COALESCE(batch_totals.total_qty, 0) as total_qty'))
                ->whereNull('iteams.deleted_at')
                ->orderBy('total_qty', 'desc');

            $iteamData = $iteamData->get();


            $dataDetails = [];
            if (isset($iteamData)) {
                foreach ($iteamData as $key => $list) {
                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                    $totalStock = BatchModel::where('item_id', $list->id)->whereIn('user_id', $allUserId)->sum('total_qty');
                    $uniteData = UniteTable::where('id', $list->old_unit)->first();
                    $unitName = isset($uniteData->name) ? $uniteData->name : "";
                    $company = CompanyModel::where('id', $list->pharma_shop)->first();

                    $iteamName = IteamsModel::find($list->id);
                    if (isset($iteamName)) {
                        $iteamName->stock = $totalStock;
                        $iteamName->update();
                    }
                    $dataDetails[$key]['id'] = isset($list->id) ? $list->id : "";
                    $dataDetails[$key]['iteam_name'] = isset($list->iteam_name) ? $list->iteam_name : "";
                    $totalIteam = PurchesDetails::whereIn('user_id', $allUserId)->where('iteam_id', $list->id)->orderBy('id', 'DESC')->first();
                    $dataDetails[$key]['weightage'] = isset($totalIteam->weightage) ? $totalIteam->weightage : $list->weightage;
                    $dataDetails[$key]['weightage'] = isset($list->weightage) ? $list->weightage : "";
                    $dataDetails[$key]['company'] = isset($company->company_name) ? $company->company_name : "";

                    $dataDetails[$key]['mrp'] = isset($list->mrp) ? $list->mrp : "";
                    $dataDetails[$key]['minimum'] = isset($list->minimum) ? $list->minimum : "";
                    $dataDetails[$key]['maximum'] = isset($list->maximum) ? $list->maximum : "";
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                    $itemLocation = ItemLocation::where('item_id', $list->id)->whereIn('user_id', $allUserId)->first();

                    $dataDetails[$key]['location'] = isset($itemLocation->location) ? $itemLocation->location : "";
                    $dataDetails[$key]['discount'] = isset($list->discount) ? $list->discount : "";
                    $batchDatas = BatchModel::where('item_id', $list->id)->whereIn('user_id', $allUserId)->first();
                    // $dataDetails[$key]['old_unit'] = isset( $batchDatas->unit) ? $batchDatas->unit :$unitName ;

                    $dataDetails[$key]['old_unit'] = isset($list->old_unit) ? $list->old_unit : "";
                  	$dataDetails[$key]['unit'] = isset($list->unit) ? $list->unit : "";
                    $dataDetails[$key]['gst'] = isset($list->gst) ? $list->gst : "";
                    $dataDetails[$key]['item_type'] = isset($list->item_type) ? $list->item_type : "";
                    $dataDetails[$key]['packing_type'] = isset($list->packing_type) ? $list->packing_type : "";
                    $dataDetails[$key]['packing_size'] = isset($list->packing_size) ? $list->packing_size : "";
                    $dataDetails[$key]['hsn_code'] = isset($list->hsn_code) ? $list->hsn_code : "";
                    $dataDetails[$key]['mrp'] = isset($list->mrp) ? $list->mrp : "";
                    $dataDetails[$key]['stock'] = isset($totalStock) ? $totalStock : "";
                    $dataDetails[$key]['front_photo'] = isset($list->front_photo) ? asset('/public/front_photo/' . $list->front_photo) : "";
                    $dataDetails[$key]['back_photo'] = isset($list->back_photo) ? asset('/public/back_photo/' . $list->back_photo) : "";
                    $dataDetails[$key]['mrp_photo'] = isset($list->mrp_photo) ? asset('/public/mrp_photo/' . $list->mrp_photo) : "";
                }
            }

            return $this->sendResponse($dataDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Iteams List api" . $e->getMessage());
            return $e->getMessage();
        }
    }
    //this function use delete iteam
    public function itemsDelete(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => 'Enter Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $iteamData = IteamsModel::where('id', $request->id)->orderBy('id', 'DESC')->first();
            if (isset($iteamData)) {
                $iteamData->delete();
            }

            return $this->sendResponse('', 'Data Deleted Successfully');
        } catch (\Exception $e) {
            Log::info("Iteams Delete api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // public function itemImport(Request $request)
    // {
    //     try {

    //         $file = $request->file;
    //         $filePath = $file->getRealPath();

    //         $data = array_map('str_getcsv', file($filePath));
    //         array_shift($data);

    //         if (isset($data)) {
    //             foreach ($data as $list) {

    //                 $iteamsData = new IteamsModel;
    //                 $iteamsData->iteam_name = isset($list[0]) ? $list[0] : "";
    //                 $packageData = Package::where('packging_name', $list[1])->first();
    //                 if (isset($packageData)) {
    //                     $packageId = $packageData->id;
    //                 } 
    //                //else {
    //                  //   if (isset($list[1])) {
    //                    //     $package = new Package;
    //                      //   $package->packging_name = isset($list[1]) ? $list[1] : "";
    //                       //  $package->save();
    //                        // $packageId = $package->id;
    //                     //}
    //                 //}

    //                 $iteamsData->packaging_id = isset($packageId) ? $packageId : "";
    //                 $uniteData = UniteTable::where('name', $list[2])->first();
    //                 if (isset($uniteData)) {
    //                     $uniteId = $uniteData->id;
    //                 } else {
    //                     if (isset($list[2])) {
    //                         $unit = new UniteTable;

    //                         $unit->name = isset($list[2]) ? $list[2] : "";
    //                         $unit->package_id = isset($iteamsData->packaging_id) ? $iteamsData->packaging_id : "";
    //                         $unit->save();
    //                         $uniteId = $unit->id;
    //                     }
    //                 }
    //                 $iteamsData->unit = isset($uniteId) ? $uniteId : "";
    //                 $iteamsData->weightage = isset($list[3]) ? $list[3] : "";
    //                 $iteamsData->packing_size =  isset($list[4]) ? $list[4] : "";

    //                 $companyData = CompanyModel::where('company_name', $list[5])->first();
    //                 if (isset($companyData)) {
    //                     $companyId = $companyData->id;
    //                 } 
    //                 else {
    //                   if (isset($list[5])) {
    //                        $company = new CompanyModel;
    //                         $company->company_name = isset($list[5]) ? $list[5] : "";
    //                         $company->save();
    //                        $companyId = $company->id;
    //                   }
    //                 }
    //                 $iteamsData->pharma_shop = isset($companyId) ? $companyId : "";

    //                 if (isset($list[6])) {
    //                     $distributerData = Distributer::where('id', $list[6])->first();
    //                     if (isset($distributerData)) {
    //                         $distributerId = $distributerData->id;
    //                     } else {
    //                         if ($list[6] != "") {
    //                             $distributorData = new Distributer;
    //                             $distributorData->name = isset($list[6]) ? $list[6] : "";
    //                             $distributorData->status = '1';
    //                             $distributorData->role = '4';
    //                             $distributorData->save();
    //                             $distributerId = $distributorData->id;
    //                         }
    //                     }
    //                     $iteamsData->distributer_id = isset($distributerId) ? $distributerId : "";
    //                 }
    //                 $getData = GstModel::where('name', $list[7])->first();
    //                 if (isset($getData)) {
    //                     $gstId = $getData->id;
    //                 } 
    //                 $iteamsData->gst = isset($gstId) ? $gstId : "";

    //                 $categoryData = ItemCategory::where('category_name', $list[8])->first();
    //                 if (isset($categoryData)) {
    //                     $catgeoyrId = $categoryData->id;
    //                 } 

    //                 $iteamsData->item_category_id = isset($catgeoyrId) ? $catgeoyrId : "";

    //                 $drugGroup = DrugGroup::where('name', $list[9])->first();
    //                 if (isset($drugGroup)) {
    //                     $drugGroupId = $drugGroup->id;
    //                 } else {
    //                     if (isset($list[1])) {
    //                         $drugs = new DrugGroup;
    //                         $drugs->name = isset($list[9]) ? $list[9] : "";
    //                         $drugs->save();
    //                         $drugGroupId = $drugs->id;
    //                     }
    //                 }
    //                 $iteamsData->drug_group = isset($drugGroupId) ? $drugGroupId : "";

    //                 $iteamsData->accept_online_order = isset($list[11]) ? $list[11] : "";
    //                 $iteamsData->mrp = isset($list[12]) ? $list[12] : "";
    //                 $iteamsData->barcode = isset($list[13]) ? $list[13] : "";
    //                 $iteamsData->hsn_code = isset($list[14]) ? $list[14] : "";
    //                 $iteamsData->message = isset($list[15]) ? $list[15] : "";
    //                 $iteamsData->prescription_required = isset($list[16]) ? $list[16] : "";
    //                 $iteamsData->label = isset($list[17]) ? $list[17] : "";
    //                 $iteamsData->fact_box = isset($list[18]) ? $list[18] : "";
    //                 $iteamsData->primary_use = isset($list[19]) ? $list[19] : "";
    //                 $iteamsData->storage = isset($list[20]) ? $list[20] : "";
    //                 $iteamsData->use_of = isset($list[21]) ? $list[21] : "";
    //                 $iteamsData->common_side_effect = isset($list[22]) ? $list[22] : "";
    //                 $iteamsData->alcohol_Interaction = isset($list[23]) ? $list[23] : "";
    //                 $iteamsData->pregnancy_Interaction = isset($list[24]) ? $list[24] : "";
    //                 $iteamsData->lactation_Interaction = isset($list[25]) ? $list[25] : "";
    //                 $iteamsData->driving_Interaction     = isset($list[26]) ? $list[26] : "";
    //                 $iteamsData->kidney_Interaction     = isset($list[27]) ? $list[27] : "";
    //                 $iteamsData->liver_Interaction     = isset($list[28]) ? $list[28] : "";
    //                 $iteamsData->manufacture_address     = isset($list[29]) ? $this->sanitize_string($list[29]) : "";
    //                 $iteamsData->country_of_origin     = isset($list[30]) ? $list[30] : "";
    //                 $iteamsData->q_a =  isset($list[31]) ? $this->sanitize_string($list[31]) : "";
    //                 $iteamsData->save();
    //               if(isset($list[10]))
    //               {
    //                 $itemLocation = new ItemLocation;
    //                 $itemLocation->user_id = auth()->user()->id;
    //                 $itemLocation->item_id =  $iteamsData->id;
    //                 $itemLocation->location = isset($list[10]) ? $list[10] : "";
    //                 $itemLocation->save();
    //               }

    //             }
    //         }
    //         return $this->sendResponse([], 'Data Import Successfully');
    //     } catch (\Exception $e) {
    //         dD($e);
    //         Log::info("Iteams import api" . $e->getMessage());
    //         return $e->getMessage();
    //     }
    // }

    public function itemImport(Request $request)
    {
        try {
            $file = $request->file;
            $filePath = $file->getRealPath();

            $data = array_map('str_getcsv', file($filePath));
            // array_shift($data);
            $filteredData = array_slice($data, 5);

            if (isset($filteredData)) {
                foreach ($filteredData as $list) {
                    $iteamName = isset($list[1]) ? $list[1] : "";
                    $iteamDetails = IteamsModel::where('id', $iteamName)->first();
                    if (empty($iteamDetails)) {
                        $iteamDetails = new IteamsModel;
                        $iteamDetails->iteam_name = isset($list[1]) ? $list[1] : "";
                        $iteamDetails->user_id = auth()->user()->id;
                        $iteamDetails->save();
                        $iteamDetails = IteamsModel::where('id', $iteamDetails->iteam_name)->first();
                    }

                    if (isset($iteamDetails)) {
                        $batchData = new BatchModel;
                        $batchData->user_id = auth()->user()->id;
                        $batchData->unit = isset($list[2]) ? $list[2] : '';
                        $batchData->batch_number = isset($list[3]) ? $list[3] : '';
                        $batchData->batch_name = isset($list[3]) ? $list[3] : '';
                        $batchData->total_qty = isset($list[4]) ? $list[4] : '';
                        $batchData->qty = isset($list[4]) ? $list[4] : '';
                        $batchData->purches_qty = isset($list[4]) ? $list[4] : '';
                        $batchData->mrp = isset($list[6]) ? $list[6] : '';
                        $batchData->ptr = isset($list[8]) ? $list[8] : '';
                        $batchData->expiry_date = str_replace("'", "", $list[10]);
                        $batchData->save();
                    }
                }
            }
          
            return $this->sendResponse([], 'Item Data Import Successfully.');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Iteams import api" . $e->getMessage());
            return $e->getMessage();
        }
    }
  
  	public function testingItemDataImport(Request $request)
    {
    	if ($request->hasFile('item_data')) {
            $file = $request->file('item_data');

            $data = [];
            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    $data[] = $row;
                }
                fclose($handle);
            }

            foreach (array_slice($data, 1) as $row) {
                $item_data = new IteamsModel();
                $item_data->iteam_name = !empty($row[1]) ? $row[1] : null;
                $item_data->old_unit = !empty($row[2]) ? $row[2] : null;
                $item_data->mrp = !empty($row[6]) ? $row[6] : null;
                $item_data->location = !empty($row[9]) ? $row[9] : null;
              	$item_data->user_id = auth()->user()->id;
                $item_data->save();
            }

            return $this->sendResponse([], 'Item Data Import Successfully.');
        }else
        {
        	return $this->sendError('Please select file');
        }
    }

    function sanitize_string($string)
    {
        return preg_replace('/[^\x20-\x7E\x0A]/', '', $string); // Removes non-printable characters
    }

    // this function use edit item
    public function editIteams(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => 'Enter Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $iteamData = IteamsModel::where('id', $request->id)->orderBy('id', 'DESC')->first();

            $iteamDetails = [];
            if (isset($iteamData)) {

                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                $itemLocation = ItemLocation::where('item_id', $iteamData->id)->whereIn('user_id', $allUserId)->first();

                $iteamDetails['id'] = isset($iteamData->id) ? $iteamData->id : "";
                $iteamDetails['mrp'] = isset($iteamData->mrp) ? $iteamData->mrp : "";
                $iteamDetails['hsn_code'] = isset($iteamData->hsn_code) ? $iteamData->hsn_code : "";
                $iteamDetails['message'] = isset($iteamData->message) ? $iteamData->message : "";
                $iteamDetails['iteam_name'] = isset($iteamData->iteam_name) ? $iteamData->iteam_name : "";
                $iteamDetails['old_unit'] = isset($iteamData->old_unit) ? $iteamData->old_unit : "";
                $iteamDetails['gst'] = isset($iteamData->gst) ? $iteamData->gst : "";
                $iteamDetails['packing_type'] = isset($iteamData->getPackage->packging_name) ? $iteamData->getPackage->packging_name : "";
                $iteamDetails['pharma_shop'] = isset($iteamData->getPharma->pharma_name) ? $iteamData->getPharma->pharma_name : "";
                $iteamDetails['distributer'] = isset($iteamData->getDistibuter->name) ? $iteamData->getDistibuter->name : "";
                $iteamDetails['drug_group'] = isset($iteamData->drug_group) ? $iteamData->drug_group : "";
                $iteamDetails['barcode'] = isset($iteamData->barcode) ? $iteamData->barcode : "";
                $iteamDetails['schedule'] = isset($iteamData->schedule) ? $iteamData->schedule : "";
                $iteamDetails['tax'] = isset($iteamData->tax) ? $iteamData->tax : "";
                $iteamDetails['discount'] = isset($iteamData->discount) ? $iteamData->discount : "";
                $iteamDetails['margin'] = isset($iteamData->margin) ? $iteamData->margin : "";
                $iteamDetails['tax_not_applied'] = isset($iteamData->tax_not_applied) ? $iteamData->tax_not_applied : "";
                $iteamDetails['item_type'] = isset($iteamData->item_type) ? $iteamData->item_type : "";
                $iteamDetails['loaction'] = isset($itemLocation->location) ? $itemLocation->location : "";
                $iteamDetails['minimum'] = isset($iteamData->minimum) ? $iteamData->minimum : "";
                $iteamDetails['maximum'] = isset($iteamData->maximum) ? $iteamData->maximum : "";
                $iteamDetails['item_category_id'] = isset($iteamData->getCategory->category_name) ? $iteamData->getCategory->category_name : "";
                $iteamDetails['packaging_id'] = isset($iteamData->getPackage->packging_name) ? $iteamData->getPackage->packging_name : "";
                $iteamDetails['category_id'] = isset($iteamData->item_category_id) ? $iteamData->item_category_id : "";
                $iteamDetails['drug_group_id'] = isset($iteamData->drug_group) ? $iteamData->drug_group : "";
                $iteamDetails['company_id'] = isset($iteamData->pharma_shop) ? $iteamData->pharma_shop : "";
                $iteamDetails['distributor_id'] = isset($iteamData->distributer_id) ? $iteamData->distributer_id : "";
                $iteamDetails['package_id'] = isset($iteamData->packaging_id) ? $iteamData->packaging_id : "";
                $iteamDetails['front_photo'] = isset($iteamData->front_photo) ? asset('/public/front_photo/' . $iteamData->front_photo) : "";
                $iteamDetails['back_photo'] = isset($iteamData->back_photo) ? asset('/public/back_photo/' . $iteamData->back_photo) : "";
                $iteamDetails['mrp_photo'] = isset($iteamData->mrp_photo) ? asset('/public/mrp_photo/' . $iteamData->mrp_photo) : "";
            }
            return $this->sendResponse($iteamDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Iteams Edit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use update iteam
    public function updateIteams(Request $request)
    {
        try {
            $iteamsData = IteamsModel::where('id', $request->id)->first();
            if (empty($iteamsData)) {
                return $this->sendError('Id Not Found');
            }
            if (isset($request->pahrma)) {
                $iteamsData->pharma_shop = isset($request->pahrma) ? $request->pahrma : "";
            }
            if (isset($request->item_category_id)) {
                $iteamsData->item_category_id = isset($request->item_category_id) ? $request->item_category_id : '';
            }
            if (isset($request->drug_group)) {
                $iteamsData->drug_group = isset($request->drug_group) ? $request->drug_group : "";
            }
            if (isset($request->packaging_id)) {
                $iteamsData->packaging_id = isset($request->packaging_id) ? $request->packaging_id : "";
            }
            if (isset($request->location)) {
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                $itemLocation = ItemLocation::where('item_id', $iteamsData->id)->whereIn('user_id', $allUserId)->first();
                if (isset($itemLocation)) {
                    $itemLocation->location = $request->location;
                    $itemLocation->update();
                } else {

                    $itemLocation = new ItemLocation;
                    $itemLocation->user_id = auth()->user()->id;
                    $itemLocation->item_id =  $iteamsData->id;
                    $itemLocation->location = $request->location;
                    $itemLocation->save();
                }
            }

            $iteamsData->update();

            $dataDetails = [];
            $dataDetails['id'] = isset($iteamsData->id) ? $iteamsData->id : "";
            return $this->sendResponse($dataDetails, 'Iteam Updated Successfully.');
        } catch (\Exception $e) {
            Log::info("Iteams Update api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use item search
    public function itemSearch(Request $request)
    {
        try {
            if ((isset($request->item)) && ($request->item == 'all_Items')) {
                $currentUser = auth()->user();
                $staffGetData = User::where('create_by', $currentUser->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', $currentUser->id)->pluck('create_by')->toArray();
                $userId = [$currentUser->id];
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                // Subquery to get the item IDs with total_qty and exclude deleted_at records
                $subQuery = DB::table('batch')
                    ->select('item_id', DB::raw('SUM(total_qty) as total_qty'))
                    ->whereIn('user_id', $allUserId)
                    ->whereNull('deleted_at') // Exclude deleted_at records
                    ->groupBy('item_id');

                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 12;
                $offset = ($page - 1) * $limit;

                // Main query to get items with total_qty
                $iteamData = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->leftJoinSub($subQuery, 'batch_totals', function ($join) {
                    $join->on('iteams.id', '=', 'batch_totals.item_id');
                })
                    ->select('iteams.*', DB::raw('COALESCE(batch_totals.total_qty, 0) as total_qty'))
                    ->whereNull('iteams.deleted_at') // Ensure soft-deleted items are excluded
                    ->orderBy('total_qty', 'desc') // Order by total_qty
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
                $offset = ($page - 1) * $limit;
                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);
              	$allUserId2 = array_filter($allUserId);

                // $iteamData = IteamsModel::whereNull('user_id')->orWhereIn('user_id', $allUserId2)->offset($offset)->limit($limit);
              	// $iteamData = IteamsModel::orderBy('id','DESC')->offset($offset)->limit($limit);
                $iteamData = IteamsModel::where(function($q) use ($allUserId2) {
                    $q->whereNull('user_id')
                      ->orWhereIn('user_id', $allUserId2);
                })
                ->offset($offset)
                ->limit($limit);
              
              	$itemDataTotalCount = IteamsModel::where('user_id',$userid->id)->count();

                if ((isset($request->items_with_missing_hsn))) {
                    $categoryData = IteamsModel::whereNull('user_id')->orWhereIn('user_id', $allUserId)->whereNull('item_category_id')->count();
                    $iteamData->whereNull('hsn_code');
                }

                if ((isset($request->items_with_missing_category))) {
                    $iteamData->whereNull('item_category_id');
                }

                if ((isset($request->items_with_missing_location))) {
                    $IteamIds = ItemLocation::whereIn('user_id', $allUserId)->pluck('item_id')->toArray();
                    $iteamData->whereNotIn('id', $IteamIds);
                }

                if ((isset($request->items_with_invalid_mrp))) {
                    $countIteam = PurchesDetails::whereRaw("CAST(ptr AS DECIMAL(10,2)) <= CAST(mrp AS DECIMAL(10,2))")
                        ->whereIn('user_id', $allUserId)
                        ->whereRaw("mrp REGEXP '^[0-9]+(\.[0-9]+)?$'") // Only numeric values in `mrp`
                        ->pluck('iteam_id')
                        ->toArray();

                    $iteamData->whereNotIn('id', $countIteam);
                }

                if ((isset($request->items_with_invalid_price))) {
                    $countIteamPrice = PurchesDetails::whereRaw("CAST(mrp AS DECIMAL(10,2)) >= CAST(ptr AS DECIMAL(10,2))")
                        ->whereIn('user_id', $allUserId)
                        ->whereRaw("mrp REGEXP '^[0-9]+(\.[0-9]+)?$'") // Only numeric values in `mrp`
                        ->pluck('iteam_id')
                        ->toArray();
                    $iteamData->whereNotIn('id', $countIteamPrice);
                }

                // if ((isset($request->search)) && ($request->search != "")) {
                    // $drugGroupIds = DrugGroup::where('name', 'like', '%' . $request->search . '%')->pluck('id')->toArray();
                    // $iteamData->orWhere('iteam_name', 'like', '%' . $request->search . '%')->orWhereIn('drug_group', $drugGroupIds);
                  	// $iteamData->orWhere('iteam_name', 'like', '%' . $request->search . '%');
                // }

                if (isset($request->search)) {
                    $drugGroupIds = DrugGroup::where('name', 'like', '%' . $request->search . '%')->pluck('id')->toArray();
                    // $iteamData->orWhere('iteam_name', 'like', '%' . $request->search . '%')->orWhereIn('drug_group', $drugGroupIds);
                  	// dd($iteamData->pluck('iteam_name')->toArray(),$iteamData->where('iteam_name', $request->search)->first());
                  	$iteamData->where('iteam_name', 'like', '%' . $request->search . '%');
                }

                if ($request->sort_by) {
                    if ($request->sort_by == 'Item Name - A to Z') {
                        $iteamData->orderByRaw('iteam_name COLLATE utf8mb4_unicode_ci asc');
                    } elseif ($request->sort_by == 'Item Name - Z to A') {
                        $iteamData->orderByRaw('iteam_name COLLATE utf8mb4_unicode_ci desc');
                    } elseif ($request->sort_by == 'Stock - 1 to 9') {
                        $currentUser = auth()->user();
                        $staffGetData = User::where('create_by', $currentUser->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', $currentUser->id)->pluck('create_by')->toArray();
                        $userId = [$currentUser->id];
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                        $subQuery = DB::table('batch')
                            ->select('item_id', DB::raw('SUM(total_qty) as total_qty'))
                            ->whereIn('user_id', $allUserId)
                            ->whereNull('deleted_at')
                            ->groupBy('item_id');

                        $iteamData->leftJoinSub($subQuery, 'batch_totals', function ($join) {
                            $join->on('iteams.id', '=', 'batch_totals.item_id');
                        })
                            ->select('iteams.*', DB::raw('COALESCE(batch_totals.total_qty, 0) as total_qty'))
                            ->whereNull('iteams.deleted_at')
                            ->orderBy('total_qty', 'asc');
                    } elseif ($request->sort_by == 'Stock - 9 to 1') {
                        $currentUser = auth()->user();
                        $staffGetData = User::where('create_by', $currentUser->id)->pluck('id')->toArray();
                        $ownerGet = User::where('id', $currentUser->id)->pluck('create_by')->toArray();
                        $userId = [$currentUser->id]; // Create an array with current user ID
                        $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                        $subQuery = DB::table('batch')
                            ->select('item_id', DB::raw('SUM(total_qty) as total_qty'))
                            ->whereIn('user_id', $allUserId)
                            ->whereNull('deleted_at')
                            ->groupBy('item_id');

                        $iteamData->leftJoinSub($subQuery, 'batch_totals', function ($join) {
                            $join->on('iteams.id', '=', 'batch_totals.item_id');
                        })
                            ->select('iteams.*', DB::raw('COALESCE(batch_totals.total_qty, 0) as total_qty'))
                            ->whereNull('iteams.deleted_at')
                            ->orderBy('total_qty', 'desc');
                    } elseif ($request->sort_by == 'Distributor - A to Z') {
                        $iteamData = $iteamData->join('supplier_details', 'iteams.distributer_id', '=', 'supplier_details.id')
                            ->orderByRaw('supplier_details.name COLLATE utf8mb4_unicode_ci asc')
                            ->select('iteams.*')
                            ->whereNull('iteams.deleted_at');
                    } elseif ($request->sort_by == 'Distributor - Z to A') {
                        // $iteamData = $iteamData->join('supplier_details', 'iteams.distributer_id', '=', 'supplier_details.id')
                            // ->orderByRaw('supplier_details.name COLLATE utf8mb4_unicode_ci desc')
                            // ->select('iteams.*')
                            // ->whereNull('iteams.deleted_at');
                    } elseif ($request->sort_by == 'Location - A to Z') {

                        $iteamData->join('item_location', 'iteams.id', '=', 'item_location.item_id')
                            ->orderByRaw('item_location.location COLLATE utf8mb4_unicode_ci asc')
                            ->select('iteams.*', 'item_location.location')
                            ->whereNull('iteams.deleted_at');
                    } elseif ($request->sort_by == 'Location - Z to A') {
                        $iteamData->join('item_location', 'iteams.id', '=', 'item_location.item_id')
                            ->orderByRaw('item_location.location COLLATE utf8mb4_unicode_ci desc')
                            ->select('iteams.*', 'item_location.location')
                            ->whereNull('iteams.deleted_at');
                    }
                } else {
                    $currentUser = auth()->user();
                    $staffGetData = User::where('create_by', $currentUser->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', $currentUser->id)->pluck('create_by')->toArray();
                    $userId = [$currentUser->id];
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                    $subQuery = DB::table('batch')
                        ->select('item_id', DB::raw('SUM(total_qty) as total_qty'))
                        ->whereIn('user_id', $allUserId)
                        ->whereNull('deleted_at')
                        ->groupBy('item_id');

                    $iteamData->leftJoinSub($subQuery, 'batch_totals', function ($join) {
                        $join->on('iteams.id', '=', 'batch_totals.item_id');
                    })
                        ->select('iteams.*', DB::raw('COALESCE(batch_totals.total_qty, 0) as total_qty'))
                        ->whereNull('iteams.deleted_at')
                        ->orderBy('total_qty', 'desc');
                }
                if (isset($request->distributer_id)) {
                    $distributerId = Distributer::where('name', 'like', '%' . $request->distributer_id . '%')->pluck('id')->ToArray();
                    $iteamData->whereIn('distributer_id', $distributerId);
                }

                if ((isset($request->item)) && ($request->item == 'discontinued_items')) {
                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $totalStock = BatchModel::whereIn('user_id',  $allUserId)
                        ->select('item_id', DB::raw('SUM(total_qty) as total_qty'))
                        ->groupBy('item_id')
                        ->get();

                    $iteamDatas = [];

                    foreach ($totalStock as $stock) {
                        if ($stock->total_qty == 0) {
                            $iteamDatas[] = $stock->item_id;
                        }
                    }
                    $iteamData->whereIn('id', $iteamDatas);
                }

                if ((isset($request->item)) && ($request->item == 'only_Not_Set_HSN_Code')) {
                    $iteamData->whereNull('hsn_code');
                }
                if (isset($request->barcode)) {
                    $iteamData->where('barcode', $request->barcode);
                }
                if ((isset($request->item)) && ($request->item == 'only_Not_Set_Categories')) {
                    $iteamData->whereNull('item_category_id');
                }
                if ((isset($request->item)) && ($request->item == 'only_Newly_Added_Items')) {
                    $currentMonth = Carbon::now()->month;
                    $currentYear = Carbon::now()->year;

                    $iteamData->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear);
                }
                if (isset($request->category)) {
                    $category = explode(',', $request->category);
                    $iteamData->whereIn('item_category_id', $category);
                }
                if (isset($request->package)) {
                    $package = explode(',', $request->package);
                    $iteamData->whereIn('packaging_id', $package);
                }

                if ($request->expired == 'expired') {
                    $currentDate = Carbon::now();

                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $batchData = BatchModel::whereRaw("STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') < ?", [$currentDate])
                        ->whereIn('user_id', $allUserId)
                        ->whereNull('deleted_at')
                        ->pluck('item_id')
                        ->toArray();
                    $iteamData->whereIn('id', $batchData);
                }
                if ($request->expired == 'next_month') {
                    $currentDate = Carbon::now(); // Get the current date and time

                    // Calculate the start of next month
                    $startOfNextMonth = $currentDate->copy()->addMonth()->startOfMonth();

                    // Calculate the end of next month
                    $endOfNextMonth = $startOfNextMonth->copy()->endOfMonth();

                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    // Construct the query to fetch next month's expiry batch items
                    $batchData = BatchModel::whereRaw("STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') >= ? AND STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') <= ?", [$startOfNextMonth, $endOfNextMonth])
                        ->whereIn('user_id', $allUserId) // Assuming you have user authentication
                        ->whereNull('deleted_at') // Assuming soft deletes and checking for not deleted records
                        ->pluck('item_id')
                        ->toArray();

                    $iteamData->whereIn('id', $batchData);
                }
                if ($request->expired == 'next_two_month') {
                    $currentDate = Carbon::now(); // Get the current date and time

                    // Calculate the start of next month
                    $startOfNextMonth = $currentDate->copy()->addMonth()->startOfMonth();

                    // Calculate the end of two months later
                    $endOfTwoMonthsLater = $currentDate->copy()->addMonths(2)->endOfMonth();

                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    // Construct the query to fetch next two months' expiry batch items
                    $batchData = BatchModel::whereRaw("STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') >= ? AND STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') <= ?", [$startOfNextMonth, $endOfTwoMonthsLater])
                        ->whereIn('user_id', $allUserId) // Assuming you have user authentication
                        ->whereNull('deleted_at') // Assuming soft deletes and checking for not deleted records
                        ->pluck('item_id')
                        ->toArray();
                    $iteamData->whereIn('id', $batchData);
                }

                if ($request->expired == 'next_three_month') {
                    $currentDate = Carbon::now();

                    // Calculate the start of next month
                    $startOfNextMonth = $currentDate->copy()->addMonth()->startOfMonth();

                    // Calculate the end of three months later
                    $endOfThreeMonthsLater = $currentDate->copy()->addMonths(3)->endOfMonth();
                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $batchData = BatchModel::whereRaw("STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') >= ? AND STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y') <= ?", [$startOfNextMonth, $endOfThreeMonthsLater])
                        ->whereIn('user_id', $allUserId)
                        ->whereNull('deleted_at')
                        ->pluck('item_id')
                        ->toArray();
                    $iteamData->whereIn('id', $batchData);
                }

                $userId = auth()->user()->id;

                $iteamDatas = [];
                if ($request->stock == '0_15') {
                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $totalStock = BatchModel::whereIn('user_id', $allUserId)
                        ->select('item_id', DB::raw('SUM(total_qty) as total_qty'))
                        ->groupBy('item_id')
                        ->having('total_qty', '>=', 0)
                        ->having('total_qty', '<=', 15)
                        ->get();

                    $iteamDatas = [];

                    foreach ($totalStock as $stock) {
                        $iteamDatas[] = $stock->item_id;
                    }
                    $iteamData->whereIn('id', $iteamDatas);
                } elseif ($request->stock == '15_30') {
                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $totalStock = BatchModel::whereIn('user_id',  $allUserId)
                        ->select('item_id', DB::raw('SUM(total_qty) as total_qty'))
                        ->groupBy('item_id')
                        ->having('total_qty', '>=', 15)
                        ->having('total_qty', '<=', 30)
                        ->get();

                    $iteamDatas = [];

                    foreach ($totalStock as $stock) {
                        $iteamDatas[] = $stock->item_id;
                    }
                    $iteamData->whereIn('id', $iteamDatas);
                } elseif ($request->stock == 'above_30') {
                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $totalStock = BatchModel::whereIn('user_id', $allUserId)
                        ->select('item_id', DB::raw('SUM(total_qty) as total_qty'))
                        ->groupBy('item_id')
                        ->having('total_qty', '>=', 30)
                        ->get();

                    $iteamDatas = [];

                    foreach ($totalStock as $stock) {
                        $iteamDatas[] = $stock->item_id;
                    }
                    $iteamData->whereIn('id', $iteamDatas);
                }

                if ((isset($request->stock)) && ($request->stock == 'minus_one')) {
                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);
                    $totalStock = BatchModel::whereIn('user_id', $allUserId)
                        ->select('item_id', DB::raw('SUM(total_qty) as total_qty'))
                        ->groupBy('item_id')
                        ->having('total_qty', '<', 0)
                        ->get();

                    $iteamDatas = [];

                    foreach ($totalStock as $stock) {
                        $iteamDatas[] = $stock->item_id;
                    }

                    $iteamData->whereIn('id', $iteamDatas);
                }

                if (isset($request->manufacturer)) {

                    $company = $request->manufacturer;
                    $iteamData->where('pharma_shop', $company);
                }
                if (isset($request->drug_group)) {
                    $iteamData->where('drug_group', 'like', '%' . $request->drug_group . '%');
                }
                if (isset($request->gst)) {
                    $gst = explode(',', $request->gst);
                    $iteamData->whereIn('gst', $gst);
                }
                if (isset($request->location)) {
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $itemLocationItem = ItemLocation::where('location', 'like', '%' . $request->location . '%')->whereIn('user_id', $allUserId)->pluck('item_id')->toArray();
                    $iteamData->whereIn('id', $itemLocationItem);
                    // $iteamData->where('location','like', '%' .$request->location. '%')->orderBy('stock', 'desc');
                }
                if (isset($request->hsn_code)) {
                    $iteamData->where('hsn_code', 'like', '%' . $request->hsn_code . '%');
                }

                if ((isset($request->margin_start)) && (isset($request->margin_end))) {
                    $startMargin = (float) $request->margin_start;
                    $endMargin = (float) $request->margin_end;

                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $marginData = BatchModel::whereIn('user_id', $allUserId)
                        ->whereRaw("ROUND(margin, 2) BETWEEN ? AND ?", [$startMargin, $endMargin])
                        ->pluck('item_id')
                        ->toArray();

                    $iteamData->whereIn('id', $marginData);
                }

                $iteamData = $iteamData->get();

                if ((isset($request->mrp_start)) && (isset($request->mrp_end))) {

                    $startMRP = (int)$request->mrp_start;
                    $endMRP = (int)$request->mrp_end;
                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $totalStock = BatchModel::whereIn('user_id', $allUserId)
                        ->whereBetween('mrp', [$startMRP, $endMRP])
                        ->distinct()
                        ->pluck('item_id')->toArray();

                    $iteamData = IteamsModel::whereIn('id', $totalStock);
                    $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                    $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 12;
                    $offset = ($page - 1) * $limit;
                    $iteamData =  $iteamData->offset($offset)->limit($limit)->get();
                }

                if ((isset($request->ptr_start)) && (isset($request->ptr_end))) {
                    $startPtr = (int)$request->ptr_start;
                    $endPtr = (int)$request->ptr_end;

                    $userid = auth()->user();
                    $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                    $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                    $userId = array(auth()->user()->id);
                    $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                    $totalPtr = BatchModel::whereIn('user_id', $allUserId)
                        ->whereBetween('ptr', [$startPtr, $endPtr])
                        ->distinct()
                        ->pluck('item_id')->toArray();

                    $iteamData = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->whereIn('id', $totalPtr);

                    $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                    $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 12;
                    $offset = ($page - 1) * $limit;
                    $iteamData =  $iteamData->offset($offset)->limit($limit)->get();
                }
            }

            //  if ((isset($request->search)) && ($request->search != "")) {
            //  $itemData = IteamsModel::where('iteam_name', $request->search)->first();

            //  $authUser = auth()->user();
            //  $staffGetData = User::where('create_by', $authUser->id)->pluck('id')->toArray();
            //  $ownerGet = User::where('id', $authUser->id)->pluck('create_by')->toArray();
            //  $userId = [$authUser->id];
            //  $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            //  $totalStock = BatchModel::whereIn('user_id', $allUserId)
            //  	->where('item_id', $itemData->id)
            //  	->sum('total_qty');
          	
            //  if($totalStock == 0)
            //  {
            // 		$iteamData = IteamsModel::where('drug_group',$itemData->drug_group)->get();
            //  }
            //  }

            $iteamDetails = [];

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
                $iteamDetails[$key]['weightage'] = isset($totalIteam->weightage) ? $totalIteam->weightage : "";

                $onlineData = OnlineOrder::where('item_id', $listData->id)->whereIn('user_id', $allUserId)->first();

                if (isset($onlineData)) {
                    $iteamDetails[$key]['is_order'] = true;
                } else {
                    $iteamDetails[$key]['is_order'] = false;
                }

                $earliestBatch = BatchModel::whereIn('user_id', $allUserId)
                  ->where('item_id', $listData->id)
                  ->orderByRaw("STR_TO_DATE(CONCAT('01/', expiry_date), '%d/%m/%y')")
                  ->first();

                $iteamDetails[$key]['unit'] = isset($listData->unit) ? $listData->unit : "";
                $iteamDetails[$key]['expiry'] = isset($earliestBatch->expiry_date) ? (string)$earliestBatch->expiry_date : '';
                $iteamDetails[$key]['old_unit'] = isset($listData->old_unit) ? $listData->old_unit : "";
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
                $iteamDetails[$key]['front_photo'] = isset($listData->front_photo) ? asset('/public/front_photo/' . $listData->front_photo) : "";
                $iteamDetails[$key]['back_photo'] = isset($listData->back_photo) ? asset('/public/back_photo/' . $listData->back_photo) : "";
                $iteamDetails[$key]['mrp_photo'] = isset($listData->mrp_photo) ? asset('/public/mrp_photo/' . $listData->mrp_photo) : "";
                $iteamDetails[$key]['count'] =  $iteamDataCount->count();
            }

            $dataList['data'] = $iteamDetails;
            //  $dataList['count'] = isset($iteamData) ? count($iteamData) : '';
          
          	$response = [
              'status' => 200,
              'count' => !empty($request->page) ? $iteamData->count() : $itemDataTotalCount,
              'total_records' => $itemDataTotalCount,
              'data'   => $dataList,
              'message' => 'Item Data Fetch Successfully.',
            ];
            return response()->json($response, 200);
          	
            // return $this->sendResponse($dataList, 'Data Fetch Successfully.');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Iteams Update api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function iteamDrugGroup(Request $request)
    {
        $iteamData = IteamsModel::where('iteam_name','LIKE' ,'%'.$request->search.'%')->first();
      	// dd($iteamData);
      
      	if (!$iteamData) {
            return $this->sendError('Item not found with name: ' . $request->search);
        }

        $iteamDataNew = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->where('drug_group', $iteamData->drug_group)->get();

        $iteamDetails = [];

        foreach ($iteamDataNew as $key => $listData) {
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

            // $iteamDetails[$key]['old_unit'] = isset($uniteData->name) ? $uniteData->name : "";
            $iteamDetails[$key]['expiry'] = isset($earliestBatch->expiry_date) ? (string)$earliestBatch->expiry_date : '';
            $iteamDetails[$key]['old_unit'] = isset($listData->old_unit) ? $listData->old_unit : "";
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
            $iteamDetails[$key]['front_photo'] = isset($listData->front_photo) ? asset('/public/front_photo/' . $listData->front_photo) : "";
            $iteamDetails[$key]['back_photo'] = isset($listData->back_photo) ? asset('/public/back_photo/' . $listData->back_photo) : "";
            $iteamDetails[$key]['mrp_photo'] = isset($listData->mrp_photo) ? asset('/public/mrp_photo/' . $listData->mrp_photo) : "";
            $iteamDetails[$key]['count'] =  $iteamDataCount->count();
        }

        $dataList['data'] = $iteamDetails;
        //  $dataList['count'] = isset($iteamData) ? count($iteamData) :'';
        return $this->sendResponse($dataList, 'Data Fetch Successfully.');
    }

    public function itemLocation(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);
            $iteamLocation = ItemLocation::whereIn('user_id', $allUserId)
                ->whereNotNull('location')
                ->select(DB::raw('TRIM(LOWER(location)) as location')) // Normalize and select distinct locations
                ->distinct()
                ->pluck('location') // Retrieve only the 'location' values
                ->toArray();

            return $this->sendResponse($iteamLocation, 'Data Fetch Successfully.');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Iteams Update api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function itemLocationData()
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);
            $iteamLocation = ItemLocation::whereIn('user_id', $allUserId)->whereNotNull('location')->select(DB::raw('TRIM(LOWER(location)) as location')) // Normalize and select distinct locations
                ->distinct()->get();

            $locationList = [];
            if (isset($iteamLocation)) {
                foreach ($iteamLocation as $key => $list) {
                    $locationList[$key]['name'] =  $list->location;
                }
            }
            return $this->sendResponse($locationList, 'Data Fetch Successfully.');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Iteams Update api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function iteamSearchTags(Request $request)
    {
        $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
        $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();

        $userId = array(auth()->user()->id);
        $allUserId = array_merge($staffGetData, $ownerGet, $userId);

        $IteamIds = ItemLocation::whereIn('user_id', $allUserId)->pluck('item_id')->toArray();
        $itemLocation = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->whereNotIn('id', $IteamIds)->count();

        $categoryData = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->whereNull('item_category_id')->count();
        $HsnData = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->whereNull('hsn_code')->count();

        $countIteam = PurchesDetails::whereRaw("CAST(ptr AS DECIMAL(10,2)) <= CAST(mrp AS DECIMAL(10,2))")
            ->whereIn('user_id', $allUserId)
            ->whereRaw("mrp REGEXP '^[0-9]+(\.[0-9]+)?$'") // Only numeric values in `mrp`
            ->pluck('iteam_id')
            ->toArray();

        $itemMrpCount = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->whereNotIn('id', $countIteam)->count();

        $countIteamPrice = PurchesDetails::whereRaw("CAST(mrp AS DECIMAL(10,2)) >= CAST(ptr AS DECIMAL(10,2))")
            ->whereIn('user_id', $allUserId)
            ->whereRaw("mrp REGEXP '^[0-9]+(\.[0-9]+)?$'") // Only numeric values in `mrp`
            ->pluck('iteam_id')
            ->toArray();
        $itemMrpCountPrice = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->whereNotIn('id', $countIteamPrice)->count();

        $locationList = [];
        $locationList['items_with_missing_location'] = (string)$itemLocation;
        $locationList['items_with_missing_category'] = (string)$categoryData;
        $locationList['items_with_invalid_price'] = (string)$itemMrpCountPrice;
        $locationList['items_with_invalid_mrp'] = (string)$itemMrpCount;
        $locationList['items_with_missing_hsn'] = (string)$HsnData;
        return $this->sendResponse($locationList, 'Data Fetch Successfully.');
    }
}
