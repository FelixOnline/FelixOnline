#Felix Online
Welcome to the Felix Online source code! This is the code that powers the [Felix Online](http://felixonline.co.uk) website. Completely custom built and a bit messy!

Contributors welcome! Just fork the repository and send us a pull request with your changes. 

Maintained by Philip Kent (pk1811@imperial.ac.uk) and, previously, Jonathan Kim (hello@jkimbo.com)

[![Stories in Ready](https://badge.waffle.io/felixonline/felixonline.png?label=ready)](http://waffle.io/felixonline/felixonline)

## Getting started:
###Requirements:
* Local LAMP stack (Apache, PHP and MySQL)
* [Git](http://git-scm.com/)
* 
Have a look a our [installation guide](//github.com/FelixOnline/FelixOnline/wiki/Installation) if you are unsure how to get the above. 

###Setup:
* Clone the repo into the folder your local web server hosts from
* Import a SQL download into your MySql database
* Install [composer](http://getcomposer.org/download/) if you haven't already got it and run `composer install`
* Make your own config.inc.php to connect to your local database and change default links. There is a config.sample.php in the inc/ folder so use that as a base.
* Grant write access to the css and js folders inside your theme
* Set the self-explanatory settings in the settings database table
* Go to local site (e.g. http://localhost/felix/)

Grab a copy of the latest database from https://union.ic.ac.uk/media/felix_stage/backups.

You may wish to add the two role scripts in the scripts folder, and the spam cleaning script to a cron task.

If you host Admin on the same server as the main site, you may need to define CACHE_PATH in the configuration file and set it to the same path as the main site for cache resetting to work.

Note that the current theme requires use of Glyphicons Pro, which is not included for licensing reasons.

### Database Migrations
* Run `./vendor/bin/phinx init` to create a `phinx.yml` file in the root directory and fill it in with your database credentials
* Run `./vendor/bin/phinx migrate` to run all migrations
