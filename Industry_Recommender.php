

<html>
    <head>
    <title>Recommand</title>
    </head>

    <body>
    Hou many do You have?
    <table>
    <form method="GET" action="./Industry_Recommender.php">
    <tr>
        <td>Tritanium</td>
        <td><input type=number id=tritanium name="tritanium" value=1 ></td>
    </tr>
    <tr>
        <td>Pyerite</td>
        <td><input type=number id="pyerite" name="pyerite" value=0 ></td>
    </tr>
    <tr>
        <td>Mexallon</td>
        <td><input type=number id="mexallon" name="mexallon" value=0 ></td>
    </tr>
    <tr>
        <td>Isogen</td>
        <td><input type=number id="isogen" name="isogen" value=0 ></td>
    </tr>
    <tr>
        <td>Nocxium</td>
        <td><input type=number id="nocxium" name="nocxium" value=0 ></td>
    </tr>
    <tr>
        <td>Zydrine</td>
        <td><input type=number id="zydrine" name="zydrine" value=0 ></td>
    </tr>
    <tr>
        <td>Megacyte</td>
        <td><input type=number id="megacyte" name="megacyte" value=0 ></td>
    </tr>
    <tr>
        <td>Minimum ISK</td>
        <td><input type=number id="isk" name="isk" value=0 ></td>
    </tr>
    <tr>
        <td></td>
        <td><input type=submit value="SEXSEX"></td>
    </tr>

    </form>
    </table>
    <textarea id="inputarea" name="inputarea" onchange="javascript:parse_minerals();"></textarea><Br>

    </body>
</html>
<?php

include $_SERVER['DOCUMENT_ROOT']."/CorpESI/shrimp/phplib.php";

dbset();


$material_list=array(34,35,36,37,38,39,40);
$buyprice=array();

$have=array();

for($i=0;$i<sizeof($material_list);$i++){
    $bqr="select price from Industry_Marketorders where typeid=".$material_list[$i]." and quantity>0 and is_buy_order=1 order by time desc, price desc limit 2";
    $result=$dbcon->query($bqr);
    
    if($result->num_rows>0){
        $price=$result->fetch_row();
        $buyprice["".$material_list[$i]]=$price[0];
        
        
        
        
    }
    //마켓이 뒤져서 디버그용
    /*
    $buyprice["34"]=6.56;
    $buyprice["35"]=5;
    $buyprice["36"]=60;
    $buyprice["37"]=18;
    $buyprice["38"]=352;
    $buyprice["39"]=551;
    $buyprice["40"]=391;
    */
    /*
    else{
        errordebug($bqr);
    }
    */
    
}



class Product{
    public $materials=array();
    public $typeid;
    public $name;
    public $inner_vector;

    function __construct($typeid,$name){
        $this->typeid=$typeid;
        $this->name=$name;
        $this->inner_vector=0;
        for($i=0;$i<sizeof($material_list);$i++){
            $this->materials["".$material_list[$i]]=0;
        }

    }

}

if(isset($_GET["tritanium"])){
    

    if($_GET["isk"]==""){
        return $_GET["isk"]=0;
    }
    

    $have=array();

    $have["34"]=$_GET["tritanium"];
    $have["35"]=$_GET["pyerite"];
    $have["36"]=$_GET["mexallon"];
    $have["37"]=$_GET["isogen"];
    $have["38"]=$_GET["nocxium"];
    $have["39"]=$_GET["zydrine"];
    $have["40"]=$_GET["megacyte"];
    
    $hvl=0;
    for($k=0;$k<sizeof($material_list);$k++){
        if($k==0){
            $hvl=0;
        }
        $hvl+=$have["".$material_list[$k]]*$have["".$material_list[$k]]*$buyprice["".$material_list[$k]]*$buyprice["".$material_list[$k]];
    }
    $hvl=sqrt($hvl);
    $product=array();
    
    //7종 미네랄 외에 다른 것들이 들어가지 않는지 검사한다.
    $qr1="select item_to_id,count(*) as s from Industry_Relation 
    where relation_type=2 and item_from_id<41 and item_from_id>33
    group by item_to_id";
    
    $result=$dbcon->query($qr1);
    
    
    for($i=0,$j=0;$i<$result->num_rows;$i++){
     
    
        $data1=$result->fetch_row();
        $qr2="select item_to_id,count(*) as s,ANY_VALUE(item_to_name) from Industry_Relation 
        where relation_type=2 and item_to_id=".$data1[0]."
        group by item_to_id";
        $data2= $dbcon->query($qr2)->fetch_row();

        //7종 미네랄로만 이루어져있으면.
        if($data1[1]==$data2[1]){
            
            $qr="select * from Industry_Relation where relation_type=2 and item_to_id=".$data1[0];
            $mresult=$dbcon->query($qr);
            $product[$j]=new Product($data2[0],$data2[2]);
            for($k=0;$k<$mresult->num_rows;$k++){
                $data= $mresult->fetch_array();
                $product[$j]->materials["".$data["item_from_id"]]=$data["item_from_quantity"];
            }
            $product[$j]->inner_vector=0;
            $pvl=array();
            
            
            for($k=0;$k<sizeof($material_list);$k++){
                if($k==0){
                    $pvl[$j]=0;
                }
                $pvl[$j]+=$product[$j]->materials["".$material_list[$k]]*$product[$j]->materials["".$material_list[$k]]*$buyprice["".$material_list[$k]]*$buyprice["".$material_list[$k]];
                
            }
            
            $pvl[$j]=sqrt($pvl[$j]);
            for($k=0;$k<sizeof($material_list);$k++){
                $product[$j]->inner_vector+=$product[$j]->materials["".$material_list[$k]]*$have["".$material_list[$k]]*$buyprice["".$material_list[$k]]*$buyprice["".$material_list[$k]]/($pvl[$j]*$hvl);
                //echo($product[$j]->inner_vector."<br>\n");
            }
            $j++;
            
        }
    }
    for($i=0;$i<sizeof($product);$i++){
        //echo($product[$i]->inner_vector."<Br>\n");
    }
    
    //정렬한다
    for($i=0;$i<sizeof($product);$i++){
        for($j=($i+1);$j<sizeof($product);$j++){
            
            if($product[$i]->inner_vector < $product[$j]->inner_vector){
                
                $p=clone $product[$i];
                $product[$i]= clone $product[$j];
                $product[$j]= clone $p;
                
            }
            
        }
    }
    
    for($i=0;$i<sizeof($product);$i++){
        
        if($product[$i]->inner_vector > $_GET["isk"]*$_GET["isk"] ){
            echo($product[$i]->name." : ".number_format($product[$i]->inner_vector,4)."<br>\n");
        }
        
    }
    
}


?>

<script>

function parse_minerals(){
    var rawstr=document.getElementById("inputarea").value;
    var strarray=rawstr.split(/\r\n|\r|\n/);

    for(var i=0;i<strarray.length;i++){
        strarray[i]=strarray[i].split("\t");
        strarray[i][0]=strarray[i][0].replace(/ /g,"");
        strarray[i][1]=strarray[i][1].replace(/ /g,"");
        strarray[i][1]=strarray[i][1].replace(/,/g,"");
        //alert(strarray[i][0]+" : "+strarray[i][1])
    }
    for(var i=0;i<strarray.length;i++){
        switch(strarray[i][0]){
            case "Tritanium":
                document.getElementById("tritanium").value=strarray[i][1];
                break;
            case "Pyerite":
                document.getElementById("pyerite").value=strarray[i][1];
                break;
            case "Mexallon":
                document.getElementById("mexallon").value=strarray[i][1];
                break;
            case "Isogen":
                document.getElementById("isogen").value=strarray[i][1];
                break;
            case "Nocxium":
                document.getElementById("nocxium").value=strarray[i][1];
                break;
            case "Zydrine":
                document.getElementById("zydrine").value=strarray[i][1];
                break;
            case "Megacyte":
                document.getElementById("megacyte").value=strarray[i][1];
                break;

        }
    }
}
</script>