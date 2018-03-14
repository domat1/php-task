<?php
include("./connection.php");
$db = new Connection();
$connection =  $db->konche();
$request_method=$_SERVER["REQUEST_METHOD"];

function get_news($connection)
{
    $news = $connection->prepare('SELECT id, title, date, text FROM news');
    $news->execute();
    $result = $news->FetchAll(PDO::FETCH_ASSOC);
    foreach( $result as $all ){
        print_r( $all );
    }
    header('Content-Type: application/json');
    echo json_encode($all);
}

$domat = get_news($connection);

?>