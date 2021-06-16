function notify(){

    $(document).ajaxview({
        ajaxdestine : "init/notificacion",
        ajaxdata    : {"idUsuario":_IDUSER}
    });

    setTimeout("notify()",4000);
}

$(function(){
    notify();
});

// Paralel