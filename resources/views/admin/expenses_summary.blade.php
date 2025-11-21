@extends('layouts.admin')

@section('content')
<div class="content-body">
    <div class="row">
        <div class="col-xl-12">

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa-solid fa-file-invoice-dollar mr-2"></i>Expenses Summary by Category
                    </h3>
                <form method="GET" class="mb-3">

                    <div class="form-inline">
                        <label class="mr-2"><strong>Financial Year:</strong></label>

                        <select name="financial_year" class="form-control mr-2" onchange="this.form.submit()">
                            @foreach($financialYears as $fy)
                                <option value="{{ $fy }}" {{ $fy == $current_fy ? 'selected' : '' }}>
                                    {{ $fy }}
                                </option>
                            @endforeach
                        </select>

                        <noscript><button type="submit" class="btn btn-primary">Apply</button></noscript>
                    </div>

                </form>

            </div>

                <div class="card-body">

                    <table class="table table-bordered text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Expense Category</th>

                                @foreach($dairies as $d)
                                    <th>{{ $d->name }}</th>
                                @endforeach

                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($categoryData as $cat)
                            <tr>
                                <td class="text-left">
                                    <strong>{{ $cat['category'] }}</strong>
                                </td>

                                @foreach($dairies as $d)
                                    <td>
                                        ₹ {{ number_format($cat['dairies'][$d->name], 2) }}
                                    </td>
                                @endforeach

                                <td class="text-danger">
                                    <strong>₹ {{ number_format($cat['total'], 2) }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
