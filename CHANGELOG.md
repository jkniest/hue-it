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
### Added
- Added generic phpdocs for all collections

### Packages
- Updated php cs fixer
- Update min `ozdemirburak/iris` library version to 3
- Update min `illuminate/collections` library version to 9

### Removed
- Removed PHP 7.4 support

## [0.2.0] - 2022-01-04
### Added
- Added initial support for php 8.1

### Packages
- Updated php cs fixer and phpstan

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

[0.2.0]: https://github.com/jkniest/hue-it/compare/0.1.1...0.2.0
[0.1.1]: https://github.com/jkniest/hue-it/compare/0.1.0...0.1.1