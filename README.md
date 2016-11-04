# [mobiCMS 0.3.0](http://mobicms.net)
Mobile Content Management System.

[![License](https://img.shields.io/badge/license-GPL%20v.3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0-standalone.html)
[![Crowdin](https://d322cqt584bo4o.cloudfront.net/mobicms/localized.png)](http://translate.mobicms.net/project/mobicms)

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
- PHP 5.6 +
- MySQL (InnoDB support required) 5.5 +
- Apache with mod_rewrite and .htaccess support

## How to install
1. Run `composer install`
2. Create database and import all SQL files ftom /install dir
3. Copy `database.local.php.inst` from `/install` dir to folder `/system/config/`, rename it to `database.local.php` and write down in this file your requisites of access to a database
4. Make sure the folder `/system/cache/` has **write** permissions

## Documentation
Documentation is under development and will be constantly updated.    
Read here: [http://mobicms.info](http://mobicms.info/ru/index.html)

## Thanks
We express gratitude for the grant assistance to our project (alphabetical list):

  * [Crowdin Localization Management Platform](http://crowdin.com) - free full featured account
  * [JetBrains s.r.o.](http://www.jetbrains.com) - PhpStorm license
  * [Zend Technologies Inc.](http://www.zend.com) - Zend Server license
  
Special thanks to [JohnCMS](http://johncms.com) community for moral support, discussion, criticism and advice.
