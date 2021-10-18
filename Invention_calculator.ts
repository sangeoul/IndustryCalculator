const DEC_ACCELERANT =34201;
const DEC_ATTAINMENT =34202;
const DEC_AUGMENTATION =34203;
const DEC_OPTIMIZED_ATTAINMENT =34207;
const DEC_OPTIMIZED_AUGMENTATION =34208;
const DEC_PARITY =34204;
const DEC_PROCESS =34205;
const DEC_SYMMETRY =34206;


window.onload=function(){
    for(let i=34201; i<=34208;i++){
        loadDecryptorPrice(i);
    }
    
}

var decryptors= new Map();

decryptors.set(DEC_ACCELERANT,{
    name:"Accelerant",
    rate_bonus:20,
    multi_bonus:1,
    me_bonus:2,
    //te_bonus:number,
    price:0  
    
});
decryptors.set(DEC_ATTAINMENT,{
    name:'Attainment',
    rate_bonus:80,
    multi_bonus:4,
    me_bonus:-1,
    //te_bonus:number,
    price:0   
    
});
decryptors.set(DEC_AUGMENTATION,{
    name:'Augmentation',
    rate_bonus:-40,
    multi_bonus:9,
    me_bonus:-2,
    //te_bonus:number,
    price:0  
    
});
decryptors.set(DEC_OPTIMIZED_ATTAINMENT,{
    name:'Optimized Attainmemt',
    rate_bonus:90,
    multi_bonus:2,
    me_bonus:1,
    //te_bonus:number,
    price:0  
    
});
decryptors.set(DEC_OPTIMIZED_AUGMENTATION,{
    name:'Optimized Augmentation',
    rate_bonus:-10,
    multi_bonus:7,
    me_bonus:2,
    //te_bonus:number,
    price:0  
    
});
decryptors.set(DEC_PARITY,{
    name:'Parity',
    rate_bonus:50,
    multi_bonus:3,
    me_bonus:1,
    //te_bonus:number,
    price:0  
    
});
decryptors.set(DEC_PROCESS,{
    name: 'Process',
    rate_bonus:10,
    multi_bonus:0,
    me_bonus:3,
    //te_bonus:number,
    price:0   
    
});
decryptors.set(DEC_SYMMETRY,{
    name:'Symmetry',
    rate_bonus:0,
    multi_bonus:2,
    me_bonus:1,
    //te_bonus:number,
    price:0   
    
});


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

function loadDecryptorPrice(n:number){

    let input_price =document.getElementById("dec"+n+"price") as HTMLInputElement;
    let radio =document.querySelector("input[name=dec"+n+"sb]:checked") as HTMLInputElement;

    let price:any =getJsonByURL("https://lindows.kr/IndustryCalculator/get_market_data.php?typeid="+n);
    
    input_price.value=price[radio.value];

    console.log(decryptors.get(n).name + " : " + price[radio.value] + "ISK");

    getDecryptorPrice(n);

}

function decryptorPriceControl(order:string){
    
    for(let i=34201;i<=34208;i++){
        document.getElementById("dec"+i+"sell").removeAttribute("checked");
        document.getElementById("dec"+i+"buy").removeAttribute("checked");

        (document.getElementById("dec"+i+order) as HTMLInputElement).setAttribute("checked","checked");;
    
        loadDecryptorPrice(i);
    }
    
    
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


function calcDecryptorEfficiency(dec:number){
    
    
    let invention_cost:number[]=
    [
        0,
        parseFloat((document.getElementById('mat1price') as HTMLInputElement).value) * parseFloat((document.getElementById('mat1num') as HTMLInputElement).value) ,
        parseFloat((document.getElementById('mat2price') as HTMLInputElement).value) * parseFloat((document.getElementById('mat2num') as HTMLInputElement).value),
        parseFloat((document.getElementById('mat3price') as HTMLInputElement).value) * parseFloat((document.getElementById('mat3num') as HTMLInputElement).value),
        parseFloat((document.getElementById('mat4price') as HTMLInputElement).value) * parseFloat((document.getElementById('mat4num') as HTMLInputElement).value)
    ];
    
    let invention_cost_sum:number=0;
    for(let i=0;i<5;i++){
        invention_cost_sum +=invention_cost[i];
    }


    let rate_bonus:number =decryptors.get(dec).rate_bonus;
    let multi_bonus:number =decryptors.get(dec).multi_bonus;
    let me_bonus:number =decryptors.get(dec).me_bonus;
    let decryptor_price:number =decryptors.get(dec).price;
    
    let base_rate:number =parseFloat((document.getElementById('base_probability') as HTMLInputElement).value);
    let blueprint_price:number =parseFloat((document.getElementById('blueprint_price') as HTMLInputElement).value);
    let manufacturing_cost:number =parseFloat((document.getElementById('manufacturing_cost') as HTMLInputElement).value);
    
    let profit= ((1+(rate_bonus/100))*multi_bonus -1)*(invention_cost_sum+blueprint_price) + (manufacturing_cost*(me_bonus/100)*(1+(rate_bonus/100))*multi_bonus*(base_rate/100))- decryptor_price;
    
    
    return profit;
}

function getDecryptorPrice(n:number){
    
    let dec = decryptors.get(n);
    decryptors.set(n,
            {
                name:dec.name,
                rate_bonus:dec.rate_bonus,
                multi_bonus:dec.multi_bonus,
                me_bonus:dec.me_bonus,
                price:  parseFloat((document.getElementById('dec'+n+'price') as HTMLInputElement).value)
            }
        );
        
   
   /* decryptors.forEach((value,key,mapObject)=> {decryptors.set(key,
            {
                name:value.name,
                rate_bonus:value.rate_bonus,
                multi_bonus:value.multi_bonus,
                me_bonus:value.me_bonus,
                price:  parseFloat((document.getElementById('dec'+key+'price') as HTMLInputElement).value)
            }
        )
        console.log(value.name + " : " + decryptors.get(key).price + "ISK");

        }
    ) ;
*/

    rankDecryptor();
}

function rankDecryptor(){
    let cost:any=[[34201,0],[34202,0],[34203,0],[34204,0],[34205,0],[34206,0],[34207,0],[34208,0]];
    for(let i=0;i<8;i++){
        cost[i][1]=calcDecryptorEfficiency(cost[i][0]);
    }

    for(let i=0;i<8;i++){
        for(let j=i+1;j<8;j++){
            if(cost[i][1]<cost[j][1]){
                let id=cost[i][0],isk=cost[i][1];
                cost[i][0]=cost[j][0];
                cost[i][1]=cost[j][1];
                cost[j][0]=id;
                cost[j][1]=isk;
            }
        }
    }

    for(let i=0;i<8;i++){
        let dec=decryptors.get(cost[i][0]);
        (document.getElementById('dec'+(i+1)) as HTMLSpanElement).innerHTML= dec.name;
        (document.getElementById('dec'+(i+1)+"info") as HTMLSpanElement).innerHTML 
        = "P.M " + (dec.rate_bonus>0?"+"+dec.rate_bonus:dec.rate_bonus) + "% / " 
        + "Run +" + dec.multi_bonus + " / "
        + "M.E " + (dec.me_bonus>0?"+"+dec.me_bonus:dec.me_bonus);
        Intl.NumberFormat().format((document.getElementById('dec'+(i+1)+"profit") as HTMLSpanElement).innerHTML=cost[i][1])+" ISK";
    }   
    
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