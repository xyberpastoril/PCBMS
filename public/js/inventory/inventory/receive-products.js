$(document).on('click', '#next-form-receive-products', function(e){
    console.log("Next form button clicked.");
    console.log(elm_supplier_tagify[0]);
    error_count = 0;

    $(".error-rp_supplier").hide();
    $(".error-rp_date").hide();

    if(elm_supplier_tagify[0].value.length == 0)
    {
        console.log("Supplier is empty.");
        $(".error-rp_supplier").show().text("Supplier is required.");
        error_count++;
    }
    if($("#input-form-receive-products-date").val() == "")
    {
        console.log("Date is empty.");
        $(".error-rp_date").show().text("Date is required.");
        error_count++;
    }

    var request = $.ajax({
        url: '/ajax/inventory/count/orders',
        type: 'GET',
    });

    request.done(function(data) {
        console.log(data);
        $('#rp_order_text').html(parseInt(data) + 1);
    });

    if(error_count == 0)
    {
        $('#rp_body_1').hide();
        $('#rp_body_2').show();
        $('#rp_footer_1').attr('style', 'display:none');
        $('#rp_footer_2').attr('style', 'display:flex');
        $('#rp_modal_size').attr('class', 'modal-dialog modal-xl')
        $('#rp_supplier_text').html(elm_supplier_tagify[0].value[0].name);
        $('#rp_date_text').html($("#input-form-receive-products-date").val());
    }
    
});

$(document).on('click', '#back-form-receive-products', function(e){
    $('#rp_body_1').show();
    $('#rp_body_2').hide();
    $('#rp_footer_1').attr('style', 'display:flex');
    $('#rp_footer_2').attr('style', 'display:none');
    $('#rp_modal_size').attr('class', 'modal-dialog');
});

setInterval(function(){
    console.log("Updating potential ID (automatic)");
    var request = $.ajax({
        url: '/ajax/inventory/count/orders',
        type: 'GET',
    });

    request.done(function(data) {
        console.log(data);
        $('#rp_order_text').html(parseInt(data) + 1);
    });
}, 30000);

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

$(document).on('change', '.rp_unit_prices', function(event) {
    var id = $(this)[0].dataset.id;
    var unit_price = $(this).val();
    console.log(id);
    var sale_price = $(this).val() * 1.10;

    if($(`#rp_sale_prices_${id}`).val() == "")
    {
        $(`#rp_sale_prices_${id}`).val(parseFloat(sale_price).toFixed(2));
    }
});

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
            <input data-id="${received_products_count}" data-rp="1" id="rp_products_${received_products_count}" class="rp_products form-control-sm" name="products[]">
            <p class="text-danger error error-rp_products" style="display:none"></p>
        </td>
        <td>
            <!-- input group -->
            <div class="input-group input-group-sm">
                <input data-id="${received_products_count}" id="rp_particulars_${received_products_count}" type="text" class="rp_particulars form-control form-control-sm text-end" name="particulars[]" placeholder="Enter Particulars" disabled required>
                <span class="input-group-text" id="rp_unit_${received_products_count}"></span>
            </div>
            <p class="text-danger error error-rp_particulars" style="display:none"></p>
        </td>
        <td>
            <input data-id="${received_products_count}" id="rp_expiration_dates_${received_products_count}" type="date" class="rp_expiration_dates form-control form-control-sm" name="expiration_dates[]" disabled required>
            <p class="text-danger error error-rp_expiration_dates" style="display:none"></p>
        </td>
        <td>
            <input data-id="${received_products_count}" id="rp_unit_prices_${received_products_count}" type="number" step="0.01" class="rp_unit_prices form-input-price form-control form-control-sm text-end" name="unit_prices[]" placeholder="0.00" disabled required>
            <p class="text-danger error error-rp_unit_prices" style="display:none"></p>
        </td>
        <td>
            <input data-id="${received_products_count}" id="rp_sale_prices_${received_products_count}" type="number" step="0.01" class="rp_sale_prices form-input-price form-control form-control-sm text-end" name="sale_prices[]" placeholder="0.00" disabled required>
            <p class="text-danger error error-rp_sale_prices" style="display:none"></p>
        </td>
        <td>
            <input data-id="${received_products_count}" id="rp_quantities_${received_products_count}" type="text" class="rp_quantities form-control form-control-sm" name="quantities[]" placeholder="0" disabled required>
            <p class="text-danger error error-rp_quantities" style="display:none"></p>
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