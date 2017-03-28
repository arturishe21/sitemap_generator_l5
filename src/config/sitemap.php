<?php
/**
 * Sitemaps XML format docs @link https://www.sitemaps.org/protocol.html
 * Sitemap validator @link https://validator.w3.org/#validate_by_uri
 */

return array(
    /**
     * Option to enabled\disable Sitemap generation, if disabled shows 404 error
     */
    'is_enabled' => true,

    /**
     * Array of models that will be used for classmap generation
     */
    'models' => ([

        'Tree' => [
            // Valid values are "always|hourly|daily|weekly|monthly|yearly|never"
            'changefreq'       => "daily",

            // Valid values range from 0.0 to 1.0.
            'priority'         => 0.7,

            // method that will be called upon model to get Url of entity
            // default(if removed) - "getUrl" or set your method name
            'get_url_method'   => "getUrl",

            // optional property. default(if removed) - "updated_at", set false to disable or set your field name
            'lastmod_field'    => "updated_at",

            // optional property. default(if removed) - "is_active", set false to disable or set your field name
            'is_active_field'  => "is_active",

            // optional property allows to specify query, can be removed if not required
            'additional_where' => [
                'template' => [
                    'sign'  => '!=',
                    'value' => 'main'
                ],
            ],
        ],
    ]),
);