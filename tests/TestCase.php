<?php

namespace Revolution\ServerPush\Tests;

use Revolution\ServerPush\Providers\ServerPushServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServerPushServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [

        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('server-push', [
            'default_links' => [
                'styles' => [
                    'css/test.css',
                ],

                'scripts' => [
                    'js/test.js',
                ],

                'images' => [
                    'image/test.jpg',
                ],

                'fonts' => [
                    'font/test.woff2',
                ],
            ],

            'autolink_from_manifest' => true,

            'manifest_path' => __DIR__.'/mix-manifest.json',
        ]);
    }
}
