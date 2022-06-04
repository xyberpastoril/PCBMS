function initTagifyConsignedProduct(elm, item = null)
{
    var whitelist = [];
    if(item != undefined) {
        whitelist = [
            {
                'value': item.uuid,
                'id': item.id,
                'name': item.name,
                'sale_price': sale_price,
            }
        ];
    }

    let elm_tagify = new Tagify(elm, {
        tagTextProp: 'name', // very important since a custom template is used with this property as text
        enforceWhitelist: true,
        mode: "select",
        skipInvalid: false, // do not remporarily add invalid tags
        dropdown: {
            closeOnSelect: true,
            enabled: 0,
            classname: 'customer-list',
            searchKeys: ['name', 'id'] // very important to set by which keys to search for suggesttions when typing
        },
        templates: {
            tag: consignedProductItemTagTemplate,
            dropdownItem: consignedProductItemSuggestionTemplate
        },
        whitelist: whitelist,
    });

    // Set events of tagify instance.
    // elm_tagify.on('dropdown:show dropdown:updated', onReceiptItemDropdownShow)
    elm_tagify.on('dropdown:select', onConsignedProductItemSelectSuggestion)
    elm_tagify.on('remove', onConsignedProductItemRemove)
    elm_tagify.on('input', onConsignedProductItemInput)

    return elm_tagify;
}

function onConsignedProductItemSelectSuggestion(e) {
    console.log(`Selected a consigned product.`);
    // console.log(e);
    let data = e.detail.data;
    let elm = e.detail.tagify.DOM.originalInput;

    console.log(data);
    console.log(elm.dataset.id);

    $(`#sp_quantities_${elm.dataset.id}`).removeAttr('disabled').val(1);
    $(`#sp_sale_prices_${elm.dataset.id}`).html(parseFloat(data.sale_price).toFixed(2));

    displayItemTotal(elm.dataset.id, 1, data.sale_price);
    displayGrandTotal();
}

function onConsignedProductItemRemove(e) {
    console.log(`Removed a consigned product.`);
    console.log(e);

    let data = e.detail.data;
    let elm = e.detail.tagify.DOM.originalInput;

    $(`#sp_quantities_${elm.dataset.id}`).attr('disabled', true).val(0);
    $(`#sp_sale_prices_${elm.dataset.id}`).html(parseFloat(0).toFixed(2));

    displayItemTotal(elm.dataset.id, 0, 0);
    displayGrandTotal();
}

function onConsignedProductItemInput(e) {
    var value = e.detail.value;
    var tagify = e.detail.tagify;

    // This resets the whitelist.
    tagify.whitelist = null;

    // Allows abortion of previous request from previous input.
    // https://developer.mozilla.org/en-US/docs/Web/API/AbortController/abort
    controller && controller.abort();
    controller = new AbortController();

    // Show loading animation and hide the suggestions dropdown.
    tagify.loading(true).dropdown.hide();

    // Fetches the list of consignedProducts based from the value,
    // then loads it if successful.
    fetch(`/ajax/search/consigned-products/${value}`, {
        signal: controller.signal,
    })
    .then(RES => RES.json())
    .then(function (newWhitelist) {
        // This updates the whitelist into the values fetched from the server.
        tagify.whitelist = newWhitelist;

        // Hide loading animation and show the suggestions dropdown.
        tagify.loading(false).dropdown.show(value);
    }).catch(function(error) {
        console.log(error);
        tagify.loading(false);
    });
}

function consignedProductItemTagTemplate(tagData){
    return `
        <tag title="${tagData.email}"
                contenteditable='false'
                spellcheck='false'
                tabIndex="-1"
                class="tagify__tag ${tagData.class ? tagData.class : ""}"
                ${this.getAttributes(tagData)}>
            <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
            <div>
                <div class='tagify__tag__avatar-wrap'>
                    <img onerror="this.style.visibility='hidden'" src="${tagData.avatar}">
                </div>
                <span class='tagify__tag-text'>${tagData.name}</span>
            </div>
        </tag>
    `
}

function consignedProductItemSuggestionTemplate(tagData){
    return `
        <div ${this.getAttributes(tagData)}
            class='tagify__dropdown__item ${tagData.class ? tagData.class : ""}'
            tabindex="0"
            role="option">
            ${ tagData.avatar ? `
            <div class='tagify__dropdown__item__avatar-wrap'>
                <img onerror="this.style.visibility='hidden'" src="${tagData.avatar}">
            </div>` : ''
            }
            <strong>${tagData.name}</strong><br>
            <span>Php ${parseFloat(tagData.sale_price).toFixed(2)}</span>
        </div>
    `
}