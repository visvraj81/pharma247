@extends('pharma.layout.main')
@section('main')
@if(Session::has('error'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
@endif
@if(Session::has('success'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
@endif
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <form action="{{route('purches.return.store')}}" method="POST" enctype="multipart/form-data" class="forms-sample">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Distributor</label>
                        <select class="form-control" name="distributor_id" id="distributor_id">
                            @if(isset($distrubuter))
                            @foreach($distrubuter as $list)
                            <option value="{{$list->id}}">{{$list->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Bill Date</label>
                        <input type="date" class="form-control" name="bill_date" placeholder="Enter Bill Date">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Select Date</label>
                        <input type="date" class="form-control" name="select_date" placeholder="Enter Select Date">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Remark</label>
                        <input type="text" class="form-control" name="remark" placeholder="Enter Remark">
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="dataTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>HSN Code</th>
                                <th>Unit</th>
                                <th>Batch</th>
                                <th>Exp Date</th>
                                <th>MRP</th>
                                <th>Qty</th>
                                <th>FR Qty</th>
                                <th>PTR</th>
                                <th>D%</th>
                                <th>Disc</th>
                                <th>Base</th>
                                <th>GST</th>
                                <th>Amount</th>
                                <th>LP</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!-- Submit Button -->
                <br> <br>
               
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
</div>



@endsection
@section('js')

<script>
    $(document).ready(function(){
        $(document).on("change","#distributor_id",function(){
            var distributorData = $(this).val();
            $.ajax({
                url: "{{route('purches-data')}}",
                data: { 
                    "id": distributorData
                },
                cache: false,
                type: "GET",
                success: function(response) {
                      console.log(response.dataReponse);

                      var responseData = response.dataReponse;
                      
                      var table = $('#dataTable'); // Define table variable
                      table.find('tbody').empty();
                      $.each(responseData, function(index, item) {
                        // Append a new row to the table
                        var newRow = $('<tr>');
                        
                        // Add columns to the row with data from the current item
                        newRow.append('<td>' + item.iteam_id + '</td> <input type="hidden" name="iteam[]" value="'+ item.iteam_id +'" id="iteam">');
                        newRow.append('<td>' + item.hsn_code + '</td> <input type="hidden" name="hsn_code[]" value="'+ item.hsn_code +'" id="hsn_code">');
                        newRow.append('<td>' + item.unit + '</td> <input type="hidden" name="unit[]" value="'+ item.unit +'" id="unit">');
                        newRow.append('<td>' + item.batch + '</td> <input type="hidden" name="batch[]"  value="'+ item.batch +'" id="batch">');
                        newRow.append('<td>' + item.exp_dt + '</td>  <input type="hidden" name="exp_dt[]" value="'+ item.exp_dt +'" id="exp_dt">');
                        newRow.append('<td>' + item.mrp + '</td>  <input type="hidden" name="mrp[]" value="'+ item.mrp +'" id="mrp">');
                        newRow.append('<td>' + item.qty + '</td> <input type="hidden" name="qty[]" value="'+ item.qty +'" id="qty">');
                        newRow.append('<td>' + item.fr_qty + '</td> <input type="hidden" name="fr_qty[]" value="'+ item.fr_qty +'" id="fr_qty">');
                        newRow.append('<td>' + item.ptr + '</td> <input type="hidden" name="ptr[]" value="'+ item.ptr +'" id="ptr">');
                        newRow.append('<td>' + item.d_percent + '</td> <input type="hidden" name="d_percent[]"  value="'+ item.d_percent +'" id="d_percent">');
                        newRow.append('<td>' + item.disocunt + '</td>  <input type="hidden" name="disocunt[]" value="'+ item.disocunt +'" id="disocunt">');
                        newRow.append('<td>' + item.base + '</td> <input type="hidden" name="base[]" value="'+ item.base +'" id="base">');
                        newRow.append('<td>' + item.gst + '</td> <input type="hidden" name="gst[]" value="'+ item.gst +'" id="gst">');
                        newRow.append('<td>' + item.amount + '</td>   <input type="hidden" name="amount[]" value="'+ item.amount +'" id="amount">');
                        newRow.append('<td>' + item.lp + '</td>  <input type="hidden" name="lp[]" value="'+ item.lp +'" id="lp">');
                        newRow.append('<td>' + item.location + '</td> <input type="hidden" value="'+ item.location +'" name="location[]" id="location">');
                        newRow.append('<td> <a class="btn btn-primary"> Delete <a> </td>');
                        // Append the new row to the table
                        table.find('tbody').append(newRow);
                    });
                }
            });
        });
    });
</script>
@endsection