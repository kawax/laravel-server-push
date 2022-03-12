<?php

namespace Revolution\ServerPush;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ServerPush
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * @var Response $response
         */
        $response = $next($request);

        if ($this->shouldPush($request, $response)) {
            $builder = app(LinkBuilder::class);

            $response->headers->set('Link', $builder->render(), false);
        }

        return $response;
    }

    /**
     * @param  Request  $request
     * @param  Response  $response
     * @return bool
     */
    protected function shouldPush(Request $request, $response): bool
    {
        return ! $request->ajax()
            && $request->method() === 'GET'
            && $response instanceof Response
            && Str::contains($response->headers->get('Content-Type') ?? '', 'text/html');
    }
}
