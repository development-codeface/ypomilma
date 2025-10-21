@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header"><p>
    <i class="fi fi-br-list mr_15_icc"></i>
        {{ trans('cruds.region.title_singular') }} {{ trans('global.list') }}
        </p>
        @can('region_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success lh20" href="{{ route("admin.regions.create") }}">
            <i class="fi fi-br-plus-small mr_5"></i>
                {{ trans('global.add') }} {{ trans('cruds.region.title_singular') }}
            </a>
        </div>
    </div>
@endcan
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Region">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                        <i class="fi fi-br-hastag ictabl "></i>
                            {{ trans('cruds.region.fields.id') }}
                        </th>
                        <th>
                        <i class="fi fi-br-users-alt ictabl"></i>
                            {{ trans('cruds.region.fields.name') }}
                        </th>
                        <th>
                        <i class="fi fi-br-apps-add ictabl"></i>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($regions as $key => $region)
                        <tr data-entry-id="{{ $region->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $region->id ?? '' }}
                            </td>
                            <td>
                                {{ $region->name ?? '' }}
                            </td>
                            <td>
                                @can('region_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.regions.show', $region->id) }}">
                                    <i class="fi fi-br-eye"></i>
                                        <!-- {{ trans('global.view') }} -->
                                    </a>
                                @endcan

                                @can('region_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.regions.edit', $region->id) }}">
                                        <!-- {{ trans('global.edit') }} -->
                                        <i class="fi fi-br-list"></i>
                                    </a>
                                @endcan

                                @can('region_delete')
                                    <form action="{{ route('admin.regions.destroy', $region->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                       
                                        <button input type="submit" class="btn btn-xs btn-danger" value="" ><i class="fi fi-br-trash"></i> </button>

                                          <!-- need to add below icon insted od delete text -->
                                        <!-- <i class="fa-regular fa-trash-can"></i> -->
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script src="{{ asset('css/vendor/global/global.min.js') }}"></script>
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('region_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.regions.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-Region:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection