
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Cash Report</title>
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
        <h3>Cash Statment </h3>
      <table>
        <thead>
          <tr>
               <th>Sr</th>
               <th>Category</th>
               <th>Date</th>
               <!--<th>Description</th>-->
               <th>Voucher</th>
               <th>Reference No	</th>
               <th>Credit</th>
               <th>Debit</th>
               <th>Amount</th>
               <th>Opining Balance</th>
          </tr>
        </thead>
        <tbody>
              @php  $i =1; @endphp
                    @foreach($dataList['cash_list'] as $expenseData)
                              
                        <tr style="background-color: white;">
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $i }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['category'] }} </td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['date']   }} </td>
                           <!--<td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['description'] }} </td>-->
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['voucher'] }} </td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['reference_no'] }} </td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['credit'] }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['debit'] }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['amount'] }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $expenseData['opining_balance'] }}</td>
                   </tr>
                   @php $i++;  @endphp
                    @endforeach
                    <tr>
                           <td ></td>
                           <td ></td>
                           <td ></td>
                    <td colspan="4">Total Credit</td>
                    <td class="total">{{ $dataList['credit'] }}</td>
                  </tr>
                   <tr>
                           <td ></td>
                           <td ></td>
                           <td ></td>
                    <td colspan="4">Total Debit</td>
                    <td class="total">{{ $dataList['debit'] }}</td>
                  </tr>
                   <tr>
                           <td ></td>
                           <td ></td>
                           <td ></td>
                    <td colspan="4">    Total</td>
                    <td class="total">{{ $dataList['total'] }}</td>
                  </tr>
        </tbody>
      </table>
      <div id="notices">
        <div class="notice">Thanks for Cash Report!</div>
      </div>
    </main>
   
  </body>
</html>