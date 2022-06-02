var unit_tagify;

function initTagifyUnit(elm, item = undefined)
{
    var whitelist = [];

    console.log(elm);
    

    if(item != undefined) {
        whitelist = [
            {
                'value': item.uuid,
                'id': item.id,
                'name': item.name,
                'abbreviation': item.abbreviation,
            }
        ];

        
    }

    if(elm.dataset.tagified != 1) {
        let elm_tagify = new Tagify(elm, {
            tagTextProp: 'abbreviation', // very important since a custom template is used with this property as text
            enforceWhitelist: true,
            mode: "select",
            skipInvalid: false, // do not remporarily add invalid tags
            dropdown: {
                closeOnSelect: true,
                enabled: 0,
                classname: 'customer-list',
                searchKeys: ['name', 'abbreviation'] // very important to set by which keys to search for suggesttions when typing
            },
            templates: {
                tag: UnitItemTagTemplate,
                dropdownItem: UnitItemSuggestionTemplate
            },
            whitelist: whitelist,
        });
    
        // Set events of tagify instance.
        // elm_tagify.on('dropdown:show dropdown:updated', onReceiptItemDropdownShow)
        elm_tagify.on('dropdown:select', onUnitItemSelectSuggestion)
        elm_tagify.on('remove', onUnitItemRemove)
        elm_tagify.on('input', onUnitItemInput)
    
        elm.dataset.tagified = 1;
        unit_tagify = elm_tagify;
    }
    else {
        elm_tagify = unit_tagify;
        elm_tagify.whitelist = whitelist;
    }

    if(item != undefined) {
        elm.value = item.name;
    }

    return elm_tagify;
}

function onUnitItemSelectSuggestion(e) {
    id = e.detail.tagify.DOM.originalInput.dataset.id;
    console.log(`Unit selected : ${id}`);
}

function onUnitItemRemove(e) {
    id = e.detail.tagify.DOM.originalInput.dataset.id;
    console.log(`Unit removed from selection : ${id}`);
}

function onUnitItemInput(e) {
    console.log("Unit Input.");
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

    // Fetches the list of Units based from the value,
    // then loads it if successful.
    fetch(`/ajax/search/units/${value}`, {
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

function UnitItemTagTemplate(tagData){
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
                <span class='tagify__tag-text'>${tagData.abbreviation}</span>
            </div>
        </tag>
    `
}

function UnitItemSuggestionTemplate(tagData){
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
            <strong>${tagData.abbreviation}</strong><br>
            <span>${tagData.name}</span>
        </div>
    `
}