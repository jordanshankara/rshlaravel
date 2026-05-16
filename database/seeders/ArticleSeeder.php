<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'admin')->first();
        $authorId = $admin?->id ?? 1;

        foreach ($this->articles() as $data) {
            $catIds = [];
            foreach ($data['categories'] as $catName) {
                $cat = Category::firstOrCreate(['name' => $catName]);
                $catIds[] = $cat->id;
            }

            $article = Article::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title'        => $data['title'],
                    'excerpt'      => $data['excerpt'],
                    'content'      => $this->toHtml($data['content']),
                    'status'       => 'PUBLISHED',
                    'author_id'    => $authorId,
                    'published_at' => $data['dateISO'],
                ]
            );

            $article->categories()->sync($catIds);
        }
    }

    private function toHtml(array $blocks): string
    {
        $html = '';
        $listItems = [];

        $flushList = function () use (&$html, &$listItems) {
            if ($listItems) {
                $html .= '<ul>' . implode('', array_map(fn($i) => "<li>{$i}</li>", $listItems)) . '</ul>';
                $listItems = [];
            }
        };

        foreach ($blocks as $block) {
            // Skip hashtag lines (social tags)
            if (preg_match('/^#[A-Za-z]/', $block)) {
                continue;
            }

            if (str_starts_with($block, '### ')) {
                $flushList();
                $html .= '<h3>' . htmlspecialchars(substr($block, 4), ENT_QUOTES) . '</h3>';
            } elseif (str_starts_with($block, '## ')) {
                $flushList();
                $html .= '<h2>' . htmlspecialchars(substr($block, 3), ENT_QUOTES) . '</h2>';
            } elseif (str_starts_with($block, '- ')) {
                $listItems[] = htmlspecialchars(substr($block, 2), ENT_QUOTES);
            } else {
                $flushList();
                $html .= '<p>' . htmlspecialchars($block, ENT_QUOTES) . '</p>';
            }
        }

        $flushList();

        return $html;
    }

    private function articles(): array
    {
        return [
            [
                'slug' => 'diabetes-meningkat-pesat-di-dunia-indonesia-masuk-dalam-daftar-teratas-penderita',
                'title' => 'Diabetes Meningkat Pesat di Dunia, Indonesia Masuk dalam Daftar Teratas Penderita',
                'dateISO' => '2024-11-15',
                'categories' => ['Diabetes', 'Gaya Hidup Sehat'],
                'excerpt' => 'Jumlah penderita diabetes di dunia terus meningkat dengan cepat. Berdasarkan analisis global yang diterbitkan di jurnal The Lancet menjelang Hari Diabetes Sedunia, jumlah total orang dewasa yang menderita diabetes tipe 1 atau tipe 2 di dunia telah mencapai lebih dari 828 juta orang.',
                'content' => [
                    '## Jumlah Penderita Diabetes di Dunia Melonjak',
                    'Jumlah penderita diabetes di dunia terus meningkat dengan cepat. Berdasarkan analisis global yang diterbitkan di jurnal The Lancet menjelang Hari Diabetes Sedunia, jumlah total orang dewasa yang menderita diabetes tipe 1 atau tipe 2 di dunia telah mencapai lebih dari 828 juta orang. Angka ini lebih dari empat kali lipat jumlah pada tahun 1990, mencerminkan krisis kesehatan yang semakin mendalam di seluruh dunia, terutama di negara-negara miskin dan berkembang.',
                    'Di Indonesia, diperkirakan ada sekitar 25 juta orang dewasa yang hidup dengan diabetes, menempatkan negara ini sebagai salah satu dari lima negara dengan jumlah penderita diabetes tertinggi, bersama India, China, Amerika Serikat, dan Pakistan.',
                    '## Perubahan Umur dan Tingkat Keparahan',
                    'Di negara-negara berpenghasilan rendah, penderita diabetes cenderung lebih muda dan lebih rentan terhadap komplikasi jangka panjang akibat kurangnya akses terhadap pengobatan. Penelitian ini menemukan bahwa lebih dari setengah penderita diabetes di seluruh dunia, atau sekitar 445 juta orang dewasa, tidak menerima pengobatan pada tahun 2022.',
                    '## Penyebab Utama Peningkatan Angka Diabetes',
                    'Diabetes, terutama diabetes tipe 2, dipicu oleh berbagai faktor gaya hidup seperti obesitas, pola makan buruk, dan kurangnya aktivitas fisik.',
                    '- Gaya Hidup Modern yang Serba Instan – Kebiasaan mengonsumsi makanan cepat saji dan ultraprocessed telah mengakibatkan tubuh kekurangan nutrisi esensial.',
                    '- Stres Berkepanjangan – Tekanan hidup yang semakin tinggi di era modern membuat banyak orang rentan terhadap stres kronis.',
                    '- Kurangnya Aktivitas Fisik – Aktivitas fisik yang menurun, ditambah gaya hidup yang terlalu banyak duduk, turut menyumbang risiko diabetes.',
                    '- Ketidakseimbangan Emosi dan Pikiran – Kesehatan mental dan emosional sangat penting dalam mengelola penyakit kronis seperti diabetes.',
                    '## RSH Satu Bumi Sebagai Solusi Holistik untuk Diabetes',
                    'Sebagai pusat kesehatan holistik, RSH Satu Bumi menawarkan pendekatan yang berbeda dan komprehensif dalam menangani diabetes.',
                    '- Perbaikan Pola Makan: Program kami membantu klien mengenali dan beralih ke pola makan sehat yang seimbang.',
                    '- Teknik Relaksasi dan Pengelolaan Stres: Melalui meditasi, yoga, dan latihan pernapasan.',
                    '- Program Aktivitas Fisik yang Menyenangkan: Aktivitas fisik rutin yang menyenangkan dan mudah dilakukan setiap hari.',
                    '- Pendekatan Psikologis dan Emosional: Kami memberikan perhatian pada kesehatan mental dan emosional.',
                    '## Kesimpulan',
                    'Pandemi diabetes bukan hanya tentang angka statistik, tetapi tentang bagaimana kita merespons tantangan ini secara pribadi dan kolektif.',
                ],
            ],
            [
                'slug' => 'memahami-enzim-pencernaan-pentingnya-dan-cara-menjaganya',
                'title' => 'Memahami Enzim Pencernaan: Pentingnya dan Cara Menjaganya',
                'dateISO' => '2024-10-08',
                'categories' => ['Kesehatan Pencernaan', 'Gaya Hidup Sehat'],
                'excerpt' => 'Dalam keseharian, kita sering menikmati berbagai macam makanan tanpa menyadari betapa pentingnya enzim pencernaan dalam proses tersebut. Enzim pencernaan adalah protein yang diproduksi secara alami oleh tubuh untuk membantu memecah makanan menjadi nutrisi yang dapat diserap dan digunakan oleh tubuh.',
                'content' => [
                    'Dalam keseharian, kita sering menikmati berbagai macam makanan tanpa menyadari betapa pentingnya enzim pencernaan dalam proses tersebut. Enzim pencernaan adalah protein yang diproduksi secara alami oleh tubuh untuk membantu memecah makanan menjadi nutrisi yang dapat diserap dan digunakan oleh tubuh. Tanpa enzim ini, proses pencernaan akan terhambat.',
                    '## Fungsi Enzim Pencernaan dalam Tubuh',
                    'Enzim pencernaan memiliki peran krusial dalam membangun otot, menghancurkan racun, dan memecah partikel makanan selama proses pencernaan. Ada tiga jenis utama enzim pencernaan yang diproduksi oleh pankreas, yaitu amilase, protease, dan lipase.',
                    '## Akibat Kekurangan Enzim Pencernaan',
                    'Kekurangan enzim pencernaan dapat menimbulkan berbagai masalah kesehatan serius. Tanpa cukup enzim, makanan tidak dapat dicerna dengan baik, menyebabkan gangguan penyerapan nutrisi dan penumpukan makanan di perut.',
                    '## Cara Agar Tidak Kekurangan Enzim Pencernaan',
                    'Untuk mencegah kekurangan enzim pencernaan, ada beberapa langkah yang dapat diambil:',
                    '- Menerapkan Pola Makan Seimbang: Konsumsi makanan yang kaya akan serat, vitamin, dan mineral.',
                    '- Hindari Konsumsi Makanan Olahan: Makanan yang diproses berlebihan, tinggi gula, dan lemak jenuh dapat merusak pankreas.',
                    '- Rutin Berolahraga: Aktivitas fisik dapat meningkatkan metabolisme dan membantu menjaga kesehatan pankreas.',
                    '- Mengelola Stres: Stres kronis dapat mempengaruhi kesehatan pencernaan.',
                    '- Suplementasi Enzim Pencernaan: Bagi mereka yang memiliki kondisi medis tertentu, suplementasi mungkin diperlukan.',
                    '## Kesimpulan',
                    'Enzim pencernaan memainkan peran vital dalam menjaga kesehatan dan kesejahteraan tubuh. Dengan memahami fungsi dan pentingnya enzim ini, kita dapat mengambil langkah-langkah proaktif untuk menjaga produksi enzim pencernaan tetap optimal.',
                ],
            ],
            [
                'slug' => 'diabetes-dan-pengaruhnya-pada-penglihatan-memahami-risiko-dan-pencegahan',
                'title' => 'Diabetes dan Pengaruhnya pada Penglihatan: Memahami Risiko dan Pencegahan',
                'dateISO' => '2024-10-04',
                'categories' => ['Diabetes'],
                'excerpt' => 'Diabetes tidak hanya berdampak pada kadar gula darah seseorang, tetapi juga dapat menyebabkan berbagai masalah pada kesehatan mata. Diabetic eye disease adalah istilah untuk sekelompok gangguan mata yang dapat dialami oleh penderita diabetes.',
                'content' => [
                    'Diabetes tidak hanya berdampak pada kadar gula darah seseorang, tetapi juga dapat menyebabkan berbagai masalah pada kesehatan mata. Diabetic eye disease adalah istilah untuk sekelompok gangguan mata yang dapat dialami oleh penderita diabetes, termasuk retinopati diabetik, edema makula diabetik, glaukoma, dan katarak.',
                    '## Bagaimana Diabetes Mempengaruhi Mata?',
                    'Diabetes dapat merusak pembuluh darah kecil di retina—bagian belakang mata yang menangkap cahaya dan mengubahnya menjadi sinyal untuk otak. Tingginya kadar gula dalam darah dapat menyebabkan pembuluh darah ini melemah dan bocor.',
                    '## Gejala dan Tanda Awal Masalah Penglihatan',
                    'Meski sering tidak menunjukkan gejala dini, beberapa tanda yang perlu diwaspadai antara lain penglihatan kabur atau bergelombang, perubahan penglihatan yang sering, munculnya area gelap, sulit melihat warna, atau bintik hitam.',
                    '## Langkah Pencegahan dan Pengobatan',
                    'Untuk mencegah atau memperlambat diabetic eye disease, penting untuk menjaga kadar gula darah, tekanan darah, dan kolesterol dalam batas normal.',
                    '## Pendekatan Holistik di Rumah Sehat Holistik Satu Bumi',
                    'Di Rumah Sehat Holistik Satu Bumi, kami percaya bahwa kesehatan tubuh, termasuk pengelolaan diabetes, harus dikelola secara holistik dan menyeluruh. Kami tidak hanya fokus pada mengatasi gejala dari penyakit, tetapi lebih kepada mengatasi akarnya.',
                ],
            ],
            [
                'slug' => 'terungkap-gaya-hidup-tak-sehat-picu-tingginya-kasus-diabetes-di-yogyakarta',
                'title' => 'Terungkap! Gaya Hidup Tak Sehat Picu Tingginya Kasus Diabetes di Yogyakarta',
                'dateISO' => '2024-09-10',
                'categories' => ['Diabetes', 'Gaya Hidup Sehat'],
                'excerpt' => 'Diabetes melitus terus menjadi salah satu penyakit tidak menular yang paling mengkhawatirkan di Indonesia, dan Kota Yogyakarta tidak luput dari fenomena ini. Berdasarkan data terbaru dari Dinas Kesehatan Kota Yogyakarta, prevalensi diabetes di kota ini telah mencapai angka 4,9 persen pada akhir tahun 2023.',
                'content' => [
                    'Diabetes melitus terus menjadi salah satu penyakit tidak menular (PTM) yang paling mengkhawatirkan di Indonesia, dan Kota Yogyakarta tidak luput dari fenomena ini.',
                    '## 1. Peningkatan Prevalensi Diabetes di Yogyakarta',
                    'Kepala Dinas Kesehatan Kota Yogyakarta menyebut bahwa tingginya prevalensi diabetes melitus di Yogyakarta menunjukkan pola hidup yang kurang sehat di kalangan masyarakat, terutama mereka yang berada dalam usia produktif.',
                    '## 2. Dampak Diabetes pada Masyarakat dan Perusahaan',
                    'Diabetes melitus tidak hanya berdampak pada kualitas hidup individu, tetapi juga memberikan beban signifikan bagi perusahaan dan masyarakat secara keseluruhan.',
                    '## 3. Menjaga Pola Hidup Sehat Ala Rumah Sehat Holistik Satu Bumi',
                    'Untuk mencegah dan mengelola diabetes, serta meningkatkan kualitas hidup masyarakat, Rumah Sehat Holistik Satu Bumi menganjurkan pendekatan holistik.',
                    '- Konsumsi Makanan Sehat: Fokus pada makanan alami yang kaya serat seperti buah-buahan, sayuran, biji-bijian, dan protein rendah lemak.',
                    '- Aktivitas Fisik Teratur: Lakukan olahraga minimal 30 menit setiap hari.',
                    '- Manajemen Stres: Stres yang tidak dikelola dengan baik dapat mempengaruhi kadar gula darah.',
                    '- Rutin Cek Kesehatan: Deteksi dini sangat penting dalam pencegahan diabetes.',
                    '## Kesimpulan',
                    'Tingginya prevalensi diabetes di Yogyakarta menjadi peringatan bagi kita semua untuk lebih serius menangani pola hidup yang kurang sehat.',
                ],
            ],
            [
                'slug' => 'obesitas-ancaman-kesehatan-global-yang-terus-meningkat',
                'title' => 'Obesitas: Ancaman Kesehatan Global yang Terus Meningkat',
                'dateISO' => '2024-08-09',
                'categories' => ['Obesitas', 'Gaya Hidup Sehat'],
                'excerpt' => 'Obesitas telah menjadi salah satu masalah kesehatan utama di seluruh dunia, dengan prevalensi yang terus meningkat dari tahun ke tahun. Menurut studi terbaru yang dipublikasikan oleh The Lancet, lebih dari 1 miliar orang di seluruh dunia hidup dengan obesitas pada tahun 2022.',
                'content' => [
                    '## Pengenalan',
                    'Obesitas telah menjadi salah satu masalah kesehatan utama di seluruh dunia, dengan prevalensi yang terus meningkat dari tahun ke tahun. Menurut studi terbaru yang dipublikasikan oleh The Lancet, lebih dari 1 miliar orang di seluruh dunia hidup dengan obesitas pada tahun 2022.',
                    '## Obesitas Itu Apa?',
                    'Obesitas adalah kondisi medis kronis di mana seseorang memiliki kelebihan lemak tubuh yang dapat berdampak negatif pada kesehatannya. Biasanya, obesitas diukur dengan menggunakan Indeks Massa Tubuh (IMT).',
                    '## Faktor-Faktor Penyebab Obesitas',
                    '- Pola Makan Tidak Sehat: Konsumsi makanan tinggi kalori, terutama dari makanan olahan yang kaya lemak dan gula.',
                    '- Kurangnya Aktivitas Fisik: Gaya hidup yang semakin tidak aktif, terutama di perkotaan.',
                    '- Faktor Genetik: Beberapa orang mungkin lebih rentan terhadap obesitas karena faktor genetik.',
                    '- Lingkungan dan Sosial: Lingkungan yang mendukung ketersediaan makanan cepat saji.',
                    '## Pembahasan',
                    'Obesitas tidak hanya berdampak pada penampilan fisik, tetapi juga menimbulkan berbagai masalah kesehatan serius termasuk penyakit jantung, diabetes tipe 2, dan hipertensi.',
                    '## Solusi',
                    '- Peningkatan Edukasi dan Kesadaran: Masyarakat perlu diberikan edukasi mengenai pentingnya pola makan sehat.',
                    '- Regulasi dan Kebijakan: Pemerintah perlu menerapkan regulasi yang ketat untuk mengurangi ketersediaan makanan tidak sehat.',
                    '- Promosi Aktivitas Fisik: Penyediaan fasilitas umum seperti taman, jalur sepeda, dan pusat kebugaran.',
                    '## Penutup',
                    'Obesitas adalah krisis kesehatan global yang memerlukan perhatian serius dari berbagai pihak, termasuk pemerintah, masyarakat, dan sektor swasta.',
                ],
            ],
            [
                'slug' => 'mengatasi-konstipasi-gejala-penyebab-dan-solusinya',
                'title' => 'Mengatasi Konstipasi: Gejala, Penyebab, dan Solusinya',
                'dateISO' => '2024-08-07',
                'categories' => ['Kesehatan Pencernaan'],
                'excerpt' => 'Konstipasi adalah kondisi umum yang sering kali mengganggu kenyamanan dan kualitas hidup. Banyak orang mengalami konstipasi dari waktu ke waktu, tetapi ketika menjadi kronis, dapat menjadi masalah yang serius.',
                'content' => [
                    '## Introduction',
                    'Konstipasi adalah kondisi umum yang sering kali mengganggu kenyamanan dan kualitas hidup. Banyak orang mengalami konstipasi dari waktu ke waktu, tetapi ketika menjadi kronis, dapat menjadi masalah yang serius.',
                    '## Apa Itu Konstipasi?',
                    'Konstipasi, atau sembelit, adalah kondisi di mana seseorang mengalami kesulitan untuk buang air besar. Biasanya, seseorang dianggap mengalami konstipasi jika buang air besar kurang dari tiga kali dalam seminggu.',
                    '## Gejala Konstipasi',
                    '- Buang air besar yang jarang (kurang dari tiga kali seminggu)',
                    '- Tinja yang keras atau padat',
                    '- Merasa tidak puas setelah buang air besar',
                    '- Perut terasa kembung atau penuh',
                    '## Penyebab Konstipasi',
                    '- Diet rendah serat: Kurangnya asupan serat dalam makanan sehari-hari.',
                    '- Dehidrasi: Tidak cukup minum air dapat menyebabkan tinja menjadi keras.',
                    '- Kurangnya aktivitas fisik: Gaya hidup yang tidak aktif dapat memperlambat gerakan usus.',
                    '- Stres: Stres dan kecemasan dapat mempengaruhi fungsi usus.',
                    '## Solusi untuk Menyembuhkan Konstipasi',
                    '- Perbaiki pola makan: Tambahkan lebih banyak serat ke dalam diet Anda.',
                    '- Tingkatkan asupan cairan: Minum cukup air setiap hari.',
                    '- Berolahraga secara teratur: Aktivitas fisik dapat membantu merangsang gerakan usus.',
                    '- Kelola stres: Praktikkan teknik relaksasi seperti meditasi dan yoga.',
                    '## Yoga untuk Mengatasi Konstipasi',
                    'Yoga telah terbukti efektif dalam meningkatkan kesehatan pencernaan dan mengatasi konstipasi. Praktik yoga melibatkan postur dan teknik pernapasan yang dapat merangsang gerakan usus.',
                    '## Kesimpulan',
                    'Konstipasi adalah kondisi yang umum, tetapi dapat diatasi dengan perubahan gaya hidup dan perawatan yang tepat.',
                ],
            ],
            [
                'slug' => 'ultraprocessed-food-dan-diabetes-ancaman-tersembunyi-bagi-kesehatan',
                'title' => 'Ultraprocessed Food dan Diabetes: Ancaman Tersembunyi bagi Kesehatan',
                'dateISO' => '2024-07-30',
                'categories' => ['Diabetes', 'Nutrisi'],
                'excerpt' => 'Kita semua suka makanan yang praktis dan lezat, tetapi apakah kita sadar akan bahaya di balik makanan ultraprocessed? Makanan ultraprocessed telah menjadi bagian tak terpisahkan dari kehidupan modern kita.',
                'content' => [
                    '## Pendahuluan',
                    'Kita semua suka makanan yang praktis dan lezat, tetapi apakah kita sadar akan bahaya di balik makanan ultraprocessed? Makanan ultraprocessed telah menjadi bagian tak terpisahkan dari kehidupan modern kita. Namun, berbagai penelitian menunjukkan bahwa konsumsi makanan ini bisa meningkatkan risiko berbagai penyakit serius, termasuk diabetes tipe 2.',
                    '## Apa Itu Makanan Ultraprocessed?',
                    'Makanan ultraprocessed adalah produk makanan yang telah mengalami banyak proses industri, biasanya dengan penambahan bahan-bahan seperti gula, garam, lemak, dan aditif buatan.',
                    '## Hubungan antara Makanan Ultraprocessed dan Diabetes',
                    'Penelitian terbaru yang dipublikasikan dalam jurnal The BMJ menunjukkan bahwa konsumsi makanan ultraprocessed secara signifikan meningkatkan risiko diabetes tipe 2.',
                    '## Mengapa Makanan Ultraprocessed Berbahaya?',
                    'Makanan ultraprocessed biasanya tinggi kalori, gula tambahan, garam, dan rendah serat. Komponen-komponen ini diketahui berkontribusi pada peningkatan berat badan, obesitas, dan resistensi insulin.',
                    '## Cara Mengurangi Konsumsi Makanan Ultraprocessed',
                    '- Baca Label Produk: Pilih produk yang lebih sedikit diproses.',
                    '- Fokus pada Makanan Utuh: Sertakan lebih banyak buah-buahan, sayuran, kacang-kacangan.',
                    '- Hindari Minuman Manis: Ganti minuman manis dengan air putih atau teh tanpa gula.',
                    '- Memasak di Rumah: Memasak makanan di rumah memberikan kendali lebih besar atas bahan-bahan.',
                    '## Kesimpulan',
                    'Makanan ultraprocessed mungkin praktis dan lezat, tetapi risiko kesehatan yang ditimbulkannya tidak bisa diabaikan.',
                ],
            ],
            [
                'slug' => 'cuci-darah-usia-muda-fenomena-yang-mengkhawatirkan-dan-cara-mengatasinya',
                'title' => 'Cuci Darah Usia Muda: Fenomena yang Mengkhawatirkan dan Cara Mengatasinya',
                'dateISO' => '2024-07-11',
                'categories' => ['Kesehatan Ginjal', 'Gaya Hidup Sehat'],
                'excerpt' => 'Dalam beberapa tahun terakhir, kasus gagal ginjal kronis yang membutuhkan cuci darah semakin banyak terjadi pada usia produktif dan bahkan anak-anak. Fenomena ini menunjukkan bahwa gagal ginjal tidak hanya menyerang orang lanjut usia.',
                'content' => [
                    'Dalam beberapa tahun terakhir, kasus gagal ginjal kronis yang membutuhkan cuci darah semakin banyak terjadi pada usia produktif dan bahkan anak-anak.',
                    '## Penyebab Cuci Darah di Usia Muda',
                    'Salah satu penyebab utama meningkatnya kasus cuci darah di usia muda adalah gaya hidup yang tidak sehat, terutama konsumsi gula berlebih.',
                    '- Hipertensi: Tekanan darah tinggi dapat merusak pembuluh darah di ginjal.',
                    '- Kebiasaan Merokok dan Konsumsi Alkohol: Kedua kebiasaan ini mempercepat kerusakan ginjal.',
                    '- Kurangnya Asupan Air Putih: Dehidrasi dapat merusak ginjal secara perlahan.',
                    '- Pola Makan Tidak Sehat: Diet tinggi garam, lemak, dan rendah serat.',
                    '## Mengatasi dan Mencegah Gagal Ginjal di Usia Muda',
                    '- Diet Seimbang: Konsumsi lebih banyak buah dan sayur, kurangi gula, garam, dan lemak.',
                    '- Aktivitas Fisik: Lakukan olahraga secara teratur untuk menjaga kesehatan ginjal.',
                    '- Hindari Merokok dan Alkohol: Berhenti merokok dan mengurangi konsumsi alkohol.',
                    '- Rutin Minum Air Putih: Pastikan tubuh terhidrasi dengan baik.',
                    '## Solusi dari Rumah Sehat Holistik (RSH) Satu Bumi',
                    'RSH Satu Bumi menawarkan program "7 Hari Menuju Sehat Raga dan Jiwa" yang dirancang untuk membantu individu memulai gaya hidup sehat.',
                ],
            ],
            [
                'slug' => 'sisi-negatif-positif-dari-prediabetes',
                'title' => 'Sisi Negatif & Positif dari PreDiabetes',
                'dateISO' => '2024-07-05',
                'categories' => ['Diabetes', 'Gaya Hidup Sehat'],
                'excerpt' => 'Prediabetes adalah kondisi kesehatan yang sering kali luput dari perhatian namun memiliki implikasi serius bagi masa depan kesehatan seseorang. Kondisi ini ditandai dengan kadar gula darah yang lebih tinggi dari normal, tetapi belum cukup tinggi untuk didiagnosis sebagai diabetes tipe 2.',
                'content' => [
                    'Prediabetes adalah kondisi kesehatan yang sering kali luput dari perhatian namun memiliki implikasi serius bagi masa depan kesehatan seseorang.',
                    'Situasi prediabetes di Indonesia mencerminkan tren global yang mengkhawatirkan. Data dari International Diabetes Federation (IDF) menunjukkan peningkatan drastis jumlah penderita diabetes di dunia, dengan Indonesia menduduki peringkat kelima.',
                    'Kabar buruknya, kondisi prediabetes tidak boleh dianggap remeh. Jika dibiarkan tanpa penanganan yang tepat, prediabetes sangat mungkin berkembang menjadi diabetes tipe 2.',
                    'Prediabetes bukanlah vonis akhir. Dengan perubahan gaya hidup yang tepat, kondisi ini dapat dikendalikan dan bahkan diubah.',
                    'Menyadari kompleksitas tantangan ini, RSH Satu Bumi hadir dengan solusi holistik untuk membantu individu mengelola prediabetes secara efektif.',
                ],
            ],
            [
                'slug' => 'meditasi-dapat-membantu-mengatasi-insomnia',
                'title' => 'Meditasi Dapat Membantu Mengatasi Insomnia',
                'dateISO' => '2024-06-27',
                'categories' => ['Meditasi & Yoga', 'Insomnia'],
                'excerpt' => 'Meditasi telah dipraktikkan selama ribuan tahun, baik untuk pencerahan spiritual maupun manfaat kesehatan lainnya. Salah satu manfaat signifikan dari meditasi adalah kemampuannya untuk membantu meningkatkan kualitas tidur.',
                'content' => [
                    '## Pendahuluan',
                    'Meditasi telah dipraktikkan selama ribuan tahun, baik untuk pencerahan spiritual maupun manfaat kesehatan lainnya. Salah satu manfaat signifikan dari meditasi adalah kemampuannya untuk membantu meningkatkan kualitas tidur.',
                    '## Permasalahan dalam Hidup Akibat Insomnia',
                    '- Penurunan Produktivitas: Kurangnya tidur mengurangi konsentrasi dan daya ingat.',
                    '- Penurunan Kesehatan: Insomnia kronis meningkatkan risiko penyakit serius seperti diabetes dan penyakit jantung.',
                    '- Mood yang Menurun: Kekurangan tidur dapat menyebabkan perubahan mood.',
                    '- Gangguan Pola Makan: Kurang tidur mempengaruhi hormon yang mengatur rasa lapar.',
                    '## Bagaimana Meditasi Membantu Insomnia',
                    '- Reduksi Rasa Sakit: Meditasi dapat mengurangi rasa sakit kronis yang mungkin mengganggu tidur.',
                    '- Kesehatan Mental: Meditasi dapat meredakan kecemasan, depresi, dan stres.',
                    '- Persiapan Tubuh untuk Tidur: Meditasi membantu menurunkan detak jantung dan kadar kortisol.',
                    '## Solusi dari RSH Satu Bumi',
                    'Rumah Sehat Satu Bumi memahami pentingnya pendekatan holistik dalam mengatasi masalah tidur. Kami menawarkan program "7 Hari Menuju Sehat Raga dan Jiwa".',
                    '## Kesimpulan',
                    'Insomnia dapat memiliki dampak serius pada kesehatan jika tidak ditangani dengan baik. Meditasi adalah solusi alami yang efektif untuk membantu meningkatkan kualitas tidur.',
                ],
            ],
            [
                'slug' => 'mengatasi-insomnia-dengan-yoga',
                'title' => 'Mengatasi Insomnia dengan Yoga',
                'dateISO' => '2024-06-25',
                'categories' => ['Meditasi & Yoga', 'Insomnia'],
                'excerpt' => 'Insomnia adalah gangguan tidur yang sering kali mengganggu banyak orang, mempengaruhi kualitas hidup mereka secara keseluruhan. Salah satu cara alami dan efektif untuk mengatasi insomnia adalah melalui praktik yoga.',
                'content' => [
                    'Insomnia adalah gangguan tidur yang sering kali mengganggu banyak orang, mempengaruhi kualitas hidup mereka secara keseluruhan.',
                    '## Manfaat Yoga untuk Mengatasi Insomnia',
                    '### Mengurangi Insomnia',
                    'Penelitian menunjukkan bahwa berlatih yoga secara teratur dapat membantu mengelola gejala insomnia. Yoga membantu menenangkan pikiran dan tubuh.',
                    '### Mengurangi Stres',
                    'Stres adalah salah satu penyebab utama insomnia. Dalam sebuah survei nasional, ditemukan bahwa lebih dari 85% orang yang berlatih yoga merasa bahwa yoga membantu mengurangi tingkat stres mereka.',
                    '### Meningkatkan Kualitas Tidur dan Kualitas Hidup',
                    'Yoga merupakan alternatif alami untuk obat tidur yang sering diberikan kepada orang dewasa yang lebih tua.',
                    '## Tips dan Trik untuk Praktik Yoga Sebelum Tidur',
                    '- Rutinitas: Jadikan yoga sebagai bagian dari rutinitas malam Anda.',
                    '- Lingkungan: Ciptakan lingkungan yang tenang dan nyaman.',
                    '- Fleksibilitas: Tubuh mungkin lebih terbuka dan fleksibel di malam hari.',
                    '## Kesimpulan',
                    'Praktik yoga sebelum tidur adalah cara alami yang efektif untuk mengatasi insomnia. Dengan mengurangi stres dan ketegangan, serta meningkatkan kualitas tidur, yoga memberikan solusi yang sehat dan holistik.',
                ],
            ],
            [
                'slug' => 'gaya-hidup-berperan-dalam-penularan-diabetes',
                'title' => 'Gaya Hidup Berperan dalam Penularan Diabetes',
                'dateISO' => '2024-06-25',
                'categories' => ['Diabetes', 'Gaya Hidup Sehat'],
                'excerpt' => 'Seringkali, ketika kita berbicara tentang diabetes, perhatian utama tertuju pada faktor genetik. Namun, meskipun genetik memainkan peran dalam predisposisi terhadap diabetes, gaya hidup keluarga memiliki dampak yang lebih signifikan.',
                'content' => [
                    '## Pengantar',
                    'Seringkali, ketika kita berbicara tentang diabetes, perhatian utama tertuju pada faktor genetik. Namun, meskipun genetik memainkan peran dalam predisposisi terhadap diabetes, gaya hidup keluarga memiliki dampak yang lebih signifikan.',
                    '## Diabetes: Lebih dari Sekadar Genetika',
                    'Diabetes tipe 2 adalah jenis diabetes yang paling umum dan sering dikaitkan dengan gaya hidup dan pola makan yang tidak sehat.',
                    '## Dampak Gaya Hidup terhadap Diabetes',
                    '- Pola Makan: Konsumsi makanan tinggi gula dan lemak dapat meningkatkan risiko diabetes.',
                    '- Aktivitas Fisik: Kurangnya aktivitas fisik dapat menyebabkan obesitas.',
                    '- Stres: Stres kronis dapat mempengaruhi kadar gula darah dan insulin.',
                    '- Kebiasaan Tidur: Tidur yang tidak cukup dapat mempengaruhi metabolisme.',
                    '## Membangun Gaya Hidup Sehat untuk Mencegah Diabetes',
                    '- Pola Makan Sehat: Mengkonsumsi makanan yang seimbang dengan banyak sayuran dan buah-buahan.',
                    '- Aktivitas Fisik Teratur: Berolahraga minimal 30 menit setiap hari.',
                    '- Manajemen Stres: Menggunakan teknik relaksasi seperti meditasi dan yoga.',
                    '- Tidur yang Cukup: Memastikan mendapatkan tidur berkualitas selama 7-9 jam setiap malam.',
                    '## Kesimpulan',
                    'Meskipun genetik memainkan peran dalam risiko diabetes, gaya hidup keluarga memiliki dampak yang jauh lebih besar.',
                ],
            ],
            [
                'slug' => 'pengobatan-holistik-apakah-hanya-tentang-herbal-makanan-sehat',
                'title' => 'Pengobatan Holistik Apakah Hanya Tentang Herbal & Makanan Sehat?',
                'dateISO' => '2024-06-25',
                'categories' => ['Pengobatan Holistik'],
                'excerpt' => 'Apa yang terbayang oleh Anda pertama kali ketika mendengar kata pengobatan holistik? Apakah jamu? Makanan sehat? Rempah-rempah? Tidak salah, namun belum tepat sepenuhnya. Pengobatan holistik lebih luas dari itu, mencakup semua lapisan manusia seutuhnya.',
                'content' => [
                    'Apa yang terbayang oleh Anda pertama kali ketika mendengar kata pengobatan holistik? Apakah jamu? Makanan sehat? Rempah-rempah? Tidak salah, namun belum tepat sepenuhnya.',
                    'Pengobatan Holistik sering diasosiasikannya hanya dengan pengobatan alami, ramuan herbal, dan perubahan pola makan. Memang benar bahwa elemen-elemen tersebut merupakan bagian dari pengobatan holistik, tetapi itu hanyalah sebagian kecil dari keseluruhan pendekatan.',
                    '## Pertanyaan Penting',
                    'Lalu, apakah benar pengobatan holistik hanya identik dengan herbal dan makanan sehat? Untuk menjawab pertanyaan ini, kita harus memahami definisi holistik.',
                    '## Memahami Manusia Secara Holistik',
                    'Menurut Guruji Anand Krishna, manusia memiliki lima lapisan kesadaran yang saling mempengaruhi:',
                    '- Lapisan Fisik: Ditentukan oleh makanan yang dikonsumsi.',
                    '- Lapisan Energi/Psikis: Diperoleh dari alam sekitar melalui pernapasan dan aktivitas fisik.',
                    '- Lapisan Mental/Emosional: Pikiran yang kacau bisa mengacaukan pernapasan dan kesehatan fisik.',
                    '- Lapisan Intelejensia: Kemampuan untuk membedakan yang baik dan buruk bagi kita.',
                    '- Lapisan Kesadaran Spiritual: Kemampuan melihat kehadiran ilahi dalam setiap ciptaan.',
                    '## Pendekatan Holistik yang Komprehensif',
                    'Jika ada yang menawarkan pengobatan holistik namun hanya memberikan herbal dan makanan sehat saja, itu kurang tepat. Pengobatan holistik harus mencakup seluruh lapisan manusia.',
                    '## Pengobatan Holistik di Rumah Sehat Satu Bumi',
                    'Rumah Sehat Satu Bumi memahami pentingnya pendekatan holistik yang komprehensif. Kami menawarkan pengobatan yang mencakup manusia seutuhnya.',
                    '## Kesimpulan',
                    'Dengan memahami arti holistik yang sebenarnya, kita sekarang tahu bahwa pengobatan holistik bukan hanya tentang herbal dan makanan sehat, tetapi harus mencakup manusia secara utuh.',
                ],
            ],
        ];
    }
}
