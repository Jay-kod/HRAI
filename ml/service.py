from __future__ import annotations

import json
from pathlib import Path
from typing import Any

import joblib
import numpy as np
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel, Field

FEATURE_NAMES = ["age_cat", "sex", "bmi", "high_bp", "high_chol", "smoker", "hvy_alcohol", "phys_activity", "fruit", "veggies", "gen_health", "mental_health", "physical_health", "diabetes"]
CONDITIONS = ("heart", "diabetes", "hypertension")
ARTIFACTS = Path(__file__).parent / "artifacts"


class PredictionRequest(BaseModel):
    features: dict[str, float | int] = Field(..., min_length=len(FEATURE_NAMES), max_length=len(FEATURE_NAMES))


class ModelStore:
    def __init__(self, artifacts: Path) -> None:
        self.artifacts = artifacts
        self.models: dict[str, Any] = {}
        self.meta: dict[str, Any] = {}
        self.metrics: dict[str, Any] = {}
        self.load()

    def load(self) -> None:
        meta_path, metrics_path = self.artifacts / "model_meta.json", self.artifacts / "metrics.json"
        if not meta_path.exists() or not metrics_path.exists():
            return
        self.meta = json.loads(meta_path.read_text(encoding="utf-8"))
        self.metrics = json.loads(metrics_path.read_text(encoding="utf-8"))
        for condition in CONDITIONS:
            model_path = self.artifacts / f"{condition}.joblib"
            if model_path.exists():
                self.models[condition] = joblib.load(model_path)

    @property
    def ready(self) -> bool:
        return len(self.models) == len(CONDITIONS) and bool(self.meta)

    def predict(self, features: dict[str, float | int]) -> dict[str, Any]:
        if not self.ready:
            raise HTTPException(status_code=503, detail="Model artifacts are not available.")
        values = np.array([[float(features[name]) for name in FEATURE_NAMES]])
        result: dict[str, Any] = {}
        for condition in CONDITIONS:
            probability = float(self.models[condition].predict_proba(values)[0][1])
            meta, reference = self.meta["conditions"][condition], np.array(self.meta["conditions"][condition]["percentile_values"])
            percentile = max(0.0, min(100.0, float(np.searchsorted(reference, probability, side="right"))))
            result[condition] = {"probability": round(probability, 4), "percentile": round(percentile, 2), "level": "low" if percentile <= 33 else "moderate" if percentile <= 66 else "high", "served_model": meta["served_model"]}
        return result


store = ModelStore(ARTIFACTS)
app = FastAPI(title="HealthRisk AI ML Service", version="1.0.0")


@app.get("/health")
def health() -> dict[str, Any]:
    return {"status": "ok" if store.ready else "degraded", "artifacts_available": store.ready, "conditions": list(store.models.keys()), "synthetic": bool(store.meta.get("synthetic", False))}


@app.post("/predict")
def predict(request: PredictionRequest) -> dict[str, Any]:
    missing = set(FEATURE_NAMES) - request.features.keys()
    if missing:
        raise HTTPException(status_code=422, detail=f"Missing features: {', '.join(sorted(missing))}")
    return {"conditions": store.predict(request.features)}
