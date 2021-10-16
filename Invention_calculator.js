const DEC_ACCELERANT = 34201;
const DEC_ATTAINMENT = 34202;
const DEC_AUGMENTATION = 34203;
const DEC_OPTIMIZED_ATTAINMENT = 34207;
const DEC_OPTIMIZED_AUGMENTATION = 34208;
const DEC_PARITY = 34204;
const DEC_PROCESS = 34205;
const DEC_SYMMETRY = 34206;
let decryptors = new Map();
decryptors.set(DEC_ACCELERANT, {
    name: "Accelerant",
    rate_bonus: 20,
    multi_bonus: 1,
    me_bonus: 2,
    price: 0
});
decryptors.set(DEC_ATTAINMENT, {
    name: 'Attainment',
    rate_bonus: 80,
    multi_bonus: 4,
    me_bonus: -1,
    price: 0
});
decryptors.set(DEC_AUGMENTATION, {
    name: 'Augmentation',
    rate_bonus: -40,
    multi_bonus: 9,
    me_bonus: -2,
    price: 0
});
decryptors.set(DEC_OPTIMIZED_ATTAINMENT, {
    name: 'Optimized Attainmemt',
    rate_bonus: 90,
    multi_bonus: 2,
    me_bonus: 1,
    price: 0
});
decryptors.set(DEC_OPTIMIZED_AUGMENTATION, {
    name: 'Optimized Augmentation',
    rate_bonus: -10,
    multi_bonus: 7,
    me_bonus: 2,
    price: 0
});
decryptors.set(DEC_PARITY, {
    name: 'Parity',
    rate_bonus: 50,
    multi_bonus: 3,
    me_bonus: 1,
    price: 0
});
decryptors.set(DEC_PROCESS, {
    name: 'Process',
    rate_bonus: 10,
    multi_bonus: 0,
    me_bonus: 3,
    price: 0
});
decryptors.set(DEC_SYMMETRY, {
    name: 'Symmetry',
    rate_bonus: 0,
    multi_bonus: 2,
    me_bonus: 1,
    price: 0
});
function calcMaterialPrice(n) {
    let span_result = document.getElementById("mat" + n + "sum");
    let input_num = (document.getElementById("mat" + n + "num"));
    let input_price = (document.getElementById("mat" + n + "price"));
    span_result.innerHTML = Intl.NumberFormat().format(parseInt(input_num.value) * parseFloat(input_price.value));
}
function loadMaterialPrice(n) {
    let input_material = document.getElementById("mat" + n);
    let input_price = document.getElementById("mat" + n + "price");
    let radio = document.querySelector("input[name=mat" + n + "sb]:checked");
    let price = getJsonByURL("https://lindows.kr/IndustryCalculator/get_market_data.php?itemname=" + input_material.value);
    input_price.value = price[radio.value];
    calcMaterialPrice(n);
}
function loadDecryptorPrice(n) {
    let input_price = document.getElementById("dec" + n + "price");
    let radio = document.querySelector("input[name=dec" + n + "sb]:checked");
    let price = getJsonByURL("https://lindows.kr/IndustryCalculator/get_market_data.php?id=" + n);
    input_price.value = price[radio.value];
    getDecryptorPrice(n);
}
function decryptorPriceControl(order) {
    for (let i = 34201; i <= 34208; i++) {
        let radio = document.querySelector("#dec" + i + order);
        radio.setAttribute("checked", "checked");
        loadDecryptorPrice(i);
    }
}
function loadDatacoreList(datalist) {
    let dataoptions = "";
    dataoptions += "<option value=\"Datacore - Defensive Subsystems Engineering\" />\n";
    dataoptions += "<option value=\"Datacore - Propulsion Subsystems Engineering\" />\n";
    dataoptions += "<option value=\"Datacore - Core Subsystems Engineering\" />\n";
    dataoptions += "<option value=\"Datacore - Hydromagnetic Physics\" />\n";
    dataoptions += "<option value=\"Datacore - Minmatar Starship Engineering\" />\n";
    dataoptions += "<option value=\"Datacore - High Energy Physics\" />\n";
    dataoptions += "<option value=\"Datacore - Gallentean Starship Engineering\" />\n";
    dataoptions += "<option value=\"Datacore - Plasma Physics\" />\n";
    dataoptions += "<option value=\"Datacore - Laser Physics\" />\n";
    dataoptions += "<option value=\"Datacore - Quantum Physics\" />\n";
    dataoptions += "<option value=\"Datacore - Molecular Engineering\" />\n";
    dataoptions += "<option value=\"Datacore - Electromagnetic Physics\" />\n";
    dataoptions += "<option value=\"Datacore - Nanite Engineering\" />\n";
    dataoptions += "<option value=\"Datacore - Electronic Engineering\" />\n";
    dataoptions += "<option value=\"Datacore - Graviton Physics\" />\n";
    dataoptions += "<option value=\"Datacore - Rocket Science\" />\n";
    dataoptions += "<option value=\"Datacore - Amarrian Starship Engineering\" />\n";
    dataoptions += "<option value=\"Datacore - Mechanical Engineering\" />\n";
    dataoptions += "<option value=\"Datacore - Nuclear Physics\" />\n";
    dataoptions += "<option value=\"Datacore - Offensive Subsystems Engineering\" />\n";
    dataoptions += "<option value=\"Datacore - Caldari Starship Engineering\" />\n";
    dataoptions += "<option value=\"Datacore - Triglavian Quantum Engineering\" />\n";
    dataoptions += "<option value=\"Other materials\" />\n";
    datalist.innerHTML = dataoptions;
}
function calcDecryptorEfficiency(dec) {
    let invention_cost = [0, parseFloat(document.getElementById('mat1sum').innerHTML), parseFloat(document.getElementById('mat2sum').innerHTML), parseFloat(document.getElementById('mat3sum').innerHTML), parseFloat(document.getElementById('mat4sum').innerHTML)];
    let invention_cost_sum = 0;
    for (let i = 0; i < 6; i++) {
        invention_cost_sum += invention_cost[i];
    }
    let rate_bonus = decryptors.get(dec).rate_bonus;
    let multi_bonus = decryptors.get(dec).multi_bonus;
    let me_bonus = decryptors.get(dec).me_bonus;
    let decryptor_price = decryptors.get(dec).price;
    let base_rate = parseFloat(document.getElementById('base_probability').value);
    let blueprint_price = parseFloat(document.getElementById('blueprint_price').value);
    let manufacturing_cost = parseFloat(document.getElementById('manufacturing_cost').value);
    let profit = ((1 + rate_bonus) * multi_bonus - 1) * (invention_cost_sum + blueprint_price) + (manufacturing_cost * me_bonus * (1 + rate_bonus) * multi_bonus * base_rate) - decryptor_price;
    return profit;
}
function getDecryptorPrice(n) {
    let dec = decryptors.get(n);
    decryptors.set(n, {
        name: dec.name,
        rate_bonus: dec.rate_bonus,
        multi_bonus: dec.multi_bonus,
        me_bonus: dec.me_bonus,
        price: parseFloat(document.getElementById('dec' + n + 'price').value)
    });
    console.log(dec.name + " : " + decryptors.get(n).price + "ISK");
    rankDecryptor();
}
function rankDecryptor() {
    let cost = [[34201, 0], [34202, 0], [34203, 0], [34204, 0], [34205, 0], [34206, 0], [34207, 0], [34208, 0]];
    for (let i = 0; i < 8; i++) {
        cost[i][1] = calcDecryptorEfficiency(cost[i][0]);
    }
    for (let i = 0; i < 8; i++) {
        for (let j = i + 1; j < 8; j++) {
            if (cost[i][1] < cost[j][1]) {
                let id = cost[i][0], isk = cost[i][1];
                cost[i][0] = cost[j][0];
                cost[i][1] = cost[j][1];
                cost[j][0] = id;
                cost[j][1] = isk;
            }
        }
    }
    for (let i = 0; i < 8; i++) {
        document.getElementById('dec' + (i + 1)).innerHTML = decryptors.get(cost[i][0]).name;
        document.getElementById('dec' + (i + 1) + "profit").innerHTML = cost[i][1] + " ISK";
    }
}
function getJsonByURL(url) {
    var xhr = new XMLHttpRequest();
    var returndata;
    xhr.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE) {
            returndata = JSON.parse(this.responseText);
        }
    };
    xhr.open("GET", url, false);
    xhr.send();
    return returndata;
}
//# sourceMappingURL=Invention_calculator.js.map