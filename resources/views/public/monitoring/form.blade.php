<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Hari ke-{{ $token->day_number }} — RSH Satu Bumi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .btn-option { transition: all 0.15s; }
        .btn-option.selected-green  { background:#15803d; color:#fff; border-color:#15803d; }
        .btn-option.selected-yellow { background:#ca8a04; color:#fff; border-color:#ca8a04; }
        .btn-option.selected-red    { background:#dc2626; color:#fff; border-color:#dc2626; }
        .dot-active   { background:#2d6a4f; }
        .dot-inactive { background:#d1d5db; }
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

    <form method="POST" action="{{ route('monitoring.store', $token->token) }}" id="monForm"
          x-data="monitoringForm()" @submit.prevent="submitForm">
        @csrf

        <div class="max-w-lg mx-auto px-4 py-6 space-y-8 pb-32">

            {{-- Instruction card --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-sm text-gray-600 leading-relaxed">
                Pilih jawaban yang paling sesuai dengan kondisi Anda hari ini.
                Tidak ada jawaban benar atau salah — isilah dengan jujur. 🙏
            </div>

            @foreach ($categories as $catKey => $category)
            <div>
                <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    @if($catKey === 'EMOSI')
                    <span class="text-xl">🧘</span>
                    @else
                    <span class="text-xl">💪</span>
                    @endif
                    {{ $category['label'] }}
                </h2>

                <div class="space-y-4">
                    @foreach ($category['questions'] as $qNum => $question)
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <p class="text-sm font-medium text-gray-800 mb-3">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[#2d6a4f]/10 text-[#2d6a4f] text-xs font-bold mr-1.5">{{ $qNum }}</span>
                            {{ $question['text'] }}
                        </p>
                        <div class="flex flex-col gap-2">
                            @foreach ($question['options'] as $value => $option)
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio"
                                       name="answers[{{ $catKey }}][{{ $qNum }}]"
                                       value="{{ $value }}"
                                       class="sr-only"
                                       required
                                       x-on:change="setAnswer('{{ $catKey }}', {{ $qNum }}, {{ $value }})">
                                <div class="btn-option flex-1 flex items-center gap-3 border-2 rounded-xl px-4 py-3
                                            @if($option['color'] === 'green') border-green-200 bg-green-50 hover:border-green-400
                                            @elseif($option['color'] === 'yellow') border-yellow-200 bg-yellow-50 hover:border-yellow-400
                                            @else border-red-200 bg-red-50 hover:border-red-400 @endif
                                            transition-all"
                                     :class="answers['{{ $catKey }}'] && answers['{{ $catKey }}'][{{ $qNum }}] == {{ $value }} ? 'selected-{{ $option['color'] }}' : ''">
                                    <span class="text-xl">
                                        @if($option['color'] === 'green') 🟢
                                        @elseif($option['color'] === 'yellow') 🟡
                                        @else 🔴 @endif
                                    </span>
                                    <span class="text-sm font-medium">{{ $option['label'] }}</span>
                                </div>
                            </label>
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
                {{-- Progress dots --}}
                <div class="flex items-center gap-1 mb-2 justify-center" id="progressDots"></div>

                <div x-show="error" class="text-xs text-red-600 text-center mb-2" x-text="error"></div>

                <button type="submit"
                        class="w-full py-3.5 bg-[#2d6a4f] text-white font-semibold rounded-xl text-sm hover:bg-[#1a5a3f] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="submitting">
                    <span x-show="!submitting">Kirim Monitoring →</span>
                    <span x-show="submitting">Menyimpan…</span>
                </button>
            </div>
        </div>

    </form>

<script>
function monitoringForm() {
    const categories = @json(array_keys($categories));
    const questionCounts = @json(array_map(fn($cat) => count($cat['questions']), $categories));

    // Build total question map: { EMOSI: [1..8], FISIK: [1..8] }
    const questionNums = @json(array_map(fn($cat) => array_keys($cat['questions']), $categories));

    return {
        answers: {},
        error: '',
        submitting: false,

        init() {
            // Initialize answer tracking
            categories.forEach(cat => { this.answers[cat] = {}; });
            this.renderDots();

            // Update dots reactively
            this.$watch('answers', () => this.renderDots(), { deep: true });
        },

        setAnswer(cat, qNum, value) {
            if (!this.answers[cat]) this.answers[cat] = {};
            this.answers[cat][qNum] = value;
            this.answers = { ...this.answers }; // trigger reactivity
        },

        totalAnswered() {
            let n = 0;
            categories.forEach((cat, i) => {
                const nums = questionNums[cat] || questionNums[i];
                if (nums) nums.forEach(q => {
                    if (this.answers[cat] && this.answers[cat][q] !== undefined) n++;
                });
            });
            return n;
        },

        totalQuestions() {
            let t = 0;
            Object.values(questionCounts).forEach(c => t += c);
            return t;
        },

        renderDots() {
            const container = document.getElementById('progressDots');
            if (!container) return;
            const answered = this.totalAnswered();
            const total = this.totalQuestions();
            let html = '';
            for (let i = 0; i < total; i++) {
                html += `<span class="inline-block w-2 h-2 rounded-full ${i < answered ? 'bg-[#2d6a4f]' : 'bg-gray-300'}"></span>`;
            }
            container.innerHTML = html;
        },

        submitForm() {
            const answered = this.totalAnswered();
            const total = this.totalQuestions();
            if (answered < total) {
                this.error = `Mohon isi semua pertanyaan. (${answered}/${total} terjawab)`;
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }
            this.error = '';
            this.submitting = true;
            document.getElementById('monForm').submit();
        },
    };
}
</script>
</body>
</html>
