# Database Contract

Use explicit fillable fields, casts, relationships, factories, and backed enums.

## Tables

- `users`: Laravel auth fields plus `role`, `date_of_birth`, `gender`, and encrypted `contact`.
- `biometric_data`: `user_id`, `assessed_at`, `height_cm`, `weight_kg`, `bmi`, `systolic`, `diastolic`, `fasting_glucose_mgdl`, `resting_heart_rate`.
- `lifestyle_surveys`: `user_id`, `biometric_data_id`, smoking, alcohol, activity, diet, sleep, stress, family history, stroke, and diagnosed conditions.
- `risk_assessments`: `user_id`, `biometric_data_id`, `engine`, `overall_score`, `overall_level`, `condition_results`, `risk_factors`, `assessed_at`.
- `risk_rules`: code, label, condition scope, fact key, operator, threshold, severity, points, and active state.
- `recommendation_templates`: code, title, body, rationale, priority, category, goal metric, trigger codes, and active state.
- `preventive_recommendations`: `risk_assessment_id`, template code, title, advice, rationale, priority, status, and completed timestamp.
- `health_goals`: `user_id`, metric, target, start value, status, and dates.
- `wearable_sessions`: `user_id`, source, session date, average heart rate, steps, sleep hours, and raw data.
- `report_shares`: `user_id`, `risk_assessment_id`, token, provider email, expires timestamp, revoked timestamp, and last viewed timestamp.
- `usability_feedback`: `user_id`, Likert responses, comment, and submitted timestamp.

Migrations must work on SQLite and MySQL.
