PROJET 4
========
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/885710077d1e406d9f3dacb6167e200e)](https://www.codacy.com/app/JeffChevasson/louvre-master?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=JeffChevasson/louvre-master&amp;utm_campaign=Badge_Grade)

"Projet 4" is a Symfony project created between June and August 2019. It's my fourth project with Openclassrooms.

The project’s target is to create the ticketing of Louvre Museum.

REQUIREMENTS
------------

PHP 7.1.3 or higher,
and the usual Symfony application requirements.

PRESENTATION OF THIS OPENCLASSROOMS'S PROJECT
---------------------------------------------


https://openclassrooms.com/fr/projects/developpez-un-back-end-pour-un-client


INSTALLATION
------------


* Go to your localhost file and launch Laragon
* Execute this command to clone the project: 
  ```bash 
    $ git clone https://github.com/JeffChevasson/louvre-master.git
  ```
 * Go to "louvre-master" file:
  ```bash 
    $ cd louvre-master 
  ```
* Get dependancies with Composer: 
  ```bash
    $ composer install (windows)
   # OR 
    $ composer.phar install (Mac)
  ```
* Create the database: 
  ```bash
    $ php bin/console doctrine:database:create
  ```
* Update database : 
  ```bash
    $ php bin/console doctrine:migration:migrate
  ```
* Run the project : 
  ```bash
    $ php bin/console server:run
  ```

LAUNCH TEST
-----------
  ```bash
    $ php bin/phpunit
  ```

Enjoy !!!
---------
