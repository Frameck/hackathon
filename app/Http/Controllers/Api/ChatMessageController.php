<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexChatMessageRequest;
use App\Http\Requests\StoreChatMessageRequest;
use App\Http\Requests\UpdateChatMessageRequest;
use App\Http\Resources\ChatMessageResource;
use App\Models\Account;
use App\Models\ChatMessage;
use App\Services\ChatMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageController extends Controller
{
    public function index(IndexChatMessageRequest $request): JsonResource|JsonResponse
    {
        $ids = ChatMessage::query()
            ->where('general_risk', '>', 4)
            ->limit(20)
            ->get(['id', 'general_risk', 'account_id']);

        $final = [];

        foreach ($ids as $key => $value) {
            $accountKarma = Account::where('account_id', $value->account_id)->value('karma');
            $ids[$key]['factor'] = $value->general_risk * $accountKarma;
        }

        $ids = $ids->sortByDesc('factor')->all();

        foreach ($ids as $key => $value) {
            $final[] = ChatMessageService::getSurroundMessages($value->id);
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
