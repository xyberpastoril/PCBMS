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