@extends('layouts.public')
@section('title', 'Inspirator Kami')

@section('content')

{{-- Header --}}
<section class="relative overflow-hidden py-28 px-4 text-center">
    <img src="{{ asset('assets/green.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/75 via-green-900/65 to-green-950/80"></div>
    <div class="relative z-10">
        <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">Our Inspirator</span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white">Mā Archanā</h1>
        <p class="text-green-200 mt-2 text-lg">(Maya Safira Muchtar)</p>
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
                        <img src="{{ asset('assets/founder/ma.jpg') }}" alt="Mā Archanā" class="w-full h-full object-cover object-top">
                    </div>
                    <p class="text-center text-xs text-[#1a6b2f] font-semibold mt-2">Mā Archanā</p>
                </div>
                <div class="flex-1 space-y-4 text-gray-700 leading-relaxed text-sm">
                    <p>Mā Archanā, yang bernama asli Maya Safira Muchtar, adalah sosok yang menjadi inspirator utama di balik berdirinya Rumah Sehat Holistik Satu Bumi. Beliau mendedikasikan seluruh hidupnya untuk menghidupkan kembali ilmu-ilmu Usada Kuno yang masih sangat relevan bagi kehidupan manusia modern.</p>
                    <p>Lahir dan besar dalam lingkungan yang kaya akan tradisi dan kearifan lokal Nusantara, Mā Archanā tumbuh dengan kepekaan yang luar biasa terhadap kekayaan ilmu pengetahuan leluhur, khususnya dalam bidang kesehatan dan penyembuhan alami.</p>
                    <p>Ketertarikannya pada dunia kesehatan holistik membawanya pada perjalanan panjang mempelajari berbagai tradisi penyembuhan dari Nusantara dan Asia, termasuk Ayurveda dari India, berbagai tradisi herbal dan usada dari Jawa, Bali, dan kepulauan Nusantara lainnya, serta teknik-teknik meditasi dan prana healing.</p>
                </div>
            </div>
            <div class="space-y-4 text-gray-700 leading-relaxed text-sm">
                <p>Di bawah bimbingan Anand Krishna, Mā Archanā memperdalam pemahamannya tentang pendekatan holistik terhadap kesehatan — bahwa manusia bukan sekadar tubuh fisik, melainkan makhluk yang utuh yang terdiri dari tubuh, pikiran, perasaan, dan jiwa.</p>
                <p>Filosofi kesehatan Mā Archanā berpusat pada prinsip bahwa penyembuhan sejati harus menyentuh semua dimensi kemanusiaan. Tidak cukup hanya mengobati gejala fisik; kita harus mengenali dan menangani akar penyebab yang mungkin berada di level pikiran, emosi, atau bahkan jiwa.</p>
                <p>Mā Archanā juga dikenal sebagai praktisi L'Ayurveda — pendekatan Ayurveda yang telah disesuaikan dan diperkaya dengan kearifan lokal Nusantara dan wawasan modern tentang kesehatan.</p>
                <p>Warisan terbesar Mā Archanā adalah semangatnya yang tak pernah padam untuk berbagi ilmu dan pengalaman — keyakinannya bahwa setiap manusia berhak dan mampu mencapai kesehatan sejati yang holistik, dan bahwa kearifan leluhur yang kaya harus terus dihidupkan dan disesuaikan untuk menjawab tantangan zaman.</p>
                <p>Semangat dan visi Mā Archanā inilah yang menjadi jiwa dari Rumah Sehat Holistik Satu Bumi — sebuah tempat di mana kearifan kuno bertemu dengan kebutuhan modern, di mana setiap individu disambut dan dibantu dalam perjalanan mereka menuju kesehatan yang sesungguhnya.</p>
            </div>
        </div>

        {{-- Links --}}
        <div class="glass-card rounded-2xl p-6 sm:p-8">
            <h3 class="text-lg font-bold text-[#0d3d1a] mb-5">Tautan Terkait</h3>
            <div class="flex flex-wrap gap-3">
                @foreach([
                    ['label'=>"L'Ayurveda",'href'=>'https://layurveda.com'],
                    ['label'=>'Youtube Mā Archanā','href'=>'https://youtube.com/@maarchana'],
                ] as $link)
                <a href="{{ $link['href'] }}" target="_blank" rel="noopener noreferrer"
                   class="px-4 py-2 bg-green-50 border border-[#1a6b2f]/20 text-[#1a6b2f] font-semibold rounded-xl hover:bg-[#1a6b2f] hover:text-white transition-all text-sm">
                    {{ $link['label'] }} →
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

@endsection
