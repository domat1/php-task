<?php
$router = array();

$router['news'] = function() {
echo '<p>news page</p>';
};

$router['newsid'] = function() {
echo '<p>news page</p>';
};

$uri = 'newsid';

if(isset($router[$uri])) {
	$router[$uri]();
}
else {
	echo '<h1>Error not found</h1>';
}

?>