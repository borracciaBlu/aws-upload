# Change Log
All notable changes to this project will be documented in this file.

## 1.8.0 - 2017-07-27
### Added
- Add new feature `aws-upload autocomplete` to enable autocomplete in `aws-upload`.
- Add `Model` folder.
- Add `System` folder.

### Changed
- Changed behaviour of `aws-upload new key`.
- Added `CodeSniffer` to dev.
- Clean up the script section.

### Fixed
- namespace for tests

## 1.7.2 - 2017-07-12
### Changed
- Improve help section
- Improve abstraction

### Bugfix
- Fixed issue on new

## 1.7.1 - 2017-05-11
### Added
- Add new feature `aws-upload copy proj.env newproj.dev` to copy a setting file.

### Bugfix
- Fixed phpunit names on test.

## 1.6.1 - 2017-05-11
### Added
- Add new feature `aws-upload selfupdate` to self update `aws-upload`.
- Add new feature `aws-upload check proj.env` to check for json syntax, pem permission, and local forlder.

## 1.4.1 - 2017-05-09
### Added
- Add new feature `aws-upload keys` to get projects' keys.

## 1.3.1 - 2017-05-05
### Added
- Add new feature `aws-upload edit proj.env` to edit setting files.

### Changed
- Update convention phpunit methods.
- Update way to instantiate a command in `AwsUpload::run`.

## 1.2.1 - 2017-04-02
### Added
- Add new feature `aws-upload new proj.env` to create new project setting.

### Changed
- Update convention phpunit methods.

## 1.1.2 - 2017-04-03
### Added
- Output to manage colors, exit, stdout.

### Changed
- Now Facilitator contains only che text for each message.
- Cleanup `AwsUpload::run`.

## 1.1.1 - 2017-03-30
### Added
- escapeshellargs

### Changed
- Improved `--help`: added description for `--simulate`.
- Improved instruction for `aws-upload-zsh`.
- Improved demo.

## 1.1.0 - 2017-03-11
### Added
- Ability to use double notation or key notation.
- Error handle for cli/Arguments.

## 1.0.6 - 2017-03-09
### Added
- Demo in `README.md`.
- What?, Why?, Why Not?.
- Test for Check.

### Fixed
- `Check::fileExists` as static.
- Help output, fixed allign.

## 1.0.5 - 2017-03-08
### Changed
- Updated all the initial doc block in each file.

### Fixed
- Fix `SettingFiles::getList()` to exclude folders from the returned array.
- Fix `AwsUpload::getWildArgs()` to avoid collisions with `-q` and `--quiet`.

## 1.0.4 - 2017-03-07
### Fixed
- Fix position of the bin script from bin to root. `mv bin/aws-upload aws-upload`.
- Fix in `aws-upload` the way to import `autoload.php`.

## 1.0.3 - 2017-03-07
### Changed
- Fix issues installation on packagist.
- Version on construct.
- README.md update installation.

## 0.1.2 - 2017-03-07
### Added
- graceExit() for phpunit.

### Changed
- Improved tests for AwsUpload, Facilitator, Rsync.
- README.md removed aws-upload.plugin.zsh.

## 0.1.0 - 2017-03-06
### Changed
- README.md removed `aws-upload.plugin.zsh`.

### Fixed
- Fixed `phpunit.xml.dist` `.travis.yml`.

## 0.0.3 - 2017-03-06
### Added
- Added `.travis.yml` `phpunit.xml.dist`.

## 0.0.2 - 2017-03-06
### Added  
- Added LICENCE file for MIT.

### Changed
- Update comments on Rsync.

### Removed
- Removed `aws-upload.plugin.zsh` moved in different repo.

## 0.0.1 - 2017-03-06
### Added
- Added `composer.json`.
- Added Facilitator to print `help`, `banner`, `version`.
- Added Rsync to user `rsync`.
- Added SettingFolder to get `getHomeDir`, `getPath`.