function initTagifyConsignOrder(elm)
{
    let elm_tagify = new Tagify(elm, {
        tagTextProp: 'label', // very important since a custom template is used with this property as text
        enforceWhitelist: true,
        mode: "select",
        skipInvalid: false, // do not remporarily add invalid tags
        dropdown: {
            closeOnSelect: true,
            enabled: 0,
            classname: 'customer-list',
            searchKeys: ['label'] // very important to set by which keys to search for suggesttions when typing
        },
        templates: {
            tag: ConsignOrderItemTagTemplate,
            dropdownItem: ConsignOrderItemSuggestionTemplate
        },
        whitelist: [],
    });

    // Set events of tagify instance.
    // elm_tagify.on('dropdown:show dropdown:updated', onReceiptItemDropdownShow)
    elm_tagify.on('dropdown:select', onConsignOrderItemSelectSuggestion)
    elm_tagify.on('remove', onConsignOrderItemRemove)
    elm_tagify.on('input', onConsignOrderItemInput)

    return elm_tagify;
}

function onConsignOrderItemSelectSuggestion(e) {
    console.log(e);
    id = e.detail.data.value;
    console.log(`ConsignOrder selected : ${id}`);

    action = e.detail.tagify.DOM.originalInput.dataset.action;
    try {
        if(action == 'pay-supplier') {
            loadProductsToPaySupplier(id);
        }
        else if(action == 'return-products') {
            loadExpiredProductsToReturn(id);
        }
    }
    catch(err) {
        console.log(`Can't proceed with ${action} after consign order selection event.`);
    }
}

function onConsignOrderItemRemove(e) {
    id = e.detail.tagify.DOM.originalInput.dataset.id;
    console.log(`ConsignOrder removed from selection : ${id}`);
}

function onConsignOrderItemInput(e) {
    console.log("ConsignOrder Input.");
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

    // Fetches the list of ConsignOrders based from the value,
    // then loads it if successful.
    fetch(`/ajax/search/consign-orders/${value}`, {
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

function ConsignOrderItemTagTemplate(tagData){
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
                <span class='tagify__tag-text'>${tagData.label}</span>
            </div>
        </tag>
    `
}

function ConsignOrderItemSuggestionTemplate(tagData){
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
            <strong>${tagData.label}</strong><br>
            <small>${tagData.order_delivered_at}</small>
        </div>
    `
}