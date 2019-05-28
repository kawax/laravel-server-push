<?php

namespace Tests;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Revolution\ServerPush\LinkBuilder;
use Revolution\ServerPush\ServerPush;

class ServerPushTest extends TestCase
{
    public function testRender()
    {
        $builder = new LinkBuilder();

        $links = $builder->render();

        $this->assertNotEmpty($links);
        $this->assertContains('<css/test.css>; rel=preload; as=style', $links);
    }

    public function testManifestDisable()
    {
        config(['server-push.autolink_from_manifest' => false]);

        $builder = new LinkBuilder();

        $links = $builder->render();

        $this->assertNotEmpty($links);
        $this->assertNotContains('/js/app.js?id=aaaa', $links);
    }

    public function testManifestEmpty()
    {
        config(['server-push.manifest_path' => '']);

        $builder = new LinkBuilder();

        $links = $builder->render();

        $this->assertNotEmpty($links);
        $this->assertNotContains('/js/app.js?id=aaaa', $links);
    }

    public function testMiddleware()
    {
        $middle = new ServerPush();

        /**
         * @var Response $res
         */
        $res = $middle->handle(Request::create(''), function ($request) {
            return Response::create('', 200, ['Content-Type' => 'text/html']);
        });

        $this->assertTrue($res->headers->has('Link'));
        $this->assertContains('<css/test.css>; rel=preload; as=style', $res->headers->get('Link'));
    }

    public function testMiddlewareRedirect()
    {
        $middle = new ServerPush();

        $res = $middle->handle(Request::create(''), function ($request) {
            return RedirectResponse::create('http://localhost');
        });

        $this->assertFalse($res->headers->has('Link'));
    }

    public function testMiddlewarePost()
    {
        $middle = new ServerPush();

        $res = $middle->handle(Request::create('', 'POST'), function ($request) {
            return Response::create('', 200, ['Content-Type' => 'text/html']);
        });

        $this->assertFalse($res->headers->has('Link'));
    }

    public function testMiddlewareString()
    {
        $middle = new ServerPush();

        $res = $middle->handle(Request::create(''), function ($request) {
            return 'test';
        });

        $this->assertNotEmpty($res);
    }

    public function testAddLink()
    {
        /**
         * @var LinkBuilder $builder
         */
        $builder = resolve(LinkBuilder::class);

        $builder = $builder->addLink('/image/image.png')
                           ->addLink('/css/test.css');

        $this->assertInstanceOf(LinkBuilder::class, $builder);
        $this->assertContains('</image/image.png>; rel=preload; as=image', $builder->render());
    }
}
