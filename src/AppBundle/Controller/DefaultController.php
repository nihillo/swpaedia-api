<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;



class DefaultController extends FOSRestController
{
    /**
     * @Rest\Get("/")
     */
    public function indexAction(Request $request)
    {
    	echo $this->container->getParameter('database_host');
        $data = [
        	'endpoints' => array(
	        	'/people' => 'GET // Returns 10 first elements in characters list',
	        	'/people/page/{page}' => 'GET // Returns other elements in characters list. Pagination step 10 elements.',
	        	'/people/id/{id}'  => 'GET // Returns character by ID. Pagination step 10 elements.',
	        	'/planets' => 'GET // Returns 10 first elements in planets list',
	        	'/planets/page/{page}' => 'GET // Returns other elements in planets list. Pagination step 10 elements.',
	        	'/planets/id/{id}'  => 'GET // Returns planet by ID. Pagination step 10 elements.',
	        	'/films' => 'GET // Returns 10 first elements in films list',
	        	'/films/page/{page}' => 'GET // Returns other elements in films list. Pagination step 10 elements.',
	        	'/films/id/{id}'  => 'GET // Returns film by ID. Pagination step 10 elements.',
	        	'/species' => 'GET // Returns 10 first elements in species list',
	        	'/species/page/{page}' => 'GET // Returns other elements in species list. Pagination step 10 elements.',
	        	'/species/id/{id}'  => 'GET // Returns species by ID. Pagination step 10 elements.',
	        	'/vehicles' => 'GET // Returns 10 first elements in vehicles list',
	        	'/vehicles/page/{page}' => 'GET // Returns other elements in vehicles list. Pagination step 10 elements.',
	        	'/vehicles/id/{id}'  => 'GET // Returns vehicle by ID. Pagination step 10 elements.',
	        	'/starships' => 'GET // Returns 10 first elements in starships list',
	        	'/starships/page/{page}' => 'GET // Returns other elements in starships list. Pagination step 10 elements.',
	        	'/starships/id/{id}'  => 'GET // Returns starship by ID. Pagination step 10 elements.',
        	)
        ];
        $view = $this->view($data, Response::HTTP_OK);
        return $view;
    }
}