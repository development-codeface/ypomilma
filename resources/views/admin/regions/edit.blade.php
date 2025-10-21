@extends('layouts.admin')
@section('content')

<div class="card  w_80">
    <div class="card-header"><p>
    <i class="fi fi-br-edit mr_15_icc"></i>
        {{ trans('global.edit') }} {{ trans('cruds.region.title_singular') }} </p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.regions.update", [$region->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
            <div class="form-group col-lg-9 col-sm-12">
                <label class="required" for="name">{{ trans('cruds.region.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $region->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.region.fields.name_helper') }}</span>
            </div>
            <div class="form-group col-lg-3 col-sm-12">
                <button class="btn btn-success mar_top30 min-w-200" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
            </div>
        </form>
    </div>
</div>



@endsection