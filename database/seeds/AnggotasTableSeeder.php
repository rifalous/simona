<?php

use Illuminate\Database\Seeder;

class AnggotasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Anggota::insert([
            [
              'id'  			=> 1,
              'user_id'  		=> 1,
              'npm'				=> 10000353,
              'nama' 			=> 'Admin Perpusku',
              'tempat_lahir'	=> 'Jakarta',
              'tgl_lahir'		=> '1997-02-01',
              'jk'				=> 'L',
              'prodi'			=> 'TI',
              'created_at'      => \Carbon\Carbon::now(),
              'updated_at'      => \Carbon\Carbon::now()
            ],
            [
              'id'  			=> 2,
              'user_id'  		=> 2,
              'npm'				=> 10000375,
              'nama' 			=> 'Ramadhan',
              'tempat_lahir'	=> 'Depok',
              'tgl_lahir'		=> '1997-01-01',
              'jk'				=> 'L',
              'prodi'			=> 'TI',
              'created_at'      => \Carbon\Carbon::now(),
              'updated_at'      => \Carbon\Carbon::now()
            ],
        ]);
    }
}
