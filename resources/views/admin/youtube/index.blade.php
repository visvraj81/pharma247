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
                        <h3 class="card-title">Youtube</h3>
                        @can('youtue-create')
                        <a href="{{ route('youtue-create') }}" class="text-white btn btn-primary btn-sm float-right">Add Youtube Link</a>
                        @endcan
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>

                                    <th>Sr No</th>
                                    <th>Name</th>
                                    <th>Youtube Link</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($youtubeList))
                                <?php
                                $i = 1;
                                ?>
                                @foreach($youtubeList as $list)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$list->name}}</td>
                                    <td>
                                        @if(isset($list->youtube_link))
                                        {{$list->youtube_link}}
                                        @endif
                                    </td>
                                    <td>
                                        @can('youtue-edit')
                                        <a href="{{ route('youtue.edit',$list->id) }}" class="text-white btn btn-success"><i class="fa fa-edit"></i></a>
                                        @endcan
                                        @can('youtue-delete')
                                        <a href="{{ route('youtue.delete',$list->id) }}" class="text-white btn btn-danger"><i class="fa fa-trash"></i></a>
                                        @endcan
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