<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use  App\Models\ExpenseModel;
use  App\Models\CashManagement;
use App\Models\CashCategory;
use App\Models\BankAccount;
use App\Models\PassBook;
use PDF;
use App\Mail\ExpenseMail;
use Illuminate\Support\Facades\Mail;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\LogsModel;

class ExpenseContorller extends ResponseController
{
    public function addExpense(Request $request)
    {
        try {
           $expensData = new ExpenseModel;
           $expensData->category       = isset($request->category) ? $request->category : null;
           $expensData->expense_date   = isset($request->expense_date) ? $request->expense_date : null;
           $expensData->payment_date   = isset($request->payment_date) ? $request->payment_date : null;
           $expensData->gst_type       = isset($request->gst_type) ? $request->gst_type : null;
           $expensData->gst            = isset($request->gst) ? $request->gst : null;
           $expensData->gstn_number    = isset($request->gstn_number) ? $request->gstn_number : null;
           $expensData->party          = isset($request->party) ? $request->party : null;
           $expensData->amount         = isset($request->amount) ? $request->amount : null;
           $expensData->total          = isset($request->total) ? $request->total : null;
           $expensData->payment_mode   = isset($request->payment_mode) ? $request->payment_mode : null;
           if($request->payment_mode == 'cash')
           {
            $cashManage = CashManagement::where('user_id',auth()->user()->id)->orderBy('id', 'DESC')->first();
         
            if(isset($cashManage))
            {
              $category = CashCategory::where('id',$request->category)->first();
              $amount = $cashManage->opining_balance - $request->total;
              $cashAdd = new CashManagement;
              $cashAdd->date = $request->payment_date;
              $cashAdd->category = $request->category;
              $cashAdd->description = 'Expenses Manage';
              $cashAdd->reference_no =  $request->reference_no;
              $cashAdd->voucher	 =  $category->name;
              $cashAdd->type = 'debit';
              $cashAdd->amount = $request->total;
              $cashAdd->user_id = auth()->user()->id;
              $cashAdd->opining_balance = $amount;
              $cashAdd->save();
            }else{
              $category = CashCategory::where('id',$request->category)->first();
              $cashAdd = new CashManagement;
              $cashAdd->date = $request->payment_date;
              $cashAdd->category = $request->category;
              $cashAdd->description = 'Expenses Manage';
              $cashAdd->reference_no =  $request->reference_no;
              $cashAdd->voucher	 =  $category->name;
              $cashAdd->type = 'debit';
              $cashAdd->amount = $request->total;
              $cashAdd->user_id = auth()->user()->id;
              $cashAdd->opining_balance = $request->total;
              $cashAdd->save();
            }
           }else{
             $passBook =  PassBook::where('bank_id',$request->payment_mode)->where('user_id',auth()->user()->id)->orderBy('id', 'DESC')->first();
             if(isset($passBook))
             {
                $amount = $passBook->balance - $request->total;
                $category = CashCategory::where('id', $request->category)->first();
                $passbook = new PassBook;
                $passbook->user_id = auth()->user()->id;
                $passbook->date = $request->payment_date;
                $passbook->party_name = isset($request->party) ? $request->party :"";
                $passbook->bank_id = $request->payment_mode;
                $passbook->deposit = "";
                $passbook->withdraw	 = $request->total;
                $passbook->balance = $amount;
                $passbook->mode = "";
                $passbook->remark = $category->name;
                $passbook->save();
             } else{
                $category = CashCategory::where('id', $request->category)->first();
                $passbook = new  PassBook;
                $passbook->user_id = auth()->user()->id;
                $passbook->date = $request->payment_date;
                $passbook->party_name = isset($request->party) ? $request->party :"";
                $passbook->bank_id = $request->payment_mode;
                $passbook->deposit = "";
                $passbook->withdraw	 = $request->total;
                $passbook->balance = $request->total;
                $passbook->mode = "";
                $passbook->remark = $category->name;
                $passbook->save();
             }
           }
           $expensData->reference_no = isset($request->reference_no) ? $request->reference_no : null;
           $expensData->remark = isset($request->remark) ? $request->remark : null;
           $expensData->user_id = auth()->user()->id;
           $expensData->save();
           
           $userLogs = new LogsModel;
           $userLogs->message = 'Expense Added';
           $userLogs->user_id = auth()->user()->id;
           $userLogs->date_time = date('Y-m-d H:i a');
           $userLogs->save();
           
           return $this->sendResponse([], 'Expense Added Successfully.');
        } catch (\Exception $e) {
          Log::info("Create Expense api" . $e->getMessage());
          return $e->getMessage();
       }
    }
    
    public function listExpense(Request $request)
    {
        try{
          $listExpense = ExpenseModel::where('user_id',auth()->user()->id)->orderBy('id', 'DESC');
          if(isset($request->start_date) && isset($request->end_date))
          {
              $startDate = $request->start_date;
              $endDate = $request->end_date;
              $listExpense->whereBetween('expense_date', [$startDate, $endDate]);
          }
          if(isset($request->category))
          {
              $listExpense->where('category', $request->category);
          }
          $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
          $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;
          $offset = ($page - 1) * $limit;
          $listExpense = $listExpense->offset($offset)->limit($limit)->get();
          $total = $listExpense->sum('total');
          
          $expenseTotalCount = ExpenseModel::where('user_id',auth()->user()->id)->count();
          
          $expenseData = [];
          if(isset($listExpense))
          {
              foreach($listExpense as $key => $list)
              {
                  $category = CashCategory::where('id',$list->category)->first();
                  $bankName  = BankAccount::where('id',$list->payment_mode)->first();
                  $expenseData[$key]['id'] = isset($list->id) ? $list->id :"";
                  $expenseData[$key]['expense_date'] = isset($list->expense_date) ? $list->expense_date :"";
                  $expenseData[$key]['category'] = isset($category->name) ? $category->name :"";
                  $expenseData[$key]['payment_mode'] = isset($bankName->bank_name) ? $bankName->bank_name :"cash";
                  $expenseData[$key]['reference_no'] = isset($list->reference_no) ? $list->reference_no :"";
                  $expenseData[$key]['remark'] = isset($list->remark) ? $list->remark :"";
                  $expenseData[$key]['total'] = isset($list->total) ? $list->total :"";
                  $expenseData[$key]['amount'] = isset($list->amount) ? $list->amount :"";
                  $expenseData[$key]['gst'] = isset($list->gst) ? $list->gst != 'null' ? $list->gst :"" :"";
              }
          }
          $dataList['expense_list'] = $expenseData;
          $dataList['total'] = $total;
          
          $response = [
              'status' => 200,
              'count' => !empty($request->page) ? $listExpense->count() : $expenseTotalCount,
              'total_records' => $expenseTotalCount,
              'data'   => $dataList,
              'message' => 'Expense List Get Successfully.',
            ];
            return response()->json($response, 200);
          // return $this->sendResponse( $dataList, 'Expense List Get Successfully.');
        } catch (\Exception $e) {
          Log::info("list Expense api" . $e->getMessage());
          return $e->getMessage();
       }
    }
            
        public function pdfExpense(Request $request)
        {
            $listExpense = ExpenseModel::where('user_id', auth()->user()->id)->orderBy('id', 'DESC');
        
            // Apply filters if provided
            if (isset($request->start_date) && isset($request->end_date)) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                $listExpense->whereBetween('expense_date', [$startDate, $endDate]);
            }
            if (isset($request->category)) {
                $listExpense->where('category', $request->category);
            }
        
            // Fetch expenses
            $expenses = $listExpense->get();
        
            // Calculate total
            $total = $expenses->sum('total');
        
            // Save PDF to a temporary location
            $pdfPath = public_path('pdf/expense_report.pdf');
            $this->generatePDF($expenses, $pdfPath);
           
            // Send email with attached PDF
            $userEmail = auth()->user()->email;
            Mail::to($userEmail)->send(new ExpenseMail($pdfPath));
        
            return $this->sendResponse([], 'Expense report sent successfully');
        }
        
       private function generatePDF($expenses, $path) {
            // Instantiate Dompdf with options
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $pdf = new Dompdf($options);
            
            // Load HTML content (you need to create a blade view for the PDF content)
            $html = view('expense_report', compact('expenses'))->render();
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
}
