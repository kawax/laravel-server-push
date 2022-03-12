<?php

namespace Revolution\ServerPush;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LinkBuilder
{
    protected Collection $links;

    public function __construct()
    {
        $this->links = collect([]);

        $this->loadDefault();

        $this->loadManifest();
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $links = $this->links->map(function ($link) {
            $path = Arr::get($link, 'path');
            $type = Arr::get($link, 'type');

            return "<{$path}>; rel=preload; as={$type}".($type === 'font' ? '; crossorigin' : '');
        });

        return $links->implode(',');
    }

    protected function loadDefault()
    {
        collect(config('server-push.default_links', []))
            ->each(function ($paths, $type) {
                $type = rtrim($type, 's');

                collect($paths)->each(function ($path) use ($type) {
                    $this->addLink($path, $type);
                });
            });
    }

    protected function loadManifest()
    {
        if (! config('server-push.autolink_from_manifest', false)) {
            return;
        }

        $manifest_path = config('server-push.manifest_path');
        if (! File::exists($manifest_path)) {
            return;
        }

        collect(json_decode(File::get($manifest_path), true))
            ->values()
            ->each(function ($path) {
                $this->addLink($path);
            });
    }

    /**
     * @param  string  $path
     * @param  string|null  $type
     * @return $this
     */
    public function addLink(string $path, string $type = null): self
    {
        $link = [
            'path' => $path,
            'type' => $type ?? $this->type($path),
        ];

        $this->links->add($link);

        return $this;
    }

    /**
     * @param  string  $path
     * @return string
     */
    protected function type(string $path): string
    {
        $extension = File::extension($path);
        $extension = Str::before($extension, '?');

        switch ($extension) {
            case 'css':
                return 'style';
            case 'js':
                return 'script';
            case 'ttf':
            case 'otf':
            case 'woff':
            case 'woff2':
                return 'font';
            default:
                return 'image';
        }
    }
}
