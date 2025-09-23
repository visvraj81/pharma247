<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\PassBook;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Mail\PassBookMail;
use Illuminate\Support\Facades\Mail;
use  App\Models\CashManagement;
use Carbon\Carbon;
use App\Models\LogsModel;

class BankController extends ResponseController
{
    public function addBank(Request $request)
    {
        try {
            $newBank = new BankAccount;
            $newBank->bank_name = $request->bank_name;
            $newBank->bank_account_name = $request->bank_account_name;
            $newBank->opening_balance = $request->opening_balance;
            $newBank->date = $request->date;
            $newBank->bank_account_number = $request->bank_account_number;
            $newBank->reenter_bank_account_number     = $request->reenter_bank_account_number;
            $newBank->ifsc_code     = $request->ifsc_code;
            $newBank->bank_branch_name = $request->bank_branch_name;
            $newBank->account_holder_name = $request->account_holder_name;
            $newBank->upi_id = $request->upi_id;
            $newBank->user_id = auth()->user()->id;
            $newBank->save();

            $passbook = new PassBook;
            $passbook->user_id = auth()->user()->id;
            $passbook->bank_id =  $newBank->id;
            $passbook->date = $request->date;
            $passbook->deposit = $request->opening_balance;
            $passbook->balance = $request->opening_balance;
            $passbook->remark = 'Opening Balance';
            $passbook->save();

            $userLogs = new LogsModel;
            $userLogs->message = 'Bank Create';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            return $this->sendResponse([], 'Bank Create Successfully.');
        } catch (\Exception $e) {
            Log::info("Create Bank api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function bankList(Request $request)
    {
        try {
            $bankName = BankAccount::where('user_id', auth()->user()->id)->orderBy('id', 'DESC');
          	
          	if (isset($request->type)) {
                if ($request->type == 1) {
                    $bankName->where('bank_account_name', 'Saving');
                } else {
                    $bankName->where('bank_account_name', 'Current');
                }
            }
            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
            $offset = ($page - 1) * $limit;
            $bankName->limit($limit)->offset($offset);
            $bankName = $bankName->get();

            $bankList = [];

            if (isset($bankName)) {
                foreach ($bankName as $key => $listBank) {
                    $cashAmount = CashManagement::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
                    $passBookAmount = PassBook::where('user_id', auth()->user()->id)->where('bank_id', $listBank->id)->orderBy('id', 'DESC')->first();
                  
                  	$bank_name = $listBank->bank_name ?? '';
					$accountHolder = $listBank->account_holder_name ?? '';
                  	
                    $bankList[$key]['id'] = isset($listBank->id) ? $listBank->id : "";
                    $bankList[$key]['bank_name'] = isset($listBank->bank_name) ? $listBank->bank_name : "";
                  	$bankList[$key]['bank_user_name'] = trim($bank_name . ' - ' . $accountHolder);
                    $bankList[$key]['total_amount'] = isset($passBookAmount->balance) ? (string)round($passBookAmount->balance, 2) : "";
                    $bankList[$key]['cash_amount'] = isset($cashAmount->opining_balance) ? (string)round($cashAmount->opining_balance, 2) : "";
                    $bankList[$key]['opening_balance'] = isset($listBank->opening_balance) ? $listBank->opening_balance : "";
                    $bankList[$key]['bank_account_name'] = isset($listBank->bank_account_name) ? $listBank->bank_account_name : "";
                    $bankList[$key]['date'] = isset($listBank->date) ? $listBank->date : "";
                    $bankList[$key]['bank_account_number'] = isset($listBank->bank_account_number) ? $listBank->bank_account_number : "";
                    $bankList[$key]['reenter_bank_account_number'] = isset($listBank->reenter_bank_account_number) ? $listBank->reenter_bank_account_number : "";
                    $bankList[$key]['ifsc_code'] = isset($listBank->ifsc_code) ? $listBank->ifsc_code : "";
                    $bankList[$key]['bank_branch_name'] = isset($listBank->bank_branch_name) ? $listBank->bank_branch_name : "";
                    $bankList[$key]['account_holder_name'] = isset($listBank->account_holder_name) ? $listBank->account_holder_name : "";
                    $bankList[$key]['upi_id'] = isset($listBank->upi_id) ? $listBank->upi_id : "";
                }
            }

            return $this->sendResponse($bankList, 'Bank List Get Successfully.');
        } catch (\Exception $e) {
            Log::info("list Bank api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function bankDetails(Request $request)
    {
        try {
            $passBookList = PassBook::where('bank_id', $request->bank_id);

            if (!empty($request->search)) {
                $passBookList->where(function ($q) use ($request) {
                    $q->where('party_name', 'like', '%' . $request->search . '%')
                      ->orWhere('remark', 'like', '%' . $request->search . '%');
                });
            }

            if (!empty($request->start_date) && !empty($request->end_date)) {
                $passBookList->whereBetween('date', [$request->start_date, $request->end_date]);
            } else {
                $firstDateOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
                $currentDate = Carbon::now()->format('Y-m-d');
                $passBookList->whereBetween('date', [$firstDateOfMonth, $currentDate]);
            }

            $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
            $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
            $offset = ($page - 1) * $limit;

            $passBookList = $passBookList
                ->where('user_id', auth()->user()->id)
                ->orderBy('id', 'DESC')
                ->offset($offset)
                ->limit($limit)
                ->get();
          
          	$passBookTotalCount = PassBook::where('bank_id', $request->bank_id)->where('user_id', auth()->user()->id)->count();

            $bankList = [];
            if (isset($passBookList)) {
                foreach ($passBookList as $key => $listBook) {
                    $bankList[$key]['id'] = isset($listBook->id) ? $listBook->id : "";
                    $bankList[$key]['date'] = isset($listBook->date) ? $listBook->date : "";
                    $bankList[$key]['party_name'] = isset($listBook->party_name)  ? $listBook->party_name != 'null' ? $listBook->party_name : "" : "";
                    $bankList[$key]['deposit'] = isset($listBook->deposit) ? $listBook->deposit : "";
                    $bankList[$key]['withdraw'] = isset($listBook->withdraw) ? $listBook->withdraw : "";
                    $bankList[$key]['balance'] = isset($listBook->balance) ? $listBook->balance : "";
                    $bankList[$key]['remark'] = isset($listBook->remark) ? $listBook->remark : "";
                }
            }
          
          	$response = [
              'status' => 200,
              'count' => !empty($request->page) ? $passBookList->count() : $passBookTotalCount,
              'total_records' => $passBookTotalCount,
              'data'   => $bankList,
              'message' => 'Bank Details Fetch Successfully.',
            ];
            return response()->json($response, 200);

            // return $this->sendResponse($bankList, 'Bank Details Fetch Successfully.');
        } catch (\Exception $e) {
            Log::info("details Bank api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function pdfBank(Request $request)
    {
        $passBookList = PassBook::where('bank_id', $request->bank_id)->where('user_id', auth()->user()->id);

        if (isset($request->search)) {
            $passBookList->where('party_name', 'like', '%' . $request->search . '%');
        }

        if ((isset($request->start_date)) && (isset($request->end_date))) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $passBookList->whereBetween('date', [$startDate, $endDate]);
        }

        $passbooks = $passBookList->orderBy('id', 'DESC')->get();


        // Save PDF to a temporary location
        $pdfPath = public_path('pdf/passbook_report.pdf');
        $this->generatePDF($passbooks, $pdfPath);

        // Send email with attached PDF
        $userEmail = auth()->user()->email;
        Mail::to($userEmail)->send(new PassBookMail($pdfPath));

        return $this->sendResponse([], 'Passbook report sent successfully.');
    }

    private function generatePDF($passbooks, $path)
    {
        // Instantiate Dompdf with options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $pdf = new Dompdf($options);

        // Load HTML content (you need to create a blade view for the PDF content)
        $html = view('passbook_report', compact('passbooks'))->render();
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

    public function addBalance(Request $request)
    {
        try {
            if ($request->payment_type == 'cash') {
                $cashNew = new CashManagement;
                $cashNew->date = $request->date;
                if ($request->add_or_reduce == '0') {
                    $cashNew->type = 'credit';
                } else {
                    $cashNew->type = 'debit';
                }
                $cashNew->amount = $request->amount;
                $cashNew->opining_balance = round($request->total_amount, 2);
                $cashNew->description = $request->remark;
                $cashNew->voucher = 'Adjust Money';
                $cashNew->user_id = auth()->user()->id;
                $cashNew->save();
            } else {
                $passbook = new PassBook;
                $passbook->user_id = auth()->user()->id;
                $passbook->bank_id =  $request->payment_type;

                $passbook->date = $request->date;
                if ($request->add_or_reduce == '0') {
                    $passbook->deposit = round($request->amount, 2);
                } else {

                    $passbook->withdraw = $request->amount;
                }
                $passbook->balance = round($request->total_amount, 2);
                $passbook->remark = $request->remark;
                $passbook->save();
            }

            $userLogs = new LogsModel;
            $userLogs->message = 'Adujst Balance';
            $userLogs->user_id = auth()->user()->id;
            $userLogs->date_time = date('Y-m-d H:i a');
            $userLogs->save();

            return $this->sendResponse([], 'Adujst Balance successfully.');
        } catch (\Exception $e) {
            Log::info("add Bank api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
