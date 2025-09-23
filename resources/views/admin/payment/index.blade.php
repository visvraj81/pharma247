@extends('layouts.main')
@section('main')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Payment Method</h3>
                        <a href="{{route('payment.create')}}" class="text-white btn btn-primary float-right">Add Payment</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Payment Name</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($detailsList))
                                <?php
                                $i=1; 
                                ?>
                                    @foreach($detailsList as $list)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>
                                                {{$list['payment_method']}}
                                            </td>
                                            <td>
                                                <img src="{{'payment_image/'.$list['icon']}}" height="50" width="50" />
                                            </td>
                                            <td>
                                                <a href="{{route('payment.edit',$list['id'])}}" class="text-white btn btn-success"><i class="fa fa-edit"></i></a>
                                                   <a href="{{route('payment.delete',$list['id'])}}" class="text-white btn btn-danger"><i class="fa fa-trash"></i></a>
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
    $(document).ready(function () {
        $('.example2').DataTable();
    });
</script>
@endsection