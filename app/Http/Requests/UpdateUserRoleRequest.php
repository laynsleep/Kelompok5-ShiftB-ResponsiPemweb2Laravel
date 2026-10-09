<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        // Form web memakai checkbox "is_admin" (dengan hidden input 0); API memakai field "role".
        if ($this->has('is_admin')) {
            $this->merge([
                'role' => $this->boolean('is_admin') ? UserRole::Admin->value : UserRole::User->value,
            ]);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', Rule::enum(UserRole::class)],
        ];
    }
}
