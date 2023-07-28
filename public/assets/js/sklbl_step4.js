$(document).ready(function () {

    var table = $('#fx_table').dataTable( {
        destroy: true,
        order: [[ 0, 'asc' ],[ 4, 'desc' ]],
        columnDefs: [
            //{ visible: false, targets: 0 },
            //{ visible: false, targets: 1 }

        ],
        fixedColumns: true
    } );

});

function generate_f1(ofId) {
    window.location.replace("/sklbl/generate_f1/"+ofId);
}

function ask_transfert(ofId) {
    window.location.replace("/sklbl/ask_transfert/"+ofId);
}

function conf_reception(ofId) {
    window.location.replace("/sklbl/conf_reception/"+ofId);
}

