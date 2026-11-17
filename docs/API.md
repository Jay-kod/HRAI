# API Contract

## ML service

- `GET /health` returns service status, artifact availability, and model metadata.
- `POST /predict` accepts the documented feature object and returns three condition results.
- Invalid feature payloads return HTTP 422.

Laravel uses a five-second timeout and one short retry. Connection failures, timeouts, 5xx responses, and invalid responses are mapped to the rule-based fallback.

## Web routes

- `/`, `/register`, `/login`, `/logout`
- `/profile`
- `/dashboard`
- `/check-in`
- `/assessments/{assessment}`
- `/history`
- `/reports/{assessment}`
- `/shared/{token}`
- `/admin/*`

All user-specific routes require authentication and ownership authorization.
