<?php
namespace Shineklbm\Tourplan;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TourplanCurlRequestController extends Controller
{
    var $curl_object;
    var $headers = array();
    var $request_method;
    var $request_data;

    public function __construct($request_method, $request_data){
        $this->curl_object = curl_init();
        $this->request_method = $request_method;
        $this->request_data = $request_data;
    }

    public function setHeaders(){
	    $this->headers = array(
			    			'Content-Type: text/xml; charset="utf-8"',
			    			'Content-Length: '.strlen($this->request_data),
			    			'Accept: text/xml',
			    			'Cache-Control: no-cache',
			    			'Pragma: no-cache',
			    			'SOAPAction: '.$this->request_method
						);
    }

    public function setParams(){
    	curl_setopt($this->curl_object, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->curl_object, CURLOPT_URL, env('EXOTRAVEL_API_URL', false));
		curl_setopt($this->curl_object, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl_object, CURLOPT_TIMEOUT, 60);
		curl_setopt($this->curl_object, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($this->curl_object, CURLOPT_POST, true);
		curl_setopt($this->curl_object, CURLOPT_POSTFIELDS, $this->request_data);
		curl_setopt($this->curl_object, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    }

    public function getResponse(){
    	$this->setHeaders();
    	$this->setParams();
    	return curl_exec($this->curl_object);
    }
}
