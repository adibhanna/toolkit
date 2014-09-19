<?php namespace Ollieread\Toolkit\Validators;

use Illuminate\Validation\Factory;
use Ollieread\Toolkit\Exceptions\ValidationException;

abstract class BaseValidator
{

    /**
     * The validation rules to apply.
     *
     * Create and update are set as default so that we don't get key not found.
     *
     * @var array
     */
    public static $rules    = ['create' => [], 'update' => []];

    /**
     * Custom messages for the validation rules.
     *
     * Again, create and update are set so that we don't get key not found errors.
     *
     * @var array
     */
    public static $messages = ['create' => [], 'update' => []];

    /**
     * @var \Illuminate\Validation\Factory
     */
    protected $validator;

    /**
     * @param Factory $validator
     */
    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Catch the validation and run for the action.
     *
     * @param $method
     * @param $arguments
     * @return bool
     * @throws ValidationException
     */
    public function __call($method, $arguments)
    {
        if (starts_with($method, 'validFor')) {
            $name = snake_case(substr($method, 8));

            if (isset(static::$messages[$name])) {
                $messages = static::$messages[$name];
            } else {
                $messages = [];
            }

            if (isset(static::$rules[$name])) {
                $rules = static::$rules[$name];

                if (isset($arguments[1]) && is_array($arguments[1])) {
                    $rules = array_merge($rules, $arguments[1]);
                }

                return $this->fire($arguments[0], $rules, $messages);
            }
        }
    }

    /**
     * This handles the calling of the individual validation actions.
     *
     * @param $action
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return bool
     * @throws ValidationException
     * @throws \Exception
     */
    protected function validFor($action, array $data, array $rules = [], array $messages = [])
    {
        if (!isset($action) || is_array($action) || !is_string($action)) {
            throw new \Exception('Invalid valid for ' . $action);
        }

        $rules    = array_key_exists($action, static::$rules)    ? array_merge(static::$rules[$action], $rules) : $rules;
        $messages = array_key_exists($action, static::$messages) ? array_merge(static::$messages[$action], $messages) : $messages;

        return $this->fire($data, $rules, $messages);
    }

    /**
     * Trigger validation
     *
     * @param array $data
     * @param array $rules
     *
     * @return bool
     * @throws ValidationException
     */
    private function fire(array $data, array $rules = [])
    {
        $validation = $this->validator->make($data, $rules);

        if ( $validation->fails()) throw new ValidationException($validation->messages());

        return true;
    }
}