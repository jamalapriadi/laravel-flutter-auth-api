<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Cms\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lists = array(
            array(
                'title'=>'Aplikasi Sapawarga Tawarkan Fitur Bank Sampah, Bisa Tingkatkan Efesiensi dan Efektivitas',
                'teaser'=>'Dinas Komunikasi dan Informatika (Diskominfo) Provinsi Jawa Barat menawarkan fitur bank sampah lewat aplikasi Sapawarga. Fitur ini bisa mendorong efesiensi dan efektivitas pengelolaan sampah. Baik dari hulu (sumber sampah) sampai hilir (Tempat Pembuangan Akhir).',
                'featured_image'=>'https://img.kliknusae.com/uploads/2024/10/Aplikasi-Sapawarga.jpg',
                'description'=>'Dinas Komunikasi dan Informatika (Diskominfo) Provinsi Jawa Barat menawarkan fitur bank sampah lewat aplikasi Sapawarga. Fitur ini bisa mendorong efesiensi dan efektivitas pengelolaan sampah. Baik dari hulu (sumber sampah) sampai hilir (Tempat Pembuangan Akhir).

                    Hal itu disampaikan Kepala Diskominfo Jabar Ika Mardiah acara IKP Talks #13 Tahun 2024 dengan tema “SampahKita: Wujudkan Lingkungan Sehat Mulai dari Pilah Sampah”, Rabu 9 Oktober 2024.

                    Dalam IKP Talks #13 kali ini, menghadirkan sejumlah pemateri, yakni Plt. Kepala Dinas Lingkungan Hidup Jabar Dodit Ardian Pancapana yang memberikan keynote speech.

                    Kemudian, Lead Business Analyst dari Jabar Digital Service (JDS) Rizki Adam Kurniawan, dan CEO Plastavfall Bank Reza Ramadhan Tarik.'
            ),

            array(
                'title'=>'Sekda Herman Ingatkan Kembali Pentingnya Pengelolaan Sampah, Jangan Kotori Citarum',
                'teaser'=>'Sekretaris Daerah Jawa Barat Herman Suryatman menekankan pentingnya pengelolaan sampah mulai dari hulu dalam upaya mendukung program Citarum Harum.',
                'featured_image'=>'https://img.kliknusae.com/uploads/2024/10/Pengelolaan-Sampah.jpg',
                'description'=>'Sekretaris Daerah Jawa Barat Herman Suryatman menekankan pentingnya pengelolaan sampah mulai dari hulu dalam upaya mendukung program Citarum Harum.'
            ),

            array(
                'title'=>'Mulai Hari Ini, Tol Bogor-Ciawi-Sukabumi Berbayar, Berikut Tarifnya',
                'teaser'=>'Mulai hari ini, Sabtu, 12 Oktober 2024, masyarakat tak lagi bisa melintasi Jalan Tol Bogor-Ciawi-Sukabumi (Bocimi) Seksi 2 Cigombong-Cibadak secara cuma-cuma.',
                'featured_image'=>'https://img.kliknusae.com/uploads/2024/10/Tol-Bocimi.jpg',
                'description'=>'Mulai hari ini, Sabtu, 12 Oktober 2024, masyarakat tak lagi bisa melintasi Jalan Tol Bogor-Ciawi-Sukabumi (Bocimi) Seksi 2 Cigombong-Cibadak secara cuma-cuma.'
            ),
        );

        foreach($lists as $key=>$val){
            $post = new Post;
            $post->title = $val['title'];
            $post->teaser = $val['teaser'];
            $post->description = $val['description'];
            $post->featured_image = $val['featured_image'];
            $post->visibility = 'publish';
            $post->save();
        }
    }
}
