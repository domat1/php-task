<?php

try {
    $db = new PDO('mysql:host=localhost;dbname=test', 'root', 'usbw');
} catch (PDOException $e) {
    echo $e->getMessage()."<br>";
    die();
}
$sql = 'SELECT id, text, title, date from news';
foreach( $db->query($sql) as $row ) {
    $json_array[] = $row;
}
echo json_encode($json_array);
?>