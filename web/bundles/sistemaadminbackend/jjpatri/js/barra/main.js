//$(document).ready(function() {
(function(){
	$('#barra-progreso').progressBar({	
		url:'proceso.php',
	    progressUrl:'progreso.txt',
		trigger: '#proceso',
		finished: function(){
		//	alert("El proceso ha finalizado");
		}
	})

});
