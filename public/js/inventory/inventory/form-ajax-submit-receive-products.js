// 1. Add .ajax-submit-received-products class as one of the form's class names.
// 2. Error elements must have an id with following format:
//      #error-{form-name}-{input-field}
// 3. Error elements should also have a data-field bearing the related input's name.
// 4. Submit button must have an id with following format:
//      #submit-{form-name}
// 5. Close button must have an id with following format:
//      #close-{form-name}

$("#form-receive-products").submit(function(e){
    console.log(`Submitting form ${e.target.id}`);
    console.log(e);
    e.preventDefault();

    var errorTextElements = [], submitButtonElement, closeButtonElement;
    
    console.log(`Getting errorTextElements, submitButtonElement, and closeButtonElement`);
    console.log(e.target);
    for(i = 0; i < e.target.length; i++)
    {
        let name = e.target[i].name;
        let data = e.target[i].dataset;
        if(name != undefined && name != '' && name != '_token' && name != '_method')
        {
            console.log(`Found input element: ${name}`);
            // errorTextElements.push($(`#error-${e.target.id}-${name}`));
        }
    }

    closeButtonElement = $(`#form-receive-products.close`);
    submitButtonElement = $(`#form-receive-products.submit`);

    // Hide error elements and disable submit button.
    hideFormErrorsReceiveProducts();
    disableSubmitButton(submitButtonElement);

    console.log(`Creating a POST request from ${e.target.id}`);
    console.log($(`#${e.target.id}`).serialize());

    // Creating a request
    var request = $.ajax({
        url: e.target.action,
        method: e.target.method,
        data: $(`#${e.target.id}`).serialize(),
    });

    // If request has been successfully processed.
    request.done(function(res, status, jqXhr) {
        console.log(`Request for ${e.target.id} has successfully been made.`);
        console.log(res);

        closeButtonElement.click();
        generateToast(res, 'bg-success');

        console.log("TEST");

        if(e.target.dataset.model != undefined)
        {
            request = window[e.target.dataset.model + 'Table'].requestData();
            processRequest(request, window[e.target.dataset.model + 'Table']);
        }
    });

    // If request has errors (e.g. validation errors).
    request.fail(function(jqXHR, status, error){
        console.log(`Request for ${e.target.id} has failed.`);
        console.log(jqXHR);

        if(jqXHR.status != 422)
        {
            console.log(jqXHR.responseJSON.message);
            generateToast(jqXHR.responseJSON.message, 'bg-danger');
            closeButtonElement.click();
        }
        else
        {
            console.log(jqXHR.responseJSON.errors);
            showFormErrorsReceiveProducts(errorTextElements, jqXHR.responseJSON.errors);
        }

    });

    // The following always executes regardless of status.
    request.always(function() {
        enableSubmitButton(submitButtonElement);
    });
});

function hideFormErrorsReceiveProducts()
{
    console.log("Attempting to hide errors.");
    $(`.error`).hide();
}

function showFormErrorsReceiveProducts(errorTextElements, errors)
{
    error_list = Object.keys(errors);
    console.log("Show form errors");
    console.log(error_list);
    console.log(errors);

    error_list.forEach(function(e){
        let err = e;
        err = err.split(".");
        if(Array.isArray(err)) {
            if(err.length == 2) {
                let errElm = $(`#form-receive-products .error-rp_${err[0]}`);
                errElm[parseInt(err[1])].innerHTML = errors[e];
                errElm[parseInt(err[1])].style = "display: flex";
            }
            else {
                let errElm = $(`#form-receive-products .error-rp_${err[0]}`);      
                errElm.show().html(errors[e]);
            }
        }
    });
    
    
    // errorTextElements.forEach(function(e){
    //     err = e[0].dataset.field;
    //     if(isErrorInArray(error_list, err)){
    //         e.show().html(errors[err][0]);
    //     }
    // });
}

function isErrorInArray(error, index)
{
    
}

function enableSubmitButton(btn) {
    btn.removeAttr('disabled');
}

function disableSubmitButton(btn) {
    btn.attr('disabled', true);
}