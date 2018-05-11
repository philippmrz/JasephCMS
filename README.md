<img src=https://rawgit.com/phmrz/JasephCMS/master/assets/jaseph_normal.png width=250px>

Jaseph Content Management System created by <a href=https://github.com/phmrz>phmrz</a>, <a href=https://github.com/Kaeks>Kaeks</a> and <a href=https://github.com/Zaby0>Zaby0</a>.

This CMS is still in an early stage and does not include **any** security measures regarding user database (passwords).

### Installation with docker
* Clone repository to local machine.
* Run `docker-compose up` (if necessary with root permissions) inside `docker/` directory.
* Create file with name `credentials.php` inside `require/` with content:

```
<?php
$db_servername = 'db';
$db_username   = <db user name>;
$db_password   = <db user password>;
$db_name     = 'jaseph';
$usertable  = 'user';
$posttable  = 'post';
$drafttable = 'draft';
$imgtable   = 'images';
?>
```
* Fill in your `$db_username` and `$db_password`.
* `$db_servername` must be `db`
* Visit `localhost` with port `8000`


### Installation without docker
* Set up a web server including an SQL database and a php7 installation.
* Extract the contents of this repository into your web server's host directory.
* Import `jaseph.sql` inside `docker/` into your database.
* Create file with name `credentials.php` inside `require/` with content:

```
<?php
$db_servername = '<db server address>';
$db_username   = <db user name>;
$db_password   = <db user password>;
$db_name     = 'jaseph';
$usertable  = 'user';
$posttable  = 'post';
$drafttable = 'draft';
$imgtable   = 'images';
?>
```
* Fill in your `$db_servername`, `$db_username` and `$db_password`.
* Visit your web server's address.
