###################
Aplikasi Audit Any
###################

CodeIgniter is an Application Development Framework - a toolkit - for people
who build web sites using PHP. Its goal is to enable you to develop projects
much faster than you could if you were writing code from scratch, by providing
a rich set of libraries for commonly needed tasks, as well as a simple
interface and logical structure to access these libraries. CodeIgniter lets
you creatively focus on your project by minimizing the amount of code needed
for a given task.

*******************
Konfigurasi
*******************
1. Atur settingan database dengan yang dilocal ataupun di hosting direktori nya ada di application/config/database.php
2. Atur email untuk yang di hosting direktori nya ada di application/core/Render_Controller.php di function send_email. ubah smtp nya disesuaikan dengan yang ada di hosting
3. API
 *untuk akses api saat ini hanya bisa mengambil data aktifitas aja. dengan cara berikut:
  1. url: audityany.com/api/aktifitas/list_aktifitas
  2. method: get
  3. params: tidak ada
 *untuk sample cara penggunaan ada didalam folder
  1. controllers: applications/controllers/SampleApi.php
  2. views: applications/views/templates/sample-api.php


