<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserService
{
    /**
     * Retrieve a paginated list of users.
     *
     * @param  int  $perPage Number of items per page.
     * @param  int  $page    Current page number.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getPaginatedUsers(int $perPage, int $page): AnonymousResourceCollection
    {
        $effectivePage = $this->calculateEffectivePage($perPage, $page);
        $cacheDuration = now()->addHour();
        $cacheKey = "users_perPage_{$perPage}_page_{$effectivePage}";

        return Cache::remember($cacheKey, $cacheDuration, function () use ($perPage, $effectivePage) {
            $users = User::orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $effectivePage);

            return UserResource::collection($users);
        });
    }

    /**
     * Calculate the effective page number based on total users and page request.
     *
     * @param  int  $perPage Number of items per page.
     * @param  int  $page    Requested page number.
     * @return int           Calculated effective page number.
     */
    private function calculateEffectivePage(int $perPage, int $page): int
    {
        $totalUsers = User::count();
        $lastPage = (int) ceil($totalUsers / $perPage);

        return max(1, min($page, $lastPage)); // Ensure the requested page is within valid bounds
    }
}
