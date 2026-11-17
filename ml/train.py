from __future__ import annotations

import argparse
import json
from datetime import date
from pathlib import Path
from typing import Any

import joblib
import numpy as np
import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import average_precision_score, confusion_matrix, f1_score, precision_score, recall_score, roc_auc_score
from sklearn.model_selection import train_test_split
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import StandardScaler

SEED = 42
FEATURE_NAMES = ["age_cat", "sex", "bmi", "high_bp", "high_chol", "smoker", "hvy_alcohol", "phys_activity", "fruit", "veggies", "gen_health", "mental_health", "physical_health", "diabetes"]
CONDITION_TARGETS = {"heart": "HeartDiseaseorAttack", "diabetes": "Diabetes_012", "hypertension": "HighBP"}


def _synthetic_dataset(rows: int = 2500) -> pd.DataFrame:
    rng = np.random.default_rng(SEED)
    frame = pd.DataFrame({
        "Age": rng.integers(1, 14, rows), "Sex": rng.integers(0, 2, rows),
        "BMI": np.clip(rng.normal(27, 5, rows), 15, 55), "HighBP": rng.integers(0, 2, rows),
        "HighChol": rng.integers(0, 2, rows), "Smoker": rng.integers(0, 2, rows),
        "HvyAlcoholConsump": rng.integers(0, 2, rows), "PhysActivity": rng.integers(0, 2, rows),
        "Fruits": rng.integers(0, 2, rows), "Veggies": rng.integers(0, 2, rows),
        "GenHlth": rng.integers(1, 6, rows), "MentHlth": rng.integers(0, 31, rows),
        "PhysHlth": rng.integers(0, 31, rows), "Diabetes_012": rng.integers(0, 2, rows),
    })
    base = 0.04 * frame["Age"] + 0.04 * (frame["BMI"] - 25) + 0.5 * frame["HighBP"] + 0.35 * frame["HighChol"] + 0.3 * frame["Smoker"] + 0.25 * frame["Diabetes_012"]
    for target, offset in (("HeartDiseaseorAttack", -2.0), ("Diabetes_012", -2.4), ("HighBP", -1.7)):
        probability = 1 / (1 + np.exp(-(base + offset)))
        frame[target] = (rng.random(rows) < probability).astype(int)
    return frame


def _load_dataset(path: Path, synthetic: bool) -> tuple[pd.DataFrame, bool]:
    if synthetic:
        return _synthetic_dataset(), True
    if not path.exists():
        raise FileNotFoundError(f"Dataset not found at {path}. Download the BRFSS CSV or rerun with --synthetic.")
    return pd.read_csv(path), False


def _feature_frame(frame: pd.DataFrame) -> pd.DataFrame:
    aliases = {"age_cat": "Age", "sex": "Sex", "bmi": "BMI", "high_bp": "HighBP", "high_chol": "HighChol", "smoker": "Smoker", "hvy_alcohol": "HvyAlcoholConsump", "phys_activity": "PhysActivity", "fruit": "Fruits", "veggies": "Veggies", "gen_health": "GenHlth", "mental_health": "MentHlth", "physical_health": "PhysHlth", "diabetes": "Diabetes_012"}
    missing = [source for source in aliases.values() if source not in frame.columns]
    if missing:
        raise ValueError(f"Dataset is missing required columns: {', '.join(missing)}")
    features = frame[[aliases[name] for name in FEATURE_NAMES]].copy()
    features.columns = FEATURE_NAMES
    return features.apply(pd.to_numeric, errors="coerce").fillna(0)


def _model_metrics(model: Any, x_test: pd.DataFrame, y_test: pd.Series) -> dict[str, Any]:
    probabilities = model.predict_proba(x_test)[:, 1]
    predictions = (probabilities >= 0.5).astype(int)
    return {"roc_auc": round(float(roc_auc_score(y_test, probabilities)), 4), "pr_auc": round(float(average_precision_score(y_test, probabilities)), 4), "precision": round(float(precision_score(y_test, predictions, zero_division=0)), 4), "recall": round(float(recall_score(y_test, predictions, zero_division=0)), 4), "f1": round(float(f1_score(y_test, predictions, zero_division=0)), 4), "confusion_matrix": confusion_matrix(y_test, predictions).tolist()}


def train(dataset: Path, artifacts: Path, synthetic: bool = False) -> dict[str, Any]:
    frame, synthetic_data = _load_dataset(dataset, synthetic)
    features = _feature_frame(frame)
    artifacts.mkdir(parents=True, exist_ok=True)
    metrics: dict[str, Any] = {"dataset_size": int(len(frame)), "training_date": date.today().isoformat(), "synthetic": synthetic_data, "conditions": {}}
    model_meta: dict[str, Any] = {"features": FEATURE_NAMES, "seed": SEED, "synthetic": synthetic_data, "conditions": {}}
    for condition, target_name in CONDITION_TARGETS.items():
        target = (pd.to_numeric(frame[target_name], errors="coerce").fillna(0) > 0).astype(int)
        x_train, x_test, y_train, y_test = train_test_split(features, target, test_size=0.2, random_state=SEED, stratify=target)
        candidates = {"random_forest": RandomForestClassifier(n_estimators=150, random_state=SEED, class_weight="balanced"), "logistic_regression": Pipeline([("scale", StandardScaler()), ("model", LogisticRegression(max_iter=1000, random_state=SEED))])}
        condition_metrics: dict[str, Any] = {}
        trained: dict[str, Any] = {}
        for name, estimator in candidates.items():
            estimator.fit(x_train, y_train)
            trained[name] = estimator
            condition_metrics[name] = _model_metrics(estimator, x_test, y_test)
        served_name = max(condition_metrics, key=lambda name: condition_metrics[name]["roc_auc"])
        served = trained[served_name]
        probabilities = served.predict_proba(features)[:, 1]
        joblib.dump(served, artifacts / f"{condition}.joblib")
        metrics["conditions"][condition] = {"served_model": served_name, "models": condition_metrics}
        model_meta["conditions"][condition] = {"served_model": served_name, "percentile_values": np.quantile(probabilities, np.linspace(0, 1, 101)).tolist(), "levels": {"low_max": 33, "moderate_max": 66}}
    (artifacts / "metrics.json").write_text(json.dumps(metrics, indent=2), encoding="utf-8")
    (artifacts / "model_meta.json").write_text(json.dumps(model_meta, indent=2), encoding="utf-8")
    return metrics


def main() -> None:
    parser = argparse.ArgumentParser(description="Train HealthRisk AI models.")
    parser.add_argument("--dataset", type=Path, default=Path(__file__).parent / "data" / "heart_disease_health_indicators_BRFSS2015.csv")
    parser.add_argument("--artifacts", type=Path, default=Path(__file__).parent / "artifacts")
    parser.add_argument("--synthetic", action="store_true")
    args = parser.parse_args()
    result = train(args.dataset, args.artifacts, args.synthetic)
    for condition, values in result["conditions"].items():
        print(f"{condition}: served={values['served_model']}")
        for model, metrics in values["models"].items():
            print(f"  {model}: ROC-AUC={metrics['roc_auc']} PR-AUC={metrics['pr_auc']}")


if __name__ == "__main__":
    main()
