<?php
if ($idpart == null || $idpart == 0 || $idpart == '-' || $this->session->userdata('data')['level'] == 'Super Admin') :
?>
  <div class="card card-info card-outline" id="filter">
    <div class="card-header">
      <div class="container-fluid">
        <div class="d-flex justify-content-end  align-items-star w-100 flex-md-row flex-column">
          <h3 class="card-title align-self-center">Filter : </h3>
          <div class="form-group  mb-lg-0 ml-lg-2">
            <select class="form-control" id="filter-partner" name="filter-partner" style=" min-width:150px" value="" style="width: 100%;"></select>
          </div>
          <div class="form-group  mb-lg-0 ml-lg-2">
            <select class="form-control" id="filter-posisi" name="filter-posisi" style=" min-width:150px" value="" style="width: 100%;"></select>
          </div>
          <div class="form-group  mb-lg-0 ml-lg-2">
            <select class="form-control" id="filter-kategorial" name="filter-kategorial" style=" min-width:150px" value="" style="width: 100%;"></select>
          </div>
          <div class="form-group  mb-lg-0 ml-lg-2">
            <select class="form-control" id="filter-wilayah" name="filter-wilayah" style=" min-width:150px" value="" style="width: 100%;"></select>
          </div>
          <div class="form-group  mb-lg-0 ml-lg-2">
            <select class="form-control" id="filter-keanggotaan" name="filter-keanggotaan" style=" min-width:150px" value="" style="width: 100%;"></select>
          </div>
          <div class="form-group  mb-lg-0 ml-lg-2">
            <select class="form-control" id="filter-usia" name="filter-usia" style=" min-width:150px" value="" style="width: 100%;">
              <option value="">Pilih Range Usia</option>
              <option value="0-12">0-12</option>
              <option value="13-18">13-18</option>
              <option value="19-25">19-25</option>
              <option value="26-40">26-40</option>
              <option value="41-55">41-55</option>
              <option value="56-65">56-65</option>
              <option value="66-80">66-80</option>
              <option value="81-1000">Diatas 80</option>
            </select>
          </div>
          <div class="form-group  mb-lg-0 ml-lg-2">
            <button type="button" class="btn btn-info btn" id="btn-filter" style="width: 100%;"><i class="fas fa-search"></i> Cari</button>
          </div>
          <div class="ml-lg-2">
            <a class="btn btn-warning btn" href="" style="width: 100%;"><i class="fas fa-undo"></i> Reset filter</a>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
endif;
?>

<div class="row">
  <div class="col-md-2 col-sm-6 col-12">
    <a href="<?=base_url()?>pengaturan/profile?level=125" style="color: black;">
      <div class="info-box">
        <span class="info-box-icon bg-info"><i class="far fa-star"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">LEVEL [Partner L1]</span>
          <span class="info-box-number"><?=$level_partner_level_1?></span>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-2 col-sm-6 col-12">
    <a href="<?=base_url()?>pengaturan/profile?level=126" style="color: black;">
      <div class="info-box">
        <span class="info-box-icon bg-info"><i class="far fa-star"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">LEVEL [Partner L2]</span>
          <span class="info-box-number"><?=$level_partner_level_2?></span>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-2 col-sm-6 col-12">
    <a href="<?=base_url()?>pengaturan/profile?level=123" style="color: black;">
      <div class="info-box">
        <span class="info-box-icon bg-info"><i class="far fa-star"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">LEVEL [Reader]</span>
          <span class="info-box-number"><?=$level_partner_level_reader?></span>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-2 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-info"><i class="far fa-star"></i></span>
      <div class="info-box-content">
        <span class="info-box-text"><?=$membership_alias?> [BOD]</span>
        <span class="info-box-number"><?=$membership?></span>
      </div>
    </div>
  </div>

  <div class="col-md-2 col-sm-6 col-12">
    <a href="<?=base_url()?>pengaturan/profile?jk=1" style="color: black;">
      <div class="info-box">
        <span class="info-box-icon bg-info"><i class="far fa-star"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Laki-laki</span>
          <span class="info-box-number"><?=$pria?></span>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-2 col-sm-6 col-12">
    <a href="<?=base_url()?>pengaturan/profile?jk=2" style="color: black;">
      <div class="info-box">
        <span class="info-box-icon bg-info"><i class="far fa-star"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Perempuan</span>
          <span class="info-box-number"><?=$wanita?></span>
        </div>
      </div>
    </a>
  </div>

</div>

<div class="card">
  <div class="card-header">
    <div class="d-flex justify-content-between w-100">
      <h3 class="card-title">Daftar <?= $title ?></h3>
      <div class="row">
        <div class="col-md-12">
          <a id="btn_export_pdf" href="<?= base_url() ?>pengaturan/profile/export_pdf" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> Export PDF</a>
          <a id="btn_export" href="<?= base_url() ?>pengaturan/profile/export_excel" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Export Excel</a>
          <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#import"><i class="fas fa-file-excel"></i> <span>Import Excel</span></button>
          <a class="btn btn-primary btn-sm" href="<?= base_url() ?>pengaturan/profile/tambah"><i class="fa fa-plus"></i> Tambah</a>
        </div>
      </div>

    </div>
  </div>
  <!-- /.card-header -->
  <input type="hidden" value="<?=$membership_alias?>" id="alias_name_membership">
  <input type="hidden" value="<?=$partner_alias?>" id="isi-partner">
  <input type="hidden" value="<?=$kategori_keanggotaan_alias?>" id="isi-kategori-keanggotaan">
  <div class="card-body">
    <table id="dt_basic_v2" class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Aksi</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Tanggal Daftar</th>
          <!-- <th class="text-center align-middle" rowspan='1' colspan='1'>Kode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> -->
          <th class="text-center align-middle" rowspan='1' colspan='1'>Level</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'><?=$partner_alias?></th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Posisi</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'><?=$kategori_keanggotaan_alias?></th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>NIK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Email</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Nama Lengkap</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Jenis Kelamin</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Flag</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Tanggal Registrasi</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Tanggal Lahir</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Tanggal Meninggal</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Foto</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'><?=$membership_alias?></th>
          <th class="text-center align-middle" rowspan='1' colspan='1'><?=$data_formal_alias?></th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Kontak</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Alamat</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'>Pendidikan</th>
          <th class="text-center align-middle" rowspan='1' colspan='1'><?=$kategorial_alias?></th>
          <th class="text-center align-middle" rowspan='1' colspan='1'><?=$wilayah_alias?></th>
        </tr>
      </thead>
    </table>
    <hr>
    <a style="float: right;" type="button" class="btn btn-primary btn-sm" href="<?=base_url()?>activity/logging?menu=<?=$this->modul?>"><i class="fa fa-search"></i> &nbsp; Log Aktifitas</a>
  </div>
  <!-- /.card-body -->
</div>

<!-- photo -->
<div class="modal fade" id="photo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header outline-info">
        <h5 class="modal-title text-center">Photo</h5>
      </div>
      <div class="modal-body">
        <img src="<?= base_url() ?>\assets\images\student.png" class="img-fluid" alt="" id="img-view">
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- contact -->
<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center">Contact</h5>
      </div>
      <div class="modal-body">
        <table id="" class="table table-bordered table-striped table-hover">
          <thead id="tbl-head-contact">
          </thead>
          <tbody id="tbl-body-contact">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- keanggotaan -->
<div class="modal fade" id="keanggotaan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center">Keanggotaan</h5>
      </div>
      <div class="modal-body">
        <table id="" class="table table-bordered table-striped table-hover">
          <thead id="tbl-head-keanggotaan">
          </thead>
          <tbody id="tbl-body-keanggotaan">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- alamat -->
<div class="modal fade" id="lalamat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center">Alamat</h5>
      </div>
      <div class="modal-body">
        <table id="" class="table table-bordered table-striped table-hover">
          <thead id="tbl-head-alamat">
          </thead>
          <tbody id="tbl-body-alamat">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- formal -->
<div class="modal fade" id="formal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center"><?=$data_formal_alias?></h5>
      </div>
      <div class="modal-body">
        <table id="" class="table table-bordered table-striped table-hover">
          <thead id="tbl-head-formal">
          </thead>
          <tbody id="tbl-body-formal">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>
  
<!-- kategorial -->
<div class="modal fade" id="kategorial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center"><?=$kategorial_alias?></h5>
      </div>
      <div class="modal-body">
        <table id="" class="table table-bordered table-striped table-hover">
          <thead id="tbl-head-kategorial">
          </thead>
          <tbody id="tbl-body-kategorial">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- wilayah -->
<div class="modal fade" id="wilayah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center"><?=$wilayah_alias?></h5>
      </div>
      <div class="modal-body">
        <table id="" class="table table-bordered table-striped table-hover">
          <thead id="tbl-head-wilayah">
          </thead>
          <tbody id="tbl-body-wilayah">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- education -->
<div class="modal fade" id="education" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center">Education</h5>
      </div>
      <div class="modal-body">
        <table id="" class="table table-bordered table-striped table-hover">
          <thead id="tbl-head-education">
          </thead>
          <tbody id="tbl-body-education">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- membership -->
<div class="modal fade" id="membership" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center"><?=$membership_alias?></h5>
      </div>
      <div class="modal-body">
        <table id="" class="table table-bordered table-striped table-hover">
          <thead id="tbl-head-membership">
          </thead>
          <tbody id="tbl-body-membership">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- profile -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center">Hapus <?= $title ?></h5>
      </div>
      <div class="modal-body">
        <form action="" id="fdelete">
          <input type="hidden" name="id_profile" id="delete-id_profile">
          <input type="hidden" name="id_user" id="delete-id_user">
          <p>Apakah anda yakin akan menghapus profile ini..?</p>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger btn-ef btn-ef-3 btn-ef-3c" form="fdelete"><i class="fa fa-check"></i> Hapus</button>
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="importExcel" method="post" enctype="multipart/form-data" action="<?= base_url() ?>pengaturan/profile/import_excel" id="form-import">
        <div class="modal-header">
          <h3 class="modal-title custom-font">Import Data</h3>
        </div>
        <div class="modal-body">
          <label>File(.xls) <a href="<?= base_url() ?>pengaturan/profile/downloadSample?filter-partner=<?= $idpart ?>">Download sample file import</a></label>
          <br>
          <input type="file" name="file" id="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
          <br>
          <br>
          <h5><b>Ketentuan Pengisian Excel :</b></h5>
          <div class="row">

            <div class="col-md-4">
              <ul class="">
                <li class=""><b>Level</b></li>
                <ol class="">
                  <?php foreach ($level as $p) {
                    echo "<li>" . $p['lev_nama'] . "</li>";
                  }
                  ?>
                </ol>
              </ul>
            </div>

            <div class="col-md-4">
              <ul class="">
                <li class=""><b>Partner</b></li>
                <ol class="">
                  <?php foreach ($partner as $p) {
                    echo "<li>" . $p['text'] . "</li>";
                  }
                  ?>
                </ol>
              </ul>
            </div>

            <div class="col-md-4">
              <ul class="">
                <li class=""><b>Jenis Kelamin</b></li>
                <ol class="">
                  <li>Laki-laki</li>
                  <li>Perempuan</li>
                </ol>
              </ul>
            </div>

            <div class="col-md-4">
              <ul class="">
                <li class=""><b>Tipe Kontak</b></li>
                <ol class="">
                  <?php foreach ($tipekontak as $p) {
                    echo "<li>" . $p['text'] . "</li>";
                  }
                  ?>
                </ol>
              </ul>
            </div>

            <div class="col-md-4">
              <ul class="">
                <li class=""><b>Jenis Peristiwa Formal</b></li>
                <ol class="">
                  <?php foreach ($peristiwa as $p) {
                    echo "<li>" . $p['text'] . "</li>";
                  }
                  ?>
                </ol>
              </ul>
            </div>

            <div class="col-md-4">
              <ul class="">
                <li class=""><b>Gelar</b></li>
                <ol class="">
                  <?php foreach ($jenisgelar as $p) {
                    echo "<li>" . $p['text'] . "</li>";
                  }
                  ?>
                </ol>
              </ul>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button id="clickImport" type="submit" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c"><i class="fa fa-arrow-right"></i> Simpan</button>

          <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>