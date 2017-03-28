<?php

    Route::group ([
        'middleware' => ['web'],
        'namespace'  => 'Vis\SitemapGenerator',
    ], function () {

        Route::get(
            'sitemap.xml', array(
                'as' => 'show_sitemap',
                'uses' => 'SitemapGeneratorController@showSiteMapXML',
            )
        );

    });


