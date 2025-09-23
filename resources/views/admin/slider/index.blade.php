@extends('layouts.main')
@section('main')
<style>
    .btn-primary:hover {
        color: #fff;
        background-color: #628a2f;
        border-color: #628a2f;
    }

    .btn-primary {
        color: #fff;
        background-color: #628a2f;
        border-color: #628a2f;
    }

    .btn-primary.focus,
    .btn-primary:focus {
        color: #fff;
        background-color: #628a2f;
        border-color: #628a2f;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Slider</h3>  
                        <a href="{{ route('slider.create') }}" class="text-white btn btn-primary btn-sm float-right">Add Slider</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Title</th>
                                    <th>Slider</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($sliderData))
                                <?php
                                $i = 1;
                                ?>
                                @foreach($sliderData as $list)
                                <tr>
                                    <td>{{$i}}</td>
                                    <th>{{ isset($list->title) ? $list->title :""}}</th>

                                    <td>
                                        @if(isset($list->image))
                                        <img src="{{ asset('public/image/'.$list->image)}}" alt="" style="width: 120px;">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('slider.edit',$list->id) }}" class="text-white btn btn-success"><i class="fa fa-edit"></i></a>
                                      
                                        <a href="{{ route('slider.delete',$list->id) }}" class="text-white btn btn-danger"><i class="fa fa-trash"></i></a>
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