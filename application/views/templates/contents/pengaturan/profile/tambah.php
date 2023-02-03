<div class="card">
  <div class="card-header">
    <div class="d-flex justify-content-between w-100">
      <h3 class="card-title">Data <?= $title ?></h3>
      <?php if($profile['user_email'] != ""):?>
       <a style="float: right;" type="button" class="btn btn-primary btn-sm" href="<?=base_url()?>activity/logging?menu=<?=$this->modul?>&cari=id_<?=$getID?>"><i class="fa fa-search"></i> &nbsp; Log Aktifitas</a>
      <?php endif;?>
      <input type="hidden" id="id_membership" value="<?=$this->member?>">
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <form id="main-form" enctype="multipart/form-data">
      <div class="row">
        <input type="hidden" name="id" id="id" value="<?= $getID; ?>">
        <input type="hidden" name="jenis_pengesah" id="jenis_pengesah" value="<?= $this->member; ?>">
        <input type="hidden" name="is-ubah" id="is-ubah" value="<?= $isUbah ? 1 : 0; ?>">
        <div class="col-md-6">
          <div class="form-group">
            <label for="id_partner"><?=$partner_alias?></label>
            <?php if ($stpart == 'ada') { ?>
              <input type="hidden" name="id_partner" value="<?= $idpart ?>">
              <select class="form-control" id="id_partner" name="id_partner" disabled required>
                <option value="">Pilih <?=$partner_alias?></option>
                <?php foreach ($partner as $p) : ?>
                  <option value="<?= $p['id'] ?>" <?= $profile['id_partner'] == $p['id'] ? 'selected' : '' ?>><?= $p['text'] ?></option>
                <?php endforeach; ?>
              </select>
            <?php } else { ?>
              <select class="form-control" id="id_partner" name="id_partner" required>
                <option value="">Pilih <?=$partner_alias?></option>
                <?php foreach ($partner as $p) : ?>
                  <option value="<?= $p['id'] ?>" <?= $profile['id_partner'] == $p['id'] ? 'selected' : '' ?>><?= $p['text'] ?></option>
                <?php endforeach; ?>
              </select>
            <?php } ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="id_posisi">Posisi *</label>
              <select class="form-control" id="id_posisi" name="id_posisi">
                <option value="">Pilih Posisi</option>
                <?php foreach ($posisi as $q) : ?>
                  <option value="<?= $q['id'] ?>" <?= $profile['id_posisi'] == $q['id'] ? 'selected' : '' ?>><?= $q['nama'] ?></option>
                <?php endforeach; ?>
              </select>
          </div>
        </div>
         <div class="col-md-6">
          <div class="form-group">
            <label for="nama_belakang">Nama Belakang *</label>
            <input type="text" class="form-control" id="nama_belakang" name="nama_belakang" placeholder="Nama Belakang" value="<?= $profile['nama_belakang'] ?>" required/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="nama_depan">Nama Depan</label>
            <input type="text" class="form-control" id="nama_depan" name="nama_depan" placeholder="Nama Depan" value="<?= $profile['nama_depan'] ?>" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="email">Email</label>
            <?php
            if ($isUbah) {
              if ($profile['user_email_status'] == 1) {
                echo '<span class="font-weight-bold text-success"><i class="far fa-check-circle"></i> Verified</span>';
              } else {
                echo '<span class="font-weight-bold text-danger"><i class="far fa-times-circle"></i> Not Verified</span>
              <button class="btn btn-info btn-xs" type="button" onclick="konfirmasiEmail(this)" data-id="' . $profile['user_id'] . '" data-email="' . $profile['user_email'] . '">Konfirmasi Email</button>';
              }
            }
            ?>
            <input type="number" style="display: none;" id="is_ubah" name="is_ubah" required value="<?= $isUbah ? 1 : 0 ?>" />
            <input type="text" style="display: none;" id="user_id" name="user_id" value="<?= $profile['user_id'] ?>" />
            <input type="text" style="display: none;" id="current_email" name="current_email" value="<?= $profile['user_email'] ?>" />
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= $profile['user_email'] ?>" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <table width="100%">
                <tr>
                    <td><label for="id_level">Nama Level</label></td>
                    <td style="float: right;">
                        <span style="font-size: 12px; text-align:right; ">(Default: Reader)</span>
                    </td>
                </tr>
            </table>
            <select class="form-control" id="id_level" name="id_level">
              <option value="">Pilih Nama Level</option>
              <?php foreach ($level as $p) :
                $selected = $profile['lev_id'] == $p['lev_id'] ? 'selected' : '';
                $selected = $profile['lev_id'] == null ? ($p['lev_nama'] == 'Reader' ?  'selected' : '') : $selected;
              ?>
                <option value="<?= $p['lev_id'] ?>" <?= $selected ?>><?= $p['lev_nama'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
         <div class="col-md-4">
          <div class="form-group">
            <table width="100%">
              <tr>
                <td>
                  <label for="nama_depan">
                    Tanggal Registrasi
                  </label>
                </td>
                <td style="float: right;">
                  <span style="font-size: 12px; text-align:right; ">(Default: Hari ini)</span>
                </td>
              </tr>
            </table>
            <input type="date" class="form-control" id="tanggal_registrasi" name="tanggal_registrasi" placeholder="Tanggal Registrasi" value="<?php if($profile['tanggal_registrasi'] == null) echo date('Y-m-d'); else echo $profile['tanggal_registrasi'];?>" />
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label for="id_level">Flag</label>
            <select class="form-control" id="flag" name="flag">
              <option value="">Pilih Flag</option>
              <option value="1" <?php if($profile['flag'] == 1) echo 'selected'; ?>>Aktif</option>
              <option value="2" <?php if($profile['flag'] == 2) echo 'selected'; ?>>Pasif</option>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <table width="100%">
              <tr>
                <td><label for="nik">NIK</label></td>
                <td style="float: right;">
                  <span style="font-size: 12px; text-align:right; ">(Code Otomatis)</span>
                </td>
              </tr>
            </table>
            <input type="text" class="form-control" id="nik" name="nik" placeholder="NIK" value="<?= $profile['nik'] ?>" readonly />
            <input type="hidden" class="form-control" id="isi_kategori" name="isi_kategori" value="<?= $profile['id_keanggotaan'] ?>" readonly />
          </div>
        </div>

        <!-- <div class="col-md-2">
          <div class="form-group">
            <label for="id_client"><?=$kategori_keanggotaan_alias?></label>
            <select class="form-control" id="id_kategori" name="id_kategori" style="width: 100%;"></select>
          </div>
        </div> -->
        
       
        <div class="col-md-6">
          <div class="form-group">
            <table width="100%">
              <tr>
                <td><label for="jk">Jenis Kelamin</label></td>
                <td style="float: right;">
                  <span style="font-size: 12px; text-align:right; ">(Default: Laki-laki)</span>
                </td>
              </tr>
            </table>
            <select class="form-control" id="jk" name="jk">
              <option value="Laki-Laki" <?= $profile['jenis_kelamin'] == 1 ? 'selected' : '' ?>>Laki-Laki</option>
              <option value="Perempuan" <?= $profile['jenis_kelamin'] == 2 ? 'selected' : '' ?>>Perempuan</option>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="photo">Photo <?= $profile['photo'] != null ? "
            <a href=\"#\"
            data-src=\"{$profile['photo']}\"
            data-alt=\"{$profile['nama_depan']}\"
            onclick=\"photo_preview(this)\">
            Lihat </a>" : '' ?></label>
            <input type="file" class="form-control" id="photo" name="photo" placeholder="Photo" ?>
          </div>
        </div>

        <!-- <div class="col-md-6">
          <div class="form-group">
            <label for="tgl-verifikasi">Tanggal Verifikasi</label>
            <input type="date" class="form-control" id="tgl-verifikasi" name="tgl-verifikasi" placeholder="Tanggal Verifikasi" required value="<?= $profile['tanggal_anggota'] ?>" />
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="approved">Status Verifikasi</label>
            <select class="form-control" id="approved" name="approved" required>
              <option value="">Pilih Status Verifikasi</option>
              <option value="Approved" <?= $profile['status_verifikasi'] == 1 ? 'selected' : '' ?>>Approved</option>
              <option value="Rejected" <?= $profile['status_verifikasi'] == 2 ? 'selected' : '' ?>>Rejected</option>
            </select>
          </div>
        </div> -->
        

        


        

        <div class="col-md-4">
          <div class="form-group">
            <label for="nama_depan">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="Tanggal Lahir" value="<?= $profile['tanggal_lahir'] ?>" />
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group">
            <label for="nama_depan">Umur</label>
            <input type="text" class="form-control" id="umur" name="umur" placeholder="Umur" />
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="nama_depan">Tanggal Meninggal</label>
            <input type="date" class="form-control" id="tanggal_meninggal" name="tanggal_meninggal" placeholder="Tanggal Meninggal" value="<?= $profile['tanggal_meninggal'] ?>" />
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group">
            <label for="nama_depan">Umur(Terakhir)</label>
            <input type="text" class="form-control" id="umur_terakhir" name="umur_terakhir" placeholder="Umur Terakhir" />
          </div>
        </div>

        <div class="col-md-12">
          <hr>
        </div>

        <div class="col-md-6">
          <!-- alamat -->
          <div class="d-flex justify-content-between align-items-center">
            <label for="btn-alamat">Alamat</label>
            <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#alamat" id="btn-alamat"><i class="fa fa-plus"></i> Tambah</button>
          </div>

          <table id="tblalamat" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Jenis Alamat</th>
                <th>Domisili</th>
                <th>Deskripsi</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="col-md-6">
          <!-- pengesah -->
          <div class="d-flex justify-content-between align-items-center">
            <label for="btn-membership"><?=$membership_alias?></label>
            <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#membership_modal" id="btn-membership"><i class="fa fa-plus"></i> Tambah</button>
          </div>
          <table id="tblmembership" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Jenis <?=$membership_alias?></th>
                <th>Tanggal</th>
                <th>Approval</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>

        </div>

        <div class="col-md-12">
          <!-- peristiwa formal -->
          <br>
          <div class="d-flex justify-content-between align-items-center">
            <label for="btn-formal"><?=$data_formal_alias?></label>
            <a class="btn btn-info btn-xs" data-toggle="modal" data-target="#formal" id="btn-formal"><i class="fa fa-plus"></i> Tambah</a>
          </div>
          <table id="tblFormal" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th rowspan="2" style="text-align: center;">Aksi</th>
                <th rowspan="2" style="text-align: center;">Nomer Peristiwa</th>
                <th rowspan="2" style="text-align: center;">Peristiwa</th>
                <th rowspan="2" style="text-align: center;">Nama Institusi</th>
                <th rowspan="2" style="text-align: center;">Nama Pengesah</th>
                <th colspan="5" style="text-align: center;">Perencanaan</th>
                <th colspan="4" style="text-align: center;">Realisasi</th>
              </tr>
              <tr>
                <th>Keterangan</th>
                <th>Tanggal Rencana</th>
                <th>Tempat</th>
                <th>Photo</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Tanggal Event</th>
                <th>Upload Hardcopy</th>
                <th>Status</th>
              </tr>
            </thead>
          </table>
        </div>

        <div class="col-md-12">
          <!-- peristiwa formal -->
          <br>
          <div class="d-flex justify-content-between align-items-center">
            <label for="btn-formal">Keaktifan</label>
            <a class="btn btn-info btn-xs" data-toggle="modal" data-target="#keaktifan" id="btn-keaktifan"><i class="fa fa-plus"></i> Tambah</a>
          </div>
          <table id="tblKeaktifan" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th><?=$jabatan_alias?></th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th><?=$pilihan_keaktifan_alias?></th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Pilihan</th>
              </tr>
            </thead>
          </table>
        </div>

        <div class="col-md-6">
          <!-- keanggotaan -->
          <br>
          <div class="d-flex justify-content-between align-items-center">
            <label for="keanggotaan"><?=$kategori_keanggotaan_alias?></label>
            <?php if($this->member == ""):?>
              <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#kategori_keanggotaan" id="btn-kategori-keanggotaan"><i class="fa fa-plus"></i> Tambah</button>
            <?php endif;?>
          </div>
          <table id="tblKategoriKeanggotaan" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Keterangan</th>
                <th>Tanggal Mulai</th>
                <th>Approval</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>

          <!-- kategorial -->
          <br>
          <div class="d-flex justify-content-between align-items-center">
            <label for="kategorial"><?=$kategorial_alias?></label>
            <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#kategorial" id="btn-kategorial"><i class="fa fa-plus"></i> Tambah</button>
          </div>
          <table id="tblKategorial" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Tanggal Mulai</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>

          <!-- pendidikan -->
          <br>
          <div class="d-flex justify-content-between align-items-center">
            <label for="btn-education">Education</label>
            <a class="btn btn-info btn-xs" data-toggle="modal" data-target="#education" id="btn-education"><i class="fa fa-plus"></i> Tambah</a>
          </div>
          <table id="tblEducation" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Gelar</th>
                <th>Tanggal Lulus</th>
                <th>Lembaga</th>
                <!-- <th>Status</th> -->
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>


        <div class="col-md-6">
          <br>
          <!-- kontak -->
          <div class="d-flex justify-content-between align-items-center">
            <label for="kontak">Kontak</label>
            <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#contact" id="btn-kontak"><i class="fa fa-plus"></i> Tambah</button>
          </div>
          <table id="tblKontak" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Tipe Kontak</th>
                <th>Keterangan</th>
                <th>Tanggal Mulai</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>

          <!-- wilayah -->
          <br>
          <div class="d-flex justify-content-between align-items-center">
            <label for="wilayah"><?=$wilayah_alias?></label>
            <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-wilayah" id="btn-wilayah"><i class="fa fa-plus"></i> Tambah</button>
          </div>
          <table id="tblWilayah" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Tanggal Mulai</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>

      </div>
    </form>
  </div>
  <!-- /.card-body -->
  <div class="card-footer text-right">
    <button type="submit" form="main-form" class="btn btn-primary">
      Simpan
    </button>
    <a class="btn btn-danger" href="<?= base_url() ?>pengaturan/profile">Kembali</a>
  </div>
</div>

<!-- wilayah -->
<div class="modal fade" id="modal-wilayah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <form id="fwilayah" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Form <?=$wilayah_alias?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_wilayah" id="id_wilayah">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="kategorial"><?=$wilayah_alias?></label>
                <select class="form-control" name="wilayah" id="wilayah" style="width: 100%;" required>
                  <option value="">Pilih <?=$wilayah_alias?></option>
                  <?php foreach ($wilayah as $q) : ?>
                    <option value="<?= $q['id'] ?>"><?= $q['text'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="kategorial">Tanggal Mulai</label>
                <input type="date" value="<?= date('Y-m-d') ?>" name="tanggal" id="tanggal" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="statusk">Status</label>
                <select class="form-control" id="status_wilayah" name="status_wilayah" required>
                  <option value="">Pilih Status</option>
                  <option value="1" selected="">Aktif</option>
                  <option value="2">Tidak Aktif</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Batal
          </button>
        </div>
      </div><!-- /.modal-content -->
    </form>
  </div>
</div>

<!-- kategorial -->
<div class="modal fade" id="kategorial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <form id="fkategorial" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Form <?=$kategorial_alias?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_kategorial" id="id_kategorial">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="kategorial"><?=$kategorial_alias?></label>
                <select class="form-control" name="kategorial" id="kategorial" style="width: 100%;" required>
                  <option value="">Pilih <?=$kategorial_alias?></option>
                  <?php foreach ($kategorial as $q) : ?>
                    <option value="<?= $q['id'] ?>"><?= $q['text'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="kategorial">Tanggal Mulai</label>
                <input type="date" value="<?= date('Y-m-d') ?>" name="tanggal" id="tanggal" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Batal
          </button>
        </div>
      </div><!-- /.modal-content -->
    </form>
  </div>
</div>

<!-- kategori keanggotaan -->
<div class="modal fade" id="kategori_keanggotaan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <form id="fkategorikeanggotaan" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Form <?=$kategori_keanggotaan_alias?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_profile_keanggotaan" id="id_profile_keanggotaan">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="keanggotaan"><?=$kategori_keanggotaan_alias?></label>
                <select class="form-control" name="id_keanggotaan" id="id_keanggotaan" style="width: 100%;" required>
                  <option value="">Pilih <?=$kategori_keanggotaan_alias?></option>
                  <?php foreach ($kategori_keanggotaan as $q) : ?>
                    <option value="<?= $q['id'] ?>"><?= $q['keterangan'] ?> (<?= $q['text'] ?>)</option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="keanggotaan">Tanggal Mulai</label>
                <input type="date" value="<?= date('Y-m-d') ?>" name="tanggal" id="tanggal" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Batal
          </button>
        </div>
      </div><!-- /.modal-content -->
    </form>
  </div>
</div>

<!-- contact -->
<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <form id="fcontact" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Kontak</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_contact" id="id_contact">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="tipe_kontak">Tipe Kontak</label>
                <select class="form-control" name="tipe_kontak" id="tipe_kontak" style="width: 100%;" required>
                  <option value="">Pilih Tipe Kontak</option>
                  <?php foreach ($tipekontak as $kontak) : ?>
                    <option value="<?= $kontak['id'] ?>"><?= $kontak['text'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" class="form-control" id="keterangan_kontak" name="keterangan_kontak" placeholder="Keterangan" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="tipe_kontak">Tanggal Mulai</label>
                <input type="date" value="<?= date('Y-m-d') ?>" name="tanggal" id="tanggal_kontak" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="statusk">Status</label>
                <select class="form-control" id="status_kontak" name="statusk" required>
                  <option value="">Pilih Status</option>
                  <option value="1">Aktif</option>
                  <option value="2">Tidak Aktif</option>
                </select>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Batal
          </button>
        </div>
      </div><!-- /.modal-content -->
    </form>
  </div>
</div>


<!-- alamat -->
<div class="modal fade" id="alamat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <form id="falamat" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Alamat</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_alamat" id="id_alamat">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="jenis_alamat">Jenis Alamat</label>
                <select class="form-control" name="jenis_alamat" id="jenis_alamat" style="width: 100%;" required>
                  <option value="">Pilih Jenis Alamat</option>
                  <?php foreach ($jenisalamat as $alamat) : ?>
                    <option value="<?= $alamat['id'] ?>"><?= $alamat['text'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="domisili">Domisili</label>
                <input type="text" class="form-control" id="domisili" name="domisili" placeholder="Domisili" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="alamat">Deskripsi</label>
                <textarea name="alamat" id="deskripsi" rows="3" class="form-control" placeholder="Deskripsi" required></textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" placeholder="Tanggal Mulai" required />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" placeholder="Tanggal Selesai" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status">
                  <option value="1" selected="">Aktif</option>
                  <option value="0">Tidak Aktif</option>
                </select>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Batal
          </button>
        </div>
      </div><!-- /.modal-content -->
    </form>
  </div>
</div>


<!-- formal -->
<div class="modal fade" id="formal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="fformal" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel"><?=$data_formal_alias?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_formal" id="id_formal">
          <div class="row">
            <div class="col-md-6">
              <label for="peristiwa_formal">Peristiwa</label>
              <select class="form-control" style="width: 100%;" id="peristiwa_formal" name="peristiwa_formal" required>
                <option value="">Pilih Peristiwa</option>
                <?php foreach ($peristiwa as $p) : ?>
                  <option value="<?= $p['id'] ?>"><?= $p['text'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="tanggal_data_formal">Nomer Peristiwa</label>
                <input type="hidden" class="form-control" id="kode_kategori" name="kode_kategori" readonly />
                <input type="text" class="form-control" id="kode_data_formal" name="kode_data_formal" placeholder="Kode" required readonly />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tanggal_data_formal">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_calon_1" name="nama_calon_1" placeholder="Nama" required value="<?php if($get_calon_suami != null) echo $get_calon_suami['nama_depan']." ".$get_calon_suami['nama_belakang'];?>" readonly/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="tanggal_data_formal">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir_calon_1" name="tanggal_lahir_calon_1" placeholder="Tanggal Lahir" required value="<?php if($get_calon_suami != null) echo $get_calon_suami['tanggal_lahir'];?>" readonly />
              </div>
            </div>
          </div>

          <div class="row" id="status_peristiwa">
            <div class="col-md-12" style="text-align: center;">
              <label for="tanggal_data_formal" style="text-align: center;">Dengan</label>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="tanggal_data_formal">Nama Lengkap</label>
                <input type="hidden" class="form-control" id="id_profile_pasangan" name="id_profile_pasangan" value="<?php if($get_calon_istri != null) echo $get_calon_istri['id'];?>" readonly />
                <input type="text" class="form-control" id="nama_calon_2" name="nama_calon_2" placeholder="Nama" value="<?php if($get_calon_istri != null) echo $get_calon_istri['nama_depan']." ".$get_calon_istri['nama_belakang'];?>" readonly />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="tanggal_data_formal">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir_calon_2" name="tanggal_lahir_calon_2" placeholder="Tanggal Lahir"  value="<?php if($get_calon_istri != null) echo $get_calon_istri['tanggal_lahir'];?>" readonly />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label for="peristiwa_formal">Nama Institusi</label>
              <select class="form-control" style="width: 100%;" id="id_institusi" name="id_institusi" required>
                <option value="">Pilih Institusi</option>
                <?php foreach ($institusi as $p) : ?>
                  <option value="<?= $p['id'] ?>"><?= $p['nama'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="peristiwa_formal">Nama Pengesah</label>
              <select class="form-control" style="width: 100%;" id="idjabatan" name="idjabatan">
                <option value="">Pilih Jabatan</option>
                <?php foreach ($isiJabatan as $p) : ?>
                  <option value="<?= $p['id'] ?>"><?= $p['nama_depan']. " " .$p['nama_belakang']  ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
      
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="peristiwa_keterangan">Keterangan</label>
                <textarea class="form-control" id="peristiwa_keterangan" name="peristiwa_keterangan" placeholder="Peristiwa Keterangan" required rows="3"></textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tanggal_data_formal">Rencana Tanggal</label>
                <input type="date" class="form-control" id="rencana_tanggal" name="rencana_tanggal" placeholder="Tanggal" required="" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="tempat">Tempat</label>
                <input type="text" class="form-control" id="tempat" name="tempat" placeholder="Tempat" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo" placeholder="photo">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="peristiwa_keterangan">Keterangan 2</label>
                <textarea class="form-control" id="keterangan_2" name="keterangan_2" placeholder="Keterangan 2" rows="3"></textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tanggal_data_formal">Tanggal Event</label>
                <input type="date" class="form-control" id="tanggal_data_formal" name="tanggal_data_formal" placeholder="Tanggal" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="photo">Upload Hardcopy</label>
                <input type="file" class="form-control" id="upload_hardcopy" name="upload_hardcopy" placeholder="upload_hardcopy">
              </div>
            </div>
          </div>

          <div class="row" style="display: none;">
            <div class="col-md-12">
              <div class="form-group">
                <label for="statusf">Status</label>
                <select class="form-control" id="statusf" name="statusf">
                  <option value="">Pilih Status</option>
                  <option value="0">Direncakan</option>
                  <option value="1">Disetujui</option>
                  <option value="2">Tidak Disetujui</option>
                </select>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Batal
          </button>
        </div>
      </div><!-- /.modal-content -->
    </form>
  </div>
</div>


<!-- education -->
<div class="modal fade" id="education" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <form id="feducation" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Education</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_education" id="id_education">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="nama">Gelar</label>
                <select class="form-control" name="nama" id="nama" style="width: 100%;" required>
                  <option value="">Pilih Gelar</option>
                  <?php foreach ($jenisgelar as $gelar) : ?>
                    <option value="<?= $gelar['id'] ?>"><?= $gelar['text'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="tanggal_lulus">Tanggal Lulus</label>
                <input type="date" class="form-control" id="tanggal_lulus" name="tanggal_lulus" placeholder="Tanggal Lulus" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="lembaga">Lembaga</label>
                <input type="text" class="form-control" id="lembaga" name="lembaga" placeholder="Lembaga" required />
              </div>
            </div>
          </div>

          <div class="row" style="display: none;">
            <div class="col-md-12">
              <div class="form-group">
                <label for="statuse">Status</label>
                <select class="form-control" id="statuse" name="statuse" required>
                  <option value="">Pilih Status</option>
                  <option value="1">Aktif</option>
                  <option value="2">Tidak Aktif</option>
                </select>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Batal
          </button>
        </div>
      </div><!-- /.modal-content -->
    </form>
  </div>
</div>


<!-- membership -->
<div class="modal fade" id="membership_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <form id="fmembership" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="membership_modal_label"><?=$membership_alias?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_membership_pengesah" id="id_membership_pengesah">

          <div class="form-group">
            <label for="membership_jenis">Jenis <?=$membership_alias?></label>
            <select class="form-control" style="width: 100%;" id="membership_jenis" name="membership_jenis" required>
              <option value="">Pilih <?=$membership_alias?></option>
              <?php foreach ($membership as $member) : ?>
                <option value="<?= $member['id'] ?>"><?= $member['text'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group" style="display: none;">
            <label for="atasan">Approval</label>
            <select class="form-control" name="atasan" id="atasan" style="width: 100%;">
              <option value="0" selected>Pilih Approval</option>
              <?php foreach ($atasanmember as $atasan) : ?>
                <option value="<?= $atasan['id'] ?>"><?= $atasan['text'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="membership_tanggal">Tanggal Anggota</label>
            <input type="date" class="form-control" id="membership_tanggal" name="membership_tanggal" placeholder="Tanggal Anggota" required />
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Batal
          </button>
        </div>
      </div><!-- /.modal-content -->
    </form>
  </div>
</div>



<!-- membership_active -->
<div class="modal fade" id="membership_active_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <form id="fmembership_active" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="membership_active_modal_label">Aktifkan Membership</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_membership" id="id_membership_active">
          <input type="hidden" name="st_membership" id="st_membership_active">
          <div class="form-group">
            <div class="form-group">
              <label for="membership_active_name"></label>
              <input type="text" class="form-control" id="membership_active_name" name="membership_active_name" placeholder="" readonly />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Batal
          </button>
        </div>
      </div><!-- /.modal-content -->
    </form>
  </div>
</div>

<!-- membership_active -->
<div class="modal fade" id="fot_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <form id="fmembership_active" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Lihat foto</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <img src="" class="img-fluid" alt="" id="foto-preview">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Batal
          </button>
        </div>
      </div><!-- /.modal-content -->
    </form>
  </div>
</div>

<div class="modal fade" id="myModalPhoto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <form id="formPhoto" enctype="multipart/form-data">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="myModalLabelPhoto"></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">

                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <img id="detail-photo" style="width: 100%;">
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">
                      Tutup
                  </button>
              </div>
          </div><!-- /.modal-content -->
      </form>
  </div><!-- /.modal-dialog -->
</div>

<!-- wilayah -->
<div class="modal fade" id="keaktifan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <form id="fkeaktifan" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Form Keaktifan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_keaktifan" id="id_keaktifan">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="kategorial"><?=$jabatan_alias?></label>
                <select class="form-control" name="id_jabatan" id="id_jabatan" style="width: 100%;" required>
                  <option value="">Pilih <?=$jabatan_alias?></option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="kategorial">Tanggal Mulai</label>
                <input type="date" value="<?= date('Y-m-d') ?>" name="tanggal_mulai" id="tanggal_mulai_keaktifan" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="kategorial">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai_keaktifan" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="kategorial"><?=$pilihan_keaktifan_alias?></label>
                <select class="form-control" name="kode_master" id="kode_master" style="width: 100%;" required>
                  <option value="">Pilih <?=$pilihan_keaktifan_alias?></option>
                  <?php foreach ($pilihanKeaktifan as $q):?>
                    <option value="<?=$q['nama']?>"><?=$q['nama']?></option>
                  <?php endforeach;?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="kategorial"><?=$master_keaktifan_alias?></label>
                <select class="form-control" name="id_master" id="id_master" style="width: 100%;" required>
                  <option value="">Pilih <?=$master_keaktifan_alias?></option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Batal
          </button>
        </div>
      </div><!-- /.modal-content -->
    </form>
  </div>
</div>

<script>
  global_id_user = "<?= $profile['id_user'] ?? 0 ?>";
</script>