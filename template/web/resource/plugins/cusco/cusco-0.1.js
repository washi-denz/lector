$(function(){

    $(document).on("click","[event=control]",function(){
        $(this).controlEvent({
            e_type : $(this).attr("event-type"),
            e_id   : $(this).attr("event-id"),
            e_data : $(this).attr("event-data")
        });
    });

    $(document).on("click",function(e){
        //e.preventDefault();
        var elem = $(e.target);

        //--config up
        if(elem.parents(".float").is(".float")!=true){
            $(".float-cont").hide();
            $(".float-link").removeClass("open");
        }
        //--prueba
    });
    
    $.fn.controlEvent=function(opciones){
        var elem=$(this);
        var config = {
            e_type : "none",
            e_id   : "",
            e_data : {},
            e_show : {display:"block"},
            e_hide : {display:"none"},
            e_width: 0,
            e_height:0
        };
        jQuery.extend(config,opciones);
        
        config.toggle=function(){
            
            $(config.e_data).slideToggle("linear");
            
            var ec=elem.children("i");
            
            if(ec.attr("class")!="icon-up-open-3"){
                ec.removeClass();
                ec.addClass("icon-up-open-3");
                $(config.e_data).attr("event-display","false");

            }else{
                ec.removeClass();
                ec.addClass("icon-down-open-3");
                $(config.e_data).attr("event-display","true");

            }
            
        };

        config.collapse=function(){
            $(config.e_id).slideToggle();
        };
        
        config.bar=function(){
            //escritorio->movil
            $("[event-movil='"+config.e_data+"']").each(function(){
                if(config.e_width<768 ){
                    //modo movil
                    //alert($(config.e_data).attr("event-display"));
                    $(this).attr("event-display","true");
                    $(config.e_data).attr("event-display","false");
                
                    if($(config.e_data).attr("event-display")=="true"){
                        $(this).find("i").removeClass();
                        $(this).find("i").addClass("icon-down-open-3");
                    }else{
                        $(this).find("i").removeClass();
                        $(this).find("i").addClass("icon-up-open-3");
                    }
                    
                }else{
                    //modo  escritorio
                    $(this).attr("event-display","false");
                    $(config.e_data).attr("event-display","true");
                    
                    $(this).attr("event-display","false");
                    $(config.e_data).attr("event-display","true");
                }
                return 0;
            });            
        };
        
        config.collapse_group=function(){
            var obj=elem.parent().parent(".collapse-group");
            if(obj.children().hasClass("open")){
                obj.children().removeClass("open");
            }else{
                obj.children().addClass("open");
            }
        };

        config.float_up = function(){            
            var elem_id = $(config.e_id);

            if(elem.hasClass("open")){
                elem_id.hide();
                elem.removeClass("open");
            }else{
                $(".float-cont").hide();
                $(".float-link").removeClass("open");
                elem_id.show();
                elem.addClass("open");
            }                                   
        };
        
        config.scrolltop = function(){                  
            $("html,body").animate({scrollTop:$(config.e_data).offset().top-60},1000 );
        };
        config.onoff = function(){
            //mjr
            
            var elem = $(config.e_id);
            var rpta = (elem.hasClass("open"))? true:false;
  
            if(rpta){
                elem.removeClass("open");
                elem.hide();
            }else{
                elem.addClass("open");
                elem.show();
            }
        };
        
        config.evento = function(opc){
            switch(opc){
                case "toggle":
                    config.toggle();
                break;
                case "collapse":
                    config.collapse();
                break;
                case "collapse-group":
                    config.collapse_group();
                break;
                case "bar":
                    config.bar();
                break;
                case "float_up":
                    config.float_up();
                break;
                case "scrolltop":
                    config.scrolltop();
                break;
                case "onoff":
                    config.onoff();
                break;
                default:
                   alert("NO_CONTROL_EVENT");
            }
        };
        
        config.evento(config.e_type);
        return this;
    };
});