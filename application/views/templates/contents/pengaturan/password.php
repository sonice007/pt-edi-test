<div class="card">
  <div class="card-header">
    <div class="d-flex justify-content-between w-100">
      <h3 class="card-title">Form <?= $title ?></h3>
    </div>
  </div>
  <div class="card-body">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><?= $title ?></h3>
                    </div>
                    <!-- form start -->
                    <form id="quickForm">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="current_password">Password Lama</label>
                                <input type="password" name="current_password" class="form-control" id="current_password" placeholder="Current Password">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="password_confirm">Konfirmasi Password</label>
                                <input type="password" name="password_confirm" class="form-control" id="password_confirm" placeholder="Password">
                            </div>
                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="terms" class="custom-control-input" id="password_visibility">
                                    <label class="custom-control-label" for="password_visibility">Lihat Password</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-info">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6">

            </div>
            <!--/.col (right) -->
        </div>
    </div>
</div>