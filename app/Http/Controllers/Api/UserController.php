<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function index(IndexUserRequest $request): JsonResource|JsonResponse
    {
        $filters = $request->validated();

        $users = User::query()
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

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::create($data);

        return response()->success([
            User::getResourceLabel() => UserResource::make($user),
        ]);
    }

    public function show(User $user): JsonResource
    {
        return UserResource::make($user);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $patchData = $request->validated();
        $isUpdated = $user->update($patchData);

        if (!$isUpdated) {
            return response()->error();
        }

        return response()->success([
            'updated_data' => $patchData,
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $isDeleted = $user->delete();

        if (!$isDeleted) {
            return response()->error();
        }

        return response()->success();
    }
}
