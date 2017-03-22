<?php

namespace Shineklbm\Tourplan\Requests;

use App\Http\Requests\Request;

class CancelBookingRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'booking_id'          => 'required|alpha_num',
        ];
    }
    public function messages()
    {
        return [
            'booking_id.required'         => 'Booking ID is required!',
            'booking_id.alpha_num'        => 'Booking ID should be alphanumeric'        
        ];
    }
}
