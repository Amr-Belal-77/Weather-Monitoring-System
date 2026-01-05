# Changelog
All notable changes to this project will be documented in this file.

The format is based on **Keep a Changelog**, and this project loosely follows **Semantic Versioning**.

---

## [Unreleased]
### Added
- Professional documentation structure (README, docs).
- Roadmap and setup instructions for local run.

### Changed
- (Planned) Move secrets (DB credentials, python path) to config files.

### Fixed
- (Planned) Prevent duplicate DB insertion (insert in one place only).
- (Planned) Improve input validation and error logging.

---

## [1.0.0] - 2026-01-06
### Added
- ESP32 → PHP ingestion via HTTP POST (`ESP32.php`)
- MySQL storage (`WeatherDataset` with `Temperature` and `IOTControl`)
- Web dashboard (`index.php`) with Plotly charts + status indicators
- Control page (`IOTControl.php`) for Manual/AI mode switching
- Python ML integration (`PythonESP32.py`) to predict **Play / Not Play**
- UI interactions and layout (`script.js`, `style.css`)
