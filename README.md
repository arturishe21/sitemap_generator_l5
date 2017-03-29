Model based sitemap generator
Generated Sitemap will be available at http(s)://yoursite.com/sitemap.xml

Add this to composer.json require section
```json
     "vis/sitemap_generator_l5": "1.*"
```

Execute
```json
    composer update
```

Add SitemapGeneratorServiceProvider to ServiceProviders in config/app.php
```php
   Vis\SitemapGenerator\SitemapGeneratorServiceProvider::class,
```

Publish sitemap view
```php
    php artisan vendor:publish --provider="Vis\SitemapGenerator\SitemapGeneratorServiceProvider" --force
```

Add your models to config at app/config/sitemap-generator/sitemap.php

Short example that will use default options
```php
    'models' => ([
        'Tree' => [
            'changefreq'       => "daily",
            'priority'         => 0.7,
        ],
    ]),
```

Full example of possible options
```php
    'models' => ([
        'Tree_1' => [
            //If this param is set model name will be taken from here rather then from array key
            //This allows to have multiple request to single model without overriding results
            'model' => "Tree",

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

            // optional property. allows to specify query, can be removed if not required
            'additional_where' => [
                'template' => [
                    'sign'  => '!=',
                    'value' => 'main'
                ],
            ],
        ],
    ]),
```