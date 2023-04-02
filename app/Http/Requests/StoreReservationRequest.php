<?php

namespace App\Http\Requests;

class StoreReservationRequest extends ReservationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'vacancies' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'days' => 'required|integer',
            'element_id' => 'required|integer',
        ];
    }
}
