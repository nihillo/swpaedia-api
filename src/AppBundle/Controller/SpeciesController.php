<?php

namespace AppBundle\Controller;
 
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 

class SpeciesController extends FOSRestController {
    /**
     * @Rest\Get("/species")
     * @Rest\Get("/species/")
     */
    public function getSpecies(Request $request)
    {   

        $swapi_url = "http://swapi.co/api/species";
        $self_url = "/species";
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
                    $response['data']['next'] = '/species/page/' . $next_page;
                }
                

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');
                for ($i=0; $i<count($response['data']['results']); $i++) { 

                    $replacer->replace('films', 'films', $response, $i);
                    $replacer->replace('people', 'people', $response, $i);

                    $replacer->replace('url', 'species', $response, $i, true);

                    $response['data']['results'][$i]['image'] = $crawler->getImage($response['data']['results'][$i]['name']);
                }
            }
            
            

            $cache->save($self_url, $response['data']);
        }
        
        $view = $this->view($response['data'], $response['status']);
        return $view; 
    }

    /**
     * @Rest\Get("/species/page/{pageNo}")
     * @Rest\Get("/species/page/{pageNo}/")
     */
    public function getSpeciesPaginated(Request $request)
    {
        $pageNo = $request->get('pageNo');

        $swapi_url = "http://swapi.co/api/species?page=$pageNo";
        $self_url = "/species/page/$pageNo";

        $cache = $this->get('cache');

        if ($cache->get("/species/page/$pageNo")) {
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
                    $response['data']['next'] = '/species/page/' . $next_page;
                }

                if ($previous_page) {
                    $response['data']['previous'] = '/species/page/' . $previous_page;
                }

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');
                for ($i=0; $i<count($response['data']['results']); $i++) { 

                    $replacer->replace('films', 'films', $response, $i);
                    $replacer->replace('people', 'people', $response, $i);

                    $replacer->replace('url', 'species', $response, $i, true);

                    $response['data']['results'][$i]['image'] = $crawler->getImage($response['data']['results'][$i]['name']);
                }
            }
            
            

            $cache->save($self_url, $response['data']);
        }
        
        $view = $this->view($response['data'], $response['status']);
        return $view;  
 
    }
 
    /**
     * @Rest\Get("/species/id/{id}")
     * @Rest\Get("/species/id/{id}/")
     */
    public function getSpeciesById(Request $request)
    {
        $id = $request->get('id');

        $swapi_url = "http://swapi.co/api/species/$id";
        $self_url = "/species/id/$id";
        $cache = $this->get('cache');

        if ($cache->get("/species/id/$id")) {
            $response['status'] = Response::HTTP_OK;
            $response['data'] = $cache->get($self_url);
        } else {

            $curl_handler = $this->get('curl_request');
            $response = $curl_handler->request($swapi_url);

            if ($response['status'] == 200) {

                // Reformat API URLs in JSON and add images
                
                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');

                $replacer->replace('people', 'people', $response);
                // $replacer->replace('planets', 'planets', $response);
                // $replacer->replace('vehicles', 'vehicles', $response);
                // $replacer->replace('starships', 'starships', $response);
                $replacer->replace('films', 'films', $response);
                $replacer->replace('url', 'species', $response, false, true);

                
                $response['data']['image'] = $crawler->getImage($response['data']['name']);
            }


            $cache->save($self_url, $response['data']);
        }        


        $view = $this->view($response['data'], $response['status']);
        
        return $view; 
    } 
}