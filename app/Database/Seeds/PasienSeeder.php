<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class PasienSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');

        for ($i = 0; $i < 50; $i++) {
            $data = [
                'nomor_reg'         => 'REG' . $faker->unique()->numerify('2025######'),
                'nomor_rawat'       => 'RW' . $faker->unique()->numerify('###'),
                'tanggal'           => $faker->date(),
                'jam'               => $faker->time('H:i'),
                'nama_pasien'       => $faker->name(),
                'jenis_kelamin'     => $faker->randomElement(['L', 'P']),
                'umur'              => $faker->numberBetween(1, 90),

                'kode_dokter'       => 'D001',
                'nama_dokter'       => 'Dr. Ahmad',
                'nomor_rm'          => 'RM' . $faker->numerify('###'),
                'poliklinik'        => $faker->randomElement(['Poli Umum', 'Poli Gigi']),
                'jenis_bayar'       => $faker->randomElement(['Umum', 'BPJS', 'Asuransi']),
                'penanggung_jawab'  => $faker->name(),
                'alamat_pj'         => $faker->address(),
                'hubungan_pj'       => $faker->randomElement(['Orang Tua', 'Saudara', 'Pasangan']),
                'biaya_registrasi'  => $faker->randomFloat(2, 10000, 100000),
                'status_registrasi' => 'Aktif',
                'no_telepon'        => $faker->phoneNumber(),
                'status_rawat'      => 'Rawat Jalan',
                'status_poli'       => 'Belum Diperiksa',
                'status_bayar'      => 'Belum Lunas',
                'nomor_bed'         => 'BED' . $faker->numerify('##'),
                'status_kamar'      => $faker->randomElement(['Terisi', 'Kosong'])
            ];

            $this->db->table('sik.registrasi')->insert($data);
        }
    }
}
