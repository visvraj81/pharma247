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
                        <h3 class="card-title">Agent</h3>
                        @can('agent-create')
                        <a href="{{ route('agent.create') }}" class="text-white btn btn-primary float-right">Add Agent</a>
                        @endcan
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($detailsList))
                                @foreach($detailsList as $index => $list)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $list['name'] }}</td>
                                    <td>{{ $list['email'] }}</td>
                                    <td><img src="{{ $list['image'] }}" height="50" width="50" /></td>
                                    <td>
                                        @can('agent-edit')
                                        <a href="{{ route('agent.edit', $list['id']) }}" class="text-white btn btn-success">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('agent-delete')
                                        <button class="text-white btn btn-danger delete-agent" data-id="{{ $list['id'] }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        @endcan
                                    </td>
                                </tr>
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
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    $(document).ready(function() {
        $('.example2').DataTable();

        $(document).on('click', '.delete-agent', function() {
            var agentId = $(this).data('id');
            var url = "{{ route('agent.delete', ':id') }}";
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
                                'The agent has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.fire(
                                'Failed!',
                                'There was a problem deleting the agent.',
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