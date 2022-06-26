$(document).on('click', '#next-form-pay-supplier', function(e){
    console.log("Next form button clicked.");
    console.log(elm_consign_order_tagify[0]);
    error_count = 0;

    $(".error-ps_consign_order").hide();

    if(elm_consign_order_tagify[0].value.length == 0)
    {
        console.log("Consign Order is empty.");
        $(".error-ps_consign_order").show().text("Consign Order is required.");
        error_count++;
    }

    if(error_count == 0)
    {
        $('#ps_body_1').hide();
        $('#ps_body_2').show();
        $('#ps_footer_1').attr('style', 'display:none');
        $('#ps_footer_2').attr('style', 'display:flex');
        $('#ps_modal_size').attr('class', 'modal-dialog modal-xl')
        $('#ps_consign-order_text').html(elm_consign_order_tagify[0].value[0].label);
    }
});

$(document).on('click', '#back-form-pay-supplier', function(e){
    $('#ps_body_1').show();
    $('#ps_body_2').hide();
    $('#ps_footer_1').attr('style', 'display:flex');
    $('#ps_footer_2').attr('style', 'display:none');
    $('#ps_modal_size').attr('class', 'modal-dialog');
});

var _paySupplierItems = $('#pay-supplier-items');

function loadProductsToPaySupplier(consign_order_id) {
    console.log(`Loading products to pay supplier.`);
    $('#pay-supplier-grand-total').html(`0.00`);

    $('#submit-form-pay-supplier').attr('disabled', true);

    // Clear the products table.
    _paySupplierItems.html("");

    // Load the products.
    var request = $.ajax({
        url: `/ajax/inventory/pay-supplier/${consign_order_id}`,
        method: "GET",
        dataType: "json", 
    });

    request.done(function(data) {
        console.log(`Products loaded.`);
        console.log(data);
        createPaySupplierItems(data);
        $('#submit-form-pay-supplier').attr('disabled', false);
    });

    request.fail(function(jqXHR, textStatus) {
        console.log(`Request failed: ${textStatus}`);
    });
}

function createPaySupplierItem(product) {
    var item = `
        <tr>
            <td>
                <input id="ps_item_${product.uuid}" type="checkbox" class="input-form-pay-supplier-item-checkbox" name="products[]" value="${product.uuid}"/>
            </td>
            <td>
                <label for="ps_item_${product.uuid}">${product.name}</label>
            </td>
            <td class="text-end">${product.quantity}</td>
            <td class="text-end">${product.quantity_sold}</td>
            <td class="text-end">${product.quantity_paid}</td>
            <td class="text-end">Php ${parseFloat(product.unit_price).toFixed(2)}</td>
            <td class="text-end">Php ${parseFloat(product.amount_to_pay).toFixed(2)}</td>
        </tr>
    `;

    return item;
}

function createPaySupplierItems(data) {
    var grand_total = 0;
    for (var i = 0; i < data.length; i++) {
        var item = createPaySupplierItem(data[i]);
        _paySupplierItems.append(item);
        grand_total += data[i].amount_to_pay;
    }
    $('#pay-supplier-grand-total').html(`${parseFloat(grand_total).toFixed(2)}`);
}