<?php

namespace Database\Seeders;

use App\Models\Artikel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArtikelSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role('admin')->first();

        if (!$admin) {
            $this->command->warn('Admin user not found. Please run UserSeeder first.');
            return;
        }

        $articles = [
            [
                'title' => 'Mengenal Jenis-Jenis Sampah dan Cara Pengelolaannya',
                'slug' => 'mengenal-jenis-jenis-sampah-dan-cara-pengelolaannya',
                'excerpt' => 'Pelajari berbagai jenis sampah dan bagaimana cara mengelolanya dengan benar untuk menjaga kelestarian lingkungan.',
                'content' => '<h2>Pentingnya Mengenal Jenis Sampah</h2><p>Memahami jenis-jenis sampah merupakan langkah awal dalam pengelolaan sampah yang efektif. Sampah dapat dikategorikan menjadi beberapa jenis utama: organik, anorganik, B3 (Bahan Berbahaya dan Beracun), dan sampah spesifik.</p><h3>Sampah Organik</h3><p>Sampah organik berasal dari makhluk hidup seperti sisa makanan, daun, ranting, dan kotoran hewan. Sampah jenis ini dapat terurai secara alami dan dapat diolah menjadi kompos yang bermanfaat untuk pertanian.</p><h3>Sampah Anorganik</h3><p>Sampah anorganik meliputi plastik, kertas, logam, dan gelas. Jenis sampah ini membutuhkan waktu lama untuk terurai, sehingga penting untuk didaur ulang. Bank sampah berperan penting dalam mengumpulkan dan mendistribusikan sampah anorganik ke tempat daur ulang.</p><h3>Cara Pengelolaan yang Benar</h3><p>Pemilahan sampah dari sumber merupakan kunci utama. Pisahkan sampah organik dan anorganik sejak dari rumah. Sampah organik dapat dijadikan kompos, sementara sampah anorganik dapat disetor ke bank sampah untuk didaur ulang.</p>',
                'category' => 'Edukasi',
                'tags' => 'sampah organik, sampah anorganik, pengelolaan sampah, daur ulang',
                'is_published' => true,
            ],
            [
                'title' => 'Manfaat Bank Sampah untuk Lingkungan dan Ekonomi',
                'slug' => 'manfaat-bank-sampah-untuk-lingkungan-dan-ekonomi',
                'excerpt' => 'Bank sampah bukan hanya menyelamatkan lingkungan, tetapi juga memberikan manfaat ekonomi bagi masyarakat.',
                'content' => '<h2>Apa Itu Bank Sampah?</h2><p>Bank sampah adalah sistem pengelolaan sampah yang melibatkan masyarakat dalam memilah, mengumpulkan, dan menjual sampah yang masih memiliki nilai ekonomi. Konsep ini menggabungkan aspek lingkungan dan ekonomi.</p><h3>Manfaat Lingkungan</h3><p>Bank sampah membantu mengurangi volume sampah yang berakhir di TPA (Tempat Pembuangan Akhir). Dengan mendaur ulang sampah anorganik, kita mengurangi pencemaran tanah dan air. Selain itu, pengomposan sampah organik membantu mengurangi emisi gas metana dari pembusukan sampah.</p><h3>Manfaat Ekonomi</h3><p>Nasabah bank sampah dapat memperoleh penghasilan tambahan dari menjual sampah mereka. Harga sampah dihitung berdasarkan jenis dan beratnya. Uang hasil penjualan dapat ditabung atau diambil sewaktu-waktu.</p><h3>Manfaat Sosial</h3><p>Bank sampah menciptakan kesadaran masyarakat tentang pentingnya kebersihan lingkungan. Kegiatan ini juga mempererat hubungan antarwarga melalui kegiatan gotong royong dan edukasi bersama.</p>',
                'category' => 'Informasi',
                'tags' => 'bank sampah, manfaat ekonomi, lingkungan, ekonomi sirkular',
                'is_published' => true,
            ],
            [
                'title' => 'Cara Memulai Daur Ulang Sampah di Rumah',
                'slug' => 'cara-memulai-daur-ulang-sampah-di-rumah',
                'excerpt' => 'Panduan praktis untuk memulai kebiasaan daur ulang sampah di rumah Anda sendiri.',
                'content' => '<h2>Langkah Mudah Memulai Daur Ulang</h2><p>Daur ulang di rumah tidak sesulit yang dibayangkan. Dengan beberapa langkah sederhana, Anda sudah berkontribusi untuk lingkungan yang lebih bersih.</p><h3>1. Siapkan Tempat Sampah Terpisah</h3><p>Sediakan minimal 2 tempat sampah: satu untuk organik dan satu untuk anorganik. Jika memungkinkan, tambahkan tempat sampah khusus untuk kertas dan plastik agar lebih terorganisir.</p><h3>2. Pelajari Jenis Sampah yang Bisa Didaur Ulang</h3><p>Tidak semua sampah dapat didaur ulang. Plastik bersih, kertas, kardus, kaleng, dan botol kaca biasanya diterima di bank sampah. Pastikan sampah dalam kondisi bersih dan kering.</p><h3>3. Bersihkan Sampah Sebelum Dibuang</h3><p>Bilas botol plastik dan kaleng bekas sebelum menyimpannya. Sampah yang bersih memiliki nilai jual lebih tinggi dan lebih mudah didaur ulang.</p><h3>4. Bergabung dengan Bank Sampah</h3><p>Cari bank sampah terdekat di lingkungan Anda. Daftarkan diri sebagai nasabah dan setor sampah secara rutin. Anda akan mendapat tabungan dari sampah yang Anda setor.</p><h3>5. Ajak Keluarga Berpartisipasi</h3><p>Libatkan seluruh anggota keluarga dalam kebiasaan daur ulang. Ajarkan anak-anak tentang pentingnya menjaga lingkungan sejak dini.</p>',
                'category' => 'Tutorial',
                'tags' => 'daur ulang, tutorial, rumah tangga, zero waste',
                'is_published' => true,
            ],
            [
                'title' => 'Dampak Sampah Plastik terhadap Lingkungan',
                'slug' => 'dampak-sampah-plastik-terhadap-lingkungan',
                'excerpt' => 'Memahami bahaya sampah plastik dan mengapa kita harus mengurangi penggunaannya.',
                'content' => '<h2>Krisis Sampah Plastik Global</h2><p>Sampah plastik telah menjadi salah satu masalah lingkungan terbesar di dunia. Setiap tahun, jutaan ton plastik berakhir di lautan dan ekosistem daratan, mengancam kehidupan satwa liar dan kesehatan manusia.</p><h3>Plastik Tidak Mudah Terurai</h3><p>Plastik membutuhkan waktu ratusan hingga ribuan tahun untuk terurai. Selama proses tersebut, plastik akan terpecah menjadi mikroplastik yang lebih berbahaya karena dapat masuk ke rantai makanan.</p><h3>Pencemaran Laut</h3><p>Sekitar 8 juta ton plastik masuk ke laut setiap tahunnya. Hewan laut seperti penyu, paus, dan burung laut sering menelan plastik karena mengira sebagai makanan, yang dapat menyebabkan kematian.</p><h3>Dampak pada Kesehatan Manusia</h3><p>Mikroplastik yang terkontaminasi dalam air dan makanan dapat masuk ke tubuh manusia. Penelitian menunjukkan bahwa mikroplastik dapat menyebabkan gangguan hormon dan penyakit lainnya.</p><h3>Solusi yang Dapat Dilakukan</h3><p>Kurangi penggunaan plastik sekali pakai dengan membawa tas belanja sendiri, menggunakan botol minum isi ulang, dan memilih produk dengan kemasan ramah lingkungan. Dukung program daur ulang dan bank sampah di komunitas Anda.</p>',
                'category' => 'Edukasi',
                'tags' => 'plastik, pencemaran laut, mikroplastik, kesehatan',
                'is_published' => true,
            ],
            [
                'title' => 'Kompos dari Sampah Organik: Panduan Lengkap',
                'slug' => 'kompos-dari-sampah-organik-panduan-lengkap',
                'excerpt' => 'Ubah sampah organik Anda menjadi pupuk kompos yang bermanfaat untuk tanaman.',
                'content' => '<h2>Keuntungan Membuat Kompos Sendiri</h2><p>Mengompos sampah organik di rumah memiliki banyak keuntungan: mengurangi volume sampah, menghasilkan pupuk alami gratis, dan mengurangi emisi gas rumah kaca dari TPA.</p><h3>Bahan yang Bisa Dikomposkan</h3><p>Hampir semua sampah organik dapat dikompos: sisa sayuran dan buah, daun kering, rumput, serbuk gergaji, ampas kopi, dan kulit telur. Hindari daging, tulang, dan produk susu karena dapat menarik hama.</p><h3>Cara Membuat Kompos</h3><p><strong>1. Siapkan Wadah:</strong> Gunakan komposter atau buat sendiri dari tong bekas dengan lubang aerasi.</p><p><strong>2. Lapisan Bahan:</strong> Masukkan bahan organik secara berlapis. Kombinasikan bahan hijau (sisa sayuran) dengan bahan coklat (daun kering) dengan perbandingan 1:2.</p><p><strong>3. Jaga Kelembaban:</strong> Kompos harus lembab seperti spons yang diperas. Siram jika terlalu kering, tambahkan bahan coklat jika terlalu basah.</p><p><strong>4. Aduk Secara Berkala:</strong> Aduk kompos setiap 1-2 minggu sekali untuk aerasi dan mempercepat pembusukan.</p><p><strong>5. Waktu Matang:</strong> Kompos biasanya matang dalam 2-3 bulan. Kompos yang matang berwarna coklat tua, berbau tanah, dan bertekstur gembur.</p><h3>Menggunakan Kompos</h3><p>Kompos matang dapat digunakan sebagai pupuk untuk tanaman pot, kebun sayur, atau taman. Campurkan dengan tanah dengan perbandingan 1:3 untuk hasil optimal.</p>',
                'category' => 'Tutorial',
                'tags' => 'kompos, sampah organik, pupuk alami, pertanian',
                'is_published' => true,
            ],
            [
                'title' => 'Ekonomi Sirkular dalam Pengelolaan Sampah',
                'slug' => 'ekonomi-sirkular-dalam-pengelolaan-sampah',
                'excerpt' => 'Konsep ekonomi sirkular yang mengubah sampah menjadi sumber daya berharga.',
                'content' => '<h2>Apa Itu Ekonomi Sirkular?</h2><p>Ekonomi sirkular adalah model ekonomi yang bertujuan untuk meminimalkan limbah dan memaksimalkan penggunaan sumber daya. Dalam sistem ini, produk dirancang untuk dapat didaur ulang, diperbaiki, atau digunakan kembali.</p><h3>Prinsip Ekonomi Sirkular</h3><p><strong>1. Design Out Waste:</strong> Produk dirancang sejak awal untuk meminimalkan sampah, menggunakan bahan yang dapat didaur ulang.</p><p><strong>2. Keep Products in Use:</strong> Memperpanjang umur produk melalui perbaikan, reuse, dan refurbishment.</p><p><strong>3. Regenerate Natural Systems:</strong> Mengembalikan nutrisi ke tanah dan ekosistem melalui pengomposan dan praktik berkelanjutan lainnya.</p><h3>Peran Bank Sampah</h3><p>Bank sampah merupakan implementasi nyata dari ekonomi sirkular. Sampah yang dikumpulkan tidak berakhir di TPA, tetapi didaur ulang menjadi produk baru. Ini menciptakan nilai ekonomi dan lingkungan sekaligus.</p><h3>Contoh Implementasi</h3><p>Plastik bekas dapat didaur ulang menjadi paving block, tas, atau produk kerajinan. Kertas bekas menjadi kertas daur ulang. Logam bekas dilebur dan dijadikan produk baru. Semua proses ini menciptakan lapangan kerja dan mengurangi kebutuhan bahan baku baru.</p>',
                'category' => 'Informasi',
                'tags' => 'ekonomi sirkular, sustainability, daur ulang, green economy',
                'is_published' => true,
            ],
            [
                'title' => 'Tips Mengurangi Sampah di Kehidupan Sehari-hari',
                'slug' => 'tips-mengurangi-sampah-di-kehidupan-sehari-hari',
                'excerpt' => 'Langkah-langkah praktis untuk menerapkan gaya hidup minim sampah atau zero waste.',
                'content' => '<h2>Mulai Gaya Hidup Zero Waste</h2><p>Zero waste bukan berarti tidak menghasilkan sampah sama sekali, tetapi berusaha meminimalkan sampah sebisa mungkin. Berikut tips yang dapat Anda terapkan:</p><h3>Di Dapur</h3><p>• Gunakan wadah kaca atau stainless steel untuk menyimpan makanan</p><p>• Bawa tas belanja sendiri saat berbelanja</p><p>• Beli produk dalam kemasan besar untuk mengurangi kemasan</p><p>• Kompos sisa makanan dan sampah organik</p><p>• Hindari penggunaan sedotan plastik, gunakan sedotan stainless atau bambu</p><h3>Di Kamar Mandi</h3><p>• Gunakan sabun batangan tanpa kemasan plastik</p><p>• Ganti sikat gigi plastik dengan sikat gigi bambu</p><p>• Gunakan shampoo bar atau isi ulang</p><p>• Pilih produk perawatan dengan kemasan minimal</p><h3>Saat Bepergian</h3><p>• Bawa botol minum isi ulang</p><p>• Bawa wadah makanan sendiri</p><p>• Tolak kantong plastik sekali pakai</p><p>• Bawa peralatan makan sendiri</p><h3>Belanja Bijak</h3><p>• Beli hanya yang dibutuhkan</p><p>• Pilih produk berkualitas yang tahan lama</p><p>• Beli produk lokal untuk mengurangi jejak karbon</p><p>• Manfaatkan pasar tradisional yang minim kemasan</p><h3>Prinsip 5R</h3><p><strong>Refuse:</strong> Tolak yang tidak dibutuhkan</p><p><strong>Reduce:</strong> Kurangi konsumsi</p><p><strong>Reuse:</strong> Gunakan kembali</p><p><strong>Recycle:</strong> Daur ulang</p><p><strong>Rot:</strong> Kompos sampah organik</p>',
                'category' => 'Tutorial',
                'tags' => 'zero waste, gaya hidup, tips, reduce reuse recycle',
                'is_published' => true,
            ],
            [
                'title' => 'Peran Masyarakat dalam Pengelolaan Sampah Berkelanjutan',
                'slug' => 'peran-masyarakat-dalam-pengelolaan-sampah-berkelanjutan',
                'excerpt' => 'Bagaimana partisipasi aktif masyarakat dapat menciptakan lingkungan yang lebih bersih dan sehat.',
                'content' => '<h2>Pentingnya Partisipasi Masyarakat</h2><p>Pengelolaan sampah yang efektif tidak dapat dilakukan hanya oleh pemerintah atau perusahaan pengelola sampah. Dibutuhkan partisipasi aktif dari seluruh lapisan masyarakat.</p><h3>Tanggung Jawab Individual</h3><p>Setiap individu bertanggung jawab atas sampah yang dihasilkannya. Dimulai dari memilah sampah di sumber, mengurangi konsumsi produk sekali pakai, hingga aktif dalam program daur ulang dan bank sampah.</p><h3>Peran Keluarga</h3><p>Keluarga adalah unit terkecil dalam masyarakat. Pendidikan tentang pengelolaan sampah harus dimulai dari rumah. Orang tua dapat mengajarkan anak-anak tentang pemilahan sampah, mengurangi konsumsi plastik, dan pentingnya menjaga kebersihan lingkungan.</p><h3>Peran Komunitas</h3><p>Komunitas seperti RT/RW, sekolah, dan organisasi masyarakat dapat mengorganisir kegiatan kebersihan bersama, mendirikan bank sampah komunitas, dan mengadakan edukasi lingkungan secara berkala.</p><h3>Peran Dunia Usaha</h3><p>Perusahaan dapat berkontribusi dengan mengurangi kemasan produk, menggunakan bahan yang dapat didaur ulang, dan mendukung program CSR terkait pengelolaan sampah.</p><h3>Gerakan Kolektif</h3><p>Ketika semua elemen masyarakat bekerja sama, perubahan besar dapat terjadi. Contohnya adalah kampung-kampung yang sukses menjadi kampung bersih melalui program bank sampah dan pengelolaan sampah terpadu.</p>',
                'category' => 'Edukasi',
                'tags' => 'partisipasi masyarakat, komunitas, pengelolaan sampah, edukasi',
                'is_published' => true,
            ],
        ];

        foreach ($articles as $articleData) {
            Artikel::firstOrCreate(
                ['slug' => $articleData['slug']],
                [
                    'title' => $articleData['title'],
                    'slug' => $articleData['slug'],
                    'excerpt' => $articleData['excerpt'],
                    'content' => $articleData['content'],
                    'tags' => explode(', ', $articleData['tags']),
                    'author_id' => $admin->id,
                    'is_published' => $articleData['is_published'],
                    'published_at' => now(),
                ]
            );
        }

        $this->command->info('✅ Artikel seeder completed: ' . count($articles) . ' articles created.');
    }
}
