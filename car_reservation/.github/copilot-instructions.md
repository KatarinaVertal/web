<!-- Auto-generated: guidance for AI coding agents working on this repo. If you edit, keep content concise and factual. -->
# Copilot / AI agent instructions — car_reservation

This file contains short, actionable guidance designed to help an AI coding agent be immediately productive in this repository.

- Project root layout (source of truth):
  - `app/config/config.php` — central configuration (DB credentials, env flags). Check here first for any global constants.
  - `app/core/database.php` — database helper / wrapper. Changes to DB access should be coordinated here.
  - `public/index.php` — web entry point; routing and public-facing bootstrap live here.

- High-level architecture (what to assume and verify):
  - The repo follows a minimal PHP app layout: a single public entry (public/), app-level code under `app/`, and configuration under `app/config/`.
  - Before changing routing or request bootstrap, inspect `public/index.php` for current behavior (it is the canonical entry point).
  - Treat `app/core` as the place for low-level services (DB, session, helpers). Keep API-compatible changes unless you update all call sites.

- Developer workflows and quick checks (discoverable from files):
  - Local dev: run a PHP built-in server from project root that serves `public/`. Example (PowerShell):
    php -S localhost:8000 -t public
  - There are no test files or composer.json detected in the repository root; do not add sweeping tooling without confirming with the repo owner.
  - Search the repo for SQL strings or table names before refactoring DB code: e.g., search for "SELECT", "INSERT", table-like names, or the string `database.php` to find call sites.

- Project-specific conventions and patterns (observed):
  - Single-entry public folder: place only assets and the front controller in `public/`.
  - Centralized config file: use `app/config/config.php` to read or write global settings. Avoid scattering environment constants elsewhere.
  - Database access centralized in `app/core/database.php`; any change to connection parameters, query execution, or result handling must be applied there first and then verified by running key pages.

- Integration points and external dependencies:
  - The codebase currently contains no explicit dependency manifest (no `composer.json`). Assume minimal/no external packages unless added.
  - Database: present through `app/core/database.php`. Concrete driver (mysqli/PDO) is not documented in the repo — inspect that file before assuming APIs.

- Safety and code-change rules for AI agents (do this, don't do that):
  - Do: Make small, localized edits and run the app locally to smoke-test (`php -S ... -t public`).
  - Do: Leave credentials and secrets out of commits. If you must add a sample config, create `config.example.php` and reference `config.php` in `.gitignore`.
  - Don't: Modify `public/index.php` routing logic without adding a comment and verifying all entry points. It's the single public bootstrap.
  - Don't: Introduce new global state or hard-coded environment values; prefer reading/writing `app/config/config.php`.

- Useful file references (examples):
  - Update DB settings: edit `app/config/config.php` (then verify `app/core/database.php`).
  - To change request handling or add routes: modify `public/index.php` and document the change in a comment at top of that file.

If anything in this file is unclear or incomplete, tell me which area (architecture, workflows, conventions, integration) you'd like expanded and I will iterate.
