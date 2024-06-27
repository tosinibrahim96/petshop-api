<?php

declare(strict_types=1);

namespace App\Http\Requests\ShopUser;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUserRequest
 * Handles validation for user creation requests.
 *
 * @OA\Schema(
 *     schema="CreateUserRequest",
 *     type="object",
 *     required={"first_name", "last_name", "email", "password", "address", "phone_number"},
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="password", type="string", format="password"),
 *     @OA\Property(property="address", type="string"),
 *     @OA\Property(property="phone_number", type="string")
 * )
 */
class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string',
            'phone_number' => 'required|string'
        ];
    }
}
