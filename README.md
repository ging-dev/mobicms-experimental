# [mobiCMS 0.0.0](http://mobicms.net)
Mobile Content Management System.

[![License](https://img.shields.io/badge/license-GPL%20v.3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0-standalone.html)
[![SemVer](http://img.shields.io/badge/semver-✓-brightgreen.svg)](http://semver.org)
[![devDependency Status](https://david-dm.org/mobiCMS/mobicms-core/dev-status.svg)](https://david-dm.org/mobiCMS/mobicms-core#info=devDependencies)

## The main possibilities of the system
- Based on components of the Zend Framework
- PSR-4 compliant core
- modular architecture
- a high level of security
- multilingual
- and many other things...

## License
GNU General Public License v.3

## System Requirements
- PHP 5.5 +
- MySQL (InnoDB support required) 5.1 +
- Apache with mod_rewrite and .htaccess support

## How to install
1. Run `composer install`
2. Create database and import all SQL files ftom /install dir
3. Copy `Database.php.inst` from `/_install` dir to a folder `/system/config/`, rename it to `Database.php` and write down in this file your requisites of access to a database
4. Make sure the folder `/system/cache/` has **write** permissions
5. In order that the common configuration was tuned through the Admin Panel, make sure that all files in the directory `/system/config` have the **change** permissions

## Documentation
Documentation is under development and will be constantly updated.    
Read here: [http://mobicms.info](http://mobicms.info/ru/index.html)

## Thanks
We express gratitude for the grant assistance to our project (alphabetical list):

  * [Crowdin Localization Management Platform](http://crowdin.com) - free full featured account
  * [JetBrains s.r.o.](http://www.jetbrains.com) - PhpStorm license
  * [Zend Technologies Inc.](http://www.zend.com) - Zend Server and Zend Studio licenses
  
Special thanks to [JohnCMS Community](http://johncms.com) for moral support, discussion, criticism and advice.
