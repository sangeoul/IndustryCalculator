<html>
<head>
<?php

include $_SERVER['DOCUMENT_ROOT']."/CorpESI/shrimp/phplib.php";
dbset();
session_start();
//logincheck();


?>
<title>
    Industry Calculator
</title>

<script src="https://lindows.kr/jslib.js"></script>

<style>
table{
    border-collapse:collapse;
}
td,th{
    border:0px solid black;
    border-collapse:collapse;
}
table.bonustable td{
    border:0px solid white;
}

img.bonus{
    width:24px;
    height:24px;
}

td.expand_bonus:hover{
    background-color: rgb(200,250,230);

}

span.expand_bonus_triangle{
    padding-left:4px;
    padding-right:4px;
    font-size:17px;
}
table.set_bonus_table td{
    border:0px solid white;
}
input.bonus_input{
    width:50px;
}
table.itemtable td{
    background-color: rgb(255,255,255);
}
table.included_itemtable td{
    background-color: rgb(210,250,230);
}
table.selected_itemtable td{
    background-color: rgb(160,180,220);
}
table.searching_itemtable td{
    background-color: #FCF4AC;
}
td.itemtable{
    border:1px solid black;
}

td.item_icon, td.item_name, td.item_quantity,td.set_bonus,td.show_material{
border-top:1px solid black;
}
td.item_icon{
    width:32px;
}
td.item_name{
    width:164px;
    max-width:164px;

}
td.set_bonus{
    width:20px;
}

td.show_material{
    border-bottom:1px solid black; 
    width:24px;  
}
td.item_buyprice, td.item_sellprice{

}
td.item_customprice{
    border-bottom:1px solid black;
}
td.selectbuy,td.selectsell{

}
td.selectcustom{
    border-bottom:1px solid black;   
}
img.item_icon{
    width:32px;
}
img.set_bonus,img.show_material{
    width:20px;
}
span.item_name,span.item_quantity,span.item_buyprice,span.item_sellprice,span.item_customprice{
    font-size:12px;

}
span.item_name{
    width:164px;
    max-width:164px;

}

input.item_customprice{

}

td.listed_item{
    background-color:rgb(255,255,255);
}

td.selected_listed_item{
    background-color: #FCF4AC;
}


div.adv1,div.adv2{
    width:95%;
    height:80px;
    
    border:2px solid green;
}
div.adv2{
    margin-top:20px;
}
div.adv3{
    float:left;
    margin-right:20px;
    height:60%;
    width:100px;
    top:300px;
    left:0px;
    border:2px solid green;
}

</style>
<?=$analytics?>
<!-- 구글애드센스-->
<script data-ad-client="ca-pub-7625490600882004" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
<body onload="javascript:bodyload()">



<?php
//당분간 공개
$lastdata=array("anonymous",1,"-");

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
$tmp_userid="";
$tmp_username="";
if(isset($_SESSION['shrimp_userid'])){
    $tmp_userid=$_SESSION['shrimp_userid'];
}
if(isset($_SESSION['shrimp_username'])){
    $tmp_username=$_SESSION["shrimp_username"];
}
$dbcon->query("insert into iplogs (userid,username,ip,registered,page) values (".$tmp_userid.",'".$tmp_username."','".$_SERVER['REMOTE_ADDR']."',NOW(),\"".$_SERVER["REQUEST_URI"]."\");");

?>
<!--
<div class="adv1">
    광고문의 Lindows Hakurei
</div>-->
<div id="settingdiv">

    Search : <input list="blueprint_d" id="bpdatalist" onchange="javascript:loadBlueprint('datalist');">
    <datalist id="blueprint_d" name="blueprint_d" style="size:300px;">
    <?php
        //Industry_Relation : 
        $qr="select distinct item_to_id,item_to_name from Industry_Relation where relation_type>1 order by item_to_name asc";
        $result=$dbcon->query($qr);
        for($i=0;$i<$result->num_rows;$i++){
            $blueprintdata[$i]=$result->fetch_row();
            echo("<option value=\"".$blueprintdata[$i][1]."\">\n");
        }    
    ?>
    </datalist>
    </input><br/>
    <select id="blueprint" name="blueprint" onchange="javascript:loadBlueprint();"> 
        <option value=0 default>-</option>
    <?php

        for($i=0;$i<$result->num_rows;$i++){

            echo("<option value=".$blueprintdata[$i][0].">".$blueprintdata[$i][1]."</option>\n");
        }
    ?>
    </select>
    
    Run : <input type=number value=1 min=1 step=1 id=item_run onchange="javascript:updateAmount();"> (<span id="runq">1</span> unit(s) per run)
    <br>
    <table class="bonustable">
    <tr>
        <td class="expand_bonus"><span id="expand_bonus_triangle" class="expand_bonus_triangle" onclick="javascript:toggle_bonus_table()">Default▶</span></td>
        <td class="bonus"><img src="./images/ma_mebonus.png" class="bonus"></td>
        <td><span id="ma_mebonus" class="bonus"></span>%　　</td>
        <td class="bonus"><img src="./images/ma_rigbonus.png" class="bonus"></td>
        <td><span id="ma_rigbonus" class="bonus"></span>%　　</td>
        <td class="bonus"><img src="./images/ma_structurebonus.png" class="bonus"></td>
        <td><span id="ma_structurebonus" class="bonus"></span>%　　</td>
        <td class="bonus"><img src="./images/re_rigbonus.png" class="bonus"></td>
        <td><span id="re_rigbonus" class="bonus"></span>%　　</td>
        <td class="bonus"><img src="./images/re_structurebonus.png" class="bonus"></td>
        <td><span id="re_structurebonus" class="bonus"></span>%　　</td>
    </tr>
    </table>
    <table class="set_bonus_table" id="set_bonus_table" style="display:none">
    <tr>
        <td>Manufacturing M.E Bonus</td>
        <td>- <input type=number value=10.0 min=0 max=10 step=1 id="ma_me_bonus" onchange="javascript:setbonus();" class="bonus_input">%</td>
        <!-- There is no ME bonus at Reaction
        <td>M.E Bonus</td>
        <td>- <input type=number value=10.0 min=0 max=10 step=0.1 id="re_me_bonus" onchange="javascript:calctable();">%</td>-->
        <td></td><td></td>
    </tr>
    <tr>
        <td>Manufacturing Rig Bonus</td>
        <td>- <input type=number value=4.2 min=0 max=10 step=0.1 id="ma_rig_bonus" onchange="javascript:setbonus();" class="bonus_input">%</td>
        <td>Reaction Rig Bonus</td>
        <td>- <input type=number value=2.2 min=0 max=10 step=0.1 id="re_rig_bonus" onchange="javascript:setbonus();" class="bonus_input">%</td>
    </tr>
    <tr>
        <td>Manufacturing Structure Role Bonus</td>
        <td>- <input type=number value=1.0 min=0 max=10 step=0.1 id="ma_structure_bonus" onchange="javascript:setbonus();" class="bonus_input">%</td>
        <td>Reaction Structure Role Bonus</td>
        <td>- <input type=number value=0.0 min=0 max=10 step=0.1 id="re_structure_bonus" onchange="javascript:setbonus();" class="bonus_input">%</td>
    </tr>
    </table>
</div>
<!--<div class="adv2">
</div>-->



<script>
var BPsearchdata=new Array();

</script>


<script>

//보너스 설정 테이블을 켜거나 끌 수 있게 한다.
var display_bonus_table=0;
var debug_performance=0;

var KEYDOWN_SHIFT=false;

const TYPE_MANUFACTURING=2;
const TYPE_REACTION=3;
const TYPE_PI=4;

var default_ma_mebonus,default_ma_rigbonus,default_ma_structurebonus,default_re_rigbonus,default_re_structurebonus;

function bodyload(){
    setbonus();
}

document.body.onkeydown=function(e){
    if(e.keyCode==16){
        KEYDOWN_SHIFT=true;
        console.log("SHIFT DOWN");
    }   
};
document.body.onkeyup=function(e){
    if(e.keyCode==16){
        KEYDOWN_SHIFT=false;
        console.log("SHIFT UP");
    }
    
};


function toggle_bonus_table(){

    if(display_bonus_table){
        document.getElementById("set_bonus_table").style.display="none";
        document.getElementById("expand_bonus_triangle").innerHTML="Default▶";
        updateAmount();
        display_bonus_table=0;
    }
    else{
        document.getElementById("set_bonus_table").style.display="block";
        document.getElementById("expand_bonus_triangle").innerHTML="Default▼"
        display_bonus_table=1;        
    }
    

}
function setbonus(){

    document.getElementById("ma_mebonus").innerHTML=document.getElementById("ma_me_bonus").value;
    document.getElementById("ma_rigbonus").innerHTML=document.getElementById("ma_rig_bonus").value;
    document.getElementById("ma_structurebonus").innerHTML=document.getElementById("ma_structure_bonus").value;
    document.getElementById("re_rigbonus").innerHTML=document.getElementById("re_rig_bonus").value;
    document.getElementById("re_structurebonus").innerHTML=document.getElementById("re_structure_bonus").value;

    default_ma_mebonus=document.getElementById("ma_me_bonus").value;
    default_ma_rigbonus=document.getElementById("ma_rig_bonus").value;
    default_ma_structurebonus=document.getElementById("ma_structure_bonus").value;
    default_re_rigbonus=document.getElementById("re_rig_bonus").value;
    default_re_structurebonus=document.getElementById("re_structure_bonus").value;
    
}

</script>

<?php 
//echo($lastdata[0]."남은 기간 : ".$lastdata[2]." days");
?>
<br>

<!--<input type=button onclick="javascript:DEBUGDOM();" value="DEBUG : Add Product Table">-->
<div id="containerdiv">
<!--<div class="adv3">
    광고문의<br> Lindows Hakurei
</div>-->
<table style="float:left;da">
<tr id="material_table_tr">
<td><font style="font-size:20px;"><br>Is this helpful to you? Then would you donate to Lindows Hakurei <br>
Item list may not be complete. Feel free to request to Lindows#7129 (Discord) adding item.<br>
추가하고 싶은 아이템은 디코 Lindows#7129 에게 요청하세욤.
</font>
<br/><br/>
<table>
<tr><td><img src="https://images.evetech.net/characters/92371624/portrait?size=64"></td><td><font style="font-size:24px;">Lindows Hakurei : Developer</font></td></tr>
</table>
<br>
<font style="font-size:26px;">Donator : </font><br><br>
<table>
<tr><td><img src="https://images.evetech.net/characters/95285543/portrait?size=64"></td><td><font style="font-size:24px;">popori Keikira : 7.0 Bil ISK</font></td></tr>

<tr><td><img src="https://images.evetech.net/characters/92594273/portrait?size=64"></td><td><font style="font-size:20px;">rikka takanashi :  1 Roasted Chicken (+ Coke)</font></td></tr>
<tr><td><img src="https://images.evetech.net/characters/97015907/portrait?size=64"></td><td><font style="font-size:20px;">whitejicon kwon : 500 mil ISK</font></td></tr>
<tr><td><img src="https://images.evetech.net/characters/2115753311/portrait?size=64"></td><td><font style="font-size:20px;">Sollar CitY : 200 mil ISK</font></td></tr>
<tr><td><img src="https://images.evetech.net/characters/2115416281/portrait?size=64"></td><td><font style="font-size:20px;">demiurgos 43 : 200 mil ISK</font></td></tr>
<tr><td><img src="https://images.evetech.net/characters/2115655270/portrait?size=64"></td><td><font style="font-size:20px;">SM Hong : 100 mil ISK</font></td></tr>
</table>

</td>
</tr>
</table>
</div>

<script>

var origin_product;
var origin_jsondata;
var globaltr;
var tables=new Array();
var wholematerials=new Array(),wholequantity= new Array(),wholename=new Array();

class Product {
    


    constructor(itemname,typeid,quantity,origin_quantity,level,row,parent_node){


        
        this.itemname=itemname;
        this.typeid=typeid;
        this.iconurl="https://images.evetech.net/types/"+typeid+"/icon";
        this.quantity=quantity;
        this.origin_quantity=origin_quantity;

        this.sellprice=0;
        this.buyprice=0; 

        this.output_per_run=1;

        this.customprice=0;
        //material 배열의 세부 내용은 아래의 setMaterials 함수를 통해서 결정된다.
        this.material=new Array();
        this.manufacturing_level=level;
        this.manufacturing_row=row;
        this.parent_node=parent_node;
        this.selectedradio=1;
        this.selectedtree=0;
        this.searchingtree=0;
        
        this.minimized=1;
        this.includethis(1);

        if(level==0){
            this.minimized=0;
            this.includethis(0);
        }


        this.me_bonus=0;
        this.rig_bonus=0;
        this.structure_bonus=0;  
        this.industry_type=0;

        if(itemname=="_dummy_origin_product"){
            
        }
        else{
            this.maketable();
            
        }
        

        

    }

    maketable() {
        
        //표를 만든다 (DOM Return)
        this.tableDOM=document.createElement("table");

        this.img_icon=document.createElement("img");
        this.span_itemname=document.createElement("span");
        this.span_quantity=document.createElement("span");
        this.img_set_bonus=document.createElement("img");
        this.img_show_material=document.createElement("img");
        this.span_buyprice=document.createElement("span");
        this.input_selectbuy=document.createElement("input");
        this.span_sellprice=document.createElement("span");
        this.input_selectsell=document.createElement("input");
        this.input_customprice=document.createElement("input");
        this.span_customprice=document.createElement("span");
        this.input_selectcustom=document.createElement("input");

        
        //tableDOM
        this.tableDOM.className="included_itemtable";

        //--------item_icon
        this.img_icon.setAttribute("src",this.iconurl);
        this.img_icon.className="item_icon";

        var td_icon=document.createElement("td");
        td_icon .className="item_icon";
        td_icon.appendChild(this.img_icon);

        //============item_name
        this.span_itemname.innerHTML=number_format(Math.ceil(this.quantity))+"x "+this.itemname;
        this.span_itemname.className="item_name";
   
        var td_itemname=document.createElement("td");
        td_itemname.className="item_name";
        td_itemname.onclick=()=>{if(this.manufacturing_level>0){this.toggleMinimize();}}
        td_itemname.appendChild(this.span_itemname);



        
        //===========item_quantity (No Use)
        /*
        this.span_quantity.innerHTML=Math.ceil(this.quantity);
        this.span_quantity.className="item_quantity";

        var td_quantity=document.createElement("td");
        td_quantity.className="item_quantity";
        td_quantity.appendChild(this.span_quantity);
*/
        //=============set bonus icon (%)
        if(window.localStorage.getItem("mebonus"+this.typeid)===null){
            this.img_set_bonus.src="./images/set_itembonus.png";
        }
        else{
            this.img_set_bonus.src="./images/set_itembonus_modified.png";
        }
        this.img_set_bonus.className="set_bonus";
        this.img_set_bonus.onclick=(mouse_event)=>{
            
            this.openBonusWindow(mouse_event.pageX,mouse_event.pageY);
        }

        this.td_set_bonus=document.createElement("td");
        this.td_set_bonus.className="set_bonus";
        if(this.industry_type==2 || this.industry_type==4){
            this.td_set_bonus.appendChild(this.img_set_bonus);
        }
        

        //=============show material icon (▶)

        this.img_show_material.setAttribute("src","./images/show_material.png");
        this.img_show_material.className="show_material";
        this.img_show_material.onclick=()=>{
                if(KEYDOWN_SHIFT){
                    openAllExceptForFuelblocks(this);
                    KEYDOWN_SHIFT=0;
                }
                else{
                    this.openMaterials();
                }                
        }
        
        


        var td_show_material=document.createElement("td");
        td_show_material.className="show_material";
        td_show_material.appendChild(this.img_show_material);

        //============ buy price
        this.span_buyprice.innerHTML="Buy :\t"+number_format(this.buyprice*Math.ceil(this.quantity),2);
        this.span_buyprice.className="item_buyprice";

        var td_buyprice=document.createElement("td");
        td_buyprice.className="item_buyprice";
        td_buyprice.appendChild(this.span_buyprice);
        td_buyprice.onclick=()=>{if(this.manufacturing_level>0){this.toggleMinimize();}}

        // ============Select Buy Price Checkbox
        this.input_selectbuy.setAttribute("type","radio");
        this.input_selectbuy.setAttribute("name","selected_price"+this.manufacturing_level+"_"+this.manufacturing_row);
        this.input_selectbuy.onclick=()=>{
            if(this.manufacturing_level==0){
                changePriceType(1,this);
            }
            else{closingMaterials(this);this.selectedradio=1;this.includethis(1);this.handlePriceChange();}
        }
        if(this.manufacturing_level>0){
            this.input_selectbuy.checked=true;
        }

        var td_selectbuy=document.createElement("td");
        td_selectbuy.className="selectbuy";
        td_selectbuy.appendChild(this.input_selectbuy);

        //=========Sell Price
        this.span_sellprice.innerHTML="Sell :\t"+number_format(this.sellprice*Math.ceil(this.quantity),2);
        this.span_sellprice.className="item_sellprice";

        var td_sellprice=document.createElement("td");
        td_sellprice.className="item_sellprice";
        td_sellprice.appendChild(this.span_sellprice);
        td_sellprice.onclick=()=>{if(this.manufacturing_level>0){this.toggleMinimize();}} 

        //===========Select Sell Price Checkbox
        this.input_selectsell.setAttribute("type","radio");
        this.input_selectsell.setAttribute("name","selected_price"+this.manufacturing_level+"_"+this.manufacturing_row);
        this.input_selectsell.onclick=()=>{
            if(this.manufacturing_level==0){
                changePriceType(2,this);
            }
            else{closingMaterials(this);this.selectedradio=2;this.includethis(1);this.handlePriceChange();}
        }

        var td_selectsell=document.createElement("td");
        td_selectsell.className="selectsell";
        td_selectsell.appendChild(this.input_selectsell);

        //=-======Custom Price
        this.input_customprice.setAttribute("type","number");
        this.input_customprice.setAttribute("step","0.01");
        this.input_customprice.value=number_format(this.customprice*Math.ceil(this.quantity),2);
        this.input_customprice.className="item_customprice";
        this.input_customprice.onchange=()=>{
            this.customprice=parseFloat(this.input_customprice.value/Math.ceil(this.quantity));
            this.selectedradio=3;
            this.input_selectcustom.checked=true;
            this.span_customprice.innerHTML="\t"+number_format(this.customprice*Math.ceil(this.quantity),2);
            this.handlePriceChange();
            }
        this.input_customprice.onfocus=()=>{this.includethis(1);};

        this.span_customprice.innerHTML="\t"+number_format(this.customprice*Math.ceil(this.quantity),2);
        this.span_customprice.className="item_customprice";
        this.span_customprice.style.display="none";

        var td_customprice=document.createElement("td");
        td_customprice.className="item_customprice";
        td_customprice.appendChild(this.input_customprice);
        td_customprice.appendChild(this.span_customprice);
        //td_customprice.onclick=()=>{if(this.manufacturing_level>0){this.toggleMinimize();}} 

        //=========Select Custom Price Checkbox
        this.input_selectcustom.setAttribute("type","radio");
        this.input_selectcustom.setAttribute("name","selected_price"+this.manufacturing_level+"_"+this.manufacturing_row);
        this.input_selectcustom.onclick=()=>{this.selectedradio=3;this.handlePriceChange();}

        if(this.manufacturing_level==0){
            this.input_selectcustom.checked=true;
        }

        var td_selectcustom=document.createElement("td");
        td_selectcustom.className="selectcustom";
        td_selectcustom.appendChild(this.input_selectcustom);


        
        //tr list ( array size(row)=4 )
        this.array_tr=new Array();
        this.minimized=1;

        //=============================tr[0]
        this.array_tr[0]=document.createElement("tr");
            this.array_tr[0].appendChild(td_icon);
            this.array_tr[0].appendChild(td_itemname);
            this.array_tr[0].appendChild(this.td_set_bonus);
                td_show_material.setAttribute("rowspan","4");
            this.array_tr[0].appendChild(td_show_material);

        //==============================tr[1]
        this.array_tr[1]=document.createElement("tr");
                td_buyprice.setAttribute("colspan","2")
            this.array_tr[1].appendChild(td_buyprice);
            this.array_tr[1].appendChild(td_selectbuy);

        //============================tr[2]
        this.array_tr[2]=document.createElement("tr");
                td_sellprice.setAttribute("colspan","2")
            this.array_tr[2].appendChild(td_sellprice);
            this.array_tr[2].appendChild(td_selectsell);

        //============================tr[3]
        this.array_tr[3]=document.createElement("tr");
                td_customprice.setAttribute("colspan","2")
            this.array_tr[3].appendChild(td_customprice);
            this.array_tr[3].appendChild(td_selectcustom);

        

        //table
        for(var i=0;i<this.array_tr.length;i++){
            this.tableDOM.appendChild(this.array_tr[i]);
        }
        if(this.manufacturing_level>0){
            this.toggleMinimize(1);
        }

        this.setTablecolor();
        return this.tableDOM;   
    }
    openMaterials(){
            
            if(this.material.length==0){
                return;
            }
            this.includethis(0);
            for(var i=0;i<this.parent_node.material.length;i++){
                this.parent_node.material[i].tableDOM.className="itemtable";
                this.parent_node.material[i].selectedtree=0;
                this.parent_node.material[i].includethis();
            }
            this.tableDOM.className="selected_itemtable";
            this.selectedtree=1;
            //테이블을 초기화, 최적화 한다.
            if(this.manufacturing_level+2>globaltr.cells.length){

                globaltr.insertCell(-1);
                document.getElementById("containerdiv").style.width=(150+240*globaltr.cells.length)+"px";

            }
            cleanTable(this.manufacturing_level+1);

            //테이블을 구성한다.
            tables[this.manufacturing_level+1]=document.createElement("table");

            for(var i=0;i<this.material.length;i++){
                this.material[i].setMaterials(1);
                this.material[i].loadMarketPrices();
                var ntr=document.createElement("tr");
                var ntd=document.createElement("td");

                //css상으로 선택되어있는것을 초기화시켜준다
                this.material[i].tableDOM.className="itemtable";
                this.material[i].selectedtree=0;
                this.material[i].includethis();
                
                //붙인다.
                ntd.appendChild(this.material[i].getTable());
                ntr.appendChild(ntd);
                tables[this.manufacturing_level+1].appendChild(ntr);
               
            }      
                

            //테이블을 붙인다.
            var ntd=document.createElement("td");
            ntd.appendChild(tables[this.manufacturing_level+1]);
            ntd.className="itemtable";
            ntd.selectedtree=0;
            globaltr.cells[this.manufacturing_level+1].appendChild(ntd);

            this.customprice=parseFloat(this.input_customprice.value)/Math.ceil(this.quantity);
            this.span_customprice.innerHTML="\t"+number_format(this.customprice*Math.ceil(this.quantity),2);
            this.selectedradio=3;
            this.input_selectcustom.checked=true;
            this.setCustomPrice();
            this.handlePriceChange();
            
            this.toggleMinimize(0);
            
        }
    getTable(){
        return this.tableDOM; 
    }
    setMaterials( undernode){

        if(undernode===undefined){
            undernode=99;
        }
        if(undernode>0 && this.material.length==0 ){

            
            var DBxhr=new XMLHttpRequest();
            var materialdata;

            DBxhr.onreadystatechange=()=>{
                if (DBxhr.readyState == XMLHttpRequest.DONE){

                    materialdata=JSON.parse(DBxhr.responseText);
                    if(materialdata.name!==undefined){
                        this.industry_type=materialdata.relation_type;
                        if(this.industry_type==2 || this.industry_type==4){
                            this.td_set_bonus.appendChild(this.img_set_bonus);
                        }
                        this.setBonuses();
                        var bonusmodifier=(1-parseFloat(this.me_bonus/100))*(1-parseFloat(this.rig_bonus/100))*(1-parseFloat(this.structure_bonus/100));
                        for(var i=0;i<materialdata["materials"].length;i++){
                            //Product(itemname,typeid,quantity,origin_quantity,level,row,parent_node)
                            this.material[i]=new Product(
                                materialdata["materials"][i].name,
                                parseInt(materialdata["materials"][i].type_id),
                                Math.ceil(Math.max(this.quantity,this.quantity*materialdata["materials"][i].quantity*bonusmodifier))/materialdata.output,
                                materialdata["materials"][i].quantity/materialdata.output,
                                (this.manufacturing_level+1),
                                i,
                                this
                                );
                            this.material[i].setMaterials(undernode-1);
                            this.material[i].output_per_run=materialdata.output;                   
                        }
                        
                    }
                    else {
                        this.img_show_material.style.display="none";
                    }                   
                }
            }

            DBxhr.open("GET","./get_blueprint_data.php?type_id="+this.typeid,false);
            DBxhr.send();
        }
    }
    changeMaterials(){

        this.setBonuses();
        var bonusmodifier=(1-parseFloat(this.me_bonus/100))*(1-parseFloat(this.rig_bonus/100))*(1-parseFloat(this.structure_bonus/100));
        this.span_itemname.innerHTML=number_format(Math.ceil(this.quantity))+"x "+this.itemname;
        for(var i=0;i<this.material.length;i++){
            this.material[i].quantity=Math.max(this.quantity/this.material[i].output_per_run,this.quantity*this.material[i].origin_quantity*bonusmodifier);
            this.material[i].changeMaterials();               
        }

    }
    loadMarketPrices(){

        if(this.sellprice==0 || this.buyprice==0){

            var pricedata;
            var DBxhr=new XMLHttpRequest();
            DBxhr.onreadystatechange=()=>{
                if (DBxhr.readyState == XMLHttpRequest.DONE){

                    pricedata=JSON.parse(DBxhr.responseText);
                    setLoadedPrices(this.typeid,pricedata.sell,pricedata.buy,origin_product);
                    this.handlePriceChange();
                   
                }
            }
            //this.handlePriceChange();
            DBxhr.open("GET","./get_market_data.php?typeid="+this.typeid,true);
            DBxhr.send();
        }
    }
    recalcPrices(){

        this.span_buyprice.innerHTML="Buy :\t"+number_format(this.buyprice*Math.ceil(this.quantity),2);

        this.span_sellprice.innerHTML="Sell :\t"+number_format(this.sellprice*Math.ceil(this.quantity),2);

        this.input_customprice.value=Math.ceil(this.customprice*Math.ceil(this.quantity)*100)/100;
        this.span_customprice.innerHTML="\t"+number_format(this.customprice*Math.ceil(this.quantity),2);
        for(var i=0;i<this.material.length;i++){
            this.material[i].recalcPrices();               
        }
    }
    setBonuses(_mebonus,_rigbonus,_structurebonus){
        
        //3가지 종류의 보너스를 세팅한다. (local storage를 이용.)
        if(_mebonus===undefined){
            _mebonus=window.localStorage.getItem("mebonus"+this.typeid);
        }
        else if(_mebonus==-1){
            _mebonus=null;
            window.localStorage.removeItem("mebonus"+this.typeid);
        }
        else{
            window.localStorage.setItem("mebonus"+this.typeid,_mebonus);
        }
        this.me_bonus=_mebonus;
        
        if(this.me_bonus===null){
            switch(this.industry_type){
                case 2:
                    this.me_bonus=default_ma_mebonus;
                    break;
                case 4:
                    this.me_bonus=0;
                    break;
            }  
        }

        if(_rigbonus===undefined){
            _rigbonus=window.localStorage.getItem("rigbonus"+this.typeid);
        }
        else if(_rigbonus==-1){
            _rigbonus=null;
            window.localStorage.removeItem("rigbonus"+this.typeid);
        }
        else{
            window.localStorage.setItem("rigbonus"+this.typeid,_rigbonus);
        }
        this.rig_bonus=_rigbonus;
        if(this.rig_bonus===null){
            switch(this.industry_type){
                case 2:
                    this.rig_bonus=default_ma_rigbonus;
                    break;
                case 4:
                    this.rig_bonus=default_re_rigbonus;
                    break;
            }  
        }

        if(_structurebonus===undefined){
            _structurebonus=window.localStorage.getItem("structurebonus"+this.typeid);
        }
        else if(_structurebonus==-1){
            _structurebonus=null;
            window.localStorage.removeItem("structurebonus"+this.typeid);
        }
        else{
            window.localStorage.setItem("structurebonus"+this.typeid,_structurebonus);
        }
        this.structure_bonus=_structurebonus;
        if(this.structure_bonus===null){
            switch(this.industry_type){
                case 2:
                    this.structure_bonus=default_ma_structurebonus;
                    break;
                case 4:
                    this.structure_bonus=default_re_structurebonus;
                    break;
            }  
        }
 
    }
    toggleMinimize(tsize){
        if(tsize===undefined){
            tsize=(this.minimized==0)?1:0;
        }

        if(tsize==1){
            this.minimized=1;
            for(var i=1;i<4;i++){
                this.array_tr[i].style.display="none";    
            }   
            this.array_tr[this.selectedradio].style.display="table-row";
            this.array_tr[3].cells[0].childNodes[0].style.display="none";
            this.array_tr[3].cells[0].childNodes[1].style.display="inline";
        }
        else{
            
            for(var i=0;i<this.parent_node.material.length;i++){
                this.parent_node.material[i].toggleMinimize(1);
            } 
            for(var i=1;i<4;i++){
                this.array_tr[i].style.display="table-row";
            }
            this.array_tr[3].cells[0].childNodes[0].style.display="inline";
            this.array_tr[3].cells[0].childNodes[1].style.display="none";
            this.minimized=0;
                        
        }

    }
    getPrice(){
        switch(this.selectedradio){
            case 1:
                return parseFloat(this.buyprice*Math.ceil(this.quantity));
                break;
            case 2:
                return parseFloat(this.sellprice*Math.ceil(this.quantity));
                break;
            case 3:
                return parseFloat(this.customprice*Math.ceil(this.quantity));
                break;
            default:
                return 0;
        }
    }
    setCustomPrice(){
        
        var sum=0;
        for(var i=0;i<this.material.length;i++){
            sum+=this.material[i].getPrice();
        }

        this.customprice=sum/Math.ceil(this.quantity);
        
        this.includethis(0);
        this.input_customprice.value=Math.ceil(this.customprice*Math.ceil(this.quantity)*100)/100;
        this.span_customprice.innerHTML="\t"+number_format(this.customprice*Math.ceil(this.quantity),2);
    }
    includethis(setvar){
        if(setvar===undefined){
            setvar=this.is_included;
        }
        if(setvar){
            this.is_included=1;
            if(this.tableDOM!==undefined){
                cleanTable(this.manufacturing_level+1);
            }
            
        }
        else{
            this.is_included=0;      
        }
        if(origin_product!==undefined){
            listWholeMaterials();
        }
        this.setTablecolor();
    }
    handlePriceChange(){
        if(this.manufacturing_level>0){
            this.parent_node.setCustomPrice();
            this.parent_node.selectedradio=3;
            this.parent_node.input_selectcustom.checked=true;
            this.parent_node.handlePriceChange();
        }
        
    }
    setTablecolor(){
        if(this.tableDOM===undefined){
            return;
        }
        if(this.searchingtree){
            this.tableDOM.className="searching_itemtable";
        }
        else if(this.selectedtree){
            this.tableDOM.className="selected_itemtable";
        }
        else if(this.is_included){
            this.tableDOM.className="included_itemtable";
        }
        else{
            this.tableDOM.className="itemtable";
        }

    }
    openBonusWindow(position_left,position_top){
        while(document.getElementById("setbonusiframe")!==null){
            var removethis=document.getElementById("setbonusiframe");
            document.body.removeChild(removethis);
        }
        
        this.iframe_set=document.createElement("iframe"); 
        this.iframe_set.id="setbonusiframe";       
        this.iframe_set.style.position="absolute";
        this.iframe_set.style.top=position_top;
        this.iframe_set.style.left=position_left;
        this.iframe_set.style.width="140px";
        this.iframe_set.style.height="130px";
        document.body.appendChild(this.iframe_set);
        
        var ifdoc=this.iframe_set.contentWindow.document;
        ifdoc.open();
        ifdoc.write("<html><body></body></html>");
        ifdoc.close();

        var table_set=ifdoc.createElement("table");
        var tr_set=new Array(ifdoc.createElement("tr"),
        ifdoc.createElement("tr"),
        ifdoc.createElement("tr"),
        ifdoc.createElement("tr"));

        var td_me_bonus=new Array(ifdoc.createElement("td"),ifdoc.createElement("td"));
        var img_me_bonus=ifdoc.createElement("img");
        this.input_me_bonus=ifdoc.createElement("input");

        var td_rig_bonus=new Array(ifdoc.createElement("td"),ifdoc.createElement("td"));
        var img_rig_bonus=ifdoc.createElement("img");
        this.input_rig_bonus=ifdoc.createElement("input");

        var td_structure_bonus=new Array(ifdoc.createElement("td"),ifdoc.createElement("td"));
        var img_structure_bonus=ifdoc.createElement("img");
        this.input_structure_bonus=ifdoc.createElement("input");

        var td_submit=ifdoc.createElement("td");
        var input_default=ifdoc.createElement("input");
        var input_submit=ifdoc.createElement("input");
        
        switch(this.industry_type){
            case 2:
                img_me_bonus.src="./images/ma_mebonus.png";
                img_rig_bonus.src="./images/ma_rigbonus.png";
                img_structure_bonus.src="./images/ma_structurebonus.png";
                break;
            case 4:
                img_me_bonus.src="./images/re_mebonus.png";
                img_rig_bonus.src="./images/re_rigbonus.png";
                img_structure_bonus.src="./images/re_structurebonus.png";
                break;
        }
        img_me_bonus.style.width="24px";
        img_rig_bonus.style.width="24px";
        img_structure_bonus.style.width="24px";
        
        this.input_me_bonus.setAttribute("type","number");
        this.input_me_bonus.setAttribute("min","0");
        this.input_me_bonus.setAttribute("max","10");
        this.input_me_bonus.setAttribute("step","1");
        this.input_me_bonus.style.width="50px";
        this.input_me_bonus.value=this.me_bonus;

        this.input_rig_bonus.setAttribute("type","number");
        this.input_rig_bonus.setAttribute("min","0");
        this.input_rig_bonus.setAttribute("max","10");
        this.input_rig_bonus.setAttribute("step","0.1");
        this.input_rig_bonus.style.width="50px";
        this.input_rig_bonus.value=this.rig_bonus;

        this.input_structure_bonus.setAttribute("type","number");
        this.input_structure_bonus.setAttribute("min","0");
        this.input_structure_bonus.setAttribute("max","10");
        this.input_structure_bonus.setAttribute("step","0.1");
        this.input_structure_bonus.style.width="50px";
        this.input_structure_bonus.value=this.structure_bonus;

        input_default.setAttribute("type","button");
        input_default.value="Reset";
        input_default.onclick=()=>{
            modifyBonuses(this.typeid,-1,-1,-1,origin_product);

            while(document.getElementById("setbonusiframe")!==null){
                var removethis=document.getElementById("setbonusiframe");
                document.body.removeChild(removethis);
            }
            updateAmount();
        }
        input_submit.setAttribute("type","button");
        input_submit.value="OK";
        input_submit.onclick=()=>{

            modifyBonuses(this.typeid,this.input_me_bonus.value,this.input_rig_bonus.value,this.input_structure_bonus.value,origin_product);
            
            
            while(document.getElementById("setbonusiframe")!==null){
                var removethis=document.getElementById("setbonusiframe");
                document.body.removeChild(removethis);
            }
            updateAmount();
        }

        switch(this.industry_type){

            case 2:
                
                td_me_bonus[0].appendChild(img_me_bonus);
                td_me_bonus[1].appendChild(this.input_me_bonus);
                td_me_bonus[1].insertAdjacentHTML("afterbegin","-");
                td_me_bonus[1].insertAdjacentHTML("beforeend","%");
                tr_set[0].appendChild(td_me_bonus[0]);
                tr_set[0].appendChild(td_me_bonus[1]);

                td_rig_bonus[0].appendChild(img_rig_bonus);
                td_rig_bonus[1].appendChild(this.input_rig_bonus);
                td_rig_bonus[1].insertAdjacentHTML("afterbegin","-");
                td_rig_bonus[1].insertAdjacentHTML("beforeend","%");
                tr_set[1].appendChild(td_rig_bonus[0]);
                tr_set[1].appendChild(td_rig_bonus[1]);

                td_structure_bonus[0].appendChild(img_structure_bonus);
                td_structure_bonus[1].appendChild(this.input_structure_bonus);
                td_structure_bonus[1].insertAdjacentHTML("afterbegin","-");
                td_structure_bonus[1].insertAdjacentHTML("beforeend","%");
                tr_set[2].appendChild(td_structure_bonus[0]);
                tr_set[2].appendChild(td_structure_bonus[1]);

                td_submit.appendChild(input_default);
                td_submit.appendChild(input_submit);
                td_submit.setAttribute("colspan","2");
                tr_set[3].appendChild(td_submit);
                break;

            case 4:
                // td_me_bonus[0].appendChild(img_me_bonus);
                // td_me_bonus[1].appendChild(this.input_me_bonus);
                // td_me_bonus[1].insertAdjacentHTML("afterbegin","-");
                // td_me_bonus[1].insertAdjacentHTML("beforeend","%");
                tr_set[0].appendChild(td_me_bonus[0]);
                tr_set[0].appendChild(td_me_bonus[1]);

                td_rig_bonus[0].appendChild(img_rig_bonus);
                td_rig_bonus[1].appendChild(this.input_rig_bonus);
                td_rig_bonus[1].insertAdjacentHTML("afterbegin","-");
                td_rig_bonus[1].insertAdjacentHTML("beforeend","%");
                tr_set[1].appendChild(td_rig_bonus[0]);
                tr_set[1].appendChild(td_rig_bonus[1]);

                td_structure_bonus[0].appendChild(img_structure_bonus);
                td_structure_bonus[1].appendChild(this.input_structure_bonus);
                td_structure_bonus[1].insertAdjacentHTML("afterbegin","-");
                td_structure_bonus[1].insertAdjacentHTML("beforeend","%");
                tr_set[2].appendChild(td_structure_bonus[0]);
                tr_set[2].appendChild(td_structure_bonus[1]);

                td_submit.appendChild(input_default);
                td_submit.appendChild(input_submit);
                td_submit.setAttribute("colspan","2");
                tr_set[3].appendChild(td_submit);
                break;

        }

        for(var j=0;j<4;j++){
            table_set.appendChild(tr_set[j]);
        }
        ifdoc.body.style.backgroundColor="rgba(230,230,255,1)";
        ifdoc.body.appendChild(table_set);
        //alert("click debug. X:"+position_left+", Y:"+position_top);
    }

}

function loadBlueprint(asdfasdf){

    var item_id=document.getElementById("blueprint").value;
    var item_run=document.getElementById("item_run").value;   
    globaltr=document.getElementById("material_table_tr");
    while ( globaltr.hasChildNodes()) {
        globaltr.removeChild(globaltr.childNodes[0]);
    }
    if(asdfasdf=="datalist"){
        
        var selectbox = document.getElementById("blueprint");
        var datalistbox=document.getElementById("bpdatalist");
        for(var i=0;i<selectbox.length;i++){
            if(selectbox.options[i].text==datalistbox.value){
                selectbox.options[i].selected=true;
            }
        }
        loadBlueprint();
        return;
    }
    

    var newtd=globaltr.insertCell(-1);
    newtd.className="itemtable";
    newtd.selectedtree=0;

    var DBdata=new XMLHttpRequest();

    DBdata.onreadystatechange=()=>{

        if (DBdata.readyState == XMLHttpRequest.DONE){
            
            origin_jsondata=JSON.parse(DBdata.responseText);
            var dummy_product=new Product("_dummy_origin_product",0,0,0,0,0,0);

            origin_product=new Product(origin_jsondata.name,item_id,(origin_jsondata.output*item_run),item_run,0,0,dummy_product);
            origin_product.setMaterials(2);
            origin_product.output_per_run=origin_jsondata.output;
            origin_product.loadMarketPrices();

            
            while ( newtd.hasChildNodes()) {
                newtd.removeChild(newtd.childNodes[0]);
            }
            newtd.appendChild(origin_product.getTable());
            //더미 Table 을 하나 더 붙인다. 재료칸으로 쓸 더미.
            newtd.appendChild(document.createElement("table"));
            
        }       
    }

    DBdata.open("GET","./get_blueprint_data.php?type_id="+item_id,true);
    if(item_id!=0){
        DBdata.send();
    }
}
function updateAmount(){

    var item_run=document.getElementById("item_run").value;
    if(origin_product!==undefined){
        origin_product.quantity=origin_jsondata.output*item_run;
        origin_product.changeMaterials();
        origin_product.recalcPrices();
        
    }
    listWholeMaterials();
}

function cleanTable(startcolumn){
    if(globaltr!==undefined){
        for(var i=startcolumn;i<globaltr.cells.length;i++){
                while ( globaltr.cells[i].hasChildNodes()) {
                    globaltr.cells[i].removeChild(globaltr.cells[i].childNodes[0]);
                }
            }
    }


}

function listWholeMaterials(){
    
    wholematerials=new Array();
    wholequantity=new Array();
    wholename=new Array();
    loadWholeMaterials(origin_product);
    
    //일단 quantity 에 따라 정렬부터 해준다.
    for(var i=0;i<wholematerials.length;i++){
        for(var j=i+1;j<wholematerials.length;j++){
            if(wholequantity[i]<wholequantity[j]){
                var tempa=wholematerials[i];
                var tempb=wholequantity[i];
                var tempc=wholename[i];
                wholematerials[i]=wholematerials[j];
                wholematerials[j]=tempa;
                wholequantity[i]=wholequantity[j];
                wholequantity[j]=tempb; 
                wholename[i]=wholename[j];
                wholename[j]=tempc;
            }
        }
    }
    var listtable=document.createElement("table");

    var listbutton=document.createElement("input");
    listbutton.setAttribute("type","button");
    listbutton.setAttribute("value","Copy for Excel");
    listbutton.style.width="200px";
    listbutton.onclick=function(){
        loadwholematerialfield();
    }
    listtable.insertRow(-1);
    listtable.rows[0].insertCell(-1);
    listtable.rows[0].cells[0].colSpan=2;
    listtable.rows[0].cells[0].appendChild(listbutton);
    listtable.rows[0].cells[0].style.textAlign="center";
    for(var i=0;i<wholematerials.length;i++){
        
        var listtr=listtable.insertRow(-1);
        
        var tempd=new Array();
        tempd[0]=listtr.insertCell(-1);
        tempd[1]=listtr.insertCell(-1);

        var tempicon=document.createElement("img");
        tempicon.className="item_icon";
        tempicon.src="https://images.evetech.net/types/"+wholematerials[i]+"/icon"; 

        tempd[0].appendChild(tempicon);

        tempd[1].onmouseover=function(){this.className="selected_listed_item";}
        tempd[1].onmouseout=function(){this.className="listed_item";}

        var tempname=document.createElement("span");
        tempname.className="item_name";
        tempname.innerHTML=wholename[i]+" x"+number_format(Math.ceil(wholequantity[i]));

        listtr.className="listed_item";
        listtr.setAttribute("onmouseover","javascript:searchMaterial("+wholematerials[i]+",origin_product,1);");
        listtr.setAttribute("onmouseout","javascript:searchMaterial("+wholematerials[i]+",origin_product,0);");

        tempd[1].appendChild(tempname);
    }


    if(globaltr.cells[0].childNodes[1]!=undefined){
        globaltr.cells[0].removeChild(globaltr.cells[0].childNodes[1]);
        globaltr.cells[0].appendChild(listtable);
    }

}
var listwindow;
function loadwholematerialfield(){
    var listtext="";
    
    for(i=0;i<wholename.length;i++){
        listtext+=wholename[i]+"\t"+Math.ceil(wholequantity[i])+"\n";
    }

    var popupOptions = "width=500, height=650, toolbar=no, menubar=no, location=no, resizable=yes, scrollbars=yes, status=no;";    //팝업창 옵션
    
    if(listwindow===undefined){
        listwindow=window.open("","Items list",popupOptions);
    }
    else{
        listwindow.close();
        listwindow=window.open("","Items list",popupOptions);
    }

    var listtextarea=listwindow.document.createElement("textarea");
    listtextarea.style.width="480px";
    listtextarea.style.height="600px";
    listtextarea.value=listtext;
    while ( listwindow.document.body.hasChildNodes()) {
        listwindow.document.body.removeChild(listwindow.document.body.childNodes[0]);
    }
    listwindow.document.body.appendChild(listtextarea);
}
function loadWholeMaterials(cnode){
    if(cnode.is_included==1){
        addMaterial(cnode.itemname,cnode.typeid,cnode.quantity);
    }
    else{
        //alert(cnode.itemname+" is not included.");
        for(var i=0;i<cnode.material.length;i++){
            
            loadWholeMaterials(cnode.material[i]);
        }
    }
}

function addMaterial(itemname,typeid,quantity){
    var indexx=wholematerials.indexOf(typeid);
    if(indexx>=0){
        wholequantity[indexx]+=quantity;
    }
    else{
        wholematerials[wholematerials.length]=typeid;
        wholequantity[wholequantity.length]=quantity;
        wholename[wholename.length]=itemname;

    }
}


function searchMaterial(typeid,node,toggle){
    if(toggle){
        if(node.material.length==0 && node.typeid!=typeid){
            
            node.searchingtree=0;
            node.setTablecolor();
            return 0;
        }
        else if((node.is_included || node.material.length==0) && node.typeid==typeid){
            
            node.searchingtree=1;
            node.setTablecolor();
            return 1;
        }
        else if(!node.is_included){
            for(var i=0;i<node.material.length;i++){
                if(searchMaterial(typeid,node.material[i],toggle)){
                    node.searchingtree=1;
                    node.setTablecolor();
                }
            }
            if(node.searchingtree){
                return 1;
            }
            else{
                return 0;
            }
        }
        return 0;
    }
    else{
        
        node.searchingtree=0;
        for(var i=0;i<node.material.length;i++){
            searchMaterial(typeid,node.material[i],toggle);
        }
        node.setTablecolor();
        return 0;
    }
}
function modifyBonuses(type_id,me,rig,structure,cnode){
    if(cnode.typeid==type_id){
        cnode.setBonuses(me,rig,structure);
        if(me==-1){
            cnode.img_set_bonus.src="./images/set_itembonus.png";
        }
        else{
            cnode.img_set_bonus.src="./images/set_itembonus_modified.png";
        }
    }
    for(var i=0;i<cnode.material.length;i++){
        modifyBonuses(type_id,me,rig,structure,cnode.material[i]);
    }
}
function changePriceType(pricetype,cnode){

    if(cnode.selectedradio<3 && cnode.manufacturing_level>0){
        cnode.selectedradio=pricetype;
        cnode.handlePriceChange();
        if(pricetype==1)
        cnode.input_selectbuy.checked=true;
        else if(pricetype==2)
        cnode.input_selectsell.checked=true;
    }
    else{
        for(var i=0;i<cnode.material.length;i++){
            changePriceType(pricetype,cnode.material[i]);
        }
    }
}

function setLoadedPrices(typeid,sell,buy,cnode){

    if(cnode.typeid==typeid){
        cnode.sellprice=sell;
        cnode.buyprice=buy;
        
        if(cnode.span_sellprice!==undefined){
            cnode.span_sellprice.innerHTML="Sell :\t"+number_format(cnode.sellprice*Math.ceil(cnode.quantity),2);
        }
        if(cnode.span_buyprice!==undefined){
            cnode.span_buyprice.innerHTML="Buy :\t"+number_format(cnode.buyprice*Math.ceil(cnode.quantity),2);
        }
        
    }
    for(var i=0;i<cnode.material.length;i++){
            setLoadedPrices(typeid,sell,buy,cnode.material[i]);
        }
}

function openAllExceptForFuelblocks(cnode){
            /*
        Fuel blocks : 4247 , 4051 , 4312 , 4246
        */
    if(cnode.typeid==4247 || 
        cnode.typeid==4051 ||
        cnode.typeid==4312 ||
        cnode.typeid==4246 
        )
        {return;}
        
    cnode.openMaterials();
    for(var i=0;i<cnode.material.length;i++){
        openAllExceptForFuelblocks(cnode.material[i]);
    }
    
}
function closingMaterials(cnode){
    for(var i=0;i<cnode.material.length;i++){
        cnode.material[i].includethis(1)
        console.log("closing"+cnode.material[i].itemname)
        closingMaterials(cnode.material[i]);
    }

}
</script>

</body>
</html>