# AGENTS.md — HealthRisk AI

Final-year project: "Development of a Personal AI-Based Health Risk Assessment and Preventive Care Recommendation System".
Goal: a working, demo-able prototype the student can defend in 3 days. A clean working demo beats completeness and hardening.

## Stack (do not change)
- Laravel (latest stable, PHP 8.3+), Blade + Livewire 3 + Alpine.js + Tailwind CSS. Laravel Breeze (Livewire stack) for auth.
- SQLite for dev/demo (migrations must stay MySQL-compatible).
- Chart.js (npm) for charts.
- ML: Python 3.11+, scikit-learn, FastAPI + uvicorn, in `/ml`. Laravel calls it over HTTP at `http://127.0.0.1:8001`.

## Rules
- Use the exact table, column, enum, route and class names from the project documentation.
- Business logic lives in `app/Actions` and `app/Services`, not in controllers or Blade.
- Validate all input and authorize ownership-sensitive data.
- Keep health content educational screening, not diagnosis.
- Prefer small working vertical slices and test each completed task.
