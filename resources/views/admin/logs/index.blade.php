@extends('layouts.main')
@section('main')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Logs</h3>
                       
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Message</th>
                                    <th>Date & Time</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($logsData))
                                @foreach($logsData as $index => $list)
                              <?php 
                                 $userDetails = App\Models\User::where('id',$list->user_id)->first();
                              ?>
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ isset($list->message) ? $list->message :'' }}</td>
                                    <td>{{ isset($list->date_time) ? $list->date_time :'' }}</td>
                                    <td>{{ isset($userDetails->name) ? $userDetails->name:''}}</td>
                                   
                                </tr>
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
