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

  getCode()
  function getCode(){
    if($("#user_id").val() == ""){
      let tanggal = $("#tanggal_registrasi").val()
      let bulan = moment(tanggal, 'YYYY-MM-DD').format('MM')
      let tahun = moment(tanggal, 'YYYY-MM-DD').format('YYYY')
      $.ajax({
        method: 'post',
        url: '<?= base_url() ?>pengaturan/profile/getCodeKeluarga',
        data: {
          id: 0,
          bulan: bulan,
          tahun: tahun
        }
      }).done((data) => {
        $('#nik').val(data.id);
      })
    }
  }

  function getCodeDataFormal(id_kategori=null){
    let  tanggal = new Date();
    let bulan = moment(tanggal, 'YYYY-MM-DD').format('MM')
    let tahun = moment(tanggal, 'YYYY-MM-DD').format('YYYY')
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/getCodeDataFormal',
      data: {
        id: 0,
        bulan: bulan,
        tahun: tahun,
        id_kategori: id_kategori
      }
    }).done((data) => {
      $('#kode_kategori').val(data.kode_kategori);
      $('#kode_data_formal').val(data.id);
    })
  }

  let jenis_pengesah = $("#jenis_pengesah").val()
  if(jenis_pengesah == ""){
    jenis_pengesah = 0
  }
  let isi_keanggotaan = $("#isi_kategori").val()
  // perhitungan umur saat ini
  let tanggal_lahir = $("#tanggal_lahir").val()

  let umur = hitungUmur(tanggal_lahir)
  if(umur >= 0){
    umur = umur+ " Tahun"
  }else{
    umur = ''
  }
  $("#umur").val(umur)

  // perhitungan umur terakhir sebelum meninggal
  let tanggal_meninggal = $("#tanggal_meninggal").val()
  let tanggal_registrasi = $("#tanggal_registrasi").val()
  let umur_terakhir = hitungUmurMeninggal(tanggal_lahir, tanggal_meninggal)
  if(umur_terakhir >= 0){
    umur_terakhir = umur_terakhir+" Tahun";
  }else{
    umur_terakhir = "";
  }
  $("#umur_terakhir").val(umur_terakhir)

  ajax_select({ id: '#btn-tambah', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#id_kategori', '<?= base_url(); ?>pengaturan/profile/ajax_select_list_kategori', null, '#myModal', 'Pilih Kategori', isi_keanggotaan);
  

  // let isi_kategorial = $("#id_kategorial").val()
  // ajax_select({ id: '#btn-tambah', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#id_kategorial', '<?= base_url(); ?>pengaturan/profile/ajax_select_list_kategorial', null, '#myModal', 'Pilih Kategorial Umur', id_kategorial);

  // let isi_wilayah = $("#id_wilayah").val()
  // ajax_select({ id: '#btn-tambah', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#id_wilayah', '<?= base_url(); ?>pengaturan/profile/ajax_select_list_wilayah', null, '#myModal', 'Pilih Wilayah', id_wilayah);

  // fungsional keaktifan
    $("#id_jabatan").select2();
    ajax_select({ id: '#btn-keaktifan', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#id_jabatan', '<?= base_url(); ?>pengaturan/profile/ajax_select_list_jabatan', null, '', 'Pilih Jabatan');
    $("#kode_master").change(function () {
      let kode_master = $(this).val()
      ajax_select({ id: '#btn-keaktifan', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#id_master', '<?= base_url(); ?>pengaturan/profile/ajax_select_list_master', {kode_master:kode_master}, '', 'Pilih Master');
    })
  // tutup keaktifan

  $("#membership_jenis").select2();
  $("#peristiwa_formal").select2();
  $("#id_partner").select2();
  $("#id_level").select2();
  $("#jenis_alamat").select2();
  $("#tipe_kontak").select2();
  $("#atasan").select2();
  $("#nama").select2();
  $("#email").change(function () {
    emailCheck(this);
  })
  $("#nik").change(function () {
    nikCheck(this);
  })

  $("#tanggal_registrasi").change(function () {
    let tanggal = $(this).val()
    let bulan = moment(tanggal, 'YYYY-MM-DD').format('MM')
    let tahun = moment(tanggal, 'YYYY-MM-DD').format('YYYY')
      $.ajax({
        method: 'post',
        url: '<?= base_url() ?>pengaturan/profile/getCode',
        data: {
          id: profile_id,
          bulan: bulan,
          tahun: tahun
        }
    }).done((data) => {
      console.log(data)
      $('#nik').val(data.id);
    })
  })

  $("#tanggal_lahir").change(function () {
    let tanggal_lahir = $(this).val()
    let umur = hitungUmur(tanggal_lahir)
    $("#umur").val(umur+" Tahun")
  })

  $("#tanggal_meninggal").change(function () {
    let tanggal_meninggal = $(this).val()
    let tanggal_lahir = $("#tanggal_lahir").val()
    let umur_terakhir = hitungUmurMeninggal(tanggal_lahir,tanggal_meninggal)
    $("#umur_terakhir").val(umur_terakhir+" Tahun")
  })

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

  $("#membership_jenis").change(function () {
    if (this.value == '' || !membership_check) {
      membership_check = true;
      return;
    }
    if($("#membership_jenis").val() == 1 || $("#membership_jenis option:selected").text() == "BOD"){
      $("#atasan").prop('required',false)
    }else{
      ajax_select(null, '#atasan', '<?= base_url(); ?>pengaturan/profile/ajax_select_list_atasan', {id_level:this.value}, '', 'Pilih Atasan');
      // $("#atasan").prop('required',true)
    }
    membershipCheck(this);
  })


  // table wilayah
  function tblKeaktifan() {
    const idp = $("#id").val()
    const table_html = $('#tblKeaktifan');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>pengaturan/profile/getKeaktifan/",
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
        { "data": "nama" },
        { "data": "tanggal_mulai", render(data, type, full, meta) {
            return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
          }, className: 'nowrap'
        },
         { "data": "tanggal_selesai", render(data, type, full, meta) {
          if(data == null || data == '' || data == '0000-00-00'){
            return 'sekarang'
          }else{
            return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
          }

          }, className: 'nowrap'
        },
        { "data": "kode_master" },
        { "data": "text_master" },
        { "data": "status_str" },
        {
          "data": "id", render(data, type, full, meta) {
            return `<div class="pull-right">
              <button type="button" class="btn btn-primary btn-xs" onclick="ubah_keaktifan(${data})">
                <i class="fa fa-edit"></i> Ubah
              </button>
              <button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data},'profile_keaktifan')">
                <i class="fa fa-trash"></i> Hapus
              </button>
            </div>`
          }, className: "nowrap"
        }
      ],
      "initComplete": function (settings, json) {
        table_length.set('formal', json.data.length);
      }
    });
  }

  // table wilayah
  function tblWilayah() {
    const idp = $("#id").val()
    const table_html = $('#tblWilayah');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>pengaturan/profile/getWilayah/",
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
        { "data": "nama" },
        { "data": "tanggal", render(data, type, full, meta) {
            return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
          }, className: 'nowrap'
        },
        { "data": "status_str" },
        {
          "data": "id", render(data, type, full, meta) {
            return `<div class="pull-right">
              <button type="button" class="btn btn-primary btn-xs" onclick="ubah_wilayah(${data})">
                <i class="fa fa-edit"></i> Ubah
              </button>
              <button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data},'profile_wilayah')">
                <i class="fa fa-trash"></i> Hapus
              </button>
            </div>`
          }, className: "nowrap"
        }
      ],
      "initComplete": function (settings, json) {
        table_length.set('formal', json.data.length);
      }
    });
  }

  // table kategorial umur
  function tblKategorial() {
    const idp = $("#id").val()
    const table_html = $('#tblKategorial');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>pengaturan/profile/getKategorial/",
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
        { "data": "nama" },
        { "data": "tanggal", render(data, type, full, meta) {
            return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
          }, className: 'nowrap'
        },
        {
          "data": "id", render(data, type, full, meta) {
            return `<div class="pull-right">
              <button type="button" class="btn btn-primary btn-xs" onclick="ubah_kategorial(${data})">
                <i class="fa fa-edit"></i> Ubah
              </button>
              <button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data},'profile_kategorial')">
                <i class="fa fa-trash"></i> Hapus
              </button>
            </div>`
          }, className: "nowrap"
        }
      ],
      "initComplete": function (settings, json) {
        table_length.set('formal', json.data.length);
      }
    });
  }

  // table kategori keanggotaan
  function tblKategoriKeanggotaan() {
    const idp = $("#id").val()
    const table_html = $('#tblKategoriKeanggotaan');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>pengaturan/profile/getKategoriKeanggotaan/",
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
        { "data": "nama", className: 'nowrap' },
        { "data": "keterangan_keanggotaan", className: 'nowrap' },
        { "data": "tanggal", render(data, type, full, meta) {
            return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
          }, className: 'nowrap'
        },
        { "data": "tanggal_approval", render(data, type, full, meta) {
            if(data == null){
              return "";              
            }else{
              return full.nama_atasan + " (" +moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY')+")";              
            }
          }, className: 'nowrap'
        },
        { "data": "status_str", className: 'nowrap' },
        {
          "data": "id", render(data, type, full, meta) {
            if(id_membership == 1 || id_membership == 2 || id_membership == 3){
              if(full.status == 1){
                return `<div class="pull-right">
                  <button type="button" class="btn btn-success btn-xs" onclick="status_kategori_keanggotaan(${data},2)">
                    <i class="fa fa-check"></i> Terima
                  </button>
                  <button type="button" class="btn btn-warning btn-xs" onclick="status_kategori_keanggotaan(${data},3)">
                    <i class="fa fa-times"></i> Tolak
                  </button>
                  <button type="button" class="btn btn-primary btn-xs" onclick="ubah_kategori_keanggotaan(${data})">
                    <i class="fa fa-edit"></i> Ubah
                  </button>
                  <button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data},'profile_keanggotaan')">
                    <i class="fa fa-trash"></i> Hapus
                  </button>
                </div>`
              }else if(full.status == 3 || full.status == 4){
                return ``
              }else{
                  // <button type="button" class="btn btn-danger btn-xs" onclick="status_kategori_keanggotaan(${data},4)">
                  //   <i class="fa fa-trash"></i> Non Aktif
                  // </button>
                return `
                </div>`
              }
            }else{
              if(full.status == 1){
               return `<div class="pull-right">
                  <button type="button" class="btn btn-primary btn-xs" onclick="ubah_kategori_keanggotaan(${data})">
                    <i class="fa fa-edit"></i> Ubah
                  </button>
                  <button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data},'profile_keanggotaan')">
                    <i class="fa fa-trash"></i> Hapus
                  </button>
                </div>`
              }else if(full.status == 3 || full.status == 4){
                return ``
              }else{
                // <button type="button" class="btn btn-danger btn-xs" onclick="status_kategori_keanggotaan(${data},4)">
                //             <i class="fa fa-trash"></i> Non Aktif
                //           </button>
                return `
                        </div>`
              }
            }
          }, className: "nowrap"
        }
      ],
      "initComplete": function (settings, json) {
        table_length.set('formal', json.data.length);
      }
    });
  }

  // tabel Alamat
  function tblAlamat() {
    const idp = $("#id").val()
    const table_html = $('#tblalamat');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>pengaturan/profile/getAlamat/",
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
        { "data": "jenis_alamat" },
        { "data": "domisili" },
        { "data": "alamat" },
        { "data": "tanggal_mulai", render(data, type, full, meta) {
            return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
          }, className: 'nowrap'
        },
        { "data": "tanggal_selesai", render(data, type, full, meta) {
          if(data == null || data == '' || data == '0000-00-00'){
            return 'sekarang'
          }else{
            return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
          }

          }, className: 'nowrap'
        },
        { "data": "status_str" },
        {
          "data": "id", render(data, type, full, meta) {
            return `<div class="pull-right">
                <button type="button" class="btn btn-primary btn-xs" onclick="ubah_alamat(${data})">
                  <i class="fa fa-edit"></i> Ubah
                </button>
                <button  type="button"  class="btn btn-danger btn-xs" onclick="Hapus(${data},'alamat')">
                  <i class="fa fa-trash"></i> Hapus
                </button>
              </div>`
          }, className: "nowrap"
        }
      ],
      "initComplete": function (settings, json) {
        table_length.set('alamat', json.data.length);
      }
    });
  }

  // table formal
  function tblFormal() {
    const idp = $("#id").val()
    const table_html = $('#tblFormal');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>pengaturan/profile/getFormal/",
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
        {
          "data": "id", render(data, type, full, meta) {
            if(id_membership == 1){
              if(full.status == 0){
                return `<div class="pull-right">
                  <button type="button" class="btn btn-success btn-xs" onclick="status_formal(${data},1)">
                    <i class="fa fa-check"></i> Disetujui
                  </button>
                  <button type="button" class="btn btn-warning btn-xs" onclick="status_formal(${data},2)">
                    <i class="fa fa-times"></i> Ditolak
                  </button>
                  <button type="button" class="btn btn-primary btn-xs" onclick="cetak_formal(${data})">
                    <i class="fa fa-print"></i> Cetak
                  </button>
                </div>`
              }else if(full.status == 3 || full.status == 2){
                return ` <div class="pull-right">
                  <button type="button" class="btn btn-primary btn-xs" onclick="cetak_formal(${data})">
                    <i class="fa fa-print"></i> Cetak
                  </button>
                </div>`
              }else{
                return `
                  <div class="pull-right">
                    <button type="button" class="btn btn-primary btn-xs" onclick="cetak_formal(${data})">
                      <i class="fa fa-print"></i> Cetak
                    </button>
                  </div>`
              }
            }else{
              if(full.status == 0){
               return `<div class="pull-right">
                  <button type="button" class="btn btn-primary btn-xs" onclick="cetak_formal(${data})">
                    <i class="fa fa-print"></i> Cetak
                  </button>
                </div>`
              }else if(full.status == 3 || full.status == 4){
                return `<div class="pull-right">
                    <button type="button" class="btn btn-primary btn-xs" onclick="cetak_formal(${data})">
                      <i class="fa fa-print"></i> Cetak
                    </button>
                  </div>`
              }else{
                return `<div class="pull-right">
                    <button type="button" class="btn btn-primary btn-xs" onclick="cetak_formal(${data})">
                      <i class="fa fa-print"></i> Cetak
                    </button>
                  </div>`
              }
            }
          }, className: "nowrap"
        },
        { "data": "kode", className: 'nowrap' },
        { "data": "peristiwa", className: 'nowrap' },
        { "data": "institusi", className: 'nowrap' },
        { "data": "id", render(data, type, full, meta) {
            return full.nama_depan+" "+full.nama_belakang;
          }, className: 'nowrap'
        },
        { "data": "keterangan" },
        { "data": "tanggal_data_formal", render(data, type, full, meta) {
            return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
          }, className: 'nowrap'
        },
        { "data": "tempat", className: 'nowrap' },
        {
            "data": "photo", render(data, type, full, meta) {
                if(data == null || data == ''){
                    return `Belum ada photo`
                }else{
                    return `<a style='cursor: pointer;' onclick='LihatPhoto(${full.id})'><i class='fa fa-eye'></i> Lihat</a>`                                                        
                }
            }, className: "nowrap"
        },
        { "data": "status_str", className: 'nowrap' },
        { "data": "keterangan_2" },
        { "data": "rencana_tanggal", render(data, type, full, meta) {
          if(data == null || data == '' || data == '0000-00-00'){
            return ''
          }else{
            return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
          }

          }, className: 'nowrap'
        },
        { "data": "upload_hardcopy", className: 'nowrap' },
        { "data": "status2_str", className: 'nowrap' }
      ],
      "initComplete": function (settings, json) {
        table_length.set('formal', json.data.length);
      }
    });
  }

  // tabel education
  function tblEducation() {
    const idp = $("#id").val()
    const table_html = $('#tblEducation');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>pengaturan/profile/getEducation/",
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
        { "data": "nama" },
        { "data": "tanggal_lulus", render(data, type, full, meta) {
          return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
        }, className: 'nowrap'
       },
        { "data": "lembaga" },
        // { "data": "status_str" },
        {
          "data": "id", render(data, type, full, meta) {
            return `<div class="pull-right">
              <button type="button" class="btn btn-primary btn-xs" onclick="ubah_education(${data})">
                <i class="fa fa-edit"></i> Ubah
              </button>
              <button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data},'gelar')">
                <i class="fa fa-trash"></i> Hapus
              </button>
            </div>`
          }, className: "nowrap"
        }
      ],
      "initComplete": function (settings, json) {
        table_length.set('education', json.data.length);
      }
    });
  }

  // tabel membership
  function tblMembership() {
    const idp = $("#id").val()
    const table_html = $('#tblmembership  ');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>pengaturan/profile/getMembership/",
        "data": { idp: idp },
        "type": 'POST'
      },
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "searching": false,
      "paging": false,
      "info": false,
      "columns": [
        { "data": "nama" },
        // { "data": "nama_atasan" },
        { "data": "tanggal", render(data, type, full, meta) {
            return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
          }, className: 'nowrap'
        },
        { "data": "tanggal_approval", render(data, type, full, meta) {
            if(data == null){
              return "";              
            }else{
              return full.nama_atasan + " (" +moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY')+")";              
            }
          }, className: 'nowrap'
        },
        { "data": "status_str", className: 'nowrap' },
        {
          "data": "id", render(data, type, full, meta) {
            // <button type="button"
            // class="btn btn-info btn-xs"
            // data-id_profile="${idp}"
            // data-id="${data}"
            // data-title="${full.nama}"
            // data-st="3"
            // onclick="set_active_membership(this)">
            //   <i class="fa fa-key"></i> Non-Aktifkan
            // </button>
            const btn_nonactive = full.status == 1 ? `
            `: '';
            const btn_active = full.status == 3 ? `

            `: '';
            const btn_delete = full.status == 2 ? `
              
            ` : '';
            const btn_ubah = jenis_pengesah <= 2 ?
              full.status == 0 ? `
               <a
              class="btn btn-primary btn-sm ml-1"
              data-id_profile="${full.id_profile}"
              data-id_membership="${full.id}"
              data-st="1"
              data-id_approver="${full.id_approver}"
              id="btn-membership"
              onclick="set_st_membership(this)"
              ><i class="fas fa-check"></i></a>`+
              `<a
              class="btn btn-danger btn-sm ml-1"
              data-id_profile="${full.id_profile}"
              data-id_membership="${full.id}"
              data-st="2"
              id="btn-membership"
              onclick="set_st_membership(this)"
              ><i class="fas fa-times"></i></a>
              ` : ''  : '' 
            const btn_persetujuan = full.status == 9 ? `
            <button type="button" class="btn btn-warning btn-xs"
              data-id="${full.id}"
              data-id_approver="${full.id_approver}"
              data-id_jenis="${full.id_jenis}"
              data-status="${full.status}"
              data-tanggal="${full.tanggal}"
              onclick="ubah_membership(this)">
                <i class="fa fa-edit"></i> Ubah
              </button>
              <button type="button" class="btn btn-primary btn-xs"
              data-id="${full.id}"
              data-id_approver="${full.id_approver}"
              data-id_jenis="${full.id_jenis}"
              data-status="${full.status}"
              data-tanggal="${full.tanggal}"
              onclick="minta_membership(this)">
                <i class="fa fa-edit"></i> Meminta Persetujuan
              </button>
            ` : '';
            if(full.id_jenis == 2){
              if(jenis_pengesah == 1){
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_ubah}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
              }else{
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
              }
            }else if(full.id_jenis == 3){
              if(jenis_pengesah > 0 && jenis_pengesah <= 2){
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_ubah}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
              }else{
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
              }
            }else if(full.id_jenis > 3 && full.id_jenis <= 5){
              if(jenis_pengesah > 0 && jenis_pengesah <= 2){
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_ubah}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
              }else{
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
              }
            }else{
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
            }
          }, className: "nowrap"
        }
      ],
      "initComplete": function (settings, json) {
        table_length.set('membership', json.data.length);
      }
    });
  
    $.ajax({
      method: 'post',
      url: '<?= base_url()?>pengaturan/profile/getMembership/',
      data: { idp: idp },
    }).done((data) => {
      if(data.data.length == 0){
        $("#btn-membership").show()
      }else{
        let cek_status = 0
        let cek_status_proses = 0
        data.data.forEach(e => {
          if(e.status_str == "Disetujui"){
            cek_status = cek_status + 1
          }else if(data.data[0].status_str == "Tidak Aktif"){
            cek_status = cek_status + 1
          }else if(data.data[0].status_str == "Ditolak"){
            cek_status = cek_status + 1
          }
          if(cek_status > 0){
            $("#btn-membership").show()
          }else{
            $("#btn-membership").hide()
          }

          if(e.status_str == "Belum di minta persetujuan"){
            cek_status_proses = cek_status_proses + 1
          }

          if(e.status_str == "Persetujuan dimintakan"){
            cek_status_proses = cek_status_proses + 1
          }
        });

        if(cek_status_proses == 1){
          $("#btn-membership").hide()                      
        }
      }
    })

  }

  // tabel kontak
  function tblKontak() {
    const idp = $("#id").val()
    const table_html = $('#tblKontak');
    table_html.dataTable().fnDestroy()
    table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>pengaturan/profile/getKontak/",
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
        { "data": "tipe_kontak" },
        { "data": "keterangan" },
        { "data": "tanggal", render(data, type, full, meta) {
            return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
          }, className: 'nowrap'
        },
        { "data": "status_str" },
        {
          "data": "id", render(data, type, full, meta) {
            return `<div class="pull-right">
              <button type="button" class="btn btn-primary btn-xs" onclick="ubah_kontak(${data})">
                <i class="fa fa-edit"></i> Ubah
              </button>
              <button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data},'contact')">
                <i class="fa fa-trash"></i> Hapus
              </button>
            </div>`
          }, className: "nowrap"
        }
      ],
      "initComplete": function (settings, json) {
        table_length.set('kontak', json.data.length);
      }
    });
  }

  // inital table
  tblKeaktifan();
  tblKategoriKeanggotaan();
  tblAlamat();
  tblFormal();
  tblEducation();
  tblMembership();
  tblKontak();
  tblKategorial();
  tblWilayah();

  // fungsi simpan keaktifan
  $("#fkeaktifan").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/' + ($("#id_keaktifan").val() == "" ? 'insert_keaktifan' : 'update_keaktifan'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      tblKeaktifan();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#keaktifan').modal('toggle')
    })
  });

  // form
  $("#fwilayah").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/' + ($("#id_wilayah").val() == "" ? 'insert_wilayah' : 'update_wilayah'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      $("#id_wilayah").val()
      $("#wilayah").val()
      $("#status_wilayah").val()
      $("#tanggal").val()
      tblWilayah();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#modal-wilayah').modal('toggle')
    })
  });
  
  // for kategorial
  $("#fkategorial").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/' + ($("#id_kategorial").val() == "" ? 'insert_kategorial' : 'update_kategorial'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      $("#id_kategorial").val()
      $("#tanggal").val()
      tblAlamat();
      tblFormal();
      tblEducation();
      tblKontak();
      tblKategorial();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#kategorial').modal('toggle')
    })
  });

  // for kategori keanggotaan
  $("#fkategorikeanggotaan").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/' + ($("#id_profile_keanggotaan").val() == "" ? 'insert_kategori_keanggotaan' : 'update_kategori_keanggotaan'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      $("#id_profile_keanggotaan").val()
      $("#id_keanggotaan").val()
      $("#tanggal").val()
      cekKategoriKeanggoaan();
      tblKategoriKeanggotaan();
      tblAlamat();
      tblFormal();
      tblEducation();
      tblKontak();
      tblKategorial();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#kategori_keanggotaan').modal('toggle')
    })
  });

  // tambah dan ubah
  $("#fcontact").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/' + ($("#id_contact").val() == "" ? 'insert_contact' : 'update_contact'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      // tblContact(data);
      tblKontak();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#contact').modal('toggle')
    })
  });

  $("#btn-wilayah").click(() => {
    $('#tanggal').val("");
    $('#wilayah').val("").trigger('change');
    $('#status_wilayah').val(1).trigger('change');
  })

  $("#btn-kontak").click(() => {
    $('#status_kontak').val(1).trigger('change');
    $('#tanggal_kontak').val("");
    $('#keterangan_kontak').val("");
    $('#tipe_kontak').val("").trigger('change');
  })

  $("#btn-keaktifan").click(() => {
    $('#id_jabatan').val('').trigger('change');
    $('#tanggal_mulai_keaktifan').val("");
    $('#tanggal_selesai_keaktifan').val("");
    $('#kode_master').val('').trigger('change');
    $('#id_master').val('').trigger('change');
  })
  
  $("#btn-lalamat").click(() => {
    $('#id_contact').val("");
    // $('#id_profile').val("");
    $('#jenis_alamat').val("");
    $('#alamat').val("");
    $('#domisili').val("");
    $('#tanggal_mulai').val("");
    $('#tanggal_selesai').val("");
    $('#status').val("1");
  });

  $("#btn-formal").click(() => {
    $("#peristiwa_formal").change(function () {
      getCodeDataFormal($(this).val())
    })
    $('#id_alamat').val("");
    $('#peristiwa_formal').val("");
    $('#tanggal_data_formal').val("");
    $('#tempat').val("");
    $('#status_peristiwa').hide();
    $('#statusf').val("0");
  });

  $("#peristiwa_formal").change(function () {
    let id_peristiwa = $(this).val()
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/getPeristiwaFormal',
      data: {
        id: id_peristiwa
      },
    }).done((data) => {
      if(data){
        if(data.kategori == 2){
          $("#status_peristiwa").show()
        }else{
          $("#status_peristiwa").hide()
        }
      }
    })
  })

  $("#btn-education").click(() => {
    $('#id_education').val("");
    $('#nama').val("");
    $('#tanggal_lulus').val("");
    $('#lembaga').val("");
    $('#statuse').val("1");
  });

  $("#btn-membership").click(() => {
    $('#id_membership').val("");
    $('#membership_tanggal').val("");
    $('#membership_status').val("1");
    $('#atasan').val('').trigger('change');
    $('#membership_jenis').val('').trigger('change');
  });

  $("#btn-kontak").click(() => {
    $('#id_contact').val("");
    $('#tipe_kontak').val("").trigger('change');
    $('#keterangan').val("");
    $('#tanggal').val();
    $('#statusk').val("1");
  });

  $("#btn-kategori-keanggotaan").click(() => {
    $('#id_profile_keanggotaan').val("");
    $('#id_keanggotaan').val("").trigger('change');
    $('#tanggal').val("");
  });


  $("#main-form").submit(function (ev) {

    ev.preventDefault();
    // validasi datatable
    // if (table_length.get('alamat') < 1) {
    //   Toast.fire({
    //     icon: 'error',
    //     title: 'Alamat belum di isi'
    //   })
    //   return;
    // }

    // if (table_length.get('membership') < 1) {
    //   Toast.fire({
    //     icon: 'error',
    //     title: 'Membership belum di isi'
    //   })
    //   return;
    // }

    // if (table_length.get('formal') < 1) {
    //   Toast.fire({
    //     icon: 'error',
    //     title: 'Formal belum di isi'
    //   })
    //   return;
    // }

    // if (table_length.get('education') < 1) {
    //   Toast.fire({
    //     icon: 'error',
    //     title: 'Education belum di isi'
    //   })
    //   return;
    // }

    const form = new FormData(this);
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/simpan',
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
        // window.location.reload();
        location.replace("<?= base_url() ?>pengaturan/profile");
        // location.reload();
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

  $("#falamat").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/' + ($("#id_alamat").val() == "" ? 'insert_alamat' : 'update_alamat'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })

      $("#id_alamat").val("")
      $("#jenis_alamat").val("")
      $("#jenis_alamat").val("")
      $("#domisili").val("")
      $("#alamat").text("")
      $("#tanggal_mulai").val("")
      $("#tanggal_selesai").val("")

      tblAlamat();
      $('#alamat').modal('hide')
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#alamat').modal('toggle')
    })
  });

  $("#fmembership_active").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/set_active_membersip',
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      tblMembership();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#membership_active_modal').modal('toggle')
    })
  });

  $("#fmembership").submit(function (ev) {
    ev.preventDefault();

    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/' + ($('#id_membership_pengesah').val() == "" ? 'insert_membership' : 'update_membership'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      tblMembership();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#membership_modal').modal('toggle')
    })
  });

  $("#fformal").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/' + ($("#id_formal").val() == "" ? 'insert_formal' : 'update_formal'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })

      tblFormal();
      $('#formal').modal('hide')
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#formal').modal('toggle')
    })
  });

  $("#feducation").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    form.append('id_profile', $('#id').val())
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/' + ($("#id_education").val() == "" ? 'insert_education' : 'update_education'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })

      tblEducation();
      $('#education').modal('hide')
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#education').modal('toggle')
    })
  });

  // hapus
  $('#OkCheck').click(() => {
    let id = $("#idCheck").val()
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/hapusDaftar',
      data: {
        id: id,
        tbl: tabelHapus
      }
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil dihapus'
      })
      tblKategorial();
      tblKategoriKeanggotaan();
      tblWilayah();
      tblAlamat();
      tblFormal();
      tblEducation();
      tblMembership();
      tblKontak();
      tblKeaktifan();
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

  email.attr('readonly', '')
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
      email.removeAttr('readonly');
    }
  });
}

function nikCheck(nik_ele) {
  const nik = $(nik_ele);
  if (nik.val() == '') {
    return;
  }

  nik.attr('readonly', '')
  $.ajax({
    url: '<?= base_url() ?>pengaturan/profile/nikCheck',
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
      nik.removeAttr('readonly');
    }
  });
}

function membershipCheck(membership_ele) {
  const membership = $(membership_ele);
  if (membership.val() == '') {
    return;
  }

  membership.attr('readonly', '')
  $.ajax({
    url: '<?= base_url() ?>pengaturan/profile/membershipCheck',
    data: {
      id_profile: profile_id,
      id_jenis: membership.val()
    },
    type: 'post',
    success: function (data) {
      Toast.fire({
        icon: 'success',
        title: 'Success Jenis Membership Belum Terdaftar'
      })
    },
    error: function (data) {
      Toast.fire({
        icon: 'error',
        title: data.responseJSON.message
      })
      membership.val('').trigger('change');
      membership.focus();
    },
    complete: function () {
      membership.removeAttr('readonly');
    }
  });
}

// Click Hapus
const Hapus = (id, tbl) => {
  $("#idCheck").val(id)
  tabelHapus = tbl
  $("#LabelCheck").text('Form Hapus')
  $("#ContentCheck").text('Apakah anda yakin akan menghapus data ini?')
  $('#ModalCheck').modal('toggle')
}

const ubah_kategorial = (id) => {
  const idp = $("#id").val()
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/getKategorial',
    data: {
      id: id
    }
  }).done((data) => {
    if (data.data) {
      data = data.data[0];
      $("#myModalLabel").text("Ubah Kategorial");
      $('#id_kategorial').val(data.id);
      $('#kategorial').val(data.id_kategorial).trigger('change');
      $('#tanggal').val(data.tanggal);
      $('#statusk').val(data.status);
      $('#kategorial').modal('toggle')
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

const cetak_formal = (id) => {
  const idp = $("#id").val()
  $.LoadingOverlay("show");
  window.open('<?= base_url() ?>pengaturan/profile/cetakFormal/'+id, '_blank')
  $.LoadingOverlay("hide");
}

const status_formal  = (id, status) => {
  const idp = $("#id").val()
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/ubah_status_formal',
    data: {
      id_formal: id,
      idp: idp,
      status: status
    }
  }).done((data) => {
      tblFormal()
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
  }).fail(($xhr) => {
    Toast.fire({
      icon: 'error',
      title: 'Gagal mendapatkan data.'
    })
  }).always(() => {
    $.LoadingOverlay("hide");
  })
}

const status_kategori_keanggotaan  = (id, status) => {
  const idp = $("#id").val()
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/ubah_status_kategori_keanggotaan',
    data: {
      id_profile_keanggotaan: id,
      idp: idp,
      status: status
    }
  }).done((data) => {
      tblKategoriKeanggotaan()
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
  }).fail(($xhr) => {
    Toast.fire({
      icon: 'error',
      title: 'Gagal mendapatkan data.'
    })
  }).always(() => {
    $.LoadingOverlay("hide");
  })
}

const ubah_kategori_keanggotaan  = (id) => {
  const idp = $("#id").val()
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/getKategoriKeanggotaan',
    data: {
      id_profile_keanggotaan: id,
      idp: idp
    }
  }).done((data) => {
    if (data.data) {
      data = data.data[0];
      $("#myModalLabel").text("Ubah Kategori keanggotaan");
      $('#id_profile_keanggotaan').val(data.id);
      $('#id_keanggotaan').val(data.id_kategori_keanggotaan).trigger('change');
      $('#tanggal').val(data.tanggal);
      $('#kategori_keanggotaan').modal('toggle')
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

const ubah_wilayah = (id) => {
  const idp = $("#id").val()
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/getWilayah',
    data: {
      id: id
    }
  }).done((data) => {
    if (data.data) {
      data = data.data[0];
      console.log(data.id_wilayah)
      $("#myModalLabel").text("Ubah Wilayah");
      $('#id_wilayah').val(data.id);
      $('#wilayah').val(data.id_wilayah);
      $('#tanggal').val(data.tanggal);
      $('#status_wilayah').val(data.status);
      $('#modal-wilayah').modal('toggle')
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

const ubah_keaktifan = (id) => {
  const idp = $("#id").val()
  $.LoadingOverlay("show");
  
  $("#id_jabatan").val("")
  $("#kode_master").val("")
  $("#id_master").val("")

  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/getKeaktifan',
    data: {
      id: id
    }
  }).done((data) => {
    if (data.data) {
      data = data.data[0];
      ajax_select({ id: '#btn-keaktifan', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#id_jabatan', '<?= base_url(); ?>pengaturan/profile/ajax_select_list_jabatan', null, '', 'Pilih Jabatan', data.id_jabatan);
      $("#myModalLabel").text("Ubah keaktifan");
      $('#id_keaktifan').val(data.id);
      // $('#id_jabatan').val(data.id_jabatan).trigger('change');
      $('#kode_master').val(data.kode_master).trigger('change');
      let kode_master = $("#kode_master").val()
      ajax_select({ id: '#btn-keaktifan', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#id_master', '<?= base_url(); ?>pengaturan/profile/ajax_select_list_master', {kode_master:data.kode_master}, '', 'Pilih Master', data.id_master);
      $('#id_master').val(data.id_master).trigger('change');

      
      // alert(data.id_master)
      $('#tanggal_mulai_keaktifan').val(data.tanggal_mulai);
      $('#tanggal_selesai_keaktifan').val(data.tanggal_selesai);
      $('#keaktifan').modal('toggle')
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

const ubah_kontak = (id) => {
  const idp = $("#id").val()
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/getContact',
    data: {
      id: id
    }
  }).done((data) => {
    if (data.data) {
      data = data.data[0];
      $("#myModalLabel").text("Ubah Contact");
      $('#id_contact').val(data.id);
      $('#tipe_kontak').val(data.id_tipe_contact).trigger('change');
      $('#keterangan_kontak').val(data.keterangan);
      $('#tanggal_kontak').val(data.tanggal);
      $('#status_kontak').val(data.status);
      $('#contact').modal('toggle')
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

// Click Ubah
const ubah_contact = (id) => {
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/getContact',
    data: {
      id: id
    }
  }).done((data) => {
    if (data.data) {
      data = data.data[0];
      $("#myModalLabel").text("Ubah Contact");
      $('#id_contact').val(data.id);
      $('#no_telepon').val(data.no_telepon);
      $('#instagram').val(data.instagram);
      $('#youtube').val(data.youtube);
      $('#linkedin').val(data.linkedin);
      $('#contact').modal('toggle')
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

const ubah_alamat = (id) => {
  const idp = $("#id").val()
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/getAlamat',
    data: {
      idp: idp,
      id: id
    }
  }).done((data) => {
    if (data.data) {
      data = data.data[0];
      $("#myModalLabel").text("Ubah Alamat");
      $('#id_alamat').val(data.id);
      $('#jenis_alamat').val(data.id_jenis_alamat).trigger('change');
      $('#deskripsi').val(data.alamat);
      $('#domisili').val(data.domisili);
      $('#tanggal_mulai').val(data.tanggal_mulai);
      $('#tanggal_selesai').val(data.tanggal_selesai);
      $('#status').val(data.status);
      $('#alamat').modal('toggle')
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

const ubah_formal = (id) => {
  const idp = $("#id").val()
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/getFormal',
    data: {
      idp: idp,
      id: id
    }
  }).done((data) => {
    if (data.data) {
      data = data.data[0];
      $("#myModalLabel").text("Ubah Formal");
      $('#id_formal').val(data.id);
      $('#peristiwa_formal').val(data.peristiwa_formal);
      $('#tempat').val(data.tempat);
      $('#peristiwa_formal').val(data.dtm_peristiwa_id).trigger('change');
      $('#peristiwa_keterangan').val(data.keterangan);
      $('#tanggal_data_formal').val(data.tanggal_data_formal);
      $('#statusf').val(data.status);
      $('#formal').modal('toggle')
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

const ubah_education = (id) => {
  const idp = $("#id").val()
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/getEducation',
    data: {
      idp: idp,
      id: id
    }
  }).done((data) => {
    if (data.data) {
      data = data.data[0];
      $("#myModalLabel").text("Ubah Education");
      $('#id_education').val(data.id);
      $('#nama').val(data.id_jenis_gelar).trigger('change');
      $('#tanggal_lulus').val(data.tanggal_lulus);
      $('#lembaga').val(data.lembaga);
      $('#statuse').val(data.status);
      $('#education').modal('toggle')
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

const ubah_membership = (datas) => {
  const data = datas.dataset;
  $("#membership_modal").modal('toggle');
  $('#id_membership_pengesah').val(data.id);
  $('#membership_tanggal').val(data.tanggal);
  $('#membership_status').val(data.status);
  membership_check = false;
  $('#atasan').val(data.id_approver).trigger('change');
  $('#membership_jenis').val(data.id_jenis).trigger('change');
}

const minta_membership = (datas) => {
  console.log(datas)
  const data = datas.dataset;
   $.ajax({
      method: 'post',
      url: '<?= base_url() ?>pengaturan/profile/set_active_persetujuan_membersip',  
      data: {
        id: data.id
      },
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Minta Persetujuan berhasil dikirim'
      })
      tblMembership()
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Minta Persetujuan gagal dikirim'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
    })
}

const set_active_membership = (datas) => {
  const data = datas.dataset;
  $("#membership_active_modal").modal('toggle');
  $("#id_membership_active").val(data.id);
  $("#st_membership_active").val(data.st);
  $("#membership_active_name").val(data.title);
}

function photo_preview(datas) {
  const data = datas.dataset;
  const img = $("#foto-preview");
  $("#fot_preview").modal('toggle');
  img.attr('src', `<?= base_url() ?>files/profile/${data.src}`);
  img.attr('alt', data.alt);

}

function konfirmasiEmail(datas) {
  const data = datas.dataset;
  $(datas).attr('disabled', '');
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/sendEmailConfirm',
    data: {
      id: data.id,
      email: data.email
    }
  }).done((data) => {
    Toast.fire({
      icon: 'success',
      title: 'Email konfirmasi berhasil dikirim'
    })
    $(datas).text(`Email konfirmasi terkirim ke: ${datas.dataset.email}`);
  }).fail(($xhr) => {
    Toast.fire({
      icon: 'error',
      title: 'Gagal mengirim email'
    })
    $(datas).removeAttr('disabled', '');
  }).always(() => {
    $.LoadingOverlay("hide");
  })
}

// tabel membership
function tblMembership() {
  const idp = $("#id").val()
  const table_html = $('#tblmembership  ');
  table_html.dataTable().fnDestroy()
  table_html.DataTable({
    "ajax": {
      "url": "<?= base_url()?>pengaturan/profile/getMembership/",
      "data": { idp: idp },
      "type": 'POST'
    },
    "pageLength": 10,
    "processing": true,
    "serverSide": true,
    "responsive": true,
    "lengthChange": true,
    "autoWidth": false,
    "searching": false,
    "paging": false,
    "info": false,
    "columns": [
      { "data": "nama" },
      // { "data": "nama_atasan" },
      { "data": "tanggal", render(data, type, full, meta) {
        return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
      }, className: 'nowrap'
     },
     { "data": "tanggal_approval", render(data, type, full, meta) {
            if(data == null){
              return "";              
            }else{
              return full.nama_atasan + " (" +moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY')+")";              
            }
          }, className: 'nowrap'
        },
      { "data": "status_str", className: 'nowrap' },
      {
          "data": "id", render(data, type, full, meta) {
            // <button type="button"
            // class="btn btn-info btn-xs"
            // data-id_profile="${idp}"
            // data-id="${data}"
            // data-title="${full.nama}"
            // data-st="3"
            // onclick="set_active_membership(this)">
            //   <i class="fa fa-key"></i> Non-Aktifkan
            // </button>
            const btn_nonactive = full.status == 1 ? `
            `: '';
            const btn_active = full.status == 3 ? `

            `: '';
            const btn_delete = full.status == 2 ? `
              
            ` : '';
            const btn_ubah = jenis_pengesah <= 2 ?
              full.status == 0 ? `
               <a
              class="btn btn-primary btn-sm ml-1"
              data-id_profile="${full.id_profile}"
              data-id_membership="${full.id}"
              data-st="1"
              data-id_approver="${full.id_approver}"
              id="btn-membership"
              onclick="set_st_membership(this)"
              ><i class="fas fa-check"></i></a>`+
              `<a
              class="btn btn-danger btn-sm ml-1"
              data-id_profile="${full.id_profile}"
              data-id_membership="${full.id}"
              data-st="2"
              id="btn-membership"
              onclick="set_st_membership(this)"
              ><i class="fas fa-times"></i></a>
              ` : ''  : '' 
            const btn_persetujuan = full.status == 9 ? `
            <button type="button" class="btn btn-warning btn-xs"
              data-id="${full.id}"
              data-id_approver="${full.id_approver}"
              data-id_jenis="${full.id_jenis}"
              data-status="${full.status}"
              data-tanggal="${full.tanggal}"
              onclick="ubah_membership(this)">
                <i class="fa fa-edit"></i> Ubah
              </button>
              <button type="button" class="btn btn-primary btn-xs"
              data-id="${full.id}"
              data-id_approver="${full.id_approver}"
              data-id_jenis="${full.id_jenis}"
              data-status="${full.status}"
              data-tanggal="${full.tanggal}"
              onclick="minta_membership(this)">
                <i class="fa fa-edit"></i> Meminta Persetujuan
              </button>
            ` : '';
            if(full.id_jenis == 2){
              if(jenis_pengesah == 1){
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_ubah}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
              }else{
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
              }
            }else if(full.id_jenis == 3){
              if(jenis_pengesah > 0 && jenis_pengesah <= 2){
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_ubah}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
              }else{
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
              }
            }else if(full.id_jenis > 3 && full.id_jenis <= 5){
              if(jenis_pengesah > 0 && jenis_pengesah <= 2){
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_ubah}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
              }else{
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
              }
            }else{
                return `<div class="pull-right">
                  ${btn_nonactive}
                  ${btn_active}
                  ${btn_persetujuan}
                  ${btn_delete}
                </div>`
            }
          }, className: "nowrap"
        }
    ],
    "initComplete": function (settings, json) {
      table_length.set('membership', json.data.length);
    }
  });

  $.ajax({
    method: 'post',
    url: '<?= base_url()?>pengaturan/profile/getMembership/',
    data: { idp: idp },
  }).done((data) => {
    if(data.data.length == 0){
      $("#btn-membership").show()
    }else{
      let cek_status = 0
      let cek_status_proses = 0
      data.data.forEach(e => {
        if(e.status_str == "Disetujui"){
          cek_status = cek_status + 1
        }else if(data.data[0].status_str == "Tidak Aktif"){
          cek_status = cek_status + 1
        }else if(data.data[0].status_str == "Ditolak"){
          cek_status = cek_status + 1
        }
        if(cek_status > 0){
          $("#btn-membership").show()
        }else{
          $("#btn-membership").hide()
        }

        if(e.status_str == "Belum di minta persetujuan"){
          cek_status_proses = cek_status_proses + 1
        }

        if(e.status_str == "Persetujuan dimintakan"){
          cek_status_proses = cek_status_proses + 1
        }
      });

      if(cek_status_proses == 1){
        $("#btn-membership").hide()                      
      }

    }
  })
}
const set_st_membership = (datas) => {
  $.LoadingOverlay("show");
  const data = datas.dataset;
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/set_st_membership',
    data: {
      id_profile : data.id_profile,
      id_membership : data.id_membership,
      status : data.st,
      id_approver : data.id_approver,
    },
    success: function (data) {
      Toast.fire({
        icon: 'success',
        title: 'Status Membership berhasil diubah'
      })
      tblMembership()
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

// table formal
function tblFormal() {
  const idp = $("#id").val()
  const table_html = $('#tblFormal');
  table_html.dataTable().fnDestroy()
  table_html.DataTable({
    "ajax": {
      "url": "<?= base_url()?>pengaturan/profile/getFormal/",
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
      {
        "data": "id", render(data, type, full, meta) {
          if(id_membership == 1){
            if(full.status == 0){
              return `<div class="pull-right">
                <button type="button" class="btn btn-success btn-xs" onclick="status_formal(${data},1)">
                  <i class="fa fa-check"></i> Disetujui
                </button>
                <button type="button" class="btn btn-warning btn-xs" onclick="status_formal(${data},2)">
                  <i class="fa fa-times"></i> Ditolak
                </button>
                <button type="button" class="btn btn-primary btn-xs" onclick="cetak_formal(${data})">
                  <i class="fa fa-print"></i> Cetak
                </button>
              </div>`
            }else if(full.status == 3 || full.status == 2){
              return ` <div class="pull-right">
                <button type="button" class="btn btn-primary btn-xs" onclick="cetak_formal(${data})">
                  <i class="fa fa-print"></i> Cetak
                </button>
              </div>`
            }else{
              return `
                <div class="pull-right">
                  <button type="button" class="btn btn-primary btn-xs" onclick="cetak_formal(${data})">
                    <i class="fa fa-print"></i> Cetak
                  </button>
                </div>`
            }
          }else{
            if(full.status == 0){
             return `<div class="pull-right">
                <button type="button" class="btn btn-primary btn-xs" onclick="cetak_formal(${data})">
                  <i class="fa fa-print"></i> Cetak
                </button>
              </div>`
            }else if(full.status == 3 || full.status == 4){
              return `<div class="pull-right">
                  <button type="button" class="btn btn-primary btn-xs" onclick="cetak_formal(${data})">
                    <i class="fa fa-print"></i> Cetak
                  </button>
                </div>`
            }else{
              return `<div class="pull-right">
                  <button type="button" class="btn btn-primary btn-xs" onclick="cetak_formal(${data})">
                    <i class="fa fa-print"></i> Cetak
                  </button>
                </div>`
            }
          }
        }, className: "nowrap"
      },
      { "data": "kode", className: 'nowrap' },
      { "data": "peristiwa" },
      { "data": "institusi" },
      { "data": "id", render(data, type, full, meta) {
          return full.nama_depan+" "+full.nama_belakang;
        }, className: 'nowrap'
      },
      { "data": "keterangan" },
      { "data": "tanggal_data_formal", render(data, type, full, meta) {
          return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
        }, className: 'nowrap'
      },
      { "data": "tempat" },
      {
          "data": "photo", render(data, type, full, meta) {
              if(data == null || data == ''){
                  return `Belum ada photo`
              }else{
                  return `<a style='cursor: pointer;' onclick='LihatPhoto(${full.id})'><i class='fa fa-eye'></i> Lihat</a>`                                                        
              }
          }, className: "nowrap"
      },
      { "data": "status_str" },
      { "data": "keterangan_2" },
      { "data": "rencana_tanggal", render(data, type, full, meta) {
        if(data == null || data == '' || data == '0000-00-00'){
          return ''
        }else{
          return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
        }

        }, className: 'nowrap'
      },
      { "data": "upload_hardcopy" },
      { "data": "status2_str" }
    ],
    "initComplete": function (settings, json) {
      table_length.set('formal', json.data.length);
    }
  });
}

cekKategoriKeanggoaan()

function cekKategoriKeanggoaan(){
  const idp = $("#id").val()
  $.ajax({  
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/getKategoriKeanggotaan',
    data: {
      idp: idp
    }
  }).done((data) => {
    if(data.data[0] != null){
      if(data.data[0].status == 1){
        $("#btn-kategori-keanggotaan").hide()
      }else{
        $("#btn-kategori-keanggotaan").show() 
      }
    }
  })
}

// table kategori keanggotaan
function tblKategoriKeanggotaan() {
  const idp = $("#id").val()
  const table_html = $('#tblKategoriKeanggotaan');
  table_html.dataTable().fnDestroy()
  table_html.DataTable({
    "ajax": {
      "url": "<?= base_url()?>pengaturan/profile/getKategoriKeanggotaan/",
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
      { "data": "nama", className: 'nowrap' },
      { "data": "keterangan_keanggotaan", className: 'nowrap' },
      { "data": "tanggal", render(data, type, full, meta) {
          return moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY');
        }, className: 'nowrap'
      },
      { "data": "tanggal_approval", render(data, type, full, meta) {
          if(data == null){
            return "";              
          }else{
            return full.nama_atasan + " (" +moment(data, 'YYYY-MM-DD H:mm:ss').format('DD-MM-YYYY')+")";              
          }
        }, className: 'nowrap'
      },
      { "data": "status_str", className: 'nowrap' },
      {
        "data": "id", render(data, type, full, meta) {
          if(id_membership == 1 || id_membership == 2 || id_membership == 3){
            if(full.status == 1){
              return `<div class="pull-right">
                <button type="button" class="btn btn-success btn-xs" onclick="status_kategori_keanggotaan(${data},2)">
                  <i class="fa fa-check"></i> Terima
                </button>
                <button type="button" class="btn btn-warning btn-xs" onclick="status_kategori_keanggotaan(${data},3)">
                  <i class="fa fa-times"></i> Tolak
                </button>
                <button type="button" class="btn btn-primary btn-xs" onclick="ubah_kategori_keanggotaan(${data})">
                  <i class="fa fa-edit"></i> Ubah
                </button>
                <button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data},'profile_keanggotaan')">
                  <i class="fa fa-trash"></i> Hapus
                </button>
              </div>`
            }else{
              return ``
            }
          }else{
             return `<div class="pull-right">
                <button type="button" class="btn btn-primary btn-xs" onclick="ubah_kategori_keanggotaan(${data})">
                  <i class="fa fa-edit"></i> Ubah
                </button>
                <button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data},'profile_keanggotaan')">
                  <i class="fa fa-trash"></i> Hapus
                </button>
              </div>`
          }
        }, className: "nowrap"
      }
    ],
    "initComplete": function (settings, json) {
      table_length.set('formal', json.data.length);
    }
  });
}

// lihat photo
const LihatPhoto = (id) => {
  const idp = $("#id").val()
  $.LoadingOverlay("show");
  $.ajax({
    method: 'post',
    url: '<?= base_url() ?>pengaturan/profile/getFormal',
    data: {
      idp: idp,
      id: id
    }
  }).done((data) => {

    if (data.data) {
      data = data.data;
      $("#myModalLabelPhoto").text("Photo: "+data[0].photo);
      $('#detail-photo').attr('src', '<?= base_url() ?>files/data-formal/photo/'+data[0].photo);
      $('#myModalPhoto').modal('toggle')
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