/********************************
    
    cusco.js v.0.2
    Desarrollado en lab.Paralel
    Última versión modificada: v.0.2.6
    
********************************/

$(function(){

    $("[event-type = cut_text]").each(function(){
    
            var elem = $(this);
            var text = elem.html();
            var num  = elem.attr("event-num");
            var text_new = '';
            
            for(var i=0;i<text.length;i++){
                if(i<num){
                    text_new += text[i];
                }
            }
            elem.html(text_new+" ...");
    });

    $(document).on("click","[event=control]",function(){
        
        var attr = $(this).attr("event-data");
        var data = (typeof attr!== typeof undefined && attr !== false)? JSON.parse(attr):{};
        //ERROR si e_id y e_data existen al mismo tiempo.
        $(this).controlEvent({
            e_type : $(this).attr("event-type"),
            e_id   : $(this).attr("event-id"),
            e_data : data,
            e_auto : "auto"
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
    });
    

    $.fn.controlEvent=function(opciones){
        
        var elem = $(this);
        
        var config = {
            e_type : "none",
            e_id   : "#",
            e_data : {}, // {},[],...
            e_auto : ""
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
        
        config.slide_icon=function(){
            
            var elem1 = $(config.e_data.id1);
            var elem2 = $(config.e_data.id2);
            
            if($(elem1).hasClass("open")){
                $(elem1).slideUp();
                $(elem1).removeClass("open");
        
                $(elem2).removeClass(config.e_data.icon_2);
                $(elem2).addClass(config.e_data.icon_1);
  
            }else{
                $(elem1).slideDown();
                $(elem1).addClass("open");

                $(elem2).removeClass(config.e_data.icon_1);
                $(elem2).addClass(config.e_data.icon_2);
            }
        };

        config.collapse=function(){
            $(config.e_id).slideToggle();
        };
        
        config.collapse_group=function(){
        
            var elem_id = $(config.e_id);
            var obj     = elem.parent().parent(".collapse-group");
 
            if(!elem.parent(".cg-item").hasClass("open")){
                
                obj.children().removeClass("open");
                obj.find(".collapse").slideUp();
                
                elem.parent(".cg-item").addClass("open");
                elem_id.slideDown();
            }else{
                elem.parent(".cg-item").removeClass("open");
                elem_id.slideUp();
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
            var id   = (typeof config.e_data.id == typeof undefined)? config.e_id : config.e_data.id;
            var rest = (typeof config.e_data.rest == typeof undefined)? 0 : config.e_data.rest;
            $("html,body").animate({scrollTop:$(id).offset().top - rest},1000);
        };
        
        config.scrollfast = function(){
            var id   = (typeof config.e_data.id == typeof undefined)? config.e_id : config.e_data.id;
            var rest = (typeof config.e_data.rest == typeof undefined)? 0 : config.e_data.rest;
            $("html,body").animate({scrollTop:$(id).offset().top - rest},100);
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
        
        config.append =function(){};
        
        config.off = function(){ 
            var elem_id = $(config.e_id);
            elem_id.hide(); 
        };
        
        config.on = function(){ 
            var elem_id = $(config.e_id);
            elem_id.show(); 
        };
        
        config.more = function(){
            var elem_id = $(config.e_id);
            elem_id.slideDown();
            elem.html('');
        }
        
        config.eyelash = function(){
        
            var elem_id = $(config.e_id);
            
            $(".control-eyelash").children().removeClass("active");
            
            if(elem.parents(".control-eyelash").hasClass("control-eyelash")){
                
                elem.parent("li").addClass("active");

            }else{
                if(elem.attr("event-id") == config.e_id){
                    $(".control-eyelash li a").each(function(){
                        if($(this).attr("event-id") == config.e_id){
                           $(this).parent("li").addClass("active");
                        }
                    });
                }
            }
            
            elem_id.parent(".container-eyelash").children().hide();
            elem_id.show();
  
        };
        
        config.copy = function(){
            
            var tmp = $("<input>");
            $("body").append(tmp);
            tmp.val(elem.attr("event-container")).select();
            document.execCommand("copy");
            tmp.remove(); 
            
            elem.animate({"font-size":"120%"},500)
                .animate({"font-size":"100%"},500,function(){})
            
        };
        
        config.checkbox_text = function(){
            
            var elem_id        = elem.attr("id");
            var elem_for       = $("[for='"+elem_id+"']");
            var elem_event_for = $("[event-for='"+config.e_id+"']");
            
            if(elem.is(":checked")){
                elem_event_for.slideDown();
                elem_for.html(config.e_data[1]);
            }else{
                elem_event_for.slideUp();
                elem_for.html(config.e_data[0]);
            }
            
        }
        
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
                case "scrollfast":
                    config.scrollfast();
                break;
                case "off":
                    config.off();
                break;
                case "on":
                    config.on();
                break;
                case "onoff":
                    config.onoff();
                break;
                case "slide_icon":
                    config.slide_icon();
                break;
                case "append":
                    config.append();
                break;
                case "more":
                    config.more();
                break;
                case "eyelash":
                    config.eyelash();
                break;
                case "copy":
                    config.copy();
                break;
                case "checkbox_text":
                    config.checkbox_text();
                break;
                default:
                   alert("NO_CONTROL_EVENT");
            }
        };
        
        config.evento(config.e_type);
        return this;
    };
});