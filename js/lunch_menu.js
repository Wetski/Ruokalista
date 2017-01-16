var ruokalista = "";
var urlArray = [];
for(var i = 0; i < 5; i++) {
	var aimo = "menus/aimo/amica_" + i + ".json";
	var bitti = "menus/bitti/bitti_" + i + ".json";
	var poliisi = "menus/poliisi/poliisi_" + i + ".json";
	urlArray.push(aimo);
	urlArray.push(bitti);
	urlArray.push(poliisi);
}
function start() {
	var date = new Date();
	setViikonPaivat();
	for(var value of urlArray){
		if(checkArray(value, date.getDay()-1)) {
			loadJSON(value);
		}
	}
}

function checkArray(str, nmbr) {
	
	if(str.search(nmbr) != -1) {
		return true;
	}
	else {
		return false;
	}
}

function navigate(index) {
	for(var i  = 1;i <6;i++) {
		var inactive = document.getElementById(i);
		inactive.setAttribute("class","vkp");
	}
	var active = document.getElementById(index);
	active.setAttribute("class","vkp active");
	
	var paikat= ["aimo", "bitti", "poliisi"];
	var element = "";
	for (var value of paikat) {
		element = document.getElementById(value);
		if(element != null) element.innerHTML = null;
	}
	
	for(var value of urlArray){
		if(checkArray(value, (index-1))) {
			loadJSON(value);
		}
	}
}

function navigateProfile(index) {
	var inactive = document.getElementById(1);
	inactive.setAttribute("class","");
	var inactive = document.getElementById(2);
	inactive.setAttribute("class","");
	 var active = document.getElementById(index);
	 active.setAttribute("class", "active");
	 
	 if (index == 1) {
		 
	 }
	 else {
		 
	 }
}

function setViikonPaivat() {
	
	var viikonpaiva = ["maanantai" , "tiistai", "keskiviikko", "torstai", "perjantai"];
	
	var date = new Date();
	var ehto = date.getDay();
	if(ehto <= 5 && ehto != 0) {
		var active = document.getElementById(ehto); //hakee navbarin tämänhetkisen viikonpäivän
		active.setAttribute("class" , "vkp active"); // asettaa lista elementin aktiiviseksi
	}
	
	var temp_date = date.getDate()-date.getDay()+1; //hakee kyseisen viikon maanantain
	
	date.setDate(temp_date); // asettaa oikean päivämäärän maanantaille
	temp_date = date.getDate();
	
	for(var i = 0;i<5;i++) { //asetetaan viikonpäivien päivämäärät
		var weekday = document.getElementById(viikonpaiva[i])
		date.setDate(temp_date+i);
		
		if(date.getDate() < (temp_date+i)) temp_date = 1 - i; //tsekkaa onko kuukausi vaihtunut välissä
		weekday.innerHTML = date.getDate() + "." + (date.getMonth() + 1); //asettaa span elementin sisälle viikonpäivän pvm:n
	}
}

function loadJSON(url) {
	
	$.getJSON(url, function(response){
		if(url.search("amica") != -1) {
			ruokalista = response.MenusForDays[0].SetMenus;
			for(var i = 0; i < response.MenusForDays[0].SetMenus.length; i++) {
				naytaAimo(i);
			}
		}
		if(url.search("bitti") != -1) {
			ruokalista = response.courses;
			for(var i = 0; i < response.courses.length; i++) {
				naytaBitti(i);
			}
		}
		if(url.search("poliisi") != -1) {
			ruokalista = response.courses;
			for(var i = 0; i < response.courses.length; i++) {
				naytaPoliisi(i);
			}
		}
	});
}

function naytaAimo(index){
	// uusi div	
	//console.log(ruokalista);
	//console.log(index);
	//console.log(ruokalista[0].SetMenus[index].Name);
	
	var ruokaDiv = document.createElement("tr");
	ruokaDiv.setAttribute("class","ruokaRow");
	
	var tr = document.createElement("tr");
	tr.setAttribute("class", "infoRow");
	
	var td1 = document.createElement("td");
	td1.setAttribute("class","otsikko");
	var text = document.createTextNode(ruokalista[index].Name);	
	td1.appendChild(text);
		
	var td2 = document.createElement("td");
	td2.setAttribute("class","hinta");
	text = document.createTextNode(ruokalista[index].Price);	
	td2.appendChild(text);
		
	var td3 = document.createElement("td");
	td3.setAttribute("class","ruoka");
		
	var t_text = "";
	var array = ruokalista[index].Components;
	if(Object.prototype.toString.call(array) === '[object Array]') //tarkistaa onko muuttuja lista
	{
		for (var i = 0; i < array.length; i++) {
			td3.appendChild(document.createTextNode(array[i]));
			td3.appendChild(document.createElement("br"));
		}
	}
	else{
		text = document.createTextNode(array);
		td3.appdendChild(text);
	}

	ruokaDiv.appendChild(td1);
	ruokaDiv.appendChild(td2);
	tr.appendChild(td3);
	
	var aimoDiv = document.getElementById("aimo");
	
	aimoDiv.appendChild(ruokaDiv);
	aimoDiv.appendChild(tr);
}
function naytaBitti(index) {
	
	var ruokaDiv = document.createElement("tr");
	ruokaDiv.setAttribute("class","ruokaRow");
	
	var tr = document.createElement("tr");
	tr.setAttribute("class", "infoRow");
	
	var td1 = document.createElement("td");
	td1.setAttribute("class", "otsikko");
	td1.setAttribute("style","text-transform: uppercase;")
	
	var text = document.createTextNode(ruokalista[index].category);
	td1.appendChild(text);
	
	var td2 = document.createElement("td");
	td2.setAttribute("class", "hinta");
	
	text = document.createTextNode(ruokalista[index].price);
	td2.appendChild(text);
	
	var td3 = document.createElement("td");
	td3.setAttribute("class", "ruoka");
	
	var t_text = ruokalista[index].title_fi;
	t_text = t_text + "\n" + ruokalista[index].desc_fi;
	text = document.createTextNode(t_text);
	td3.appendChild(text);
	
	ruokaDiv.appendChild(td1);
	ruokaDiv.appendChild(td2);
	tr.appendChild(td3);
	
	var bittiDiv = document.getElementById("bitti");
	bittiDiv.appendChild(ruokaDiv);
	bittiDiv.appendChild(tr);
	
	
}
function naytaPoliisi(index) {
	
	var ruokaDiv = document.createElement("tr");
	ruokaDiv.setAttribute("class","ruokaRow");
	
	var tr = document.createElement("tr");
	tr.setAttribute("class", "infoRow");
	
	var td1 = document.createElement("td");
	td1.setAttribute("class", "otsikko");
	td1.setAttribute("style","text-transform: uppercase;")
	
	var text = document.createTextNode(ruokalista[index].category);
	td1.appendChild(text);
	
	var td2 = document.createElement("td");
	td2.setAttribute("class", "hinta");
	
	text = document.createTextNode(ruokalista[index].price);
	td2.appendChild(text);
	
	var td3 = document.createElement("td");
	td3.setAttribute("class", "ruoka");
	
	text = document.createTextNode(ruokalista[index].title_fi);
	td3.appendChild(text);
	
	ruokaDiv.appendChild(td1);
	ruokaDiv.appendChild(td2);
	tr.appendChild(td3);
	
	var poliisiDiv = document.getElementById("poliisi");
	poliisiDiv.appendChild(ruokaDiv);
	poliisiDiv.appendChild(tr);
}