invoicing-app
==============

A Symfony project created on October 12, 2015, 8:38 pm.

# Demo

A demo is running at http://demo.npirotte.be/

Username : test@test.be
password : test1234

# Instructions

## Run the project

In a cmd tool, run "bower install", "php composer.phar install" to install front and backend dependencies

## Build asset

Assets are built with gulp, so you have to install node, npm and gulp globaly, the install dependencies with "npm install" cmd, then run the "gulp" command

## Unit testing

I set up some unit tests for the EmailScheduler development

To execute tests juste run "phpunit -c app src/InvoicingBundle/Tests/

# Resources

The front user the material-lite framework for a nice and fresh theme. (http://www.getmdl.io/)

Autocomplete use the twitter typeahead plugin (https://github.com/twitter/typeahead.js)

# EmailScheduler

A iCal calendar is saved in /data/Holidays.ics
An holiday period starts on 12/24/2015 and ends on 31/24/2015
