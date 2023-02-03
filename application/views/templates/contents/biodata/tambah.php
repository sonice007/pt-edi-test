<div class="card">
  <div class="card-header">
    <div class="d-flex justify-content-between w-100">
      <h3 class="card-title">Data Biodata</h3>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <form id="main-form" enctype="multipart/form-data">
      <div class="row">
        <input type="hidden" name="id" id="id" value="<?= $getID; ?>">
        <input type="hidden" name="id_user" id="id_user" value="<?= $id_user; ?>">
        <div class="col-md-6">
          <div class="form-group">
            <label for="nama_belakang">Posisi *</label>
            <input type="text" class="form-control" id="posisi" name="posisi" placeholder="Posisi" value="<?php if($biodata != null) echo $biodata['posisi'];?>" required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="nama_belakang">Nama *</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" value="<?php if($biodata != null) echo $biodata['nama'];?>" required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="nama_belakang">No. KTP *</label>
            <input type="text" class="form-control" id="no_ktp" name="no_ktp" placeholder="No. KTP " value="<?php if($biodata != null) echo $biodata['no_ktp'];?>" required />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="nama_belakang">Tempat Lahir *</label>
            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" value="<?php if($biodata != null) echo $biodata['tempat_lahir'];?>" required />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="nama_belakang">Tanggal Lahir *</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="" value="<?php if($biodata != null) echo $biodata['tanggal_lahir'];?>" required />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="id_level">Jenis Kelamin *</label>
            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
              <option value="">Pilih Jenis Kelamin</option>
              <option value="Laki-laki" <?php if($biodata != null) if ($biodata['jenis_kelamin'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
              <option value="Perempuan" <?php if($biodata != null) if ($biodata['jenis_kelamin'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="id_level">Agama *</label>
            <select class="form-control" id="agama" name="agama" required>
              <option value="">Pilih Agama</option>
              <option value="Islam" <?php if($biodata != null) if ($biodata['agama'] == 'Islam') echo 'selected'; ?>>Islam</option>
              <option value="Kristen" <?php if($biodata != null) if ($biodata['agama'] == 'Kristen') echo 'selected'; ?>>Kristen</option>
              <option value="Hindu" <?php if($biodata != null) if ($biodata['agama'] == 'Hindu') echo 'selected'; ?>>Hindu </option>
              <option value="Buddha" <?php if($biodata != null) if ($biodata['agama'] == 'Buddha') echo 'selected'; ?>>Buddha</option>
              <option value="Katolik" <?php if($biodata != null) if ($biodata['agama'] == 'Katolik') echo 'selected'; ?>>Katolik</option>
              <option value="Konghucu" <?php if($biodata != null) if ($biodata['agama'] == 'Konghucu') echo 'selected'; ?>>Konghucu</option>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="id_level">Golongan Darah *</label>
            <select class="form-control" id="golongan_darah" name="golongan_darah" required>
              <option value="">Pilih Golongan Darah</option>
              <option value="A" <?php if($biodata != null) if ($biodata['golongan_darah'] == 'A') echo 'selected'; ?>>A</option>
              <option value="B" <?php if($biodata != null) if ($biodata['golongan_darah'] == 'B') echo 'selected'; ?>>B</option>
              <option value="AB" <?php if($biodata != null) if ($biodata['golongan_darah'] == 'AB') echo 'selected'; ?>>AB</option>
              <option value="O" <?php if($biodata != null) if ($biodata['golongan_darah'] == 'O') echo 'selected'; ?>>O</option>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="id_level">Status *</label>
            <select class="form-control" id="status" name="status" required>
              <option value="">Pilih Status</option>
              <option value="Lajang" <?php if($biodata != null) if ($biodata['status'] == 'Lajang') echo 'selected'; ?>>Lajang</option>
              <option value="Menikah" <?php if($biodata != null) if ($biodata['status'] == 'Menikah') echo 'selected'; ?>>Menikah</option>
              <option value="Cerai" <?php if($biodata != null) if ($biodata['status'] == 'Cerai') echo 'selected'; ?>>Cerai </option>
            </select>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="nama_belakang">Alamat KTP *</label>
            <textarea class="form-control" required id="alamat_ktp" name="alamat_ktp"><?php if($biodata != null) echo $biodata['alamat_ktp'];?></textarea>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="nama_belakang">Alamat Tinggal *</label>
            <textarea class="form-control" required id="alamat_tinggal" name="alamat_tinggal"><?php if($biodata != null) echo $biodata['alamat_tinggal'];?></textarea>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="nama_belakang">Email *</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Email " value="<?php if($biodata != null) echo $biodata['email'];?>" required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="nama_belakang">No. Telpon *</label>
            <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="No. Telpon " value="<?php if($biodata != null) echo $biodata['no_telp'];?>" required />
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="nama_belakang">Orang Terdekat Yang Dapat Dihubungi *</label>
            <input type="text" class="form-control" id="orang_terdekat" name="orang_terdekat" placeholder="Nama dan No. Telpon " value="<?php if($biodata != null) echo $biodata['orang_terdekat'];?>" required />
          </div>
        </div>

        <div class="col-md-12">
          <br>
          <!-- pendidikan terakhir -->
          <div class="d-flex justify-content-between align-items-center">
            <label for="pendidikan terakhir">Pendidikan Terakhir</label>
            <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#pendidikan" id="btn-pendidikan"><i class="fa fa-plus"></i> Tambah</button>
          </div>
          <table id="tblPendidikan" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Jenjang Pendidikan Terakhir</th>
                <th>Nama Institusi Akademik</th>
                <th>Jurusan</th>
                <th>Tahun Lulus</th>
                <th>IPK</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>

        <div class="col-md-12">
          <br>
          <!-- riwayat pelatihan -->
          <div class="d-flex justify-content-between align-items-center">
            <label for="riwayat pelatihan">Riwayat Pelatihan</label>
            <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#pelatihan" id="btn-pelatihan"><i class="fa fa-plus"></i> Tambah</button>
          </div>
          <table id="tblPelatihan" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Nama Kursus Seminar</th>
                <th>Sertifikat (ada/tidak)</th>
                <th>Tahun</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>

        <div class="col-md-12">
          <br>
          <!-- riwayat pekerjaan -->
          <div class="d-flex justify-content-between align-items-center">
            <label for="riwayat pekerjaan">Riwayat Pekerjaan</label>
            <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#pekerjaan" id="btn-pekerjaan"><i class="fa fa-plus"></i> Tambah</button>
          </div>
          <table id="tblPekerjaan" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Nama Perusahaan</th>
                <th>Posisi Terakhir</th>
                <th>Pendapatan Terakhir</th>
                <th>Tahun</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>

        <div class="col-md-12">
          <div class="form-group">
            <label for="nama_belakang">Skill</label>
            <textarea class="form-control" id="skill" name="skill" placeholder="Tuliskan keahlian & keterampilan yang saat ini anda miliki"><?php if($biodata != null) echo $biodata['skill'];?></textarea>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="id_level">Bersedia Ditempatkan Diseluruh Kantor Perusahaan *</label>
            <select class="form-control" id="status_ditempatkan" name="status_ditempatkan" required>
              <option value="Ya" <?php if($biodata != null) if ($biodata['status_ditempatkan'] == 'Ya') echo 'selected'; ?>>Ya</option>
              <option value="Tidak" <?php if($biodata != null) if ($biodata['status_ditempatkan'] == 'Tidak') echo 'selected'; ?>>Tidak</option>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="nama_belakang">Penghasilan Yang Diharapkan *</label>
            <input type="number" class="form-control" id="penghasilan" name="penghasilan" placeholder="Penghasilan / Bulan " value="<?php if($biodata != null) echo $biodata['penghasilan'];?>" required />
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- /.card-body -->
  <div class="card-footer text-right">
    <button type="submit" form="main-form" class="btn btn-primary">
      Simpan
    </button>
    <?php if($this->session->userdata('data')['level'] == "Super Admin"):?>
      <a href="<?=base_url()?>biodata" class="btn btn-default">
        Kembali
      </a>
    <?php endif;?>
  </div>
</div>

<!-- pendidikan -->
<div class="modal fade" id="pendidikan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <form id="fpendidikan" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Pendidikan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_pendidikan" id="id_pendidikan">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="tipe_kontak">Jenjang Pendidikan Terakhir</label>
                <input type="text" class="form-control" id="jenjang_pendidikan_terakhir" name="jenjang_pendidikan_terakhir" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="keterangan">Nama Institusi Akademik</label>
                <input type="text" class="form-control" id="nama_institusi_akademik" name="nama_institusi_akademik" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="keterangan">Jurusan</label>
                <input type="text" class="form-control" id="jurusan" name="jurusan" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="keterangan">Tahun Lulus</label>
                <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" required />
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="keterangan">IPK</label>
                <input type="text" class="form-control" id="ipk" name="ipk" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="statusk">Status</label>
                <select class="form-control" id="status_pendidikan" name="status_pendidikan" required>
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
<!-- endp endidikan -->

<!-- pelatihan -->
<div class="modal fade" id="pelatihan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <form id="fpelatihan" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Pelatihan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_pelatihan" id="id_pelatihan">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="tipe_kontak">Nama Kursus Seminar</label>
                <input type="text" class="form-control" id="nama_kursus_seminar" name="nama_kursus_seminar" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="keterangan">Sertifikat</label>
                <select class="form-control" id="sertifikat" name="sertifikat">
                  <option value="Ada">Ada</option>
                  <option value="Tidak">Tidak</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="keterangan">Tahun</label>
                <input type="number" class="form-control" id="tahun" name="tahun" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="statusk">Status</label>
                <select class="form-control" id="status_pelatihan" name="status_pelatihan" required>
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
<!-- endp pelatihan -->

<!-- pekerjaan -->
<div class="modal fade" id="pekerjaan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <form id="fpekerjaan" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Pekerjaan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_pekerjaan" id="id_pekerjaan">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="tipe_kontak">Nama Perusahaan</label>
                <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="tipe_kontak">Posisi Terakhir</label>
                <input type="text" class="form-control" id="posisi_terakhir" name="posisi_terakhir" required />
              </div>
            </div>
          </div>

      
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="keterangan">Pendapatan Terakhir</label>
                <input type="number" class="form-control" id="pendapatan_terakhir" name="pendapatan_terakhir" required />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="keterangan">Tahun</label>
                <input type="number" class="form-control" id="tahun_pekerjaan" name="tahun_pekerjaan" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="statusk">Status</label>
                <select class="form-control" id="status_pelatihan" name="status_pekerjaan" required>
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
<!-- endp pelatihan -->

<script>
  global_id_user = "<?= $biodata['id_user'] ?? 0 ?>";
  const level = "<?=$this->session->userdata('data')['level']?>";
</script>