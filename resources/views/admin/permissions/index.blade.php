@extends('layouts.main')
@section('main')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Permissions</h3>
                        @can('permissions-create')
                        <a href="{{ route('permissions.create') }}" class="text-white btn btn-primary btn-sm float-right">Add Permissions</a>
                        @endcan
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Permissions</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($persmmionData))
                                <?php
                                $i = 1;
                                ?>
                                @foreach($persmmionData as $list)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        {{ $list->permissions}}                                        
                                    </td>
                                    <td>
                                        @can('permissions-edit')
                                        <a href="{{ route('permissions.edit',$list->id) }}" class="text-white btn btn-success"><i class="fa fa-edit"></i></a>
                                        @endcan
                                        @can('permissions-delete')
                                        <button class="text-white btn btn-danger delete-agent" data-id="{{$list->id}}">
                                            <i class="fa fa-trash"></i>
                                        </button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
     $(document).ready(function () {
        $('.example2').DataTable();

        $('body').on('click', '.delete-agent', function () {
            var agentId = $(this).data('id');
            var url = "{{ route('permissions.delete', ':id') }}";
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
                        success: function (response) {
                            Swal.fire(
                                'Deleted!',
                                'The Permission has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function (response) {
                            Swal.fire(
                                'Failed!',
                                'There was a problem deleting the permission.',
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
