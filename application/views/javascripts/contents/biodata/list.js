let global_dinamic = null;
$(function () {
  var url_string = location.href;
  var url = new URL(url_string);

  function dynamic_v2(datas = {}) {
    const table_html = $('#dt_basic_v2');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>biodata/ajax_data/",
        "data": datas,
        "type": 'POST'
      },
      "processing": true,
      "serverSide": true,
      "responsive": false,
      "lengthChange": true,
      "autoWidth": false,
      "scrollX": true,
      "columns": [
        {
          "data": "id", render(data, type, full, meta) {
            return `<div class="pull-right" style="text-align: center;">
                <a class="btn btn-primary btn-xs" href="<?= base_url() ?>biodata/tambah/${data}">
                  <i class="fa fa-edit"></i> Lihat
                </a>
                <button class="btn btn-danger btn-xs"
                data-id_profile="${full.id}"
                data-id_user="${full.id_user}"
                onclick="Hapus(this)">
                  <i class="fa fa-trash"></i> Hapus
                </button>
              </div>`
          }, className: "nowrap"
        },
        {
          "data": "nama", render(data, type, full, meta) {
            return data
          }, className: "nowrap"
        },
        {
            "data": "tempat_lahir", render(data, type, full, meta) {
                return `<div class="pull-right" style="text-align: left;">${full.tempat_lahir + ',' +moment(full.tanggal_lahir, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY')}</div>`

            }, className: "nowrap"
        },
        {
          "data": "posisi", render(data, type, full, meta) {
            return data
          }, className: "nowrap"
        },
        // {
        //   "data": "id", render(data, type, full, meta) {
        //     let ret = null
        //     if(full.tipe_contact_sekarang != ''){
        //       ret = `<a style="cursor: pointer;" data-toggle="modal" data-target="#contact" id="btn-contact" onclick="contact('${data}')"><i class="far fa-check-circle text-success"></i> ${full.tipe_contact_sekarang} ${full.contact_sekarang} </a>`
        //     }else{
        //       ret = `tidak ada data`
        //     }
        //     return ret
        //   }, className: "nowrap"
        // }
      ],
    });
  }

  dynamic_v2();
  global_dinamic = dynamic_v2;

  // delete
  $("#fdelete").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    $.LoadingOverlay("show");
    $.ajax({
      url: '<?= base_url() ?>biodata/delete',
      cache: false,
      contentType: false,
      processData: false,
      data: form,
      type: 'post',
      success: function (data) {
        Toast.fire({
          icon: 'success',
          title: 'Berhasil Dihapus'
        })
        dynamic_v2();
      },
      error: function (data) {
        Toast.fire({
          icon: 'error',
          title: 'Gagal Dihapus'
        })
        console.log(data);
      },
      complete: function () {
        $.LoadingOverlay("hide");
        $('#delete').modal('toggle')
      }
    });
  });

  // hapus
  $('#OkCheck').click(() => {
    let id = $("#idCheck").val()
    $.LoadingOverlay("show");
    Toast.fire({
      icon: 'success',
      title: 'Data berhasil dihapus'
    })
    $('#ModalCheck').modal('toggle')
    $.LoadingOverlay("hide");
  })
  

})

// Click Hapus
const Hapus = (datas) => {
  const data = datas.dataset;
  $("#delete-id_profile").val(data.id_profile)
  $("#delete-id_user").val(data.id_user)
  $('#delete').modal('toggle')
}

