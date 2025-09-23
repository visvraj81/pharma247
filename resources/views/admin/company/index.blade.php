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
                        <h3 class="card-title" style="margin-top: 10px;">Pharma Shop</h3>
                        @can('pharma-list')
                        <a href="{{ route('pharma.create') }}" class="text-white btn btn-primary float-right">Add Pharma Shop</a>
                        @endcan
                    </div>

                    <!-- Filter Inputs -->
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search by Name or Email">
                            </div>
                            <div class="col-md-4">
                                <select id="planFilter" class="form-control">
                                    <option value="">Filter by Plan</option>
                                    <option value="active">Active Plans</option>
                                    <option value="expired">Expired Plans</option>
                                    <option value="Plan Not Available">Plan Not Available</option>
                                </select>
                            </div>
                        </div>

                        <table id="example2" class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Light Logo</th>
                                    <th>Owner Name</th>
                                    <th>Pharma Email</th>
                                    <th>Pharma Mobile Number</th>
                                    <th>Register Date</th>
                                    <th>Subscription Plan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($detailsList))
                                <?php $i = 1; ?>
                                @foreach($detailsList as $list)
                                <?php
                                    $userDetails = App\Models\User::where('id', $list['id'])->first();
                                ?>
                                <tr class="pharma-row" style="line-height: 70px;">
                                    <td>{{ $i }}</td>
                                    <td>
                                      @if(isset($list['light_logo']) && !empty($list['light_logo']))
                                      <img src="{{ $list['light_logo'] }}" style="object-fit: contain; width: 100px; height: 70px;">
                                      @else
                                      <img src="https://testadmin.pharma247.in/public/pharmalogo.png" style="object-fit: contain; width: 100px; height: 70px;">
                                      @endif
                                  	</td>
                                    <td class="pharma-name">{{ $list['pharma_name'] }}</td>
                                    <td class="pharma-email">{{ $list['pharma_email'] }}</td>
                                    <td >{{ isset($userDetails->phone_number) ? $userDetails->phone_number : ""}}</td>
                                    <td>{{ $list['register_date'] }}</td>
                                    <td class="plan-status">
                                      @php
                                          $pharmaData = App\Models\Transcations::where('pharma_name', $list['id'])->first();
                                      @endphp

                                      @if(isset($pharmaData) && $pharmaData->plan_name)
                                          @php
                                              $detailsListPlan = \App\Models\SubscriptionPlan::find($pharmaData->plan_name);
                                              $isExpired = isset($pharmaData->next_payment_date) && \Carbon\Carbon::parse($pharmaData->next_payment_date)->isPast();
                                          @endphp

                                          @if(isset($detailsListPlan))
                                              <button class="btn {{ $isExpired ? 'btn-danger' : 'btn-success' }}">
                                                  Plan: {{ $detailsListPlan->name }}
                                              </button>

                                              @if(isset($pharmaData->next_payment_date))
                                                  <button class="btn {{ $isExpired ? 'btn-danger' : 'btn-success' }}">
                                                      Plan Expiry Date: {{ \Carbon\Carbon::parse($pharmaData->next_payment_date)->format('Y-m-d') }}
                                                  </button>
                                              @else
                                                  <button class="btn btn-warning">Plan Expiry Date Not Available</button>
                                              @endif
                                          @else
                                              <button class="btn btn-danger">Plan Not Available</button>
                                          @endif

                                          @if($isExpired)
                                              <span class="badge badge-danger plan-type">expired</span>
                                          @else
                                              <span class="badge badge-success plan-type">active</span>
                                          @endif

                                      @else
                                          <span class="badge badge-danger plan-type">Plan Not Available</span>
                                      @endif
                                    </td>
                                    <td>
                                        @can('pharma-edit')
                                        <a href="{{ route('pharma.edit', $list['id']) }}" class="text-white btn btn-success"><i class="fa fa-edit"></i></a>
                                        @endcan
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
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    $(document).ready(function () {
        $('.example2').DataTable();

        // Search by Name or Email
        $("#searchInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $(".pharma-row").filter(function () {
                $(this).toggle(
                    $(this).find(".pharma-name").text().toLowerCase().indexOf(value) > -1 ||
                    $(this).find(".pharma-email").text().toLowerCase().indexOf(value) > -1
                );
            });
        });

        // Filter by Subscription Plan Status
        $("#planFilter").on("change", function () {
            var value = $(this).val();
            $(".pharma-row").filter(function () {
                if (value === "") {
                    $(this).show();
                } else {
                    $(this).toggle($(this).find(".plan-type").text() === value);
                }
            });
        });
    });
</script>
@endsection
