<?php namespace Vis\SitemapGenerator;

class SitemapLink extends AbstractSitemapObject
{
    /**
     * @param string $key
     * @return $this
     */
    protected function setKey($key)
    {
        $this->url = $key;
        return $this;
    }

    //fixme links array? wtf.
    public function getLinksArray()
    {
        $links[] = $this->convertToArray();

        return $links;
    }

}