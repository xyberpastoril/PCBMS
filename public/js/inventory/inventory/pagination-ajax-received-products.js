class ReceivedProductsPaginationAjax {
    /**
     * Creates an instance of PaginationAjax. 
     * 
     * @param {*} url : Stores the request url
     * @param {*} modelName : The name of the model involved (e.g. suppliers)
     * @param {*} tbodyElement : The tbody tag element of the table.
     * @param {*} btnGroupElement : The button group <div> tag element of the table.
     * @param {*} spinnerElement: The spinner element.
     */
    constructor(args) {
        this.url = args.url;
        this.ajaxUrl = args.ajaxUrl;
        this.modelName = args.modelName;
        this.columns = args.columns;
        this.actions = args.actions;
        this.customActionsHtml = args.customActionsHtml;

        this.tbodyElement = $(`#table-content-${args.modelName}`);
        this.btnGroupElement = $(`#table-links-${args.modelName}`);
        this.spinnerElement = $(`#table-spinner-${args.modelName}`);
        this.linksButtonElements = $(`.btn-paginate-${args.modelName}`);

    }

    requestData(ajaxUrl = null) 
    {
        this.spinnerElement.show();
        this.enableLinksButtonElements(false);

        this.request = $.ajax({
            url: ajaxUrl ? ajaxUrl : this.ajaxUrl,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            method: 'GET',
        });

        return this.request;
    }

    displayTableRows(rows) 
    {
        this.tbodyElement.html("");

        console.log("Columns:");
        console.log(this.columns);

        var columns = this.columns;
        var actions = this.actions;
        var modelName = this.modelName;

        var inner = '';
        rows.forEach(function(row){           
            // Add the opening tag and actions column of the row.
            inner += `
            <tr>
                <td>
                    ${displayActionButtonsReceivedProducts(row.id, row.uuid, modelName, actions)}
                </td>
                `

            // Add the values of each column.
            columns.forEach(function(c){
                inner += `
                    <td>${
                        c == 'unit_price' || c == 'sale_price' || c == 'amount'
                        ? '₱ '
                        : ''    
                    }${row[c]}${
                        c == 'particulars' ? row['unit'] : ''
                    }</td>
                `
            });

            // Add the closing tag of the row.
            inner += `
            </tr>
            `;
        });

        this.tbodyElement.append(inner);
        
        // Add events to the buttons
        actionsAddEventListenersReceivedProducts(this.modelName, this.ajaxUrl, this.actions);
    }

    displayPaginationButtons(links) 
    {
        this.btnGroupElement.html("");

        var modelName = this.modelName;
        var inner = '';
        links.forEach(function(link) {
            inner += `
                <button type="button" class="btn btn-${link.active ? '' : 'outline-'}primary btn-paginate-${modelName}" data-href="${link.url}" ${link.url == null ? 'disabled=true' : ''}>${link.label}</button>
            `;
        });

        this.btnGroupElement.append(inner);
    }

    enableLinksButtonElements(val)
    {
        if(val) this.linksButtonElements.removeAttr('disabled');
        else this.linksButtonElements.attr('disabled', true);
    }
}

function processRequestReceivedProducts(request, table)
{
    console.log("Processing request");
    request.done(function(res, status, jqXHR) {
        console.log(`GET request to load ${table.modelName} has successfully been made.`);
        console.log(res);

        // Print data to table
        table.displayTableRows(res.data);
        table.displayPaginationButtons(res.links);

        $(`.btn-paginate-${table.modelName}`).click(function(e) {
            console.log("Paginate button has been clicked.");
            console.log(e.target.dataset.href);

            request = table.requestData(e.target.dataset.href);
            processRequestReceivedProducts(request, table);
        });
    });

    request.fail(function(jqXHR, status, error) {
        console.log(`GET request to load ${table.modelName} has failed.`);
        console.log(jqXHR);

        generateToast(jqXHR.responseJSON.message, 'bg-danger');
    });

    request.always(function(){
        table.spinnerElement.hide();
    });
}

function displayActionButtonsReceivedProducts(id, uuid, modelName, actions, customActionsHtml = undefined)
{
    var inner = '';

    actions.forEach(function(a){
        if(a == 'edit') {
            inner += `
            <button 
                type="button" 
                class="btn btn-primary btn-edit-${modelName}" 
                data-id="${id}" data-uuid="${uuid}"
                data-bs-toggle="modal"
                data-bs-target="#modal-edit-${modelName}">
                <span class="icon text-white-50">
                    <i class="fas fa-pen"></i>
                </span>
            </button>
            `;
        }
        else if(a == 'delete') {
            inner += `
            <button 
                type="button" 
                class="btn btn-danger btn-delete-${modelName}" 
                data-id="${id}" data-uuid="${uuid}"
                data-bs-toggle="modal"
                data-bs-target="#modal-delete-${modelName}">
                <span class="icon text-white-50">
                    <i class="fas fa-trash"></i>
                </span>
            </button>
            `;
        }
        else if(a == 'barcode') {
            inner += `
            <button 
                type="button" 
                class="btn btn-primary btn-barcode-${modelName}" 
                data-id="${id}" data-uuid="${uuid}">
                <span class="icon text-white-50">
                    <i class="fas fa-barcode"></i>
                </span>
            </button>
            `;
        }
        else if(a == 'view') {
            inner += `
            <button 
                type="button" 
                class="btn btn-primary btn-view-${modelName}" 
                data-id="${id}" data-uuid="${uuid}">
                <span class="icon text-white-50">
                    <i class="fas fa-eye"></i>
                </span>
            </button>
            `;
        }
    });

    if(customActionsHtml != undefined)
    {
        inner += customActionsHtml;
    }


    return inner;
}

function actionsAddEventListenersReceivedProducts(modelName, ajaxUrl, actions)
{
    console.log("Adding event listenders to actions.");
    actions.forEach(function(a){
        console.log(a);
        if(a == 'edit') {
            $(`.btn-edit-${modelName}`).click(function(e) {
                console.log(`Edit button clicked for model: ${modelName}`);
                console.log(e);

                $(`#modal-spinner-edit-${modelName}`).show();
                $(`#form-edit-${modelName}`).hide();
                $(`#submit-form-edit-${modelName}`).attr('disabled', true);

                var request = $.ajax({
                    url: `${ajaxUrl}/${e.currentTarget.dataset.uuid}`,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    method: 'GET',
                });

                request.done(function(res, status, jqXHR){
                    console.log(`GET Request successful to fill in current values in ${modelName} edit form.`);
                    console.log(res);

                    var form = document.querySelector(`#form-edit-${modelName}`);
                    console.log(form);
                    form.action = `${ajaxUrl}/${res.uuid}`;

                    console.log("Collecting editable input elements");
                    var inputElements = document.querySelectorAll(`#form-edit-${modelName} input`);
                    console.log(inputElements);

                    inputElements.forEach(function(i){
                        if(i.name != '_token' && i.name != '_method')
                        {
                            console.log(`${i.name} : ${res[i.name]}`);
                            $(`#input-form-edit-${modelName}-${i.name}`).val(res[i.name]);
                        }
                    });

                    $(`#modal-spinner-edit-${modelName}`).hide();
                    $(`#form-edit-${modelName}`).show();
                    $(`#submit-form-edit-${modelName}`).removeAttr('disabled');
                });

                request.fail(function(jqXHR, status, error) {
                    console.log(`GET Request failed to fill in current values in ${modelName} edit form.`);
                    console.log(jqXHR);

                    $(`#close-form-edit-${modelName}`).click();
                    generateToast(jqXHR.responseJSON.message, 'bg-danger');
                });
            });
        }
        else if(a == 'delete') {
            $(`.btn-delete-${modelName}`).click(function(e) {
                console.log(`Delete button clicked for model: ${modelName}`);
                console.log(e);

                $(`#modal-spinner-delete-${modelName}`).show();
                $(`#form-delete-${modelName}`).hide();
                $(`#submit-form-delete-${modelName}`).attr('disabled', true);

                var request = $.ajax({
                    url: `${ajaxUrl}/${e.currentTarget.dataset.uuid}`,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    method: 'GET',
                });

                request.done(function(res, status, jqXHR){
                    console.log(`GET Request successful to fill in current values in ${modelName} delete form.`);
                    console.log(res);

                    var form = document.querySelector(`#form-delete-${modelName}`);
                    console.log(form);
                    form.action = `${ajaxUrl}/${res.uuid}`;
                    $(`#form-delete-${modelName}-name`).html(res.name);

                    $(`#modal-spinner-delete-${modelName}`).hide();
                    $(`#form-delete-${modelName}`).show();
                    $(`#submit-form-delete-${modelName}`).removeAttr('disabled');
                });

                request.fail(function(jqXHR, status, error) {
                    console.log(`GET Request failed to fill in current values in ${modelName} delete form.`);
                    console.log(jqXHR);

                    $(`#close-form-delete-${modelName}`).click();
                    generateToast(jqXHR.responseJSON.message, 'bg-danger');
                });
            });
        }
        else if(a == 'barcode') {
            $(`.btn-barcode-${modelName}`).click(function(e) {
                console.log(`Barcode button clicked for model: ${modelName}`);
                console.log(e.currentTarget.dataset.uuid);

                $('#modal-label_generate-barcode-pdf').html("Generate Barcodes - " + e.currentTarget.dataset.id);
                $('#iframe-generate-barcode-pdf').contents().find('body').attr('style', 'background-color:#fff').html("");
                $(`#modal-generate-barcode-pdf`).modal('show');
                $('#iframe-generate-barcode-pdf').attr('src', `/inventory/pdf/${e.currentTarget.dataset.uuid}`);

            });
        }
        else if(a == 'view') {
            $(`.btn-view-${modelName}`).click(function(e) {
                $(`.btn-view-${modelName}`).attr('disabled', true);

                console.log(`Barcode button clicked for model: ${modelName}`);
                console.log(e.currentTarget.dataset.uuid);

                $('#show-consign-order-items').html("");

                console.log(`${ajaxUrl}/order/${e.currentTarget.dataset.uuid}`)
                
                var request = $.ajax({
                    url: `/ajax/inventory/order/${e.currentTarget.dataset.uuid}`,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    method: 'GET',
                });
                
                request.done(function(res, status, jqXHR){
                    console.log(`GET Request successful to fill in current values in ${modelName} view form.`);
                    console.log(res);
                    $('#modal-label_show-consign-order').html("Viewing Consign Order - " + e.currentTarget.dataset.id);

                    $(`#modal-show-consign-order`).modal('show');

                    res.forEach(function(i){
                        inner = `
                        <tr>
                            <td>${i.id}</td>
                            <td>${i.name}</td>
                            <td>${i.quantity}</td>
                            <td>${i.total_quantity_sold}</td>
                            <td class="text-end">Php ${parseFloat(i.unit_price).toFixed(2)}</td>
                            <td class="text-end">Php ${parseFloat(i.sale_price).toFixed(2)}</td>
                            <td>${i.expiration_date}</td>
                            <td class="text-end">Php ${parseFloat(i.amount).toFixed(2)}</td>
                        </tr>
                        `;
                        $(`#show-consign-order-items`).append(inner);
                    });                 
                });

                request.fail(function(jqXHR, status, error) {
                    console.log(`GET Request failed to fill in current values in ${modelName} view form.`);
                    generateToast(error, 'bg-danger');
                });

                request.always(function(){
                    $(`.btn-view-${modelName}`).removeAttr('disabled');
                })
            });
        }
    });
}