<?php

namespace AppBundle\Services;

class UrlReplacer {
	public function replace($field, $str, &$response, $i=false, $unique=false) {

		if ($unique) {
			if($i !== false) {

					$offset = stripos($response['data']['results'][$i][$field], "/$str/") + strlen("/$str/");
	           		$id = substr($response['data']['results'][$i][$field], $offset);
	                $response['data']['results'][$i][$field] = "/$str/id/$id";


			} else {

					$offset = stripos($response['data'][$field], "/$str/") + strlen("/$str/");
	           		$id = substr($response['data'][$field], $offset);
	                $response['data'][$field] = "/$str/id/$id";

			}

		} else {

			if($i !== false) {

				for ($j=0; $j<count($response['data']['results'][$i][$field]); $j++) {
					$offset = stripos($response['data']['results'][$i][$field][$j], "/$str/") + strlen("/$str/");
	           		$id = substr($response['data']['results'][$i][$field][$j], $offset);
	                $response['data']['results'][$i][$field][$j] = "/$str/id/$id";

	            }
			} else {

				for ($k=0; $k<count($response['data'][$field]); $k++) {
					$offset = stripos($response['data'][$field][$k], "/$str/") + strlen("/$str/");
	           		$id = substr($response['data'][$field][$k], $offset);
	                $response['data'][$field][$k] = "/$str/id/$id";
	            }
			}
		}
	}
}