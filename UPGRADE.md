#Update instructions

Unless instructions are explicitly provided below, just run *composer update* and then run any Phinx migrations.

##September 2015 release

A significant number of changes have been made in this release which make the upgrade procedure more complex. For a successful update, follow the steps below:

1. Back up your database!!
3.  Ensure that, if you use the issue archive, the database user Phinx connects using has at least read access to the archive database - make a note of the database name
3. Run *composer update* to download the latest release of Core and side components
4. Run the script *fixup.php* in the scripts folder. This corrects any inconsistencies identified in the database
6. Run the Phinx migration process to upgrade the database. This may take upwards of 15 minutes in some circumstances. Report any breakages you find. Provide the archive database name when prompted (or use CTRL-D to cancel the migration step)
7. Run the *to-sir-trevor.php* script to upgrade article content  to the new JSON-Markdown based format
8. Run the *clearcache.php* script to ensure the cache contains no old data
9. Set up the self-explanatory settings in the Settings table

You are now up to date. Please note that all admin permissions except for Super Users have been lost, however you can run:

- *apply-author-roles.php* to give all authors a role, and
- *apply-sectioneditor-roles.php* to give all section editors their own role, and strip former section editors of access

These scripts are optional.

You may, also optionally, wish to run *fix-missing-image-size.php*, *script to be written* and *clean-old-spam.php*, the first and second fixes some missing entries in the database (only run the second if you migrated the archive database in Phinx), and the last removes spam comments older than one month.

You may wish to add *clean-old-spam.php*, and the two role scripts to a cron job.

If you did not migrate the archive database, the issue archive will not work. To do it at a later date, you will need to migrate it manually (refer to the migration *20150827094001_issue_archive.php* source code).

The migration step may give some errors about issue records being missing - you may want to investigate these as it shows there is an issue with a missing PDF file.

Don't forget to install the shiny new admin interface!