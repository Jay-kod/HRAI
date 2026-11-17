# Changelog

## Unreleased

- Added the implementation plan and project contracts.
- Added the Phase 1 progress checklist.
- Replaced the Laravel starter README with HealthRisk AI setup and verification instructions.
- Added the ML training pipeline, synthetic fallback, FastAPI service, generated artifacts, and pytest coverage.
- Added Phase 3 domain enums, SQLite/MySQL-compatible migrations, model relationships and casts, factories, and seeder placeholders.
- Added Phase 4 profile completion fields, role helpers, ownership policies, dashboard status cards, and authorization coverage.
- Added reusable disclaimer and risk-level components plus consistent authenticated footer and role navigation.
- Added seeded baseline risk rules and recommendation templates, feature mapping, ML client fallback, rule engine, recommendation generation, and assessment action.
- Added the four-stage check-in, persisted assessment result view, assessment history, navigation links, and UI tests.
- Added health goal creation, simulated wearable sessions, and dashboard latest-result/activity summaries.
- Added print-friendly reports, expiring public report shares, and shared-report access tests.
- Added admin-only Model Performance, usability feedback capture, demo accounts, and seeded demo assessment history.
- Redesigned user login and registration with a teal onboarding experience, and added a separate slate/amber administrator login portal with role enforcement.
- Added clearly labelled local demo credentials to the user and administrator login screens.
- Replaced visible demo credentials with one-click Test User and Test Administrator login buttons, including loading and authentication animations.
- Namespaced canonical URLs so user authentication and application pages use `/user/...`, while administrator pages use `/admin/...`.
- Adjusted the shared guest authentication shell to occupy at least 99vh for a fuller login-page layout.
- Tightened desktop authentication spacing so user and administrator login content fits within one 99vh viewport without page scrolling.
- Reduced desktop authentication typography, control heights, and vertical gaps further to keep the complete login content visible on one screen.
- Made the desktop login and administrator forms more compact with smaller controls, headings, panels, and link spacing.
- Redesigned user and administrator dashboards with a dark analytics workspace, compact KPI cards, quick actions, and teal/emerald system theming.
- Reworked dashboard structure to mirror the reference layout with a persistent sidebar, workspace header, four KPI cards, a dominant screening-trend panel, and three lower information panels.
- Removed the shared bottom disclaimer footer from user and administrator dashboard views to keep the analytics workspace clean.
- Added a HealthRisk AI SVG favicon, global skeleton-style Livewire loading cards, logout confirmation modal, and goal-abandonment confirmation modal.
- Added the admin overview, model, template, rule, feedback, and user pages, shared admin sidebar navigation, and user assessment/report URL aliases.
- Added user activation controls to the admin Users page and prevented inactive accounts from signing in.
- Added a persistent user sidebar to all authenticated user pages, with mobile fallback navigation retained.
- Separated user and administrator workspaces with explicit role middleware; administrators cannot enter user pages and user accounts cannot enter admin pages.
- Consolidated the authenticated user shell onto one sidebar and replaced the dashboard's illustrative trend with a real Chart.js chart from recent assessments.
- Reworked the admin overview to match the dashboard hierarchy, use real risk-distribution data, and show a safe ML offline state.
