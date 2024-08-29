	String.prototype.splice = function(idx, rem, str) {
		return this.slice(0, idx) + str + this.slice(idx + Math.abs(rem));
};
	//conversion meses eng a es
    var meses = {"January":"Enero", "February":"Febrero", "March":"Marzo", "April":"Abril", "May":"Mayo", 
    "June":"Junio", "July":"Julio", "August":"Agosto", 
    "September":"Septiembre", "Octuber":"Octubre", "November":"Noviembre", "December":"Diciembre"};

var arrclass = ["netsales", "netgv", "netga", "pmsales", "pmgv", "pmga", "varsales", "vargv", "varga", "pyvarsales","pyvargv","pyvarga"];
var arrporcent = ["porcentsales", "porcentgv", "porcentga", "pyporcentsales","pyporcentgv","pyporcentga"];

	$('.section').each(function (index, value) {

		var idsection = $(this).attr('id');
		$.each(arrclass, function (index, element) {
		var sum = 0;
			$("[id='"+idsection+"'] ."+element).each(function (index) {
				var  value = parseInt($(this).text().replace(/,/g, ''));
				switchcolor($(this));
				sum += value;
			});
			//imprimir sumas en celda td
			cell = $("[id='"+idsection+element+"']");
			cell.text(sum.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
			switchcolor(cell);
		}); 
		$.each(arrporcent, function (index, element) {
			$("."+element).each(function (index) {
				switchcolor($(this));
			});
			//imprimir operacion de porcentuales en celda td
			cell = $("[id='"+idsection+element+"']");
			var dividiendo = parseInt(cell.prev().text().replace(/,/g, ''));
			if (element.indexOf("py") >= 0)
			var divisor = parseInt(cell.prev().prev().prev().prev().prev().text().replace(/,/g, ''));
			else
			var divisor = parseInt(cell.prev().prev().text().replace(/,/g, ''));
			result = (divisor) ? dividiendo / divisor * 100 : 0;
			cell.text(Math.round(result)+"%"); //redondea %
			switchcolor(cell);
		});

	});//fin ciclo, imprimir en tabla seccion global
	function switchcolor(object){
		if (!object.is('[class*="net"], [class*="pm"], [id*="net"], [id*="pm"]')) //excluye las clases con net y pm 
		{
			if (object.text().indexOf("-") > -1)
				object.css("color","red");
			else
				object.css("color","green");
		}
	}

//function modal en tablas desactualizada borrar!!!!!!!!!
	// function modal(){
	// 	document.getElementById('myModal').style.display='block';
	// }
	//modificaciones
	$(document).ready(function(){
		window.scrollTo(0,0);
		$('#page-loader').css( "display","none");
		$("#807").text("REAL ESTATE");
		$("[id='USA ALIMENTOS']").prev().prev().text("ALIMENTOS EU");
		$("[id='USA ALIMENTOS'] #global table thead tr th").text("GLOBAL ALIMENTOS EU");
	

	});


