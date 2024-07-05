<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UploadFileRequest
 *
 * @OA\Schema(
 *     schema="UploadFileRequest",
 *     type="object",
 *     title="UploadFileRequest",
 *     description="Request body for uploading a file",
 *     @OA\Property(property="file", type="string", format="binary")
 * )
 */
class UploadFileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'file' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048',
        ];
    }
}
