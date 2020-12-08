<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCorrectiveRequest;
use App\Http\Requests\UpdateCorrectiveRequest;
use App\Http\Resources\Admin\CorrectiveResource;
use App\Models\Corrective;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorrectiveApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('corrective_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CorrectiveResource(Corrective::with(['sources', 'tanggung_jawab', 'status'])->get());
    }

    public function store(StoreCorrectiveRequest $request)
    {
        $corrective = Corrective::create($request->all());

        if ($request->input('evident', false)) {
            $corrective->addMedia(storage_path('tmp/uploads/' . $request->input('evident')))->toMediaCollection('evident');
        }

        return (new CorrectiveResource($corrective))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Corrective $corrective)
    {
        abort_if(Gate::denies('corrective_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CorrectiveResource($corrective->load(['sources', 'tanggung_jawab', 'status']));
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

        return (new CorrectiveResource($corrective))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Corrective $corrective)
    {
        abort_if(Gate::denies('corrective_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $corrective->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
