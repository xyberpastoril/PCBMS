function initTagifyProduct(elm)
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
            searchKeys: ['name'] // very important to set by which keys to search for suggesttions when typing
        },
        templates: {
            tag: productItemTagTemplate,
            dropdownItem: productItemSuggestionTemplate
        },
        whitelist: [],
    });

    // Set events of tagify instance.
    // elm_tagify.on('dropdown:show dropdown:updated', onReceiptItemDropdownShow)
    elm_tagify.on('dropdown:select', onProductItemSelectSuggestion)
    elm_tagify.on('remove', onProductItemRemove)
    elm_tagify.on('input', onProductItemInput)

    return elm_tagify;
}

function onProductItemSelectSuggestion(e) {
    console.log(`Product selected : ${id}`);
    id = e.detail.tagify.DOM.originalInput.dataset.id;
}

function onProductItemRemove(e) {
    console.log(`Product removed from selection : ${id}`);
    id = e.detail.tagify.DOM.originalInput.dataset.id;
}

function onProductItemInput(e) {
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

    // Fetches the list of products based from the value,
    // then loads it if successful.
    fetch(`/ajax/products/search/${value}`, {
        signal: controller.signal,
    })
    .then(RES => RES.json())
    .then(function (newWhitelist) {
        // This updates the whitelist into the values fetched from the server.
        tagify.whitelist = newWhitelist;

        // Hide loading animation and show the suggestions dropdown.
        tagify.loading(false).dropdown.show(value);
    }).catch(function(error) {
        console.log("error");
        tagify.loading(false);
    });
}

function productItemTagTemplate(tagData){
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

function productItemSuggestionTemplate(tagData){
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
            <span>Birr ${tagData.sale_price}</span>
        </div>
    `
}