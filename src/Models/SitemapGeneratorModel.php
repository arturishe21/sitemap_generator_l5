<?php namespace Vis\SitemapGenerator;

class SitemapGeneratorModel
{
    private $links;

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return $this->links;
    }

    public function addMainPage()
    {
        $item['url'] = asset("/");
        $item['changefreq'] = 'hourly';
        $item['priority'] = 1;
        $this->links[$item['url']] = $item;
    }

    //fixme this method should be refactored
    public function getEntities($model, $params)
    {
        $model = new $model;

        if (isset($params['is_active_field']) && $params['is_active_field'] != false) {
            $model = $model->where($params['is_active_field'], 1);
        }elseif(!isset($params['is_active_field'])){
            $model = $model->where('is_active', 1);
        }

        if (isset($params['additional_where']) && count($params['additional_where'])) {
            foreach ($params['additional_where'] as $fieldName => $condition) {
                $model = $model->where($fieldName, $condition['sign'], $condition['value']);
            }
        }

        $entities = $model->get();

        if(isset($params['get_url_method'])){
            $urlMethod = $params['get_url_method'];
        }else{
            $urlMethod = "getUrl";
        }

        if (isset($params['lastmod_field']) && $params['lastmod_field'] != false) {
            $lastmodField = $params['lastmod_field'];
        } elseif (!isset($params['lastmod_field'])) {
            $lastmodField = "updated_at";
        } else {
            $lastmodField = false;
        }

        foreach ($entities as $link) {
            $item['url']        = $link->$urlMethod();
            $item['date']       = $lastmodField  ? date("c", strtotime($link->$lastmodField)) : "";
            $item['changefreq'] = $params['changefreq'];
            $item['priority']   = $params['priority'];

            $this->links[$item['url']] = $item;
        }
    }

}