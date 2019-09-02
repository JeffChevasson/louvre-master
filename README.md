PROJET 4
========

"Projet 4" is a Symfony project created between June and August 2019. It's my fourth project with Openclassrooms.

The project’s target is to create the ticketing of Louvre Museum.

REQUIREMENTS
------------

PHP 5.5.9 or higher,
and the usual Symfony application requirements.

PRESENTATION OF THIS OPENCLASSROOMS'S PROJECT
---------------------------------------------


https://openclassrooms.com/fr/projects/developpez-un-back-end-pour-un-client


INSTALLATION
------------


* Go to your localhost file and launch Laragon
* Execute this command to clone the project: https://github.com/JeffChevasson/louvre-master.git
  ```bash 
    $ git clone 
  ```
 * Go to "louvre-master" file:
       ```bash 
        $ cd louvre-master
       ```
* Get dependancies with Composer: 
  ```bash
    $ composer install (windows)or $ composer.phar install (Mac)
  ```
* Create the database: 
  ```bash
    $ php bin/console doctrine:database:create
  ```
* Update database : 
  ```bash
    $ php bin/console doctrine:schema:update --force
  ```
* Run the project : 
  ```bash
    $ php bin/console server:run
  ```

Enjoy !!!
---------
