<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\MonitoringToken;
use App\Services\MonitoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class MonitoringController extends Controller
{
    public function __construct(private MonitoringService $service) {}

    /**
     * Friendly URL: /energylevel/{regId}/{day}/{sig}
     * Verifies HMAC signature, then delegates to show().
     */
    public function showByParams(int $regId, int $day, string $sig)
    {
        $expected = substr(
            hash_hmac('sha256', $regId . '-' . $day, config('app.key')),
            0, 24
        );
        if (!hash_equals($expected, $sig)) abort(404);

        $monitoringToken = MonitoringToken::where('registration_id', $regId)
            ->where('day_number', $day)
            ->firstOrFail();

        return $this->show($monitoringToken->token);
    }

    public function show(string $token)
    {
        $monitoringToken = MonitoringToken::where('token', $token)
            ->with('registration.programPeriod')
            ->firstOrFail();

        if ($monitoringToken->isCompleted()) {
            return view('public.monitoring.completed', [
                'token'        => $monitoringToken,
                'registration' => $monitoringToken->registration,
                'emosiScore'   => $monitoringToken->emosiScore(),
                'fisikScore'   => $monitoringToken->fisikScore(),
                'emosiLevel'   => $monitoringToken->emosiLevel(),
                'fisikLevel'   => $monitoringToken->fisikLevel(),
                'questions'    => config('monitoring.categories'),
                'responses'    => $monitoringToken->responses->keyBy(fn($r) => $r->category . '_' . $r->question_number),
            ]);
        }

        return view('public.monitoring.form', [
            'token'        => $monitoringToken,
            'registration' => $monitoringToken->registration,
            'categories'   => config('monitoring.categories'),
            'days'         => config('monitoring.days', 7),
        ]);
    }

    public function store(string $token, Request $request)
    {
        $monitoringToken = MonitoringToken::where('token', $token)
            ->with('registration')
            ->firstOrFail();

        if ($monitoringToken->isCompleted()) {
            return redirect()->route('energylevel.show', $token);
        }

        $key = 'energylevel:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return back()->withErrors(['rate' => 'Terlalu banyak percobaan. Coba lagi nanti.']);
        }
        RateLimiter::hit($key, 60);

        $categories = array_keys(config('monitoring.categories'));
        $rules = [];
        $expectedCount = 0;
        foreach ($categories as $cat) {
            $questions = config("monitoring.categories.{$cat}.questions");
            foreach (array_keys($questions) as $q) {
                $rules["answers.{$cat}.{$q}"] = 'required|integer|in:0,1,2';
                $expectedCount++;
            }
        }
        $request->validate($rules);

        $actualCount = collect($request->input('answers', []))->flatMap(fn($q) => $q)->count();
        if ($actualCount !== $expectedCount) {
            return back()->withErrors(['answers' => 'Semua pertanyaan harus dijawab sebelum mengirim.']);
        }

        $this->service->submitResponse($monitoringToken, $request->input('answers', []));

        return redirect()->route('energylevel.thankyou', $token);
    }

    public function thankyou(string $token)
    {
        $monitoringToken = MonitoringToken::where('token', $token)
            ->with('registration.programPeriod')
            ->firstOrFail();

        if (!$monitoringToken->isCompleted()) {
            return redirect()->route('energylevel.show', $token);
        }

        $emosiScore = $monitoringToken->emosiScore();
        $fisikScore = $monitoringToken->fisikScore();

        return view('public.monitoring.thankyou', [
            'token'        => $monitoringToken,
            'registration' => $monitoringToken->registration,
            'emosiScore'   => $emosiScore,
            'fisikScore'   => $fisikScore,
            'emosiLevel'   => $this->service->getLevel($emosiScore),
            'fisikLevel'   => $this->service->getLevel($fisikScore),
            'maxScore'     => config('monitoring.scoring.max_per_category'),
        ]);
    }
}
