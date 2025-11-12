@extends('layouts.admin')

@section('content')

<div class="card w_90">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fi fi-br-list mr_15_icc"></i> Head Office Fund</h5>
        <a href="{{ route('admin.headoffices.create') }}" class="btn btn-success">+ Add Fund</a>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Financial Year</th>
                    <th>Amount</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($headOffices as $office)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $office->financial_year }}</td>
                        <td>{{ number_format($office->amount, 2) }}</td>
                        <td>{{ $office->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('admin.headoffices.edit', $office->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('admin.headoffices.destroy', $office->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No head office records found.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $headOffices->links() }}
        </div>
    </div>
</div>

@endsection
