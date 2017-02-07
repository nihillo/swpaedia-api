<?php

namespace AppBundle\Controller;
 
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 

class PeopleController extends FOSRestController {
    /**
     * @Rest\Get("/people")
     * @Rest\Get("/people/")
     */
    public function getPeople(Request $request)
    {   

        $swapi_url = "http://swapi.co/api/people";
        $self_url = "/people";
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
                    $response['data']['next'] = '/people/page/' . $next_page;
                }
                

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');
                for ($i=0; $i<count($response['data']['results']); $i++) { 

                    $replacer->replace('films', 'films', $response, $i);
                    $replacer->replace('species', 'species', $response, $i);
                    $replacer->replace('vehicles', 'vehicles', $response, $i);
                    $replacer->replace('starships', 'starships', $response, $i);
                    $replacer->replace('homeworld', 'planets', $response, $i, true);
                    $replacer->replace('url', 'people', $response, $i, true);

                    $response['data']['results'][$i]['image'] = $crawler->getImage($response['data']['results'][$i]['name']);
                }
            }
            
            

            $cache->save($self_url, $response['data']);
        }
        
        $view = $this->view($response['data'], $response['status']);
        return $view; 
    }

    /**
     * @Rest\Get("/people/page/{pageNo}")
     * @Rest\Get("/people/page/{pageNo}/")
     */
    public function getPeoplePaginated(Request $request)
    {
        $pageNo = $request->get('pageNo');

        $swapi_url = "http://swapi.co/api/people?page=$pageNo";
        $self_url = "/people/page/$pageNo";

        $cache = $this->get('cache');

        if ($cache->get("/people/page/$pageNo")) {
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
                    $response['data']['next'] = '/people/page/' . $next_page;
                }

                if ($previous_page) {
                    $response['data']['previous'] = '/people/page/' . $previous_page;
                }

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');
                for ($i=0; $i<count($response['data']['results']); $i++) { 

                    $replacer->replace('films', 'films', $response, $i);
                    $replacer->replace('species', 'species', $response, $i);
                    $replacer->replace('vehicles', 'vehicles', $response, $i);
                    $replacer->replace('starships', 'starships', $response, $i);
                    $replacer->replace('homeworld', 'planets', $response, $i, true);
                    $replacer->replace('url', 'people', $response, $i, true);

                    $response['data']['results'][$i]['image'] = $crawler->getImage($response['data']['results'][$i]['name']);
                }
            }
            
            

            $cache->save($self_url, $response['data']);
        }
        
        $view = $this->view($response['data'], $response['status']);
        return $view;  
 
    }
 
    /**
     * @Rest\Get("/people/id/{id}")
     * @Rest\Get("/people/id/{id}/")
     */
    public function getPeopleById(Request $request)
    {
        $id = $request->get('id');

        $swapi_url = "http://swapi.co/api/people/$id";
        $self_url = "/people/id/$id";
        $cache = $this->get('cache');

        if ($cache->get("/people/id/$id")) {
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
                $replacer->replace('species', 'species', $response);
                $replacer->replace('vehicles', 'vehicles', $response);
                $replacer->replace('starships', 'starships', $response);
                $replacer->replace('homeworld', 'planets', $response, false, true);
                $replacer->replace('url', 'people', $response, false, true);

                
                $response['data']['image'] = $crawler->getImage($response['data']['name']);
            }


            $cache->save($self_url, $response['data']);
        }        


        $view = $this->view($response['data'], $response['status']);
        
        return $view; 
    } 
}