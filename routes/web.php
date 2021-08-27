<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('home');
});
*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index');

Route::resource('user', 'UserController');

Route::resource('keuangan', 'KeuanganController');
Route::resource('transaksi', 'LihatAnggaranController');

Route::get('/laporan/ra', 'LaporanController@realisasiAnggaran');
Route::get('/laporan/ra/pdf', 'LaporanController@realisasiAnggaranPdf');
Route::get('/laporan/ra/excel', 'LaporanController@realisasiAnggaranExcel');
// Bawaan Aplikasi

/*
Route::get('/user', 'UserController@index');
Route::get('/user-register', 'UserController@create');
Route::post('/user-register', 'UserController@store');
Route::get('/user-edit/{id}', 'UserController@edit');
*/

Route::resource('buku', 'BukuController');
Route::get('/format_buku', 'BukuController@format');
Route::post('/import_buku', 'BukuController@import');

Route::resource('anggota', 'AnggotaController');

Route::get('/laporan/buku', 'LaporanController@buku');
Route::get('/laporan/buku/pdf', 'LaporanController@bukuPdf');
Route::get('/laporan/buku/excel', 'LaporanController@bukuExcel');
