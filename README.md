<img src="https://github.com/blekerfeld/donut/blob/master/library/staticimages/logo.png?raw=true" width="400">

#### Under Construction
#### Planned release: January 2018

Donut is a dictionary and grammar toolkit that makes it easy to create and mantain an online dictionary and grammar for any language. It has purposes within descriptive linguistics and language construction.

Please note that Donut is under heavy development, so its structure might change drastically. If you are eager to try out some of the features already, installation goes as follows. The example database is filled with example content of the Dutch language.

Donut is licensed under MIT.

### Requirements

* PHP 5.6+ (ideally PHP 7+), might work with PHP 5.4+ (not tested)
* PHP 7+ if you want to use PDF generation
* MySQL or MariaDB-server

### Installation

* Clone the git repository to a web server (alternativly just download its contents)
* Run `composer install` in the donut directory.
* Edit config.php to fit your configuaration (database usernames etc.)
* Import `datadump.sql` to the database
* It should work now.

**Note**: for now `datadump.sql` is filled with Dutch example content.

Please note that donut still lacks a lot of features. At this moment you should only install donut if you want to take a look at its progress.

If your host does not allow for .htacces rewriting of urls it should be turned of automatically, if that however does not work, simply go to `config.php` and change `define('CONFIG_REWRITE', true);` to `define('CONFIG_REWRITE', false);`.

#### Test account
**Username**: donut
**Password**: yeast
