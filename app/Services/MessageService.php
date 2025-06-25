<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class MessageService
{
    protected string $locale;
    protected string $page;
    protected array $messages = [];

    public function __construct(Request $request)
    {
        $this->locale = Session::get('app_locale', 'ko');

        $this->page = $request->route()->getName() ?? 'default';

        $this->loadMessages();
    }

    protected function loadMessages(): void
    {
        $path = resource_path("lang_json/{$this->page}.json");

        if (File::exists($path)) {
            $json = json_decode(File::get($path), true);
            $this->messages = $json[$this->page] ?? [];
        }
    }

    public function get(string $key, string $default = ''): string
    {
        return $this->messages[$key][$this->locale] ?? $default ?: $key;
    }
}