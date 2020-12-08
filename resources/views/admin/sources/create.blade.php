@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.source.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sources.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="source">{{ trans('cruds.source.fields.source') }}</label>
                <input class="form-control {{ $errors->has('source') ? 'is-invalid' : '' }}" type="text" name="source" id="source" value="{{ old('source', '') }}">
                @if($errors->has('source'))
                    <div class="invalid-feedback">
                        {{ $errors->first('source') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.source.fields.source_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection