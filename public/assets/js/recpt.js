$(document).ready(function () {

    var table = $('#receptions_table').dataTable( {
        destroy: true,
        order: [[ 16, 'desc' ], [ 0, 'desc' ]],
        columnDefs: [
            { visible: false, targets: 1 },
            { width: 50, targets: 2 },
            { width: 50, targets: 3 },
            { width: 100, targets: 4 },
            { width: 100, targets: 5 },
            { width: 180, targets: 8 },
            { visible: false, targets: 19 }
        ],
        fixedColumns: true
    } );
   /* setInterval( function () {
        table.ajax.reload();
    }, 10000 );*/


    $('#hideReceived').change(function() {
        if($(this).is(":checked")) {
           window.location.replace("/recpt/recpt/show/1/"+$('#dateLimit').val());
        }else{
           window.location.replace("/recpt/recpt/show/0/"+$('#dateLimit').val());     
        }
     });
     $('#dateLimit').change(function() {
        if($('#hideReceived').is(":checked")){
            window.location.replace("/recpt/recpt/show/1/"+$(this).val()); 
        }else{
            window.location.replace("/recpt/recpt/show/0/"+$(this).val()); 
        }

     });

     
     var table = $('#receptions_table').DataTable();
     $('#receptions_table').on('click', 'tr', function () {
        var data = table.row(this).data();
        window.location.replace("/recpt/recpt/add/"+data[1]+"/"+data[19]); 
    });
});
