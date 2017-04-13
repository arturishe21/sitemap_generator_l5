<?php namespace Vis\SitemapGenerator;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class SitemapGeneratorController extends Controller
{
    private $sitemap;

    public function __construct()
    {
        $this->sitemap = new SitemapGenerator();
    }

    public function showSiteMapXML()
    {
        if(!$this->sitemap->getConfigValue('is_enabled')){
            abort(404);
        }

        $view = View::make('sitemap-generator.sitemap')->with('links', $this->sitemap->makeSitemap());

        return Response::make($view, '200')->header('Content-Type', 'text/xml; charset="utf-8"');
    }
}