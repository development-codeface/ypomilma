@extends('layouts.admin')
@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i>
            Create Dairy
        </p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.dairies.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Name --}}
            <div class="form-group">
                <label class="required" for="name">Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       type="text" name="name" id="name" value="{{ old('name') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
               
            </div>

            {{-- Location --}}
            <div class="form-group">
                <label for="location">Location</label>
                <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}"
                       type="text" name="location" id="location" value="{{ old('location') }}">
                @if($errors->has('location'))
                    <div class="invalid-feedback">{{ $errors->first('location') }}</div>
                @endif
             </div>

            <div class="form-group">
            <label for="admin_userid">Admin Name</label>
            <select class="form-control {{ $errors->has('admin_userid') ? 'is-invalid' : '' }}"
                    name="admin_userid" id="admin_userid">
                <option value="">-- Select Admin --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('admin_userid') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @if($errors->has('admin_userid'))
                <div class="invalid-feedback">{{ $errors->first('admin_userid') }}</div>
            @endif
        </div>

            {{-- Phone --}}
            <div class="form-group">
                <label for="phone">Contact Number</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                       type="text" name="phone" id="phone" value="{{ old('phone') }}">
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
