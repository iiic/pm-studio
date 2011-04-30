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

jQuery.fn.calculator = function() {

	var result = '';
	var count = 0;

	if($('#frmadForm-print').attr('checked')) {

		var localCount = 0;
		result += '<h4>'+$('#frmadForm-print').next().text()+'</h4>';

		n = $(this).getRowFromId('frmadForm-printMaterial');
		localCount += n[0];
		result += n[1];

		n = $(this).getRowFromId('frmadForm-printColors');
		localCount *= n[0];
		result += n[1];

		n = $(this).getRowFromId('frmadForm-printArea');
		localCount *= n[0];
		result += n[1];

		n = $(this).getRowFromId('frmadForm-printCount');
		localCount *= n[0];
		result += n[1];

		result += '<hr>';

		if(isNaN(localCount)) {
			count = 'NaN';
			result += '<span>nevyplnil jste správně všechny údaje</span>';
		} else {
			localCount = (Math.round(localCount * 10)/10);
			count += localCount;
			result += '<span>'+localCount.toFixed(2)+' Kč</span>';
		}

	}

	if($('#frmadForm-card').attr('checked')) {

		var localCount = 0;
		result += '<h4>'+$('#frmadForm-card').next().text()+'</h4>';

		n = $(this).getRowFromId('frmadForm-cardSize');
		localCount += n[0];
		result += n[1];

		n = $(this).getRowFromId('frmadForm-cardColors');
		localCount *= n[0];
		result += n[1];

		n = $(this).getRowFromId('frmadForm-cardCount');
		localCount *= n[0];
		result += n[1];

		result += '<hr>';

		if(isNaN(localCount)) {
			count = 'NaN';
			result += '<span>nevyplnil jste správně všechny údaje</span>';
		} else {
			localCount = (Math.round(localCount * 10)/10);
			count += localCount;
			result += '<span>'+localCount.toFixed(2)+' Kč</span>';
		}

	}

	if($('#frmadForm-car').attr('checked')) {

		var localCount = 0;
		result += '<h4>'+$('#frmadForm-car').next().text()+'</h4>';

		n = $(this).getRowFromId('frmadForm-carSize');
		localCount += n[0];
		result += n[1];

		n = $(this).getRowFromId('frmadForm-carColors');
		localCount *= n[0];
		result += n[1];

		n = $(this).getRowFromId('frmadForm-carCount');
		localCount *= n[0];
		result += n[1];

		result += '<hr>';

		if(isNaN(localCount)) {
			count = 'NaN';
			result += '<span>nevyplnil jste správně všechny údaje</span>';
		} else {
			localCount = (Math.round(localCount * 10)/10);
			count += localCount;
			result += '<span>'+localCount.toFixed(2)+' Kč</span>';
		}

	}

	$('#result').empty();
	if(result == '') {
		$('#result').append('<legend>pozor !</legend>');
		$('#result').append('Musíte zaškrtnout checkboxy u vámi požadovaného zboží.');
	} else {
		$('#result').append('<legend>vaše vypočtená cena</legend>');
		$('#result').append(result);
		if(isNaN(count)) {
			$('#result').append('nejsou vyplněny všechny údaje !');
		} else {
			count = (Math.round(count * 10)/10);
			vat = (Math.round(count * vatRatio * 10)/10);
			$('#result').append('<table><tr>');
			$('#result').append('<th>cena bez DPH : </th><td>'+(count - vat).toFixed(2)+' Kč</td>');
			$('#result').append('</tr><tr>');
			$('#result').append('<th>+ DPH : </th><td>'+vat.toFixed(2)+' Kč</td>');
			$('#result').append('</tr><tr>');
			$('#result').append('<th>cena celkem : </th><td>'+count.toFixed(2)+' Kč</td>');
			$('#result').append('</tr></table>');
		}
	}
};

jQuery.fn.getRowFromId = function(id) {
	if($('#'+id).is('select')) {
		selectedText = $('#'+id+' option:selected').text();
		selectedLabel = jQuery.trim($('#'+id).parent().prev().text());
		num = parseFloat(selectedText.split('|')[1]);
		result = '<b>'+selectedLabel+'</b> '+selectedText.split('|')[0]+'<br>';
		return [num, result];
	} else if($('#'+id).is('input')) {
		insertedValue = $('#'+id).val();
		insertedValue = insertedValue.replace(',','.'); // desetiná tečka místo čárky
		insertedValue = insertedValue.replace(' ',''); // odstraní mezery v číslech
		inserdedLabel = jQuery.trim($('#'+id).parent().prev().text());
		num = parseFloat(insertedValue);
		result = '<b>'+inserdedLabel+'</b> '+insertedValue+'<br>';
		return [num, result];
	} else {
		return false;
	}
};

$("#frm-adForm").live("submit", function () {
	$(this).calculator();
	return false;
});

$("#frm-adForm :submit").live("click", function () {
	$(this).calculator();
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
});
