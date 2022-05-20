/**
 * Suppliers Tagify
 */
var elm_supplier = document.querySelector(`#input-form-receive-products-name`);
var elm_supplier_tagify = initTagifySupplier(elm_supplier);

/**
 * Products Tagify
 */

// This array lists down the tagify instances of the delivered products.
var received_products = [];

// This variable counts how many instances of delivered products are made.
// This ensures that there will be no conflict ids on selection.
var received_products_count = 0;

// Create a delivered product entry when document is fully loaded or when
// add entry button is clicked.
$(document).ready(createReceivedProductEntry());
$(document).on('click', '.rp_add_item_entry', 
    function() {createReceivedProductEntry()});

// Delete the delivered product entry when the row's delete button is clicked.
$(document).on('click', '.rp_item_delete', function(event) {
    removeReceivedProductEntry($(this)[0].dataset.id)
    $(this).parents('tr').remove();

    // If there are no longer item entries in table, generate a new one.
    if(received_products.length < 1) createReceivedProductEntry();
});

// TODO: Create event to adjust sales price when unit price is changed.
// TODO: There should be a checkmark whether to allow this or not.

/**
 * Auxilary Functions
 */

function createReceivedProductEntry(item = undefined)
{
    console.log("Creating received product item row.");
    // Increment count to avoid element conflicts.
    received_products_count++;

    // Create row template
    let inner = `
    <tr data-id=${received_products_count} id="rp_item_entry_${received_products_count}">
        <td>
            <button type="button" data-id="${received_products_count}" id="rp_item_delete_${received_products_count}" class="btn btn-sm btn-icon btn-danger rp_item_delete" data-toggle="tooltip" data-placement="bottom" title="Delete">
                <span class="icon text-white-50">
                    <i class="fas fa-trash"></i>
                </span>
            </button>
        </td>
        <td>
            <input data-id="${received_products_count}" id="rp_products_${received_products_count}" class="r_products form-control-sm" name="products[]"
        </td>
        <td>
            <input data-id="${received_products_count}" id="rp_particulars_${received_products_count}" type="text" class="form-control form-control-sm" name="particulars[]" placeholder="Enter Particulars" disabled>
        </td>
        <td>
            <input data-id="${received_products_count}" id="rp_expiration_dates_${received_products_count}" type="date" class="form-control form-control-sm" name="expiration_dates[]">
        </td>
        <td>
            <input data-id="${received_products_count}" id="rp_unit_prices_${received_products_count}" type="number" step="0.01" class="form-input-price form-control form-control-sm text-end" name="unit_prices[]" placeholder="0.00">
        </td>
        <td>
            <input data-id="${received_products_count}" id="rp_sale_prices_${received_products_count}" type="number" step="0.01" class="form-input-price form-control form-control-sm text-end" name="sale_prices[]" placeholder="0.00">
        </td>
        <td>
            <input data-id="${received_products_count}" id="rp_quantities_${received_products_count}" type="text" class="form-control form-control-sm" name="quantities[]" placeholder="0">
        </td>
    </tr>
    `;

    // Append template to the table.
    $("#receive-product-items").append(inner);

    // TODO: Set values if item exists.

    // Create new Tagify instance of item selector of newly created row.
    let elm = document.querySelector(`#rp_products_${received_products_count}`);
    let elm_tagify = initTagifyProduct(elm);

    // Push item to array receipt_items
    let item_entry = {
        "entry_id": received_products_count,
        "tagify": elm_tagify,
    }

    received_products.push(item_entry);
    console.log(received_products);
}

function removeReceivedProductEntry(entry_id)
{
    for(let i = 0; i < received_products.length; i++)
    {
        if(received_products[i].entry_id == entry_id)
        {   
            console.log("Removing entry " + entry_id);
            received_products.splice(i, 1);
            return true;
        }
    }   
    return false;
}

function getReceivedProductEntry(entry_id)
{

}

function getReceivedProductIndex(entry_id)
{

}