<?php

namespace test\Table;

/**
 * Description of Table
 * 表基类
 * @author dongasai
 */
class Table
{

    public $fields = [];

    public function __construct($where)
    {
        if (empty($where)) {
            throw new \Exception("没有查询条件");
        }
        foreach ($where as $k => $v) {
            $this->$k = $v;
        }
        foreach ($this->fields as $k => $name) {
            $func       = 'get' . ucfirst($name);
            $this->$name = call_user_func([$this, $func]);
        }
    }


    
    public function __get($name)
    {
        if (isset($this->fields[$name])) {
            $func = 'get' . ucfirst($name);
            return call_user_func([$this, $func]);
        }
        throw new \Exception("不存在的字段");
    }

    /**
     * 查找数据
     * @param array $where
     * @return \self
     * @throws \Exception
     */
    public static function find(array $where)
    {
        $table = new self();
        if (empty($where)) {
            throw new \Exception("没有查询条件");
        }
        foreach ($table as $k => $v) {
            $table->$k = $v;
        }
        return $table;
    }

}
