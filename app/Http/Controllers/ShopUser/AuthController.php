<?php

declare(strict_types=1);

namespace App\Http\Controllers\ShopUser;

use App\Actions\Auth\LoginAction;
use App\Http\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Http\Response;

/**
 * Class AuthController
 * Handles the authentication logic for shop users.
 *
 * @OA\Info(title="Pet Shop API - Swagger Documentation", version="1.0")
 */
class AuthController extends Controller
{
    /**
     * @var LoginAction
     */
    protected $loginAction;

    /**
     * AuthController constructor.
     *
     * @param LoginAction $loginAction
     */
    public function __construct(LoginAction $loginAction)
    {
        $this->loginAction = $loginAction;
    }

    /**
     * Handle the user login request.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     summary="Login a user account",
     *     tags={"Shop User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/LoginRequest"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid login credentials"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $authData = $this->loginAction->execute($request->validated());

        if (is_null($authData)) {
            return ApiResponse::send(false, Response::HTTP_UNAUTHORIZED, "Invalid login credentials");
        }

        return ApiResponse::send(true, Response::HTTP_OK, "Login successful", AuthResource::make((object) $authData));
    }
}
