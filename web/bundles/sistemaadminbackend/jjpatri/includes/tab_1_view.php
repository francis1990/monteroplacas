	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="<?php  echo base_url(); ?>js/libs/jquery-2.1.1.min.js"><\/script>');
			}
		</script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="<?php  echo base_url(); ?>js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script> 
		<script type="text/javascript">
			$(document).ready(function() {
				
                $("#capa_tabulador_area").load("<?php echo base_url(); ?>portada/viewPortada/"+x_idempresa_trabajo );
				$(".linka1").click(function(event){
                                    event.preventDefault();
                                    var url =  $(this).attr('href');
                                //    $("#capa_tabulador_area").html("<p class='loading'></p>");
                                    $("#capa_tabulador_area").load(url);
                 });
			});
		</script>
<!-- NAVIGATION : This navigation is also responsive-->
<nav>
    <ul>
        <li >
            <a class="linka1" href="<?php echo base_url() ?>portada/viewPortada" title="Portada"><i class="fa fa-lg fa-fw fa-home"></i><span class="menu-item-parent">Portada</span></a>
           <!-- <ul>
                <li class="active">
                    <a href="index.html" title="Dashboard"><span class="menu-item-parent">Notificaciones</span></a>
                </li>
                <li class="">
                    <a href="dashboard-social.html" title="Dashboard"><span class="menu-item-parent">Social Wall</span></a>
                </li>
            </ul>-->
        </li>
         <?php if ($this->session->userdata('acceso_gerencia') == 1) { ?>
            <li >
                <a class="linka1" href="<?php echo base_url() ?>gerencia_jjsalud/viewMenuGerencia"><i class="fa fa-lg fa-fw fa-tachometer"></i> <span class="menu-item-parent">Gerencia</span></a>
            </li>
        <?php } ?>
        <?php if ($this->session->userdata('acceso_produccion') == 1) { ?>
            <li >
                <a class="linka1" href="<?php echo base_url() ?>produccion_jjsalud/viewMenuProduccion"><i class="fa fa-lg fa-fw fa-flask"></i> <span class="menu-item-parent">Produccion</span></a>
            </li>
        <?php } ?>
        <?php if ($this->session->userdata('acceso_ventas') == 1) { ?>
            <li >
                <a class="linka1" href="<?php echo base_url() ?>venta_jjsalud/viewMenuVentas/<?php echo $infoempresa->idempresa_corp;?>"><i class="fa fa-lg fa-fw fa-money"></i> <span class="menu-item-parent">Ventas</span></a>
            </li>
        <?php } ?>
       
        <?php if ($this->session->userdata('acceso_logistica') == 1) { ?>
            <li>
                <a class="linka1" href="<?php echo base_url() ?>logistica_jjsalud/viewMenuLogistica/<?php echo $infoempresa->idempresa_corp;?>"><i class="fa fa-lg fa-fw fa-truck"></i> <span class="menu-item-parent">Logistica</span></a>
            </li>
        <?php } ?>
        <?php if ($this->session->userdata('acceso_contabilidad') == 1) { ?>
            <li>
                <a class="linka1" href="<?php echo base_url() ?>contable_jjsalud/viewMenuContableVoucher/<?php echo $infoempresa->idempresa_corp;?>"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span class="menu-item-parent">Contabilidad</span></a>
              
            </li>
        <?php } ?>
        <?php if ($this->session->userdata('acceso_config') == 1) { ?>
            
             <li>
                <a href="#"><i class="fa fa-lg fa-fw fa-wrench"></i> <span class="menu-item-parent">Config</span></a>
                 <ul>
                    <li>
                        <a class="linka1" href="<?php echo base_url() ?>configuracion_usuario/gridUsuario">Usuarios Activos</a>
                    </li>
                    <li>
                        <a class="linka1" href="<?php echo base_url() ?>configuracion_usuario/gridUsuarioInactivos">Usuarios Inactivos</a>
                    </li>
                    <li>
                        <a class="linka1" href="<?php echo base_url() ?>configuracion_acceso/gridUsuarioTipo">Gestionar Perfiles</a>
                    </li>
                 </ul>
             </li>
        <?php } ?>

    </ul>
</nav>


<span class="minifyme" data-action="minifyMenu"> 
    <i class="fa fa-arrow-circle-left hit"></i> 
</span>

   
</aside>
<!-- END NAVIGATION -->
