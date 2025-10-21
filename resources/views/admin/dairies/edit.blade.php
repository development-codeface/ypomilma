@extends('layouts.admin')
@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i>
          Edit Dairy
        </p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.dairies.update', [$dairy->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            {{-- Name --}}
            <div class="form-group">
                <label class="required" for="name">Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       type="text" name="name" id="name" value="{{ old('name', $dairy->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
                
            </div>

            {{-- Location --}}
            <div class="form-group">
                <label for="location">Location</label>
                <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}"
                       type="text" name="location" id="location" value="{{ old('location', $dairy->location) }}">
                @if($errors->has('location'))
                    <div class="invalid-feedback">{{ $errors->first('location') }}</div>
                @endif
              </div>

            {{-- President Name --}}
            <div class="form-group">
                <label for="presidentname">President Name</label>
                <input class="form-control {{ $errors->has('presidentname') ? 'is-invalid' : '' }}"
                       type="text" name="presidentname" id="presidentname"
                       value="{{ old('presidentname', $dairy->presidentname) }}">
                @if($errors->has('presidentname'))
                    <div class="invalid-feedback">{{ $errors->first('presidentname') }}</div>
                @endif
             </div>

            {{-- Phone --}}
            <div class="form-group">
                <label for="phone">Contact Number</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                       type="text" name="phone" id="phone" value="{{ old('phone', $dairy->phone) }}">
                @if($errors->has('phone'))
                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                @endif
             </div>

            {{-- Submit --}}
            <div class="form-group">
                <button class="btn btn-success min-w-200" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
