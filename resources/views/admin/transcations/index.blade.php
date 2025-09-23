@extends('layouts.main')
@section('main')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Transactions</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Date</th>
                                    <th>Next Payment Date</th>
                                    <th>Pharma Shop</th>
                                    <th>Transcation Id</th>
                                    <th>Plan Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                 $detailsList = \App\Models\Transcations::all();
                                
                                ?>
                                @if(isset($detailsList))
                                <?php
                                $i = 1;
                                ?>
                                @foreach($detailsList as $list)
                              <?php 
                               $detailsListPlan = \App\Models\SubscriptionPlan::where('id',$list->plan_name)->first();
                               $userName = \App\Models\User::where('id',$list->pharma_name)->first();
                              ?>
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        {{$list->date}}
                                    </td>
                                    <td>
                                        {{$list->next_payment_date}}
                                    </td>
                                    <td>
                                        {{isset($userName->name) ? $userName->name :""}}
                                    </td>
                                    <td>
                                        {{$list->transcation_id}}
                                    </td>
                                  <td>
                                        {{ isset($detailsListPlan->name) ? $detailsListPlan->name :""}}
                                    </td>
                                  
                                    <td>
                                        {{  isset($detailsListPlan->annual_price) ? $detailsListPlan->annual_price : ""}}
                                    </td>
                        
                                </tr>
                                <?php
                                $i++;
                                ?>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
@endsection

@section('js')
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('.example2').DataTable();
    });
</script>
@endsection