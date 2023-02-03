let global_table_html = null;
let default_name = ''
let type = ''
$(function () {
    const table_html = $('#dt_basic');
    table_html.dataTable().fnDestroy()
    const main_table = table_html.DataTable({
        "ajax": {
            "url": "<?= base_url()?>customer/ajax_data",
            "data": null,
            "type": 'POST'
        },
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "columns": [
            { "data": null },
            { "data": "email", className: "nowrap" },
            { "data": "nama", className: "nowrap" },
            { "data": "no_telpon", className: "nowrap" },
            { "data": "status_str", className: "nowrap text-right" },
            {
                "data": "updated_at", render(data, type, full, meta) {
                    // return data == null || data == '' ? full.created_at : full.updated_at;
                    return data == null || data == '' ? full.created_at : full.updated_at;

                }, className: "nowrap"
            },

        ],
        columnDefs: [{
            orderable: true,
            // targets: [0, 4]
        }],
        order: [
            [1, 'asc']
        ]
    });
    main_table.on('draw.dt', function () {
        var PageInfo = $(table_html).DataTable().page.info();
        main_table.column(0, {
            page: 'current'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1 + PageInfo.start;
        });
    });
    global_table_html = table_html;


})