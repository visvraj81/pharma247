
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Expense Report</title>
    <link rel="stylesheet" href="{{asset('invoice/style.css')}}" media="all" />
    
  </head>
  <body>
    <header class="clearfix">
      <div id="logo" style="margin-right: 702px;width: 300px;">
        <img src="https://thekapda.in/pharmalogo.png">
      </div>
       
      <div id="company" class="clearfix">
     
      </div>
      <div id="project" style="float: right;">
         <div><span>Date</span> {{date('d-m-Y')}}</div>
        <div><span>Name</span> {{auth()->user()->name}}</div>
        <div><span>Mobile Number</span> {{auth()->user()->phone_number}}</div>
        <div><span>Email</span> <a href="mailto:john@example.com">{{auth()->user()->email}}</a></div>
      </div>
    </header>
    <main>
        <h3>Expense Statment </h3>
      <table>
        <thead>
          <tr>
               <th>Sr</th>
               <th>Expense Date</th>
               <th>Category</th>
               <th>Payment Mode</th>
               <th>Reference No</th>
               <th>Remark</th>
               <th>Total</th>
               <th>Amount</th>
               <th>GST</th>
          </tr>
        </thead>
        <tbody>
              @php $total = 1; @endphp
              @php  $i =1; @endphp
                    @foreach($expenses as $expenseData)
                
                    @php
                      $category = \App\Models\CashCategory::where('id',$expenseData->category)->first();
                     $bankName  = \App\Models\BankAccount::where('id',$expenseData->payment_mode)->first();
                   @endphp
                        <tr style="background-color: white;">
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $i }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['expense_date'] }} </td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ isset($category->name) ? $category->name  :"" }} </td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ isset($bankName->bank_name) ? $bankName->bank_name :"cash" }} </td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['reference_no'] }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['remark'] }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['total'] }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['amount'] }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['gst'] }}</td>
                   </tr>
                   @php $i++;  @endphp
                   @php $total += $expenseData['total']; @endphp
                    @endforeach
                    <tr>
                           <td > </td>
                           <td ></td>
                           <td ></td>
                           <td ></td>
                    <td colspan="4">    Total</td>
                    <td class="total">{{ $total }}</td>
                  </tr>
     
        </tbody>
      </table>
      <div id="notices">
        <div class="notice">Thanks for Expense Report!</div>
      </div>
    </main>
   
  </body>
</html>