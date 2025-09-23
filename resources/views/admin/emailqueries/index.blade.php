@extends('layouts.main')
@section('main')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Offline Request</h3>
                    </div>
                    <!-- /.card-header -->
                    <ul class="nav nav-tabs txt f-family mt-4" id="myTab" role="tablist">
                        <a class="nav-link active" href="{{ route('emailqueries.index') }}">
                            <li class="nav-item">
                                Not Replied
                            </li>
                        </a>
                        <a class="nav-link" href="{{ route('emailqueriesreplied') }}">
                            <li class="nav-item">
                                Replied
                            </li>
                        </a>
                    </ul>
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Date Time</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                    <th>Replied</th>
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
                                        {{$list['date_time']}}
                                    </td>
                                    <td>
                                        {{$list['name']}}
                                    </td>
                                    <td>
                                        {{$list['email']}}
                                    </td>
                                    <td>
                                        {{$list['message']}}
                                    </td>
                                    <td>
                                        {{$list['replied']}}
                                    </td>
                                    @if($list['replied'] == 'No')
                                    <td>
                                        <a href="{{ route('send.email',$list['id']) }}" class="btn btn-info"><i class="fa fa-share"></i></a>
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
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