<?php namespace Ollieread\Toolkit\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Config;

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
     * @param bool $sortField
     *
     * @return array
     */
    public function lists($value, $key = null, $select = false, $id = 0, $sortField = false)
    {
        $list = $this->make();

        if($sortField && is_string($sortField)) {
            $list = $list->orderBy($sortField);
        }

        $list = $list->lists($value, $key);

        if(!$select) {
            return $list;
        } else {
            $return = [-1 => 'Please select'];

            foreach($list as $key => $value) {
                if(is_array($id)) {
                    if(in_array($key, $id)) {
                        continue;
                    }
                } elseif($id != 0 && $key == $id) {
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

        $data = $this->tidy($model, $data);

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

    /**
     * @param Eloquent $model
     * @param array $data
     * @return array
     */
    protected function tidy(Eloquent $model, array $data)
    {
        $empty = Config::get('toolkit::pre_update_empty', true);
        $clean = Config::get('toolkit::pre_update_clean', true);

        foreach($data as $key => $value) {
            if($empty && empty($value)) {
                unset($data[$key]);
            } else {
                if ($clean && $model->$key == $value) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }

}