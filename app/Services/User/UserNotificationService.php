<?php

namespace App\Services\User;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use App\Helpers\Pagination\PaginationHelper;
use Illuminate\Support\Facades\DB;

class UserNotificationService
{
    public const PER_PAGE = 8;

    public const SHOW = 140;
    public const RECENT_SHOW = 4;

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getNotifications(Request $request)
    {
        $query = Notification::where('notifiable_id', auth()->id());

        $query = $this->setDates($request, $query);

        if ($request->get('unread') == 1) {
            $query = $query->whereNull('read_at');
        }

        if ($request->get('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%");
        }

//        $notifications = $query->latest('created_at')->take(self::SHOW);
//        return PaginationHelper::paginate($notifications, self::PER_PAGE);
        return $query->latest()->paginate(self::PER_PAGE);
    }

    /**
     * @return mixed
     */
    public static function getRecent()
    {
        return Notification::where('notifiable_id', auth()->id())
            ->latest('created_at')
            ->limit(self::RECENT_SHOW)
            ->get();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public static function getUserRecent($userId)
    {
        return Notification::where('notifiable_id', $userId)
            ->orderByDesc('created_at')
            ->limit(self::RECENT_SHOW)
            ->get();
    }

    /**
     * @param $notificationId
     * @param User|null $user
     * @return mixed
     */
    public function read($notificationId, User $user = null)
    {
        return Notification::where('id', $notificationId)
            ->where('notifiable_id', auth()->id() ?? $user->id)
            ->update(['read_at' => now()]);
    }

    /**
     * @param $notificationId
     * @param User|null $user
     * @return mixed
     */
    public function unread($notificationId, User $user = null)
    {
        return Notification::where('id', $notificationId)
            ->where('notifiable_id', auth()->id() ?? $user->id)
            ->update(['read_at' => null]);
    }

    /**
     * @param User|null $user
     * @return mixed
     */
    public function readAll(User $user = null)
    {
        return Notification::whereNull('read_at')
            ->where('notifiable_id', auth()->id() ?? $user->id)
            ->update(['read_at' => now()]);
    }

    /**
     * @return mixed
     */
    public function getNumberOfNew()
    {
        return auth()->user()->notifications()->where(['read_at' => null])->count();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public static function getUserNumberOfNew($userId)
    {
        return Notification::where('notifiable_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * @param Request $request
     * @param $query
     * @return mixed
     */
    private function setDates(Request $request, $query): mixed
    {
        $fromDate = $request->get('from_date');;
        $toDate = $request->get('to_date');

        if (!$fromDate) return $query;

        if ($toDate) {
            $toDate = Carbon::createFromFormat('Y-m-d', $toDate)->addDay()->toDateString();
        } else {
            $toDate = Carbon::now()->addDay()->toDateString();
        }

        return $query->whereBetween('created_at', [$fromDate, $toDate]);
    }
}
