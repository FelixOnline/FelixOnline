# Welcome to the FelixOnline source code!

Maintained by Jonathan Kim (jk708@imperial.ac.uk) and Chris Birkett

## Getting started:
Always clone from preview section of the felix site (git clone felix/preview/.git) and ONLY EVER push to that repo. You can then check that your changes work on the server and then ssh into the main section and pull from preview (git pull preview master)

    * `git clone USERNAME@dougal.union.ic.ac.uk:~/PATH/TO/FELIX/preview/.git`
    * Make your own config.inc.php to connect to your local database. There is a config.sample.php in the inc/ folder so use that as a base.

Currently the repo ignores the mobile, api and engine repos so to add them to your local copy just clone them. At the moment I am considering them as individual repos that can be used independently of each other but in the future I may make them git submodules.  

## TODO:
    * Make dummy sql dump for local work
    * Build script (or modify html5 build scripts) to minify css and javascript
    * Move css to less
    * Rewrite page.php
    * Separate backend from frontend to give us a template system
        - Much more flexibility 

# IDEA:
    Have a php built system that looks for a flag (already in config.inc.php) and if it is set uses the minified version of the files. If not uses production versions. 

