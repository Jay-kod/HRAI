# Development Guidelines

- Put business logic in `app/Actions` and `app/Services`.
- Use backed enums for fixed value sets.
- Validate all input server-side and scope user queries by authenticated user.
- Use policies for assessment ownership and role middleware for admin pages.
- Use Carbon for dates and display Africa/Lagos time.
- Keep ML service binding local to `127.0.0.1`.
- Never commit `.env`, `ml/data/`, `ml/.venv/`, or secrets.
- Run `php artisan test`, `pytest` in `ml/`, and `npm run build` at the relevant phase gate.
- Add focused tests for every new behavior and update the progress checklist and changelog.
