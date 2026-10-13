<?php


require '../../../wp-load.php';


$returnurl = site_url().'/?wc-api=meezanresponse';


echo ("<script>window.top.location='$returnurl'</script>");


?>