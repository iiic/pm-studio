$("a.ajax").live("click", function (event) {// Volání AJAXu u všech odkazů s třídou ajax
	event.preventDefault();
	$.get(this.href);
});

$("form.ajax").live("submit", function () {// AJAXové odeslání formulářů
	$(this).ajaxSubmit();
	return false;
});

$("form.ajax :submit").live("click", function () {// další funkce nutná pro ajaxové odeslání formulářů
	$(this).ajaxSubmit();
	return false;
});
/*
function disInput(arg) {// funkce nastaví všechny inputy na readonly a vypne všechna tlačítka (bez jQuery) argumentem je ukazatel na DOM objekt
	inputs = arg.getElementsByTagName('input');
	for (i=0;i<inputs.length;i++)// getElementsByTagName vrací ještě nějaký bonusový sračky takže nelze použít for (i in inputs)
	{
		if((inputs[i].type == 'button') || (inputs[i].type == 'submit') || (inputs[i].type == 'reset')) {
			inputs[i].disabled=true;
			//@todo : pokud půjde s flash message spouštět i nějaký javascript dám na tlačítko text odesílám... a potom text opakovat nebo tak něco
		} else {
			lock = document.createAttribute('readonly','readonly');// nemůže být mimo cyklus
			inputs[i].setAttributeNode(lock);// nelze použít něco jako inputs[i].readonly='readonly' podobně jako třeba u title nebo id
		}
	}
}
*/
$(function () {// proběhne po načtení celé stránky
	$('.hide-with-js').hide();// schovat to, co se schovat má
/*
	if(!location.hash) {
		window.location.replace("#novniky");
	}
*/
});
