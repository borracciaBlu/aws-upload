# Change Log
All notable changes to this project will be documented in this file.

## 1.0.5 - 2017-03-08
### Changed
- Updated all the initial doc block in each file 

### Fixed
- Fixed SettingFiles::getList() to exclude folders from the returned array
- Fixed AwsUpload::getWildArgs() to avoid collisions with -q and --quiet  

## 1.0.4 - 2017-03-07
### Fixed
- Fixed position of the bin script from bin to root. `mv bin/aws-upload aws-upload` .
- Fixed in `aws-upload` the way to import `autoload.php` 

## 1.0.3 - 2017-03-07
### Changed
- Fixed issues installation on packagist   
- Added version on construct 
- README.md update installation

## 0.1.2 - 2017-03-07
### Changed
- Improved tests for AwsUpload, Facilitator, Rsync   
- Added graceExit() for phpunit  
- README.md removed aws-upload.plugin.zsh  

## 0.1.0 - 2017-03-06  
### Changed  
- README.md removed aws-upload.plugin.zsh  

### Fixed  
- Fixed phpunit.xml.dist .travis.yml

## 0.0.3 - 2017-03-06
### Added  
- Added .travis.yml phpunit.xml.dist

## 0.0.2 - 2017-03-06
### Added  
- Added LICENCE file for MIT  

### Changed  
- Update comments on Rsync

### Removed  
- Removed aws-upload.plugin.zsh moved in different repo  

## 0.0.1 - 2017-03-06  
### Added  
- Added composer.json  
- Added Facilitator to print `help`, `banner`, `version` 
- Added Rsync to user `rsync`
- Added SettingFolder to get `getHomeDir`, `getPath`