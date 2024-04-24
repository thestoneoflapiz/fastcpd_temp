# Fast CPD System

VERSIONING
----
<b>0 . 0 . 0</b>

* First <b>"0"</b> is a major change that affects the main modules of the system
* Second <b>"0"</b> is a new whole module <to be> implemented-to/removed-from the system
* Third <b>"0"</b> is a minor change to design, validation, grammar, correction to ui/uix or form procedures of the system

BRANCHING
----
<b>dev-branch-{module}-{level} --{status}</b>

* <b>{module}</b> of the current development
* <b>{level}</b> as to critical, high, major, minor
* <b>{status}</b> as to fix, save, update, removal, addition

MERGING?
------
* <b>ALWAYS</b> check new updates from COMMITS
* <b>ALWAYS</b> use BRANCHING
* <b>ALWAYS</b> use PULL REQUESTS


IMPORTANT IDENTIFICATION
--------
* <b>ALWAYS</b> log in your account in github
* <b>ALWAYS</b> set your username and email in git

INSTALLING TO LOCAL
----
What you need:
1. Docker (if webserver is Apache)
2. Linux/Ubuntu Server (Or use Vagrant if Windows)

Installation and Setup:
1. git clone this repository on your /var/www/ or any directory
2. copy .env.example and create .env file, fill up all required credentials
3. create a virtual host on your local server 
4. stop services (Apache & MYSQL)
5. cd to project directory
6. execute "dcup --build -d"
7. execute "composer install"
8. execute "drit sms-php bash" or "docker exec -it {name of container} bash"
9. execute "php artisan key:generate" and
10. execute "php artisan migrate:fresh"

LARAVEL ARTISAN
---
*<b>ALWAYS</b> use the "php artisan config:cache" if you have change in any configuration files including .env file
