<?php

return [

    /*
     * This is the path for your routes directory, it may be app/routes or it may be
     * app/Project/Routes, whatever.
     */
    'routes_directory'      => app_path() . '/routes/',

    /*
     * Whether or not to use the default error handler for ValidationException, this
     * will basically catch validation errors and redirect back with input and errors.
     *
     * Set to false if you wish to define your own.
     */
    'catch_validation'      => true,

    /*
     * Whether or not updates should be tidied up. This will basically go through the dat passed
     * to BaseRepository::tidy($model, $data) and compare against the value in the model, anything
     * that matches is stripped from the array.
     */
    'pre_update_clean'      => true,

    /*
     * Whether or not to remove empty values from an update, set to false if you wish to write empty values
     * to your database.
     */
    'pre_update_empty'      => true

];