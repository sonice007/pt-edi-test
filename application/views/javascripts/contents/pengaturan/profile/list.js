let global_dinamic = null;
$(function () {
  let text_partner = $("#isi-partner").val()
  let text_keanggotaan = $("#isi-kategori-keanggotaan").val()
  var url_string = location.href;
  var url = new URL(url_string);
  var filter_jk = url.searchParams.get("jk");
  var filter_level = url.searchParams.get("level");
  var filter_ulang_tahun = url.searchParams.get("ulang-tahun");
  $('#filter-partner').select2();
  $('#filter-posisi').select2();
  $('#filter-kategorial').select2();
  $('#filter-wilayah').select2();
  $('#filter-keanggotaan').select2();
  function dynamic_v2(datas = {
    partner: null,
    posisi: null,
    kategorial: null,
    wilayah: null,
    keanggotaan: null,
    usia: null,
    jenis_kelamin: null,
    level: null,
    ulang_tahun: null
  }) {
    let filter = null;
    // if (datas.partner != null) {
        filter = {
            partner: datas.partner,
            posisi: datas.posisi,
            kategorial: datas.kategorial,
            wilayah: datas.wilayah,
            keanggotaan: datas.keanggotaan,
            usia: datas.usia,
            jenis_kelamin: datas.jenis_kelamin,
            level: datas.level,
            ulang_tahun: datas.ulang_tahun,
        }
    // }
    const table_html = $('#dt_basic_v2');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>pengaturan/profile/ajax_data/",
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
            return `<div class="pull-right">
                <a class="btn btn-primary btn-xs" href="<?= base_url() ?>pengaturan/profile/tambah/${data}">
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
            "data": "tanggal_daftar", render(data, type, full, meta) {
                // return data == null || data == '' ? full.created_at : full.updated_at;
                return moment(data == null || data == '' ? full.tanggal_daftar : full.tanggal_daftar, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');

            }, className: "nowrap"
        },
        {
          "data": "lev_nama", render(data, type, full, meta) {
            return data
          }, className: "nowrap"
        },
        {
          "data": "nama_partner", render(data, type, full, meta) {
            return data
          }, className: "nowrap"
        },
        {
          "data": "nama_posisi", render(data, type, full, meta) {
            return data
          }, className: "nowrap"
        },
        {
          "data": "id", render(data, type, full, meta) {
            let ret = null
            if(full.keanggotaan != ''){
              ret = `<a style="cursor: pointer;" data-toggle="modal" data-target="#keanggotaan" id="btn-keanggotaan" onclick="keanggotaan('${data}')"><i class="far fa-check-circle text-success"></i> ${full.keanggotaan} ${full.nama_keanggotaan} </a>`
            }else{
              ret = `tidak ada data`
            }
            return ret
          }, className: "nowrap"
        },
        {
          "data": "nik", render(data, type, full, meta) {
            return data + full.kode_keanggotaan
          }, className: "nowrap"
        },
        {
          "data": "email", render(data, type, full, meta) {
            return (full.user_email_status == 0 ?  (`<i class="far fa-times-circle text-danger"></i> `) : (`<i class="far fa-check-circle text-success"></i> `)) + full.email
          }, className: "nowrap"
        },
        {
          "data": "nama_depan", render(data, type, full, meta) {
            return full.nama_belakang + " " + full.nama_depan
          }, className: "nowrap"
        },
        {
          "data": "jk", render(data, type, full, meta) {
            return data
          }, className: "nowrap"
        },
        {
          "data": "flag", render(data, type, full, meta) {
            if(data == 1){
              return 'Aktif'              
            }else{
              return 'Pasif'
            }
          }, className: "nowrap"
        },
        {
            "data": "tanggal_registrasi", render(data, type, full, meta) {
              return moment(data == null || data == '' ? full.tanggal_registrasi : full.tanggal_registrasi, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
            }, className: "nowrap"
        },
        {
            "data": "tanggal_lahir", render(data, type, full, meta) {
              let umur = hitungUmur(full.tanggal_lahir)
              if(full.tanggal_lahir == "" || full.tanggal_lahir == null){
                umur = ""
              }else{
                umur = " ("+umur+" Tahun)"
              }
              return moment(data == null || data == '' ? full.tanggal_lahir : full.tanggal_lahir, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY')+umur;
            }, className: "nowrap"
        },
        {
            "data": "tanggal_meninggal", render(data, type, full, meta) {
              let umur = hitungUmurMeninggal(full.tanggal_lahir,full.tanggal_meninggal)
              let tanggal = ''
              if(full.tanggal_meninggal == "" || full.tanggal_meninggal == null || full.tanggal_meninggal == '0000-00-00'){
                tanggal = ''
                umur = ""
              }else{
                tanggal = moment(full.tanggal_meninggal, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY')
                umur = " ("+umur+" Tahun)"
              }
              return tanggal+umur;
            }, className: "nowrap"
        },
        {
          "data": "photo", render(data, type, full, meta) {
            let photo = ''
            if(data == null || data == "null"){
              photo = 'tidak ada photo'
            }else{
              photo = data
            }
            return `<a
            class="btn btn-photo"
            data-toggle="modal"
            data-data="${data}"
            data-target="#photo"
            onclick="view_photo(this)"
            id="btn-photo">${photo}</a>`
          }, className: "nowrap"
        },
        {
          "data": "id", render(data, type, full, meta) {
            let ret = null
            if(full.membership != ''){
              ret = `<a style="cursor: pointer;" data-toggle="modal"
              data-data="${data}"
              data-target="#membership"
              id="btn-membership"
              onclick="membership('${data}')"><i class="far fa-check-circle text-success"></i> ${full.membership} </a>`
            }else{
              ret = `tidak ada data`
            }

            if(full.lv_login == 1 || full.id_atasan == full.id_log){
              if(full.lv_login == 1 ? full.st_membership != null && full.st_membership != '' && full.st_membership == 0 : full.st_membership != null && full.st_membership != '' && full.st_membership == 0 && full.id_partner == full.part_login){
                ret += `<button
                class="btn btn-primary btn-sm ml-1"
                data-id="${data}"
                data-id_membership="${full.id_membership}"
                data-st="1"
                id="btn-membership"
                onclick="set_st_membership(this)"
                ><i class="fas fa-check"></i></button>`+
                `<button
                class="btn btn-danger btn-sm ml-1"
                data-id="${data}"
                data-id_membership="${full.id_membership}"
                data-st="2"
                id="btn-membership"
                onclick="set_st_membership(this)"
              ><i class="fas fa-times"></i></button>`
              }
            }
            return ret
          }, className: "nowrap"
        },
        {
          "data": "id", render(data, type, full, meta) {
            let ret = null
            if(full.peristiwa_formal != ''){
              ret = `<a style="cursor: pointer;" data-toggle="modal" data-target="#formal"
            onclick="formal('${data}')" id="btn-formal"><i class="far fa-check-circle text-success"></i> ${full.peristiwa_formal} </a>`
            }else{
              ret = `tidak ada data`
            }

            return ret
          }, className: "nowrap"
        },
        {
          "data": "id", render(data, type, full, meta) {
            let ret = null
            if(full.tipe_contact_sekarang != ''){
              ret = `<a style="cursor: pointer;" data-toggle="modal" data-target="#contact" id="btn-contact" onclick="contact('${data}')"><i class="far fa-check-circle text-success"></i> ${full.tipe_contact_sekarang} ${full.contact_sekarang} </a>`
            }else{
              ret = `tidak ada data`
            }
            return ret
          }, className: "nowrap"
        },
        {
          "data": "id", render(data, type, full, meta) {
            let ret = null
            if(full.alamat_sekarang != ''){
              ret = `<a style="cursor: pointer;" data-toggle="modal" data-target="#lalamat" id="btn-lalamat" onclick="alamat('${data}')"><i class="far fa-check-circle text-success"></i> ${full.alamat_sekarang} </a>`
            }else{
              ret = `tidak ada data`
            }
            return ret
          }, className: "nowrap"
        },
        {
          "data": "id", render(data, type, full, meta) {
            let ret = null
            if(full.gelar_sekarang != ''){
              ret = `<a style="cursor: pointer;" data-toggle="modal" data-target="#education" id="btn-education" onclick="education('${data}')"><i class="far fa-check-circle text-success"></i> ${full.gelar_sekarang} </a>`
            }else{
              ret = `tidak ada data`
            }
            return ret
          }, className: "nowrap"
        },
        {
          "data": "id", render(data, type, full, meta) {
            let ret = null
            if(full.kategorial_sekarang != ''){
              ret = `<a style="cursor: pointer;" data-toggle="modal" data-target="#kategorial" id="btn-kategorial" onclick="kategorial('${data}')"><i class="far fa-check-circle text-success"></i> ${full.kategorial_sekarang} </a>`
            }else{
              ret = `tidak ada data`
            }
            return ret
          }, className: "nowrap"
        },
        {
          "data": "id", render(data, type, full, meta) {
            let ret = null
            if(full.wilayah_sekarang != ''){
              ret = `<a style="cursor: pointer;" data-toggle="modal" data-target="#wilayah" id="btn-wilayah" onclick="wilayah('${data}')"><i class="far fa-check-circle text-success"></i> ${full.wilayah_sekarang} </a>`
            }else{
              ret = `tidak ada data`
            }
            return ret
          }, className: "nowrap"
        }
      ],
      columnDefs: [{
        orderable: true,
        targets: [0, 5]
      }],
    });
  }

  function hitungUmur(tanggal_lahir=null){
    var today = new Date();
    var birthday = new Date(tanggal_lahir);
    var year = 0;
    if (today.getMonth() < birthday.getMonth()) {
      year = 1;
    } else if ((today.getMonth() == birthday.getMonth()) && today.getDate() < birthday.getDate()) {
      year = 1;
    }
    var age = today.getFullYear() - birthday.getFullYear() - year;
 
    if(age < 0){
      age = 0;
    }
    return age;
  }

  function hitungUmurMeninggal(tanggal_lahir=null, tanggal_meninggal = null){
    var tanggal_meninggal = new Date(tanggal_meninggal);
    var birthday = new Date(tanggal_lahir);
    var year = 0;
    if (tanggal_meninggal.getMonth() < birthday.getMonth()) {
      year = 1;
    } else if ((tanggal_meninggal.getMonth() == birthday.getMonth()) && tanggal_meninggal.getDate() < birthday.getDate()) {
      year = 1;
    }
    var age = tanggal_meninggal.getFullYear() - birthday.getFullYear() - year;
 
    if(age < 0){
      age = 0;
    }
    return age;
  }
  
  // dynamic_v2();
  dynamic_v2({
    partner: null,
    posisi: null,
    kategorial: null,
    wilayah: null,
    keanggotaan: null,
    usia: null,
    jenis_kelamin: filter_jk,
    level: filter_level,
    ulang_tahun: filter_ulang_tahun
  });
  global_dinamic = dynamic_v2;
  ajax_select(null, '#filter-partner', '<?= base_url(); ?>/pengaturan/profile/ajax_select_list_partner', null, null, 'Pilih '+text_partner);
  ajax_select(null, '#filter-posisi', '<?= base_url(); ?>/pengaturan/profile/ajax_select_list_posisi', null, null, 'Pilih Posisi');
  ajax_select(null, '#filter-kategorial', '<?= base_url(); ?>/pengaturan/profile/ajax_select_list_kategorial', null, null, 'Pilih Kategorial');
  ajax_select(null, '#filter-wilayah', '<?= base_url(); ?>/pengaturan/profile/ajax_select_list_wilayah', null, null, 'Pilih Wilayah');
  ajax_select(null, '#filter-keanggotaan', '<?= base_url(); ?>/pengaturan/profile/ajax_select_list_keanggotaan', null, null, 'Pilih '+text_keanggotaan);

  $("#btn-filter").click(() => {
    $('#stfilter').val(1);
    const partner = $('#filter-partner').val();
    const posisi = $('#filter-posisi').val();
    const kategorial = $('#filter-kategorial').val();
    const wilayah = $('#filter-wilayah').val();
    const keanggotaan = $('#filter-keanggotaan').val();
    const usia = $('#filter-usia').val();
    var a = document.getElementById('btn_export');
    var b = document.getElementById('btn_export_pdf');
    var link = `<?= base_url() ?>pengaturan/profile/export_excel`;
    var link_pdf = `<?= base_url() ?>pengaturan/profile/export_pdf`;
    a.href = link + `?filter-partner=${partner}&filter-posisi=${posisi}&filter-kategorial=${kategorial}&filter-wilayah=${wilayah}&filter-keanggotaan=${keanggotaan}&filter-usia=${usia}`;
    b.href = link_pdf + `?filter-partner=${partner}&filter-posisi=${posisi}&filter-kategorial=${kategorial}&filter-wilayah=${wilayah}&filter-keanggotaan=${keanggotaan}&filter-usia=${usia}`;
    dynamic_v2({
        partner: partner,
        posisi: posisi,
        kategorial: kategorial,
        wilayah: wilayah,
        keanggotaan: keanggotaan,
        usia: usia,
    });
});
  $("#btn-tambah").click(() => {
    $("#myModalLabel").text("Tambah List Profile");
    $('#id').val("");
    $('#nama').val("");
    $('#alamat').val("");
    $('#no_telp').val("");
    $('#email').val("");
    $('#resiko').val("");
    $('#remark').val("");
  });

    // Import
  $("#importExcel").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    $.LoadingOverlay("show");
    $.ajax({
        method: 'post',
        url: '<?= base_url() ?>pengaturan/profile/import_excel',
        data: form,
        cache: false,
        contentType: false,
        processData: false,
    }).done((data) => {
        Toast.fire({
            icon: 'success',
            title: 'Data berhasil diimport'
        })
        window.location.replace("<?= base_url() ?>pengaturan/profile");
        dynamic();
    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Data gagal diimport'
        })
    }).always(() => {
        $.LoadingOverlay("hide");
        $('#import').modal('toggle')
    })
  });

  // tambah dan ubah
  $("#form").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    $.LoadingOverlay("show");
    Toast.fire({
      icon: 'success',
      title: 'Data berhasil disimpan'
    })
    $.LoadingOverlay("hide");
    $('#myModal').modal('toggle')
  });

  // delete
  $("#fdelete").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    $.LoadingOverlay("show");
    $.ajax({
      url: '<?= base_url() ?>pengaturan/profile/delete',
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

const set_st_membership = (datas) => {
  $.LoadingOverlay("show");
  const data = datas.dataset;
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/set_st_membership',
    data: {
      id_profile : data.id,
      id_membership : data.id_membership,
      status : data.st
    },
    success: function (data) {
      Toast.fire({
        icon: 'success',
        title: 'Status Membership berhasil diubah'
      })
      global_dinamic();
    },
    error: function (data) {
      Toast.fire({
        icon: 'error',
        title: 'Status Membership gagal diubah'
      })
      console.log(data);
    },
    complete: function () {
      $.LoadingOverlay("hide");
    }
  });
}

const view_photo = (datas) => {
  $("#img-view").attr('src', `<?= base_url() ?>/files/profile/${datas.dataset.data}`)
}

const keanggotaan = (id) => {
  $.ajax({
    url: '<?= base_url() ?>pengaturan/profile/getKeanggotaan',
    data: {
      idp: id
    },
    type: 'post',
    success: function (data) {
      data = data.data;
      $('#tbl-head-keanggotaan').html(`
          <tr>
            <th>Kategori</th>
            <th>Nama</th>
            <th>Tanggal Mulai</th>
            <th>Status</th>
          </tr>
      `);
      $('#tbl-body-keanggotaan').html('');
      data.forEach((data) => {
        $('#tbl-body-keanggotaan').append(`
            <tr>
                <td>${data.nama}</td>
                <td>${data.keterangan}</td>
                <td>${data.tanggal}</td>
                <td>${data.status_str}</td>
            </tr>
        `)
      })
    },
    error: function (data) {
      Toast.fire({
        icon: 'error',
        title: 'Gagal mendapatkan data'
      })
      console.log(data);
    }
  });
}

const contact = (id) => {
  $.ajax({
    url: '<?= base_url() ?>pengaturan/profile/getContact1',
    data: {
      idp: id
    },
    type: 'post',
    success: function (data) {
      data = data.data;
      $('#tbl-head-contact').html(`
          <tr>
            <th>Tipe Kontak</th>
            <th>Keterangan</th>
            <th>Tanggal Mulai</th>
            <th>Status</th>
          </tr>
      `);
      $('#tbl-body-contact').html('');
      data.forEach((data) => {
        $('#tbl-body-contact').append(`
            <tr>
                <td>${data.tipe_contact}</td>
                <td>${data.keterangan}</td>
                <td>${data.tanggal}</td>
                <td>${data.status_str}</td>
            </tr>
        `)
      })
    },
    error: function (data) {
      Toast.fire({
        icon: 'error',
        title: 'Gagal mendapatkan data'
      })
      console.log(data);
    }
  });
}

const alamat = (id) => {
  $.ajax({
    url: '<?= base_url() ?>pengaturan/profile/getAlamat',
    data: {
      idp: id
    },
    type: 'post',
    success: function (data) {
      data = data.data;
      $('#tbl-head-alamat').html(`
          <tr>
            <th>Jenis Alamat</th>
            <th>Domisili</th>
            <th>Deskripsi</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Status</th>
          </tr>
      `);
      $('#tbl-body-alamat').html('');
      data.forEach((data) => {
        let tanggal_selesai = ''
        if(data.tanggal_selesai == null || data.tanggal_selesai == '' || data.tanggal_selesai == '0000-00-00'){
          tanggal_selesai = 'sekarang'
        }else{
          tanggal_selesai = moment(data.tanggal_selesai, 'YYYY-MM-DD').format('DD-MM-YYYY')
        }
        $('#tbl-body-alamat').append(`
            <tr>
                <td>${data.jenis_alamat}</td>
                <td>${data.domisili}</td>
                <td>${data.alamat}</td>
                <td>${moment(data.tanggal_mulai, 'YYYY-MM-DD').format('DD-MM-YYYY')}</td>
                <td>${tanggal_selesai}</td>
                <td>${data.status_str}</td>
            </tr>
        `)
      })
    },
    error: function (data) {
      Toast.fire({
        icon: 'error',
        title: 'Gagal mendapatkan data'
      })
      console.log(data);
    }
  });
}

const formal = (id) => {
  $.ajax({
    url: '<?= base_url() ?>pengaturan/profile/getFormal',
    data: {
      idp: id
    },
    type: 'post',
    success: function (data) {
      data = data.data;
      $('#tbl-head-formal').html(`
          <tr>
            <th>Peristiwa Formal</th>
            <th>Tanggal Event</th>
            <th>Tempat</th>
            <th>Status</th>
          </tr>
      `);
      $('#tbl-body-formal').html('');
      data.forEach((data) => {
        $('#tbl-body-formal').append(`
            <tr>
                <td>${data.peristiwa}</td>
                <td>${moment(data.tanggal_data_formal, 'YYYY-MM-DD').format('DD-MM-YYYY')}</td>
                <td>${data.tempat}</td>
                <td>${data.status_str}</td>
            </tr>
        `)
      })
    },
    error: function (data) {
      Toast.fire({
        icon: 'error',
        title: 'Gagal mendapatkan data'
      })
      console.log(data);
    }
  });
}

const education = (id) => {
  $.ajax({
    url: '<?= base_url() ?>pengaturan/profile/getEducation',
    data: {
      idp: id
    },
    type: 'post',
    success: function (data) {
      data = data.data;
      $('#tbl-head-education').html(`
          <tr>
            <th>Gelar</th>
            <th>Tanggal Lulus</th>
            <th>Lembaga</th>
            <th>Status</th>
          </tr>
      `);
      $('#tbl-body-education').html('');
      data.forEach((data) => {
        $('#tbl-body-education').append(`
            <tr>
                <td>${data.nama}</td>
                <td>${moment(data.tanggal_lulus, 'YYYY-MM-DD').format('DD-MM-YYYY')}</td>
                <td>${data.lembaga}</td>
                <td>${data.status_str}</td>
            </tr>
        `)
      })
    },
    error: function (data) {
      Toast.fire({
        icon: 'error',
        title: 'Gagal mendapatkan data'
      })
      console.log(data);
    }
  });
}

const kategorial = (id) => {
  $.ajax({
    url: '<?= base_url() ?>pengaturan/profile/getKategorial',
    data: {
      idp: id
    },
    type: 'post',
    success: function (data) {
      data = data.data;
      $('#tbl-head-kategorial').html(`
          <tr>
            <th>Nama</th>
            <th>Tanggal Mulai</th>
          </tr>
      `);
      $('#tbl-body-kategorial').html('');
      data.forEach((data) => {
        $('#tbl-body-kategorial').append(`
            <tr>
                <td>${data.nama}</td>
                <td>${moment(data.tanggal, 'YYYY-MM-DD').format('DD-MM-YYYY')}</td>
            </tr>
        `)
      })
    },
    error: function (data) {
      Toast.fire({
        icon: 'error',
        title: 'Gagal mendapatkan data'
      })
      console.log(data);
    }
  });
}

const wilayah = (id) => {
  $.ajax({
    url: '<?= base_url() ?>pengaturan/profile/getWilayah',
    data: {
      idp: id
    },
    type: 'post',
    success: function (data) {
      data = data.data;
      $('#tbl-head-wilayah').html(`
          <tr>
            <th>Nama</th>
            <th>Tanggal Mulai</th>
          </tr>
      `);
      $('#tbl-body-wilayah').html('');
      data.forEach((data) => {
        $('#tbl-body-wilayah').append(`
            <tr>
                <td>${data.nama}</td>
                <td>${moment(data.tanggal, 'YYYY-MM-DD').format('DD-MM-YYYY')}</td>
            </tr>
        `)
      })
    },
    error: function (data) {
      Toast.fire({
        icon: 'error',
        title: 'Gagal mendapatkan data'
      })
      console.log(data);
    }
  });
}

const membership = (id) => {
  let alias_name = $("#alias_name_membership").val()
  $.ajax({
    url: '<?= base_url() ?>pengaturan/profile/getMembership',
    data: {
      idp: id
    },
    type: 'post',
    success: function (data) {
      data = data.data;
      $('#tbl-head-membership').html(`
          <tr>
            <th>Jenis ${alias_name}</th>
            <th>Atasan</th>
            <th>Tanggal</th>
            <th>Status</th>
          </tr>
      `);
      $('#tbl-body-membership').html('');
      data.forEach((data) => {
        $('#tbl-body-membership').append(`
            <tr>
                <td>${data.nama}</td>
                <td>${data.nama_atasan}</td>
                <td>${moment(data.tanggal, 'YYYY-MM-DD').format('DD-MM-YYYY')}</td>
                <td>${data.status_str}</td>
            </tr>
        `)
      })
    },
    error: function (data) {
      Toast.fire({
        icon: 'error',
        title: 'Gagal mendapatkan data'
      })
      console.log(data);
    }
  });
}
