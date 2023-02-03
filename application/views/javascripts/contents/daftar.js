moment.defineLocale('id', {});
$(function () {
    let isi_judul = $("#isi-judul").val()
    function dynamic() {
        const table_html = $('#dt_basic');
        table_html.dataTable().fnDestroy()
        var tableUser = table_html.DataTable({
            "ajax": {
                "url": "<?= base_url()?>daftar/ajax_data/",
                "data": null,
                "type": 'POST'
            },
            "processing": true,
            "serverSide": true,
            "lengthChange": true,
            "autoWidth": false,
            "scrollX": true,
            "columns": [
                // {
                //     "data": "id", render(data, type, full, meta) {
                //         return `<div class="pull-right">
				// 					<button class="btn btn-info btn-xs" onclick="Lihat(${data})">
				// 						<i class="fa fa-eye"></i> Lihat Data
				// 					</button>
				// 				</div>`
                //     }, className: "nowrap"
                // },
                { "data": "sebagai", className: "nowrap" },
                { "data": "nama", className: "nowrap" },
                {
                    "data": "sebagai_value", render(data, type, full, meta) {
                        if(full.sebagai == "nelayan"){
                            return `${data}`
                        }else{
                            let ktp = `<i class="far fa-check-circle text-success"></i> Lihat KTP`
                            return `<a style="float: left; text-align: left; padding-left: 0px!important; padding: none;" class="btn btn-ktp" data-toggle="modal" data-data="${data}" data-target="#modal-ktp" onclick="view_ktp(this)" id="btn-ktp">${ktp}</a>`
                        }
                    }, className: "nowrap"
                },
                { "data": "email", className: "nowrap" },
                { "data": "area", className: "nowrap" },
                { "data": "status_str" },
                {
                    "data": "created_at", render(data, type, full, meta) {
                        // return data == null || data == '' ? full.created_at : full.updated_at;
                        return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');

                    }, className: "nowrap"
                },
            ],
        });
    }
    dynamic();

})
let isi_judul = $("#isi-judul").val()

const view_ktp = (datas) => {
    let url = 'http://localhost/nelayanloka/assets/daftar/'
    $("#img-view").attr('src', `${url}${datas.dataset.data}`)
}
// Click Lihat
const Lihat = (id) => {
    $.LoadingOverlay("show");
    $.ajax({
        method: 'get',
        url: '<?= base_url() ?>daftar/getDaftar',
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