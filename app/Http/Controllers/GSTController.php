<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use App\Models\GstModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use Carbon\Carbon;
use App\Models\salesDetails;
use App\Models\IteamsModel;
use App\Models\SalesReturnDetails;
use App\Models\PurchesDetails;
use App\Models\PurchesReturnDetails;
use App\Models\PurchesModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Distributer;
use App\Models\User;

class GSTController extends ResponseController
{
    public function GSTStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ], [
                'name.required' => 'Enter GST Name'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $package = new GstModel;
            $package->name = $request->name;
            $package->save();

            return $this->sendResponse('', 'GST Added Successfully');
        } catch (\Exception $e) {
            Log::info("Create GST api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use list package
    public function listGST(Request $request)
    {
        try {
            $packageList = GstModel::orderBy('id', 'DESC')->get();

            $packageListArray = [];
            if (isset($packageList)) {
                foreach ($packageList as $key => $value) {
                    $packageListArray[$key]['id'] = isset($value->id) ? $value->id : '';
                    $packageListArray[$key]['name'] = isset($value->name) ? $value->name : '';
                }
            }
            return $this->sendResponse($packageListArray, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("List unit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use edit package
    public function editGST(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => "Enter Package Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $packageEdit = GstModel::where('id', $request->id)->first();

            if (empty($packageEdit)) {
                return $this->sendError('Data Not Found');
            }

            $packageEditData = [];
            $packageEditData['id'] = isset($packageEdit->id) ? $packageEdit->id : '';
            $packageEditData['name'] = isset($packageEdit->name) ? $packageEdit->name : '';

            return $this->sendResponse($packageEditData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Edit GST api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use update package
    public function updateGST(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
            ], [
                'id.required' => 'Enter Item GST Id',
                'name.required' => 'Enter GST Name'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $packageUpdate = GstModel::find($request->id);
            $packageUpdate->name = $request->name;
            $packageUpdate->update();

            return $this->sendResponse('', 'GST Updated Successfully');
        } catch (\Exception $e) {
            Log::info("Update GST api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use delete package
    public function deleteGST(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Enter Package Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $packageDelete = GstModel::where('id', $request->id)->first();
            if (isset($packageDelete)) {
                $packageDelete->delete();
            }
            return $this->sendResponse('', 'GST Deleted Successfully');
        } catch (\Exception $e) {
            Log::info("Delete unit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function gstHsnReport(Request $request)
    {
        try {
            $monthYear = $request->date;
            $date = Carbon::createFromFormat('m-Y', $monthYear);
            $year = $date->year;
            $month = $date->month;
            $userId = auth()->user()->id;
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);
            $iteamData = [];

            $models = [
                '0' => ['model' => salesDetails::class, 'relation' => 'getSales', 'date_column' => 'bill_date', 'amount_column' => 'net_amt'],
                '1' => ['model' => SalesReturnDetails::class, 'relation' => 'getSales', 'date_column' => 'date', 'amount_column' => 'net_amount'],
                '2' => ['model' => PurchesDetails::class, 'relation' => 'getpurches', 'date_column' => 'bill_date', 'amount_column' => 'net_amount'],
                '3' => ['model' => PurchesReturnDetails::class, 'relation' => 'getPurchesReturn', 'date_column' => 'select_date', 'amount_column' => 'net_amount']
            ];

            if (!array_key_exists($request->type, $models)) {
                return $this->sendResponse([], 'Invalid Type');
            }

            $model = $models[$request->type]['model'];
            $relation = $models[$request->type]['relation'];
            $dateColumn = $models[$request->type]['date_column'];
            $amountColumn = $models[$request->type]['amount_column'];

            $salesData = $model::whereIn('user_id', $allUserId)
                ->whereHas($relation, function ($q) use ($year, $month, $dateColumn) {
                    $q->whereYear($dateColumn, $year)->whereMonth($dateColumn, $month);
                })
                ->pluck('iteam_id')
                ->toArray();

            $iteamDetails = IteamsModel::whereNull('user_id')->orWhere('user_id', auth()->user()->id)->whereIn('id', $salesData)->get();

            if ($iteamDetails->isEmpty()) {
                return $this->sendResponse([], 'No Data Found');
            }

            foreach ($iteamDetails as $key => $listData) {
                $salesDetailsQuery = $model::where('user_id', $userId)
                    ->where('iteam_id', $listData->id);

                $salesIteamData = $salesDetailsQuery->sum('qty');
                $salesGSTData = $salesDetailsQuery->sum('gst');

                $salesData = $salesDetailsQuery->with([$relation => function ($q) use ($amountColumn) {
                    $q->whereNotNull($amountColumn);
                }])
                    ->whereHas($relation, function ($q) use ($amountColumn) {
                        $q->whereNotNull($amountColumn);
                    })
                    ->get();

                $netAmountSum = $salesData->sum(fn($sd) => $sd->$relation->sum($amountColumn));
                $netAmountBase = $salesData->sum(fn($sd) => $sd->$relation->sum('total_base'));
                $netAmountSgst = $salesData->sum(fn($sd) => $sd->$relation->sum('sgst'));
                $netAmountCgst = $salesData->sum(fn($sd) => $sd->$relation->sum('cgst'));

                if ((isset($request->type)) && ($request->type == '0')) {
                    $totalBasePrice = $salesData->sum('base');
                    $totalItems = $salesData->count();  // Correct variable name

                    $iteamGst = [];

                    // Iterate through sales data and get GST values
                    if (isset($salesData)) {
                        foreach ($salesData as $key => $list) {
                            // Get GST name based on ID
                            $gstName = GstModel::where('id', $list->gst)->first();
                            // Use the GST name or default to 0 if not found
                            $resultGst = isset($gstName->name) ? $gstName->name : 0;
                            // Add GST value to the array
                            array_push($iteamGst, (float)$resultGst);  // Ensure gst value is numeric
                        }
                    }

                    // Calculate the average GST value
                    $gstData = $totalItems > 0 ? array_sum($iteamGst) / $totalItems : 0;

                    // Calculate total GST
                    $totalGst = $totalBasePrice * $gstData / 100;
                } elseif ((isset($request->type)) && ($request->type == '1')) {
                    $totalBasePrice = $salesData->sum('base');
                    $totalItems = $salesData->count();  // Correct variable name

                    $iteamGst = [];

                    // Iterate through sales data and get GST values
                    if (isset($salesData)) {
                        foreach ($salesData as $key => $list) {
                            // Get GST name based on ID
                            $gstName = GstModel::where('id', $list->gst)->first();
                            // Use the GST name or default to 0 if not found
                            $resultGst = isset($gstName->name) ? $gstName->name : 0;
                            // Add GST value to the array
                            array_push($iteamGst, (float)$resultGst);  // Ensure gst value is numeric
                        }
                    }

                    // Calculate the average GST value
                    $gstData = $totalItems > 0 ? array_sum($iteamGst) / $totalItems : 0;

                    // Calculate total GST
                    $totalGst = $totalBasePrice * $gstData / 100;
                } elseif ((isset($request->type)) && ($request->type == '2')) {
                    $totalBasePrice = $salesData->sum('base');
                    $totalItems = $salesData->count();  // Correct variable name

                    $iteamGst = [];

                    // Iterate through sales data and get GST values
                    if (isset($salesData)) {
                        foreach ($salesData as $key => $list) {
                            // Get GST name based on ID
                            $gstName = GstModel::where('id', $list->gst)->first();
                            // Use the GST name or default to 0 if not found
                            $resultGst = isset($gstName->name) ? $gstName->name : 0;
                            // Add GST value to the array
                            array_push($iteamGst, (float)$resultGst);  // Ensure gst value is numeric
                        }
                    }

                    // Calculate the average GST value
                    $gstData = $totalItems > 0 ? array_sum($iteamGst) / $totalItems : 0;

                    // Calculate total GST
                    $totalGst = $totalBasePrice * $gstData / 100;
                } elseif ((isset($request->type)) && ($request->type == '3')) {
                    $totalBasePrice = $salesData->sum('ptr');
                    $totalItems = $salesData->count();  // Correct variable name

                    $iteamGst = [];

                    // Iterate through sales data and get GST values
                    if (isset($salesData)) {
                        foreach ($salesData as $key => $list) {
                            // Get GST name based on ID
                            $gstName = GstModel::where('id', $list->gst)->first();
                            // Use the GST name or default to 0 if not found
                            $resultGst = isset($gstName->name) ? $gstName->name : 0;
                            // Add GST value to the array
                            array_push($iteamGst, (float)$resultGst);  // Ensure gst value is numeric
                        }
                    }

                    // Calculate the average GST value
                    $gstData = $totalItems > 0 ? array_sum($iteamGst) / $totalItems : 0;

                    // Calculate total GST
                    $totalGst = $totalBasePrice * $gstData / 100;
                }

                $iteamData[$key] = [
                    'id' => $listData->id ?? "",
                    'iteam_name' => $listData->iteam_name ?? "",
                    'packing_size' => $listData->packing_size ?? "",
                    'hsn_code' => $listData->hsn_code !== 'null' ? $listData->hsn_code : "",
                    'qty' => (string)$salesIteamData ?? "",

                    'total_gst' => (string)round($totalGst, 0) ?? "",
                    'total_amount' => (string)round($netAmountSum, 2) ?? "",
                    'total_base' => (string)round($netAmountBase, 2) ?? "",
                    'total_sgst' => (string)round($totalGst / 2, 2) ?: "",
                    'total_cgst' => (string)round($totalGst / 2, 2) ?: "",
                ];
            }

            return $this->sendResponse($iteamData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("HSN Gst api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function gstOne(Request $request)
    {
        try {
            $userid = auth()->user();
            $staffGetData = User::where('create_by', auth()->user()->id)->pluck('id')->toArray();
            $ownerGet = User::where('id', auth()->user()->id)->pluck('create_by')->toArray();
            $userId = array(auth()->user()->id);
            $allUserId = array_merge($staffGetData, $ownerGet, $userId);

            $monthYear = $request->month_year;
            $date = Carbon::createFromFormat('m-Y', $monthYear);
            $year = $date->year;
            $month = $date->month;

            $purchesData = PurchesModel::whereYear('bill_date', $year)->whereMonth('bill_date', $month)->whereIn('user_id', $allUserId)->get();

            $purchesDetails = [];
            $totalAmount = [];
            $taxableAmount = [];
            $sgst = [];
            $cgst = [];
            if (isset($purchesData)) {
                foreach ($purchesData as $key => $list) {
                    $nameData = Distributer::where('id', $list->distributor_id)->first();
                    $purchesGSt = PurchesDetails::where('purches_id', $list->id)->pluck('gst')->toArray();
                    $gstName = GstModel::whereIn('id', $purchesGSt)->pluck('name')->toArray();

                    $purchesDetails[$key]['id'] = isset($list->id) ? $list->id : "";
                    $purchesDetails[$key]['gst_no'] = isset($list->getdistributer) ? $list->getdistributer->gst : "-";
                    $purchesDetails[$key]['name'] = isset($nameData->name) ? $nameData->name : "";
                    $purchesDetails[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                    $purchesDetails[$key]['bill_date'] = isset($list->bill_date) ? $list->bill_date : "";
                    $purchesDetails[$key]['sgst'] = isset($list->sgst) ? $list->sgst : "";
                    $purchesDetails[$key]['cgst'] = isset($list->cgst) ? $list->cgst : "";
                    $purchesDetails[$key]['location'] = isset($list->getdistributer->area_number) ? $list->getdistributer->area_number : "";
                    $purchesDetails[$key]['net_amount'] = isset($list->net_amount) ? round($list->net_amount, 2) : "";
                    $purchesDetails[$key]['gst'] = (string)array_sum($gstName);

                    $amount = $list->net_amount;
                    $gstPercentage = (string)array_sum($gstName);

                    // Calculate the GST amount
                    $gstAmount = ($amount * $gstPercentage) / 100;

                    // Subtract the GST amount from the original amount
                    $amountWithoutGst = $amount - $gstAmount;
                    $purchesDetails[$key]['taxable_amount'] = (string)$amountWithoutGst;
                    array_push($totalAmount, $list->net_amount);
                    array_push($taxableAmount, $amountWithoutGst);
                    array_push($sgst, $list->sgst);
                    array_push($cgst, $list->cgst);
                }
            }

            $purchesList['gst_bill'] = $purchesDetails;
            $purchesList['total_amount'] = (string)array_sum($totalAmount);
            $purchesList['taxable_amount'] = (string)array_sum($taxableAmount);
            $purchesList['sgst'] = (string)array_sum($sgst);
            $purchesList['cgst'] = (string)array_sum($cgst);
            // Generate PDF
            $pdf = PDF::loadView('gst_report', compact('purchesList'));
            $pdf->setPaper('a3', 'landscape');
            // Download the PDF
            return $pdf->download('gst_report.pdf');
        } catch (\Exception $e) {
            Log::info("Gst One api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
