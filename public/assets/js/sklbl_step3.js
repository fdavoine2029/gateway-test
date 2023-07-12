function import_ofs(orderId) {
    window.open("/sklbl/import_ofs/"+orderId);
}

function confirmer_of(orderId,ofId) {
    window.location.replace("/sklbl/confirmer_of/"+orderId+"/"+ofId);
}