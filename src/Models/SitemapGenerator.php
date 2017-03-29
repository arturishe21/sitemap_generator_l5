<?php namespace Vis\SitemapGenerator;

class SitemapGenerator
{
    private $links;

    private $model;

    private $changefreq;
    private $priority;
    private $url_method;
    private $lastmod;
    private $is_active;
    private $additional_where;

    public function setDefaultState()
    {
        $this->changefreq = "weekly";
        $this->priority   = '0.5';
        $this->url_method = "getUrl";
        $this->lastmod    = "updated_at";
        $this->is_active  = "is_active";
        $this->additional_where = [];
        return $this;
    }

    /**
     * @param string $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams($params)
    {
        foreach($params as $key => $value){
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * @return string
     */
    private function getChangefreq()
    {
        return $this->changefreq;
    }

    /**
     * @return int
     */
    private function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return array
     */
    private function getAdditionalWhere()
    {
        return $this->additional_where;
    }

    /**
     * @return string
     */
    private function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param object
     * @return string
     */
    private function getUrlMethod($link)
    {
        $field = $this->url_method;
        return $field ? asset($link->$field()) : "/" ;
    }
    /**
     * @param object
     * @return string
     */
    private function getLastmod($link)
    {
        $field = $this->lastmod;
        return $field ? date("c", strtotime($link->$field)) : "" ;
    }

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return $this->links;
    }

    public function __construct()
    {
        $this->addToLinks(asset("/"));
    }

    private function addToLinks($url, $date = null)
    {
        $this->links[$url] = [
            'url'        => $url,
            'date'       => $date,
            'changefreq' => $this->getChangefreq(),
            'priority'   => $this->getPriority(),
        ];
    }

    public function getEntitiesLinks()
    {
        $model = new $this->model;

        if($this->getIsActive()){
            $model = $model->where($this->getIsActive(), "=", 1);
        }

        foreach ($this->getAdditionalWhere() as $fieldName => $condition) {
            $model = $model->where($fieldName, $condition['sign'], $condition['value']);
        }

        $entities = $model->get();

        foreach ($entities as $link) {
            $this->addToLinks(
                $this->getUrlMethod($link),
                $this->getLastmod($link)
            );
        }
    }

}