<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'dashboard';
$route['404_override'] = 'my404';
$route['translate_uri_dashes'] = TRUE;

$route['javascripts/(:any).js'] = 'loader/javascripts/$1';
$route['stylesheets/(:any).css'] = 'loader/stylesheets/$1';
$route['pengaturan/hakakseslevel/(:num)'] = 'pengaturan/hakAksesLevel/index/$1';

// Data Master
$route['master/kondisi-item']      = 'master/kondisiItem';
// $route['master/kondisi-item/tambah']      = 'master/kondisiItem/tambah';
// $route['master/kondisi-item/ubah/(:any)']      = 'master/kondisiItem/ubah/$1';

// tambahan
$route['javascripts/contents/(:any).js'] = 'loader/javascripts_contents/$1';
$route['javascripts/contents/(:any)/(:any).js'] = 'loader/javascripts_contents/$1/$2';
$route['javascripts/contents/(:any)/(:any)/(:any).js'] = 'loader/javascripts_contents/$1/$2/$3';
$route['javascripts/contents/(:any)/(:any)/(:any)/(:any).js'] = 'loader/javascripts_contents/$1/$2/$3/$4';
$route['javascripts/contents/(:any)/(:any)/(:any)/(:any)/(:any).js'] = 'loader/javascripts_contents/$1/$2/$3/$4/$5';

$route['stylesheets/contents/(:any).js'] = 'loader/stylesheets_contents/$1';
$route['stylesheets/contents/(:any)/(:any).js'] = 'loader/stylesheets_contents/$1/$2';
$route['stylesheets/contents/(:any)/(:any)/(:any).js'] = 'loader/stylesheets_contents/$1/$2/$3';

$route['data-master/kategori-warung']   = 'data-master/kategoriWarung';
$route['data-master/kategori-produk']   = 'data-master/kategoriProduk';
$route['data-master/satuan-harga']      = 'data-master/satuanHarga';

$route['pengaturan/kategorial-umur']    = 'pengaturan/kategorialUmur';
$route['data-master/laporan-header']    = 'data-master/laporanHeader';

$route['content/team-anggota']      = 'content/teamAnggota';
$route['content/team-anggota/tambah']      = 'content/teamAnggota/tambah';
$route['content/team-anggota/ubah/(:any)']      = 'content/teamAnggota/ubah/$1';
$route['content/team-libraries']      = 'content/teamLibraries';
$route['content/team-libraries/tambah']      = 'content/teamLibraries/tambah';
$route['content/team-libraries/ubah/(:any)']      = 'content/teamLibraries/ubah/$1';