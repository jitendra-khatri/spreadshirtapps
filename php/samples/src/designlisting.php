<?php
    // This script shows you how to retrieve an design list using Spreadshirt API v1
    // and render it with html.

    $query = isset($_POST['query']) ? $_POST['query'] : '';
    $query = isset($_GET['query']) ? $_GET['query'] : $query;

    // 1. Get shop
    $shopUrl = "http://api.spreadshirt.net/api/v1/shops/205909";
    $ch = curl_init($shopUrl);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $result = curl_exec($ch);
    // Close the handle
    curl_close($ch);

    $shop = new SimpleXMLElement($result);
    $namespaces = $shop->getNamespaces(true);

    // 2. Get designs
    $attributes = $shop->designs->attributes($namespaces['xlink']);
    $designsUrl = $attributes->href;
    $ch = curl_init($designsUrl . "?limit=100&query=". $query);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $result = curl_exec($ch);
    // Close the handle
    curl_close($ch);

    $designs = new SimpleXMLElement($result);

    // 3. Print design list
    echo '<html>';
    echo '<head>';
    echo '<title>Design List Rendering Example</title>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
    echo '</head>';
    echo '<body>';
    echo '<div>';
    echo '<form action="designlisting.php">';
    echo '<input type="text" id="query" name="query" value="' . $query . '" style="font-size: 15px; font-weight: bold; width: 400px;"/><input type="submit" value="Search"/>';
    echo '</form></div>';
    echo '<div>';
    foreach ($designs->design as $design) {
        $resource = $design->resources->resource[0];
        $attributes = $resource->attributes($namespaces['xlink']);
        echo '<div style="float: left; width: 150px; height: 150px;"><img src="' . $attributes->href . '?width=150&height=150" width="150" height="150"/></div>';
    }
    echo '</div>';
    echo '</body>';
    echo '</html>';
?>