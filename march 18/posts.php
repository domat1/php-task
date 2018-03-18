<?php
echo "Starting...";
//set POST variables
$url = 'localhost/news.php';
$fields = array(
    'title'=>urlencode('laina'),
    'date'=>urlencode('0000-00-00 00:00:00'),
    'text'=>urlencode('tekst laina'),
    "id"=>urlencode('21')
);
$fields_string = '';
//url-ify the data for the POST
foreach($fields as $key=>$value) {
    $fields_string .= $key.'='.$value.'&';
}
$field_string = rtrim($fields_string,'&');
echo "Opening connection...";
//open connection
// nachi tuk krashva
$ch = curl_init();
echo "Setting options...";
//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
echo "Sending request...";
//execute post
$result = curl_exec($ch);
echo "Closing connection...";
//close connection
curl_close($ch);

echo "Bye!";
echo $fields_string;