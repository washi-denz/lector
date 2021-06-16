function rtnAjax($url,$data){
    var $rtn = false;

    if(typeof($url) != 'undefined')
    {
        $url = $url.split("/");
        $url = _URL+$url[0]+"/json/"+$url[1];
    }

    if(typeof($data.edit) != 'undefined'){
        if($data.edit == true){
            $data.json = JSON.stringify(examen);  
        }
    }

    console.log(JSON.stringify($data));

    $.ajax({
        type     : "POST",
        url      : $url,
        cache    : false,
        data     : $data,
        dataType : "json",
        async    : false,
        beforeSend:function(){
            if(typeof($data.load) != 'undefined')
            {
                var $str  = $data.load.split("_");
                var id   = $str[0];
                var type = $str[1];

                $("#"+id).css({"position":"relative"});

                $("#"+id).fadeIn(50,function(){
                    if(type == 'bottom'){
                        $(this).html('<i class="icon-spin6 animate-spin onload-icon" id="onload"></i>');
                    }
                });
              
            }

        },
        complete:function(){
            $("#onload").fadeOut(100,function(){
                $(this).remove();
            });
        },
        error:function(){
            alert("ERROR,El servidor AJAX no funciona.");
        },
        success:function(respuesta){

            console.log("rtnAJAX --> "+JSON.stringify(respuesta));

            if(!respuesta['success'] || respuesta['success']){
                if(respuesta['return'] != undefined && respuesta['return'] != ''){
                    $rtn = respuesta['return'];
                }
                if(respuesta['update'] != undefined && respuesta['update'] != ''){
                    for(var i=0; i<respuesta['update'].length; i++){

                        if(respuesta['update'][i]['action'] == "remove")
                        {
                            $("#"+respuesta['update'][i]['id']).remove();
                        }
                        else if(respuesta['update'][i]['action'] == "html")
                        {
                            $("#"+respuesta['update'][i]['id']).html(respuesta['update'][i]['value']);                                      
                        }
                        else if(respuesta['update'][i]['action'] == "openModal")
                        {
                            $("#"+respuesta['update'][i]['id']).modal('show');
                        }
                        else if(respuesta['update'][i]['action'] == "closeModal")
                        {
                            $("#"+respuesta['update'][i]['id']).modal('hide');
                        }
                        else if(respuesta['update'][i]['action'] == "notification"){
                            alert(respuesta['update'][i]['value']);
                        }
                        else if(respuesta['update'][i]['action'] == "redirection"){
                            top.location.href = respuesta['update'][i]['value'];
                        }

                    }
                }

            }
        }
    });

    return $rtn;

} 

//---

$(function(){

    $("#menuBar").on("click",function(){
      if($(".nav").hasClass("active")){
          $(".nav-right").slideUp();
          $(".nav").removeClass("active");
      }else{
          $(".nav-right").slideDown();
          $(".nav").addClass("active");
      }
   });
    $(document).on("click",function(e){
        
        var tg = $(e.target);

        if(tg.hasClass("view") || tg.parent().hasClass("view")){
            
            if(tg.next("ul").hasClass("show") || tg.parent(".view").next("ul").hasClass("show")){
            
                tg.next("ul").slideUp();
                tg.next("ul").removeClass("show");
                tg.next("ul").removeClass("show");
                
                tg.parent(".view").next("ul").slideUp();
                tg.parent(".view").next("ul").removeClass("show");
                
            }else{
                
                $(".menu .show").slideUp();
                $(".show").removeClass();
                $(".view.active").removeClass("active");
                
                tg.next("ul").slideDown();
                tg.next("ul").addClass("show");
                
                tg.parent(".view").next("ul").slideDown();
                tg.parent(".view").next("ul").addClass("show");
                tg.addClass("active");
                
            }
    
        }else{
            $(".container-nav .menu .show").slideUp();
            $(".container-nav .show").removeClass();
            $(".container-nav .view.active").removeClass("active");
        }
    
    });
});

//---

function eliminarEspacio(cadTexto){
    //convertir la cadena en minúscula
    cadTexto = cadTexto.toLowerCase();
    //no incluir caracteres reservados
    cadTexto = cadTexto.replace(/([\$\(\)\*\+\.\[\]\¿\?\\\/\^\{\}\|])/g,"");
    //eliminar espacios al inicio y al final
    cadTexto =cadTexto.replace(/^\s+|\s+$/g,"");
    //combierte más de un espacio en uno
    cadTexto = cadTexto.replace(/\s+/g," ");
    return cadTexto;
}

function eliminarEspacioInicioFinal(cadTexto){
    cadTexto = cadTexto.replace(/^\s+|\s+$/g,"");
    cadTexto = cadTexto.replace(/\s+/g," ");
    return cadTexto;
}

//---

$(function(){

  $.tablaBusquedaMain = function(textBusqueda,idTabla,numFil){

    var elem=$(idTabla);
    var cont=0;
    elem.find("#msjBusqueda").remove();

    elem.find('tbody tr').each(function(indiceFila,objFila){
      var objCeldas=$(objFila).find('td');
      if(objCeldas.length>0){
        var textExiste=false;
        objCeldas.each(function(indiceCelda,objCeldaFila){

          objRegExp = new RegExp(RegExp.escape(textBusqueda,'i'));

          if(objRegExp.test(eliminarEspacio($(objCeldaFila).text()))){
            textExiste=true;
            cont++;
            return false;
          }
          
        });

        if(textExiste==true && cont<=numFil){
          $(objFila).show();
        }else{
          $(objFila).remove();
        }

      }

    });
  }
  RegExp.escape=function(textBusqueda){
        var strCaracteresEspeciales=new RegExp("[.*+?|()\\[\\]{}\\\\]", "g");
        //devolvemos la cadena limpia
        return textBusqueda.replace(strCaracteresEspeciales, "\\$&");
    };

}); 

//---

$(function(){

    const ARTICLE_TITLE  = encodeURIComponent($('meta[property="og:title"]').attr('content'));    
    const ARTICLE_URL    = encodeURIComponent($('meta[property="og:url"]').attr('content'));    
    const MAIN_IMAGE_URL = encodeURIComponent($('meta[property="og:image"]').attr('content'));   

    $(document).on("click",".share-fb",function(){      
        open_window("http://www.facebook.com/sharer/sharer.php?u="+ARTICLE_URL,"facebook_share");
    });

    $(document).on("click",".share-twitter",function(){
        open_window("http://twitter.com/share?url="+ARTICLE_URL,"twitter_share");
    });

    $(document).on("click",".share-google-plus",function(){
        open_window("https://plus.google.com/share?url="+ARTICLE_URL,"google_share");
    });

    $(document).on("click",".share-linkedin",function(){
        open_window("https://www.linkedin.com/shareArticle?mini=true&url="+ARTICLE_URL+"&title="+ARTICLE_TITLE+"&summary=&source=","linkedin_share");
    });

    $(document).on("click",".share-pinterest",function(){
        open_window("https://pinterest.com/pin/create/button/?url="+ARTICLE_URL+"&media="+MAIN_IMAGE_URL+"&description="+ARTICLE_TITLE,"pinterest_share");
    });

    $(document).on("click",".share-tumblr",function(){
        open_window("http://www.tumblr.com/share/link?url="+ARTICLE_URL+"&name="+ARTICLE_TITLE+"&description="+ARTICLE_TITLE,"tumblr_share");
    });

    $(document).on("click",".share-whatsapp",function(){
        open_window("https://api.whatsapp.com/send?text="+ARTICLE_URL,"whatsapp_share");
    });

    function open_window(url,name){
        window.open(url,name,"height=320,width=640,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no");
    }

});

//---
$(function(){
    //habilitando popover para ajax
     $(document).ajaxSuccess(function(){  
        $('[data-toggle="popover"]').popover();
    });
});
//---

function str_modal($type='',$id='modalPrincipal'){

    //modal principal
    //modal dynamic
    //modal submit

    $str = '';
    if($type == 'static')
        $str = '<div class="modal fade" id="'+$id+'" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content" id="modalContentStatic"></div></div></div>';
    else if($type == 'submit')
        $str = '<div class="modal fade" id="'+$id+'"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="modalTitleSubmit"></h4><button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-hidden="true"></button></div><div class="modal-body" id="modalBodySubmit"></div></div></div></div>';
    else if($type == 'alert')
        $rtn = '<div class="modal fade" id="'+$id+'" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body" id="modalBodyAlert"></div><div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button></div></div></div></div>';
    else if($type == 'confirm')
        $str = '<div class="modal fade" id="'+$id+'" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="modalTitleConfirm"></h4></div><div class="modal-body" id="modalBodyConfirm"></div><div class="modal-footer"><span id="modalFooterConfirm"></span><button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button></div></div></div></div>';
    else
        $str = '<div class="modal fade" id="'+$id+'"><div class="modal-dialog" id="modalDialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title truncate" id="modalTitle"></h4><button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-hidden="true"></button></div><div class="modal-body" id="modalBody"></div><div class="modal-msg" id="modalMsg"></div><div class="modal-footer"><span class="form-load" id="formLoad"></span><span id="modalFooterLeft"></span><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button><span id="modalFooter"></span></div></div></div></div>';
    return $str;

}

//---

$(function(){
    
    $(document).on("click","#guardarImg",function(){

        const canvas = document.querySelector("#result>canvas");
        const result = document.getElementById("result");
        
         if(result.innerHTML !== ''){

            let base64 = canvas.toDataURL();            

            var $attr    = $(this).attr("data-json");
            var $json    = JSON.parse($attr);  
          
            $json.imagen = base64;

            $(this).ajaxview({
                ajaxdestine : "admin/guardarImgPregBase64",
                ajaxdata    : $json
            });

        }else{
            alert("Recorte la imagen.");
        }
        
    });

});

// Paralel