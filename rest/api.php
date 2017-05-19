<?php

	require_once("Rest.inc.php");
	
	class API extends REST {
	
		public $data = "";
		
		const DB_SERVER = "localhost";
		const DB_USER = "root";
		const DB_PASSWORD = "";
		const DB = "rest_api";
		
		private $db = NULL;
	
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Database connection 
		*/
		private function dbConnect(){
			$this->db = mysqli_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD,self::DB);
			
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  user_name : <user_name>
		 *  pwd : <USER PASSWORD>
		 */
		
		private function login(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$user_name = addslashes($this->_request['user_name']);		
			$password = addslashes($this->_request['pwd']);
			
			// Input validations
			if(!empty($user_name) and !empty($password)){
				
					$sql = mysqli_query($this->db,"SELECT user_id, user_name, user_pwd FROM users WHERE user_name = '$user_name' AND user_pwd = '".md5($password)."' LIMIT 1");
					if(mysqli_num_rows($sql) > 0){
						$result = mysqli_fetch_array($sql);
						
						// If success everythig is good send header as "OK" and user details
						$this->response($this->json($result), 200);
					}
					$error = array('status' => "Failed", "msg" => "Invalid user name address or Password"); 
					$this->response($error, 204);	// If no records "No Content" status
				
			}
			
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Invalid user name address or Password");
			$this->response($this->json($error), 400);
		}
		
		private function work_list(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$user_id = addslashes($this->_request['user_id']);
			$sql = mysqli_query($this->db,"SELECT * from work WHERE user_id = '$user_id'");
			if(mysqli_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysqli_fetch_array($sql)){
					$result[] = $rlt;
				}
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		private function update_score(){
			// Cross validation if the request method is DELETE else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$work_id = addslashes($this->_request['work_id']);
			$score = addslashes($this->_request['score']);
			if($work_id > 0){				
				mysqli_query($this->db,"update work set work_score='$score' WHERE work_id = '$work_id'");
				$success = array('status' => "Success", "msg" => "Successfully one record updated.");
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// If no records "No Content" status
		}
		
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();
?>