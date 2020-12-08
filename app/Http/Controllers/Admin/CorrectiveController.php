<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCorrectiveRequest;
use App\Http\Requests\StoreCorrectiveRequest;
use App\Http\Requests\UpdateCorrectiveRequest;
use App\Models\Corrective;
use App\Models\Source;
use App\Models\Status;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CorrectiveController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('corrective_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Corrective::with(['sources', 'tanggung_jawab', 'status'])->select(sprintf('%s.*', (new Corrective)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'corrective_show';
                $editGate      = 'corrective_edit';
                $deleteGate    = 'corrective_delete';
                $crudRoutePart = 'correctives';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });

            $table->addColumn('sources_source', function ($row) {
                return $row->sources ? $row->sources->source : '';
            });

            $table->editColumn('finding', function ($row) {
                return $row->finding ? $row->finding : "";
            });
            $table->editColumn('action', function ($row) {
                return $row->action ? $row->action : "";
            });
            $table->addColumn('tanggung_jawab_name', function ($row) {
                return $row->tanggung_jawab ? $row->tanggung_jawab->name : '';
            });

            $table->editColumn('tanggung_jawab.name', function ($row) {
                return $row->tanggung_jawab ? (is_string($row->tanggung_jawab) ? $row->tanggung_jawab : $row->tanggung_jawab->name) : '';
            });

            $table->editColumn('evident', function ($row) {
                if ($photo = $row->evident) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });
            $table->addColumn('status_status', function ($row) {
                return $row->status ? $row->status->status : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'sources', 'tanggung_jawab', 'evident', 'status']);

            return $table->make(true);
        }

        $sources  = Source::get();
        $users    = User::get();
        $statuses = Status::get();

        return view('admin.correctives.index', compact('sources', 'users', 'statuses'));
    }

    public function create()
    {
        abort_if(Gate::denies('corrective_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sources = Source::all()->pluck('source', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tanggung_jawabs = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $statuses = Status::all()->pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.correctives.create', compact('sources', 'tanggung_jawabs', 'statuses'));
    }

    public function store(StoreCorrectiveRequest $request)
    {
        $corrective = Corrective::create($request->all());

        if ($request->input('evident', false)) {
            $corrective->addMedia(storage_path('tmp/uploads/' . $request->input('evident')))->toMediaCollection('evident');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $corrective->id]);
        }

        return redirect()->route('admin.correctives.index');
    }

    public function edit(Corrective $corrective)
    {
        abort_if(Gate::denies('corrective_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sources = Source::all()->pluck('source', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tanggung_jawabs = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $statuses = Status::all()->pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $corrective->load('sources', 'tanggung_jawab', 'status');

        return view('admin.correctives.edit', compact('sources', 'tanggung_jawabs', 'statuses', 'corrective'));
    }

    public function update(UpdateCorrectiveRequest $request, Corrective $corrective)
    {
        $corrective->update($request->all());

        if ($request->input('evident', false)) {
            if (!$corrective->evident || $request->input('evident') !== $corrective->evident->file_name) {
                if ($corrective->evident) {
                    $corrective->evident->delete();
                }

                $corrective->addMedia(storage_path('tmp/uploads/' . $request->input('evident')))->toMediaCollection('evident');
            }
        } elseif ($corrective->evident) {
            $corrective->evident->delete();
        }

        return redirect()->route('admin.correctives.index');
    }

    public function show(Corrective $corrective)
    {
        abort_if(Gate::denies('corrective_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $corrective->load('sources', 'tanggung_jawab', 'status');

        return view('admin.correctives.show', compact('corrective'));
    }

    public function destroy(Corrective $corrective)
    {
        abort_if(Gate::denies('corrective_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $corrective->delete();

        return back();
    }

    public function massDestroy(MassDestroyCorrectiveRequest $request)
    {
        Corrective::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('corrective_create') && Gate::denies('corrective_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Corrective();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
