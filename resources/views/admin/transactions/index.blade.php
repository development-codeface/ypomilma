@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <p><i class="fi fi-br-list mr_15_icc"></i> Transaction History</p>
    </div>

    <div class="card-body">
   

    <form method="GET" action="{{ route('admin.transactions.index') }}" class="mb-4">
        <div class="row g-3">
           <div class="col-md-3">
                <label>Dairy</label>
                <select name="dairy_id" class="form-control">
                    <option value="">All Dairies</option>
                    @foreach ($dairies as $dairy)
                        <option value="{{ $dairy->id }}" {{ request('dairy_id') == $dairy->id ? 'selected' : '' }}>
                            {{ $dairy->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label>Type</label>
                <select name="type" class="form-control">
                    <option value="">All</option>
                    <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                    <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                    <option value="hold" {{ request('type') == 'hold' ? 'selected' : '' }}>Hold</option>
                    <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refund</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">All</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>

            <div class="col-md-2">
                <label>End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>

            <!-- <div class="col-md-2">
                <label>Reference No</label>
                <input type="text" name="reference_no" value="{{ request('reference_no') }}" class="form-control">
            </div> -->

            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('admin.transactions.export', request()->query()) }}" class="btn btn-success">Download</a>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Dairy ID</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Reference No</th>
                <th>Transaction Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $txn)
                <tr>
                    <td>{{ $txn->id }}</td>
                    <td>{{ $txn->dairy->name }}</td>
                    <td>{{ ucfirst($txn->type) }}</td>
                    <td>{{ number_format($txn->amount, 2) }}</td>
                    <td>{{ ucfirst($txn->status) }}</td>
                    <td>{{ $txn->reference_no ?? '-' }}</td>
                    <td>{{ $txn->transaction_date }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No transactions found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $transactions->links() }}
</div>
</div>
@endsection
