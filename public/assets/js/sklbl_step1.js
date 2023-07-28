$(document).ready(function () {

    var table = $('#sku_table').dataTable( {
        destroy: true,
        order: [[ 1, 'asc' ],[ 0, 'desc' ]],

        fixedColumns: true
    } );

});

function delete_file(id) {
    window.location.replace("/sklbl/step_1/delete_sku/"+id);
}

function step1_conf_column(orderId,nbColumn) {
    window.location.replace("/sklbl/step_1/configure_colum/"+orderId+"/"+nbColumn);
}