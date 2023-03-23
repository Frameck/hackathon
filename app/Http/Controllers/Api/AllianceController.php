<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexAllianceRequest;
use App\Http\Requests\StoreAllianceRequest;
use App\Http\Requests\UpdateAllianceRequest;
use App\Http\Resources\AllianceResource;
use App\Models\Alliance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AllianceController extends Controller
{
    public function index(IndexAllianceRequest $request): JsonResource|JsonResponse
    {
        $filters = $request->validated();

        $alliance = Alliance::query()
            ->when(
                isset($filters['ids']),
                fn (Builder $query) => $query->whereIn('id', $filters['ids'])
            )
            ->when(
                isset($filters['sort_by']),
                fn (Builder $query) => $query->orderBy(
                    $filters['sort_by'],
                    $filters['sort_direction'] ?? 'asc'
                )
            )
            ->when(
                isset($filters['all']),
                fn (Builder $query) => $query->get(),
                fn (Builder $query) => (
                    $query->paginate(
                        request('per_page', PAGINATION_PER_PAGE)
                    )
                        ->appends($filters)
                ),
            );

        return AllianceResource::collection($alliance);
    }

    public function store(StoreAllianceRequest $request): JsonResponse
    {
        $data = $request->validated();
        $alliance = Alliance::create($data);

        return response()->success([
            Alliance::getResourceLabel() => AllianceResource::make($alliance),
        ]);
    }

    public function show(Alliance $alliance): JsonResource
    {
        return AllianceResource::make($alliance);
    }

    public function update(UpdateAllianceRequest $request, Alliance $alliance): JsonResponse
    {
        $patchData = $request->validated();
        $isUpdated = $alliance->update($patchData);

        if (!$isUpdated) {
            return response()->error();
        }

        return response()->success([
            'updated_data' => $patchData,
        ]);
    }

    public function destroy(Alliance $alliance): JsonResponse
    {
        $isDeleted = $alliance->delete();

        if (!$isDeleted) {
            return response()->error();
        }

        return response()->success();
    }
}
