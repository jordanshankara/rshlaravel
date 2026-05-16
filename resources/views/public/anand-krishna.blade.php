@extends('layouts.public')
@section('title', 'Anand Krishna')

@section('content')

{{-- Header --}}
<section class="relative overflow-hidden py-28 px-4 text-center">
    <img src="{{ asset('assets/green.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/75 via-green-900/65 to-green-950/80"></div>
    <div class="relative z-10">
        <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">Pendiri &amp; Inspirator</span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white">Anand Krishna</h1>
    </div>
</section>

{{-- Content --}}
<section class="py-20 px-4 bg-white relative overflow-hidden">
    <div class="blob absolute top-10 right-10 w-80 h-80 bg-green-100/50"></div>
    <div class="blob absolute bottom-10 left-10 w-64 h-64 bg-emerald-100/40"></div>
    <div class="max-w-4xl mx-auto relative">
        <div class="glass-card rounded-3xl p-8 sm:p-10 mb-8">
            <div class="flex flex-col sm:flex-row gap-8 items-start mb-6">
                <div class="flex-shrink-0 mx-auto sm:mx-0">
                    <div class="relative w-44 h-44 rounded-full overflow-hidden ring-4 ring-[#52c273]/40 shadow-xl">
                        <img src="{{ asset('assets/founder/guruji.webp') }}" alt="Anand Krishna" class="w-full h-full object-cover object-top">
                    </div>
                    <p class="text-center text-xs text-[#1a6b2f] font-semibold mt-2">Anand Krishna</p>
                </div>
                <div class="flex-1 space-y-4 text-gray-700 leading-relaxed text-sm">
                    <p>Bangga dengan akar budayanya dari Peradaban dan Kebudayaan Sunda-Sindhu-Saraswati yang Gemilang, Anand Krishna dilahirkan di Solo, Jawa Tengah pada 1956.</p>
                    <p>Sejak usia belia, ia sudah menunjukkan bakat kepemimpinan yang luar biasa dalam setiap bidang yang ia geluti — dari dunia bisnis hingga spiritual. Tidak heran kalau dalam waktu singkat ia berhasil membangun sebuah kerajaan bisnis yang sangat sukses.</p>
                    <p>Namun, di balik kesuksesan itu, Anand Krishna menderita penyakit leukemia yang hampir merenggut nyawanya. Berhadapan dengan kematian, ia memutuskan untuk meninggalkan semua harta bendanya dan melakukan perjalanan spiritual yang mendalam.</p>
                </div>
            </div>
            <div class="space-y-4 text-gray-700 leading-relaxed text-sm">
                <p>Perjalanan itu membawanya ke berbagai penjuru dunia — India, Tibet, Nepal, Israel, Eropa — di mana ia berguru dengan para master spiritual dari berbagai tradisi. Pengalaman-pengalaman ini membentuk pandangannya yang unik tentang spiritualitas lintas batas agama dan budaya.</p>
                <p>Sembuh secara ajaib dari leukemia, Anand Krishna kembali ke Indonesia dengan misi baru: berbagi kebijaksanaan spiritual yang ia peroleh dengan masyarakat luas. Ia mendirikan Anand Ashram, pusat spiritualitas dan pengembangan diri yang kini memiliki cabang di berbagai kota di Indonesia.</p>
                <p>Hingga saat ini, Anand Krishna telah menulis lebih dari <strong class="text-[#0d3d1a]">170 buku</strong> tentang meditasi, yoga, filsafat timur, dan pengembangan diri dalam bahasa Indonesia dan Inggris. Buku-bukunya telah diterjemahkan ke berbagai bahasa dan dibaca oleh jutaan orang di seluruh dunia.</p>
                <p>Melalui program-program pelatihan dan meditasi yang ia kembangkan, jutaan orang telah merasakan transformasi nyata dalam kehidupan mereka — baik dari segi kesehatan fisik, keseimbangan mental, maupun pertumbuhan spiritual.</p>
                <p>Anand Krishna adalah pendiri Yayasan Anand Ashram, Yayasan Anand Krishna, Charter for Global Harmony, dan berbagai lembaga lainnya yang berfokus pada pengembangan manusia seutuhnya dan keharmonisan antar-umat beragama.</p>
                <p>Visinya sederhana namun mendalam: <strong class="text-[#1a6b2f]">Manusia Baru Indonesia</strong> — manusia yang sehat jiwa dan raga, cerdas, berkarakter, dan mampu memberikan kontribusi nyata bagi peradaban.</p>
            </div>
        </div>

        {{-- Buku --}}
        <div class="glass-card rounded-2xl p-6 sm:p-8 mb-6">
            <h3 class="text-lg font-bold text-[#0d3d1a] mb-2">Karya Buku</h3>
            <p class="text-gray-500 text-sm mb-5">Lebih dari 170 judul buku yang telah dibaca jutaan orang.</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
                @foreach([
                    ['img'=>'buku/neo-self.jpg','title'=>'Neo Self Empowerment','href'=>'https://www.booksindonesia.com/produk/anandas-neo-self-empowerment-seni-memberdaya-diri-bagi-orang-modern/'],
                    ['img'=>'buku/kundalini-yoga.jpg','title'=>'Kundalini Yoga','href'=>'https://www.booksindonesia.com/produk/kundalini-yoga-dalam-hidup-sehari-hari/'],
                    ['img'=>'buku/yoga-sutra.jpg','title'=>'Yoga Sutra Patanjali','href'=>'https://www.booksindonesia.com/produk/yoga-sutra-patanjali-bagi-orang-modern-2/'],
                    ['img'=>'buku/science-fear.jpg','title'=>'The Science of Fear Management','href'=>'https://www.booksindonesia.com/produk/the-science-of-fear-management-the-art-of-being-happy-2/'],
                ] as $buku)
                <a href="{{ $buku['href'] }}" target="_blank" rel="noopener noreferrer"
                   class="relative rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group block" style="aspect-ratio: 3/4">
                    <img src="{{ asset('assets/'.$buku['img']) }}" alt="{{ $buku['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </a>
                @endforeach
            </div>
            <a href="https://www.booksindonesia.com/" target="_blank" rel="noopener noreferrer"
               class="inline-block px-6 py-2.5 bg-[#1a6b2f] text-white font-semibold rounded-xl hover:bg-[#0d3d1a] transition-all text-sm shadow-[0_4px_12px_rgba(26,107,47,0.3)]">
                Selengkapnya →
            </a>
        </div>

        {{-- Links --}}
        <div class="glass-card rounded-2xl p-6 sm:p-8 mb-6">
            <h3 class="text-lg font-bold text-[#0d3d1a] mb-5">Tautan Terkait</h3>
            <div class="flex flex-wrap gap-3">
                @foreach([
                    ['label'=>'Anand Ashram Foundation','href'=>'https://anandashram.or.id'],
                    ['label'=>'Yayasan Pendidikan Anand Krishna','href'=>'https://anandkrishna.or.id'],
                    ['label'=>'Charter for Global Harmony','href'=>'https://charterforglobalharmony.com'],
                    ['label'=>'Anand Krishna Profile','href'=>'https://anandkrishna.org'],
                    ['label'=>'Anand Krishna Youtube','href'=>'https://youtube.com/@anandkrishna'],
                ] as $link)
                <a href="{{ $link['href'] }}" target="_blank" rel="noopener noreferrer"
                   class="px-4 py-2 bg-green-50 border border-[#1a6b2f]/20 text-[#1a6b2f] font-semibold rounded-xl hover:bg-[#1a6b2f] hover:text-white transition-all text-sm">
                    {{ $link['label'] }} →
                </a>
                @endforeach
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('sembuh-dari-leukimia') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-[#f97316] text-white font-semibold rounded-xl hover:bg-[#ea6d0a] transition-all shadow-[0_4px_12px_rgba(249,115,22,0.35)]">
                Baca kisah: Sembuh dari Leukimia →
            </a>
        </div>
    </div>
</section>

@endsection
