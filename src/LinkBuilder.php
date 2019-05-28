<?php

namespace Revolution\ServerPush;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class LinkBuilder
{
    /**
     * @var Collection
     */
    protected $links;

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
            return '<'.Arr::get($link, 'path').'>; rel=preload; as='.Arr::get($link, 'type');
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
            ->each(function ($file) {
                $this->addLink($file);
            });
    }

    /**
     * @param  string  $file
     * @param  string|null  $type
     *
     * @return  $this
     */
    public function addLink(string $file, string $type = null)
    {
        $link = [
            'path' => $file,
            'type' => $type ?? $this->type($file),
        ];

        $this->links->add($link);

        return $this;
    }

    /**
     * @param  string  $file
     *
     * @return string
     */
    protected function type(string $file): string
    {
        $extension = File::extension($file);
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
