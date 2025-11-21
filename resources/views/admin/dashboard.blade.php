@extends('layouts.admin')
@section('content')
<div class="content-body">
    <div class="row">
        <div class="col-xl-12">
            {{-- ================= SUPER ADMIN DASHBOARD ================= --}}
            @if($role->title == 'SuperAdmin')
            <div class="card mb-4">
        
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Dairy Units Overview</h4>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="form-inline">
                        <select name="financial_year" class="form-control mr-2" onchange="this.form.submit()">
                            @foreach($financialYears as $fy)
                                <option value="{{ $fy }}" {{ $selectedYear == $fy ? 'selected' : '' }}>
                                    {{ $fy }}
                                </option>
                            @endforeach
                        </select>
                        <!-- <select name="dairy_id" class="form-control" onchange="this.form.submit()">
                            <option value="">All Dairies</option>
                            @foreach($dairies as $dairy)
                                <option value="{{ $dairy->id }}" {{ $selectedDairyId == $dairy->id ? 'selected' : '' }}>
                                    {{ $dairy->name }}
                                </option>
                            @endforeach
                        </select> -->
                    </form>
                </div>

                <div class="card-body">
                {{-- Summary Cards --}}
                <div class="row text-center mb-4">

                    <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <div>
                            <h6>Total Head Office Budget</h6>
                            <h2 class="text-primary">₹ {{ number_format($totalHOBudget, 2) }}</h2>
                        </div>
                        <div>
                            <i class="fa-solid fa-building-columns text-primary"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                <div class="p-3 bg-light rounded shadow-sm">
                    <div>
                        <h6>Remaining HO Balance</h6>
                        <h2 class="text-success">₹ {{ number_format($remainingHOBalance, 2) }}</h2>
                    </div>
                    <div>
                        <i class="fa-solid fa-vault text-success"></i>
                    </div>
                </div>
            </div>


        
        <!-- Total Allocated Funds -->
        <div class="col-md-3">
            <div class="p-3 bg-light rounded shadow-sm">
                <div>
                    <h6>Total Allocated Funds</h6>
                    <h2 class="text-success">₹ {{ number_format($totalAllocatedFund, 2) }}</h2>
                </div>
                <div>
                    <i class="fa-solid fa-wallet text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Total Expenses -->
        <!-- <div class="col-md-3">
            <div class="p-3 bg-light rounded shadow-sm">
                <div>
                    <h6>Total Expenses</h6>
                    <h2 class="text-danger">₹ {{ number_format($totalExpenses, 2) }}</h2>
                </div>
                <div>
                    <i class="fa-solid fa-money-bill-wave text-danger"></i>
                </div>
            </div>
        </div> -->

        <!-- Remaining Balance -->
        <!-- <div class="col-md-3">
            <div class="p-3 bg-light rounded shadow-sm">
                <div>
                    <h6>Remaining Balance</h6>
                    <h2 class="text-primary">₹ {{ number_format($totalAllocatedFund - $totalExpenses, 2) }}</h2>
                </div>
                <div>
                    <i class="fa-solid fa-money-bill-wave text-danger"></i>
                </div>
            </div>
        </div> -->

        <!-- Active Dairy Units -->
        <div class="col-md-3">
            <div class="p-3 bg-light rounded shadow-sm">
                <div>
                    <h6>Active Dairy Units</h6>
                    <h2 class="text-primary">4</h2>
                </div>
                <div>
                    <i class="fa-solid fa-building text-purple"></i>
                </div>
            </div>
        </div>

    </div>
</div>

                    {{-- Dairy Units Financial Summary --}}
                 

                   @if($dairySummaries && count($dairySummaries) > 0)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5><i class="fa-solid fa-table-list mr-2"></i>Dairy Units Financial Summary</h5>
                        <a href="{{ route('admin.expenses.summary') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-eye mr-1"></i> View Expenses Summary
                        </a>
                        <!-- <button class="btn btn-primary btn-sm"><i class="fa-solid fa-file-export mr-1"></i>Export Report</button> -->
                    </div>

            <table class="table table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th>Dairy Unit</th>
                        <th>Allocated Funds</th>
                        <th>Expenses</th>
                        <th>Balance</th>
                        <th>Utilization</th>
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($dairySummaries as $dairy)
                    @php
                        $allocated = $dairy['allocated'] ?? 0;
                        $expenses = $dairy['expenses'] ?? 0;
                        $balance = $dairy['balance'] ?? 0;
                        $utilization = $allocated > 0 ? round(($expenses / $allocated) * 100, 0) : 0;
                        $status = $utilization >= 85 ? 'High Usage' : 'Active';
                        $statusClass = $utilization >= 85 ? 'bg-warning text-dark' : 'bg-success';
                    @endphp
                    <tr>
                        <td><i class="fa-solid fa-building text-primary mr-2"></i>{{ $dairy['name'] ?? 'N/A' }}</td>
                        <td>₹ {{ number_format($allocated, 2) }}</td>
                        <td>₹ {{ number_format($expenses, 2) }}</td>
                        <td class="text-success">₹ {{ number_format($balance, 2) }}</td>
                        <td>{{ $utilization }}%</td>
                        <td><span class="badge {{ $statusClass }}">{{ $status }}</span></td>
                    
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="alert alert-info mt-3">No dairy summaries available.</div>
            @endif

                </div>
            </div>

            {{-- ================= DAIRY ADMIN DASHBOARD ================= --}}
            @else
           <div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fa-solid fa-industry mr-2"></i>Dairy Dashboard Overview
        </h3>
        <p class="text-muted">Overview of your allocated funds, expenses, and remaining balance</p>
    </div>

    <div class="card-body">
        <div class="row text-center mb-4">
            
            <!-- Total Allocated Funds -->
            <div class="col-md-4">
                <div class="p-3 bg-light rounded shadow-sm">
                    <div>
                        <h6>Total Allocated Funds</h6>
                        <h2 class="text-success">
                            ₹ {{ number_format($dairyData['fund_allocated'] ?? 2_000_000, 2) }}
                        </h2>
                    </div>
                    <div>
                        <i class="fa-solid fa-sack-dollar text-success"></i>
                    </div>
                </div>
            </div>

            <!-- Total Expenses -->
            <div class="col-md-4">
                <div class="p-3 bg-light rounded shadow-sm">
                    <div>
                        <h6>Total Expenses</h6>
                        <h2 class="text-danger">
                            ₹ {{ number_format($dairyData['expenses'] ?? 1_200_000, 2) }}
                        </h2>
                    </div>
                    <div>
                        <i class="fa-solid fa-file-invoice-dollar text-danger"></i>
                    </div>
                </div>
            </div>

            <!-- Remaining Balance -->
            <div class="col-md-4">
                <div class="p-3 bg-light rounded shadow-sm">
                    <div>
                        <h6>Remaining Balance</h6>
                        <h2 class="text-primary">
                            ₹ {{ number_format(($dairyData['fund_allocated'] ?? 2_000_000) - ($dairyData['expenses'] ?? 1_200_000), 2) }}
                        </h2>
                    </div>
                    <div>
                        <i class="fa-solid fa-wallet text-primary"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

            @endif
        </div>
    </div>
</div>

{{-- Font Awesome --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
