# HealthRisk AI Implementation Plan

## Current baseline

The repository currently contains a Laravel 13 application with:

- PHP 8.3+ support, SQLite configuration, and Africa/Lagos timezone defaults.
- Laravel Breeze authentication using Livewire 3, Volt, Alpine.js, and Tailwind CSS.
- Chart.js installed through npm.
- ML service configuration at `ML_SERVICE_URL`.
- Passing baseline authentication tests and a successful Vite production build.

The implementation must remain a small, explainable, offline-capable prototype. Complete one vertical slice at a time and keep the application runnable after every phase.

## Delivery order

### Phase 1 — Restore project contracts and development workflow

1. Restore or add the project documentation required by the implementation:
   - requirements and acceptance criteria;
   - architecture and folder conventions;
   - database schema;
   - ML specification and API contract;
   - knowledge-base rules and recommendation templates;
   - UI specification and disclaimer wording;
   - development checklist and changelog.
2. Add a progress checklist and update it after each completed phase.
3. Add a README with Windows setup, offline demo startup, test commands, and demo accounts.
4. Confirm the repository has no secrets, datasets, virtual environments, or generated dependencies committed.

**Exit criteria:** documentation identifies the exact tables, columns, enums, routes, service payloads, and acceptance tests used by later phases.

### Phase 2 — Machine-learning service

1. Add `ml/requirements.txt`.
2. Add `ml/train.py`:
   - load and clean the BRFSS 2015 dataset;
   - map the documented features;
   - train Random Forest and Logistic Regression models for heart disease, diabetes, and hypertension;
   - use deterministic seed `42`;
   - support a clearly labelled synthetic fallback dataset when the Kaggle dataset is unavailable;
   - write three model artifacts, `model_meta.json`, and `metrics.json`.
3. Add `ml/service.py` with:
   - `GET /health`;
   - `POST /predict`;
   - strict request and response schemas;
   - model artifact loading at startup;
   - local-only binding on `127.0.0.1`.
4. Add pytest coverage for feature validation, prediction shape, metadata, and health status.
5. Verify the service manually with the documented example request.

**Exit criteria:** training, pytest, `/health`, and `/predict` work with both real and labelled synthetic artifacts.

### Phase 3 — Database and domain model

1. Add backed enums for roles, risk levels, severity, priority, recommendation status/category, goal metrics/status, and gender.
2. Add MySQL-compatible migrations for:
   - user profile fields and role;
   - biometric data;
   - lifestyle surveys;
   - risk assessments;
   - risk rules;
   - recommendation templates;
   - preventive recommendations;
   - health goals;
   - wearable sessions;
   - report shares;
   - usability feedback.
3. Add Eloquent models with explicit `$fillable`, casts, relationships, and factories.
4. Ensure `BiometricData` uses the required `biometric_data` table and computes BMI from height and weight.
5. Add empty seeder classes and register them in `DatabaseSeeder`.

**Exit criteria:** `php artisan migrate:fresh` works on SQLite and factory relationships can create a complete assessment graph.

### Phase 4 — Auth, roles, profile, and shared UI foundation

1. Add the `role` middleware and protect `/admin/*`.
2. Extend profile completion with date of birth, gender, and encrypted contact.
3. Enforce adult-only profiles and block check-ins until the profile is complete.
4. Add the responsive application layout, navigation, landing page, and footer.
5. Add reusable `risk-badge`, `stat-card`, `empty-state`, `section-card`, and `disclaimer` components.
6. Preserve CSRF, Breeze authentication, ownership policies, and server-side validation.

**Exit criteria:** registration leads to profile completion, incomplete users cannot start a check-in, and non-admin users receive 403 on admin routes.

### Phase 5 — Knowledge base and seeded accounts

1. Seed the documented risk rules verbatim.
2. Seed the documented recommendation templates verbatim.
3. Seed admin, provider, and demo user accounts with local-only passwords.
4. Add count and content tests for rules, templates, and accounts.

**Exit criteria:** a fresh seeded database contains the expected knowledge-base rows and demo accounts.

### Phase 6 — Assessment engine and fallback

1. Implement `FeatureMapper` for facts and the documented 14 model features.
2. Implement `MlClient` using the configured URL, timeout, retry, and health methods.
3. Implement `RiskScorer`, `MlRiskScorer`, `RuleBasedRiskScorer`, and `MlUnavailableException`.
4. Implement `RiskRuleEngine` using active database rules and null-safe comparisons.
5. Implement `RecommendationGenerator` using triggered rules, risk level, priorities, and templates.
6. Implement `RunAssessment` as the only assessment creation entry point:
   - validate input before execution;
   - save biometric and survey rows in one transaction;
   - attempt ML scoring;
   - fall back to rules on timeout, connection failure, 5xx, or invalid ML response;
   - handle known diagnoses;
   - save three condition results, overall result, factors, and recommendations.
7. Add the assessment policy.
8. Add focused unit and feature tests for boundaries, HTTP success, fallback, known conditions, and recommendation selection.

**Exit criteria:** a tinker or feature test creates an assessment with ML enabled and another with ML unavailable; both persist valid results.

### Phase 7 — Core user flow

1. Build the four-stage Livewire check-in wizard:
   - body measurements and live BMI;
   - lifestyle;
   - medical history;
   - review and submit.
2. Add per-step validation, preserved values, inline errors, and documented ranges.
3. Add the result page with:
   - overall risk;
   - three condition cards;
   - probability, percentile, and level;
   - risk factors and affected conditions;
   - grouped recommendations;
   - rationale and action controls;
   - ML fallback banner;
   - required disclaimer.
4. Add policy-protected assessment routes.

**Exit criteria:** register → profile → check-in → result works in the browser with the ML service both on and off.

### Phase 8 — Dashboard, history, and demo data

1. Add dashboard cards for latest scores, condition risks, recommendations, goals, and wearable summaries.
2. Add Chart.js trend charts for overall score, weight, and blood pressure.
3. Add empty states for users with no check-ins.
4. Add assessment history sorted newest first with change indicators.
5. Add assessment detail and comparison where time permits.
6. Add five monthly demo assessments with improving scores, simulated wearable data, and a goal.
7. Eager-load dashboard/history queries and verify no N+1 behavior.

**Exit criteria:** the demo user has meaningful trends and a new user sees a clear empty state and CTA.

### Phase 9 — Goals and wearable simulation

1. Add goal creation, update, abandon, progress calculation, and auto-achievement.
2. Support the documented metrics: weight, BMI, systolic blood pressure, fasting glucose, and resting heart rate.
3. Add wearable simulation producing seven sessions.
4. Add CSV import with validation, skipped-row counting, and source labels.
5. Add sessions table and average heart-rate chart.

**Exit criteria:** goals update after check-ins, simulated wearable data appears in charts, and malformed CSV rows are reported without aborting valid rows.

### Phase 10 — Reports and sharing

1. Add the consolidated, print-friendly report page.
2. Include profile summary, scores, factors, recommendations, disclaimer, and engine status.
3. Add expiring share links with random tokens, revoke support, optional provider email, and `noindex`.
4. Add the read-only public shared-report page.
5. Return a friendly 404 for expired or revoked links and record last-viewed time.

**Exit criteria:** print preview is usable, a logged-out user can open a valid share link, and revoked/expired links are inaccessible.

### Phase 11 — Admin and evaluation

1. Add the admin overview with user/assessment totals, risk distribution, and ML health status.
2. Add Model Performance using `ml/artifacts/metrics.json`:
   - RF vs LR metrics;
   - served model;
   - ROC chart;
   - confusion matrix;
   - feature importance;
   - dataset size and training date;
   - synthetic-data badge when applicable.
3. Add rule/template list, search, edit, and active toggles.
4. Add the usability feedback form and admin summary.
5. Protect every admin page and test non-admin 403 responses.

**Exit criteria:** the admin can demonstrate model evidence and feedback results without editing source files.

### Phase 12 — Hardening, packaging, and defence rehearsal

1. Run the complete PHP and Python test suites.
2. Perform a responsive review at 360 px, 768 px, and 1280 px.
3. Review loading, empty, validation, fallback, and error states.
4. Add `start-demo.bat` for Laravel, Vite, and FastAPI startup.
5. Run the manual smoke checklist with the ML service on and off.
6. Run `migrate:fresh --seed` on a clean database.
7. Capture model-performance and application screenshots.
8. Verify the seven-minute demo script twice.
9. Commit a final demo tag only after the clean-machine smoke test passes.

**Exit criteria:** the full P0 set is complete, the demo works offline, and all known limitations are documented honestly.

## Test gates

Every phase must pass the smallest relevant gate before the next phase starts:

- Laravel: `php artisan test`
- ML: `pytest` from `ml/`
- Frontend: `npm run build`
- Database: `php artisan migrate:fresh --seed`
- Manual: the affected flow at 360 px and desktop width

## Recommended implementation sequence

Implement phases in order. Within each phase, prefer this loop:

1. Read the relevant contract.
2. Add the smallest vertical slice.
3. Add a focused test that fails before the change.
4. Run the phase test gate.
5. Review the diff for naming and ownership errors.
6. Update progress/changelog.
7. Commit the phase before starting the next one.

Do not begin dashboard, reporting, or polish work until the assessment pipeline and fallback scorer are proven end to end.
