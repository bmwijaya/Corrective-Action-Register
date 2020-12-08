@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.corrective.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.correctives.update", [$corrective->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="finding_date">{{ trans('cruds.corrective.fields.finding_date') }}</label>
                <input class="form-control date {{ $errors->has('finding_date') ? 'is-invalid' : '' }}" type="text" name="finding_date" id="finding_date" value="{{ old('finding_date', $corrective->finding_date) }}">
                @if($errors->has('finding_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('finding_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.corrective.fields.finding_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sources_id">{{ trans('cruds.corrective.fields.sources') }}</label>
                <select class="form-control select2 {{ $errors->has('sources') ? 'is-invalid' : '' }}" name="sources_id" id="sources_id">
                    @foreach($sources as $id => $sources)
                        <option value="{{ $id }}" {{ (old('sources_id') ? old('sources_id') : $corrective->sources->id ?? '') == $id ? 'selected' : '' }}>{{ $sources }}</option>
                    @endforeach
                </select>
                @if($errors->has('sources'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sources') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.corrective.fields.sources_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="finding">{{ trans('cruds.corrective.fields.finding') }}</label>
                <textarea class="form-control {{ $errors->has('finding') ? 'is-invalid' : '' }}" name="finding" id="finding">{{ old('finding', $corrective->finding) }}</textarea>
                @if($errors->has('finding'))
                    <div class="invalid-feedback">
                        {{ $errors->first('finding') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.corrective.fields.finding_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="action">{{ trans('cruds.corrective.fields.action') }}</label>
                <input class="form-control {{ $errors->has('action') ? 'is-invalid' : '' }}" type="text" name="action" id="action" value="{{ old('action', $corrective->action) }}">
                @if($errors->has('action'))
                    <div class="invalid-feedback">
                        {{ $errors->first('action') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.corrective.fields.action_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tanggung_jawab_id">{{ trans('cruds.corrective.fields.tanggung_jawab') }}</label>
                <select class="form-control select2 {{ $errors->has('tanggung_jawab') ? 'is-invalid' : '' }}" name="tanggung_jawab_id" id="tanggung_jawab_id">
                    @foreach($tanggung_jawabs as $id => $tanggung_jawab)
                        <option value="{{ $id }}" {{ (old('tanggung_jawab_id') ? old('tanggung_jawab_id') : $corrective->tanggung_jawab->id ?? '') == $id ? 'selected' : '' }}>{{ $tanggung_jawab }}</option>
                    @endforeach
                </select>
                @if($errors->has('tanggung_jawab'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tanggung_jawab') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.corrective.fields.tanggung_jawab_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="target_date">{{ trans('cruds.corrective.fields.target_date') }}</label>
                <input class="form-control date {{ $errors->has('target_date') ? 'is-invalid' : '' }}" type="text" name="target_date" id="target_date" value="{{ old('target_date', $corrective->target_date) }}">
                @if($errors->has('target_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('target_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.corrective.fields.target_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="evident">{{ trans('cruds.corrective.fields.evident') }}</label>
                <div class="needsclick dropzone {{ $errors->has('evident') ? 'is-invalid' : '' }}" id="evident-dropzone">
                </div>
                @if($errors->has('evident'))
                    <div class="invalid-feedback">
                        {{ $errors->first('evident') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.corrective.fields.evident_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="status_id">{{ trans('cruds.corrective.fields.status') }}</label>
                <select class="form-control select2 {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status_id" id="status_id">
                    @foreach($statuses as $id => $status)
                        <option value="{{ $id }}" {{ (old('status_id') ? old('status_id') : $corrective->status->id ?? '') == $id ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.corrective.fields.status_helper') }}</span>
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

@section('scripts')
<script>
    Dropzone.options.evidentDropzone = {
    url: '{{ route('admin.correctives.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="evident"]').remove()
      $('form').append('<input type="hidden" name="evident" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="evident"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($corrective) && $corrective->evident)
      var file = {!! json_encode($corrective->evident) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="evident" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
@endsection