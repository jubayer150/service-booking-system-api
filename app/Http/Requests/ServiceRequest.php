<?php

namespace App\Http\Requests;

use App\Enums\ServiceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return $this->updateRules();
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => [Rule::enum(ServiceStatus::class)],
        ];
    }

    public function updateRules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'description' => ['string', 'max:1000'],
            'price' => ['numeric', 'min:0'],
            'status' => [Rule::enum(ServiceStatus::class)],
        ];
    }
}
