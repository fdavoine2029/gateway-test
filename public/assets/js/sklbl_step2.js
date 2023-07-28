$(document).ready(function () {

    var table = $('#sku2_table').dataTable( {
        destroy: true,
        order: [[ 1, 'desc' ],[ 0, 'desc' ]],
        columnDefs: [
            //{ visible: false, targets: 0 },
            //{ visible: false, targets: 1 }

        ],
        fixedColumns: true
    } );

});