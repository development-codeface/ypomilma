@extends('layouts.admin')
@section('content')
    <style>
        td .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>

      <div class="card">
        <div class="card-header">
            <p> <i class="fi fi-br-list mr_15_icc"></i> Dairy List    </p>
        
                @can('dairy_create')
                    <div style="margin-bottom: 10px;" class="row">
                        <div class="col-lg-12">
                            <a class="btn btn-success lh20" href="{{ route('admin.dairies.create') }}">
                                <i class="fi fi-br-plus-small mr_5"></i> Add Dairy
                            </a>
                        </div>
                    </div>
                @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-Dairy">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>
                                <i class="fi fi-br-hastag ictabl"></i>
                                ID
                            </th>
                            <th>
                                <i class="fi fi-br-edit ictabl"></i>
                               Name
                            </th>
                            <th>
                                <i class="fi fi-br-map-marker ictabl"></i>
                               Location
                            </th>
                            <th>
                                <i class="fi fi-br-user ictabl"></i>
                               President Name
                            </th>
                            <th>
                                <i class="fi fi-br-phone ictabl"></i>
                               Contact Number
                            </th>
                            <th>
                                <i class="fi fi-br-apps-add ictabl"></i>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dairies as $key => $dairy)
                            <tr data-entry-id="{{ $dairy->id }}">
                                <td></td>
                                <td>{{ $dairy->id ?? '' }}</td>
                                <td>{{ $dairy->name ?? '' }}</td>
                                <td>{{ $dairy->location ?? '' }}</td>
                                <td>{{ $dairy->presidentname ?? '' }}</td>
                                <td>{{ $dairy->phone ?? '' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <!-- @can('dairy_show')
                                            <a class="btn btn-xs btn-primary"
                                               href="{{ route('admin.dairies.show', $dairy->id) }}">
                                                <i class="fi fi-br-eye"></i>
                                            </a>
                                        @endcan -->

                                        @can('dairy_edit')
                                            <a class="btn btn-xs btn-info"
                                               href="{{ route('admin.dairies.edit', $dairy->id) }}">
                                                <i class="fi fi-br-list"></i>
                                            </a>
                                        @endcan

                                        @can('dairy_delete')
                                            <form action="{{ route('admin.dairies.destroy', $dairy->id) }}" method="POST"
                                                  onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                  style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger">
                                                    <i class="fi fi-br-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

