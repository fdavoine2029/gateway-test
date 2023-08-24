$(document).ready(function () {

    var table = $('#logs_table').dataTable( {
        destroy: true,
        order: [[ 5, 'desc' ]],
        columnDefs: [
            { visible: false, targets: 0 },
            { width: 250, targets: 1 },
            { width: 150, targets: 2 },
            { width: 150, targets: 4 },
            { width: 150, targets: 5 }
        ],
        fixedColumns: true
    } );

});