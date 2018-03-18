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
function debug_query( $data ) {
    $logFile = 'debugQuery.log';
    file_put_contents($logFile, $data);
}

function debug_request() {
    $logFile = 'debugRequest.log';
    $getData = "\r\n";
    $postData = "\r\n";
    $requestData = "\r\n";
    foreach( $_GET as $key => $value ){ $getData .= $key . " : " . $value . "\r\n"; }
    foreach( $_POST as $key => $value ) { $postData .= $key . " : " . $value . "\r\n"; }
    foreach( $_REQUEST as $key => $value ) { $requestData .= $key . " : " . $value . "\r\n"; }
    $logInfo = "=== GET DATA\n\r";
    $logInfo .= $getData;
    $logInfo .= "\r\n\r\n=== POST DATA\n\r";
    $logInfo .= $postData;
    $logInfo .= "\r\n\r\n=== REQUEST DATA\n\r";
    $logInfo .= $requestData;
    file_put_contents($logFile, $logInfo);
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
        $news = $connection->prepare( 'SELECT id, title, date, text FROM news WHERE id=' . sanitize_numbers( $id ) );
        $news->execute();
        $result = $news->FetchAll( PDO::FETCH_OBJ );
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
function update_news ( $connection, $id, $text, $title, $date )
{
    $tvoitoid = 15;
    $query = "UPDATE news SET title='" . $title . "', text='" . $text . "', date='" . $date . "' WHERE id='" . $tvoitoid . "'";
    $news = $connection->prepare($query);
    debug_request();
    debug_query($query);
    $news->execute();
}




$date = $_POST['date'];
$id = $_POST['id'];
$title = $_POST['title'];
$text = $_POST ['text'];



$benom = update_news ( $connection, $id, $text, $title, $date );



?>