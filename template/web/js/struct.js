
//--- modal

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

//--- copy

$(function(){

    $(document).on('click','.copy-title',function(){

        let copyTitle = $(this);
        let title     = copyTitle.text();

        let copyText = $('.copy-text');

        if(title == 'Copiar'){
                
            copyText.select();
            document.execCommand('copy');

            copyTitle.html('Copiado');

            setTimeout(function(){
               copyTitle.html(title);
            },700);
        }

    });

});

/*
const modalP = document.querySelector('#modalPrincipal');

const copyText  = modalP.querySelector('.copy-text');
const copyTitle = modalP.querySelector('.copy-title');

copyTitle.addEventListener('click',initCopy(),false);

function initCopy(){
    
    if(copyTitle.innerHTML== 'Copiar'){
        
        copyText.select();
        document.execCommand('copy');
 
        copyTitle.innerHTML = 'Copiado';
        
        setTimeout(function(){
           copyTitle.innerHTML = 'Copiar';
        },700);
    }
}
*/

//--- ...



// Paralel