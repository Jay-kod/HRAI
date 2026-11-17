# Architecture

## Flow

Browser → Livewire components → Actions/Services → SQLite and knowledge-base tables. The Laravel assessment service calls FastAPI over HTTP and falls back to the rule scorer when the service is unavailable.

## Required classes

- `App\Actions\RunAssessment`
- `App\Services\FeatureMapper`
- `App\Services\Ml\MlClient`
- `App\Services\Ml\MlRiskScorer`
- `App\Services\Ml\RuleBasedRiskScorer`
- `App\Services\RiskRuleEngine`
- `App\Services\RecommendationGenerator`
- `App\Services\WearableImporter`
- `App\Services\WearableSimulator`
- `App\Policies\RiskAssessmentPolicy`
- `App\Support\Metrics`

Controllers and Livewire components validate, authorize, call these classes, and render responses. They must not contain assessment or recommendation business logic.

## ML configuration

`config/services.php` exposes:

```php
'ml' => [
    'url' => env('ML_SERVICE_URL', 'http://127.0.0.1:8001'),
    'artifacts' => base_path('ml/artifacts'),
],
```
