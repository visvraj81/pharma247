<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\IteamsModel;
use App\Models\salesDetails;
use App\Models\PurchesDetails;
use App\Models\BatchModel;
use Carbon\Carbon;
use App\Models\GstModel;
use App\Models\PurchesReturn;
use App\Models\BankAccount;
use App\Models\adjustStock;
use App\Models\CompanyModel;
use App\Models\User;

class StockController extends ResponseController
{
    //this function use ajdustment 
    public function stockAdujment(Request $request)
    {
        try {
            $iteamModel = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->orderBy('id', 'DESC');
            if (isset($request->start_date)) {
                $start_date = date('Y-m-d', strtotime($request->start_date));
                $end_date = date('Y-m-d', strtotime($request->end_date));
                $iteamModel->whereBetween('created_at', [$start_date, $end_date]);
            }
            $iteamModel = $iteamModel->get();

            $iteamDetails = [];
            if (isset($iteamModel)) {
                foreach ($iteamModel as $key => $list) {
                    $iteamDetails[$key]['id'] = isset($list->id) ? $list->id : "";
                    $iteamDetails[$key]['item_name'] = isset($list->iteam_name) ? $list->iteam_name : "";
                    $iteamDetails[$key]['old_unit'] = isset($list->old_unit) ? $list->old_unit : "";
                    $iteamDetails[$key]['stock'] = isset($list->stock) ? $list->stock : "";
                }
            }
            return $this->sendResponse($iteamDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Stock Adujstment api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function nonMovingItems(Request $request)
    {
        try {
            $days = $request->input('days', 15);

            $validator = Validator::make($request->all(), [
                'days' => 'nullable|integer|min:1',
            ], [
                'days.integer' => 'Please valid day number',
            ]);
            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendResponse('', $error->first());
            }

            $start_date = Carbon::now()->subDays($days)->format('Y-m-d');
            $end_date = Carbon::now()->format('Y-m-d');

            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $salesData = SalesDetails::whereIn('user_id', $allUserId)
                // ->whereBetween('created_at', [$start_date, $end_date])
                ->orderBy('id', 'DESC')
                ->pluck('iteam_id')
                ->toArray();

            $batchStock = BatchModel::whereIn('user_id', $allUserId)
                ->where('total_qty', '!=', '0')
                ->pluck('item_id')
                ->toArray();

            $iteamModel = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->whereNotIn('id', $salesData)
                ->whereIn('id', $batchStock)
                ->orderBy('id', 'DESC');

            $iteamModel = $iteamModel->get();

            $iteamData = [];
            foreach ($iteamModel as $key => $list) {
                $userid = auth()->user();
                $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
                $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
                $userId = array(auth()->user()->id);
                $allUserId = array_merge($staffGetData, $ownerGet, $userId);

                $purchesDataStock = BatchModel::whereIn('user_id', $allUserId)
                    ->where('item_id', $list->id)
                    ->sum('total_qty');
                $lastPurchesData = PurchesDetails::whereIn('user_id', $allUserId)
                    ->where('iteam_id', $list->id)
                    ->orderBy('id', 'DESC')
                    ->first();

                $iteamData[$key] = [
                    'id' => $list->id ?? "",
                    'iteam_name' => $list->iteam_name ?? "",
                    'packing_size' => $list->packing_size ?? "",
                    'location' => $list->location ?? "",
                    'mrp' => $list->mrp ?? "",
                    'stock' => $purchesDataStock ?? "",
                    'last_purches_date' => isset($lastPurchesData->created_at) ? date("d-m-Y", strtotime($lastPurchesData->created_at)) : ""
                ];
            }

            // if ($request->has('export') && $request->export == 'csv') {
            // return $this->exportToCSV($iteamData);
            // }


            return $this->sendResponse($iteamData, 'Non Moving Items Data Fetch Successfully.');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Stock Adjustment API Error: " . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching data.'], 500);
        }
    }

    public function exportToCSV(array $iteamData)
    {
        $fileName = 'non_moving_items_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Item Name', 'Packing Size', 'Location', 'MRP', 'Stock', 'Last Purchase Date'];

        $callback = function () use ($iteamData, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($iteamData as $row) {
                fputcsv($file, [
                    $row['id'],
                    $row['iteam_name'],
                    $row['packing_size'],
                    $row['location'],
                    $row['mrp'],
                    $row['stock'],
                    $row['last_purches_date']
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function iteamBatchViseStock(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $batchModel = BatchModel::whereIn('user_id', $allUserId);
            if (isset($request->start_date) && isset($request->end_date)) {
                $start_date = Carbon::parse($request->start_date);
                $end_date = Carbon::parse($request->end_date);
                $batchModel->whereBetween('created_at', [$start_date, $end_date]);
            }

            if (isset($request->company_name)) {
                $companyName = $request->company_name;
                $batchModel->whereHas('getIteam', function ($q) use ($companyName) {
                    $q->whereHas('getPharma', function ($qr) use ($companyName) {
                        $qr->where('company_name',  'like', '%' . $companyName . '%');
                    });
                });
            }
            if (isset($request->drug_group)) {
                $drugName = $request->drug_group;
                $batchModel->whereHas('getIteam', function ($q) use ($drugName) {
                    $q->whereHas('getDrugGroup', function($qr) use ($drugName){
                      	$qr->where('name','like','%'.$drugName . '%');
                    });
                    // $q->where('drug_group', 'like', '%' . $drugName . '%');1
                });
            }
            if (isset($request->location)) {
                $batchModel->where('location', 'like', '%' . $request->location . '%');
            }
            $limit = '10';
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $batchModel->limit($limit)->offset($offset);
            $batchModel = $batchModel->get();

            $salesDetails = [];

            if (isset($batchModel)) {
                foreach ($batchModel as $key => $list) {
                    $gst = GstModel::where('id', $list->gst)->first();
                    $salesDetails[$key]['id'] = isset($list->id) ? $list->id : "";
                    $salesDetails[$key]['stock'] = isset($list->total_qty) ? $list->total_qty : "";
                    $salesDetails[$key]['batch_name'] = isset($list->batch_name) ? $list->batch_name : "";
                    $salesDetails[$key]['ptr'] = isset($list->ptr) ? $list->ptr : "";
                    $salesDetails[$key]['mrp'] = isset($list->mrp) ? $list->mrp : "";
                    $salesDetails[$key]['expiry_date'] = isset($list->expiry_date) ? $list->expiry_date : "";
                    $salesDetails[$key]['base'] = isset($list->base) ? $list->base : "";
                    $salesDetails[$key]['company_name'] = isset($list->getIteam->getPharma->company_name) ? $list->getIteam->getPharma->company_name : "";
                    $salesDetails[$key]['item_name'] = isset($list->getIteam->iteam_name) ? $list->getIteam->iteam_name : "";
                    $salesDetails[$key]['category_name'] = isset($list->getIteam->getCategory->category_name) ? $list->getIteam->getCategory->category_name : "";
                    $salesDetails[$key]['location'] = isset($list->location) ? $list->location : "";
                    $salesDetails[$key]['unit'] = isset($list->unit) ? $list->unit : "";
                    $salesDetails[$key]['gst'] = isset($gst->name) ? $gst->name : $list->gst;
                    $salesDetails[$key]['drug_group'] = isset($list->getIteam->getDrugGroup->name) ? $list->getIteam->getDrugGroup->name : "";
                }
            }
            return $this->sendResponse($salesDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Batch Vise Stock api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesReturnReport(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $purchesReturn = PurchesReturn::orderBy('id', 'DESC')->whereIn('user_id', $allUserId);
            if (isset($request->start_date) && isset($request->end_date)) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $purchesReturn->whereBetween('select_date', [$start_date, $end_date]);
            }
            if (isset($request->distributer_name)) {
                $distributerName = $request->distributer_name;
                $purchesReturn->whereHas('getUser', function ($q) use ($distributerName) {
                    $q->where('name', 'like', '%' . $distributerName . '%');
                });
            }
            if (isset($request->iss_value)) {
                $purchesReturn = $purchesReturn->get();
            } else {
                $limit = '10';
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
                $purchesReturn->limit($limit)->offset($offset);
                $purchesReturn = $purchesReturn->get();
            }
            $purchesData = [];
            if (isset($purchesReturn)) {
                foreach ($purchesReturn as $key => $purchesDetails) {
                    $banName  = BankAccount::where('id', $purchesDetails->payment_type)->first();
                    $purchesData[$key]['id'] = isset($purchesDetails->id) ? $purchesDetails->id : "";
                    $purchesData[$key]['bill_date'] = isset($purchesDetails->select_date) ? $purchesDetails->select_date : "";
                    $purchesData[$key]['bill_no'] = isset($purchesDetails->bill_no) ? $purchesDetails->bill_no : "";
                    $purchesData[$key]['distributer'] = isset($purchesDetails->getUser->name) ? $purchesDetails->getUser->name : "";
                    if (empty($purchesDetails->payment_type)) {
                        $purchesData[$key]['type'] = isset($banName->bank_name) ? $banName->bank_name : "";
                    } else {
                        $purchesData[$key]['type'] = isset($banName->bank_name) ? $banName->bank_name :  $purchesDetails->payment_type;
                    }
                    $purchesData[$key]['amount'] = isset($purchesDetails->net_amount) ? round($purchesDetails->net_amount, 2) : "";
                }
            }
            $totalData['purches_return'] =  $purchesData;
            $totalData['purches_return_total'] =  (string)$purchesReturn->sum('net_amount');
          
            return $this->sendResponse($totalData, 'Purchase Return Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("Purches Report api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function adjustStock(Request $request)
    {
        try {
            $newStock = new adjustStock;
            $newStock->adjustment_date = $request->adjustment_date;
            $newStock->item_name = $request->item_name;
            $newStock->user_id = auth()->user()->id;
            $newStock->batch = $request->batch;
            if (isset($request->company)) {
                $newStock->company = $request->company;
            }
            $newStock->unite = $request->unit;
            $newStock->expriy = $request->expiry;
            $newStock->mrp    = $request->mrp;
            $newStock->stock = $request->stock;
            $newStock->stock_adjust = $request->stock_adjust;
            $newStock->remain_stock    = $request->remaining_stock;
            $newStock->save();

            $batchData = BatchModel::where('batch_name', $request->batch)->where('user_id', auth()->user()->id)->first();
            if (isset($batchData)) {
                $batchData->qty = $request->remaining_stock;
                $batchData->total_qty = $request->remaining_stock;
                $batchData->update();
            }

            return $this->sendResponse([], 'Stock Adjust Successfully');
        } catch (\Exception $e) {
            Log::info("Adjust Stock Report api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function adjustStockList(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $stockList = adjustStock::whereIn('user_id', $allUserId)->orderBy('id', 'DESC');

            if (isset($request->start_date) && isset($request->end_date)) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $stockList->whereBetween('adjustment_date', [$start_date, $end_date]);
            }

            if (isset($request->search)) {
                $search = $request->search;
                $stockList->where('item_name', 'like', '%' . $search . '%')->orWhere('batch', 'like', '%' . $search . '%');
            }
            $stockListSums =  $stockList->sum('mrp');
            $limit = '10';
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $stockList->limit($limit)->offset($offset);

            $stockList =  $stockList->get();

            $stockTotalCount = adjustStock::where('user_id', auth()->user()->id)->count();

            $stockData  = [];
            $stockAmount = [];
            if (isset($stockList)) {
                foreach ($stockList as $key => $list) {
                    $batchName  = BatchModel::where('id', $list->batch)->first();
                    $userData  = User::where('id', $list->user_id)->first();
                    $stockData[$key]['id'] = isset($list->id) ? $list->id : "";
                    $stockData[$key]['adjusted_by'] = isset($userData->name) ? $userData->name : "";
                    $stockData[$key]['adjustment_date'] = isset($list->adjustment_date) ? $list->adjustment_date : "";
                    $stockData[$key]['iteam_name'] = isset($list->getIteam->iteam_name) ? $list->getIteam->iteam_name : $list->item_name;
                    $stockData[$key]['batch_name'] = isset($batchName->batch_name) ? $batchName->batch_name : $list->batch;
                    $stockData[$key]['unit'] = isset($list->unite) ? $list->unite : "";
                    $stockData[$key]['expriy'] = isset($list->expriy) ? $list->expriy : "";
                    $stockData[$key]['remaining_stock'] = isset($list->remain_stock) ? $list->remain_stock : "";
                    $companyName = CompanyModel::where('id', $list->company)->first();
                    $stockData[$key]['company_name'] = isset($companyName->company_name) ? $companyName->company_name : "";
                    $stockData[$key]['mrp'] = isset($list->mrp) ? $list->mrp : "";
                    $stockData[$key]['stock'] = isset($list->stock) ? $list->stock : "";
                    $stockData[$key]['stock_adjust'] = isset($list->stock_adjust) ? $list->stock_adjust : "";
                    $adustStock = $list->mrp * abs($list->stock_adjust);
                    $stockData[$key]['stock_adjust_amount'] = isset($adustStock) ? (string)$adustStock : "";
                    array_push($stockAmount, $adustStock);
                }
            }

            $dataStock['data'] = $stockData;
            $dataStock['total_amount'] =  (string) array_sum($stockAmount);

            $response = [
                'status' => 200,
                'count' => !empty($request->page) ? $stockList->count() : $stockTotalCount,
                'total_records' => $stockTotalCount,
                'data'   => $dataStock,
                'message' => 'Stock Adjust List Get Successfully.',
            ];
            return response()->json($response, 200);
            // return $this->sendResponse($dataStock, 'Stock Adjust List Get Successfully.');
        } catch (\Exception $e) {
            Log::info("Adjust Stock Report List api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesIteamlist(Request $request)
    {
        try {
            $purchesData = PurchesDetails::where('user_id', auth()->user()->id)->pluck('iteam_id')->toArray();
            $iteamData = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->whereIn('id', $purchesData)->get();

            $iteamDetails = [];
            if (isset($iteamData)) {
                foreach ($iteamData as $key => $list) {
                    $iteamDetails[$key]['id'] = isset($list->id) ? $list->id : "";
                    $iteamDetails[$key]['iteam_name'] = isset($list->iteam_name) ? $list->iteam_name : "";
                }
            }

            return $this->sendResponse($iteamDetails, 'Stock Adjust Successfully');
        } catch (\Exception $e) {
            Log::info("Adjust Stock Report List api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
