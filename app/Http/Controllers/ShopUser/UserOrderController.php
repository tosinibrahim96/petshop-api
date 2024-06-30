<?php

declare(strict_types=1);

namespace App\Http\Controllers\ShopUser;

use App\Http\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use Auth;
use Request;

/**
 * Class UserOrderController
 * Handles user account creation and related actions.
 *
 */
class UserOrderController extends Controller
{
    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * UserOrderController constructor.
     *
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

     /**
     * @OA\Get(
     *     path="/api/v1/user/orders",
     *     summary="Get orders for the user",
     *     tags={"Shop User"},
     *     @OA\Response(
     *         response=200,
     *         description="Orders retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/OrderResource")
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal server error"),
     *     security={{"apiAuth": {}}}
     * )
     *
     * Retrieve user orders.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $orders = $this->orderRepository->getOrdersForUser($user->id, ['orderStatus','payment']);
        
        return ApiResponse::send(true, Response::HTTP_OK, "Orders retrieved successfully", OrderResource::collection($orders));
    }
}
