@extends('layouts.public')
@section('title', 'Sembuh dari Leukimia')

@section('content')

{{-- Header --}}
<section class="relative overflow-hidden py-28 px-4 text-center">
    <img src="{{ asset('assets/green.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/75 via-green-900/65 to-green-950/80"></div>
    <div class="relative z-10">
        <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">Anand Krishna</span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white">Sembuh dari Leukimia</h1>
    </div>
</section>

{{-- Intro + Video --}}
<section class="py-10 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="glass-card rounded-2xl overflow-hidden">
            {{-- YouTube click-to-play --}}
            <div x-data="{ playing: false }">
                <div x-show="!playing" @click="playing = true"
                     class="relative cursor-pointer group" style="display: block">
                    <img src="{{ asset('assets/thumbnail-leukemia.jpg') }}"
                         alt="Kisah Nyata Sembuh dari Leukemia"
                         class="w-full object-cover" style="aspect-ratio: 16/9">
                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors flex items-center justify-center">
                        <div class="w-20 h-20 rounded-full bg-white/90 flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-red-600 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div x-show="playing" style="display: none; aspect-ratio: 16/9">
                    <iframe x-bind:src="playing ? 'https://www.youtube.com/embed/mxEuf8Y5NqY?autoplay=1' : ''"
                            class="w-full h-full border-0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            title="Kisah Nyata Sembuh dari Leukemia (Kankar Darah) dengan Meditasi/Yoga — Anand Krishna"></iframe>
                </div>
            </div>
            <div class="p-6 sm:p-8 text-center">
                <p class="text-lg text-gray-600 leading-relaxed italic">Kisah nyata perjalanan Anand Krishna menghadapi leukemia dan menemukan makna sejati dari kesehatan holistik — sebuah perjalanan yang menginspirasi lahirnya Rumah Sehat Holistik Satu Bumi.</p>
            </div>
        </div>
    </div>
</section>

{{-- Story Sections --}}
<section class="py-12 px-4 bg-white relative overflow-hidden">
    <div class="blob absolute top-0 right-0 w-80 h-80 bg-green-100/50"></div>
    <div class="max-w-4xl mx-auto space-y-6 relative">
        @foreach([
            ['heading'=>'JATUH PINGSAN','content'=>['Suatu hari di tahun 1993, di tengah kesibukannya mengurus bisnis yang besar dan berkembang pesat, Anand Krishna tiba-tiba jatuh pingsan di kantornya. Ini bukan pingsan biasa - ketika ia siuman, para dokter menemukan sesuatu yang sangat mengkhawatirkan dalam hasil pemeriksaan darahnya.','Tubuh yang selama ini ia abaikan demi mengejar kesuksesan bisnis kini memberikan sinyal darurat yang tidak bisa diabaikan lagi. Para dokter memintanya untuk segera melakukan pemeriksaan lebih lanjut.']],
            ['heading'=>'LEUKIMIA (KANKER DARAH)','content'=>['Hasil pemeriksaan menyeluruh memastikan diagnosis yang mengerikan: Anand Krishna menderita leukemia, atau yang lebih dikenal sebagai kanker darah. Penyakit ini dikenal sebagai salah satu jenis kanker yang paling mematikan dan sulit ditangani.','Sel-sel darah putihnya tumbuh tak terkendali, menghancurkan sel-sel darah sehat yang ia butuhkan untuk bertahan hidup. Tubuhnya yang dulu kuat dan penuh energi kini mulai melemah dengan cepat.','Para dokter spesialis terbaik dari berbagai rumah sakit ternama memberikan diagnosis yang sama: leukemia stadium lanjut.']],
            ['heading'=>'BERAPA LAMA LAGI BISA BERTAHAN HIDUP?','content'=>['Pertanyaan yang paling menakutkan itu pun harus ia hadapi. Para dokter, dengan ekspresi yang penuh keprihatinan, memberikan perkiraan yang sangat suram.','"Mungkin beberapa bulan lagi," kata salah seorang dokter dengan hati-hati. "Kalaupun kita lakukan kemoterapi intensif, hasilnya belum tentu memuaskan. Stadium penyakitnya sudah cukup lanjut."','Anand Krishna duduk diam mendengar vonis itu. Di satu sisi, ada rasa takut yang wajar sebagai manusia. Di sisi lain, ada sesuatu dalam dirinya yang menolak untuk menyerah begitu saja.']],
            ['heading'=>'PERGI KE INDIA MENEMUI SANG GURU','content'=>['Dalam kondisi sakit parah itu, Anand Krishna memutuskan untuk pergi ke India menemui gurunya. Bagi sebagian orang, keputusan ini terdengar gila — mengapa tidak tinggal di Indonesia dan menjalani pengobatan medis yang tersedia?','Namun Anand Krishna memiliki keyakinan bahwa ada sesuatu yang lebih dalam yang perlu ia temukan - sesuatu yang melampaui sekadar pengobatan fisik.','Sang Guru menyambutnya dengan senyum hangat dan mata yang memancarkan kedamaian. "Kamu datang tepat waktu," kata Sang Guru.']],
            ['heading'=>'KEMBALI BISNIS DI JAKARTA','content'=>['Setelah pertemuannya dengan Sang Guru, Anand Krishna kembali ke Jakarta. Ia mencoba menjalani kehidupan bisnisnya seperti dulu, sambil tetap berjuang melawan penyakitnya.','Namun ia mulai merasakan bahwa ada yang berubah dalam dirinya. Prioritas-prioritas yang dulu tampak sangat penting kini terasa berbeda. Pertanyaan-pertanyaan yang dulu tidak pernah ia pikirkan kini menghantui pikirannya.']],
            ['heading'=>'MELEPASKAN PERUSAHAAN/PABRIK','content'=>['Keputusan besar itu akhirnya datang. Anand Krishna memutuskan untuk melepaskan seluruh kerajaan bisnisnya.','Bagi banyak orang di sekitarnya, ini adalah keputusan yang tidak masuk akal. Namun bagi Anand Krishna, melepaskan bisnis adalah langkah yang justru terasa membebaskan.','Ia mendistribusikan asetnya dan membulatkan tekad untuk memulai babak baru dalam hidupnya — babak yang berpusat pada pencarian makna sejati, bukan akumulasi materi.']],
            ['heading'=>'KEMBALI MENEMUI SANG GURU','content'=>['Dengan tangan yang kini kosong dari harta benda, namun hati yang terasa lebih ringan dari sebelumnya, Anand Krishna kembali menemui Sang Guru.','Sang Guru mengajarkan kepadanya berbagai teknik meditasi mendalam, pengetahuan tentang prana dan energi kehidupan, serta pemahaman tentang hubungan antara pikiran, tubuh, dan jiwa.','"Penyakitmu bukan musuhmu," kata Sang Guru. "Ia adalah gurumu yang paling keras. Dengarkan apa yang ingin ia sampaikan."']],
            ['heading'=>'BERTEMU DENGAN LAMA MISTERIUS','content'=>['Dalam perjalanannya di India dan Tibet, Anand Krishna bertemu dengan seorang Lama misterius yang memberikan petunjuk-petunjuk penting.','"Yang menahan kesembuhan bukanlah penyakitnya," kata Lama itu, "melainkan ketakutan dan penolakan terhadap kenyataan."','Kata-kata itu menghunjam jauh ke dalam diri Anand Krishna.']],
            ['heading'=>'TEMPAT YANG SANGAT INDAH UNTUK MATI','content'=>['Dalam perjalanannya melewati pegunungan Himalaya, Anand Krishna menemukan sebuah tempat yang sangat menakjubkan keindahannya — lembah yang tenang, dikelilingi puncak-puncak salju yang berkilau.','Di tempat itulah, untuk pertama kalinya, ia benar-benar berhadapan dengan kemungkinan kematiannya sendiri — bukan dengan rasa takut, melainkan dengan ketenangan yang aneh.','Paradoksnya, justru ketika ia bisa menerima kematian dengan damai itulah, sesuatu dalam dirinya mulai berubah.']],
            ['heading'=>'MENERIMA KEMATIAN DENGAN SENYUMAN','content'=>['Proses penerimaan itu bukan datang dalam sekejap. Ini adalah perjalanan batin yang panjang dan mendalam.','Perlahan-lahan, Anand Krishna berhasil mencapai kedamaian batin yang sesungguhnya. Ia menemukan bahwa ada dimensi keberadaan yang jauh melampaui tubuh — sesuatu yang tidak bisa dirusak oleh penyakit apapun, tidak bisa direnggut oleh kematian apapun.','Dengan pemahaman itu, ia bisa tersenyum menghadapi apapun yang akan datang.']],
            ['heading'=>'SEMBUH SECARA AJAIB','content'=>['Yang terjadi kemudian mengejutkan semua orang, termasuk para dokter yang telah memberikan vonis kematian.','Secara perlahan namun pasti, kondisi Anand Krishna mulai membaik. Sel-sel darahnya yang kacau mulai kembali normal. Para dokter tidak bisa memberikan penjelasan medis yang memuaskan untuk apa yang terjadi.','Kesembuhan itu bukan datang dari obat-obatan atau prosedur medis tertentu. Ini adalah kesembuhan holistik — yang menyentuh tubuh, pikiran, dan jiwa sekaligus.']],
            ['heading'=>'BERBAGI RASA','content'=>['Sembuh dari leukemia, Anand Krishna tidak kembali ke dunia bisnis. Ia telah menemukan panggilannya yang sesungguhnya: berbagi pengalaman dan kebijaksanaan yang ia peroleh dengan sebanyak mungkin orang.','Pengalamannya dengan leukemia menjadi fondasi dari filosofi kesehatan holistik yang ia ajarkan: bahwa kesehatan sejati mencakup tubuh, pikiran, dan jiwa; bahwa penyakit seringkali adalah pesan dari tubuh dan jiwa yang perlu didengarkan.','Inilah semangat yang kini hidup dalam Rumah Sehat Holistik Satu Bumi.','\"Saya sembuh bukan hanya dari leukemia,\" kata Anand Krishna, \"saya sembuh dari semua ilusi dan kesalahpahaman tentang apa sesungguhnya kehidupan ini. Dan itulah kesembuhan yang paling berharga.\"']],
        ] as $index => $section)
        <div class="glass-card rounded-2xl p-6 sm:p-8 border-l-4 border-[#52c273]">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#1a6b2f] to-[#52c273] flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">
                    {{ $index + 1 }}
                </div>
                <h2 class="text-base font-bold text-[#0d3d1a] uppercase tracking-wide">{{ $section['heading'] }}</h2>
            </div>
            <div class="space-y-3">
                @foreach($section['content'] as $para)
                <p class="text-gray-700 leading-relaxed text-sm">{{ $para }}</p>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="py-16 px-4 relative overflow-hidden" style="background: linear-gradient(135deg, #e8f5e9 0%, #d4edda 100%)">
    <div class="blob absolute top-0 right-0 w-64 h-64 bg-green-300/30"></div>
    <div class="max-w-4xl mx-auto text-center relative">
        <h3 class="text-2xl font-bold text-[#0d3d1a] mb-4">Rasakan Manfaat Pendekatan Holistik</h3>
        <p class="text-gray-700 mb-8 max-w-xl mx-auto text-sm leading-relaxed">Terinspirasi dari perjalanan kesembuhan Anand Krishna, Rumah Sehat Holistik Satu Bumi hadir untuk membantu Anda mencapai kesehatan sejati — tubuh, pikiran, dan jiwa.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('layanan') }}" class="px-8 py-3.5 bg-[#1a6b2f] text-white font-semibold rounded-xl hover:bg-[#0d3d1a] transition-all shadow-[0_4px_16px_rgba(26,107,47,0.3)] hover:-translate-y-0.5">
                Lihat Layanan Kami
            </a>
            <a href="{{ route('anand-krishna') }}" class="px-8 py-3.5 bg-[#f97316] text-white font-semibold rounded-xl hover:bg-[#ea6d0a] transition-all shadow-[0_4px_16px_rgba(249,115,22,0.3)] hover:-translate-y-0.5">
                Tentang Anand Krishna
            </a>
        </div>
    </div>
</section>

@endsection
