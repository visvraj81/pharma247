@extends('layouts.main')
@section('main')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Support Ticket</h3>
                        
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($detailsList))
                                <?php
                                $i = 1;
                                ?>
                                @foreach($detailsList as $list)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        
                                        {{$list['name']}}
                                    </td>
                                    <td>
                                        {{$list['email']}}
                                    </td>
                                    <td>
                                        {{$list['status']}}
                                    </td>
                                    <td >
                                        <a href="{{ route('support.edit',$list['id']) }}" class="text-white btn btn-success "><i class="fa fa-edit"></i></a>
                                        <a href="{{ route('support.delete',$list['id']) }}" class="text-white btn btn-danger"><i class="fa fa-trash"></i></a>
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