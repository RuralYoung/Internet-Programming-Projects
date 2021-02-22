<?php

$EMAIL_ID = 294077619; // 9-digit integer value (i.e., 123456789)

require_once '/home/common/php/dbInterface.php'; // Add database functionality
require_once '/home/common/php/mail.php'; // Add email functionality
require_once '/home/common/php/p4Functions.php'; // Add Project 4 base functions

processPageRequest(); // Call the processPageRequest() function

// DO NOT REMOVE OR MODIFY THE CODE OR PLACE YOUR CODE ABOVE THIS LINE

function authenticateUser($username, $password) 
{
	$temp = validateUser($username, $password);

	if (!empty($temp)){
		session_start();

		$_SESSION["userId"] = $temp['ID'];
		$_SESSION["displayName"] = $temp['DisplayName'];
		$_SESSION["emailAddress"] = $temp['Email'];

		return true;
	}
	else {
		return false;
	}
}

function displayLoginForm($message = "")
{
	require_once './templates/logon_form.html';
}

function processPageRequest()
{
	// DO NOT REMOVE OR MODIFY THE CODE OR PLACE YOUR CODE BELOW THIS LINE
	if(session_status() == PHP_SESSION_ACTIVE)
	{
		session_destroy();
	}
	// DO NOT REMOVE OR MODIFY THE CODE OR PLACE YOUR CODE ABOVE THIS LINE

	if (empty($_POST['username']) || empty($_POST['password']))
		displayLoginForm();

	if(isset($_POST['action'])) {
		if ($_POST['action'] == 'login') {

			$username = $_POST['username'];
			$password = $_POST['password'];

			if (authenticateUser($username, $password)) {
				header("Location: index.php");
			} else {
				$message = "Error! Username or Password was incorrect!";
				displayLoginForm($message);
			}
		}
	}
}
?>
