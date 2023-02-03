moment.defineLocale('id', {});
$(function () {
    let isi_judul = $("#isi-judul").val()
    function dynamic() {
        const table_html = $('#dt_basic');
        table_html.dataTable().fnDestroy()
        var tableUser = table_html.DataTable({
            "ajax": {
                "url": "<?= base_url()?>message/ajax_data/",
                "data": null,
                "type": 'POST'
            },
            "processing": true,
            "serverSide": true,
            "lengthChange": true,
            "autoWidth": false,
            "scrollX": true,
            "columns": [
                { "data": null }, {
                    "data": "id", render(data, type, full, meta) {
                        return `<div class="pull-right">
									<button class="btn btn-info btn-xs" onclick="Lihat(${data})">
										<i class="fa fa-eye"></i> Lihat Data
									</button>
								</div>`
                    }, className: "nowrap"
                },
                { "data": "nama" },
                {
                    "data": "email", render(data, type, full, meta) {
                        return `<a href="mailto:${data}">${data}</a>`
                    }, className: "nowrap"
                },
                {
                    "data": "pesan", render: function (data, type, row) {
                        return `
            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="${data}">
            ${data.substr(0, 30) + (data.length > 30 ? '...' : '')}
            </span>
            `;
                    }, className: "nowrap"
                },
                { "data": "status_str" },
                {
                    "data": "updated_at", render(data, type, full, meta) {
                        // return data == null || data == '' ? full.created_at : full.updated_at;
                        return moment(data == null || data == '' ? full.created_at : full.updated_at, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');

                    }, className: "nowrap"
                },

            ],
            columnDefs: [{
                orderable: true,
                // targets: [0, 4]
            }],
            order: [
                [2, 'asc']
            ]
        });
        tableUser.on('draw.dt', function () {
            var PageInfo = $(table_html).DataTable().page.info();
            tableUser.column(0, {
                page: 'current'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });
    }
    dynamic();

    $("#form_email").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>Message/save_email/',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data berhasil disimpan'
            })
            dynamic();
        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Data gagal disimpan'
            })
        }).always(() => {
            $.LoadingOverlay("hide");
            $('#tambahModal').modal('toggle')
        })
    });

    $("#contact_footer_form").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>Message/save_judul_footer/',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data berhasil disimpan'
            })
            dynamic();
        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Data gagal disimpan'
            })
        }).always(() => {
            $.LoadingOverlay("hide");
            $('#tambahModal').modal('toggle')
        })
    });

    $("#contact_form").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>Message/save_judul_contact/',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data berhasil disimpan'
            })
            dynamic();
        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Data gagal disimpan'
            })
        }).always(() => {
            $.LoadingOverlay("hide");
            $('#tambahModal').modal('toggle')
        })
    });
})
let isi_judul = $("#isi-judul").val()


// Click Lihat
const Lihat = (id) => {
    $.LoadingOverlay("show");
    $.ajax({
        method: 'get',
        url: '<?= base_url() ?>message/getMessage',
        data: {
            id: id
        }
    }).done((data) => {
        if (data.data) {
            data = data.data;
            $("#myModalLabel").text("Lihat Data " + isi_judul);
            $('#nama').html(data.nama);
            $('#email').html(`<a href="mailto:${data.email}">${data.email}</a>`);
            $('#pesan').html(data.pesan);
            $('#myModal').modal('toggle')
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Data tidak valid.'
            })
        }
    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Gagal mendapatkan data.'
        })
    }).always(() => {
        $.LoadingOverlay("hide");
    })

}

function handleToltipFromDatatable({ element = '', title = '' }) {
    const el = $(element);
    el.tooltip({ title: title == null ? '' : title });
    el.mouseenter(function () {
        $(this).tooltip('show');
    });
    el.mouseout(function () {
        $(this).tooltip('hide');
    });
}