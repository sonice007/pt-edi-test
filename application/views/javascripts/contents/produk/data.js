let global_table_html = null;
let default_name = ''
let type = ''
$(function () {
    const table_html = $('#dt_basic');
    table_html.dataTable().fnDestroy()
    const main_table = table_html.DataTable({
        "ajax": {
            "url": "<?= base_url()?>produk/data/ajax_data",
            "data": null,
            "type": 'POST'
        },
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "columns": [
            { "data": null },
            {
                "data": "id", render(data, type, full, meta) {
                    return `<div class="pull-right">
                                <a class="btn btn-primary btn-xs" href="<?= base_url() ?>produk/ubah?id=${data}" title="Ubah">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-xs" onclick="Delete(${data})" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>`
                }, className: "nowrap"
            },
            { "data": "toko", className: "nowrap" },
            { "data": "judul", className: "nowrap" },
            { "data": "categories", className: "nowrap" },
            { "data": "tags", className: "nowrap" },
            { "data": "liked", className: "nowrap text-right" },
            { "data": "saved", className: "nowrap text-right" },
            {
                "data": "view", render: function (data, type, full, meta) {
                    return data ?? 0;
                }, className: "nowrap text-right"
            },
            {
                "data": "rating", render: function (data, type, full, meta) {
                    return ratingToStar(Number(data));
                }, className: "nowrap text-right"
            },
            { "data": "status_str", className: "nowrap text-left" },
            {
                "data": "updated_at", render(data, type, full, meta) {
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
let isi_judul = $("#isi-judul").val()

// Click Delete
const Delete = (id) => {
    swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Apakah kamu yakin ingin menghapus data ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes'
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: '<?= base_url() ?>produk/toko/delete',
                type: 'post',
                data: {
                    id: id
                },
                beforeSend: function () {
                    swal.fire({
                        title: 'Tunggu sebentar..!',
                        text: 'Sedang berjalan..',
                        onOpen: function () {
                            Swal.showLoading()
                        }
                    })
                },
                success: function (data) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Data berhasil dihapus.',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    var oTable = global_table_html.dataTable();
                    oTable.fnDraw(false);
                },
                complete: function () {
                    swal.hideLoading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    swal.hideLoading();
                    swal.fire("!Maag ", "Ada sesuatu yang salah, coba lagi nanti", "error");
                }
            });
        }
    });
}

// Click Edit
const Edit = (id) => {
    $.LoadingOverlay("show");
    $.ajax({
        method: 'get',
        url: '<?= base_url() ?>produk/toko/find',
        data: {
            id: id
        }
    }).done((data) => {
        if (data.data) {
            data = data.data;
            $("#myModalLabel").text("Ubah Toko");
            $('#id').val(data.id);
            $('#nama').val(data.nama);
            $('#keterangan').summernote('code', data.keterangan);
            $('#status').val(data.status);
            $('#myModal').modal('toggle');
            $('#old_logo').val(data.logo);
            $('#thumbnail_view_img').attr('src', `<?= base_url() ?>files/produk/toko/${data.logo}`);
            $('#logo').val('');
            default_name = data.nama
            type = 'edit'
            const view_p = $('#myModalViewlogo');
            if (data.logo) {
                view_p.fadeIn();
                view_p.attr('onclick', `showGambar('${data.logo}')`);
            } else {
                view_p.fadeOut();
            }
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Data tidak valid'
            })
        }
    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Gagal mengambil data'
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

$('.summernote').summernote({

    height: 200,
})

function showGambar(file) {
    $('#thumbnailModal').modal('show');
    $('#thumbnail_view_img_show').attr('src', `<?= base_url() ?>files/produk/toko/${file}`);
}

function View(id) {
    $.LoadingOverlay("show");
    $.ajax({
        method: 'get',
        url: '<?= base_url() ?>produk/toko/find',
        data: {
            id: id
        }
    }).done((d) => {
        if (d.data) {
            const data = d.data;
            $('#keterangan_view').summernote('code', data.keterangan);
            $('#keterangan_view').summernote('disable');
            $('#viewModal').modal('show');
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Data not valid'
            })
        }
    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Filed Get Data'
        })
    }).always(() => {
        $.LoadingOverlay("hide");
    })
}
