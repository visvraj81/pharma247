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
                        <h3 class="card-title">User</h3>
                        <a href="{{ route('superadmin.create') }}" class="text-white btn btn-primary btn-sm float-right">Add User</a>
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
                                    <td>
                                        <a href="{{ route('superadmin.edit',$list['id']) }}" class="text-white btn btn-success"><i class="fa fa-edit"></i></a>
                                        <button class="text-white btn btn-danger delete-agent" data-id="{{$list['id']}}">
                                            <i class="fa fa-trash"></i>
                                        </button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    // $(document).ready(function() {
    //     $('.example2').DataTable();
    // });
    $(document).ready(function() {
        $('.example2').DataTable();

        $('.delete-agent').on('click', function() {
            var agentId = $(this).data('id');
            var url = "{{ route('superadmin.delete', ':id') }}";
            url = url.replace(':id', agentId);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'The User has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.fire(
                                'Failed!',
                                'There was a problem deleting the user.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
@endsection