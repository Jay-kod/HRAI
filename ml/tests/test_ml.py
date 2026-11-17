from __future__ import annotations

import json

from fastapi.testclient import TestClient

from ml.train import FEATURE_NAMES, train


def test_synthetic_training_writes_expected_artifacts(tmp_path):
    result = train(tmp_path / "missing.csv", tmp_path / "artifacts", synthetic=True)
    assert result["synthetic"] is True
    assert set(result["conditions"]) == {"heart", "diabetes", "hypertension"}
    assert (tmp_path / "artifacts" / "heart.joblib").exists()
    metadata = json.loads((tmp_path / "artifacts" / "model_meta.json").read_text())
    assert metadata["features"] == FEATURE_NAMES


def test_predict_requires_all_features(monkeypatch, tmp_path):
    from ml import service
    train(tmp_path / "missing.csv", tmp_path / "artifacts", synthetic=True)
    monkeypatch.setattr(service, "ARTIFACTS", tmp_path / "artifacts")
    service.store = service.ModelStore(tmp_path / "artifacts")
    response = TestClient(service.app).post("/predict", json={"features": {"bmi": 27}})
    assert response.status_code == 422


def test_health_reports_artifact_status(monkeypatch, tmp_path):
    from ml import service
    train(tmp_path / "missing.csv", tmp_path / "artifacts", synthetic=True)
    monkeypatch.setattr(service, "ARTIFACTS", tmp_path / "artifacts")
    service.store = service.ModelStore(tmp_path / "artifacts")
    response = TestClient(service.app).get("/health")
    assert response.status_code == 200
    assert response.json()["status"] == "ok"
    assert response.json()["synthetic"] is True
