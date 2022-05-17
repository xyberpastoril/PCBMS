class PaginationAjax {
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
        this.modelName = args.modelName;
        this.columns = args.columns;


        this.tbodyElement = $(`#table-content-${args.modelName}`);
        this.btnGroupElement = $(`#table-links-${args.modelName}`);
        this.spinnerElement = $(`#table-spinner-${args.modelName}`);
        this.linksButtonElements = $(`.btn-paginate-${args.modelName}`);

    }

    requestData(url = null) 
    {
        this.spinnerElement.show();
        this.enableLinksButtonElements(false);

        this.request = $.ajax({
            url: url ? url : this.url,
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

        var inner = '';
        rows.forEach(function(row){           
            // Add the opening tag and actions column of the row.
            inner += `
            <tr>
                <td>
                    TBA
                </td>
                `

            // Add the values of each column.
            columns.forEach(function(c){
                inner += `
                    <td>${row[c]}</td>
                `
            });

            // Add the closing tag of the row.
            inner += `
            </tr>
            `;
        });

        this.tbodyElement.append(inner);
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