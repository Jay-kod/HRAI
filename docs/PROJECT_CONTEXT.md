# Project Context

## Purpose

The application helps an adult understand relative risk for heart disease, diabetes, and hypertension and receive preventive-care recommendations. It is an educational screening prototype, not a diagnosis or clinical decision-support system.

## Stack

- Laravel 13, PHP 8.3+
- Blade, Livewire 3, Volt, Alpine.js, Tailwind CSS
- SQLite for development/demo; migrations remain MySQL-compatible
- Chart.js for charts
- Python 3.11+, scikit-learn, FastAPI, uvicorn
- Laravel calls the ML service at `http://127.0.0.1:8001`

## Decisions

- One BRFSS 2015 dataset supplies three targets.
- Random Forest and Logistic Regression are trained and compared.
- Results show an estimated probability and a percentile relative to the reference population.
- A rule-based scorer keeps the demo usable when the ML service is offline.
- Wearables and EHR data are simulated or imported from CSV.
- Adults 18+ are supported.

## Definition of done

Register, complete a profile, submit a check-in, see three condition results and recommendations, view seeded trends, and open the admin Model Performance page. The core flow must work with the ML service on and off.
