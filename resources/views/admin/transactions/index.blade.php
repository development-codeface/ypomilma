@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <p><i class="fi fi-br-bank mr_15_icc"></i> Transaction Statement</p>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="mb-4">
            <div class="row g-3">
                @php
                    $user = auth()->user();
                    $roleName = strtolower($user->role_name);
                @endphp

                @if ($roleName === 'superadmin')
                    <div class="col-md-3">
                        <label>Dairy <span class="text-danger">*</span></label>
                        <select name="dairy_id" class="form-control" required>
                            <option value="">All Dairies</option>
                            @foreach ($dairies as $dairy)
                                <option value="{{ $dairy->id }}" {{ request('dairy_id') == $dairy->id ? 'selected' : '' }}>
                                    {{ $dairy->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- <div class="col-md-2">
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
                </div> -->

                <div class="col-md-2">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>

                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('admin.transactions.export', request()->query()) }}" class="btn btn-success">Download</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Dairy</th>
                    <th> Reference</th>
                    <th>Description </th>
                    <th class="text-end">Debit (₹)</th>
                    <th class="text-end">Credit (₹)</th>
                    <th class="text-end">Balance (₹)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $txn)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($txn->transaction_date)->format('d M Y') }}</td>
                        <td>{{ $txn->dairy->name ?? '-' }}</td>
                        <td>{{ $txn->reference_no ?? '-' }}</td>
                        <td>{{ $txn->description ?? '-' }}</td>
                        <td class="text-end">
                            {{ in_array($txn->type, ['debit', 'refund', 'hold']) ? number_format($txn->amount, 2) : '' }}
                        </td>
                        <td class="text-end">
                            {{ $txn->type === 'credit' ? number_format($txn->amount, 2) : '' }}
                        </td>
                        <td class="text-end fw-bold">{{ number_format($txn->running_balance, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No transactions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $transactions->links() }}
    </div>
</div>
@endsection
