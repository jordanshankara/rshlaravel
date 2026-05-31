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
     * Friendly URL: /monitoring/{regId}/{day}/{sig}
     * Verifies HMAC signature, then delegates to show().
     */
    public function showByParams(int $regId, int $day, string $sig)
    {
        $expected = substr(
            hash_hmac('sha256', $regId . '-' . $day, config('app.key')),
            0, 10
        );
        if (!hash_equals($expected, $sig)) abort(404);

        $monitoringToken = \App\Models\MonitoringToken::where('registration_id', $regId)
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
                'token'       => $monitoringToken,
                'registration' => $monitoringToken->registration,
                'emosiScore'  => $monitoringToken->emosiScore(),
                'fisikScore'  => $monitoringToken->fisikScore(),
                'emosiLevel'  => $monitoringToken->emosiLevel(),
                'fisikLevel'  => $monitoringToken->fisikLevel(),
                'questions'   => config('monitoring.categories'),
                'responses'   => $monitoringToken->responses->keyBy(fn($r) => $r->category . '_' . $r->question_number),
            ]);
        }

        return view('public.monitoring.form', [
            'token'        => $monitoringToken,
            'registration' => $monitoringToken->registration,
            'categories'   => config('monitoring.categories'),
            'maxScore'     => config('monitoring.scoring.max_per_category'),
        ]);
    }

    public function store(string $token, Request $request)
    {
        $monitoringToken = MonitoringToken::where('token', $token)
            ->with('registration')
            ->firstOrFail();

        if ($monitoringToken->isCompleted()) {
            return redirect()->route('monitoring.show', $token);
        }

        // Rate limit by IP
        $key = 'monitoring:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return back()->withErrors(['rate' => 'Terlalu banyak percobaan. Coba lagi nanti.']);
        }
        RateLimiter::hit($key, 60);

        // Validate all 16 answers
        $categories = array_keys(config('monitoring.categories'));
        $rules = [];
        foreach ($categories as $cat) {
            $questions = config("monitoring.categories.{$cat}.questions");
            foreach (array_keys($questions) as $q) {
                $rules["answers.{$cat}.{$q}"] = 'required|integer|in:0,1,2';
            }
        }
        $request->validate($rules);

        $this->service->submitResponse($monitoringToken, $request->input('answers', []));

        return redirect()->route('monitoring.thankyou', $token);
    }

    public function thankyou(string $token)
    {
        $monitoringToken = MonitoringToken::where('token', $token)
            ->with('registration.programPeriod')
            ->firstOrFail();

        if (!$monitoringToken->isCompleted()) {
            return redirect()->route('monitoring.show', $token);
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
