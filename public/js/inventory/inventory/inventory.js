/**
 * Suppliers Tagify
 */
 var elm_supplier = document.querySelectorAll(`.input-supplier`);
 var elm_supplier_tagify = [];
 elm_supplier.forEach(function(elm){
     elm_supplier_tagify.push(initTagifySupplier(elm));
 });