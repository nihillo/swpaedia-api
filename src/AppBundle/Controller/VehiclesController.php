<?php

namespace AppBundle\Controller;
 
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 

class VehiclesController extends FOSRestController {
    /**
     * @Rest\Get("/vehicles")
     * @Rest\Get("/vehicles/")
     */
    public function getVehicles(Request $request)
    {   

        $swapi_url = "http://swapi.co/api/vehicles";
        $self_url = "/vehicles";
        $cache = $this->get('cache');

        if ($cache->get($self_url)) {
            $response['status'] = Response::HTTP_OK;
            $response['data'] = $cache->get($self_url);
        } else {

            $curl_handler = $this->get('curl_request');
            $response = $curl_handler->request($swapi_url);

            if ($response['status'] == 200) {

                
                // Reformat API URLs in JSON and add images

                $offset = stripos($response['data']['next'], 'page=') + strlen('page=');
                $next_page = substr($response['data']['next'], $offset);

                if ($next_page) {
                    $response['data']['next'] = '/vehicles/page/' . $next_page;
                }
                

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');
                for ($i=0; $i<count($response['data']['results']); $i++) { 

                    $replacer->replace('films', 'films', $response, $i);
                    $replacer->replace('pilots', 'people', $response, $i);

                    $replacer->replace('url', 'vehicles', $response, $i, true);

                    $response['data']['results'][$i]['image'] = $crawler->getImage($response['data']['results'][$i]['name']);
                }
            }
            
            

            $cache->save($self_url, $response['data']);
        }
        
        $view = $this->view($response['data'], $response['status']);
        return $view; 
    }

    /**
     * @Rest\Get("/vehicles/page/{pageNo}")
     * @Rest\Get("/vehicles/page/{pageNo}/")
     */
    public function getVehiclesPaginated(Request $request)
    {
        $pageNo = $request->get('pageNo');

        $swapi_url = "http://swapi.co/api/vehicles?page=$pageNo";
        $self_url = "/vehicles/page/$pageNo";

        $cache = $this->get('cache');

        if ($cache->get("/vehicles/page/$pageNo")) {
            $response['status'] = Response::HTTP_OK;
            $response['data'] = $cache->get($self_url);
        } else {

            $curl_handler = $this->get('curl_request');
            $response = $curl_handler->request($swapi_url);

            if ($response['status'] == 200) {

                
                // Reformat API URLs in JSON and add images

                $offsetNext = stripos($response['data']['next'], 'page=') + strlen('page=');
                $next_page = substr($response['data']['next'], $offsetNext);
                $offsetPrev = stripos($response['data']['previous'], 'page=') + strlen('page=');
                $previous_page = substr($response['data']['previous'], $offsetPrev);

                if ($next_page) {
                    $response['data']['next'] = '/vehicles/page/' . $next_page;
                }

                if ($previous_page) {
                    $response['data']['previous'] = '/vehicles/page/' . $previous_page;
                }

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');
                for ($i=0; $i<count($response['data']['results']); $i++) { 

                    $replacer->replace('films', 'films', $response, $i);
                    $replacer->replace('pilots', 'people', $response, $i);
                    
                    $replacer->replace('url', 'vehicles', $response, $i, true);

                    $response['data']['results'][$i]['image'] = $crawler->getImage($response['data']['results'][$i]['name']);
                }
            }
            
            

            $cache->save($self_url, $response['data']);
        }
        
        $view = $this->view($response['data'], $response['status']);
        return $view;  
 
    }
 
    /**
     * @Rest\Get("/vehicles/id/{id}")
     * @Rest\Get("/vehicles/id/{id}/")
     */
    public function getVehiclesById(Request $request)
    {
        $id = $request->get('id');

        $swapi_url = "http://swapi.co/api/vehicles/$id";
        $self_url = "/vehicles/id/$id";
        $cache = $this->get('cache');

        if ($cache->get("/vehicles/id/$id")) {
            $response['status'] = Response::HTTP_OK;
            $response['data'] = $cache->get($self_url);
        } else {

            $curl_handler = $this->get('curl_request');
            $response = $curl_handler->request($swapi_url);

            if ($response['status'] == 200) {

                // Reformat API URLs in JSON and add images
                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');

                $replacer->replace('films', 'films', $response);
                $replacer->replace('pilots', 'people', $response);

                $replacer->replace('url', 'vehicles', $response, false, true);

                
                $response['data']['image'] = $crawler->getImage($response['data']['name']);
            }


            $cache->save($self_url, $response['data']);
        }        


        $view = $this->view($response['data'], $response['status']);
        
        return $view; 
    } 
}