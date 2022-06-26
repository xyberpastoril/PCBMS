$(document).on('click', '#next-form-return-expired-products', function(e){
    console.log("Next form button clicked.");
    console.log(elm_consign_order_tagify[1]);
    error_count = 0;

    $(".error-rep_consign_order").hide();

    if(elm_consign_order_tagify[1].value.length == 0)
    {
        console.log("Consign Order is empty.");
        $(".error-rep_consign_order").show().text("Consign Order is required.");
        error_count++;
    }

    if(error_count == 0)
    {
        $('#rep_body_1').hide();
        $('#rep_body_2').show();
        $('#rep_footer_1').attr('style', 'display:none');
        $('#rep_footer_2').attr('style', 'display:flex');
        $('#rep_modal_size').attr('class', 'modal-dialog modal-xl')
        $('#rep_consign-order_text').html(elm_consign_order_tagify[1].value[0].label);
    }
});

$(document).on('click', '#back-form-return-expired-products', function(e){
    $('#rep_body_1').show();
    $('#rep_body_2').hide();
    $('#rep_footer_1').attr('style', 'display:flex');
    $('#rep_footer_2').attr('style', 'display:none');
    $('#rep_modal_size').attr('class', 'modal-dialog');
});



var _returnExpiredProductItems = $('#return-expired-product-items');

function loadExpiredProductsToReturn(consign_order_id) {
    console.log(`Loading products to pay supplier.`);

    $('#submit-form-return-expired-products').attr('disabled', true);

    // Clear the products table.
    _returnExpiredProductItems.html("");

    // Load the products.
    var request = $.ajax({
        url: `/ajax/inventory/return-expired-products/${consign_order_id}`,
        method: "GET",
        dataType: "json",
    });

    request.done(function(data) {
        console.log(`Products loaded.`);
        console.log(data);
        createReturnExpiredProductItems(data);
        $('#submit-form-return-expired-products').attr('disabled', false);
    });

    request.fail(function(jqXHR, textStatus) {
        console.log(`Request failed: ${textStatus}`);
    });
}

function createReturnExpiredProductItem(product) {
    var item = `
        <tr>
            <td>
                <input id="rep_item_${product.uuid}" type="checkbox" class="input-form-return-expired-products-item-checkbox" name="products[]" value="${product.uuid}"/>
            </td>
            <td>
                <label for="rep_item_${product.uuid}">${product.name}</label>
                </td>
            <td class="text-end">${product.expiration_date}</td>
            <td class="text-end">${product.quantity}</td>
            <td class="text-end">${product.quantity_sold == null ? 0 : product.quantity_sold}</td>
            <td class="text-end">${product.quantity_to_return}</td>
        </tr>
    `;

    return item;
}

function createReturnExpiredProductItems(data) {
    for (var i = 0; i < data.length; i++) {
        var item = createReturnExpiredProductItem(data[i]);
        _returnExpiredProductItems.append(item);
    }
}