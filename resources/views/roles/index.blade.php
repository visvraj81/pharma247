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
                        <h3 class="card-title">Role</h3>
                        @can('role-create')
                        <a href="{{ route('roles.create') }}" class="text-white btn btn-primary btn-sm float-right">Add Role</a>
                        @endcan
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($bannerData))
                                @foreach($bannerData as $index => $list)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if(isset($list->banner))
                                        <img src="{{ asset('/public/image/'.$list->banner)}}" alt="" style="width: 120px;">
                                        @else
                                        <img src="{{ asset('/public//image/placeholder.png') }}" alt="No Image" style="width: 120px;">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('banner.edit',$list->id) }}" class="text-white btn btn-success"><i class="fa fa-edit"></i></a>
                                        <a href="{{ route('banner.delete',$list->id) }}" class="text-white btn btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                                @foreach ($roles as $key => $role)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        @can('role-edit')
                                        <a href="{{ route('roles.edit',$role->id) }}" class="text-white btn btn-success"><i class="fa fa-edit"></i></a>
                                        @endcan
                                        @can('role-delete')
                                        <button class="text-white btn btn-danger delete-agent" data-id="{{ $role->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
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
    $(document).ready(function() {
        $('.example2').DataTable();

        $('.delete-agent').on('click', function() {
            var agentId = $(this).data('id');
            var url = "{{ route('roles.delete', ':id') }}";
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
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'The Role has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.fire(
                                'Failed!',
                                'There was a problem deleting the role.',
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