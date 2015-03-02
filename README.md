Memcached Manager
=================

[![Build Status](https://secure.travis-ci.org/fraserreed/memcached-manager.png?branch=master)](http://travis-ci.org/fraserreed/memcached-manager)
[![Coverage Status](https://coveralls.io/repos/fraserreed/memcached-manager/badge.svg)](https://coveralls.io/r/fraserreed/memcached-manager)
[![Code Climate](https://codeclimate.com/github/fraserreed/memcached-manager/badges/gpa.svg)](https://codeclimate.com/github/fraserreed/memcached-manager)

Memcached Manager is a modern implementation of the [Harun Yayli](https://twitter.com/haruny) memcached.php script.

### Features

* support for a multi-cluster, multi-node environments
* full cluster statistics
* add/edit/increment/decrement/flush keys

### Todo

* search keys
* key pagination
* key listing optimization

### Screenshots

##### Cluster Listing:

![image](https://cloud.githubusercontent.com/assets/3450927/6435462/899f6482-c06c-11e4-8f27-e20559836b39.png)

##### Cluster Detail:

![image](https://cloud.githubusercontent.com/assets/3450927/6435472/c1d834dc-c06c-11e4-9e5a-dd3b50da5d6a.png)

##### Key Listing:

![image](https://cloud.githubusercontent.com/assets/3450927/6435484/f14f3738-c06c-11e4-8a39-9fbd06de80ac.png)

### Installing via Composer

The recommended way to install Memcached Manager is through
[Composer](http://getcomposer.org).


First, install composer:

```
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of Guzzle:

```
composer require fraserreed/memcached-manager
```

After installing, you need to require Composer's autoloader:

```
require 'vendor/autoload.php';
```

### Contribution

Feel free to fork the repo and contribute in any way that you feel will make this a better solution.  For any issues or feature requests, open an issue or pull request.

### Documentation

Documentation in progress.