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
                        <h3 class="card-title">Lead</h3>

                        @can('lead-create')
                        <div style="float: right;">
                            <a href="{{route('add.lead')}}">
                                <button type="button" class="btn btn-primary">Add Lead</button>
                            </a>
                        </div>
                        @endcan
                    </div>

                    <!-- /.card-header -->
                    <ul class="nav nav-tabs txt f-family mt-4" id="myTab" role="tablist">
                        <a class="nav-link" href="{{ route('offlinerequest.index') }}">
                            <li class="nav-item">
                                In Progress
                            </li>
                        </a>
                        <a class="nav-link" href="{{ route('offlinerequestapprove') }}">
                            <li class="nav-item">
                                Complete
                            </li>
                        </a>
                        <a class="nav-link" href="{{ route('offlinerequestreject') }}">
                            <li class="nav-item">
                                Rejected
                            </li>
                        </a>

                    </ul>
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Pharma Name</th>
                                     <!--<th>Subscription Plan</th>-->
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($offlineRequest))
                                <?php
                                $i = 1;
                                ?>
                                @foreach($offlineRequest as $list)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        {{ isset($listData->getPharma->pharma_name) ? $listData->getPharma->pharma_name  : $list->name }}
                                    </td>
                                   
                                    <!--<td>
                                        {{isset($list->getPlan->name) ?$list->getPlan->name :""}}
                                    </td>-->
                                    <td>
                                        {{ isset($list->email) ? $list->email : ""}}
                                    </td>
                                    <td>
                                        {{ isset($list->phone) ? $list->phone : ""}}
                                    </td>
                                    <td>
                                        {{ isset($list->payment_method) ? $list->payment_method : ""}}
                                    </td>
                                    <td>
                                        @if($list['status'] == '0')
                                        <button id="statusData" class="btn btn-primary" data-id="{{$list['id']}}">In Progress</button>
                                        @elseif($list['status'] == '1')
                                        <button id="statusData" class="btn btn-primary" data-id="{{$list['id']}}">Complete</button>
                                        @else
                                        <button id="statusData" class="btn btn-primary" data-id="{{$list['id']}}">Rejected</button>
                                        @endif
                                    </td>
                                    <td>
                                        {{ isset($list->reason) ? $list->reason : $list->message}}
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
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Status Change</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <form action="{{route('offline.request.status')}}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <input type="hidden" name="id" value="" id="modelId" />
                                            <div class="col-md-12">
                                                <label for="">Status</label>
                                                <select name="status" class="form-control" id="statusValue">
                                                    <option value="0">In Progress</option>
                                                    <option value="1">Complete</option>
                                                    <option value="2">Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row" id="hideRemark">
                                            <div class="col-md-12">
                                                <label>Reason</label>
                                                <input type="text" name="reason" class="form-control" placeholder="Reason" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
<script>
    $(document).on('click', "#statusData", function() {
        var data = $(this).data('id');
        $("#modelId").val(data);
        $('#myModal').modal('show');
    });

    $(document).on("change", "#statusValue", function() {
        var dropdownValeue = $(this).val();
        if (dropdownValeue == '2') {
            $("#hideRemark").show();
        } else if (dropdownValeue == '0') {
            $("#hideRemark").hide();
        } else if (dropdownValeue == '1') {
            $("#hideRemark").hide();
        }

    });
    $(document).ready(function() {
        $("#hideRemark").hide();
    });
</script>
@endsection