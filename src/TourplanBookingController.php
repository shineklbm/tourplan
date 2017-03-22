<?php
namespace Shineklbm\Tourplan;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchHotelRequest;
use App\Http\Requests\BookHotelRequest;
use App\Http\Requests\CancelBookingRequest;
use GreenCape\Xml\Converter;


class TourplanBookingController extends Controller
{
    var $xml;
    var $agent_id;
    var $password;

    public function __construct(){
        $wrapper = "<?xml version='1.0'?><!DOCTYPE Request SYSTEM 'hostConnect_3_00_000.dtd'><Request></Request>";
        $this->xml = simplexml_load_string($wrapper);

        $this->agent_id = env('EXOTRAVEL_AGENT_ID', false);
        $this->password = env('EXOTRAVEL_PASSWORD', false);
    }

    /**
    * searchHotel method.
    *
    * @param SearchHotelRequest
    */
    public function searchHotel(SearchHotelRequest $searchHotelRequest)
    {
        $hotels = array();
        $location_code = '???';
        $service_code = 'AC';
        $supplier_code = '??????';
        $option_code = '??????';
        $start_index = 0;
        $limit = 10;

        if(!empty($searchHotelRequest->opt)){
            $opt = $searchHotelRequest->opt;
        } else {
            if(!empty($searchHotelRequest->location_code)):
                $location_code = $searchHotelRequest->location_code;
            endif;
            if(!empty($searchHotelRequest->supplier_code)):
                $supplier_code = $searchHotelRequest->supplier_code;
            endif;
            if(!empty($searchHotelRequest->option_code)):
                $option_code = $searchHotelRequest->option_code;
            endif;
            $opt = $location_code.$service_code.$supplier_code.$option_code;
        }

        if(!empty($searchHotelRequest->start_index)):
            $start_index = $searchHotelRequest->start_index;
        endif;
        if(!empty($searchHotelRequest->limit)):
            $limit = $searchHotelRequest->limit;
        endif;

        $request_method = $this->xml->addChild("OptionInfoRequest");
        $request_method->addChild('AgentID', $this->agent_id);
        $request_method->addChild('Password', $this->password);
        $request_method->addChild('Opt', $opt);

        if(!empty($searchHotelRequest->room_category)):
            $request_method->addChild('Opt', $searchHotelRequest->room_category);
        endif;
        
        $request_method->addChild('Info', 'GAR');
        $hotels['general']['start_date']   = date("Y-m-d");
        $hotels['general']['days_count']   = 1;

        if((!empty($searchHotelRequest->start_date)) && (!empty($searchHotelRequest->days_count))){
            $request_method->addChild('DateFrom', $searchHotelRequest->start_date);
            $request_method->addChild('SCUqty', $searchHotelRequest->days_count);
            $hotels['general']['start_date']   = $searchHotelRequest->start_date;
            $hotels['general']['days_count']   = $searchHotelRequest->days_count;
        }
        $request_method->addChild('IndexFirstOption', $start_index);
        $request_method->addChild('MaximumOptions', $limit);
        $request_data = $this->xml->asXML();

        $curl_request = new TourplanCurlRequestController('OptionInfoRequest', $request_data);
        $result = $curl_request->getResponse();

        $xml = new Converter($result);
        $response = $xml->data;

        return $response;
    }

    /**
    * bookHotel method.
    *
    * @param none
    */
    public function bookHotel(BookHotelRequest $bookHotelRequest)
    {
        $request_method = $this->xml->addChild("AddServiceRequest");
        $request_method->addChild('AgentID', $this->agent_id);
        $request_method->addChild('Password', $this->password);
        $new_booking_info   = $request_method->addChild('NewBookingInfo');

        $lead_guest_name = $bookHotelRequest->lead_guest_name;

        $new_booking_info->addChild('Name', $lead_guest_name);
        $new_booking_info->addChild('QB', 'B');

        $option_code = $bookHotelRequest->option_code;
        $request_method->addChild('Opt', $option_code);

        $option_tariff = "Default";
        $request_method->addChild('RateId', $option_tariff);

        $start_date = date("Y-m-d");
        if(!empty($bookHotelRequest->start_date))
            $start_date = $bookHotelRequest->start_date;
        $request_method->addChild('DateFrom', $start_date);

        $room_configs = $request_method->addChild('RoomConfigs');
        $room_config = $room_configs->addChild('RoomConfig');

        $guest_count = 1;
        if(!empty($bookHotelRequest->guest_count))
            $guest_count = $bookHotelRequest->guest_count;
        $room_config->addChild('Adults', $guest_count);

        $room_type = 'SG';
        if(!empty($bookHotelRequest->room_type))
            $room_type = $bookHotelRequest->room_type;
        $room_config->addChild('RoomType', $room_type);

        $pax_list = $room_config->addChild('PaxList');
        $pax_details = $pax_list->addChild('PaxDetails');
        $pax_details->addChild('Title', $bookHotelRequest->title);
        $pax_details->addChild('Forename', $bookHotelRequest->forename);
        $pax_details->addChild('Surname', $bookHotelRequest->surname);
        $pax_details->addChild('PaxType', $bookHotelRequest->passenger_type); // A for adult, C for children

        $days_count = 1;
        if(!empty($bookHotelRequest->days_count))
            $days_count = $bookHotelRequest->days_count;
        $request_method->addChild('SCUqty', $days_count);
        
        $request_data = $this->xml->asXML();

        $curl_request = new TourplanCurlRequestController('AddServiceRequest', $request_data);
	    $result = $curl_request->getResponse();

        $xml = new Converter($result);
        $booking_response = $xml->data;

        return $booking_response;
    }

    /**
    * cancelBooking method.
    *
    * @param none
    */
    public function cancelBooking(CancelBookingRequest $cancelBookingRequest) {

        $request_method = $this->xml->addChild("CancelServicesRequest");
        $request_method->addChild('AgentID', $this->agent_id);
        $request_method->addChild('Password', $this->password);
        $request_method->addChild('BookingId', $cancelBookingRequest->booking_id);

        $request_data = $this->xml->asXML();
        $curl_request = new TourplanCurlRequestController('CancelServicesRequest', $request_data);
        
        $result = $curl_request->getResponse();
        
        $xml = new Converter($result);
        $response = $xml->data;

        return $response;
    }
}
