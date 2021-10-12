


function calcMaterialPrice(n:number){
    let span_result:HTMLSpanElement = document.getElementById("mat"+n+"sum");

    let input_num : HTMLInputElement=(document.getElementById("mat"+n+"num")) as HTMLInputElement;
    let input_price:HTMLInputElement= (document.getElementById("mat"+n+"price")) as HTMLInputElement;

    span_result.innerHTML = Intl.NumberFormat().format( parseInt(input_num.value) * parseFloat(input_price.value));
}

function loadMaterialPrice(n:number){
    let input_material=document.getElementById("mat"+n) as HTMLInputElement;
    let input_price=document.getElementById("mat"+n+"price") as HTMLInputElement;
    let radio=document.querySelector("input[name=mat"+n+"sb]:checked") as HTMLInputElement;

    let price:any=getJsonByURL("https://lindows.kr/IndustryCalculator/get_market_data.php?itemname="+input_material.value);
    
    input_price.value=price[radio.value];
    calcMaterialPrice(n);

}

function loadDatacoreList( datalist:HTMLDataListElement){

    

    let dataoptions:string="";
    dataoptions+="<option value=\"Datacore - Defensive Subsystems Engineering\" />\n";
    dataoptions+="<option value=\"Datacore - Propulsion Subsystems Engineering\" />\n";
    dataoptions+="<option value=\"Datacore - Core Subsystems Engineering\" />\n";
    dataoptions+="<option value=\"Datacore - Hydromagnetic Physics\" />\n";
    dataoptions+="<option value=\"Datacore - Minmatar Starship Engineering\" />\n";
    dataoptions+="<option value=\"Datacore - High Energy Physics\" />\n";
    dataoptions+="<option value=\"Datacore - Gallentean Starship Engineering\" />\n";
    dataoptions+="<option value=\"Datacore - Plasma Physics\" />\n";
    dataoptions+="<option value=\"Datacore - Laser Physics\" />\n";
    dataoptions+="<option value=\"Datacore - Quantum Physics\" />\n";
    dataoptions+="<option value=\"Datacore - Molecular Engineering\" />\n";
    dataoptions+="<option value=\"Datacore - Electromagnetic Physics\" />\n";
    dataoptions+="<option value=\"Datacore - Nanite Engineering\" />\n";
    dataoptions+="<option value=\"Datacore - Electronic Engineering\" />\n";
    dataoptions+="<option value=\"Datacore - Graviton Physics\" />\n";
    dataoptions+="<option value=\"Datacore - Rocket Science\" />\n";
    dataoptions+="<option value=\"Datacore - Amarrian Starship Engineering\" />\n";
    dataoptions+="<option value=\"Datacore - Mechanical Engineering\" />\n";
    dataoptions+="<option value=\"Datacore - Nuclear Physics\" />\n";
    dataoptions+="<option value=\"Datacore - Offensive Subsystems Engineering\" />\n";
    dataoptions+="<option value=\"Datacore - Caldari Starship Engineering\" />\n";
    dataoptions+="<option value=\"Datacore - Triglavian Quantum Engineering\" />\n";
    dataoptions+="<option value=\"Other materials\" />\n";

    datalist.innerHTML=dataoptions;

}


function getJsonByURL(url:string){


    var xhr=new XMLHttpRequest();  
    var returndata;

    xhr.onreadystatechange=function(){
        if (this.readyState == XMLHttpRequest.DONE){
            returndata=JSON.parse(this.responseText);
        }
    }

    xhr.open("GET",url,false);
    xhr.send();

    return returndata;

}