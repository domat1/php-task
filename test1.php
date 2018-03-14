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
    header('Content-Type: application/json');
    echo json_encode($result[1]);
}

$domat = get_news($connection);

?>