swpaedia-api
============
SWpaedia API is an intermediate API to serve informations about Star Wars universe. It uses [SWAPI](https://swapi.co/) as main data source, adds additional data taken from [Wookieepedia](http://starwars.wikia.com) via web crawling, reestructurates its endpoints architecture and caches results in local database to reduce requests to both sites.

It is done using Symfony PHP framework.

## Demo

A working demo can be found at [https://swpaedia-api.herokuapp.com](https://swpaedia-api.herokuapp.com)

## Installation

First you will need to create a MySQL database. This database contains a table called 'cache' with two columns:
- request varchar(50) PRIMARY KEY
- response text

Clone or download the project, copy app/config/parameters.yml.dist to app/config.parameters.yml, configure in it your database settings, and run on a LAMP server (needs PHP >= 5.5.9)


## Endpoints

All endpoints use GET method.

- /people: Returns 10 first elements in characters list
- /people/page/{page}: Returns other elements in characters list. Pagination step 10 elements
- /people/id/{id}: Returns character by ID
- /planets: Returns 10 first elements in planets list
- /planets/page/{page}: Returns other elements in planets list. Pagination step 10 elements
- /planets/id/{id}: Returns planet by ID
- /films: Returns 10 first elements in films list
- /films/page/{page}: Returns other elements in films list. Pagination step 10 elements
- /films/id/{id}: Returns film by ID
- /species: Returns 10 first elements in species list
- /species/page/{page}: Returns other elements in species list. Pagination step 10 elements
- /species/id/{id}: Returns species by ID
- /vehicles: Returns 10 first elements in vehicles list
- /vehicles/page/{page}: Returns other elements in vehicles list. Pagination step 10 elements
- /vehicles/id/{id}: Returns vehicle by ID
- /starships: Returns 10 first elements in starships list
- /starships/page/{page}: Returns other elements in starships list. Pagination step 10 elements
- /starships/id/{id}: Returns starship by ID
