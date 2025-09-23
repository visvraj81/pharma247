<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchesModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\PurchesPayment;
use App\Models\User;
use App\Models\LedgerModel;
use App\Models\PaymentDetails;
use App\Models\PurchesPaymentDetails;
use App\Models\CashCategory;
use App\Models\CashManagement;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Mail\CashMail;
use Illuminate\Support\Facades\Mail;
use App\Models\BankAccount;
use App\Models\PassBook;
use App\Models\Distributer;
use App\Models\LogsModel;

class PaymentPurchesController extends ResponseController
{
    // this function use purchase payment
    public function purchesPayment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'distributor_id' => 'required'
            ], [
                'distributor_id.required' => 'Please Enter Distributor Name',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $userId = auth()->user()->id;
            $puchesPaymentList = PurchesModel::where('distributor_id', $request->distributor_id)->where('pending_amount_status', '0')->where('user_id', $userId)->get();

            $paymentData = [];
            if (isset($puchesPaymentList)) {
                foreach ($puchesPaymentList as $key => $list) {

                    // $pendingAmount = $list->net_amount - ;
                    $paymentData[$key]['id'] = isset($list->id) ? $list->id : "";
                    $paymentData[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                    $paymentData[$key]['distributor_id'] = isset($list->distributor_id) ? $list->distributor_id : "";
                    $paymentData[$key]['date'] = isset($list->created_at) ? date("d-m-Y", strtotime($list->created_at)) : "";
                    $paymentData[$key]['net_amount'] = isset($list->net_amount) ? (string)round($list->net_amount, 2) : "";
                    $paymentData[$key]['pending_amount'] = isset($list->pending_amount) ? (string)round($list->pending_amount, 2) : "";
                }
            }
            $puchesPaymentAmount = PurchesModel::where('distributor_id', $request->distributor_id)->sum('net_amount');
            $iteamData['pruches_bill'] = $paymentData;
            $iteamData['total'] = round($puchesPaymentAmount, 2);
            return $this->sendResponse($iteamData, 'Purchase Payment Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("purches Payment api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesPaymentList(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            $purchesPayment = PurchesPaymentDetails::where('user_id', $userId);
            if (isset($request->distributor_id)) {
                $purchesPayment = $purchesPayment->where('distributor_name', $request->distributor_id);
            } else if(isset($request->bill_no)) {
            	$purchesPayment = $purchesPayment->where('bill_no','LIKE','%'.$request->bill_no.'%');
            } else if(isset($request->payment_date)) {
            	$purchesPayment = $purchesPayment->where('payment_date','LIKE','%'.$request->payment_date.'%');
            } else if(isset($request->payment_mode)) {
              	if($request->payment_mode == 'cash') {
            		$purchesPayment = $purchesPayment->where('payment_mode','cash');
                } else if($request->payment_mode == 'credit') {
                	$purchesPayment = $purchesPayment->where('payment_mode','credit');
                } else {
                	$purchesPayment = $purchesPayment->where('payment_mode',$request->payment_mode);
                }
            } else if(isset($request->status)) {
            	$purchesPayment = $purchesPayment->where('status',$request->status);
            } else if(isset($request->bill_amount)) {
            	$purchesPayment = $purchesPayment->where('bill_amount','LIKE','%'.$request->bill_amount.'%');
            } else if(isset($request->paid_amount)) {
            	$purchesPayment = $purchesPayment->where('paid_amount','LIKE','%'.$request->paid_amount.'%');
            } else if(isset($request->due_amount)) {
            	$purchesPayment = $purchesPayment->where('due_amount','LIKE','%'.$request->due_amount.'%');
            }
            $limit = 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $purchesPayment->offset($offset)->limit($limit);
            $purchesPayment = $purchesPayment->orderBy('id', 'DESC')->get();

            $purchesPaymentTotalCount = PurchesPaymentDetails::where('user_id', $userId)->count();

            $paymentData = [];
            if (isset($purchesPayment)) {
                foreach ($purchesPayment as $key => $list) {
                    $nameData = Distributer::where('id', $list->distributor_name)->first();
                    $note = PurchesPayment::where('id', $list->payment_id)->first();
                    $bankName = BankAccount::where('id', $list->payment_mode)->where('user_id', auth()->user()->id)->first();
                    $paymentData[$key]['id'] = isset($list->id) ? $list->id : "";
                    $paymentData[$key]['distributor_name'] = isset($nameData->name) ? $nameData->name : "";
                    $paymentData[$key]['distributor_id'] = isset($list->distributor_name) ? $list->distributor_name : "";
                    $paymentData[$key]['note'] = isset($note->note) ? $note->note : "";
                    $paymentData[$key]['payment_id'] = isset($list->payment_mode) ? $list->payment_mode  : $list->payment_mode;
                    $paymentData[$key]['payment_mode'] = isset($bankName->bank_name) ? $bankName->bank_name  : $list->payment_mode;
                    $paymentData[$key]['payment_date'] = isset($list->payment_date) ? date("d-m-Y", strtotime($list->payment_date)) : "";
                    $paymentData[$key]['paid_amount'] = isset($list->paid_amount) ? (string)round($list->paid_amount, 2) : "";
                    $paymentData[$key]['due_amount'] = isset($list->due_amount) ? (string) abs($list->due_amount) : "";
                    $paymentData[$key]['bill_amount'] = isset($list->bill_amount) ? (string)round($list->bill_amount, 2) : "";
                    $paymentData[$key]['status'] = isset($list->status) ? $list->status : "";
                    $paymentData[$key]['bill_no'] = isset($list->bill_no) ? $list->bill_no : "";
                }
            }

            $response = [
                'status' => 200,
                'count' => !empty($request->page) ? $purchesPayment->count() : $purchesPaymentTotalCount,
                'total_records' => $purchesPaymentTotalCount,
                'data'    => $paymentData,
                'message' => 'Purchase Payment Data Fetch Successfully.',
            ];

            return response()->json($response, 200);

            // return $this->sendResponse($paymentData, 'Purchase Payment Data Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("purches Payment list api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function purchesPaymentEdit(Request $request)
    {
        try {

            $puchesData = PurchesPaymentDetails::find($request->id);
            if (isset($puchesData)) {
                $puchesData->payment_mode = $request->payment_mode;
                $puchesData->update();
            }
            $puchesDetails = PurchesPayment::where('id', $puchesData->payment_id)->first();
            if (isset($puchesDetails)) {
                $puchesDetails->note = $request->note;
                $puchesDetails->save();
            }

            return $this->sendResponse([], 'Purchase Payment Updated Successfully.');
        } catch (\Exception $e) {
            Log::info("purches Payment Edit api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use purches payment store
    public function purchesPaymentStore(Request $request)
    {
        try {
            $paymentPurches = new PurchesPayment;
            $paymentPurches->distributor = $request->distributor_id;
            $paymentPurches->payment_date = $request->payment_date;
            $paymentPurches->payment_mode = $request->payment_mode;
            $paymentPurches->note = $request->note;
            $paymentPurches->total = $request->total;

            $distributorData = Distributer::where('id', $request->distributor_id)->first();
            // if(isset($distributorData))
            // {
            //     $total = abs($distributorData->balance_status) - abs($request->total);
            //     $distributorData->balance = $total;
            //     // $distributorData->balance_status = '1';
            //     $distributorData->update();

            //     // $paymentPurches->unused_amount = $request->unused_amount;
            // }
            $paymentPurches->save();
            if ($request->payment_mode == 'cash') {
                $cashManage = CashManagement::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();

                if (isset($cashManage)) {
                    $amount =  $cashManage->opining_balance - $request->total;
                    $cashAdd = new CashManagement;
                    $cashAdd->date = $request->payment_date;
                    $cashAdd->description = 'Purchase';
                    $cashAdd->type = 'debit';
                    $cashAdd->amount = $request->total;
                    $cashAdd->reference_no = $paymentPurches->id;
                    $cashAdd->voucher     = 'purchase';
                    $cashAdd->user_id = auth()->user()->id;
                    $cashAdd->opining_balance = abs($amount);
                    $cashAdd->save();
                } else {

                    $cashAdd = new CashManagement;
                    $cashAdd->date = $request->payment_date;
                    $cashAdd->description = 'Purchase';
                    $cashAdd->type = 'debit';
                    $cashAdd->amount = $request->total;
                    $cashAdd->user_id = auth()->user()->id;
                    $cashAdd->reference_no = $paymentPurches->id;
                    $cashAdd->voucher     = 'purchase';
                    $cashAdd->opining_balance = $request->total;
                    $cashAdd->save();
                }
            } else {
                $passBook =  PassBook::where('bank_id', $request->payment_mode)->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                if (isset($passBook)) {
                    $amount =  $passBook->balance - $request->total;

                    $passbook = new  PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $request->payment_date;
                    $passbook->party_name = $distributorData->name;
                    $passbook->bank_id = $request->payment_mode;
                    $passbook->deposit = "";
                    $passbook->withdraw     = $request->total;
                    $passbook->balance = abs($amount);
                    $passbook->mode = "";
                    $passbook->remark = $request->note;
                    $passbook->save();
                } else {
                    $passbook = new  PassBook;
                    $passbook->user_id = auth()->user()->id;
                    $passbook->date = $request->payment_date;
                    $passbook->party_name = $distributorData->name;
                    $passbook->bank_id = $request->payment_mode;
                    $passbook->deposit = "";
                    $passbook->withdraw    = $request->total;
                    $passbook->balance = $request->total;
                    $passbook->mode = "";
                    $passbook->remark = $request->note;
                    $passbook->save();
                }
            }

            $leaderData = new LedgerModel;
            $leaderData->owner_id = $request->distributor_id;
            $leaderData->entry_date = $request->payment_date;
            $leaderData->transction = 'Purchase Payment Invoice';
            $leaderData->voucher = 'Purchase Payment Invoice';
            $leaderData->bill_no = '#';
            $ledgers = LedgerModel::where('owner_id', $request->distributor_id)->orderBy('id', 'DESC')->first();
            if (isset($ledgers)) {
                if (isset($ledgers->credit)) {
                    $total =  round($request->total) + $ledgers->balance;
                    $leaderData->debit = round($request->total);
                    $leaderData->balance = round($total);
                } else {
                    $total = $ledgers->balance - round($request->total);
                    $leaderData->debit = round($request->total);
                    $leaderData->balance = round($total);
                }
            } else {
                $leaderData->debit = round($request->total);
                $leaderData->balance = round($request->total);
            }
            $leaderData->save();

            $paymentAmount = PaymentDetails::orderBy('id', 'DESC')->first();
            if (isset($paymentAmount)) {
                $balance = $request->total - $paymentAmount->balance;
            } else {
                $balance = $request->total;
            }

            $paymentDetails = new PaymentDetails;
            $paymentDetails->date = date('y-m-d');
            $paymentDetails->type = 'Payment Out';
            $paymentDetails->party = $request->distributor_id;
            $paymentDetails->mode = $request->payment_mode;
            $paymentDetails->online     = 'Online';
            $paymentDetails->paid = $request->total;
            $paymentDetails->party = '-';
            $paymentDetails->receive = '-';
            $paymentDetails->balance = round($balance, 2);
            $paymentDetails->save();

            $paymentData = json_decode($request->payment_list, true);
            if (isset($paymentData)) {
                foreach ($paymentData as $list) {
                    if ($list['paid_amount'] != "0") {
                        $purchesPayment = PurchesModel::where('id', $list['id'])->first();

                        if ((isset($purchesPayment)) && ($list['paid_amount'] != "")) {
                            $totalAmount = $list['pending_amount'] - $list['paid_amount'];
                            $purchesPayment->pending_amount = abs($totalAmount);
                            if ($purchesPayment->pending_amount == 0) {
                                $purchesPayment->pending_amount_status = '1';
                            }

                            $purchesPayment->update();

                            $userId = auth()->user()->id;
                            $purchePayment = new PurchesPaymentDetails;
                            $purchePayment->bill_date = $list['bill_date'];
                            $purchePayment->bill_no = $list['bill_no'];
                            $purchePayment->user_id = $userId;
                            $purchePayment->purchase_bill_id = $list['id'];
                            $purchePayment->distributor_name = $request->distributor_id;
                            $purchePayment->payment_id = $paymentPurches->id;
                            $purchePayment->payment_date = $request->payment_date;
                            $purchePayment->payment_mode = $request->payment_mode;
                            $purchePayment->bill_amount = $list['bill_amount'];
                            $purchePayment->paid_amount = $list['paid_amount'];
                            $purchePayment->due_amount = round($totalAmount, 2);

                            // Set status based on due amount
                            if ($totalAmount == 0) {
                                $purchePayment->status = 'Paid';
                            } else {
                                $purchePayment->status = 'Partially Paid';
                            }

                            // Save payment details
                            $purchePayment->save();
                        }
                    }
                }
            }
            $userLogs = new LogsModel;
            $userLogs->message = 'Purches Payment ';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
            return $this->sendResponse([], 'Purchase Payment Added Successfully.');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Purchase Payment Store api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // purchesPaymentList
    public function purchesDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_mode' => 'required',
            ], [
                'payment_mode.required' => 'Please Enter Payment Mode',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $paymentMode = PaymentDetails::where('mode', $request->payment_mode)->get();
            $paidTotal = PaymentDetails::where('mode', $request->payment_mode)->sum('paid');
            $receiveTotal = PaymentDetails::where('mode', $request->payment_mode)->sum('receive');

            $dataDetails = [];
            if (isset($paymentMode)) {
                $dataDetails['transction'] = [];
                foreach ($paymentMode as $key => $list) {
                    $dataDetails['transction'][$key]['id'] = isset($list->id) ? $list->id : "";
                    $dataDetails['transction'][$key]['date'] = isset($list->date) ? $list->date : "";
                    $dataDetails['transction'][$key]['type'] = isset($list->type) ? $list->type : "";
                    $dataDetails['transction'][$key]['party'] = isset($list->getUser) ? $list->getUser->name : "";
                    $dataDetails['transction'][$key]['mode'] = isset($list->mode) ? $list->mode : "";
                    $dataDetails['transction'][$key]['online'] = isset($list->online) ? $list->online : "";
                    $dataDetails['transction'][$key]['paid'] = isset($list->paid) ? $list->paid : "";
                    $dataDetails['transction'][$key]['receive'] = isset($list->receive) ? $list->receive : "";
                    $dataDetails['transction'][$key]['balance'] = isset($list->balance) ? $list->balance : "";
                }
                $dataDetails['paid_total'] = isset($paidTotal) ? (string)$paidTotal : "";
                $dataDetails['receive_total'] = isset($receiveTotal) ? (string)$receiveTotal : "";
            }
            return $this->sendResponse($dataDetails, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Purchase Details api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    // this function use payment adujst balance
    public function addMoney(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mode' => 'required',
                'type' => 'required',
                'amount' => 'required',

            ], [
                'mode.required' => 'Please Enter Mode',
                'type' => 'required',
                'amount' => 'required',

            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $paymentList = PaymentDetails::orderBy('id', 'DESC')->first();
            if (isset($paymentList->balance)) {
                $addMoney = $paymentList->balance;
            } else {
                $addMoney = $request->amount;
            }
            $paymentDetails = new PaymentDetails;
            $paymentDetails->mode = $request->mode;
            $paymentDetails->online = 'Bank';
            $paymentDetails->date = date('Y-m-d');
            $paymentDetails->type = 'Add Money';
            $paymentDetails->paid = '-';
            if ($request->type == "+") {
                $total = $addMoney + $request->amount;
                $paymentDetails->receive = round($request->amount, 2);
                $paymentDetails->balance = round($total, 2);
            } elseif ($request->type == "-") {
                $total = $addMoney - $request->amount;
                $paymentDetails->receive = round($request->amount, 2);
                $paymentDetails->balance = round($total, 2);
            }
            $paymentDetails->save();

            $userLogs = new LogsModel;
            $userLogs->message = 'Money Added';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            return $this->sendResponse([], 'Balance Adujst Successfully.');
        } catch (\Exception $e) {
            Log::info("Add Money api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function categoryList(Request $request)
    {
        try {
            $cashCategory = CashCategory::get();

            $categoryData = [];
            if (isset($cashCategory)) {
                foreach ($cashCategory as $key => $list) {
                    $categoryData[$key]['id'] = isset($list->id) ? $list->id : "";
                    $categoryData[$key]['name'] = isset($list->name) ? $list->name : "";
                }
            }

            return $this->sendResponse($categoryData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Add Money api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function cashManagmentCreate(Request $request)
    {
        try {
            $cashAmount = CashManagement::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
            if (isset($cashAmount)) {
                if ($request->type == 'credit') {
                    $amount =  $cashAmount->opining_balance + $request->amount;
                } elseif ($request->type == 'debit') {
                    $amount =  $cashAmount->opining_balance - $request->amount;
                }
            } else {
                $amount = $request->amount;
            }
            $cashMnagemnt = new CashManagement;
            $cashMnagemnt->date    = $request->date;
            $cashMnagemnt->category    = $request->category;
            $cashMnagemnt->description    = $request->description;
            $cashMnagemnt->voucher    = 'cash';
            $cashMnagemnt->type    = $request->type;
            $cashMnagemnt->amount    = round($request->amount, 2);
            $cashMnagemnt->user_id = auth()->user()->id;
            $cashMnagemnt->opining_balance = round($amount, 2);
            $cashMnagemnt->save();

            $userLogs = new LogsModel;
            $userLogs->message = 'Cash Managment  Added';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();
            return $this->sendResponse([], 'Cash Managment Created Successfully.');
        } catch (\Exception $e) {
            Log::info("cash Managment  api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function cashManagmentList(Request $request)
    {
        try {
            $cashManagment = CashManagement::where('user_id', auth()->user()->id)->orderBy('id', 'DESC');
            if ((isset($request->start_date)) && ($request->end_date)) {
                $startDate = $request->start_date;
                $EndDate = $request->end_date;
                $cashManagment->whereBetween('date', [$startDate, $EndDate]);
            } else {
                $cashManagment->whereDate('date', date('Y-m-d'));
            }
            $cashManagmentTotal = $cashManagment->get();
            $limit = $request->filled('limit') ? $request->limit : 10;
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $offset = ($page - 1) * $limit;
            $cashManagment->offset($offset)->limit($limit);
            $cashManagment = $cashManagment->get();
            $cashList = [];
            if (isset($cashManagment)) {
                foreach ($cashManagment as $key => $list) {
                    $categoryName = CashCategory::where('id', $list->category)->first();
                    $cashList[$key]['id'] = isset($list->id) ? $list->id : "";
                    $cashList[$key]['category'] = isset($categoryName->name) ? $categoryName->name : "";
                    $cashList[$key]['date'] = isset($list->date) ? $list->date : "";
                    $cashList[$key]['description'] = isset($list->description) ? $list->description : "";
                    $cashList[$key]['voucher'] = isset($list->voucher) ? $list->voucher : "";
                    $cashList[$key]['reference_no'] = isset($list->reference_no) ? $list->reference_no : "";
                    $cashList[$key]['type'] = isset($list->type) ? $list->type : "";
                    if ($list->type == 'credit') {
                        $cashList[$key]['credit'] = isset($list->amount) ? $list->amount : "";
                        $cashList[$key]['debit'] = "";
                    } else {
                        $cashList[$key]['credit'] = "";
                        $cashList[$key]['debit'] = isset($list->amount) ? $list->amount : "";
                    }
                    $cashList[$key]['amount'] = isset($list->opining_balance) ? $list->opining_balance : "";
                    $cashList[$key]['opining_balance'] = isset($list->opining_balance) ? $list->opining_balance : "";
                }
            }
            $creditData = $cashManagment->where('type', 'credit')->sum('amount');
            $debitData = $cashManagment->where('type', 'debit')->sum('amount');
            $totalAmount = $cashManagment->sum('opining_balance');

            $dataList['cash_list'] =  $cashList;
            $dataList['credit'] =  $creditData;
            $dataList['debit'] =  $debitData;
            $dataList['total'] =  round($totalAmount, 2);
            $dataList['count'] = $cashManagmentTotal->count();
            return $this->sendResponse($dataList, 'Cash Managment List Successfully.');
        } catch (\Exception $e) {
            Log::info("cash Managment List  api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    private function generatePDF($dataList, $path)
    {
        // Instantiate Dompdf with options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $pdf = new Dompdf($options);

        // Load HTML content (you need to create a blade view for the PDF content)
        $html = view('cash_report', compact('dataList'))->render();
        $pdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $pdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $pdf->render();

        // Output the generated PDF (1. Save to file, 2. Stream to browser, 3. Return raw PDF data)
        $output = $pdf->output();

        // Save the PDF to the specified path
        file_put_contents($path, $output);
    }

    public function cashManagmentPdf(Request $request)
    {
        try {

            $cashManagment = CashManagement::where('user_id', auth()->user()->id)->orderBy('id', 'DESC');
            if ((isset($request->start_date)) && ($request->end_date)) {
                $startDate = $request->start_date;
                $EndDate = $request->end_date;
                $cashManagment->whereBetween('date', [$startDate, $EndDate]);
            } else {
                $cashManagment->whereDate('date', date('Y-m-d'));
            }

            $cashManagment = $cashManagment->get();

            $cashList = [];
            if (isset($cashManagment)) {
                foreach ($cashManagment as $key => $list) {
                    $categoryName = CashCategory::where('id', $list->category)->first();
                    $cashList[$key]['id'] = isset($list->id) ? $list->id : "";
                    $cashList[$key]['category'] = isset($categoryName->name) ? $categoryName->name : "";
                    $cashList[$key]['date'] = isset($list->date) ? $list->date : "";
                    $cashList[$key]['description'] = isset($list->description) ? $list->description : "";
                    $cashList[$key]['type'] = isset($list->type) ? $list->type : "";
                    $cashList[$key]['voucher'] = isset($list->voucher) ? $list->voucher : "";
                    $cashList[$key]['reference_no'] = isset($list->reference_no) ? $list->reference_no : "";
                    if ($list->type == 'credit') {
                        $cashList[$key]['credit'] = isset($list->amount) ? $list->amount : "";
                        $cashList[$key]['debit'] = "";
                    } else {
                        $cashList[$key]['credit'] = "";
                        $cashList[$key]['debit'] = isset($list->amount) ? $list->amount : "";
                    }
                    $cashList[$key]['amount'] = isset($list->amount) ? $list->amount : "";
                    $cashList[$key]['opining_balance'] = isset($list->opining_balance) ? $list->opining_balance : "";
                }
            }

            $creditData = $cashManagment->where('type', 'credit')->sum('amount');
            $debitData = $cashManagment->where('type', 'debit')->sum('amount');
            $totalAmount = $cashManagment->sum('opining_balance');

            $dataList['cash_list'] =  $cashList;
            $dataList['credit'] =  $creditData;
            $dataList['debit'] =  $debitData;
            $dataList['total'] =  round($totalAmount, 2);

            // Save PDF to a temporary location
            $pdfPath = public_path('pdf/cash_report.pdf');
            $this->generatePDF($dataList, $pdfPath);

            // Send email with attached PDF
            $userEmail = auth()->user()->email;
            Mail::to($userEmail)->send(new CashMail($pdfPath));

            return $this->sendResponse([], 'Cash Report Mail Send Successfully.');
        } catch (\Exception $e) {
            Log::info("cash Managment List  api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
