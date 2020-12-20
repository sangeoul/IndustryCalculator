<?php

include $_SERVER["DOCUMENT_ROOT"]."/CorpESI/shrimp/phplib.php";
dbset();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

    
//바이가 쿼리 / 셀가 쿼리 조성
$bqr="select price from Industry_Marketorders where typeid=".$_GET["typeid"]." and quantity>0 and is_buy_order=1 order by time desc, price desc limit 2";
$sqr="select price from Industry_Marketorders where typeid=".$_GET["typeid"]." and quantity>0 and is_buy_order=0 order by time desc, price asc limit 2";

$bresult=$dbcon->query($bqr);
$sresult=$dbcon->query($sqr);
$return_array=array();

if($bresult && $bresult->num_rows>0){
    $price=$bresult->fetch_row();
    $return_array["buy"]=floatval($price[0]);
}
else{
    $return_array["buy"]=0.0;
}
if($sresult && $sresult->num_rows>0){
    $price=$sresult->fetch_row();
    $return_array["sell"]=floatval($price[0]);
}
else{
    $return_array["sell"]=0.0;
}

echo(json_encode($return_array));
//var_dump($return_array);

?>