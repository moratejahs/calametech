<?php

namespace App\Http\Requests\Api\V1;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isVerifiedByAdmin();
        // return true;
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new AuthorizationException('Only verified accounts can send reports.');
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => ['required'],
            'ai_tips' => ['nullable'],
            'image' => ['nullable', 'image', 'max:12288'], // max 12mb
            'type' => ['required', 'in:fire,flood'],
            'lat' => ['required'],
            'long' => ['required'],
        ];
    }
}
