<?php
	// Connect to database
	include("db_connect.php");
	$request_method = $_SERVER["REQUEST_METHOD"];

	if ($request_method == "POST" and isset($_POST['createButton']))
	{
		addUser();
	}

	function getUsers()
	{
		global $conn;
		$query = "SELECT * FROM user";
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	
	function getUser($id=0)
	{
		global $conn;
		$query = "SELECT * FROM user";
		if($id != 0)
		{
			$query .= " WHERE id=".$id." LIMIT 1";
		}
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	
	function addUser()
	{
		global $conn;
		$username = $_POST["username"];
		$pseudo = $_POST["pseudo"];
		$created_at = date('Y-m-d H:i:s');
		$email = $_POST["email"];
		$pswd = $_POST["password"];
		echo $query="INSERT INTO user(username, pseudo, created_at, email, password) VALUES('".$username."', '".$pseudo."', '".$created_at."','".$email."','".$pswd."')";
		if ($conn->connect_errno) 
		{
			echo "echec lors de la connexion a MySQL" . $conn->connect_error;
		}
		if (!$conn->query($query))
		{
			echo "echec lors de la creation de la table : (" . $conn->errno . ") " . $conn->error;
		}
		header('Content-Type: application/json');
		
		echo json_encode($response);
	}
	
	function updateUser($id)
	{
		global $conn;
		$_PUT = array();
		parse_str(file_get_contents('php://input'), $_PUT);
		$username = $_PUT["username"];
		$pseudo = $_PUT["pseudo"];
		$created_at = 'NULL';
		$query="UPDATE user SET username='".$username."', pseudo='".$pseudo."',email='".$email."' WHERE id=".$id;
		
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'user mis a jour avec succes.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'Echec de la mise a jour de user. '. mysqli_error($conn)
			);
			
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	function deleteUser($id)
	{
		global $conn;
		$query = "DELETE FROM user WHERE id=".$id;
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'user supprime avec succes.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'La suppression du user a echoue. '. mysqli_error($conn)
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}

	
	
/*	
	switch($request_method)
	{
		
		case 'GET':
			// Retrive Users
			if(!empty($_GET["id"]))
			{
				$id=intval($_GET["id"]);
				getUser($id);
			}
			else
			{
				getUsers();
			}
			break;
		default:
			// Invalid Request Method
			header("HTTP/1.0 405 Method Not Allowed");
			break;
			
		case 'POST':
			// Ajouter un user
			AddUser();
			break;
			
		case 'PUT':
			// Modifier un user
			$id = intval($_GET["id"]);
			updateUser($id);
			break;
			
		case 'DELETE':
			// Supprimer un user
			$id = intval($_GET["id"]);
			deleteUser($id);
			break;
	}
*/
?>