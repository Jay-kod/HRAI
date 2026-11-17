# HealthRisk AI

HealthRisk AI is an educational health-risk screening prototype for adults. It combines a Laravel/Livewire web application with a local FastAPI/scikit-learn service to estimate relative risk for heart disease, diabetes, and hypertension and provide preventive-care recommendations.

## Stack

- Laravel 13, PHP 8.3+
- Blade, Livewire 3, Volt, Alpine.js, Tailwind CSS
- SQLite for local development and demos
- Chart.js
- Python 3.11+, FastAPI, uvicorn, scikit-learn

## Setup on Windows

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run build
```

For the ML service:

```powershell
cd ml
python -m venv .venv
.venv\Scripts\activate
pip install -r requirements.txt
python train.py --synthetic
python -m uvicorn service:app --host 127.0.0.1 --port 8001
```

Start Laravel in another terminal:

```powershell
php artisan serve
```

## Verification

```powershell
php artisan test
npm run build
```

Once the ML service exists:

```powershell
cd ml
pytest
```

## Documentation

Start with [docs/00_START_HERE.md](docs/00_START_HERE.md) and follow [docs/IMPLEMENTATION_PLAN.md](docs/IMPLEMENTATION_PLAN.md). Progress is tracked in [docs/TODO.md](docs/TODO.md).

The application provides educational screening estimates only. It is not a diagnosis and does not replace advice from a qualified healthcare professional.
