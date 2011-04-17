const OKRAJ = 8;
function obrazky() {// Až bude jednou spolehlivě fungovat css3 calc nebude potřeba script pro odsazení obrázku z důvodu jeho umístěni na středu, script bude potřeba jen kvůli zmenšení obrázku pokud je příliš veliký (respektivě jak toto provést pomocí css nevím)
	for(i=1; i<(kde.length+1); i++) {
		picWidth = document.getElementById('pic'+i).lastChild.lastChild.width;
		picHeight = document.getElementById('pic'+i).lastChild.lastChild.height;
		raito = picWidth/picHeight;
		if( (picWidth>$(window).width()) || (picHeight>$(window).height()) ) {// pokud je potřeba zmenšovat obrázek v plné velikosti
			while( (picWidth>($(window).width()-8*OKRAJ)) || (picHeight>($(window).height()-8*OKRAJ)) ) {// zmenšuji obrázek, pokud je potřeba
				picWidth = picWidth-raito;
				picHeight = picHeight-1;
			}// konec zmenšovacího cyklu s podmínkou na začátku
			document.getElementById('pic'+i).lastChild.lastChild.width = Math.round(picWidth);// round : zaokrouhlení aritmetické
			document.getElementById('pic'+i).lastChild.lastChild.height = Math.round(picHeight);
		}
		document.getElementById('pic'+i).lastChild.style.left = ($(window).width()-picWidth)/2-OKRAJ+'px';
		document.getElementById('pic'+i).lastChild.style.top = ($(window).height()-picHeight)/2-OKRAJ+'px';
		if(document.getElementById('pic'+i).lastChild.firstChild.className == 'full') {
			document.getElementById('pic'+i).firstChild.firstChild.style.top = 'auto';
			document.getElementById('pic'+i).firstChild.firstChild.style.width = 'auto';
			document.getElementById('pic'+i).firstChild.firstChild.style.fontSize = 'medium';// medium snad vrací velikoust písma na defaultní
			document.getElementById('pic'+i).firstChild.firstChild.style.left = '0px';
			document.getElementById('pic'+i).firstChild.firstChild.style.bottom = '0px';
			if(i < kde.length) {
				nextLink = document.createElement('a');
				nextLink.href = '#pic'+(i+1);
				nextLink.className = 'nav next';
				nextLink.onclick = function(){sharpness(this.href)};// hnusný !!!
				nextLinkText = document.createTextNode('další obrázek     ►');
				nextLink.appendChild(nextLinkText);
				document.getElementById('pic'+i).lastChild.insertBefore(nextLink, document.getElementById('pic'+i).lastChild.firstChild);
			}
			if (i > 1) {
				prevLink = document.createElement('a');
				prevLink.href = '#pic'+(i-1);
				prevLink.className = 'nav prev';
				prevLink.onclick = function(){sharpness(this.href)};// hnusný !!!
				prevLinkText = document.createTextNode('◄     předchozí obrázek');
				prevLink.appendChild(prevLinkText);
				document.getElementById('pic'+i).lastChild.insertBefore(prevLink, document.getElementById('pic'+i).lastChild.firstChild);
			}
		}
	}
}
$(window).resize(function() {// při změně velikosti okna prohlížeče
	obrazky();
});
function sharpness(uri) {
	if(uri) {
		uri = uri.replace(/.+#/, '');
	} else {
		if(/^#pic[0-9]+$/.test(window.location.hash)) {
			uri = window.location.hash.substr(1);
		} else {
			return false;
		}
	}
	for(i=0; i<kde.length; i++) {
		if(kde[i].id == uri) {
			kde[i].lastChild.lastChild.src = 'http://localhost/nette-blog/document_root/images/full/'+kde[i].lastChild.lastChild.src.substr(kde[i].lastChild.lastChild.src.lastIndexOf('/'));
		}
	}
}

if(document.getElementById('data').childNodes) {
kde = document.getElementById('data').childNodes;
obrazky();
sharpness();
}
