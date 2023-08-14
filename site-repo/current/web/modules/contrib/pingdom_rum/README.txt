INTRODUCTION

Pingdom is a provider of website monitoring services.

On signing up for Visitor Insights (Real User Monitoring), you are presented
with a Javascript snippet to paste into the HEAD section of the website
you wish to monitor. This module inserts that code for you.

REQUIREMENTS

The Drupal 8 || 9 version of this module has no special requirements.

INSTALLATION

Download this module into your modules directory, and enable it for your site.

CONFIGURATION

Now find the 24-character hexadecimal string that is the unique identifier
for your project. You can find this in two ways.
1. Go to https://my.pingdom.com/app/3/visitor-insights,
  where there is a link to your RUM project(s).
  The last part of the URL for your project is your project identifier.
  For example, if your project is managed from
                https://my.pingdom.com/app/3/visitor-insights/0123456789abcdef01234567/experience
  then your project Id is 0123456789abcdef01234567.
2. Look at the Javascript snippet that Pingdom ask you to insert.
  It begins
 <script src="//rum-static.pingdom.net/pa-0123456789abcdef01234567.js
  in this case, 0123456789abcdef01234567 would be your project identifier.

Having found your project identifier, go to /admin/config/services/pingdom.
There you can enter this project identifier.

View the source on a page on your website to verify that the snippet is now in
the HEAD section.

You should then be able to go to your Visitor Insights project on Pingdom and
see information on your site's visits.

The module also lets you configure the snippet so it only appears for certain
user roles, and on certain pages of the website. For example, you may wish to
exclude /admin/*
