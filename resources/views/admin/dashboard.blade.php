@extends('layouts.admin')
@section('content')
<div class="content-body">
    <div class="row">
        <div class="col-xl-12">
            @if($role->title == 'SuperAdmin')
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Super Admin Dashboard</h4>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="form-inline">
                        <select name="financial_year" class="form-control mr-2" onchange="this.form.submit()">
                            @foreach($financialYears as $fy)
                                <option value="{{ $fy }}" {{ $selectedYear == $fy ? 'selected' : '' }}>
                                    {{ $fy }}
                                </option>
                            @endforeach
                        </select>
                        <select name="dairy_id" class="form-control" onchange="this.form.submit()">
                            <option value="">All Dairies</option>
                            @foreach($dairies as $dairy)
                                <option value="{{ $dairy->id }}" {{ $selectedDairyId == $dairy->id ? 'selected' : '' }}>
                                    {{ $dairy->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h5>Total Allocated Fund</h5>
                                <h2 class="text-success">₹ {{ number_format($totalAllocatedFund, 2) }}</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h5>Total Expenses</h5>
                                <h2 class="text-danger">₹ {{ number_format($totalExpenses, 2) }}</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h5>Remaining Balance</h5>
                                <h2 class="text-primary">₹ {{ number_format($totalAllocatedFund - $totalExpenses, 2) }}</h2>
                            </div>
                        </div>
                    </div>
                    @if($dairyData)
                        <hr>
                        <h5 class="mt-4">Details for {{ $dairies->firstWhere('id', $selectedDairyId)->name }}</h5>
                        <div class="row mt-3 text-center">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded shadow-sm">
                                <h6>Allocated Fund</h6>
                                <p class="fs-20 text-success">₹ {{ number_format($dairyData['fund_allocated'], 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded shadow-sm">
                                <h6>Expenses</h6>
                                <p class="fs-20 text-danger">₹ {{ number_format($dairyData['expenses'], 2) }}</p>
                                </div>
                            </div>
                             <div class="col-md-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h5>Remaining Balance</h5>
                                <h2 class="text-primary">₹ {{ number_format($dairyData['fund_allocated'] - $dairyData['expenses'], 2) }}</h2>
                            </div>
                        </div>
                        </div>
                    @endif
                    <hr>
                    <canvas id="fundExpenseChartxx" height="100"></canvas>
                </div>
            </div>
            @else
             <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Dairy Admin Dashboard</h4>
                    {{-- <form method="GET" action="{{ route('admin.dashboard') }}" class="form-inline">
                        <select name="financial_year" class="form-control mr-2" onchange="this.form.submit()">
                            @foreach($financialYears as $fy)
                                <option value="{{ $fy }}" {{ $selectedYear == $fy ? 'selected' : '' }}>
                                    {{ $fy }}
                                </option>
                            @endforeach
                        </select>
                        <select name="dairy_id" class="form-control" onchange="this.form.submit()">
                            <option value="">All Dairies</option>
                            @foreach($dairies as $dairy)
                                <option value="{{ $dairy->id }}" {{ $selectedDairyId == $dairy->id ? 'selected' : '' }}>
                                    {{ $dairy->name }}
                                </option>
                            @endforeach
                        </select>
                    </form> --}}
                </div>

                <div class="card-body">
                    {{-- <div class="row text-center">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h5>Total Allocated Fund</h5>
                                <h2 class="text-success">₹ {{ number_format($totalAllocatedFund, 2) }}</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h5>Total Expenses</h5>
                                <h2 class="text-danger">₹ {{ number_format($totalExpenses, 2) }}</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h5>Remaining Balance</h5>
                                <h2 class="text-primary">₹ {{ number_format($totalAllocatedFund - $totalExpenses, 2) }}</h2>
                            </div>
                        </div>
                    </div> --}}

                    @if($dairyData)
                        {{-- <hr> --}}
                        <h5 class="mt-4">Details for {{ $dairies->firstWhere('id', $selectedDairyId)->name }}</h5>
                        <div class="row mt-3 text-center">
                            <div class="col-md-4">
                                <h6>Allocated Fund</h6>
                                <p class="fs-20 text-success">₹ {{ number_format($dairyData['fund_allocated'], 2) }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6>Expenses</h6>
                                <p class="fs-20 text-danger">₹ {{ number_format($dairyData['expenses'], 2) }}</p>
                            </div>
                        </div>
                    @endif

                    <hr>
                    <canvas id="fundExpenseChartxx" height="100"></canvas>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('fundExpenseChart');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData->pluck('name')) !!},
            datasets: [
                {
                    label: 'Allocated Fund',
                    data: {!! json_encode($chartData->pluck('funds_sum_amount')) !!},
                    backgroundColor: 'rgba(33, 155, 99, 0.7)',
                },
                {
                    label: 'Expenses',
                    data: {!! json_encode($chartData->pluck('expenses_sum_amount')) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
