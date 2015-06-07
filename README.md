# InstantCake plugin for CakePHP

InstantCake is cakephp build-in-Server include custom php.ini File for CakePHP3.0

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require webuilder240/instant-cake
```

## Configuration

Set the InstantCake in bootstrap.php

config/bootstrap.php

``` php

<?php
	// Only try to load DebugKit in development mode
	// Debug Kit should not be installed on a production system
	if (Configure::read('debug')) {
		Plugin::load('DebugKit', ['bootstrap' => true]);
		Plugin::load('InstantCake');
	}
```

## Usage

``` bash
# -c options is php.ini file location
bin/cake instant_cake -c php.ini 
```
