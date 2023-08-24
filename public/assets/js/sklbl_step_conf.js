function step1_conf_addColumn(orderId,nbColumn) {
    nbColumn= nbColumn + 1;
    window.location.replace("/sklbl/step_1/configure_colum/"+orderId+"/"+nbColumn);
}
function step1_conf_deletecolumn(orderId,nbColumn) {
    nbColumn= nbColumn - 1;
    window.location.replace("/sklbl/step_1/configure_colum/"+orderId+"/"+nbColumn);
}

function step_conf_remove(orderId,field) {
    alert(field)
   // window.location.replace("/sklbl/step_1/configure_colum/"+orderId+"/"+nbColumn);
}


var myModal = document.getElementById('myModal')
var myInput = document.getElementById('myInput')

myModal.addEventListener('shown.bs.modal', function () {
  myInput.focus()
})

function step_conf_addModel(orderId) {
    $modelName = $('#nom_model').val();
    if($modelName != ''){
        window.location.replace("/sklbl/step_conf_add_model/"+orderId+"/"+$modelName);
    }else{
        alert("Compléter le nom du modèle!")
    }
    
}

function step_conf_deleteModel(orderId) {
    $idModel = $('#sklbl_upload_model_form_SklblModel').val();
    window.location.replace("/sklbl/step_conf_delete_model/"+orderId+"/"+$idModel);
}

function step_conf_saveModel(orderId) {
    $idModel = $('#sklbl_upload_model_form_SklblModel').val();
    window.location.replace("/sklbl/step_conf_save_model/"+orderId+"/"+$idModel);
}

function step_conf_loadModel(orderId) {
    $idModel = $('#sklbl_upload_model_form_SklblModel').val();
    window.location.replace("/sklbl/step_conf/"+orderId+"/none/"+$idModel+"/1");
}