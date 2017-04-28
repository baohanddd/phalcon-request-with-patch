<?php
class Request extends \Phalcon\Http\Request
{
    /**
     * @param null $name
     * @param null $filters
     * @return array
     */
    public function getPatch($name=null, $filters=null) {
        $items = [];
        if($this->isPatch()) {
            $filter = $this->getDI()->get('filter');
            $params = $this->getArrayFromRawBody();
            foreach($params as $k => $v) {
                if($name && $name != $k) continue;
                if($filters) {
                    if(is_array($filters)) {
                        foreach($filters as $f) $v = $filter->sanitize($v, $f);
                    } else {
                        $v = $filter->sanitize($v, $filters);
                    }
                }
                $items[$k] = $v;
            }
        }
        return ($name) ? $items[$name] : $items;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasPatch($name)
    {
        $has = false;
        if($this->isPatch()) {
            $params = $this->getArrayFromRawBody();
            foreach($params as $k => $v) {
                if($name && $name == $k) $has = true;
            }
        }
        return $has;
    }

    /**
     * @return array
     */
    private function getArrayFromRawBody()
    {
        $items = [];
        $raw = $this->getRawBody();
        foreach(explode('&', $raw) as $pair) {
            list($k, $v) = explode('=', $pair);
            $items[$k] = $v;
        }
        return $items;
    }
}