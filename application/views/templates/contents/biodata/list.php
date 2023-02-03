<div class="card">
  <div class="card-header">
    <div class="d-flex justify-content-between w-100">
      <h3 class="card-title">Daftar <?= $title ?></h3>

    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="dt_basic_v2" class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <th class="text-center align-middle">Aksi</th>
          <th class="text-left align-middle">Nama</th>
          <th class="text-left align-middle">Tempat, Tanggal Lahir</th>
          <th class="text-left align-middle">Posisi yang dilamar</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- /.card-body -->
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
