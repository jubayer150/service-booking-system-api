<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ServiceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_id' => [
                'required',
                'integer',
                Rule::exists('services', 'id')->where('status', ServiceStatus::ACTIVE),
            ],
            'booking_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
                Rule::unique('bookings')->where(function ($query) {
                    $query->where([
                        'user_id'    => auth()->id(),
                        'service_id' => $this->input('service_id'),
                        'booking_date' => $this->input('booking_date'),
                    ]);
                }),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'service_id' => 'service',
        ];
    }

    public function messages(): array
    {
        return [
            'booking_date.unique' => 'You have already booked this service for the selected date. Please choose another date.',
        ];
    }
}
