# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

**Order for categories:**
- Security
- Added
- Changed
- Fixed
- Removed
- Packages

## Unreleased
### Packages
- Update php cs fixer and phpstan

## [0.1.1] - 2021-03-21
### Fixed
- Fixed issue with group 0 (all lights). Class can be null

## 0.1.0 - 2021-03-20
### Added
- `All` Initial release
- `All` Added support for local and cloud connections to the Phillips Hue API
- `All` Controlling lights and groups
    - Turn on / Turn off
    - Brightness (Raw value and percentage)
    - Color temperature (Raw value and percentage)
    - Saturation (Raw value and percentage)
    - Effect (none / colorloop)
    - Alert (none / select / lselect)
    - Color (XY, Hex, RGB)
- `Cloud` Full OAuth2 authentication flow (including Digest Auth)

[0.1.1]: https://github.com/jkniest/hue-it/compare/0.1.0...0.1.1