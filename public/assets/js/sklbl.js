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
        if(data[2] == 1){
            window.location.replace("/sklbl/step_2/"+data[0]); 
        }
        if(data[2] == 2){
            window.location.replace("/sklbl/step_3/"+data[0]); 
        }
        if(data[2] == 3){
            window.location.replace("/sklbl/step_4/"+data[0]); 
        }

        
    });
});