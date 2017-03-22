<?php

namespace Shineklbm\Tourplan\Requests;

use App\Http\Requests\Request;

class BookHotelRequest extends Request
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
            'forname'               => 'sometimes|alpha_num',
            'surname'               => 'sometimes|alpha_num',
            'adults'                => 'sometimes|numeric',
            'kids'                  => 'sometimes|numeric',
            'start_date'            => 'required|date_format:"Y-m-d"',
            'city'                  => 'sometimes|string',
            'room_type'             => 'sometimes|string',
            'days_count'            => 'required',
            'lead_guest_name'       => 'required',
            'guest_count'           => 'required',
            'room_type'             => 'required',
            'option_supplier_name'  => 'required',
            'option_supplier_id'    => 'required',
            'option_number'         => 'required',
            'option_code'           => 'required',
            'option_tariff'         => 'required',
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
