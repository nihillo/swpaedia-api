<?php

namespace AppBundle\Controller;
 
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 

class PlanetsController extends FOSRestController {
    /**
     * @Rest\Get("/planets")
     * @Rest\Get("/planets/")
     */
    public function getPlanets(Request $request)
    {   

        $swapi_url = "http://swapi.co/api/planets";
        $self_url = "/planets";
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
                    $response['data']['next'] = '/planets/page/' . $next_page;
                }
                

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');
                for ($i=0; $i<count($response['data']['results']); $i++) { 
                    $replacer->replace('residents', 'people', $response, $i);
                    $replacer->replace('films', 'films', $response, $i);
                    $replacer->replace('url', 'planets', $response, $i, true);


                    $response['data']['results'][$i]['image'] = $crawler->getImage($response['data']['results'][$i]['name']);
                }
            }
            
            

            $cache->save($self_url, $response['data']);
        }
        
        $view = $this->view($response['data'], $response['status']);
        return $view; 
    }

    /**
     * @Rest\Get("/planets/page/{pageNo}")
     * @Rest\Get("/planets/page/{pageNo}/")
     */
    public function getPlanetsPaginated(Request $request)
    {
        $pageNo = $request->get('pageNo');

        $swapi_url = "http://swapi.co/api/planets?page=$pageNo";
        $self_url = "/planets/page/$pageNo";

        $cache = $this->get('cache');

        if ($cache->get("/planets/page/$pageNo")) {
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
                    $response['data']['next'] = '/planets/page/' . $next_page;
                }

                if ($previous_page) {
                    $response['data']['previous'] = '/planets/page/' . $previous_page;
                }

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');
                for ($i=0; $i<count($response['data']['results']); $i++) { 
                    $replacer->replace('residents', 'people', $response, $i);
                    $replacer->replace('films', 'films', $response, $i);
                    $replacer->replace('url', 'planets', $response, $i, true);


                    $response['data']['results'][$i]['image'] = $crawler->getImage($response['data']['results'][$i]['name']);
                }
            }
            
            

            $cache->save($self_url, $response['data']);
        }
        
        $view = $this->view($response['data'], $response['status']);
        return $view;  
 
    }
 
    /**
     * @Rest\Get("/planets/id/{id}")
     * @Rest\Get("/planets/id/{id}/")
     */
    public function getPlanetsById(Request $request)
    {
        $id = $request->get('id');

        $swapi_url = "http://swapi.co/api/planets/$id";
        $self_url = "/planets/id/$id";
        $cache = $this->get('cache');

        if ($cache->get("/planets/id/$id")) {
            $response['status'] = Response::HTTP_OK;
            $response['data'] = $cache->get($self_url);
        } else {

            $curl_handler = $this->get('curl_request');
            $response = $curl_handler->request($swapi_url);

            if ($response['status'] == 200) {

                // Reformat API URLs in JSON and add images

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');

                $replacer->replace('residents', 'people', $response);
                $replacer->replace('films', 'films', $response);
                $replacer->replace('url', 'planets', $response, false, true);

                $response['data']['image'] = $crawler->getImage($response['data']['name']);
            }


            $cache->save($self_url, $response['data']);
        }        


        $view = $this->view($response['data'], $response['status']);
        
        return $view; 
    } 
}