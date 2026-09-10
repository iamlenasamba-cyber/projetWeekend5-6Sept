<?php

declare(strict_types=1);

namespace App\View;

final class ViewRenderer
{
    public function render(string $template, array $data = []): string
    {
        $path = dirname(__DIR__, 2) . '/templates/' . ltrim($template, '/');
        extract($data);
        ob_start();
        require $path;
        return  ob_get_clean();
    }
}
