@can('corrective_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.correctives.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.corrective.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.corrective.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-sourcesCorrectives">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.corrective.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.corrective.fields.finding_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.corrective.fields.sources') }}
                        </th>
                        <th>
                            {{ trans('cruds.corrective.fields.finding') }}
                        </th>
                        <th>
                            {{ trans('cruds.corrective.fields.action') }}
                        </th>
                        <th>
                            {{ trans('cruds.corrective.fields.tanggung_jawab') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.corrective.fields.target_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.corrective.fields.evident') }}
                        </th>
                        <th>
                            {{ trans('cruds.corrective.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($correctives as $key => $corrective)
                        <tr data-entry-id="{{ $corrective->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $corrective->id ?? '' }}
                            </td>
                            <td>
                                {{ $corrective->finding_date ?? '' }}
                            </td>
                            <td>
                                {{ $corrective->sources->source ?? '' }}
                            </td>
                            <td>
                                {{ $corrective->finding ?? '' }}
                            </td>
                            <td>
                                {{ $corrective->action ?? '' }}
                            </td>
                            <td>
                                {{ $corrective->tanggung_jawab->name ?? '' }}
                            </td>
                            <td>
                                {{ $corrective->tanggung_jawab->name ?? '' }}
                            </td>
                            <td>
                                {{ $corrective->target_date ?? '' }}
                            </td>
                            <td>
                                @if($corrective->evident)
                                    <a href="{{ $corrective->evident->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $corrective->evident->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $corrective->status->status ?? '' }}
                            </td>
                            <td>
                                @can('corrective_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.correctives.show', $corrective->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('corrective_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.correctives.edit', $corrective->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('corrective_delete')
                                    <form action="{{ route('admin.correctives.destroy', $corrective->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
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

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('corrective_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.correctives.massDestroy') }}",
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
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-sourcesCorrectives:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection