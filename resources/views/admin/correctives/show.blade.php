@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.corrective.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.correctives.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.corrective.fields.id') }}
                        </th>
                        <td>
                            {{ $corrective->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.corrective.fields.finding_date') }}
                        </th>
                        <td>
                            {{ $corrective->finding_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.corrective.fields.sources') }}
                        </th>
                        <td>
                            {{ $corrective->sources->source ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.corrective.fields.finding') }}
                        </th>
                        <td>
                            {{ $corrective->finding }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.corrective.fields.action') }}
                        </th>
                        <td>
                            {{ $corrective->action }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.corrective.fields.tanggung_jawab') }}
                        </th>
                        <td>
                            {{ $corrective->tanggung_jawab->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.corrective.fields.target_date') }}
                        </th>
                        <td>
                            {{ $corrective->target_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.corrective.fields.evident') }}
                        </th>
                        <td>
                            @if($corrective->evident)
                                <a href="{{ $corrective->evident->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $corrective->evident->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.corrective.fields.status') }}
                        </th>
                        <td>
                            {{ $corrective->status->status ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.correctives.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection