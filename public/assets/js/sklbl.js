$(document).ready(function () {

    var table = $('#ofs_table').dataTable( {
        destroy: true,
        order: [[ 3, 'desc' ],[ 4, 'desc' ]],
        columnDefs: [
            //{ visible: false, targets: 0 },
            //{ visible: false, targets: 1 }

        ],
        fixedColumns: true
    } );
 

     
     var table = $('#ofs_table').DataTable();
     $('#ofs_table').on('click', 'tr', function () {
        var data = table.row(this).data();
        if(data[2] == 0){
            window.location.replace("/sklbl/step_1/"+data[0]); 
        }

        
    });
});