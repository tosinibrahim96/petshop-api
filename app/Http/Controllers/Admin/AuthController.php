<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\CreatePasswordResetTokenAction;
use App\Actions\Auth\ResetPasswordAction;
use App\Http\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\AuthResource;
use Auth;
use Illuminate\Http\Response;
use Request;

/**
 * Class AuthController
 * Handles the authentication logic for admin.
 *
 */
class AuthController extends Controller
{
    /**
     * @var LoginAction
     */
    protected $loginAction;

    /**
     * @var CreatePasswordResetTokenAction
     */
    protected $createPasswordResetTokenAction;

    /**
     * @var ResetPasswordAction
     */
    protected $resetPasswordAction;

    /**
     * AuthController constructor.
     *
     * @param LoginAction $loginAction
     */
    public function __construct(LoginAction $loginAction, CreatePasswordResetTokenAction $createPasswordResetTokenAction, ResetPasswordAction $resetPasswordAction)
    {
        $this->loginAction = $loginAction;
        $this->createPasswordResetTokenAction = $createPasswordResetTokenAction;
        $this->resetPasswordAction = $resetPasswordAction;
    }

    /**
     * Handle the admin login request.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/api/v1/admin/login",
     *     summary="Login an admin account",
     *     tags={"Admin"},
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


    /**
     * @OA\Post(
     *     path="/api/v1/admin/forgot-password",
     *     summary="Request a password reset token",
     *     tags={"Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/ForgotPasswordRequest"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token created"
     *     ),
     *     @OA\Response(response=400, description="The email address provided is not associated with a user account"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     *
     * Create a token to reset a admin password
     *
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $result = $this->createPasswordResetTokenAction->execute($request->email);

        if (!$result) {
            return ApiResponse::send(false, Response::HTTP_BAD_REQUEST, 'The email address provided is not associated with a user account');
        }

        return ApiResponse::send(true, Response::HTTP_OK, "A password reset link has been sent to $request->email");
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/reset-password-token",
     *     summary="Reset password using token",
     *     tags={"Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/ResetPasswordRequest"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successful"
     *     ),
     *     @OA\Response(response=400, description="Invalid token or email"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     * Reset password with token
     *
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPasswordWithToken(ResetPasswordRequest $request)
    {
        $result = $this->resetPasswordAction->execute($request->all());

        if (!$result) {
            return ApiResponse::send(false, Response::HTTP_BAD_REQUEST, 'Invalid token or email');
        }

        return ApiResponse::send(true, Response::HTTP_OK, 'Password reset successful');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/logout",
     *     summary="Logout the admin",
     *     tags={"Admin"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal server error"),
     *     security={{"apiAuth": {}}}
     * )
     *
     * Logout admin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return ApiResponse::send(true, Response::HTTP_OK, 'Successfully logged out');
    }

}
