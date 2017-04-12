<?php namespace Vis\SitemapGenerator;

class SitemapGenerator
{
    private $links = [];

    private $model;
    private $url;

    private $changefreq;
    private $priority;
    private $url_method;
    private $lastmod;
    private $is_active;
    private $additional_where;

    private function setDefaultState()
    {

        $this->model  = "";
        $this->url = "";

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
    private function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    private function setParams($params)
    {
        foreach($params as $key => $value){
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function getConfigValue($value)
    {
        return \Config::get('sitemap-generator.sitemap.'.$value);
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
        return $field ? $link->$field() : "/" ;
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
    private function getLinks()
    {
        return $this->links;
    }

    private function addToLinks($url, $date = null)
    {
        $this->links[$url] = [
            'url'        => asset($url),
            'date'       => $date,
            'changefreq' => $this->getChangefreq(),
            'priority'   => $this->getPriority(),
        ];
    }

    private function getCustomLink()
    {
        $this->addToLinks(
            $this->url     ?: $this->model,
            $this->lastmod ?: ""
        );
    }

    private function getModelLinks()
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

    private function handleCustomLinks()
    {
        $links = $this->getConfigValue('custom_links');

        if(empty($links)){
            return false;
        }

        foreach ($links as $key => $params) {
            $this->setDefaultState()->setModel($key)->setParams($params)->getCustomLink();
        }

        return true;
    }

    private function handleModelsLinks()
    {
        $models = $this->getConfigValue('models');

        if(empty($models)){
            return false;
        }

        foreach ($models as $key => $params) {
            $this->setDefaultState()->setModel($key)->setParams($params)->getModelLinks();
        }

        return true;
    }


    public function makeSitemap()
    {
        $this->handleCustomLinks();
        $this->handleModelsLinks();

        return $this->getLinks();
    }

}