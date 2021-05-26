let btnf1 = document.getElementById('skinF1'),
    btnf2 = document.getElementById('skinF2'),
    btncs1 = document.getElementById('skincs1'),
    btncs2 = document.getElementById('skincs2'),
    btncs3 = document.getElementById('skincs3'),
    btnt1 = document.getElementById('skint1'),
    btnt2 = document.getElementById('skint2'),
    btnt3 = document.getElementById('skin3');

let skin1 = document.getElementById('skin1'),
    skin2 = document.getElementById('skin2'),
    skin3 = document.getElementById('skin3');

btnf1.onclick = function() {
    skin1.src = "../../image/skinsps/forest.png";
}

btnf2.onclick = function() {
    skin1.src = "../../image/skinsps/forest1.png";
}

btncs1.onclick = function() {
    skin2.src = "../../image/skinsps/cs1.png";
}

btncs2.onclick = function() {
    skin2.src = "../../image/skinsps/cs2.png";
}

btncs3.onclick = function() {
    skin2.src = "../../image/skinsps/cs3.png";
}