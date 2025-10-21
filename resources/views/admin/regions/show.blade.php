@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
    <p><i class="fi fi-br-eye mr_15_icc"></i>
        {{ trans('global.show') }} {{ trans('cruds.region.title') }}</p>
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-success min-w-200" href="{{ route('admin.regions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.region.fields.id') }}
                        </th>
                        <td>
                            {{ $region->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.region.fields.name') }}
                        </th>
                        <td>
                            {{ $region->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-success min-w-200" href="{{ route('admin.regions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection