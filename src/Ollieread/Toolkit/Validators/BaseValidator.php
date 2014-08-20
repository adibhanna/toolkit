<?php namespace Ollieread\Toolkit\Validators;

use Illuminate\Validation\Factory;
use Ollieread\Toolkit\Exceptions\ValidationException;

abstract class BaseValidator
{

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

    public function validForCreate(array $data)
    {
        return $this->fire($data, static::$rules['create']);
    }

    public function validForUpdate(array $data, array $rules = [])
    {
        return $this->fire($data, array_merge(static::$rules['update'], $rules));
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