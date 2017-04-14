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

    /**
     * @param array $links
     */
    private function addToLinks(array $links)
    {
        $this->links = array_merge($this->links, $links);
    }

    /**
     * @param  string $type
     * @return string $modelName
     */
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

        if (!$modelName) {
            return false;
        }

        return __NAMESPACE__ . '\\' .$modelName;
    }

    /**
     * @param  string $type
     * @return boolean
     */
    private function handleLinks($type)
    {
        $entities  = $this->getConfigValue($type);
        $typeModel = $this->getModelName($type);

        if (empty($entities) || !$typeModel) {
            return false;
        }

        foreach ($entities as $key => $params) {
            $links = (new $typeModel($key, $params))->getLinksArray();
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
