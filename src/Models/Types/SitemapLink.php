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

    /**
     * @return array $links
     */
    public function getLinksArray()
    {
        $links[] = $this->convertToArray();

        return $links;
    }

}
