<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexAccountRequest;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountController extends Controller
{
    public function index(IndexAccountRequest $request): JsonResource|JsonResponse
    {
        $filters = $request->validated();

        $account = Account::query()
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

        return AccountResource::collection($account);
    }

    public function store(StoreAccountRequest $request): JsonResponse
    {
        $data = $request->validated();
        $account = Account::create($data);

        return response()->success([
            Account::getResourceLabel() => AccountResource::make($account),
        ]);
    }

    public function show(Account $account): JsonResource
    {
        return AccountResource::make($account);
    }

    public function update(UpdateAccountRequest $request, Account $account): JsonResponse
    {
        $patchData = $request->validated();
        $isUpdated = $account->update($patchData);

        if (!$isUpdated) {
            return response()->error();
        }

        return response()->success([
            'updated_data' => $patchData,
        ]);
    }

    public function destroy(Account $account): JsonResponse
    {
        $isDeleted = $account->delete();

        if (!$isDeleted) {
            return response()->error();
        }

        return response()->success();
    }
}
