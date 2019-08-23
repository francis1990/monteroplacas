		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="js/libs/jquery-2.1.1.min.js"><\/script>');
			}
		</script>

		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
                            
                             //$("#capa_tabulador_area").load("<?php echo base_url(); ?>portada/viewPortada");
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
            <a href="#" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
            <ul>
                <li class="active">
                    <a href="index.html" title="Dashboard"><span class="menu-item-parent">Analytics Dashboard</span></a>
                </li>
                <li class="">
                    <a href="dashboard-social.html" title="Dashboard"><span class="menu-item-parent">Social Wall</span></a>
                </li>
            </ul>	
        </li>
        <?php if ($this->session->userdata('acceso_administracion') == 1) { ?>
            <li >
                <a ><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">Configuracii</span></a>
                 
                <ul>
                    <li class="">
                        <a class="linka1" href="<?php echo base_url() ?>portada/viewPortada"><i class="fa fa-lg fa-fw fa-gear"></i> <span class="menu-item-parent">App Layouts</span></a>
                    </li>
                    <li class="">
                        <a href="skins.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-picture-o"></i> <span class="menu-item-parent">Prebuilt Skins</span></a>
                    </li>
                    <li>
                        <a href="applayout.html"><i class="fa fa-cube"></i> App Settings</a>
                    </li>
                </ul>
            </li>
        <?php } ?>
        <?php if ($this->session->userdata('acceso_produccion') == 1) { ?>
            <li>
                <a href="inbox.html"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Outlook</span> <span class="badge pull-right inbox-badge margin-right-13">14</span></a>
            </li>
        <?php } ?>
        <?php if ($this->session->userdata('acceso_ventas') == 1) { ?>
            <li>
                <a href="#"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span class="menu-item-parent">Graphs</span></a>
                <ul>
                    <li>
                        <a href="flot.html">Flot Chart</a>
                    </li>
                    <li>
                        <a href="morris.html">Morris Charts</a>
                    </li>
                    <li>
                        <a href="sparkline-charts.html">Sparklines</a>
                    </li>
                    <li>
                        <a href="easypie-charts.html">EasyPieCharts</a>
                    </li>
                    <li>
                        <a href="dygraphs.html">Dygraphs</a>
                    </li>
                    <li>
                        <a href="chartjs.html">Chart.js</a>
                    </li>
                    <li>
                        <a href="hchartable.html">HighchartTable <span class="badge pull-right inbox-badge bg-color-yellow">new</span></a>
                    </li>
                </ul>
            </li>
        <?php } ?>
        <?php if ($this->session->userdata('acceso_logistica') == 1) { ?>
            <li>
                <a href="#"><i class="fa fa-lg fa-fw fa-table"></i> <span class="menu-item-parent">Tables</span></a>
                <ul>
                    <li>
                        <a href="table.html">Normal Tables</a>
                    </li>
                    <li>
                        <a href="datatables.html">Data Tables <span class="badge inbox-badge bg-color-greenLight hidden-mobile">responsive</span></a>
                    </li>
                    <li>
                        <a href="jqgrid.html">Jquery Grid</a>
                    </li>
                </ul>
            </li>
        <?php } ?>
        <?php if ($this->session->userdata('acceso_sub1') == 1) { ?>
            <li>
                <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Forms</span></a>
                <ul>
                    <li>
                        <a href="form-elements.html">Smart Form Elements</a>
                    </li>
                    <li>
                        <a href="form-templates.html">Smart Form Layouts</a>
                    </li>
                    <li>
                        <a href="validation.html">Smart Form Validation</a>
                    </li>
                    <li>
                        <a href="bootstrap-forms.html">Bootstrap Form Elements</a>
                    </li>
                    <li>
                        <a href="bootstrap-validator.html">Bootstrap Form Validation</a>
                    </li>
                    <li>
                        <a href="plugins.html">Form Plugins</a>
                    </li>
                    <li>
                        <a href="wizard.html">Wizards</a>
                    </li>
                    <li>
                        <a href="other-editors.html">Bootstrap Editors</a>
                    </li>
                    <li>
                        <a href="dropzone.html">Dropzone</a>
                    </li>
                    <li>
                        <a href="image-editor.html">Image Cropping</a>
                    </li>
                    <li>
                        <a href="ckeditor.html">CK Editor</a>
                    </li>
                </ul>
            </li>
        <?php } ?>
        <?php if ($this->session->userdata('acceso_config') == 1) { ?>
            
             <li>
                <a href="#"><i class="fa fa-lg fa-fw fa-gears"></i> <span class="menu-item-parent">Config</span></a>
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
