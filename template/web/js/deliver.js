function updateDeliver(uniqid){

    $(document).ajaxview({
        ajaxdestine : 'admin/listaEntregar',
        ajaxdata    : {'uniqid':uniqid}
    });

    setTimeout('updateDeliver(\''+uniqid+'\')',4000);
}

// Paralel