<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySourceRequest;
use App\Http\Requests\StoreSourceRequest;
use App\Http\Requests\UpdateSourceRequest;
use App\Models\Source;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SourceController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('source_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Source::query()->select(sprintf('%s.*', (new Source)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'source_show';
                $editGate      = 'source_edit';
                $deleteGate    = 'source_delete';
                $crudRoutePart = 'sources';

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
            $table->editColumn('source', function ($row) {
                return $row->source ? $row->source : "";
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.sources.index');
    }

    public function create()
    {
        abort_if(Gate::denies('source_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sources.create');
    }

    public function store(StoreSourceRequest $request)
    {
        $source = Source::create($request->all());

        return redirect()->route('admin.sources.index');
    }

    public function edit(Source $source)
    {
        abort_if(Gate::denies('source_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sources.edit', compact('source'));
    }

    public function update(UpdateSourceRequest $request, Source $source)
    {
        $source->update($request->all());

        return redirect()->route('admin.sources.index');
    }

    public function show(Source $source)
    {
        abort_if(Gate::denies('source_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $source->load('sourcesCorrectives');

        return view('admin.sources.show', compact('source'));
    }

    public function destroy(Source $source)
    {
        abort_if(Gate::denies('source_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $source->delete();

        return back();
    }

    public function massDestroy(MassDestroySourceRequest $request)
    {
        Source::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
