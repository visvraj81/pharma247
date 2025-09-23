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

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 25px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 25px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 17px;
        width: 17px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #628a2f;
    }

    input:checked + .slider:before {
        transform: translateX(24px);
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Item Name</th>
                                    <th>Recommended Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($iteamDetails))
                                <?php $i = 1; ?>
                                @foreach($iteamDetails as $list)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ isset($list->iteam_name) ? $list->iteam_name : "" }}</td>
                                    <td>
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="toggle-recommend" data-id="{{ $list->id }}" {{ $list->status ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('.example2').DataTable();

        $('.toggle-recommend').on('change', function() {
            let itemId = $(this).data('id');
            let recommended = $(this).is(':checked') ? 1 : 0;
            
            $.ajax({
                url: '{{ route("toggle.recommend") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: itemId,
                    recommended: recommended
                },
                success: function(response) {
                   
                },
                error: function(xhr) {
                    
                }
            });
        });
    });
</script>
@endsection
