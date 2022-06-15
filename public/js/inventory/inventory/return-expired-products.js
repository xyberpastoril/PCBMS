var _returnExpiredProductItems = $('#return-expired-product-items');

function loadExpiredProductsToReturn(supplier_uuid) {
    console.log(`Loading products to pay supplier.`);

    $('#submit-form-return-expired-products').attr('disabled', true);

    // Clear the products table.
    _returnExpiredProductItems.html("");

    // Load the products.
    var request = $.ajax({
        url: `/ajax/inventory/return-expired-products/${supplier_uuid}`,
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