<?php namespace Vis\SitemapGenerator;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;


class SitemapGeneratorController extends Controller
{
    /**
     * @param string $value
     * @return mixed
     */
    private function getConfigValue($value)
    {
        return Config::get('sitemap-generator.sitemap.'.$value);
    }

    public function showSiteMapXML()
    {
        if(!$this->getConfigValue('is_enabled')){
            abort(404);
        }

        $models = $this->getConfigValue('models');

        $sitemap = new SitemapGeneratorModel();
        $sitemap->addMainPage();

        foreach ($models as $model => $params) {
            $sitemap->getEntities($model, $params);
        }

        $view = View::make('sitemap-generator.sitemap')->with('links', $sitemap->getLinks());

        return Response::make($view, '200')->header('Content-Type', 'text/xml');
    }



}