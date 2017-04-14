<?php namespace Vis\SitemapGenerator;

abstract class AbstractSitemapObject
{
    protected $url        = '';
    protected $changefreq = 'weekly';
    protected $priority   = '0.7';
    protected $lastmod    = '';

    /**
     * @param string $key
     * @param string $params
     */
    public function __construct($key, $params)
    {
        $this->setKey($key)->setParams($params);
    }

    abstract protected function setKey($key);

    /**
     * @param array $params
     * @return $this
     */
    protected function setParams($params)
    {
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $this->$key = $value;
            }
        } else {
            $this->setKey($params);
        }

        return $this;
    }

    /**
     * @return string
     */
    private function getUrl()
    {
        return $this->url;
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
     * @return string
     */
    private function getLastmod()
    {
        return $this->lastmod;
    }

    /**
     * @return string
     */
    private function getAssetUrl()
    {
        return asset($this->getUrl());
    }

    /**
     * @return string
     */
    private function getLastmodDate()
    {
        if (!$this->getLastmod()) {
            return false;
        }

        return date("c", strtotime($this->getLastmod()));
    }

    /**
     * @return array $alternateUrls
     */
    private function getAlternateUrls()
    {
        $alternateUrls = [];
        $isMulti = \Config::get('sitemap-generator.sitemap.is_multi_language');

        if($isMulti) {
            $langs = \LaravelLocalization::getSupportedLocales();

            foreach($langs as $lang => $description){

                $alternateUrls[] = [
                    'hreflang' => $lang,
                    'href'     => \LaravelLocalization::getLocalizedURL($lang,$this->getAssetUrl()),
                ];
            }
        }

        return $alternateUrls;
    }
    
    protected function convertToArray()
    {
        return [
            'url'            => $this->getAssetUrl(),
            'alternate_urls' => $this->getAlternateUrls(),
            'date'           => $this->getLastmodDate(),
            'changefreq'     => $this->getChangefreq(),
            'priority'       => $this->getPriority(),
        ];
    }

    abstract public function getLinksArray();

}
