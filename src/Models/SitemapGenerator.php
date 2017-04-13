<?php namespace Vis\SitemapGenerator;

class SitemapGenerator
{
    private $links = [];

    /**
     * @param string $value
     * @return mixed
     */
    public function getConfigValue($value)
    {
        return \Config::get('sitemap-generator.sitemap.'.$value);
    }

    /**
     * @return mixed
     */
    private function getLinks()
    {
        return $this->links;
    }

    private function addToLinks(array $links)
    {
        //fixme array merge wtf?
        $this->links = array_merge($this->links, $links);
    }

    private function getModelName($type)
    {
        switch ($type) {
            case 'models':
                $modelName = "SitemapModel";
                break;
            case 'custom_links':
                $modelName = "SitemapLink";
                break;
            default:
                $modelName = null;
                break;
        }

        //fixme is this good enough?
        return __NAMESPACE__ . '\\' .$modelName;
    }

    private function handleLinks($type)
    {
        $links     = $this->getConfigValue($type);
        $typeModel = $this->getModelName($type);

        if(empty($links) || !$typeModel){
            return false;
        }

        foreach ($links as $key => $params) {
            $links = (new $typeModel($key,$params))->getLinksArray();
            $this->addToLinks($links);
        }

        return true;
    }

    public function makeSitemap()
    {
        $this->handleLinks('custom_links');
        $this->handleLinks('models');

        return $this->getLinks();
    }

}