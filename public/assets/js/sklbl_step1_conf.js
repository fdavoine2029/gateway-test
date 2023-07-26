function step1_conf_addColumn(orderId,nbColumn) {
    nbColumn= nbColumn + 1;
    window.location.replace("/sklbl/step_1/configure_colum/"+orderId+"/"+nbColumn);
}
function step1_conf_deletecolumn(orderId,nbColumn) {
    nbColumn= nbColumn - 1;
    window.location.replace("/sklbl/step_1/configure_colum/"+orderId+"/"+nbColumn);
}