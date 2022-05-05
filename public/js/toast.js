var toast_count = 0;
var toast_container = document.getElementById("toast-container");
console.log("Creating toast container");
console.log(toast_container);

var option = {
    animation: true,
    delay: 10000,
};

function generateToast(msg, bg_color)
{
    let inner = `
    <div id="toast_${++toast_count}" class="toast align-items-center text-white ${bg_color} border-0 p-2" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div id="toast-body" class="toast-body">${msg}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    `;

    console.log("Select toast container:");
    console.log(toast_container);

    toast_container.innerHTML += inner;       

    console.log(toast_count);
    let toastHTMLElement = document.getElementById(`toast_${toast_count}`);
    let toastElement = new bootstrap.Toast(toastHTMLElement, option);
    toastElement.show();
}