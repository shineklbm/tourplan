<?php

namespace Shineklbm\Tourplan\Requests;

use App\Http\Requests\Request;

class SearchHotelRequest extends Request
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
            'deal_id'          => 'sometimes|alpha_num',
            'lead_guest_name'  => 'sometimes|string',
            'guest_count'      => 'sometimes|numeric',
            'days_count'       => 'sometimes|numeric',
            'start_date'       => 'sometimes|date_format:"Y-m-d"',
            'city'             => 'sometimes|string',
            'supplier_id'      => 'sometimes|string',
            'room_type'        => 'sometimes|string',
            'location_code'    => 'sometimes|size:3',
            'supplier_code'    => 'sometimes|size:6',
            'option_code'      => 'sometimes|size:6',
        ];
    }

    public function messages()
    {
        return [
            'agent_id.required'         => 'Please enter agent ID',
            'password.required'         => 'Please enter password',
            'deal_id.alpha_num'         => 'Please enter a valid Deal ID',
            'lead_guest_name.string'    => 'Please enter a valid name',
            'guest_count.numeric'       => 'Guest count should be a number',
            'days_count.numeric'        => 'No. of days should be numeric',
            'start_date.date_format'    => 'Start date should be a valid date',
            'city.string'               => 'City should be a valid city name',
            'supplier_id.string'        => 'Supplier name required',
            'room_type.string'          => 'Room type should be a valid string',            
        ];
    }

    public function response(array $errors) {
        $result = array(
            'success' => 0,
            'successMessage' => '',
            'result' => array('error' => $errors),
            'errorMessage' => ''
        );
        return \Response::json($result, 400); 
    }
}
