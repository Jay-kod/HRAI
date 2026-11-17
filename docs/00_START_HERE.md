# HealthRisk AI

HealthRisk AI is a responsive Laravel application with a local Python ML service. It provides educational, relative-risk screening for adults using body measurements, lifestyle answers, and medical history.

## Build order

1. Establish contracts and workflow.
2. Build and test the ML service.
3. Add the database and domain models.
4. Add profile, roles, and shared UI.
5. Seed rules, recommendations, and demo users.
6. Implement the assessment pipeline and fallback scorer.
7. Build the check-in and result flow.
8. Add dashboard, history, goals, wearables, reports, sharing, and admin features.

Read [IMPLEMENTATION_PLAN.md](./IMPLEMENTATION_PLAN.md) before starting a phase. The code must remain runnable after each phase.
