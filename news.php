<?php
// Requires
require("./Connection.php");

// Variables
$db = new Connection();
$connection = $db->create_connection();
$request_method = $_SERVER["REQUEST_METHOD"];
$inProduction = FALSE; // Set to TRUE if in production.
header( 'Content-Type: application/json' );

// Helper functions
function output( $data ) {
    echo json_encode( $data );
}
function sanitize_numbers( $input ) {
    $maxLength = 255;
    if ( ( !$inProduction ) && ( strlen( $input ) > $maxLength ) ) output( [ 'Error' => 'Input lenght is greater than 255!' ] );
    if ( ( !$inProduction ) && ( preg_match( '/[^0-9]/', $input ) ) ) output( [ 'Error' => 'Input contains illegal characters!' ] );
    return substr( preg_replace( '/[^0-9]/', '', $input ), 0, $maxLength );
}

// API functions
function get_news( $connection ) {
    try {
        $news = $connection->prepare( 'SELECT id, title, date, text FROM news' );
        $news->execute();
        $result = $news->FetchAll( PDO::FETCH_ASSOC );
        foreach( $result as $all ){
            output( $all );
        }
    } catch ( PDOException $e ) {
        if ( !$inProduction ) {
            throw new pdoDbException( $e );
        } else {
            output( [ 'Error occured' => 'Please try again!' ] );
        }
    }
}

function get_news_by_id( $connection, $id ) {
    try {
        $news = $connection->prepare( 'SELECT id, title, date, text FROM news WHERE id= '. sanitize_numbers( $id ) );
        $news->execute();
        $result = $news->FetchAll( PDO::FETCH_ASSOC );
        if ( empty ($result) ) {
            return output( [ 'Error' => 'No results.' ] );
        }
        foreach( $result as $all ){
            output( $all );
        }
    } catch ( PDOException $e ) {
        if ( !$inProduction ) {
            throw new pdoDbException( $e );
        } else {
            output( [ 'Error occured' => 'Please try again!' ] );
        }
    }
}

function post_news_by_id( $connection )
{
    try {

        if (isset($_POST['title']) && isset($_GET['id'])) {
            $title = $_POST['title'];
            $text = $_POST['text'];
            $date = $_POST['date'];
            $news = $connection->prepare("UPDATE news SET title = '" . $title . "', text ='" . $text . "', date = '" . $date . "' WHERE id = " . $_GET['id']);
            $news->execute();
        }
        $result = $news;
        if ($result != 0) {
            $result = array('success' => 1);
            return $result;
            output($result);
        };
    } catch (PDOException $e) {
        if (!$inProduction) {
            throw new pdoDbException($e);
        } else {
            output(['Error occured' => 'Please try again!']);
        }
    }
}
// Testing
// $domat = get_news( $connection );
$id = $_GET['id'];
$benom = post_news_by_id( $connection )




?>