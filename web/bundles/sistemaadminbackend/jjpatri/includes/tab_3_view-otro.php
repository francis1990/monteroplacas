<script>
    $(function(){
        $(".linka3").click(function(event){
            event.preventDefault();
            var url =  $(this).attr('href');
			$("#capa_tabulador_reporte").html("<p class='loading'></p>");
            $("#capa_tabulador_reporte").load(url);
        });
    });    
</script>
<table width="100%" height="" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="center" valign="top" width="210px">

     <div class="accordion">
     	    <div class="titrcord">&nbsp;</div>
           <h3 class="text_blanco" style="color:#FFFFFF">Matriculas Por A&ntilde;o</h3>
                <p>
                    <span class="icn1">
                        <a class="linka3" href="<?php  echo base_url() ?>marketing_seccion_profesional/formReporteAnio">  
                            Carrera Profesional
                        </a>
                    </span>
                    <br/>
                    <span class="icn1">
                        <a class="linka3" href="<?php  echo base_url() ?>marketing_seccion_tecnico/formReporteTecnicoAnio">
                           Carrera Tecnica
                        </a>
                    </span>
                    <br/>
                  
                
                  		  <span class="icn1">
                        <a class="linka3" href="<?php  echo base_url() ?>">
                            Guias de Remision
                        </a>
                    </span><br/>
                </p>
                <h3 class="text_blanco" style="color:#FFFFFF">Compras</h3>
                <p>
                    <span class="icn1">
                        <a class="linka3" href="<?php  echo base_url() ?>">
                            Registro de Compras
                        </a>
                    </span>
                    <br/>
                    <span class="icn1">
                        <a class="linka3" href="<?php  echo base_url() ?>">
                            Ordenes de Compra
                        </a>
                    </span>
                    <br/>
                    <span class="icn1">
                        <a class="linka3" href="<?php  echo base_url() ?>">
                            Notas de Debito
                        </a>
                    </span>
                    <br/>
                </p>
                <h3 class="text_blanco" style="color:#FFFFFF">Inventario</h3>
                <p>
                    <span class="icn1">
                        <a class="linka3" href="<?php  echo base_url() ?>">
                            Kardex Consolidado
                        </a>
                    </span>
                    <br/>
                    <span class="icn1">
                        <a class="linka3" href="<?php  echo base_url() ?>">
                            Kardex por Producto
                        </a>
                    </span>
                    <br/>
                    <span class="icn1">
                        <a class="linka3" href="<?php  echo base_url() ?>">
                            Stock de Productos
                        </a>
                    </span>
                    <br/>		
                 
                </p>               
            </div>

        </td>
        <td align="center" valign="top">
            <!--<div id="capa_tabulador_reporte" style="overflow:scroll; height:610px;">-->
			<div id="capa_tabulador_reporte">
            </div> 
           
        </td>
    </tr>
</table>
