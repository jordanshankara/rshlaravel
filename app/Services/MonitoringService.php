<?php

namespace App\Services;

use App\Models\MonitoringToken;
use App\Models\MonitoringResponse;
use App\Models\ProgramPeriod;
use App\Models\Registration;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MonitoringService
{
    /**
     * Generate 7 monitoring tokens for a registration.
     * Safe to call multiple times — skips existing days.
     */
    public function generateTokens(Registration $registration): Collection
    {
        $days  = config('monitoring.days', 7);
        $tokens = collect();

        for ($day = 1; $day <= $days; $day++) {
            $token = MonitoringToken::firstOrCreate(
                ['registration_id' => $registration->id, 'day_number' => $day],
                ['token' => Str::random(48)]
            );
            $tokens->push($token);
        }

        return $tokens;
    }

    /**
     * Persist all 16 answers for a monitoring token.
     * $answers format: ['EMOSI' => [1 => 2, 2 => 1, ...], 'FISIK' => [...]]
     */
    public function submitResponse(MonitoringToken $token, array $answers): void
    {
        $rows = [];
        foreach ($answers as $category => $questions) {
            foreach ($questions as $qNum => $answer) {
                $rows[] = [
                    'monitoring_token_id' => $token->id,
                    'category'            => strtoupper($category),
                    'question_number'     => (int) $qNum,
                    'answer'              => (int) $answer,
                ];
            }
        }

        MonitoringResponse::upsert(
            $rows,
            ['monitoring_token_id', 'category', 'question_number'],
            ['answer']
        );

        $token->update(['completed_at' => now()]);
    }

    /**
     * Calculate score for one category on a completed token.
     */
    public function calculateScore(MonitoringToken $token, string $category): int
    {
        return (int) $token->responses()
            ->where('category', strtoupper($category))
            ->sum('answer');
    }

    /**
     * Resolve a score to its level config array.
     */
    public function getLevel(int $score): array
    {
        foreach (config('monitoring.scoring.levels') as $level) {
            if ($score >= $level['min'] && $score <= $level['max']) {
                return $level;
            }
        }
        return config('monitoring.scoring.levels')[2];
    }

    /**
     * Build a full summary for a program period — used by the dashboard.
     * Returns an array of participant rows, each with their 7-day scores.
     */
    public function getPeriodSummary(ProgramPeriod $period): array
    {
        $registrations = $period->registrations()
            ->where('is_present', true)
            ->with(['monitoringTokens.responses'])
            ->get();

        $days = config('monitoring.days', 7);
        $rows = [];

        foreach ($registrations as $reg) {
            $tokensByDay = $reg->monitoringTokens->keyBy('day_number');
            $dayData = [];

            for ($day = 1; $day <= $days; $day++) {
                $token = $tokensByDay->get($day);
                if ($token && $token->isCompleted()) {
                    $emosiScore = $token->emosiScore();
                    $fisikScore = $token->fisikScore();
                    $dayData[$day] = [
                        'completed'   => true,
                        'emosi_score' => $emosiScore,
                        'fisik_score' => $fisikScore,
                        'emosi_level' => $this->getLevel($emosiScore),
                        'fisik_level' => $this->getLevel($fisikScore),
                    ];
                } else {
                    $dayData[$day] = ['completed' => false];
                }
            }

            $rows[] = [
                'registration' => $reg,
                'days'         => $dayData,
            ];
        }

        return $rows;
    }

    /**
     * Compute aggregate average scores per day for a period (for the group chart).
     */
    public function getPeriodAggregates(ProgramPeriod $period): array
    {
        $days = config('monitoring.days', 7);
        $summary = $this->getPeriodSummary($period);
        $result = [];

        for ($day = 1; $day <= $days; $day++) {
            $emosiScores = [];
            $fisikScores = [];

            foreach ($summary as $row) {
                $d = $row['days'][$day] ?? null;
                if ($d && $d['completed']) {
                    $emosiScores[] = $d['emosi_score'];
                    $fisikScores[] = $d['fisik_score'];
                }
            }

            $result[$day] = [
                'emosi_avg' => count($emosiScores) ? round(array_sum($emosiScores) / count($emosiScores), 1) : null,
                'fisik_avg' => count($fisikScores) ? round(array_sum($fisikScores) / count($fisikScores), 1) : null,
                'count'     => count($emosiScores),
            ];
        }

        return $result;
    }
}
