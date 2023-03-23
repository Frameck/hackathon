<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexChatMessageRequest;
use App\Http\Requests\StoreChatMessageRequest;
use App\Http\Requests\UpdateChatMessageRequest;
use App\Http\Resources\ChatMessageResource;
use App\Models\ChatMessage;
use App\Services\ChatMessageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ChatMessageController extends Controller
{
    public function index(IndexChatMessageRequest $request): JsonResource|JsonResponse
    {
        $filters = $request->validated();

        // $chatMessage = ChatMessage::query()
        //     ->where('general_risk', '>', -1)
        //     ->when(
        //         isset($filters['ids']),
        //         fn (Builder $query) => $query->whereIn('id', $filters['ids'])
        //     )
        //     ->when(
        //         isset($filters['sort_by']),
        //         fn (Builder $query) => $query->orderBy(
        //             $filters['sort_by'],
        //             $filters['sort_direction'] ?? 'asc'
        //         )
        //     )
        //     ->when(
        //         isset($filters['all']),
        //         fn (Builder $query) => $query->get(),
        //         fn (Builder $query) => (
        //             $query->paginate(
        //                 request('per_page', PAGINATION_PER_PAGE)
        //             )
        //                 ->appends($filters)
        //         ),
        //     );

        // return ChatMessageResource::collection($chatMessage);

        $ids = ChatMessage::query()
            ->where('general_risk', '>', 4)
            ->orderBy(DB::raw('RAND()'))
            ->limit(20)
            ->pluck('id');

        $final = [];

        foreach ($ids as $key => $id) {
            $final[] = ChatMessageService::getSurroundMessages($id);
        }

        return response()->json($final);
    }

    public function store(StoreChatMessageRequest $request): JsonResponse
    {
        $data = $request->validated();
        $chatMessage = ChatMessage::create($data);

        return response()->success([
            ChatMessage::getResourceLabel() => ChatMessageResource::make($chatMessage),
        ]);
    }

    public function show(ChatMessage $chatMessage): JsonResource
    {
        return ChatMessageResource::make($chatMessage);
    }

    public function update(UpdateChatMessageRequest $request, ChatMessage $chatMessage): JsonResponse
    {
        $patchData = $request->validated();
        $isUpdated = $chatMessage->update($patchData);

        if (!$isUpdated) {
            return response()->error();
        }

        return response()->success([
            'updated_data' => $patchData,
        ]);
    }

    public function destroy(ChatMessage $chatMessage): JsonResponse
    {
        $isDeleted = $chatMessage->delete();

        if (!$isDeleted) {
            return response()->error();
        }

        return response()->success();
    }
}
