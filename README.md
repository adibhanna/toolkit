Ollie's Laravel Toolkit
=======================

A small toolkit that I, Ollie, use for various projects.

## Features ##

 - [Router](#router)
   - [Route::file()](#router-filer)
   - [Modifications](#router-modifications)
 - Input
   - Nothing yet
 - [Validators](#validators)
   - [BaseValidator](#validators-base)
   - [ValidationException](#validators-exception)
 - [Repositories](#repositories)
   - [BaseRepository](#repositories-base)


<a name="router" />
## Router ##

### Route::file() ###

This method is added to simplify the process of tidying up your routes file. Quite commonly, people complain at the size and I've seen all sorts of methods to split it up, this works like the `Route::group()` method but allows for extra files.

```php
    Route::file($prefix, $file[, $attributes]);
```

The method takes three arguments, `$prefix` being the value that will be passed to the prefix attribute of the group, `$file` being the name of the file, and `$attributes` being an entirely optional argument that works the same way as it would on `Route::group()`.

***NOTE*** Adding a prefix to attributes will cause it be overwritten with the value of the first argumnent.

#### Example ####

***app/routes/user.php***

```php
<?php

Route::get('/', [
	'as'	=> 'user.index',
	'uses'	=> 'UserController@index'
]);

Route::get('/{id}', [
	'as'	=> 'user.show',
	'uses'	=> 'UserController@show'
]);
```

***app/routes.php***

```php
<?php

Route::file('/user', 'user.php', ['namespace' => 'MyApp\Controllers\User', 'before' => 'auth']);
```