function initTagifySupplier(elm)
{
    let elm_tagify = new Tagify(elm, {
        tagTextProp: 'name', // very important since a custom template is used with this property as text
        enforceWhitelist: true,
        mode: "select",
        skipInvalid: false, // do not remporarily add invalid tags
        dropdown: {
            closeOnSelect: true,
            enabled: 0,
            classname: 'customer-list',
            searchKeys: ['name', 'mobile_number'] // very important to set by which keys to search for suggesttions when typing
        },
        templates: {
            tag: SupplierItemTagTemplate,
            dropdownItem: SupplierItemSuggestionTemplate
        },
        whitelist: [],
    });

    // Set events of tagify instance.
    // elm_tagify.on('dropdown:show dropdown:updated', onReceiptItemDropdownShow)
    elm_tagify.on('dropdown:select', onSupplierItemSelectSuggestion)
    elm_tagify.on('remove', onSupplierItemRemove)
    elm_tagify.on('input', onSupplierItemInput)

    return elm_tagify;
}

function onSupplierItemSelectSuggestion(e) {
    id = e.detail.tagify.DOM.originalInput.id;
    console.log(`Supplier selected : ${id}`);
    console.log(e);

    if(id == 'input-form-pay-supplier-supplier') {
        console.log(`Seleced item with UUID: ${e.detail.data.value}`);
        try {
            loadProductsToPaySupplier(e.detail.data.value);
        } catch(err) {
            console.log(`Error loading products to pay supplier. Please check if "pay_supplier.js" is loaded.`);
            console.log(err);
        }
    }
}

function onSupplierItemRemove(e) {
    id = e.detail.tagify.DOM.originalInput.id;
    console.log(`Supplier removed from selection : ${id}`);

    if(id == 'input-form-pay-supplier-supplier') {
        try {
            $('#pay-supplier-grand-total').html(`0.00`);
            $('#pay-supplier-items').html("");
        } catch(err) {
            console.log(`Error running special tasks intended for ${id}. Please check if "pay_supplier.js" is loaded.`);
            console.log(err);
        }
    }
}

function onSupplierItemInput(e) {
    console.log("Supplier Input.");
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

    // Fetches the list of Suppliers based from the value,
    // then loads it if successful.
    fetch(`/ajax/search/suppliers/${value}`, {
        signal: controller.signal,
    })
    .then(RES => RES.json())
    .then(function (newWhitelist) {
        // This updates the whitelist into the values fetched from the server.
        tagify.whitelist = newWhitelist;
        console.log(tagify.whitelist);

        // Hide loading animation and show the suggestions dropdown.
        tagify.loading(false).dropdown.show(value);
    }).catch(function(error) {
        console.log(error);
        tagify.loading(false);
    });
}

function SupplierItemTagTemplate(tagData){
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

function SupplierItemSuggestionTemplate(tagData){
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
            <span>${tagData.physical_address}</span>
        </div>
    `
}