<?php

declare(strict_types=1);

namespace App\Http\Controllers\ShopUser;

use App\Actions\Auth\LoginAction;
use App\Actions\ShopUser\CreateUserAction;
use App\Http\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShopUser\CreateUserRequest;
use Illuminate\Http\Response;

/**
 * Class UserController
 * Handles user account creation and related actions.
 * 
 */
class UserController extends Controller
{
    /**
     * @var CreateUserAction
     */
    protected $createUserAction;

    /**
     * @var LoginAction
     */
    protected $loginAction;

    /**
     * UserController constructor.
     *
     * @param CreateUserAction $createUserAction
     * @param LoginAction $loginAction
     */
    public function __construct(CreateUserAction $createUserAction, LoginAction $loginAction)
    {
        $this->createUserAction = $createUserAction;
        $this->loginAction = $loginAction;
    }

    /**
     * Handle the user account creation request.
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/api/v1/user/create",
     *     summary="Create a new user",
     *     tags={"Shop User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateUserRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Account created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResource")
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
    public function create(CreateUserRequest $request)
    {
        $user = $this->createUserAction->execute($request->validated());
        $result = $this->loginAction->execute(['email' => $user->email, 'password' => $request->validated()['password']]);
        
        return ApiResponse::send(true, Response::HTTP_OK, "Account created successfully", $result);
    }
}
