Ollie's Laravel Toolkit
=======================

A small toolkit that I, Ollie, use for various projects.

## Features ##

 - [Router](#router)
   - [Route::file()](#router-file)
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

<a name="router-file" />
### Route::file() ###

This method is added to simplify the process of tidying up your routes file. Quite commonly, people complain at the size and I've seen all sorts of methods to split it up, this works like the `Route::group()` method but allows for extra files.

```php
Route::file($prefix, $file[, $attributes]);
```

The method takes three arguments, `$prefix` being the value that will be passed to the prefix attribute of the group, `$file` being the name of the file, and `$attributes` being an entirely optional argument that works the same way as it would on `Route::group()`.

***NOTE*** Adding a prefix to `$attributes` will have no affect and will be overwritten with the value of `$prefix`.

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

Route::file('/user', 'user.php', [
    'namespace' => 'MyApp\Controllers\User', 
    'before' => 'auth'
]);
```
<a name="router-modifications" />
###Modifications###

There are two modifications made to the Router.

####Route::controller()####

This will no longer work while debug mode is disabled in `app/config/app.php`. This is because this method should not be used in production and instead developers should be in the habit of defining routes to secure and control their app accordingly.

####Route::resource()####

This will also no longer work while debug mode is disabled in `app/config/app.php`. This is because it has no use, the only time you should be using methods that aren't POST or GET, are when you're writing an API, and when you're writing an API you should be clearly defining all of your routes.

<a name="validators" />
##Validators##

<a name="validators-base" />
###BaseValidator###

A simple abstract validator class to allow you to separate the validation out. First you define your validator.

```php
<?php namespace MyApp\Validators;

use Ollieread\Toolkit\Validators\BaseValidator;

class UserValidator extends BaseValidator
{

    public static $rules = [
        'create'	=> [
            // put rules here
        ],
        'update'	=> [
            // put rules here
        ]
    ];
    
}
```

Now you can do something like `App::make('MyApp\Validators\UserValidator')` and call either of the following methods.

####validForCreate($data)####

Run the passed data through the rules within the create part of the `$rules` array.

####validForUpdate($data[, $rules])####

Run the passed data through the rules within the update part of the `$rules` array.

You can also optionally pass in your own rules to override or append to those that have been predefined.

<a name="validators-exception" />
###ValidationException###

When validation isn't passed a `ValidationException` is thrown which can be caught, and by using the `getErrors()` method you can get a `MessageBag` of the errors that were generated.

If you do not catch it, there is a default handler which will redirect to the previous page, with the input and the errors (if there was a referer), to save messing around adding redirects and try/catch blocks.
