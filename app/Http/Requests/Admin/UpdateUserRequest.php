<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Repositories\UserRepository;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="AdminUpdateUserRequest",
 *     type="object",
 *     required={"first_name", "last_name", "email"},
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="address", type="string"),
 *     @OA\Property(property="phone_number", type="string")
 * )
 */
class UpdateUserRequest extends FormRequest
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
            'uuid' => 'required|exists:users,uuid',
            'id' => 'nullable',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->id,
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string'
        ];
    }



    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $user = app(UserRepository::class)->findByUuid($this->route('uuid'));
        $id = $user->id ?? null;

        $this->merge([
            'uuid' => $this->route('uuid'),
            'id' => $id
        ]);
    }
}
