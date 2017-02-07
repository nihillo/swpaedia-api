<?php

namespace AppBundle\Services;
use Symfony\Component\HttpFoundation\Response;

class CurlRequest {
	public function request($url) {
		$curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_FOLLOWLOCATION => 1
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $result = array();

        if ($err) {
        	$result['status'] = Response::HTTP_INTERNAL_SERVER_ERROR;
        	$result['data'] = $err;
        } else {
        	$result['status'] = Response::HTTP_OK;
        	$result['data'] = json_decode($response, true);
        }

        return $result;
	}
}