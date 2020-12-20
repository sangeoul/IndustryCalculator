<?php

include $_SERVER['DOCUMENT_ROOT']."/CorpESI/shrimp/phplib.php";
dbset();
header("Content-Type: application/json");
/*
if(isset($_GET["typeid"])){
    $_GET["type_id"]=$_GET["typeid"];
}
*/
$qr="select item_from_name, item_from_id, item_from_quantity,item_to_quantity,item_to_name,relation_type from Industry_Relation where relation_type>1 and item_to_id=".$_GET["type_id"]." order by item_from_quantity desc";

//echo($qr);
$result=$dbcon->query($qr);

$return_array=array();

for($i=0;$i<$result->num_rows;$i++){
    
    $data=$result->fetch_row();
    if($i==0){
        $return_array["name"]=$data[4];
        $return_array["output"]=intval($data[3]);
        $return_array["relation_type"]=intval($data[5]);
        $return_array["materials"]=array();
    }
    $return_array["materials"][$i]=array();
    $return_array["materials"][$i]["name"]=$data[0];
    $return_array["materials"][$i]["type_id"]=intval($data[1]);
    $return_array["materials"][$i]["quantity"]=intval($data[2]);

    /*
    //바이가 쿼리 / 셀가 쿼리 조성
    $bqr="select price from Industry_Marketorders where typeid=".$return_array["materials"][$i]["type_id"]." and quantity>0 and is_buy_order=1 order by time desc, price desc limit 2";
    $sqr="select price from Industry_Marketorders where typeid=".$return_array["materials"][$i]["type_id"]." and quantity>0 and is_buy_order=0 order by time desc, price asc limit 2";
    
    $bresult=$dbcon->query($bqr);
    $sresult=$dbcon->query($sqr);

    if($bresult && $bresult->num_rows>0){
        $price=$bresult->fetch_row();
        $return_array["materials"][$i]["buy_price"]=floatval($price[0]);
    }
    else{
        $return_array["materials"][$i]["buy_price"]=0.0;
    }

    if($sresult && $sresult->num_rows>0){
        $price=$sresult->fetch_row();
        $return_array["materials"][$i]["sell_price"]=floatval($price[0]);
    }
    else{
        $return_array["materials"][$i]["sell_price"]=0.0;
    }
    */
    
}

echo(json_encode($return_array));
//var_dump($return_array);

?>