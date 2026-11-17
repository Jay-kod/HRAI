# ML Specification

## Dataset and targets

Use the BRFSS 2015 Heart Disease Health Indicators dataset at `ml/data/heart_disease_health_indicators_BRFSS2015.csv`. Train separate binary targets for heart disease, diabetes, and high blood pressure.

## Models

Train Random Forest and Logistic Regression with fixed random seed `42`. Persist the served model for each condition as a `.joblib` artifact. Persist metadata, thresholds, feature names, training date, dataset size, and comparison metrics.

## Request

```json
{
  "features": {
    "age_cat": 5,
    "sex": 1,
    "bmi": 27.4,
    "high_bp": 1,
    "high_chol": 0,
    "smoker": 0,
    "hvy_alcohol": 0,
    "phys_activity": 1,
    "fruit": 1,
    "veggies": 1,
    "gen_health": 3,
    "mental_health": 2,
    "physical_health": 1,
    "diabetes": 0
  }
}
```

The response contains one result per condition with `probability`, `percentile`, `level`, and the served model name.
