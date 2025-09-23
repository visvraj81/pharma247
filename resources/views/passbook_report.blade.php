<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Passbook Report</title>
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
        <h3>Bank Statment</h3>
      <table>
        <thead>
          <tr>
               <th>ID</th>
               <th>Date</th>
               <th>Party Name</th>
               <th>Deposit</th>
               <th>Withdraw</th>
               <th>Balance</th>
               <th>Remark</th>
          </tr>
        </thead>
        <tbody>
          @php
                    $i = 1;
                   @endphp
                    @foreach($passbooks as $passbook)
                       
                        <tr style="background-color: white;">
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $i }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $passbook['date'] }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $passbook['party_name'] }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $passbook['deposit'] }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $passbook['withdraw'] }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $passbook['balance'] }}</td>
                           <td style="padding: 9px 20px; border: 1px solid #ddd;">{{ $passbook['remark'] }}</td>
                   </tr>
                   @php
                   $i++;
                   @endphp
                   @endforeach
     
        </tbody>
      </table>
      <div id="notices">
        <div class="notice">Thanks for Passbook Report!</div>
      </div>
    </main>
   
  </body>
</html>