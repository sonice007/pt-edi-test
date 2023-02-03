const profile_id = $("#id").val();
const id_membership = $("#id_membership").val();
let tabelHapus = '';
membership_check = true;
const table_length = new Map();
table_length.set('alamat', 0);
table_length.set('membership', 0);
table_length.set('formal', 0);
table_length.set('education', 0);
table_length.set('kontak', 0);
$(document).ready(() => {


  $("#email").change(function () {
    emailCheck(this);
  })

  $("#no_ktp").change(function () {
    ktpCheck(this);
  })

  // $("#penghasilan").keyup(function(e){
  //   let number = $(this).val()
  //   number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, ",.")
  // });

  // table pendidikan_terakhir
  function tblPendidikan() {
    const idp = $("#id").val()
    const table_html = $('#tblPendidikan');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>biodata/getPendidikan/",
        "data": { idp: idp },
        "type": 'POST'
      },
      "processing": true,
      "serverSide": true,
      "scrollX": true,
      "lengthChange": true,
      "autoWidth": false,
      "searching": false,
      "paging": false,
      "info": false,
      "columns": [
        { "data": "jenjang_pendidikan_terakhir", className: "nowrap" },
        { "data": "nama_institusi_akademik", className: "nowrap" },
        { "data": "jurusan", className: "nowrap" },
        { "data": "tahun_lulus", className: "nowrap" },
        { "data": "ipk", className: "nowrap" },
        { "data": "status_str" },
        {
          "data": "id", render(data, type, full, meta) {
            let btn_edit = null
            let btn_hapus = null
              btn_edit = `<button type="button" class="btn btn-primary btn-xs" onclick="ubah_pendidikan(${data})">
                <i class="fa fa-edit"></i> Ubah
              </button>`;

              btn_hapus = `<button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data},'profile_pendidikan')">
                <i class="fa fa-trash"></i> Hapus
              </button>`;

            return `<div class="pull-right">
              ${btn_edit}
              ${btn_hapus}
            </div>`
          }, className: "nowrap"
        }
      ],
      "initComplete": function (settings, json) {
        table_length.set('pendidikan', json.data.length);
      }
    });
  }

  function tblPelatihan() {
    const idp = $("#id").val()
    const table_html = $('#tblPelatihan');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>biodata/getPelatihan/",
        "data": { idp: idp },
        "type": 'POST'
      },
      "processing": true,
      "serverSide": true,
      "scrollX": true,
      "lengthChange": true,
      "autoWidth": false,
      "searching": false,
      "paging": false,
      "info": false,
      "columns": [
        { "data": "nama_kursus_seminar", className: "nowrap" },
        { "data": "sertifikat", className: "nowrap" },
        { "data": "tahun", className: "nowrap" },
        { "data": "status_str" },
        {
          "data": "id", render(data, type, full, meta) {
            let btn_edit = null
            let btn_hapus = null
              btn_edit = `<button type="button" class="btn btn-primary btn-xs" onclick="ubah_pelatihan(${data})">
                <i class="fa fa-edit"></i> Ubah
              </button>`;

              btn_hapus = `<button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data},'profile_pelatihan')">
                <i class="fa fa-trash"></i> Hapus
              </button>`;

            return `<div class="pull-right">
              ${btn_edit}
              ${btn_hapus}
            </div>`
          }, className: "nowrap"
        }
      ],
      "initComplete": function (settings, json) {
        table_length.set('pelatihan', json.data.length);
      }
    });
  }

  function tblPekerjaan() {
    const idp = $("#id").val()
    const table_html = $('#tblPekerjaan');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>biodata/getPekerjaan/",
        "data": { idp: idp },
        "type": 'POST'
      },
      "processing": true,
      "serverSide": true,
      "scrollX": true,
      "lengthChange": true,
      "autoWidth": false,
      "searching": false,
      "paging": false,
      "info": false,
      "columns": [
        { "data": "nama_perusahaan", className: "nowrap" },
        { "data": "posisi_terakhir", className: "nowrap" },
        { "data": "pendapatan_terakhir", className: "nowrap" },
        { "data": "tahun", className: "nowrap" },
        { "data": "status_str" },
        {
          "data": "id", render(data, type, full, meta) {
            let btn_edit = null
            let btn_hapus = null
              btn_edit = `<button type="button" class="btn btn-primary btn-xs" onclick="ubah_pekerjaan(${data})">
                <i class="fa fa-edit"></i> Ubah
              </button>`;

              btn_hapus = `<button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data},'profile_pekerjaan')">
                <i class="fa fa-trash"></i> Hapus
              </button>`;

            return `<div class="pull-right">
              ${btn_edit}
              ${btn_hapus}
            </div>`
          }, className: "nowrap"
        }
      ],
      "initComplete": function (settings, json) {
        table_length.set('pekerjaan', json.data.length);
      }
    });
  }
  
  // inital table

  tblPendidikan()
  tblPelatihan()
  tblPekerjaan()

  // tambah dan ubah contact

  $("#btn-pendidikan").click(() => {
    $('#status_pendidikan').val(1).trigger('change');
    $('#id_pendidikan').val("");
    $('#jenjang_pendidikan_terakhir').val("");
    $('#nama_institusi_akademik').val("");
    $('#jurusan').val("");
    $('#tahun_lulus').val("");
    $('#ipk').val("");
  })

  $("#fpendidikan").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>biodata/' + ($("#id_pendidikan").val() == "" ? 'insert_pendidikan' : 'update_pendidikan'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      tblPendidikan();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#pendidikan').modal('toggle')
    })
  });

  $("#fpelatihan").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>biodata/' + ($("#id_pelatihan").val() == "" ? 'insert_pelatihan' : 'update_pelatihan'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      tblPelatihan();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#pelatihan').modal('toggle')
    })
  });

  $("#fpekerjaan").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>biodata/' + ($("#id_pekerjaan").val() == "" ? 'insert_pekerjaan' : 'update_pekerjaan'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      tblPekerjaan();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#pekerjaan').modal('toggle')
    })
  });

  $("#main-form").submit(function (ev) {

    ev.preventDefault();
    const form = new FormData(this);
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>biodata/simpan',
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      setTimeout(() => {
        location.replace("<?= base_url() ?>biodata/tambah");
      }, 300)
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
    })
  });

  $('#OkCheck').click(() => {
    let id = $("#idCheck").val()
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>biodata/hapusDaftar',
      data: {
        id: id,
        tbl: tabelHapus
      }
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil dihapus'
      })
      tblPendidikan()
      tblPelatihan()
      tblPekerjaan()
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal dihapus'
      })
    }).always(() => {
      $('#ModalCheck').modal('toggle')
      $.LoadingOverlay("hide");
    })
  })
})

function emailCheck(email_ele) {
  const email = $(email_ele);
  if (email.val() == '') {
    return;
  }

  // email.attr('readonly', '')
  $.ajax({
    url: '<?= base_url() ?>pengaturan/profile/emailCheck',
    data: {
      id_user: global_id_user,
      email: email.val()
    },
    type: 'post',
    success: function (data) {
      Toast.fire({
        icon: 'success',
        title: 'Success Email Belum Terdaftar'
      })
    },
    error: function (data) {
      Toast.fire({
        icon: 'error',
        title: data.responseJSON.message
      })
      email.val('');
      email.focus();
    },
    complete: function () {
      // email.removeAttr('readonly');
    }
  });
}

function ktpCheck(nik_ele) {
  const nik = $(nik_ele);

  let hitung = $("#no_ktp").val().length
  if(hitung < 16){
    Toast.fire({
      icon: 'error',
      title: "No. KTP harus 16 digit"
    })
    nik.focus();
  }else{
    if (nik.val() == '') {
      return;
    }

    $.ajax({
      url: '<?= base_url() ?>biodata/nikCheck',
      data: {
        id_user: global_id_user,
        nik: nik.val()
      },
      type: 'post',
      success: function (data) {
        Toast.fire({
          icon: 'success',
          title: 'Success Nik Belum Terdaftar'
        })
      },
      error: function (data) {
        Toast.fire({
          icon: 'error',
          title: data.responseJSON.message
        })
        nik.val('');
        nik.focus();
      },
      complete: function () {

      }
    });
  }

  
}

// Click Hapus
const Hapus = (id, tbl) => {
  $("#idCheck").val(id)
  tabelHapus = tbl
  $("#LabelCheck").text('Form Hapus')
  $("#ContentCheck").text('Apakah anda yakin akan menghapus data ini?')
  $('#ModalCheck').modal('toggle')
}


// Click Ubah
const ubah_pendidikan = (id) => {
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>biodata/getPendidikanByID',
    data: {
      id: id
    }
  }).done((data) => {
    if (data.data) {
      data = data.data[0];
      $("#myModalLabel").text("Ubah Pendidikan");
      $('#status_pendidikan').val(data.status).trigger('change');
      $('#id_pendidikan').val(data.id);
      $('#jenjang_pendidikan_terakhir').val(data.jenjang_pendidikan_terakhir);
      $('#nama_institusi_akademik').val(data.nama_institusi_akademik);
      $('#jurusan').val(data.jurusan);
      $('#tahun_lulus').val(data.tahun_lulus);
      $('#ipk').val(data.ipk);
      $('#pendidikan').modal('toggle')
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

const ubah_pelatihan = (id) => {
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>biodata/getPelatihanByID',
    data: {
      id: id
    }
  }).done((data) => {
    if (data.data) {
      data = data.data[0];
      $("#myModalLabel").text("Ubah Pelatihan");
      $('#status_pelatihan').val(data.status).trigger('change');
      $('#id_pelatihan').val(data.id);
      $('#nama_kursus_seminar').val(data.nama_kursus_seminar);
      $('#sertifikat').val(data.sertifikat);
      $('#tahun').val(data.tahun);
      $('#pelatihan').modal('toggle')
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

const ubah_pekerjaan = (id) => {
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>biodata/getPekerjaanByID',
    data: {
      id: id
    }
  }).done((data) => {
    if (data.data) {
      data = data.data[0];
      $("#myModalLabel").text("Ubah Pekerjaan");
      $('#status_pekerjaan').val(data.status).trigger('change');
      $('#id_pekerjaan').val(data.id);
      $('#nama_perusahaan').val(data.nama_perusahaan);
      $('#posisi_terakhir').val(data.posisi_terakhir);
      $('#pendapatan_terakhir').val(data.pendapatan_terakhir);
      $('#tahun_pekerjaan').val(data.tahun);
      $('#pekerjaan').modal('toggle')
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
