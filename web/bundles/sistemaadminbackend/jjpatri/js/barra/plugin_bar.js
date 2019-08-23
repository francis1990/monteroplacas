
//$(document).ready(function(win, doc, $, _und) {
(function (win, doc, $, _und){
		$.fn.progressBar = function (oConfig){
			var _oConfig, _iniciarProceso, _mostrarProreso, _getProgreso ,_bIniciado=false, _bFinalizado =false;
			
			_oConfig = $.extend({
				trigger: "#progress-trigger",
				progressBar: $(this),
				url: _und,
				progressUrl : 'progreso.txt',
				finished: function (){ return false;}
			}, oConfig);
			
			_iniciarProceso = function(){
				_bIniciado = true;
				_bFinalizado = false;
				$(_oConfig.trigger).prop("disabled",true);
				$.ajax({
					url: _oConfig.url,
					success: function(){
						_bFinalizado = true;
						_mostrarProgreso(100);
						_oConfig.finished();
						$(_oConfig.trigger).prop("disabled",false);
					}
				});
				_getProgreso();
			};
			
			_mostrarProgreso= function(nPorcentaje){
				var oProgressBar = _oConfig.progressBar;
				
				oProgressBar.css("width", nPorcentaje +"%");
				oProgressBar.text(nPorcentaje+"%");
				oProgressBar.attr("aria-valuenow",nPorcentaje);
			};
			
			
			_getProgreso =function(){
				$.ajax({
					url:_oConfig.progressUrl,
					success:function (sData){
						var nProgreso = parseInt(sData,10);
							if(sData =="finish" ||  _bIniciado){
								nProgreso= 0;
								_bIniciado=false;
							}
							if( _bFinalizado ){
								nProgreso =100;
							}
						_mostrarProgreso(nProgreso);
						if(nProgreso <100){
							setTimeout(_getProgreso,100);
						}
					},
					error:function(){
						setTimeout(_getProgreso,100);
					}
				});
			}
			
			
			$(doc).on('click', _oConfig.trigger, function(){
				_iniciarProceso();
			});
			
	}
}(window, window.document, jQuery))
