$(document).ready(function() {
    $("#tab_1").click(function(){
        $("#capa_tabulador_area").html("");
        $("#capa_tabulador_reporte").html("");
        $("#capa_tabulador_mantenimiento").html("");
        $("#capa_tabulador_sistema").html("");
    })
    $("#tab_2").click(function(){
        $("#capa_tabulador_area").html("");
        $("#capa_tabulador_reporte").html("");
        $("#capa_tabulador_mantenimiento").html("");
        $("#capa_tabulador_sistema").html("");
    })
    $("#tab_3").click(function(){
        $("#capa_tabulador_area").html("");
        $("#capa_tabulador_reporte").html("");
        $("#capa_tabulador_mantenimiento").html("");
        $("#capa_tabulador_sistema").html("");
    })
    $("#tab_4").click(function(){
        $("#capa_tabulador_area").html("");
        $("#capa_tabulador_reporte").html("");
        $("#capa_tabulador_mantenimiento").html("");
        $("#capa_tabulador_sistema").html("");
    })
    
    //SOLAPAS
    //--------------------------------------------------------------------
    
    //Default Action
    $(".tab_content").hide(); //Hide all content
    $("ul.tabs li:first").addClass("active").show(); //Activate first tab
    $(".tab_content:first").show(); //Show first tab content

    //On Click Event
    $("ul.tabs li").click(function() {
        //alert("ayumi");
        $("ul.tabs li").removeClass("active"); //Remove any "active" class
        $(this).addClass("active"); //Add "active" class to selected tab
        $(".tab_content").hide(); //Hide all tab content
        var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
        //alert(activeTab);
        $(activeTab).show(); //Fade in the active content
        //$(activeTab).show();
        return false;
    });
    //--------------------------------------------------------------------

    //ACORDEON
    //-------------------------------------------------------------------
   
    $(".accordion h3:first").addClass("active");
    $(".accordion p:not(:first)").hide();
    
    

    
    $(".accordion h3").click(function(){
        $(this).next("p").slideToggle("slow")
        .siblings("p:visible").slideUp("slow");
        $(this).toggleClass("active");
        $(this).siblings("h3").removeClass("active");
    });
    
//-------------------------------------------------------------------
});
           
var tab_images = new Object;
preloadImgs_tabs = function(v_imgs,path_imgs,n_tabs,ext){
    for(i=1;i<=n_tabs;i++){
        //OUT
        //--------------------------------------
        var img = new Image();
        img.src = path_imgs+"tab_"+i+"_out."+ext;
        v_imgs["tab_"+i+"_out"] = img;
        //--------------------------------------
        //OVER
        //--------------------------------------
        var img1 = new Image();
        img1.src = path_imgs+"tab_"+i+"_over."+ext;
        v_imgs["tab_"+i+"_over"] = img1;
    //--------------------------------------

    }
}

preloadImgs_tabs(tab_images,"images/tabs/",4,"gif");
//alert(tab_images["tab_2_out"].src);

crossover = function(img,estado){
	//if(img == null){
	//	id="tab_3";	
	//}
	id = img.id;
    src = img.src;
    title = img.title
    //alert(src);
    //obtenemos el estado actual del tab
    //------------------------------------------
    pos_barra = src.lastIndexOf("/");
    pos_punto = src.lastIndexOf(".");
    nombre_img = src.slice(pos_barra+1,pos_punto);
    pos_linea = nombre_img.lastIndexOf("_");
    estado_current = nombre_img.substr(pos_linea+1);
    //alert(estado_current);
    //------------------------------------------
    switch(estado){
        case "over":{
            if(title == "I"){
                img.src = tab_images[id+"_"+estado].src;
            }            
        }
        case "out":{
            if(title == "I"){
                img.src = tab_images[id+"_"+estado].src;
            }
        }
    }
}

setEstado = function(imagen){
    //asignamos "I" al title de todas las imagenes
    //--------------------------------------------
    for(i=1;i<=4;i++){
        $("#tab_"+i).attr("title","I");
    }
    //--------------------------------------------
    $(imagen).attr("title","A");
    //cambiamos las imagenes de los demas tabs a out
    //--------------------------------------------
	//var cant = document.getElementById("tab_"+i);
	//alert(cant);
    for(i=1;i<=4;i++){
        imagen = document.getElementById("tab_"+i);
		
		if(imagen == null){
			break;
		}
        crossover(imagen,"out");
    }    
//--------------------------------------------
}
recargar = function(){
    //alert("recargando...");
    document.location.reload();
}