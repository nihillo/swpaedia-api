<?php

namespace AppBundle\Controller;
 
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 

class StarshipsController extends FOSRestController {
    /**
     * @Rest\Get("/starships")
     * @Rest\Get("/starships/")
     */
    public function getStarships(Request $request)
    {   

        $swapi_url = "http://swapi.co/api/starships";
        $self_url = "/starships";
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
                    $response['data']['next'] = '/starships/page/' . $next_page;
                }
                

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');
                for ($i=0; $i<count($response['data']['results']); $i++) { 

                    $replacer->replace('films', 'films', $response, $i);
                    $replacer->replace('pilots', 'people', $response, $i);

                    $replacer->replace('url', 'starships', $response, $i, true);

                    $response['data']['results'][$i]['image'] = $crawler->getImage($response['data']['results'][$i]['name']);
                }
            }
            
            

            $cache->save($self_url, $response['data']);
        }
        
        $view = $this->view($response['data'], $response['status']);
        return $view; 
    }

    /**
     * @Rest\Get("/starships/page/{pageNo}")
     * @Rest\Get("/starships/page/{pageNo}/")
     */
    public function getStarshipsPaginated(Request $request)
    {
        $pageNo = $request->get('pageNo');

        $swapi_url = "http://swapi.co/api/starships?page=$pageNo";
        $self_url = "/starships/page/$pageNo";

        $cache = $this->get('cache');

        if ($cache->get("/starships/page/$pageNo")) {
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
                    $response['data']['next'] = '/starships/page/' . $next_page;
                }

                if ($previous_page) {
                    $response['data']['previous'] = '/starships/page/' . $previous_page;
                }

                $crawler = $this->get('crawler');
                $replacer = $this->get('url_replacer');
                for ($i=0; $i<count($response['data']['results']); $i++) { 

                    $replacer->replace('films', 'films', $response, $i);
                    $replacer->replace('pilots', 'people', $response, $i);
                    
                    $replacer->replace('url', 'starships', $response, $i, true);

                    $response['data']['results'][$i]['image'] = $crawler->getImage($response['data']['results'][$i]['name']);
                }
            }
            
            

            $cache->save($self_url, $response['data']);
        }
        
        $view = $this->view($response['data'], $response['status']);
        return $view;  
 
    }
 
    /**
     * @Rest\Get("/starships/id/{id}")
     * @Rest\Get("/starships/id/{id}/")
     */
    public function getStarshipsById(Request $request)
    {
        $id = $request->get('id');

        $swapi_url = "http://swapi.co/api/starships/$id";
        $self_url = "/starships/id/$id";
        $cache = $this->get('cache');

        if ($cache->get("/starships/id/$id")) {
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

                $replacer->replace('url', 'starships', $response, false, true);

                
                $response['data']['image'] = $crawler->getImage($response['data']['name']);
            }


            $cache->save($self_url, $response['data']);
        }        


        $view = $this->view($response['data'], $response['status']);
        
        return $view; 
    } 
}