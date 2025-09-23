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
                        <h3 class="card-title">Faq</h3>
                        <a href="{{ route('faq-create') }}" class="text-white btn btn-primary btn-sm float-right">Add Faq</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Question</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($faqData))
                                <?php
                                $i = 1;
                                ?>
                                @foreach($faqData as $list)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        {{ isset($list->question) ? $list->question : "" }}
                                    </td>
                                    <td>
                                        <a href="{{ route('faq.edit',$list->id) }}" class="text-white btn btn-success"><i class="fa fa-edit"></i></a>
                                        <a href="{{ route('faq.delete',$list->id) }}" class="text-white btn btn-danger"><i class="fa fa-trash"></i></a>
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
