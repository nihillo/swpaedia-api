<?php

namespace AppBundle\Services;

use Goutte\Client;

class Crawler {

	public $base_url;
	public $client;

	public function __construct() {
		$this->base_url = 'http://starwars.wikia.com/wiki/';
		$this->client = new Client();
	}

	public function formatName($name) {
		$formatted_name = ucwords($name);
		$formatted_name = str_replace(' ', '_', $formatted_name);

		return $formatted_name;
	} 

	public function getImage($name) {
		$formatted_name = $this->formatName($name);

		$url = $this->base_url . $formatted_name;

		$crawler = $this->client->request('GET', $url);

		if (count($crawler->filter('.pi-image-thumbnail'))) {
			$image = $crawler->filter('.pi-image-thumbnail')->first()->attr('src');
		} else {
			$image = '/images/no-image.png';
		}
		
		return $image;
	}
}