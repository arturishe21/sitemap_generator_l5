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

    private function handleModelsLinks()
    {
        $links = $this->getConfigValue('models');

        if(empty($links)){
            return false;
        }

        foreach ($links as $key => $params) {
            $links = (new SitemapModel())->setParams($params)->getLinksArray();
            $this->addToLinks($links);
        }

        return true;
    }

    private function handleCustomLinks()
    {
        $links = $this->getConfigValue('custom_links');

        if(empty($links)){
            return false;
        }

        foreach ($links as $key => $params) {
            $links = (new SitemapLink())->setParams($params)->getLinksArray();
            $this->addToLinks($links);
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