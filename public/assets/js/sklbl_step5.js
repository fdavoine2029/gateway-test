function step5_conf_addColumn(orderId,nbColumn) {
    nbColumn= nbColumn + 1;
    window.location.replace("/sklbl/step_5/"+orderId+"/"+nbColumn);
}
function step5_conf_deletecolumn(orderId,nbColumn) {
    nbColumn= nbColumn - 1;
    window.location.replace("/sklbl/step_5/"+orderId+"/"+nbColumn);
}