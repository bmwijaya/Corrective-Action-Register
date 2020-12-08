@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.source.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sources.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.source.fields.id') }}
                        </th>
                        <td>
                            {{ $source->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.source.fields.source') }}
                        </th>
                        <td>
                            {{ $source->source }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sources.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#sources_correctives" role="tab" data-toggle="tab">
                {{ trans('cruds.corrective.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="sources_correctives">
            @includeIf('admin.sources.relationships.sourcesCorrectives', ['correctives' => $source->sourcesCorrectives])
        </div>
    </div>
</div>

@endsection