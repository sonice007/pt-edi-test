let global_table_html = null;
let default_name = ''
let type = ''
$(function () {
    const table_html = $('#dt_basic');
    table_html.dataTable().fnDestroy()
    const main_table = table_html.DataTable({
        "ajax": {
            "url": "<?= base_url()?>produk/toko/ajax_data",
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
                                <button class="btn btn-primary btn-xs" onclick="Edit(${data})" title="Ubah">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-xs" onclick="Delete(${data})" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>`
                }, className: "nowrap"
            },
            { "data": "nama", className: "nowrap" },
            {
                "data": "id", render(data, type, full, meta) {
                    return `<div style="text-align: center;">
                                <button class="btn btn-info btn-xs" onclick="View(${data})" title="Keterangan" style="text-align: center; width: 80%;">
                                    <i class="fa fa-eye"></i> Lihat data
                                </button>
                            </div>`
                }, className: "nowrap"
            },
            {
                "data": "logo", render: function (data, type, full, meta) {
                    return data == null ? '' : `<button class="btn btn-info btn-xs" onclick="showGambar('${data}')">
                        <i class="fas fa-eye"></i> Lihat data
                        </button>`;
                }, className: "nowrap"
            },
            {
                "data": "t_produk", render(data, type, full, meta) {
                    return `<p style="text-align: right;">
                                ${data}
                            </p>`
                }, className: "nowrap"
            },
            {
                "data": "id", render(data, type, full, meta) {
                    return `<div style="text-align: center;">
                                <button class="btn btn-info btn-xs" onclick="Kontak(${data})" title="Kontak" style="text-align: center; width: 80%;">
                                    <i class="fa fa-list"></i> detail
                                </button>
                            </div>`
                }, className: "nowrap"
            },
            {
                "data": "id", render(data, type, full, meta) {
                    return `<div style="text-align: center;">
                                <button class="btn btn-info btn-xs" onclick="Wilayah(${data})" title="Wilayah" style="text-align: center; width: 80%;">
                                    <i class="fa fa-list"></i> detail
                                </button>
                            </div>`
                }, className: "nowrap"
            },
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

    $("#btn-tambah").click(() => {
        $("#myModalLabel").text("Tambah Toko");
        $('#id').val("");
        $('#nama').val("");
        $('#keterangan').val("");
        $('#status').val("1");
        $('#logo').val('');
        $('#keterangan').summernote('code', '');
        $('#myModalViewlogo').fadeOut();
        default_name = ''
        type = ''
    });

    // tambah dan ubah
    $("#form").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>produk/toko/' + ($("#id").val() == "" ? 'insert' : 'update'),
            data: form,
            cache: false,
            contentType: false,
            processData: false,
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data has been saved.'
            })
            var oTable = table_html.dataTable();
            oTable.fnDraw(false);

        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Failed to saved data'
            })
        }).always(() => {
            $.LoadingOverlay("hide");
            $('#myModal').modal('toggle')
        })
    });

})
let isi_judul = $("#isi-judul").val()
$("#nama").change(function() {
    let name = $(this).val()
    $.ajax({
        method: 'get',
        url: '<?= base_url() ?>produk/toko/cekNama?key='+name+'&type='+type+'&default='+default_name,
        cache: false,
        contentType: false,
        processData: false,
    }).done((data) => {
        if(data > 0){
            Toast.fire({
                icon: 'error',
                title: 'nama sudah ada.'
            })
            if(type == 'edit'){
                $("#nama").val(default_name)
            }else{
                $("#nama").val('')
            }
        }
    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Failed to check'
        })
    })

     // tambah dan ubah
    $("#kontak_form").submit(function (ev) {
        ev.preventDefault();
        alert(1)
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>produk/toko/kontak_' + ($("#id_toko_kontak").val() == "" ? 'insert' : 'update'),
            data: form,
            cache: false,
            contentType: false,
            processData: false,
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data has been saved.'
            })
            Kontak($('#id_toko').val());
            answer_cancel();
        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Failed to saved data'
            })
        }).always(() => {
            $.LoadingOverlay("hide");
        })
    });
})

$("#kontak_form").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    $.LoadingOverlay("show");
    $.ajax({
        method: 'post',
        url: '<?= base_url() ?>produk/toko/kontak_' + ($("#id_toko_kontak").val() == "" ? 'insert' : 'update'),
        data: form,
        cache: false,
        contentType: false,
        processData: false,
    }).done((data) => {
        Toast.fire({
            icon: 'success',
            title: 'Data berhasil disimpan.'
        })
        Kontak($('#id_toko').val());
        kontak_batal();
    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Data gagal disimpan'
        })
    }).always(() => {
        $.LoadingOverlay("hide");
    })
});

$("#wilayah_form").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    $.LoadingOverlay("show");
    $.ajax({
        method: 'post',
        url: '<?= base_url() ?>produk/toko/wilayah_' + ($("#id_toko_wilayah").val() == "" ? 'insert' : 'update'),
        data: form,
        cache: false,
        contentType: false,
        processData: false,
    }).done((data) => {
        Toast.fire({
            icon: 'success',
            title: 'Data berhasil disimpan.'
        })
        Wilayah($('#id_toko_w').val());
        wilayah_batal();
    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Data gagal disimpan'
        })
    }).always(() => {
        $.LoadingOverlay("hide");
    })
});

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

function Kontak(id_toko) {
    $('#id_toko').val(id_toko);
    $.LoadingOverlay("show");
    $.ajax({
        method: 'get',
        url: '<?= base_url() ?>produk/toko/getAllTokoKontak',
        data: {
            id_toko
        }
    }).done((data) => {
        if (data.data) {
            kontak_batal();
            // get list kontak
            $("#id_kontak").html('')
            $.ajax({
                method: 'get',
                url: '<?= base_url() ?>produk/toko/listKontak',
            }).done((d) => {
                $("#id_kontak").append(`<option value="">Pilih Kontak</option>`)
                d.data.forEach(e => {
                    $("#id_kontak").append(`<option value="${e.id}">${e.nama}</option>`);
                });

            }).fail(($xhr) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal mendapatkan data'
                })
            })

            $('#kontakModal').modal('show');
            // destroy current datatable
            const table_html = $('#dt_kontak');
            if (table_html.hasClass('dataTable')) {
                table_html.dataTable();
                table_html.fnDestroy();
            }

            // render table
            let tbody = $('#dt_kontak').find('tbody');
            tbody.html('');

            data.data.forEach(e => {
                tbody.append(`<tr>
                <td class="nowrap">${e.nama}</td>
                <td class="nowrap">${e.keterangan}</td>
                <td>
                    <button class="btn btn-primary btn-xs" data-id="${e.id}" data-id_toko="${e.id_toko}" data-nama="${e.nama}" data-id_kontak="${e.id_kontak}" data-keterangan="${e.keterangan}" data-status="${e.status}" onclick="kontak_ubah(this)">
                        <i class="fas fa-edit"></i> Ubah
                    </button>
                    <button class="btn btn-danger btn-xs" onclick="kontak_hapus(${e.id})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </td>
                </tr>`);
            });

            // set datatable
            table_html.dataTable();
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Data tidak valid'
            })
        }
    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Gagal mendapatkan data'
        })
    }).always(() => {
        $.LoadingOverlay("hide");
    })
}

function kontak_ubah(data) {
    const { id, id_toko, nama, id_kontak, keterangan, status } = data.dataset;
    $('#id_toko_kontak').val(id);
    $('#id_toko').val(id_toko);
    $('#id_kontak').val(id_kontak);
    $('#kontak_keterangan').val(keterangan);
    $('#kontak_status').val(status);

    $('#btn-kontak-simpan').hide();
    $('#btn-kontak-ubah').fadeIn();
    $('#btn-kontak-hapus').fadeIn();
}

function kontak_batal() {
    $('#btn-kontak-ubah').hide();
    $('#btn-kontak-batal').hide();
    $('#btn-kontak-simpan').fadeIn();
    $('#id_toko_kontak').val('');
    $('#kontak_form').trigger('reset');
}

function kontak_hapus(id) {
    swal.fire({
        title: 'Are you sure?',
        text: "Are you sure you want to proceed ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes'
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: '<?= base_url() ?>produk/toko/kontak_delete',
                type: 'post',
                data: {
                    id: id
                },
                beforeSend: function () {
                    swal.fire({
                        title: 'Please Wait..!',
                        text: 'Is working..',
                        onOpen: function () {
                            Swal.showLoading()
                        }
                    })
                },
                success: function (data) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Your data has been deleted.',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    Kontak($('#id_toko').val());
                },
                complete: function () {
                    swal.hideLoading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    swal.hideLoading();
                    swal.fire("!Opps ", "Something went wrong, try again later", "error");
                }
            });
        }
    });
}

function Wilayah(id_toko) {
    $('#id_toko_w').val(id_toko);
    $.LoadingOverlay("show");
    $.ajax({
        method: 'get',
        url: '<?= base_url() ?>produk/toko/getAllTokoWilayah',
        data: {
            id_toko
        }
    }).done((data) => {
        if (data.data) {
            kontak_batal();
            // get list kontak
            $("#id_wilayah").html('')
            $.ajax({
                method: 'get',
                url: '<?= base_url() ?>produk/toko/listWilayah',
            }).done((d) => {
                $("#id_wilayah").append(`<option value="">Pilih Wilayah</option>`)
                d.data.forEach(e => {
                    $("#id_wilayah").append(`<option value="${e.id}">${e.nama}</option>`);
                });

            }).fail(($xhr) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal mendapatkan data'
                })
            })

            $('#wilayahModal').modal('show');
            // destroy current datatable
            const table_html = $('#dt_wilayah');
            if (table_html.hasClass('dataTable')) {
                table_html.dataTable();
                table_html.fnDestroy();
            }

            // render table
            let tbody = $('#dt_wilayah').find('tbody');
            tbody.html('');

            data.data.forEach(e => {
                tbody.append(`<tr>
                <td class="nowrap">${e.nama}</td>
                <td class="nowrap">${e.keterangan}</td>
                <td>
                    <button class="btn btn-primary btn-xs" data-id="${e.id}" data-id_toko="${e.id_toko}" data-nama="${e.nama}" data-id_wilayah="${e.id_wilayah}" data-keterangan="${e.keterangan}" data-status="${e.status}" onclick="wilayah_ubah(this)">
                        <i class="fas fa-edit"></i> Ubah
                    </button>
                    <button class="btn btn-danger btn-xs" onclick="wilayah_hapus(${e.id})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </td>
                </tr>`);
            });

            // set datatable
            table_html.dataTable();
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Data tidak valid'
            })
        }
    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Gagal mendapatkan data'
        })
    }).always(() => {
        $.LoadingOverlay("hide");
    })
}

function wilayah_ubah(data) {
    const { id, id_toko, nama, id_wilayah, keterangan, status } = data.dataset;
    $('#id_toko_wilayah').val(id);
    $('#id_toko_w').val(id_toko);
    $('#id_wilayah').val(id_wilayah);
    $('#wilayah_keterangan').val(keterangan);
    $('#wilayah_status').val(status);

    $('#btn-kontak-simpan').hide();
    $('#btn-kontak-ubah').fadeIn();
    $('#btn-kontak-hapus').fadeIn();
}

function wilayah_batal() {
    $('#btn-wilayah-ubah').hide();
    $('#btn-wilayah-batal').hide();
    $('#btn-wilayah-simpan').fadeIn();
    $('#id_toko_wilayah').val('');
    $('#wilayah_form').trigger('reset');
}

function wilayah_hapus(id) {
    swal.fire({
        title: 'Are you sure?',
        text: "Are you sure you want to proceed ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes'
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: '<?= base_url() ?>produk/toko/wilayah_delete',
                type: 'post',
                data: {
                    id: id
                },
                beforeSend: function () {
                    swal.fire({
                        title: 'Please Wait..!',
                        text: 'Is working..',
                        onOpen: function () {
                            Swal.showLoading()
                        }
                    })
                },
                success: function (data) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Your data has been deleted.',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    Wilayah($('#id_toko_w').val());
                },
                complete: function () {
                    swal.hideLoading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    swal.hideLoading();
                    swal.fire("!Opps ", "Something went wrong, try again later", "error");
                }
            });
        }
    });
}