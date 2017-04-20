<?php namespace Vis\SitemapGenerator;

class SitemapModel extends AbstractSitemapObject
{

    protected $model;
    protected $entity;

    protected $lastmod_field    = "updated_at";
    protected $url_method       = "getUrl";
    protected $active_field     = 'is_active';
    protected $additional_where = [];

    /**
     * @param string $key
     * @return $this
     */
    protected function setKey($key)
    {
        $this->model = $key;
        return $this;
    }

    /**
     * @return $this
     */
    protected function setUrl()
    {
        $field = $this->url_method;
        $this->url = $field ? $this->entity->$field() : "/" ;

        return $this;
    }

    /**
     * @return $this
     */
    protected function setLastmod()
    {
        $field = $this->lastmod_field;
        $this->lastmod = $field ? $this->entity->$field : "";

        return $this;
    }

    /**
     * @return array
     */
    protected function getAdditionalWhere()
    {
        return $this->additional_where;
    }

    /**
     * @return string
     */
    protected function getActiveField()
    {
        return $this->active_field;
    }

    /**
     * @return string
     */
    public function getChangefreq()
    {
        return $this->changefreq;
    }

    /**
     * @return array $links
     */
    public function getLinksArray()
    {
        $links = [];

        $this->model = new $this->model;

        if($this->getActiveField()){
            $this->model = $this->model->where($this->getActiveField(), "=", 1);
        }

        foreach ($this->getAdditionalWhere() as $fieldName => $condition) {

            $condition['sign'] = strtoupper($condition['sign']);

            switch ($condition['sign']) {
                case "IN":
                    $this->model = $this->model->whereIn($fieldName, $condition['value']);
                    break;
                case 'NOT IN':
                    $this->model = $this->model->whereNotIn($fieldName, $condition['value']);
                    break;
                case "BETWEEN":
                    $this->model = $this->model->whereBetween($fieldName, $condition['value']);
                    break;
                case 'NOT BETWEEN':
                    $this->model = $this->model->whereNotBetween($fieldName, $condition['value']);
                    break;
                case "NULL":
                    $this->model = $this->model->whereNull($fieldName);
                    break;
                case "NOT NULL":
                    $this->model = $this->model->whereNotNull($fieldName);
                    break;
               default:
                    $this->model = $this->model->where($fieldName, $condition['sign'], $condition['value']);
                    break;
            }
        }

        $entities = $this->model->get();

        foreach($entities as $this->entity){
            $links[] = $this->setUrl()->setLastmod()->convertToArray();
        }

        return $links;
    }

}
