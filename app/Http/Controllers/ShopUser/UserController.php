<?php

declare(strict_types=1);

namespace App\Http\Controllers\ShopUser;

use App\Actions\Auth\LoginAction;
use App\Actions\ShopUser\CreateUserAction;
use App\Http\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShopUser\CreateUserRequest;
use Illuminate\Http\Response;
use App\Actions\ShopUser\DeleteUserAction;
use App\Actions\ShopUser\ShowUserAction;
use App\Actions\ShopUser\UpdateUserAction;
use App\Http\Requests\ShopUser\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Auth;
use Request;

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
     * @var DeleteUserAction
     */
    protected $deleteUserAction;

    /**
     * @var ShowUserAction
     */
    protected $showUserAction;

    /**
     * @var UpdateUserAction
     */
    protected $updateUserAction;

    /**
     * UserController constructor.
     *
     * @param CreateUserAction $createUserAction
     * @param LoginAction $loginAction
     */
    public function __construct(CreateUserAction $createUserAction, LoginAction $loginAction, DeleteUserAction $deleteUserAction, ShowUserAction $showUserAction, UpdateUserAction $updateUserAction)
    {
        $this->createUserAction = $createUserAction;
        $this->loginAction = $loginAction;
        $this->deleteUserAction = $deleteUserAction;
        $this->showUserAction = $showUserAction;
        $this->updateUserAction = $updateUserAction;
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
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/CreateUserRequest"
     *             )
     *         )
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

    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="Get user account",
     *     tags={"Shop User"},
     *     @OA\Response(
     *         response=200,
     *         description="User retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal server error"),
     *     security={{"apiAuth": {}}}
     * )
     *
     * Retrieve user account.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $user = $this->showUserAction->execute($user->id ?? null);
        if ($user) {
            return ApiResponse::send(true, Response::HTTP_OK, "User retrieved successfully", UserResource::make($user));
        }
        return ApiResponse::send(false, Response::HTTP_NOT_FOUND, "User not found");
    }

    /**
     * @OA\Put(
     *     path="/api/v1/user/edit",
     *     summary="Update user account",
     *     tags={"Shop User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/UpdateUserRequest"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation failed"),
     *     @OA\Response(response=500, description="Internal server error"),
     *     security={{"apiAuth": {}}}
     * )
     *
     * Update user account.
     *
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request)
    {
        $user = Auth::user();
        $updatedUser = $this->updateUserAction->execute($request->validated(), $user->id);
        if ($updatedUser) {
            return ApiResponse::send(true, Response::HTTP_OK, "User updated successfully", UserResource::make($updatedUser));
        }
        return ApiResponse::send(false, Response::HTTP_NOT_FOUND, "User not found");
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user",
     *     summary="Delete user account",
     *     tags={"Shop User"},
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal server error"),
     *     security={{"apiAuth": {}}}
     * )
     *
     * Delete a user account.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        if ($this->deleteUserAction->execute($user->id)) {
            return ApiResponse::send(true, Response::HTTP_OK, "User deleted successfully");
        }
        return ApiResponse::send(false, Response::HTTP_NOT_FOUND, "User not found");
    }
}
