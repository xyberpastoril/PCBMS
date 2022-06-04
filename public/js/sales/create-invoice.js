/**
 * customers Tagify
 */
var elm_customer = document.querySelector(`#input-form-sell-products-customer`);
console.log($('#input-form-sell-products-customer'));
var elm_customer_tagify = initTagifyCustomer(elm_customer);

/**
 * Products Tagify
 */

// This array lists down the tagify instances of the delivered products.
var consigned_products = [];

// This variable counts how many instances of delivered products are made.
// This ensures that there will be no conflict ids on selection.
var consigned_products_count = 0;

// Create a delivered product entry when document is fully loaded or when
// add entry button is clicked.
$(document).ready(createSoldProductEntry());
$(document).on('click', '.sp_add_item_entry',
    function () {
        createSoldProductEntry();        
    });

// Delete the delivered product entry when the row's delete button is clicked.
$(document).on('click', '.sp_item_delete', function (event) {
    removeSoldProductEntry($(this)[0].dataset.id)
    $(this).parents('tr').remove();

    // If there are no longer item entries in table, generate a new one.
    if (consigned_products.length < 1) createSoldProductEntry();

    displayGrandTotal();
});

// Update total amount when quantity is changed.
$(document).on('change', '.sp_quantities', function (event) {
    let data_id = $(this)[0].dataset.id;
    let quantity = $(this).val();
    let sale_price = $(`#sp_sale_prices_${data_id}`).text();
    displayItemTotal(data_id, quantity, sale_price);
    displayGrandTotal();
});

/**
 * Auxilary Functions
 */

function createSoldProductEntry(item = undefined) {
    console.log("Creating sold product item row.");
    // Increment count to avoid element conflicts.
    consigned_products_count++;

    // Create row template
    let inner = `
    <tr data-id=${consigned_products_count} id="sp_item_entry_${consigned_products_count}">
        <td>
            <button type="button" data-id="${consigned_products_count}" id="sp_item_delete_${consigned_products_count}" class="btn btn-sm btn-icon btn-danger sp_item_delete" data-toggle="tooltip" data-placement="bottom" title="Delete">
                <span class="icon text-white-50">
                    <i class="fas fa-trash"></i>
                </span>
            </button>
        </td>
        <td>
            <input data-id="${consigned_products_count}" data-sp="1" id="sp_products_${consigned_products_count}" class="sp_products form-control-sm" name="products[]">
            <p class="text-danger error error-sp_products" style="display:none"></p>
        </td>
        <td>
            <input data-id="${consigned_products_count}" id="sp_quantities_${consigned_products_count}" type="number" class="sp_quantities form-control form-control-sm" name="quantities[]" placeholder="0" disabled required min="1">
            <p class="text-danger error error-sp_quantities" style="display:none"></p>
        </td>
        <td>
            <p class="text-end text-muted mb-0"><strong data-id="${consigned_products_count}" id="sp_sale_prices_${consigned_products_count}" class="sp_unit_prices">0.00</strong></p>
        </td>
        <td>
            <p class="text-end mb-0"><strong data-id="${consigned_products_count}" id="sp_total_prices_${consigned_products_count}" class="sp_total_prices">0.00</strong></p>
        </td>
    </tr>
    `;

    // Append template to the table.
    $("#sold-product-items").append(inner);

    // TODO: Set values if item exists.

    // Create new Tagify instance of item selector of newly created row.
    let elm = document.querySelector(`#sp_products_${consigned_products_count}`);
    let elm_tagify = initTagifyConsignedProduct(elm);

    // Push item to array receipt_items
    let item_entry = {
        "entry_id": consigned_products_count,
        "tagify": elm_tagify,
    }

    consigned_products.push(item_entry);
    console.log(consigned_products);
}

function removeSoldProductEntry(entry_id) {
    for (let i = 0; i < consigned_products.length; i++) {
        if (consigned_products[i].entry_id == entry_id) {
            console.log("Removing entry " + entry_id);
            consigned_products.splice(i, 1);
            return true;
        }
    }
    return false;
}

function getSoldProductEntry(entry_id) {

}

function getSoldProductIndex(entry_id) {

}

/**=== Calculation Functions ===*/

function displayGrandTotal()
{
    let grandTotal = 0;
    item_total_prices = document.querySelectorAll(".sp_total_prices");
    console.log("Calculate Receipt grandTotal:");
    console.log(item_total_prices);

    item_total_prices.forEach(function(item_total_price){
        console.log(item_total_price.innerHTML);
        grandTotal += item_total_price.innerHTML != '' ? parseFloat(item_total_price.innerHTML) : 0;
    });

    $(`#sp_total_amount`).html(parseFloat(grandTotal).toFixed(2))
}

function displayItemTotal(data_id, quantity, sale_price)
{
    let itemTotal = parseInt(quantity) * parseFloat(sale_price);
    $(`#sp_total_prices_${data_id}`).text(parseFloat(itemTotal).toFixed(2));
}