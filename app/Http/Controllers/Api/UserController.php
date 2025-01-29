<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Http\Requests\User\GetUsersPaginatedRequest;
use App\Exceptions\GetDataException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;


class UserController extends Controller 
{
    public function __construct(
        protected UserService $userService
    ) 
    {
    }

    public function getPaginatedUsers(GetUsersPaginatedRequest $request): JsonResponse 
    {
        $payload = $request->validated();

        try {
            $users = $this->userService->getPaginatedUsers($payload['per_page'], $payload['page']);

            return Response::paginate($users);
        } catch (GetDataException $e) {
            return Response::error($e->getMessage(), $e->getCode());
        } 
    }
}
