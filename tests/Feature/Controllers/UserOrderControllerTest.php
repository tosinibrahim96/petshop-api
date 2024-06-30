<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test retrieving orders for an authenticated user.
     *
     * @return void
     */
    public function testAuthenticatedUserCanRetrieveOrders()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $status = OrderStatus::factory()->create();
        $payment = Payment::factory()->create();

        $orders = Order::factory()->count(3)->create([
            'user_id' => $user->id,
            'order_status_id' => $status->id,
            'payment_id' => $payment->id,
        ]);

        $response = $this->getJson('api/v1/user/orders');

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         '*' => [
                             'id',
                             'user_id',
                             'order_status_id',
                             'payment_id',
                             'uuid',
                             'products',
                             'address',
                             'delivery_fee',
                             'amount',
                             'created_at',
                             'updated_at',
                             'shipped_at',
                             'order_status',
                             'payment',
                         ]
                     ]
                 ]);
    }

    /**
     * Test retrieving orders for an unauthenticated user.
     *
     * @return void
     */
    public function testUnauthenticatedUserCannotRetrieveOrders()
    {
        $response = $this->getJson('api/v1/user/orders');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test retrieving orders when there are no orders.
     *
     * @return void
     */
    public function testRetrievingOrdersWhenNoOrdersExist()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->getJson('api/v1/user/orders');

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Orders retrieved successfully',
                     'data' => [],
                 ]);
    }

    /**
     * Test retrieving orders with relationships.
     *
     * @return void
     */
    public function testRetrievingOrdersWithRelationships()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $status = OrderStatus::factory()->shipped()->create();
        $payment = Payment::factory()->create();

        $orders = Order::factory()->count(3)->create([
            'user_id' => $user->id,
            'order_status_id' => $status->id,
            'payment_id' => $payment->id,
        ]);

        $response = $this->getJson('api/v1/user/orders');

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         '*' => [
                             'id',
                             'user_id',
                             'order_status_id',
                             'payment_id',
                             'uuid',
                             'products',
                             'address',
                             'delivery_fee',
                             'amount',
                             'created_at',
                             'updated_at',
                             'shipped_at',
                             'order_status',
                             'payment' => [
                                 'id',
                                 'uuid',
                                 'type',
                                 'details'
                             ],
                         ]
                     ]
                 ]);
    }
}
