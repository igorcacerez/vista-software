$(document).ready(function(){

    // Ativa o select
    $(".selectBusca").on("click",function () {
        $(this).val("");
    });

    $(".selectBusca").selectize({
        options: [],
    });

});