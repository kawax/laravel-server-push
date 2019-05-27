<?php

namespace Revolution\ServerPush;

use Closure;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class ServerPush
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * @var Response $response
         */
        $response = $next($request);

        if ($this->shouldPush($request, $response)) {
            $builder = resolve(LinkBuilder::class);

            $response->headers->set('Link', $builder->render(), false);
        }

        return $response;
    }

    /**
     * @param  Request  $request
     * @param  Response  $response
     *
     * @return bool
     */
    protected function shouldPush(Request $request, $response): bool
    {
        return ! $request->ajax()
            and $request->method() === 'GET'
            and $response instanceof Response
            and Str::contains($response->headers->get('Content-Type') ?? '', 'text/html');
    }
}
