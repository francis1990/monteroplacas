//Flot Line Chart with Tooltips
$(document).ready(function(){

alert( "has escrito" );

$("button#buscararticuloporcategoria").on("click", function(){
	
	       alert( "has escrito" );

		var fecha = $("#articuloporcategoriaInputFecha").val();
		
		
		var url = "/inventario/web/articuloporcategoria/1?fecha="+fecha;
		

	
         alert(url);
		$("form#buscararticuloporcategoria").attr("action",url);
		

	});
	
	
	


	

	


	
	



});




