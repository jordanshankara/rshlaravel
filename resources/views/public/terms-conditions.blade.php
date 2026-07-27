@extends('layouts.public')
@section('title', 'Syarat & Ketentuan — RSH Satu Bumi')

@section('content')

@php
$sections = [
    ['id' => 'tujuan', 'label' => 'Tujuan Program'],
    ['id' => 'peserta', 'label' => 'Siapa yang Bisa Ikut'],
    ['id' => 'kewajiban-peserta', 'label' => 'Kewajiban Peserta'],
    ['id' => 'kewajiban-rsh', 'label' => 'Kewajiban RSH'],
    ['id' => 'batas-tanggung-jawab', 'label' => 'Batas Tanggung Jawab'],
    ['id' => 'hak-rsh', 'label' => 'Hak RSH'],
    ['id' => 'pembatalan', 'label' => 'Aturan Pembatalan'],
    ['id' => 'refund', 'label' => 'Kebijakan Refund'],
    ['id' => 'force-majeure', 'label' => 'Force Majeure'],
];

$tujuanDo = [
    'Memahami hubungan antara pola hidup, pola pikir, emosi, istirahat, aktivitas fisik, dan kesehatan tubuh.',
    'Meningkatkan kesadaran (self-awareness) terhadap kebiasaan yang dapat memengaruhi kondisi kesehatan.',
    'Mendorong peserta menerapkan pola makan yang lebih seimbang sesuai prinsip hidup sehat.',
    'Mendorong peserta meningkatkan kualitas tidur, pengelolaan stres, relaksasi, dan keseimbangan aktivitas sehari-hari.',
    'Membantu peserta membangun kebiasaan hidup sehat yang konsisten dan berkelanjutan.',
    'Memberikan edukasi mengenai berbagai pendekatan kesehatan holistik sebagai pelengkap upaya menjaga kesehatan.',
    'Memberikan pengalaman praktik berbagai metode relaksasi, latihan pernapasan, aktivitas fisik ringan, serta teknik pengelolaan stres yang aman sesuai kondisi peserta.',
    'Mendukung peserta dalam meningkatkan kualitas hidup melalui perubahan gaya hidup yang bertanggung jawab.',
];
$tujuanDont = [
    'Memberikan diagnosis penyakit.',
    'Menggantikan pemeriksaan oleh dokter atau tenaga kesehatan yang berwenang.',
    'Menggantikan terapi medis yang sedang dijalani peserta.',
    'Menjamin kesembuhan suatu penyakit.',
    'Menjanjikan hasil yang sama pada setiap peserta.',
];

$pesertaBoleh = [
    'Individu berusia 18 tahun ke atas yang mampu mengambil keputusan secara mandiri.',
    'Individu yang ingin meningkatkan kualitas kesehatan, kebugaran, atau kualitas hidup melalui perubahan gaya hidup.',
    'Individu yang memiliki keluhan kesehatan atau penyakit kronis yang telah mendapatkan diagnosis dari dokter dan ingin memperoleh edukasi mengenai penerapan gaya hidup sehat sebagai pendamping perawatan medis yang sedang dijalani.',
    'Individu yang bersedia mengikuti seluruh rangkaian program, tata tertib, dan arahan fasilitator.',
    'Individu yang telah mengisi formulir riwayat kesehatan secara lengkap dan benar.',
    'Individu yang dinyatakan layak mengikuti program berdasarkan proses screening yang dilakukan oleh RSH Satu Bumi.',
];
$pesertaEvaluasiKhusus = [
    'Berusia di bawah 18 tahun (dengan persetujuan orang tua atau wali).',
    'Berusia lanjut dengan keterbatasan fisik tertentu.',
    'Sedang hamil atau menyusui.',
    'Baru menjalani operasi.',
    'Memiliki penyakit kronis yang belum stabil.',
    'Memiliki keterbatasan mobilitas.',
    'Menggunakan alat bantu medis tertentu.',
    'Memiliki gangguan psikologis atau psikiatri yang sedang dalam penanganan.',
];
$pesertaTidakBisa = [
    'Kondisi kesehatannya dinilai tidak aman untuk mengikuti program.',
    'Membutuhkan penanganan medis darurat atau perawatan di fasilitas kesehatan.',
    'Memberikan informasi kesehatan yang tidak benar atau tidak lengkap.',
    'Tidak bersedia mematuhi tata tertib program.',
    'Menunjukkan perilaku yang dapat membahayakan dirinya sendiri, peserta lain, atau staf.',
];

$kewajibanPeserta = [
    ['title' => 'Memberikan Informasi yang Benar', 'desc' => 'Memberikan seluruh informasi mengenai kondisi kesehatan, riwayat penyakit, penggunaan obat, alergi, tindakan medis yang pernah dijalani, serta informasi lain yang berkaitan dengan kondisi kesehatan secara jujur, lengkap, dan akurat.'],
    ['title' => 'Melaporkan Perubahan Kondisi Kesehatan', 'desc' => 'Segera memberitahukan kepada fasilitator atau petugas apabila terjadi perubahan kondisi kesehatan sebelum maupun selama mengikuti program, termasuk apabila muncul keluhan, nyeri, pusing, sesak napas, perdarahan, atau keadaan lain yang dirasakan tidak biasa.'],
    ['title' => 'Mengikuti Arahan Program', 'desc' => 'Mengikuti seluruh jadwal, prosedur, tata tertib, dan arahan yang diberikan oleh fasilitator selama program berlangsung demi menjaga keamanan, ketertiban, dan efektivitas program.'],
    ['title' => 'Mengikuti Program Sesuai Kemampuan', 'desc' => 'Melaksanakan setiap aktivitas sesuai kemampuan fisik masing-masing serta segera menghentikan aktivitas apabila merasa tidak nyaman atau mengalami keluhan, kemudian melaporkannya kepada petugas.'],
    ['title' => 'Tetap Bertanggung Jawab atas Pengobatan yang Sedang Dijalani', 'desc' => 'Tetap mengikuti pengobatan, pemeriksaan, dan anjuran dari dokter atau tenaga kesehatan yang merawat, kecuali terdapat instruksi tertulis dari tenaga kesehatan yang berwenang.'],
    ['title' => 'Tidak Mengubah Penggunaan Obat Secara Sepihak', 'desc' => 'Tidak menghentikan, mengurangi, menambah, atau mengubah dosis obat yang diresepkan oleh dokter tanpa berkonsultasi terlebih dahulu dengan dokter yang merawat.'],
    ['title' => 'Menjaga Sikap dan Etika', 'desc' => 'Menghormati seluruh peserta, terapis, tenaga pendamping, dan staf RSH Satu Bumi serta menjaga ketertiban, kesopanan, dan kenyamanan selama program berlangsung.'],
    ['title' => 'Menjaga Kebersihan dan Keamanan', 'desc' => 'Menjaga kebersihan lingkungan, menggunakan fasilitas secara bertanggung jawab, serta mematuhi seluruh prosedur keselamatan yang berlaku.'],
    ['title' => 'Menjaga Kerahasiaan Peserta Lain', 'desc' => 'Tidak menyebarluaskan data pribadi, kondisi kesehatan, dokumentasi, maupun informasi milik peserta lain tanpa persetujuan yang bersangkutan.'],
    ['title' => 'Bertanggung Jawab atas Barang Pribadi', 'desc' => 'Menjaga sendiri barang-barang pribadi yang dibawa selama mengikuti program. RSH Satu Bumi tidak bertanggung jawab atas kehilangan atau kerusakan barang pribadi yang disebabkan oleh kelalaian peserta.'],
    ['title' => 'Mematuhi Seluruh Ketentuan Program', 'desc' => 'Mematuhi seluruh syarat dan ketentuan yang berlaku selama mengikuti Program RSH Satu Bumi.'],
    ['title' => 'Bersedia Menghentikan Keikutsertaan Apabila Diperlukan', 'desc' => 'Bersedia menghentikan sementara atau mengakhiri keikutsertaan dalam program apabila berdasarkan pertimbangan keselamatan, kondisi kesehatan, atau alasan operasional, RSH Satu Bumi menilai bahwa peserta tidak layak untuk melanjutkan program.'],
    ['title' => 'Bertanggung Jawab atas Kebenaran Data', 'desc' => 'Peserta bertanggung jawab sepenuhnya atas kebenaran seluruh data dan informasi yang diberikan kepada RSH Satu Bumi. Apabila di kemudian hari diketahui terdapat informasi yang tidak benar, tidak lengkap, atau disembunyikan sehingga menimbulkan risiko bagi peserta, peserta lain, maupun RSH Satu Bumi, maka segala akibat yang timbul menjadi tanggung jawab peserta sesuai ketentuan hukum yang berlaku.'],
];

$kewajibanRsh = [
    ['title' => 'Menyelenggarakan Program dengan Itikad Baik', 'desc' => 'Menyelenggarakan program secara profesional, bertanggung jawab, dan dengan itikad baik sesuai tujuan, ruang lingkup, serta standar operasional yang berlaku di RSH Satu Bumi.'],
    ['title' => 'Memberikan Edukasi dan Pendampingan', 'desc' => 'Memberikan edukasi, pendampingan, serta pembinaan mengenai pola hidup sehat secara holistik sesuai materi dan kurikulum program yang dipilih peserta.'],
    ['title' => 'Menjelaskan Program Secara Jelas', 'desc' => 'Memberikan informasi yang benar, jelas, dan mudah dipahami mengenai tujuan, manfaat, alur kegiatan, serta batasan program sebelum peserta mengikuti kegiatan.'],
    ['title' => 'Melakukan Skrining Awal', 'desc' => 'Melakukan penilaian awal terhadap kondisi peserta berdasarkan informasi yang diberikan, serta menentukan kelayakan peserta untuk mengikuti program demi menjaga keamanan dan keselamatan.'],
    ['title' => 'Menjaga Keselamatan Selama Program', 'desc' => 'Mengupayakan lingkungan program yang aman, tertib, dan kondusif serta menerapkan prosedur keselamatan yang wajar selama kegiatan berlangsung.'],
    ['title' => 'Menjaga Kerahasiaan Data Peserta', 'desc' => 'Menjaga kerahasiaan data pribadi dan informasi kesehatan peserta sesuai ketentuan peraturan perundang-undangan yang berlaku, kecuali diwajibkan oleh hukum atau atas persetujuan peserta.'],
    ['title' => 'Menggunakan Terapis yang Kompeten', 'desc' => 'Menugaskan Terapis atau tenaga pendamping yang telah mendapatkan pelatihan sesuai dengan tugas dan tanggung jawabnya dalam program.'],
    ['title' => 'Memberikan Kesempatan Bertanya', 'desc' => 'Memberikan kesempatan kepada peserta untuk memperoleh penjelasan mengenai pelaksanaan program sebelum maupun selama program berlangsung.'],
    ['title' => 'Menangani Keluhan Peserta', 'desc' => 'Menerima, mencatat, dan menindaklanjuti keluhan peserta sesuai prosedur penanganan pengaduan yang berlaku di RSH Satu Bumi.'],
    ['title' => 'Melakukan Rujukan Apabila Diperlukan', 'desc' => 'Apabila selama program ditemukan kondisi yang memerlukan pemeriksaan atau penanganan medis lebih lanjut, RSH Satu Bumi akan menyarankan peserta untuk berkonsultasi atau dirujuk kepada dokter atau fasilitas pelayanan kesehatan yang sesuai.'],
    ['title' => 'Menghormati Hak Peserta', 'desc' => 'Memperlakukan seluruh peserta secara adil, sopan, dan penuh penghormatan tanpa diskriminasi berdasarkan suku, agama, ras, jenis kelamin, usia, atau latar belakang lainnya.'],
    ['title' => 'Mematuhi Peraturan Perundang-undangan', 'desc' => 'Menyelenggarakan program sesuai dengan ketentuan hukum yang berlaku serta melakukan evaluasi dan perbaikan program secara berkelanjutan untuk meningkatkan mutu layanan.'],
];

$batasTanggungJawab = [
    ['title' => 'Ruang Lingkup Program', 'desc' => 'Program RSH Satu Bumi merupakan program edukasi, pendampingan, dan pembinaan pola hidup sehat secara holistik. Program ini tidak dimaksudkan sebagai layanan diagnosis, tindakan medis, atau pengobatan yang menggantikan pelayanan dokter atau fasilitas pelayanan kesehatan.'],
    ['title' => 'Tidak Menjamin Hasil Tertentu', 'desc' => 'RSH Satu Bumi tidak memberikan jaminan atau kepastian mengenai kesembuhan, perbaikan kondisi kesehatan, penurunan berat badan, normalisasi hasil pemeriksaan laboratorium, maupun hasil kesehatan tertentu. Hasil yang diperoleh setiap peserta dapat berbeda-beda bergantung pada kondisi kesehatan awal, kepatuhan terhadap program, gaya hidup, faktor biologis, serta faktor lain di luar kendali RSH Satu Bumi.'],
    ['title' => 'Keputusan Kesehatan Tetap Menjadi Tanggung Jawab Peserta', 'desc' => 'Peserta bertanggung jawab untuk berkonsultasi dengan dokter atau tenaga kesehatan yang merawat terkait kondisi kesehatannya, termasuk mengenai penggunaan obat, tindakan medis, maupun perubahan terapi. RSH Satu Bumi tidak bertanggung jawab atas keputusan peserta untuk menghentikan, mengurangi, menambah, atau mengubah pengobatan tanpa arahan dari tenaga kesehatan yang berwenang.'],
    ['title' => 'Kewajiban Memberikan Informasi yang Benar', 'desc' => 'RSH Satu Bumi tidak bertanggung jawab atas akibat yang timbul apabila peserta memberikan informasi kesehatan yang tidak benar, tidak lengkap, atau menyembunyikan kondisi yang dapat memengaruhi keamanan dan kelayakan mengikuti program.'],
    ['title' => 'Kepatuhan terhadap Program', 'desc' => 'RSH Satu Bumi tidak bertanggung jawab atas hasil atau risiko yang timbul akibat peserta tidak mengikuti arahan, tata tertib, prosedur keselamatan, atau ketentuan program yang telah disampaikan.'],
    ['title' => 'Kondisi di Luar Kendali RSH Satu Bumi', 'desc' => 'RSH Satu Bumi tidak bertanggung jawab atas keterlambatan, perubahan jadwal, pembatalan, atau gangguan pelaksanaan program yang disebabkan oleh keadaan kahar (force majeure), termasuk tetapi tidak terbatas pada bencana alam, kebakaran, wabah penyakit, gangguan keamanan, kebijakan pemerintah, pemadaman utilitas, atau keadaan lain di luar kendali yang wajar.'],
    ['title' => 'Barang Pribadi Peserta', 'desc' => 'Peserta bertanggung jawab atas keamanan barang pribadi yang dibawa selama mengikuti program. RSH Satu Bumi tidak bertanggung jawab atas kehilangan atau kerusakan barang pribadi yang disebabkan oleh kelalaian peserta atau pihak ketiga.'],
    ['title' => 'Rujukan Medis', 'desc' => 'Apabila selama program peserta memerlukan pemeriksaan atau penanganan medis lebih lanjut, RSH Satu Bumi berhak menghentikan sementara keikutsertaan peserta dan menyarankan peserta untuk mendapatkan pelayanan pada dokter atau fasilitas pelayanan kesehatan yang sesuai.'],
    ['title' => 'Hak Menghentikan Keikutsertaan', 'desc' => 'Demi menjaga keselamatan peserta maupun peserta lainnya, RSH Satu Bumi berhak menolak, menunda, membatasi, atau menghentikan keikutsertaan seseorang apabila berdasarkan pertimbangan yang wajar kondisi kesehatannya atau perilakunya dinilai tidak memungkinkan untuk melanjutkan program.'],
    ['title' => 'Batas Tanggung Jawab Hukum', 'desc' => 'Tidak ada ketentuan dalam dokumen ini yang dimaksudkan untuk membebaskan atau mengurangi tanggung jawab RSH Satu Bumi atas kerugian yang secara hukum timbul akibat kesalahan, kelalaian, atau pelanggaran kewajiban hukum yang dilakukan oleh RSH Satu Bumi. Klausul ini semata-mata bertujuan menjelaskan ruang lingkup layanan, pembagian tanggung jawab antara para pihak, serta batas-batas risiko yang secara wajar melekat pada partisipasi dalam program.'],
];

$hakRsh = [
    ['title' => 'Melakukan Skrining Peserta', 'desc' => 'Melakukan penilaian awal terhadap kondisi kesehatan dan kelayakan peserta sebelum program dimulai berdasarkan informasi yang diberikan oleh peserta.'],
    ['title' => 'Menerima, Menolak, Menunda, atau Menghentikan Keikutsertaan Peserta', 'desc' => 'Menerima, menolak, menunda, membatasi, atau menghentikan keikutsertaan peserta apabila berdasarkan pertimbangan yang wajar dinilai bahwa kondisi kesehatan, perilaku, atau keadaan lainnya dapat membahayakan peserta yang bersangkutan, peserta lain, fasilitator, maupun kelancaran program.'],
    ['title' => 'Meminta Informasi Tambahan', 'desc' => 'Meminta dokumen, riwayat kesehatan, hasil pemeriksaan, atau surat keterangan dari dokter apabila diperlukan untuk menilai kelayakan peserta mengikuti program.'],
    ['title' => 'Mengubah Jadwal Program', 'desc' => 'Mengubah jadwal, susunan kegiatan, terapis, metode pelaksanaan, atau lokasi program apabila diperlukan karena alasan operasional, keselamatan, keadaan kahar (force majeure), atau peningkatan mutu layanan, dengan pemberitahuan yang wajar kepada peserta.'],
    ['title' => 'Menghentikan Aktivitas Peserta Demi Keselamatan', 'desc' => 'Menghentikan sementara atau permanen keterlibatan peserta dalam suatu aktivitas apabila dipandang berpotensi menimbulkan risiko terhadap kesehatan atau keselamatan peserta maupun pihak lain.'],
    ['title' => 'Menolak Permintaan yang Bertentangan dengan Ketentuan', 'desc' => 'Menolak permintaan peserta yang bertentangan dengan ketentuan program, standar operasional, etika, atau peraturan perundang-undangan yang berlaku.'],
    ['title' => 'Menegakkan Tata Tertib', 'desc' => 'Memberikan teguran, pembatasan, atau mengakhiri keikutsertaan peserta yang melanggar tata tertib, mengganggu jalannya program, melakukan tindakan yang merugikan peserta lain, atau melakukan tindakan yang melanggar hukum.'],
    ['title' => 'Menolak Klaim yang Tidak Berdasar', 'desc' => 'Menolak klaim, tuntutan, atau permintaan ganti rugi yang tidak didukung oleh fakta, dokumen, atau ketentuan yang berlaku dalam perjanjian maupun peraturan perundang-undangan.'],
    ['title' => 'Menetapkan dan Menjalankan Kebijakan Operasional', 'desc' => 'Menyusun, mengubah, dan menerapkan standar operasional prosedur (SOP), tata tertib, serta kebijakan internal yang diperlukan untuk menjaga mutu layanan, keselamatan, keamanan, dan kelancaran penyelenggaraan program.'],
    ['title' => 'Melakukan Rujukan', 'desc' => 'Menyarankan atau merujuk peserta untuk memperoleh pemeriksaan atau penanganan oleh dokter atau fasilitas pelayanan kesehatan apabila berdasarkan penilaian yang wajar kondisi peserta memerlukan layanan di luar ruang lingkup program RSH Satu Bumi.'],
    ['title' => 'Mengelola Data Peserta', 'desc' => 'Mengumpulkan, menyimpan, menggunakan, dan mengelola data pribadi serta data kesehatan peserta sebatas yang diperlukan untuk pelaksanaan program, dengan tetap memperhatikan kerahasiaan data dan ketentuan peraturan perundang-undangan yang berlaku.'],
    ['title' => 'Menggunakan Dokumentasi Program', 'desc' => 'Mengambil dokumentasi kegiatan selama program berlangsung. Penggunaan foto, video, rekaman suara, atau testimoni peserta untuk keperluan publikasi, promosi, edukasi, atau pengembangan program hanya dilakukan berdasarkan persetujuan peserta sesuai ketentuan yang berlaku.'],
    ['title' => 'Menagihkan Kewajiban Pembayaran', 'desc' => 'Meminta peserta memenuhi seluruh kewajiban pembayaran sesuai ketentuan program sebelum mengikuti kegiatan, kecuali terdapat kesepakatan tertulis yang menyatakan lain.'],
    ['title' => 'Menjalankan Ketentuan Pengembalian Dana', 'desc' => 'Menetapkan dan melaksanakan kebijakan pembatalan, penjadwalan ulang, maupun pengembalian dana sesuai syarat dan ketentuan program yang telah disepakati bersama peserta.'],
    ['title' => 'Melindungi Kepentingan Hukum RSH Satu Bumi', 'desc' => 'Mengambil langkah administratif maupun hukum yang diperlukan untuk melindungi nama baik, aset, hak kekayaan intelektual, informasi rahasia, serta kepentingan hukum RSH Satu Bumi apabila terdapat tindakan yang merugikan atau melanggar hukum.'],
];

$pembatalanPasal = [
    ['title' => 'Ruang Lingkup', 'items' => ['Kebijakan ini mengatur tata cara pembatalan keikutsertaan peserta, penjadwalan ulang (reschedule), serta konsekuensi administratif yang timbul sehubungan dengan pembatalan Program RSH Satu Bumi.']],
    ['title' => 'Pembatalan oleh Peserta', 'items' => [
        'Peserta dapat mengajukan pembatalan keikutsertaan secara tertulis melalui media komunikasi resmi yang ditetapkan oleh RSH Satu Bumi.',
        'Permohonan pembatalan dianggap diterima sejak dikonfirmasi secara tertulis oleh RSH Satu Bumi.',
        'Pembatalan yang dilakukan peserta akan diproses sesuai ketentuan pengembalian dana (Refund Policy) yang berlaku.',
    ]],
    ['title' => 'Penjadwalan Ulang (Reschedule)', 'items' => [
        'Peserta dapat mengajukan penjadwalan ulang apabila terdapat alasan yang dapat dipertanggungjawabkan.',
        'Permohonan reschedule harus diajukan paling lambat 7 (tujuh) hari kalender sebelum tanggal pelaksanaan program, kecuali dalam keadaan darurat yang dapat dibuktikan.',
        'Penjadwalan ulang hanya dapat dilakukan 1 (satu) kali dalam jangka waktu paling lama 2 (dua) bulan sejak tanggal program semula.',
        'Apabila peserta tidak mengikuti jadwal pengganti tanpa pemberitahuan, keikutsertaan dianggap gugur dan mengikuti ketentuan Refund Policy yang berlaku.',
    ]],
    ['title' => 'Pembatalan oleh RSH Satu Bumi', 'items' => [
        'RSH Satu Bumi berhak membatalkan atau menunda pelaksanaan program apabila: jumlah peserta tidak memenuhi batas minimum penyelenggaraan program; terjadi keadaan kahar (force majeure); fasilitas atau lokasi program tidak dapat digunakan karena alasan keselamatan; kondisi kesehatan peserta berdasarkan hasil skrining dinilai tidak aman; atau terjadi keadaan lain yang dapat membahayakan keselamatan peserta atau kelancaran program.',
        'Dalam hal pembatalan oleh RSH Satu Bumi, peserta akan diberikan pilihan berupa penjadwalan ulang, pengalihan ke program lain yang setara, atau pengembalian dana sesuai ketentuan yang berlaku.',
    ]],
    ['title' => 'Peserta Tidak Hadir (No Show)', 'items' => [
        'Peserta yang tidak hadir pada hari pelaksanaan tanpa pemberitahuan sebelumnya dianggap mengundurkan diri.',
        'Ketidakhadiran tanpa pemberitahuan tidak menghapus kewajiban pembayaran yang telah disepakati dan hak peserta atas pengembalian dana mengikuti ketentuan Refund Policy.',
    ]],
    ['title' => 'Pengunduran Diri Setelah Program Dimulai', 'items' => [
        'Peserta yang mengundurkan diri setelah program dimulai dianggap telah menggunakan layanan program sesuai bagian yang telah diberikan.',
        'Biaya yang telah digunakan untuk penyelenggaraan program dapat diperhitungkan dalam penyelesaian administrasi sesuai Refund Policy.',
    ]],
    ['title' => 'Keadaan Darurat', 'items' => ['Dalam keadaan darurat yang dapat dibuktikan, seperti rawat inap atau kecelakaan serius, RSH Satu Bumi dapat mempertimbangkan pemberian reschedule atau bentuk penyelesaian lain berdasarkan kebijakan manajemen.']],
    ['title' => 'Ketentuan Penutup', 'items' => ['Seluruh keputusan mengenai pembatalan, penjadwalan ulang, maupun penyelesaian administratif dilakukan berdasarkan prinsip itikad baik, kewajaran, dan ketentuan hukum yang berlaku. RSH Satu Bumi mengutamakan penyelesaian secara musyawarah untuk mencapai kesepakatan yang adil bagi kedua belah pihak.']],
];

$refundTable = [
    ['waktu' => '≥ 30 hari sebelum program', 'besaran' => '90% dari pembayaran yang telah diterima'],
    ['waktu' => '14–29 hari sebelum program', 'besaran' => '75%'],
    ['waktu' => '7–13 hari sebelum program', 'besaran' => '50%'],
    ['waktu' => '3–6 hari sebelum program', 'besaran' => '25%'],
    ['waktu' => 'Kurang dari 72 jam sebelum program', 'besaran' => 'Tidak ada refund, kecuali ditentukan lain oleh manajemen'],
];

$refundPasal = [
    ['title' => 'Ketentuan Umum', 'items' => [
        'Pengembalian dana (refund) hanya dapat dilakukan sesuai dengan ketentuan dalam dokumen ini.',
        'Dengan melakukan pembayaran, peserta dianggap telah membaca, memahami, dan menyetujui Kebijakan Pengembalian Dana ini.',
        'Setiap permohonan refund harus diajukan secara tertulis kepada RSH Satu Bumi.',
    ]],
    ['title' => 'Pengembalian Dana Setelah Program Dimulai', 'items' => [
        'Setelah peserta mengikuti sebagian atau seluruh kegiatan program, biaya yang telah digunakan untuk penyelenggaraan program dianggap telah dimanfaatkan.',
        'Pengembalian dana setelah program dimulai akan diperhitungkan berdasarkan layanan yang telah diberikan dan biaya yang telah dikeluarkan oleh RSH Satu Bumi.',
        'Dalam keadaan tertentu, RSH Satu Bumi dapat memberikan reschedule, voucher, atau bentuk penyelesaian lain sebagai pengganti refund.',
    ]],
    ['title' => 'Pembatalan oleh RSH Satu Bumi', 'items' => ['Apabila program dibatalkan oleh RSH Satu Bumi karena alasan operasional atau keadaan lain yang bukan disebabkan oleh kesalahan peserta, peserta berhak memperoleh salah satu pilihan berikut: penjadwalan ulang ke program berikutnya; pengalihan ke program lain yang nilainya setara; atau pengembalian dana atas bagian pembayaran yang belum menjadi biaya penyelenggaraan program.']],
    ['title' => 'Kondisi yang Tidak Berhak Mendapat Refund', 'items' => [
        'Peserta memberikan data kesehatan yang tidak benar atau tidak lengkap.',
        'Peserta dikeluarkan dari program karena melanggar tata tertib.',
        'Peserta tidak hadir (no show) tanpa pemberitahuan.',
        'Peserta mengundurkan diri setelah sebagian besar layanan program diberikan.',
        'Peserta tidak mengikuti instruksi atau prosedur keselamatan sehingga keikutsertaannya dihentikan.',
    ]],
    ['title' => 'Keadaan Darurat', 'items' => ['Dalam kondisi luar biasa, seperti rawat inap atau kecelakaan serius yang dapat dibuktikan, RSH Satu Bumi dapat mempertimbangkan pemberian refund, reschedule, atau bentuk penyelesaian lain berdasarkan kebijakan manajemen.']],
    ['title' => 'Tata Cara Pengajuan Refund', 'items' => [
        'Permohonan refund diajukan secara tertulis melalui saluran komunikasi resmi RSH Satu Bumi.',
        'Permohonan wajib disertai identitas peserta, bukti pembayaran, alasan pengajuan refund, dan dokumen pendukung apabila diperlukan.',
        'RSH Satu Bumi akan melakukan verifikasi atas permohonan refund, dan keputusan disampaikan secara tertulis setelah proses verifikasi selesai.',
    ]],
    ['title' => 'Cara Pengembalian Dana', 'items' => [
        'Refund dilakukan melalui transfer ke rekening atas nama peserta atau pihak yang melakukan pembayaran, kecuali disepakati lain secara tertulis.',
        'Apabila terdapat biaya administrasi bank atau biaya transfer yang timbul akibat proses refund, biaya tersebut dapat diperhitungkan dalam jumlah dana yang dikembalikan.',
    ]],
    ['title' => 'Penyelesaian Perselisihan', 'items' => ['Apabila terjadi perbedaan pendapat mengenai refund, para pihak sepakat untuk terlebih dahulu menyelesaikannya melalui musyawarah dengan itikad baik sebelum menempuh upaya hukum sesuai ketentuan peraturan perundang-undangan yang berlaku.']],
];

$forceMajeurePasal = [
    ['title' => 'Pengertian', 'items' => ['Force Majeure (Keadaan Kahar) adalah setiap peristiwa atau keadaan luar biasa yang terjadi di luar kemampuan, kendali, dan kehendak para pihak, yang menyebabkan sebagian atau seluruh kewajiban dalam penyelenggaraan Program RSH Satu Bumi tidak dapat dilaksanakan untuk sementara waktu atau selamanya.']],
    ['title' => 'Keadaan yang Termasuk Force Majeure', 'items' => [
        'Gempa bumi, tsunami, banjir, tanah longsor, gunung meletus, angin puting beliung, kebakaran besar, atau bencana alam lainnya.',
        'Wabah penyakit, pandemi, atau kejadian luar biasa di bidang kesehatan masyarakat.',
        'Perang, kerusuhan, huru-hara, terorisme, sabotase, pemogokan massal, atau gangguan keamanan yang berdampak pada pelaksanaan program.',
        'Kebijakan atau peraturan pemerintah, pemerintah daerah, atau instansi yang berwenang yang mengakibatkan program tidak dapat dilaksanakan sebagaimana direncanakan.',
        'Gangguan utilitas penting, termasuk pemadaman listrik berskala besar, gangguan jaringan komunikasi atau internet, atau gangguan sistem teknologi di luar kendali RSH Satu Bumi.',
        'Kerusakan berat pada fasilitas penyelenggaraan program akibat kejadian di luar kendali RSH Satu Bumi.',
        'Keadaan darurat lain yang secara wajar tidak dapat diprediksi maupun dicegah oleh RSH Satu Bumi meskipun telah dilakukan upaya yang patut.',
    ]],
    ['title' => 'Akibat Force Majeure', 'items' => ['Apabila terjadi Force Majeure, RSH Satu Bumi berhak untuk: menunda pelaksanaan program; mengubah jadwal kegiatan; mengubah lokasi pelaksanaan program; mengubah metode pelaksanaan program termasuk menjadi daring (online) atau hybrid; menghentikan sementara program sampai keadaan memungkinkan untuk dilanjutkan; atau membatalkan program apabila keadaan berlangsung dalam waktu yang tidak memungkinkan program diselenggarakan.']],
    ['title' => 'Pemberitahuan', 'items' => ['RSH Satu Bumi akan memberitahukan kepada peserta mengenai terjadinya Force Majeure sesegera mungkin melalui media komunikasi resmi yang digunakan dalam penyelenggaraan program.']],
    ['title' => 'Penyelesaian Hak Peserta', 'items' => [
        'Penjadwalan ulang (reschedule);',
        'Pengalihan ke jadwal program berikutnya;',
        'Pengalihan ke program lain yang memiliki nilai setara;',
        'Voucher atau kredit program yang dapat digunakan dalam jangka waktu tertentu; atau',
        'Pengembalian dana sesuai Kebijakan Pengembalian Dana (Refund Policy) dan dengan memperhitungkan biaya penyelenggaraan yang telah dikeluarkan.',
    ]],
    ['title' => 'Pembebasan dari Wanprestasi', 'items' => ['Selama keadaan Force Majeure berlangsung, keterlambatan atau ketidakmampuan RSH Satu Bumi dalam melaksanakan sebagian atau seluruh kewajibannya tidak dianggap sebagai wanprestasi sepanjang keadaan tersebut benar-benar disebabkan oleh Force Majeure dan RSH Satu Bumi telah melakukan upaya yang wajar untuk mengurangi dampaknya.']],
    ['title' => 'Berakhirnya Force Majeure', 'items' => ['Setelah keadaan Force Majeure berakhir, RSH Satu Bumi akan melanjutkan pelaksanaan program sesuai kondisi yang memungkinkan atau menawarkan alternatif penyelesaian kepada peserta berdasarkan musyawarah dan itikad baik.']],
];
@endphp

{{-- Header --}}
<section class="relative overflow-hidden py-28 px-4 text-center">
    <img src="{{ asset('assets/green.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/75 via-green-900/65 to-green-950/80"></div>
    <div class="relative z-10">
        <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">Legal</span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3">Syarat &amp; Ketentuan</h1>
        <p class="text-green-200 text-lg max-w-2xl mx-auto">Ketentuan yang berlaku bagi setiap peserta Program Pemulihan Sehat Raga &amp; Jiwa di Rumah Sehat Holistik Satu Bumi</p>
    </div>
</section>

{{-- Sticky anchor nav --}}
<div class="sticky top-20 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex gap-1.5 overflow-x-auto py-3 no-scrollbar" style="scrollbar-width:none">
            @foreach($sections as $s)
            <a href="#{{ $s['id'] }}"
               class="shrink-0 px-3.5 py-1.5 text-xs font-medium rounded-full border border-gray-200 text-gray-600 hover:border-[#1a6b2f] hover:text-[#1a6b2f] hover:bg-[#e8f5e9] transition-colors whitespace-nowrap">
                {{ $s['label'] }}
            </a>
            @endforeach
        </div>
    </div>
</div>

<section class="py-16 px-4 bg-white relative overflow-hidden">
    <div class="blob absolute top-0 right-0 w-80 h-80 bg-green-100/40" style="pointer-events:none"></div>
    <div class="max-w-4xl mx-auto relative space-y-16">

        {{-- 1. Tujuan Program --}}
        <div id="tujuan" class="scroll-mt-36">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-2">1. Tujuan Program</h2>
            <p class="text-gray-600 text-sm mb-6 leading-relaxed">Rumah Sehat Holistik Satu Bumi hadir untuk mendampingi setiap peserta membangun fondasi kesehatan melalui edukasi, perubahan gaya hidup, dan pengembangan kesadaran diri, sehingga peserta memiliki kemampuan untuk merawat kesehatannya secara lebih mandiri, bertanggung jawab, dan berkelanjutan. Program ini berfokus pada peningkatan kualitas hidup melalui pendekatan holistik yang mencakup aspek fisik, mental, emosional, dan spiritual, tanpa menjanjikan hasil atau kesembuhan tertentu bagi setiap individu.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-sm font-bold text-[#1a6b2f] mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#1a6b2f] text-white flex items-center justify-center text-xs">✓</span>
                        Program ini bertujuan membantu peserta untuk:
                    </h3>
                    <ul class="space-y-2.5">
                        @foreach($tujuanDo as $item)
                        <li class="flex items-start gap-2.5 text-sm text-gray-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#52c273] mt-1.5 flex-shrink-0"></span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded-2xl p-6 bg-red-50/60 border border-red-100">
                    <h3 class="text-sm font-bold text-red-700 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center text-xs">✕</span>
                        Program RSH Satu Bumi tidak bertujuan untuk:
                    </h3>
                    <ul class="space-y-2.5">
                        @foreach($tujuanDont as $item)
                        <li class="flex items-start gap-2.5 text-sm text-gray-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 mt-1.5 flex-shrink-0"></span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6 mt-5">
                <h3 class="text-sm font-bold text-[#0d3d1a] mb-2">Tujuan Akhir Program</h3>
                <p class="text-sm text-gray-600 leading-relaxed mb-2">Keberhasilan program diukur dari komitmen dan kemampuan peserta dalam menerapkan perubahan gaya hidup sehat secara mandiri sesuai kondisi masing-masing.</p>
                <p class="text-sm text-gray-600 leading-relaxed">Hasil yang diperoleh setiap peserta dapat berbeda, bergantung pada berbagai faktor, termasuk kondisi kesehatan awal, kepatuhan terhadap program, gaya hidup, serta faktor biologis dan lingkungan.</p>
            </div>
        </div>

        {{-- 2. Siapa yang Bisa Ikut --}}
        <div id="peserta" class="scroll-mt-36">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-2">2. Siapa yang Bisa Mengikuti Program</h2>
            <p class="text-gray-600 text-sm mb-6 leading-relaxed">Program ini diperuntukkan bagi individu yang ingin memperoleh edukasi dan pendampingan dalam menerapkan pola hidup sehat secara holistik. Peserta yang dapat mengikuti program antara lain:</p>

            <div class="glass-card rounded-2xl p-6 mb-5">
                <h3 class="text-sm font-bold text-[#1a6b2f] mb-4">Peserta yang Dapat Mengikuti Program</h3>
                <ul class="space-y-2.5">
                    @foreach($pesertaBoleh as $item)
                    <li class="flex items-start gap-2.5 text-sm text-gray-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#52c273] mt-1.5 flex-shrink-0"></span>
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-2xl p-6 mb-5 bg-amber-50/60 border border-amber-100">
                <h3 class="text-sm font-bold text-amber-700 mb-4">Peserta yang Harus Mendapat Persetujuan atau Evaluasi Khusus</h3>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2.5">
                    @foreach($pesertaEvaluasiKhusus as $item)
                    <li class="flex items-start gap-2.5 text-sm text-gray-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mt-1.5 flex-shrink-0"></span>
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-2xl p-6 mb-5 bg-red-50/60 border border-red-100">
                <h3 class="text-sm font-bold text-red-700 mb-4">Peserta yang Tidak Dapat Mengikuti Program</h3>
                <p class="text-xs text-gray-500 mb-3">Demi keselamatan peserta dan kelancaran program, RSH Satu Bumi berhak menolak atau menunda keikutsertaan seseorang apabila:</p>
                <ul class="space-y-2.5">
                    @foreach($pesertaTidakBisa as $item)
                    <li class="flex items-start gap-2.5 text-sm text-gray-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-400 mt-1.5 flex-shrink-0"></span>
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <p class="text-sm text-gray-600 leading-relaxed">RSH Satu Bumi berhak melakukan proses skrining sebelum program dimulai serta berhak menerima, menunda, membatasi, atau menolak keikutsertaan peserta apabila berdasarkan pertimbangan keselamatan, kondisi kesehatan, atau alasan operasional dinilai tidak sesuai untuk mengikuti program. Keputusan tersebut merupakan bagian dari upaya menjaga keamanan dan kenyamanan seluruh peserta.</p>
        </div>

        {{-- 3. Kewajiban Peserta --}}
        <div id="kewajiban-peserta" class="scroll-mt-36">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-2">3. Kewajiban Peserta / Pasien</h2>
            <p class="text-gray-600 text-sm mb-6 leading-relaxed">Selama mengikuti Program RSH Satu Bumi, setiap peserta wajib:</p>
            <div class="space-y-3">
                @foreach($kewajibanPeserta as $i => $k)
                <div class="glass-card rounded-xl p-5 flex items-start gap-4">
                    <span class="w-7 h-7 rounded-full bg-[#1a6b2f] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                    <div>
                        <h4 class="text-sm font-bold text-[#0d3d1a] mb-1">{{ $k['title'] }}</h4>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $k['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 4. Kewajiban RSH --}}
        <div id="kewajiban-rsh" class="scroll-mt-36">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-2">4. Kewajiban RSH Satu Bumi</h2>
            <p class="text-gray-600 text-sm mb-6 leading-relaxed">Dalam penyelenggaraan program, RSH Satu Bumi berkewajiban untuk:</p>
            <div class="space-y-3">
                @foreach($kewajibanRsh as $i => $k)
                <div class="glass-card rounded-xl p-5 flex items-start gap-4">
                    <span class="w-7 h-7 rounded-full bg-[#2d9348] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                    <div>
                        <h4 class="text-sm font-bold text-[#0d3d1a] mb-1">{{ $k['title'] }}</h4>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $k['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 5. Batas Tanggung Jawab --}}
        <div id="batas-tanggung-jawab" class="scroll-mt-36">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-2">5. Batas Tanggung Jawab RSH Satu Bumi</h2>
            <p class="text-gray-600 text-sm mb-6 leading-relaxed">Ketentuan berikut menjelaskan ruang lingkup layanan dan pembagian tanggung jawab antara RSH Satu Bumi dan peserta:</p>
            <div class="space-y-3">
                @foreach($batasTanggungJawab as $i => $k)
                <div class="rounded-xl p-5 flex items-start gap-4 bg-gray-50 border border-gray-100">
                    <span class="w-7 h-7 rounded-full bg-gray-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                    <div>
                        <h4 class="text-sm font-bold text-[#0d3d1a] mb-1">{{ $k['title'] }}</h4>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $k['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 6. Hak RSH --}}
        <div id="hak-rsh" class="scroll-mt-36">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-2">6. Hak RSH Satu Bumi</h2>
            <p class="text-gray-600 text-sm mb-6 leading-relaxed">Dalam menyelenggarakan program, RSH Satu Bumi berhak untuk:</p>
            <div class="space-y-3">
                @foreach($hakRsh as $i => $k)
                <div class="glass-card rounded-xl p-5 flex items-start gap-4">
                    <span class="w-7 h-7 rounded-full bg-[#f97316] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                    <div>
                        <h4 class="text-sm font-bold text-[#0d3d1a] mb-1">{{ $k['title'] }}</h4>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $k['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 7. Aturan Pembatalan --}}
        <div id="pembatalan" class="scroll-mt-36">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-2">7. Kebijakan Pembatalan Program</h2>
            <p class="text-gray-600 text-sm mb-6 leading-relaxed">Kebijakan ini mengatur tata cara pembatalan keikutsertaan peserta, penjadwalan ulang (reschedule), serta konsekuensi administratif yang timbul sehubungan dengan pembatalan Program RSH Satu Bumi.</p>
            <div class="space-y-4">
                @foreach($pembatalanPasal as $i => $p)
                <div class="glass-card rounded-2xl p-6">
                    <h4 class="text-sm font-bold text-[#0d3d1a] mb-3">Pasal {{ $i + 1 }} — {{ $p['title'] }}</h4>
                    <ul class="space-y-2">
                        @foreach($p['items'] as $item)
                        <li class="flex items-start gap-2.5 text-sm text-gray-600 leading-relaxed">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#52c273] mt-1.5 flex-shrink-0"></span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 8. Refund --}}
        <div id="refund" class="scroll-mt-36">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-2">8. Kebijakan Pengembalian Dana (Refund Policy)</h2>
            <p class="text-gray-600 text-sm mb-6 leading-relaxed">Apabila peserta membatalkan keikutsertaan, maka besaran dana yang dapat dikembalikan adalah sebagai berikut:</p>

            <div class="glass-card rounded-2xl overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#0d3d1a] text-white">
                            <tr>
                                <th class="px-4 sm:px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Waktu Pembatalan</th>
                                <th class="px-4 sm:px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Besaran Refund</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($refundTable as $row)
                            <tr class="hover:bg-[#e8f5e9]/50 transition-colors">
                                <td class="px-4 sm:px-5 py-3.5 font-medium text-gray-800 whitespace-nowrap">{{ $row['waktu'] }}</td>
                                <td class="px-4 sm:px-5 py-3.5 text-gray-600">{{ $row['besaran'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($refundPasal as $i => $p)
                <div class="rounded-2xl p-6 bg-gray-50 border border-gray-100">
                    <h4 class="text-sm font-bold text-[#0d3d1a] mb-3">Pasal {{ $i + 1 }} — {{ $p['title'] }}</h4>
                    <ul class="space-y-2">
                        @foreach($p['items'] as $item)
                        <li class="flex items-start gap-2.5 text-sm text-gray-600 leading-relaxed">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#2d9348] mt-1.5 flex-shrink-0"></span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 9. Force Majeure --}}
        <div id="force-majeure" class="scroll-mt-36">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-2">9. Force Majeure (Keadaan Kahar)</h2>
            <p class="text-gray-600 text-sm mb-6 leading-relaxed">Ketentuan berikut mengatur kondisi di luar kendali para pihak yang dapat memengaruhi pelaksanaan Program RSH Satu Bumi.</p>
            <div class="space-y-4">
                @foreach($forceMajeurePasal as $i => $p)
                <div class="glass-card rounded-2xl p-6">
                    <h4 class="text-sm font-bold text-[#0d3d1a] mb-3">Pasal {{ $i + 1 }} — {{ $p['title'] }}</h4>
                    <ul class="space-y-2">
                        @foreach($p['items'] as $item)
                        <li class="flex items-start gap-2.5 text-sm text-gray-600 leading-relaxed">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#52c273] mt-1.5 flex-shrink-0"></span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Closing CTA --}}
        <div class="rounded-2xl p-8 text-center" style="background: linear-gradient(135deg, #0d3d1a, #1a6b2f)">
            <p class="text-white font-medium mb-1">Ada pertanyaan mengenai Syarat &amp; Ketentuan ini?</p>
            <p class="text-green-200 text-sm mb-5">Tim kami siap membantu menjelaskan lebih lanjut sebelum Anda mendaftar.</p>
            <a href="{{ route('kontak') }}" class="inline-block px-8 py-3.5 bg-[#f97316] text-white font-bold rounded-xl hover:bg-[#ea6d0a] transition-all shadow-[0_4px_16px_rgba(249,115,22,0.4)] hover:-translate-y-0.5">
                Hubungi Kami
            </a>
        </div>

    </div>
</section>

@endsection
