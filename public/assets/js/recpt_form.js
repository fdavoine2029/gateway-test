$(document).ready(function () {
    $('#numBlFou2').html('BL: ' + $('#numBlFou').val());
    $('#numBlFou2').html('BL: ' + $('#numBlFou').val());
    $('#numBlFou').change(function() {
        $('#numBlFou2').html('BL: ' + $('#numBlFou').val());
        $('#numBlFou3').html('BL: ' + $('#numBlFou').val());
        if($('#numBlFou').val() != ""){
            $('#btn_papade').prop('disabled',false);
        }else{
            $('#btn_papade').prop('disabled',true);
        }
    });
    

});

function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}

function print_papade(ref,lot,orderNum) {
    var num_bl = $('#numBlFou').val();
    //window.open("/recpt/generateQrCodePdf/"+ref+"/"+lot+"/"+orderNum+"/"+num_bl);
    window.open("/recpt/generateBarCodePdf/"+ref+"/"+lot+"/"+orderNum+"/"+num_bl);
}



