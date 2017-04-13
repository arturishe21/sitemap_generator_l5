<?php namespace Vis\SitemapGenerator;

abstract class AbstractSitemapObject
{
    protected $url        = '';
    protected $changefreq = 'weekly';
    protected $priority   = '0.5';
    protected $lastmod    = '';

    //fixme this method looks bad
    abstract protected function setKey($key);

    /**
     * @param array $params
     * @return $this
     */
    public function setParams($params)
    {
        if(is_array($params)){
            foreach($params as $key => $value){
                $this->$key = $value;
            }
        }else{
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
        if(!$this->getLastmod()){
            return false;
        }

        return date("c", strtotime($this->getLastmod()));
    }
    
    //fixme is converting looks bad
    protected function convertToArray()
    {
        return [
            'url'        => $this->getAssetUrl(),
            'date'       => $this->getLastmodDate(),
            'changefreq' => $this->getChangefreq(),
            'priority'   => $this->getPriority(),
        ];
    }

    abstract public function getLinksArray();

}