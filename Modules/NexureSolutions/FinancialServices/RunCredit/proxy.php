<?php

    $url = "https://www.experian.com/ncaconline/creditreport?type=declined";
    $proxiedContent = file_get_contents("http://127.0.0.1:3000/?url=" . urlencode($url));
    echo $proxiedContent;

?>
