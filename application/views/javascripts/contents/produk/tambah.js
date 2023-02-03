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
	            id: $("#id").val()
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
            url: '<?= base_url() ?>produk/tambah/insert',
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
})