<?php namespace Ollieread\Toolkit\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class BaseRepository
 *
 * @package Ollieread\Toolkit\Repositories
 */
class BaseRepository
{

    /**
     * @var
     */
    protected $model;

    /**
     * @var
     */
    protected $validator;

    /**
     * @param $model
     *
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param $validator
     *
     * @return $this
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * @param $context
     *
     * @return $this
     */
    public function setContext($context)
    {
        return $this->setModel($context);
    }

    /**
     * @return mixed
     */
    public function make()
    {
        return $this->model;
    }

    /**
     * @param int $paginate
     *
     * @return mixed
     */
    public function all($paginate = 0)
    {
        if($paginate > 0) {
            return $this->make()->paginate($paginate);
        }

        return $this->make()->get();
    }

    /**
     * @param      $value
     * @param null $key
     * @param bool $select
     * @param int  $id
     *
     * @return array
     */
    public function lists($value, $key = null, $select = false, $id = 0)
    {
        $list = $this->make()->lists($value, $key);

        if(!$select) {
            return $list;
        } else {
            $return = ['Please Select One'];

            foreach($list as $key => $value) {
                if($id != 0 && $key == $id) {
                    continue;
                }
                $return[$key] = $value;
            }

            return $return;
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function get($id)
    {
        return $this->make()->findOrFail($id);
    }

    /**
     * @param $field
     * @param $value
     *
     * @return mixed
     */
    public function getBy($field, $value)
    {
        return $this->make()->where($field, $value)->first();
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        $this->validator->validForCreate($data);

        return $this->make()->create($data);
    }

    /**
     * @param       $id
     * @param array $data
     *
     * @return bool
     */
    public function update($id, array $data)
    {
        if(!($id instanceof Eloquent)) {
            $model = $this->get($id);
        } else {
            $model = $id;
        }

        $this->validator->validForUpdate($data);

        $model->fill($data);

        return $model->save();
    }

    /**
     * @param $id
     *
     * @return bool|null
     */
    public function delete($id)
    {
        if(!($id instanceof Eloquent)) {
            $model = $this->get($id);
        } else {
            $model = $id;
        }

        return $model->delete();
    }

    /**
     * @param $with
     *
     * @return $this
     */
    public function with($with)
    {
        $this->setModel($this->make()->with($with));

        return $this;
    }

    /**
     * @return mixed
     */
    public function wipe()
    {
        return $this->make()->truncate();
    }

}