<?php

namespace App\Actions;

use App\Models\BiometricData;
use App\Models\LifestyleSurvey;
use App\Models\RiskAssessment;
use App\Models\User;
use App\Services\FeatureMapper;
use App\Services\RecommendationGenerator;
use App\Services\RiskRuleEngine;
use App\Services\Ml\MlClient;
use App\Services\Ml\MlRiskScorer;
use App\Services\Ml\RuleBasedRiskScorer;
use Illuminate\Support\Facades\DB;

class RunAssessment
{
    public function __construct(
        private readonly FeatureMapper $featureMapper,
        private readonly RiskRuleEngine $ruleEngine,
        private readonly MlClient $mlClient,
        private readonly MlRiskScorer $mlRiskScorer,
        private readonly RuleBasedRiskScorer $fallbackScorer,
        private readonly RecommendationGenerator $recommendationGenerator,
    ) {
    }

    public function execute(User $user, BiometricData $biometricData, LifestyleSurvey $survey): RiskAssessment
    {
        return DB::transaction(function () use ($user, $biometricData, $survey): RiskAssessment {
            $facts = [
                'bmi' => (float) $biometricData->bmi,
                'systolic' => $biometricData->systolic,
                'diastolic' => $biometricData->diastolic,
                'smoker' => $survey->smoker,
                'heavy_alcohol' => $survey->heavy_alcohol,
                'phys_activity' => $survey->phys_activity,
                'sleep_hours' => $survey->sleep_hours,
                'family_history' => $survey->family_history,
            ];
            $rules = $this->ruleEngine->evaluate($facts);
            $mlResults = $this->mlClient->predict($this->featureMapper->map($user, $biometricData, $survey));
            $conditionResults = $mlResults ?? $this->fallbackScorer->score($rules['scores']);
            $overall = $this->mlRiskScorer->overall($conditionResults);

            $assessment = RiskAssessment::create([
                'user_id' => $user->id,
                'biometric_data_id' => $biometricData->id,
                'engine' => $mlResults === null ? 'rule-based' : 'ml',
                'overall_score' => $overall['score'],
                'overall_level' => $overall['level'],
                'condition_results' => $conditionResults,
                'risk_factors' => $rules['factors'],
                'assessed_at' => now(),
            ]);

            $this->recommendationGenerator->generate($assessment, $rules['factors']);

            return $assessment;
        });
    }
}
