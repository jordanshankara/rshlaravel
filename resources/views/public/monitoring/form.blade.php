<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Energy Level Hari ke-{{ $token->day_number }} — RSH Satu Bumi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .opt { transition: all 0.12s ease; -webkit-tap-highlight-color: transparent; }
        .opt.chosen { background:#2d6a4f; color:#fff; border-color:#2d6a4f; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Header --}}
    <div class="bg-[#2d6a4f] text-white px-4 py-4 sticky top-0 z-10 shadow">
        <div class="max-w-lg mx-auto">
            <p class="text-xs text-green-200 mb-0.5">Rumah Sehat Holistik Satu Bumi</p>
            <h1 class="text-base font-bold leading-tight">{{ $registration->full_name }}</h1>
            <div class="flex items-center gap-3 mt-1">
                <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">
                    Hari ke-{{ $token->day_number }} dari {{ $days ?? 7 }}
                </span>
                @if($registration->programPeriod)
                <span class="text-xs text-green-200">{{ $registration->programPeriod->name }}</span>
                @endif
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('energylevel.store', $token->token) }}" id="monForm"
          x-data="elForm()">
        @csrf

        <div class="max-w-lg mx-auto px-4 py-6 space-y-8 pb-32">

            <div class="bg-white rounded-xl border border-gray-200 p-4 text-sm text-gray-600 leading-relaxed">
                Pilih jawaban yang paling sesuai dengan kondisi Anda hari ini.
                Tidak ada jawaban benar atau salah — isilah dengan jujur. 🙏
            </div>

            @foreach ($categories as $catKey => $category)
            <div>
                <h2 class="text-base font-bold text-gray-800 mb-4">{{ $category['label'] }}</h2>

                <div class="space-y-4">
                    @foreach ($category['questions'] as $qNum => $question)
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <p class="text-sm font-medium text-gray-700 mb-3">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gray-100 text-gray-500 text-xs font-bold mr-1.5">{{ $qNum }}</span>
                            {{ $question['text'] }}
                        </p>
                        <div class="flex flex-col gap-2">
                            @foreach ($question['options'] as $value => $option)
                            <input type="radio"
                                   name="answers[{{ $catKey }}][{{ $qNum }}]"
                                   value="{{ $value }}"
                                   id="opt_{{ $catKey }}_{{ $qNum }}_{{ $value }}"
                                   class="sr-only">
                            <div class="opt border border-gray-200 rounded-xl px-4 py-3 cursor-pointer select-none bg-white text-sm text-gray-700 font-medium"
                                 :class="answers['{{ $catKey }}'] && answers['{{ $catKey }}'][{{ $qNum }}] == {{ $value }} ? 'chosen' : 'hover:border-gray-400 hover:bg-gray-50'"
                                 @click="pick('{{ $catKey }}', {{ $qNum }}, {{ $value }})">
                                {{ $option['label'] }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>

        {{-- Sticky submit bar --}}
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-3 z-10 shadow-lg">
            <div class="max-w-lg mx-auto">
                <div class="flex items-center gap-1 mb-2 justify-center" id="progressDots"></div>
                <div x-show="error" class="text-xs text-red-600 text-center mb-2" x-text="error"></div>
                <button type="button" @click="submitForm()"
                        class="w-full py-3.5 bg-[#2d6a4f] text-white font-semibold rounded-xl text-sm hover:bg-[#1a5a3f] active:bg-[#0f3d2a] transition-colors"
                        :disabled="submitting">
                    <span x-show="!submitting">Kirim →</span>
                    <span x-show="submitting">Menyimpan…</span>
                </button>
            </div>
        </div>

    </form>

<script>
function elForm() {
    @php
        $catKeys  = array_keys($categories);
        $qNumsMap = array_map(fn($cat) => array_keys($cat['questions']), $categories);
        $qTotal   = array_sum(array_map(fn($cat) => count($cat['questions']), $categories));
    @endphp
    const catKeys  = @json($catKeys);
    const qNumsMap = @json($qNumsMap);
    const qTotal   = {{ $qTotal }};

    return {
        answers: {}, error: '', submitting: false,

        init() {
            catKeys.forEach(cat => { this.answers[cat] = {}; });
            this.renderDots();
            this.$watch('answers', () => this.renderDots(), { deep: true });
        },

        pick(cat, qNum, value) {
            if (!this.answers[cat]) this.answers[cat] = {};
            this.answers[cat][qNum] = value;
            this.answers = { ...this.answers };
            const r = document.getElementById('opt_' + cat + '_' + qNum + '_' + value);
            if (r) r.checked = true;
        },

        totalAnswered() {
            let n = 0;
            catKeys.forEach(cat => {
                (qNumsMap[cat] || []).forEach(q => {
                    if (this.answers[cat] && this.answers[cat][q] !== undefined) n++;
                });
            });
            return n;
        },

        renderDots() {
            const el = document.getElementById('progressDots');
            if (!el) return;
            const done = this.totalAnswered();
            el.innerHTML = Array.from({ length: qTotal }, (_, i) =>
                `<span class="inline-block w-2 h-2 rounded-full transition-colors ${i < done ? 'bg-[#2d6a4f]' : 'bg-gray-300'}"></span>`
            ).join('');
        },

        submitForm() {
            const done = this.totalAnswered();
            if (done < qTotal) {
                this.error = `Mohon isi semua pertanyaan. (${done}/${qTotal} terjawab)`;
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }
            this.error = ''; this.submitting = true;
            document.getElementById('monForm').submit();
        },
    };
}
</script>
</body>
</html>
