<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Requests\Notification\UpdateNotificationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Services\User\UserNotificationService;
use App\Http\Resources\Notification\UserNotificationResource;

class UserNotificationController extends Controller
{
    public function show(Request $request, UserNotificationService $notificationService)
    {
        $notifications = $notificationService->getNotifications($request);

        return UserNotificationResource::collection($notifications);
    }

    public function recent(Request $request, UserNotificationService $notificationService)
    {
        $notifications = $notificationService->getRecent($request);

        return response()->json([
            'unread' => UserNotificationService::getUserNumberOfNew(auth()->id()),
            "notifications" => UserNotificationResource::collection($notifications)
        ]);
    }

    public function read(UpdateNotificationRequest $request, UserNotificationService $notificationService): JsonResponse
    {
        $notificationService->read($request->validated('id'));

        return Response::success([
            'message' => __('Success'),
        ]);
    }

    public function unread(UpdateNotificationRequest $request, UserNotificationService $notificationService): JsonResponse
    {
        $notificationService->unread($request->validated('id'));

        return Response::success([
            'message' => __('Success'),
        ]);
    }

    public function readAll(UserNotificationService $notificationService): JsonResponse
    {
        $notificationService->readAll();

        return Response::success([
            'message' => __('Success'),
        ]);
    }
}
