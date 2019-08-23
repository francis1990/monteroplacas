//Flot Line Chart with Tooltips
$(document).ready(function(){
	

	$("button#buscadornombre").on("click", function(){
            // alert( "has tocado" );			// console.log("mostrarcartel");
		var nombre =  $("input#inpbuscar ").val();		 
		
		var url =$("form#formbuscador").attr("action").replace("pla",nombre);		$("form#formbuscador").attr("action",url);
	});			
	
	$("button#buscadornombrefoto").on("click", function(){         		
	    var nombre = $("input#inpbuscarfoto").val();		            alert( "nombre" );				var url =$("form#formbuscadorfoto").attr("action").replace("deng",nombre);		$("form#formbuscadorfoto").attr("action",url);	});
	
	$("button#buscararticuloporcategoria").on("click", function(){
	
	

		var fecha = $("#articuloporcategoriaInputFecha").val();
		
		var oldurl =	$("form#buscararticuloporcategoria").attr("action");
		var url = oldurl+fecha;
	
		$("form#buscararticuloporcategoria").attr("action",url);
		$("form#buscararticuloporcategoria").submit();
		

	});
	
});




