<html>
<head>
<title>
    Industry Calculator</title>
</head>
<body>

<?php


include $_SERVER['DOCUMENT_ROOT']."/CorpESI/shrimp/phplib.php";

dbset();


//당분간 공개
$lastdata=array("anonymous",1,"-");

logincheck();
/*
$qr="select username,calculator1,datediff(calculator1 , UTC_TIMESTAMP) as last from Shrimp_permission where userid=".$_SESSION["shrimp_userid"]." and calculator1>UTC_TIMESTAMP";

$result=$dbcon->query($qr);
if($result->num_rows==0){
    errorhome("Your Character : ".$_SESSION["shrimp_username"]." cannot access this page.");
    $_GET["type"]=-1;
}
else{
    $lastdata=$result->fetch_row();
}
*/
if(!isset($_GET["type"])){
    $_GET["type"]=2;
}
switch ($_GET["type"]){
    case "bp":
        $_GET["type"]=2;
    break;
    case "blueprint":
        $_GET["type"]=2;
    break;
    case "BP":
        $_GET["type"]=2;
    break;
    case "Blueprint":
        $_GET["type"]=2;
    break;
    case "PI":
        $_GET["type"]=3;
    break;
    case "pi":
        $_GET["type"]=3;
    break;
    case "Pi":
        $_GET["type"]=3;
    break;
    case "Reaction":
        $_GET["type"]=4;
    break;
    case "reaction":
        $_GET["type"]=4;
    break;
    case "rea":
        $_GET["type"]=4;
    break;
    case "ra":
        $_GET["type"]=4;
    break;
}

?>

<style>
table{
    border-collapse:collapse;
}
td,th{
    border:1px solid black;
    border-collapse:collapse;
}
</style>



<select id="blueprint" name="blueprint" onchange="javascript:loadBlueprint();"> 
    <option value=0 default>-</option>
<?php
    $qr="select distinct item_to_id,item_to_name from Industry_Relation where relation_type=".$_GET["type"]." order by item_to_name asc";
    $result=$dbcon->query($qr);
    for($i=0;$i<$result->num_rows;$i++){
        $blueprintdata=$result->fetch_row();
        echo("<option value=".$blueprintdata[0].">".$blueprintdata[1]."</option>\n");
    }
?>
</select> <?php echo($lastdata[0]."남은 기간 : ".$lastdata[2]." days");?><br>
<div id="options">
<?php
    if($_GET["type"]==2){
        echo("Run : <input type=number value=1 min=1 step=1 id=item_run onchange=\"javascript:calctable();\"> (<span id=\"runq\">1</span> unit(s) per run)<br>\n");
        echo("M.E Bonus : - <input type=number value=10.0 min=0 max=10 step=1 id=\"me_bonus\" onchange=\"javascript:calctable();\">% <br>\n");
        echo("Rig Bonus : - <input type=number value=3.8 min=0 max=10 step=0.1 id=\"rig_bonus\" onchange=\"javascript:calctable();\">% <br>\n");
        echo("Structure Bonus : - <input type=number value=1 min=0 max=10 step=0.1 id=\"str_bonus\" onchange=\"javascript:calctable();\">% <br>\n");
    }
    else if($_GET["type"]==3){
        echo("Run : <input type=number value=1 min=1 step=1 id=item_run onchange=\"javascript:calctable();\"> (<span id=\"runq\">1</span> unit(s) per run)<br>\n");
        echo("Custom Office Tax : <input type=number value=10.0 min=0 max=80 step=0.1 id=\"poco_tax\" onchange=\"javascript:calctable();\">% <br>\n");
    }
    else if($_GET["type"]==4){
        echo("Run : <input type=number value=1 min=1 step=1 id=item_run onchange=\"javascript:calctable();\"> (<span id=\"runq\">1</span> unit(s) per run)<br>\n");
        echo("Rig Bonus : - <input type=number value=3.8 min=0 max=10 step=0.1 id=\"rig_bonus\" onchange=\"javascript:calctable();\">% <br>\n");
        echo("Structure Bonus : - <input type=number value=1 min=0 max=10 step=0.1 id=\"str_bonus\" onchange=\"javascript:calctable();\">% <br>\n");
    }

?>

</div>
<div id="material_table">

</div>


<script>

var materials=new Array();
var outputperrun=1;
var item_id;

function getMarketPrice(type_id,order_type){
    
    
    var DBdata=new XMLHttpRequest();
    var jsondata;
    DBdata.onreadystatechange=function(){

        if (this.readyState == XMLHttpRequest.DONE){
            
            jsondata=JSON.parse(this.responseText);
                
        }

        
    }

    DBdata.open("GET","./get_market_data.php?typeid="+type_id,false);
    DBdata.send();  
    
    if(order_type=="buy"||order_type==1){
        return jsondata.buy;
    }     
    if(order_type=="sell"||order_type==0){
        return jsondata.sell;
    } 

}
function loadBlueprint(){
    
    item_id=document.getElementById("blueprint").value;

    var DBdata=new XMLHttpRequest();

    DBdata.onreadystatechange=function(){

        if (this.readyState == XMLHttpRequest.DONE){
            
            //alert(this.responseText);
            var jsondata=JSON.parse(this.responseText);
            outputperrun=jsondata.output;
            materials=jsondata["materials"];

            showtable();
            calctable();
                
        }

        
    }

    DBdata.open("GET","./get_blueprint_data.php?type_id="+item_id+"&relation_type=<?=$_GET["type"]?>",false);
    DBdata.send();        

}

function showtable(){
    
    var resultstring="<table>\n<tr>\n";
    
    resultstring+="<th>item</th><th>quantity</th><th>buy price</th><th>sell price</th>";
    resultstring+="</tr>";

    var item_run=document.getElementById("item_run").value;

    if(<?=($_GET["type"]==2?1:0)?>){
        
        var me_rate=(100.0-parseFloat(document.getElementById("me_bonus").value))/100;
        var rig_rate=(100.0-parseFloat(document.getElementById("rig_bonus").value))/100;
        var str_rate=(100.0-parseFloat(document.getElementById("str_bonus").value))/100;

        var eff_rate=me_rate*rig_rate*str_rate;

        var tcb=0.0,tcs=0.0;
        var tmb=0.0,tms=0.0;
        tmb=getMarketPrice(item_id,"buy");
        tms=getMarketPrice(item_id,"sell");

        document.getElementById("runq").innerHTML=outputperrun;

        var modified_materials=new Array();


        for(var i=0;i<materials.length;i++){
            
            modified_materials[i]=Math.max(1,eff_rate*materials[i].quantity);
            
            resultstring+="<tr>";
            resultstring+="<td><span id=\"name"+i+"\">"+materials[i].name+"</span></td>";
            resultstring+="<td><span id=\"quantity"+i+"\">"+number_format(Math.ceil(modified_materials[i]*item_run))+"</span></td>";
            resultstring+="<td><span id=\"buyprice"+i+"\">"+number_format(materials[i].buy_price*Math.ceil(modified_materials[i]*item_run),2)+"</span></td>";
            resultstring+="<td><span id=\"sellprice"+i+"\">"+number_format(materials[i].sell_price*Math.ceil(modified_materials[i]*item_run),2)+"</span></td>";
            resultstring+="</tr>";
            tcb+=parseFloat(materials[i].buy_price*Math.ceil(modified_materials[i]*item_run));
            tcs+=parseFloat(materials[i].sell_price*Math.ceil(modified_materials[i]*item_run));
            
        }

        resultstring+="<tr><td></td><td>Cost</td><td><span id=\"tcb\">"+number_format(tcb,2)+"</span></td><td><span id=\"tcs\">"+number_format(tcs,2)+"</span></td>";
        resultstring+="<tr><td></td><td>Market</td><td><span id=\"tmb\">"+number_format(tmb*outputperrun*item_run,2)+"</span></td><td><span id=\"tms\">"+number_format(tms*outputperrun*item_run,2)+"</span></td>";
        resultstring+="</table>";


        document.getElementById("material_table").innerHTML=resultstring;
    }
    
    
    else if(<?=($_GET["type"]==3)?1:0?>){
        //alert("DEBUG");
        var poco_tax=parseFloat(document.getElementById("poco_tax").value);

        var tcb=0.0,tcs=0.0;
        var tmb=0.0,tms=0.0;
        tmb=getMarketPrice(item_id,"buy");
        tms=getMarketPrice(item_id,"sell");

        document.getElementById("runq").innerHTML=outputperrun;

        var modified_materials=new Array();


        for(var i=0;i<materials.length;i++){
            
            modified_materials[i]=materials[i].quantity;
            
            resultstring+="<tr>";
            resultstring+="<td><span id=\"name"+i+"\">"+materials[i].name+"</span></td>";
            resultstring+="<td><span id=\"quantity"+i+"\">"+number_format(Math.ceil(modified_materials[i]*item_run))+"</span></td>";
            resultstring+="<td><span id=\"buyprice"+i+"\">"+number_format(materials[i].buy_price*Math.ceil(modified_materials[i]*item_run),2)+"</span></td>";
            resultstring+="<td><span id=\"sellprice"+i+"\">"+number_format(materials[i].sell_price*Math.ceil(modified_materials[i]*item_run),2)+"</span></td>";
            resultstring+="</tr>";
            tcb+=parseFloat(materials[i].buy_price*Math.ceil(modified_materials[i]*item_run));
            tcs+=parseFloat(materials[i].sell_price*Math.ceil(modified_materials[i]*item_run));
            
        }
        resultstring+="<tr><td></td><td>Price+Tax</td><td><span id=\"tcb_tax\">"+number_format(tcb,2)+"+"+number_format(tcb*poco_tax*1.5/100,2)+"</span></td><td><span id=\"tcs_tax\">"+number_format(tcs,2)+"+"+number_format(tcs*poco_tax*1.5/100,2)+"</span></td>";
        resultstring+="<tr><td></td><td>Cost</td><td><span id=\"tcb\">"+number_format(tcb+(tcb*poco_tax*1.5/100),2)+"</span></td><td><span id=\"tcs\">"+number_format(tcs+(tcs*poco_tax*1.5/100),2)+"</span></td>";
        resultstring+="<tr><td></td><td>Market</td><td><span id=\"tmb\">"+number_format(tmb*outputperrun*item_run,2)+"</span></td><td><span id=\"tms\">"+number_format(tms*outputperrun*item_run,2)+"</span></td>";
        resultstring+="</table>";


        document.getElementById("material_table").innerHTML=resultstring;
    }
    else if(<?=($_GET["type"]==4?1:0)?>){
        
        var rig_rate=(100.0-parseFloat(document.getElementById("rig_bonus").value))/100;
        var str_rate=(100.0-parseFloat(document.getElementById("str_bonus").value))/100;

        var eff_rate=rig_rate*str_rate;

        var tcb=0.0,tcs=0.0;
        var tmb=0.0,tms=0.0;
        tmb=getMarketPrice(item_id,"buy");
        tms=getMarketPrice(item_id,"sell");

        document.getElementById("runq").innerHTML=outputperrun;

        var modified_materials=new Array();


        for(var i=0;i<materials.length;i++){
            
            modified_materials[i]=Math.max(1,eff_rate*materials[i].quantity);
            
            resultstring+="<tr>";
            resultstring+="<td><span id=\"name"+i+"\">"+materials[i].name+"</span></td>";
            resultstring+="<td><span id=\"quantity"+i+"\">"+number_format(Math.ceil(modified_materials[i]*item_run))+"</span></td>";
            resultstring+="<td><span id=\"buyprice"+i+"\">"+number_format(materials[i].buy_price*Math.ceil(modified_materials[i]*item_run),2)+"</span></td>";
            resultstring+="<td><span id=\"sellprice"+i+"\">"+number_format(materials[i].sell_price*Math.ceil(modified_materials[i]*item_run),2)+"</span></td>";
            resultstring+="</tr>";
            tcb+=parseFloat(materials[i].buy_price*Math.ceil(modified_materials[i]*item_run));
            tcs+=parseFloat(materials[i].sell_price*Math.ceil(modified_materials[i]*item_run));
            
        }

        resultstring+="<tr><td></td><td>Cost</td><td><span id=\"tcb\">"+number_format(tcb,2)+"</span></td><td><span id=\"tcs\">"+number_format(tcs,2)+"</span></td>";
        resultstring+="<tr><td></td><td>Market</td><td><span id=\"tmb\">"+number_format(tmb*outputperrun*item_run,2)+"</span></td><td><span id=\"tms\">"+number_format(tms*outputperrun*item_run,2)+"</span></td>";
        resultstring+="</table>";


        document.getElementById("material_table").innerHTML=resultstring;
    }




}

function calctable(){

    
    var item_run=document.getElementById("item_run").value;

    if(<?=($_GET["type"]==2?1:0)?>){
        var me_rate=(100.0-parseFloat(document.getElementById("me_bonus").value))/100;
        var rig_rate=(100.0-parseFloat(document.getElementById("rig_bonus").value))/100;
        var str_rate=(100.0-parseFloat(document.getElementById("str_bonus").value))/100;


        var eff_rate=me_rate*rig_rate*str_rate;
        var tcb=0.0,tcs=0.0;
        var tmb=0.0,tms=0.0;
        tmb=getMarketPrice(item_id,"buy");
        tms=getMarketPrice(item_id,"sell");

        var modified_materials=new Array();

        for(var i=0;i<materials.length;i++){
            
            
            modified_materials[i]=Math.max(1.0,materials[i].quantity*eff_rate);  
            document.getElementById("quantity"+i).innerHTML=number_format(Math.ceil(modified_materials[i]*item_run));
            document.getElementById("buyprice"+i).innerHTML=number_format(materials[i].buy_price*Math.ceil(modified_materials[i]*item_run),2);
            document.getElementById("sellprice"+i).innerHTML=number_format(materials[i].sell_price*Math.ceil(modified_materials[i]*item_run),2);
            
            tcb+=parseFloat(materials[i].buy_price*Math.ceil(modified_materials[i]*item_run));
            tcs+=parseFloat(materials[i].sell_price*Math.ceil(modified_materials[i]*item_run));     
            
        }
        document.getElementById("tcb").innerHTML=number_format(tcb,2);
        document.getElementById("tcs").innerHTML=number_format(tcs,2);
        document.getElementById("tmb").innerHTML=number_format(tmb*outputperrun*item_run,2);
        document.getElementById("tms").innerHTML=number_format(tms*outputperrun*item_run,2);    
    }

    else if(<?=($_GET["type"]==3?1:0)?>){

        var poco_tax=parseFloat(document.getElementById("poco_tax").value);

        var tcb=0.0,tcs=0.0;
        var tmb=0.0,tms=0.0;
        tmb=getMarketPrice(item_id,"buy");
        tms=getMarketPrice(item_id,"sell");

        var modified_materials=new Array();
        
        for(var i=0;i<materials.length;i++){
            
            
            modified_materials[i]=materials[i].quantity;  
            document.getElementById("quantity"+i).innerHTML=number_format(Math.ceil(modified_materials[i]*item_run));
            document.getElementById("buyprice"+i).innerHTML=number_format(materials[i].buy_price*Math.ceil(modified_materials[i]*item_run),2);
            document.getElementById("sellprice"+i).innerHTML=number_format(materials[i].sell_price*Math.ceil(modified_materials[i]*item_run),2);
            
            tcb+=parseFloat(materials[i].buy_price*Math.ceil(modified_materials[i]*item_run));
            tcs+=parseFloat(materials[i].sell_price*Math.ceil(modified_materials[i]*item_run));     
            
        }
        //alert("DEBUG");
        document.getElementById("tcb_tax").innerHTML=number_format(tcb,2)+"+"+number_format(tcb*poco_tax*1.5/100,2);
        document.getElementById("tcs_tax").innerHTML=number_format(tcs,2)+"+"+number_format(tcs*poco_tax*1.5/100,2);       
        document.getElementById("tcb").innerHTML=number_format(tcb+(tcb*poco_tax*1.5/100),2);
        document.getElementById("tcs").innerHTML=number_format(tcs+(tcs*poco_tax*1.5/100),2);

        document.getElementById("tmb").innerHTML=number_format(tmb*outputperrun*item_run,2);
        document.getElementById("tms").innerHTML=number_format(tms*outputperrun*item_run,2);    
    }
    else if(<?=($_GET["type"]==4?1:0)?>){

        var rig_rate=(100.0-parseFloat(document.getElementById("rig_bonus").value))/100;
        var str_rate=(100.0-parseFloat(document.getElementById("str_bonus").value))/100;


        var eff_rate=rig_rate*str_rate;
        var tcb=0.0,tcs=0.0;
        var tmb=0.0,tms=0.0;
        tmb=getMarketPrice(item_id,"buy");
        tms=getMarketPrice(item_id,"sell");

        var modified_materials=new Array();

        for(var i=0;i<materials.length;i++){
            
            
            modified_materials[i]=Math.max(1.0,materials[i].quantity*eff_rate);  
            document.getElementById("quantity"+i).innerHTML=number_format(Math.ceil(modified_materials[i]*item_run));
            document.getElementById("buyprice"+i).innerHTML=number_format(materials[i].buy_price*Math.ceil(modified_materials[i]*item_run),2);
            document.getElementById("sellprice"+i).innerHTML=number_format(materials[i].sell_price*Math.ceil(modified_materials[i]*item_run),2);
            
            tcb+=parseFloat(materials[i].buy_price*Math.ceil(modified_materials[i]*item_run));
            tcs+=parseFloat(materials[i].sell_price*Math.ceil(modified_materials[i]*item_run));     
            
        }
        document.getElementById("tcb").innerHTML=number_format(tcb,2);
        document.getElementById("tcs").innerHTML=number_format(tcs,2);
        document.getElementById("tmb").innerHTML=number_format(tmb*outputperrun*item_run,2);
        document.getElementById("tms").innerHTML=number_format(tms*outputperrun*item_run,2);    
    }
}

function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

</script>

</body>
</html>