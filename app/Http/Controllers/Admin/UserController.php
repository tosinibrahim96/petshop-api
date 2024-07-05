<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\ShopUser\DeleteUserAction;
use App\Actions\ShopUser\UpdateUserAction;
use App\Http\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * UserController
 *
 * Handles user management functionalities for admins.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UpdateUserAction
     */
    protected $updateUserAction;

    /**
     * @var DeleteUserAction
     */
    protected $deleteUserAction;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, UpdateUserAction $updateUserAction, DeleteUserAction $deleteUserAction)
    {
        $this->userRepository = $userRepository;
        $this->updateUserAction = $updateUserAction;
        $this->deleteUserAction = $deleteUserAction;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/user-listing",
     *     tags={"Admin"},
     *     summary="Get a paginated list of non-admin users",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="desc",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Users retrieved successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/UserResource"))
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation failed"),
     *     @OA\Response(response=500, description="Internal server error"),
     *     security={{"apiAuth": {}}}
     * )
     *
     * Get a paginated list of non-admin users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->getPaginatedUsersWithoutAdmins($request->all());

        return ApiResponse::sendWithCollection(true, Response::HTTP_OK, 'Users retrieved successfully', UserResource::collection($users)->response()->getData(true));
    }


    /**
     * @OA\Put(
     *     path="/api/v1/admin/user-edit/{uuid}",
     *     tags={"Admin"},
     *     summary="Edit a non-admin user",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/AdminUpdateUserRequest"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation failed"),
     *     @OA\Response(response=500, description="Internal server error"),
     *     security={{"apiAuth": {}}}
     * )
     *
     * Edit a non-admin user.
     *
     * @param UpdateUserRequest $request
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, $uuid)
    {
        $user = $this->userRepository->findByUuid($uuid);

        if (isset($user) && $user->is_admin) {
            return ApiResponse::send(true, Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }

        $user = $this->updateUserAction->execute($request->validated(), $user->id);

        return ApiResponse::send(true, Response::HTTP_OK, 'User details updated successfully', $user);
    }


    /**
     * @OA\Delete(
     *     path="/api/v1/admin/user-delete/{uuid}",
     *     tags={"Admin"},
     *     summary="Delete a non-admin user",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="boolean"))
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation failed"),
     *     @OA\Response(response=500, description="Internal server error"),
     *     security={{"apiAuth": {}}}
     * )
     *
     * Delete a non-admin user.
     *
     * @param DeleteUserRequest $request
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteUserRequest $request, $uuid)
    {
        $user = $this->userRepository->findByUuid($uuid);

        if (isset($user) && $user->is_admin) {
            return ApiResponse::send(true, Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }

        $userDeleted = $this->deleteUserAction->execute($user->id);

        if ($userDeleted) {
            return ApiResponse::send(true, Response::HTTP_OK, "User deleted successfully");
        }

        return ApiResponse::send(false, Response::HTTP_NOT_FOUND, "User not found");
    }

}
