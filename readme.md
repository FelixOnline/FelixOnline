#Felix Online
Welcome to the Felix Online source code! This is the code that powers the [Felix Online](http://felixonline.co.uk) website. Completely custom built and a bit messy!

Contributors welcome! Just fork the repository and send us a pull request with your changes. 

Maintained by Philip Kent (pk1811@imperial.ac.uk) and Jonathan Kim (hello@jkimbo.com)

[![Stories in Ready](https://badge.waffle.io/felixonline/felixonline.png?label=ready)](http://waffle.io/felixonline/felixonline)

## Getting started:
###Requirements:
* Local LAMP stack (Apache, PHP and MySQL)
* [Git](http://git-scm.com/)
Have a look a our [installation guide](wiki/Installation) if you are unsure how to get the above. 

###Setup:
* Clone the repo into the folder your local web server hosts from
* Import media\_felix.sql into your MySql database
* Make your own config.inc.php to connect to your local database and change default links. There is a config.sample.php in the inc/ folder so use that as a base.
* Grant write access to log, cache, and the generated folder inside the CSS (and JS if applicable) for your theme
* Go to local site (e.g. http://localhost/felix/)

##Contributing:
We are always looking for help from people so please free to contribute to the site if you find a bug or have thought up a new feature. Here is a step by step way of doing it:

* Fork this repo (button is in the top right corner)
* Clone your fork
* Create a topic branch for your changes (name it something recognisable!)
* Do some awesome coding
* Commit your branch to your forked repo on github
* Switch branches on the github site
* Click the "Pull Request" button at the top of the page
* Write a pithy description of your pull request
* Profit!

We will then review your changes, making sure it doesn't break anything etc, and merge it into the master to be pushed to the live site.
You can read more about pull requests here: http://help.github.com/send-pull-requests/

##License
Copyright (c) 2011 Felix Imperial, Jonathan Kim, Philip Kent, Chris Birkett

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the “Software”), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Includes the is_email() method from http://code.google.com/p/isemail/, licensed under the BSD License
