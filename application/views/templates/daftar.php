<!DOCTYPE html>
<html lang="zxx">
<head>
    <title>Login - <?=$app_name?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets-login/assets/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets-login/assets/fonts/font-awesome/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets-login/assets/fonts/flaticon/font/flaticon.css">

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="<?=base_url()?>assets-login/assets/img/favicon.ico" type="image/x-icon" >

    <!-- Google fonts -->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets-login/fonts.googleapis.com/css65e6.css?family=Open+Sans:400,300,600,700,800%7CPoppins:400,500,700,800,900%7CRoboto:100,300,400,400i,500,700">
    <link href="<?=base_url()?>assets-login/fonts.googleapis.com/css22962.css?family=Jost:wght@300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets-login/assets/css/style.css">
    <link rel="stylesheet" type="text/css" id="style_sheet" href="<?=base_url()?>assets-login/assets/css/skins/default.css">
    <style type="text/css">
        .login-2 .waviy span{
            font-size: 32px;
        }
    </style>

</head>
<body id="top">
<div class="page_loader"></div>

<!-- Login 2 start -->
<div class="login-2">
    <div class="container">
        <div class="row login-box">
            <div class="col-lg-6 col-md-12 bg-img">
                <div class="info">
                    <div class="info-text">
                        <div class="waviy">
                            <span style="--i:1">w</span>
                            <span style="--i:2">e</span>
                            <span style="--i:3">l</span>
                            <span style="--i:4">c</span>
                            <span style="--i:5">o</span>
                            <span style="--i:6">m</span>
                            <span style="--i:7">e</span>
                            <span class="color-yellow" style="--i:8">t</span>
                            <span class="color-yellow" style="--i:9">o</span>
                            <br>
                            <span style="--i:10">P</span>
                            <span style="--i:10">T</span>
                            <span style="--i:10">.</span>
                            <span style="--i:10">E</span>
                            <span style="--i:10">D</span>
                            <span style="--i:10">I</span>
                            <span style="--i:10"></span>
                            <span style="--i:10"></span>
                            <span style="--i:10">I</span>
                            <span style="--i:10">N</span>
                            <span style="--i:10">D</span>
                            <span style="--i:10">O</span>
                            <span style="--i:10">N</span>
                            <span style="--i:10">E</span>
                            <span style="--i:10">S</span>
                            <span style="--i:10">I</span>
                            <span style="--i:10">A</span>
                        </div>
                        <p>PT EDI Indonesia didirikan pada tanggal 1 Juni 1995 sebagai perusahaan pelopor dalam mengembangkan Jasa Pertukaran Data Elektronik (PDE) di Indonesia yang merupakan anak perusahaan dari PT Pelabuhan Indonesia II (Persero).</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 form-info">
                <div class="form-section">
                    <h3>Form Daftar</h3>
                    <?php
                        $flash = $this->session->flashdata();
                        if (!empty($flash)) :
                          $message = !is_array($flash['message']) ? $flash['message'] : $flash['message']['message'];
                          $color = !is_array($flash['message']) ? 'danger' : ($flash['message']['status'] ? 'success' : 'danger');
                          $text_title = !is_array($flash['message']) ? 'Failed' : ($flash['message']['status'] ? 'Success' : 'Failed');
                        ?>
                        <div class="alert alert-<?= $color ?> alert-dismissible fade show" role="alert" id="alert">
                            <strong><?= $text_title ?></strong><br><?= $message ?>
                            <br>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif ?>
                    <div class="login-inner-form">
                        <form action="" method="POST" id="form-sign-up">
                            <div class="form-group form-box">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" aria-label="Email Address">
                                <i class="flaticon-mail-2"></i>
                            </div>
                            <div class="form-group form-box">
                                <input type="password" name="password" id="password" class="form-control" autocomplete="off" placeholder="Password" aria-label="Password">
                                <i class="flaticon-password"></i>
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" form="form-sign-up" class="btn-md btn-theme w-100">Submit</button>
                            </div>
                            <hr>
                            <div style="float: right;">
                                <a class="btn-md btn-theme" style="width: 100%;" href="<?=base_url()?>login">Sign In</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Login 2 end -->

<!-- External JS libraries -->
<script src="<?=base_url()?>assets-login/assets/js/jquery.min.js"></script>
<script src="<?=base_url()?>assets-login/assets/js/popper.min.js"></script>
<script src="<?=base_url()?>assets-login/assets/js/bootstrap.bundle.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
  <script src="<?= base_url('assets/plugins/') ?>jquery-validation/jquery.validate.min.js"></script>
<!-- Custom JS Script -->
<script type="text/javascript">
    const base_url = '<?= base_url() ?>';
    $(document).ready(function () {
        // $("#loader").LoadingOverlay('progress');
        $('#password-visibility').change(function () {
            const password = $('#password')
            // password toggle
            if (this.checked) {
                password.attr('type', 'text')
            } else {
                password.attr('type', 'password')
            }
        })

        $('#form-sign-up').validate({
            // Rules for form validation
            rules: {
                email: {
                    required: true,
                },
                password: {
                    required: true,
                }
            },

            // Messages for form validation
            messages: {
                email: {
                    required: 'Tolong masukan Email atau Username'
                },
                password: {
                    required: 'Tolong masukan password'
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function (form) {
                // setBtnLoading('button[type=submit]', 'Masuk')
                let recaptcha = $("#g-recaptcha-response").val()
                $.ajax({
                    method: 'post',
                    url: '<?= base_url() ?>daftar/submit',
                    data: {
                        email: form.email.value,
                        password: form.password.value,
                        recaptcha: recaptcha
                    },
                    success: function (response) {
                    if (response.status == 1) {
                            alert(response.message)
                            $('#email').val('')
                            $('#password').val('')

                            $('#email').focus()
                            window.location.href = base_url + 'login';
                        } else if (response.status == 0) {
                            alert(response.message)
                            window.location.href = base_url + 'daftar';
                        } else {
                            // setToast({ title: "Gagal", body: "Koneksi buruk.", class: "bg-warning" });
                            alert('Koneksi buruk')
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(textStatus, errorThrown);
                    },
                    complete: function () {
                        // setBtnLoading('button[type=submit]', '<i class="fas fa-sign-in-alt"></i> Masuk', false);
                    }
                })
            }
        });
    })
</script>
</body>

</html>