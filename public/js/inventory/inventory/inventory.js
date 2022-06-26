/**
 * Suppliers Tagify
 */
 var elm_supplier = document.querySelectorAll(`.input-supplier`);
 var elm_supplier_tagify = [];
 elm_supplier.forEach(function(elm){
     elm_supplier_tagify.push(initTagifySupplier(elm));
 });

 /**
 * Consign Orders Tagify
 */
  var elm_consign_order = document.querySelectorAll(`.input-consign_order`);
  var elm_consign_order_tagify = [];
  elm_consign_order.forEach(function(elm){
      elm_consign_order_tagify.push(initTagifyConsignOrder(elm));
  });