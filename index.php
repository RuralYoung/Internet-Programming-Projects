<?php

$EMAIL_ID = 294077619; // 9-digit integer value (i.e., 123456789)
$API_KEY = "eca46789"; // API key (string) provided by Open Movie DataBase (i.e., "ab123456")

session_start(); // Connect to the existing session

require_once '/home/common/php/dbInterface.php'; // Add database functionality
require_once '/home/common/php/mail.php'; // Add email functionality
require_once '/home/common/php/p4Functions.php'; // Add Project 4 base functions

processPageRequest(); // Call the processPageRequest() function

// DO NOT REMOVE OR MODIFY THE CODE OR PLACE YOUR CODE ABOVE THIS LINE

function addMovieToCart($imdbID)
{
    $movieId = movieExistsinDB($imdbID);

    if($movieId == 0) {
        $result= file_get_contents('http://www.omdbapi.com/?apikey='.$GLOBALS['API_KEY'].'&i='.$imdbID.'&type=movie&r=json');
        $movieInfo = json_decode($result, true);

        $imdbId = $movieInfo['imdbID'];
        $title = $movieInfo['Title'];
        $year = $movieInfo['Year'];
        $rating = $movieInfo['Rated'];
        $runtime = $movieInfo['Runtime'];
        $genre = $movieInfo['Genre'];
        $actors = $movieInfo['Actors'];
        $director = $movieInfo['Director'];
        $writer = $movieInfo['Writer'];
        $plot = $movieInfo['Plot'];
        $poster = $movieInfo['Poster'];

        $movieId = addMovie($imdbId, $title, $year, $rating, $runtime, $genre, $actors, $director, $writer, $plot, $poster);
    }

    $userId = $_SESSION['userId'];

    addMovieToShoppingCart($userId, $movieId);
    displayCart();
}

function displayCart()
{
    $userId = $_SESSION['userId'];

    $movies = getMoviesInCart($userId);

    $count = count($movies);

    require_once "./templates/cart_form.html";
}

function processPageRequest()
{
	if(empty($_SESSION["displayName"])) {
        header("Location: ./logon.php");
    }

	if(empty($_GET['action'])) {
	    displayCart();
    }

	elseif($_GET['action'] == "add") {

	    $imdbID = $_GET['imdb_id'];
	    addMovieToCart($imdbID);
	    displayCart();

    }
    elseif($_GET['action'] == "remove") {
	    $movieID = $_GET['movie_id'];
	    removeMovieFromCart($movieID);
	    displayCart();
    }

}

function removeMovieFromCart($movieID)
{
    $userId = $_SESSION['userId'];

    removeMovieFromShoppingCart($userId, $movieID);

    displayCart();
}

?>