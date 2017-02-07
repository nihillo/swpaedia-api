<?php

namespace AppBundle\Services;

class Cache {
	private $db_server;
	private $db_user;
	private $db_pass;
	private $db_name;

	public function __construct($db_host, $db_name, $db_user, $db_pass) {
		$this->db_server = $db_host;
		$this->db_user = $db_user;
		$this->db_pass = $db_pass;
		$this->db_name = $db_name;

		echo $this->db_pass;
	}

	// public function isCached($request) {
	// 	// ToDo

	// 	return false;
	// }

	public function save($request, $response) {

		$response = json_encode($response, false);

		$mysqli = new \mysqli($this->db_server, $this->db_user, $this->db_pass, $this->db_name);

		if ($mysqli->connect_errno) {
		    die("Database connection failed");
		}

		$response = $mysqli->real_escape_string($response);

		$sql = "INSERT INTO cache(request, response) VALUES ('$request', '$response');";

		if ($mysqli->query($sql) === TRUE) {
			// echo "Request cached succesfully";
		} else {
			echo "Database operation error: " . $mysqli->error;
		}

		$mysqli->close();
	}

	public function get($request) {
		$mysqli = new \mysqli($this->db_server, $this->db_user, $this->db_pass, $this->db_name);

		if ($mysqli->connect_errno) {
		    die("Database connection failed");
		}


		$sql = "SELECT response FROM cache WHERE request = '$request';";

		$result = $mysqli->query($sql);

		if ($result && $result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		        $response = $row['response'];
		    }
		} else {
		   	$response = false;
		}


		$mysqli->close();

		if ($response) {
			$response = json_decode($response, true);
		}

		return $response;
	}
}