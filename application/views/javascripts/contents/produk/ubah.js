let global_toko_check_fetch = null;
let global_toko_check_slug = [];

$(document).ready(() => {
	// slug
	$("#judul").keyup(function () {
	    var Text = $(this).val();
	    $("#slug").val(Text.toLowerCase()
	        .replace(/[^\w ]+/g, '')
	        .replace(/ +/g, '-'));
	    // check
	    // getList(Text);
	});

	$('#toko').focus(e => {
        $('#toko-check').fadeIn();
    })

    $('#toko').focusout(e => {
        $('#toko-check').fadeOut();
    })

	$("#toko").keyup(function () {
	    var Text = $(this).val();
	    getList(Text);
	});

	function getList(key = '') {
	    if (key == '') {
	        toko_check_loading(false);
	        return;
	    }
	    if (global_toko_check_fetch != null) {
	        global_toko_check_fetch.abort();
	        toko_check_loading(false);
	    }
	    toko_check_loading();
	    global_toko_check_fetch = $.ajax({
	        method: 'post',
	        url: '<?= base_url() ?>produk/data/list_toko',
	        data: {
	            key,
	            id: 0
	        },
	    }).done((data) => {
	        const el = $('#toko-check-list');
	        el.html('')
	        global_toko_check_slug = [];
	        data.forEach(e => {
	            global_toko_check_slug.push(e.slug);
	            el.append(`<li class="list-group-item" data-id="${e.id}" data-nama="${e.nama}"><a href="#" id="pilih-toko" class="text_dark_switch text-dark">${e.nama}</a></li>`);
	        });
	        $(".list-group-item").click(function () {
	        	var id = $(this).attr('data-id');
	        	var nama = $(this).attr('data-nama');
	        	$("#id_toko").val(id)
	        	$("#toko").val(nama)
			});
	        toko_check_loading(false);
	    }).fail(($xhr) => {
	        toko_check_loading(false);
	        // console.log($xhr);
	    }).always(() => {
	        toko_check_loading(false);
	    })
	}

	function toko_check_loading(set = true) {
	    const el = $('#title-check-loading');
	    if (set) {
	        el.fadeIn();
	    } else {
	        el.hide();
	    }
	}
	// end slug

	$('.summernote').summernote({
        height: 250,
    });

    $('#tags').select2({
        ajax: {
            url: '<?= base_url() ?>master/tags/select2/',
            dataType: 'json',
            method: 'get',
            data: function (params) {
                return {
                    search: params.term,
                    new: 1,
                };
            },
        },
        dropdownParent: $(".card-body"),
        minimumInputLength: 0,
    });

    $('#categories').select2({
        ajax: {
            url: '<?= base_url() ?>master/kategori/select2/',
            dataType: 'json',
            method: 'get',
            data: function (params) {
                return {
                    search: params.term,
                    // new: 1,
                };
            },
        },
        dropdownParent: $(".card-body"),
        minimumInputLength: 0,
    })

    $("#form").submit(function (ev) {
        ev.preventDefault();
        // if (global_title_check_slug.includes($("#slug").val())) {
        //     Toast.fire({
        //         icon: 'error',
        //         title: 'Title or slug already exist'
        //     })
        //     return;
        // }
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>produk/tambah/update',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data has been saved.'
            })
            console.log(data)
            setTimeout(() => {
                window.location.href = "<?= base_url('produk/data') ?>";
            }, 1500)
        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Failed to saved data'
            })
        }).always(() => {
            $.LoadingOverlay("hide");
        })
    });

    
    // produk gambar
    $("#btn-tambah-gambar").click(() => {
        $("#myModalLabel").text("Tambah Gambar");
        $('#id').val("");
        $('#gambar').val('');
        $('#cover').val('0');
        $('#status').val("1");
    });

    const table_html = $('#dt_basic_gambar');
    table_html.dataTable().fnDestroy()
    const main_table = table_html.DataTable({
        "ajax": {
            "url": "<?= base_url()?>produk/data/ajax_data_gambar",
            "data": {
                id_produk: $("#id").val()
            },
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
                                <button class="btn btn-primary btn-xs" onclick="EditGambar(${data})" title="Ubah">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-xs" onclick="DeleteGambar(${data})" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>`
                }, className: "nowrap"
            },
            {
                "data": "gambar", render: function (data, type, full, meta) {
                    return data == null ? '' : `<button class="btn btn-info btn-xs" onclick="showGambar('${data}')">
                        <i class="fas fa-eye"></i> view
                        </button>`;
                }, className: "nowrap"
            },
            { "data": "cover", className: "nowrap" },
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

    $("#form-gambar").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>produk/ubah/' + ($("#id_produk_gambar").val() == "" ? 'insert_gambar' : 'update_gambar'),
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
            $('#myModalGambar').modal('toggle')
        })
    });
    // end produk gambar


    // produk varian
    $("#btn-tambah-varian").click(() => {
        $("#myModalLabelVarian").text("Tambah Produk Varian");
        $('#id').val("");
        $('#varian').val('');
        $('#status').val("1");
    });

    const table_html_varian = $('#dt_basic_varian');
    table_html_varian.dataTable().fnDestroy()
    const main_table_varian = table_html_varian.DataTable({
        "ajax": {
            "url": "<?= base_url()?>produk/data/ajax_data_varian",
            "data": {
                id_produk: $("#id").val()
            },
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
                                <button class="btn btn-primary btn-xs" onclick="EditVarian(${data})" title="Ubah">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-xs" onclick="DeleteVarian(${data})" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button class="btn btn-info btn-xs" onclick="Ukuran(${data},\'${full.varian}\')" title="Detail Varian">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>`
                }, className: "nowrap"
            },
            { "data": "varian", className: "nowrap" },
            { "data": "t_ukuran", className: "nowrap text-right" },
            { "data": "t_stok", className: "nowrap text-right" },
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
    main_table_varian.on('draw.dt', function () {
        var PageInfo_varian = $(table_html_varian).DataTable().page.info();
        main_table_varian.column(0, {
            page: 'current'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1 + PageInfo_varian.start;
        });
    });
    varian_table_html = table_html_varian;

    $("#form-varian").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>produk/ubah/' + ($("#id_produk_varian").val() == "" ? 'insert_varian' : 'update_varian'),
            data: form,
            cache: false,
            contentType: false,
            processData: false,
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data has been saved.'
            })
            var oTableVarian = table_html_varian.dataTable();
            oTableVarian.fnDraw(false);

        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Failed to saved data'
            })
        }).always(() => {
            $.LoadingOverlay("hide");
            $('#myModalVarian').modal('toggle')
        })
    });
    // end produk varian
})

// produk gambar
    // Click Delete
    const DeleteGambar = (id) => {
        swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Apakah kamu yakin ingin menghapus data ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '<?= base_url() ?>produk/ubah/delete_gambar',
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
                        swal.fire("!Maaf ", "Ada sesuatu yang salah, coba lagi nanti", "error");
                    }
                });
            }
        });
    }

    // Click Edit
    const EditGambar = (id) => {
        $.LoadingOverlay("show");
        $.ajax({
            method: 'get',
            url: '<?= base_url() ?>produk/ubah/find_gambar',
            data: {
                id: id
            }
        }).done((data) => {
            if (data.data) {
                data = data.data;
                $("#myModalLabel").text("Ubah Gambar");
                $('#id_produk_gambar').val(data.id);
                $('#cover').val(data.cover);
                $('#status').val(data.status);
                $('#myModalGambar').modal('toggle');
                $('#old_gambar').val(data.gambar);
                $('#img_view').attr('src', `<?= base_url() ?>files/produk/data/${data.gambar}`);
                $('#gambar').val('');
                type = 'edit'
                const view_p = $('#myModalViewgambar');
                if (data.gambar) {
                    view_p.fadeIn();
                    view_p.attr('onclick', `showGambar('${data.gambar}')`);
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

    function showGambar(file) {
        $('#thumbnailModal').modal('show');
        $('#thumbnail_view_img_show').attr('src', `<?= base_url() ?>files/produk/data/${file}`);
    }
// end produk gambar

// produk varian
    // Click Delete
    const DeleteVarian = (id) => {
        swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Apakah kamu yakin ingin menghapus data ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '<?= base_url() ?>produk/ubah/delete_varian',
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
                        var oTableVarian = varian_table_html.dataTable();
                        oTableVarian.fnDraw(false);
                    },
                    complete: function () {
                        swal.hideLoading();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal.hideLoading();
                        swal.fire("!Maaf ", "Ada sesuatu yang salah, coba lagi nanti", "error");
                    }
                });
            }
        });
    }

    // Click Edit
    const EditVarian = (id) => {
        $.LoadingOverlay("show");
        $.ajax({
            method: 'get',
            url: '<?= base_url() ?>produk/ubah/find_varian',
            data: {
                id: id
            }
        }).done((data) => {
            if (data.data) {
                data = data.data;
                $("#myModalLabelVarian").text("Ubah Varian");
                $('#id_produk_varian').val(data.id);
                $('#varian').val(data.varian);
                $('#status').val(data.status);
                $('#myModalVarian').modal('toggle');
                type = 'edit'
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
// end produk gambar

// produk varian ukuran
    function Ukuran(ukuran_id_produk_varian, varian) {
        $('#ukuran_id_produk_varian').val(ukuran_id_produk_varian);
        $('#ukuran_varian').val(varian);
        $("#myModalLabelUkuran").text('Produk Varian Ukuran: '+varian+'')
        $.LoadingOverlay("show");
        $.ajax({
            method: 'get',
            url: '<?= base_url() ?>produk/data/getAllProdukVarianUkuran',
            data: {
                ukuran_id_produk_varian
            }
        }).done((data) => {
            console.log(data)
            if (data.data) {
                ukuran_batal();
                $('#myModalUkuran').modal('show');
                // destroy current datatable
                const table_html_ukuran = $('#dt_ukuran');
                if (table_html_ukuran.hasClass('dataTable')) {
                    table_html_ukuran.dataTable();
                    table_html_ukuran.fnDestroy();
                }

                // render table
                let tbody = $('#dt_ukuran').find('tbody');
                tbody.html('');

                data.data.forEach(e => {
                    tbody.append(`<tr>
                    <td  class="nowrap">${e.ukuran}</td>
                    <td  class="nowrap">${e.berat}</td>
                    <td  class="nowrap">${e.stok}</td>
                    <td >
                        <button class="btn btn-primary btn-xs" data-id="${e.id}" data-id_produk_varian="${e.id_produk_varian}" data-ukuran="${e.ukuran}" data-berat="${e.berat}" data-stok="${e.stok}" data-status="${e.status}" onclick="ukuran_ubah(this)">
                            <i class="fas fa-edit"></i> Ubah
                        </button>
                        <button class="btn btn-danger btn-xs" onclick="ukuran_hapus(${e.id})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                    </tr>`);
                });


                // set datatable
                table_html_ukuran.dataTable();
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

    function ukuran_ubah(data) {
        const { id, id_produk_varian, ukuran, berat, stok, status } = data.dataset;
        console.log(id_produk_varian)
        console.log(ukuran)
        $('#ukuran_id').val(id);
        $('#ukuran_id_produk_varian').val(id_produk_varian);
        $('#ukuran').val(ukuran);
        $('#berat').val(berat);
        $('#stok').val(stok);

        $('#btn-ukuran-simpan').hide();
        $('#btn-ukuran-ubah').fadeIn();
        $('#btn-ukuran-batal').fadeIn();
    }

    function ukuran_batal() {
        $('#btn-ukuran-ubah').hide();
        $('#btn-ukuran-batal').hide();
        $('#btn-ukuran-simpan').fadeIn();
        $('#ukuran_id').val('');
        $('#ukuran_form').trigger('reset');
    }

    function ukuran_hapus(id) {
        swal.fire({
            title: 'Are you sure?',
            text: "Are you sure you want to proceed ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '<?= base_url() ?>produk/ubah/ukuran_delete',
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
                        Ukuran($('#ukuran_id_produk_varian').val(),$('#ukuran_varian').val());
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

    $("#ukuran_form").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>produk/ubah/ukuran_' + ($("#ukuran_id").val() == "" ? 'insert' : 'update'),
            data: form,
            cache: false,
            contentType: false,
            processData: false,
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data has been saved.'
            })
            Ukuran($('#ukuran_id_produk_varian').val(),$('#ukuran_varian').val());
            ukuran_batal();
        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Failed to saved data'
            })
        }).always(() => {
            $.LoadingOverlay("hide");
        })
    });
// end produk varian ukuran

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