@extends('layouts.admin')
@section('content')
    <div class="card w_120">
        <div class="card-header">
            <p><i class="fi fi-br-plus-small mr_15_icc"></i> Create Agency</p>
        </div>
        {{-- {{ route('admin.asset-management.invoice.store') }} --}}
        <div class="card-body col-md-12">
            <form method="POST" action="{{ route('admin.aggency.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- Dairy --}}
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.agency.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                            name="name" id="name" value="{{ old('name', '') }}">
                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.agency.fields.email') }}</label>
                        <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email"
                            value="{{ old('email', '') }}">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="required" for="address">{{ trans('cruds.agency.fields.address') }}</label>
                        <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address" rows="3">{{ old('address', '') }}</textarea>
                        @if ($errors->has('address'))
                            <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="required" for="contact_no">{{ trans('cruds.agency.fields.contact_no') }}</label>
                        <input class="form-control {{ $errors->has('contact_no') ? 'is-invalid' : '' }}" type="text" name="contact_no" id="contact_no"
                            value="{{ old('contact_no', '') }}">
                             @if ($errors->has('contact_no'))
                            <div class="invalid-feedback">{{ $errors->first('contact_no') }}</div>
                        @endif
                    </div>
                    <div class="col-md-12 mt-4 text-end">
                        <button class="btn btn-success min-w-200" type="submit" id="">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
