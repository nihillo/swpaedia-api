<?php

namespace AppBundle\Controller;
 
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 

class FilmsController extends FOSRestController {
    /**
     * @Rest\Get("/films")
     * @Rest\Get("/films/")
     */
    public function getFilms(Request $request)
    {   

        $swapi_url = "http://swapi.co/api/films";
        $self_url = "/films";
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
                    $response['data']['next'] = '/films/page/' . $next_page;
                }
                

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');
                for ($i=0; $i<count($response['data']['results']); $i++) { 

                    $replacer->replace('characters', 'people', $response, $i);
                    $replacer->replace('planets', 'planets', $response, $i);
                    $replacer->replace('vehicles', 'vehicles', $response, $i);
                    $replacer->replace('starships', 'starships', $response, $i);
                    $replacer->replace('species', 'species', $response, $i);

                    $replacer->replace('url', 'films', $response, $i, true);

                    $response['data']['results'][$i]['image'] = $crawler->getImage($response['data']['results'][$i]['title']);
                }
            }
            
            

            $cache->save($self_url, $response['data']);
        }
        
        $view = $this->view($response['data'], $response['status']);
        return $view; 
    }

    /**
     * @Rest\Get("/films/page/{pageNo}")
     * @Rest\Get("/films/page/{pageNo}/")
     */
    public function getFilmsPaginated(Request $request)
    {
        $pageNo = $request->get('pageNo');

        $swapi_url = "http://swapi.co/api/films?page=$pageNo";
        $self_url = "/films/page/$pageNo";

        $cache = $this->get('cache');

        if ($cache->get("/films/page/$pageNo")) {
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
                    $response['data']['next'] = '/films/page/' . $next_page;
                }

                if ($previous_page) {
                    $response['data']['previous'] = '/films/page/' . $previous_page;
                }

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');
                for ($i=0; $i<count($response['data']['results']); $i++) { 

                    $replacer->replace('characters', 'people', $response, $i);
                    $replacer->replace('planets', 'planets', $response, $i);
                    $replacer->replace('vehicles', 'vehicles', $response, $i);
                    $replacer->replace('starships', 'starships', $response, $i);
                    $replacer->replace('species', 'species', $response, $i);

                    $replacer->replace('url', 'films', $response, $i, true);

                    $response['data']['results'][$i]['image'] = $crawler->getImage($response['data']['results'][$i]['title']);
                }
            }
            
            

            $cache->save($self_url, $response['data']);
        }
        
        $view = $this->view($response['data'], $response['status']);
        return $view;  
 
    }
 
    /**
     * @Rest\Get("/films/id/{id}")
     * @Rest\Get("/films/id/{id}/")
     */
    public function getFilmsById(Request $request)
    {
        $id = $request->get('id');

        $swapi_url = "http://swapi.co/api/films/$id";
        $self_url = "/films/id/$id";
        $cache = $this->get('cache');

        if ($cache->get("/films/id/$id")) {
            $response['status'] = Response::HTTP_OK;
            $response['data'] = $cache->get($self_url);
        } else {

            $curl_handler = $this->get('curl_request');
            $response = $curl_handler->request($swapi_url);

            if ($response['status'] == 200) {

                // Reformat API URLs in JSON and add images
                
                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');

                $replacer->replace('characters', 'people', $response);
                $replacer->replace('planets', 'planets', $response);
                $replacer->replace('vehicles', 'vehicles', $response);
                $replacer->replace('starships', 'starships', $response);
                $replacer->replace('species', 'species', $response);
                $replacer->replace('url', 'films', $response, false, true);

                
                $response['data']['image'] = $crawler->getImage($response['data']['title']);
            }


            $cache->save($self_url, $response['data']);
        }        


        $view = $this->view($response['data'], $response['status']);
        
        return $view; 
    } 
}