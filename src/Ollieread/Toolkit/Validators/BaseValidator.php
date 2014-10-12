<?php namespace Ollieread\Toolkito\Validators;

use Illuminate\Validation\Factory;
use Ollieread\Toolkit\Exceptions\ValidationException;

abstract class BaseValidator
{
    public static $rules    = ['create' => [], 'update' => []];
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
     * Generic method
     *
     * @param String $action   The action that define the validation. It corresponds to the array key on the Validator file.
     * @param array  $data     Array of data to validate against.
     * @param array  $rules    Additionnal rules that could come from the controller.
     * @param array  $messages Additionnal messages that could come from the controller.
     *
     * @return bool
     * @throws \Exception
     * @throws ValidationException
     */
    public function validFor($action, array $data, array $rules = [], array $messages = [])
    {
        if (!isset($action) || is_array($action) || !is_string($action)) {
            throw new \Exception('Invalid validation rulset for ' . $action);
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
     * @param array $messages
     *
     * @return bool
     * @throws ValidationException
     */
    private function fire(array $data, array $rules = [], array $messages = [])
    {
        $validation = $this->validator->make($data, $rules, $messages);

        if ( $validation->fails()) throw new ValidationException($validation->messages());
        return true;
    }

}
