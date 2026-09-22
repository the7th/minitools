<?php

declare(strict_types=1);

namespace App;

class View
{
    public static function render(string $name, array $data = []): string
    {
        $data['content'] = self::partial($name, $data);

        return self::partial('layout', $data);
    }

    private static function partial(string $name, array $data): string
    {
        $path = dirname(__DIR__) . '/views/' . $name . '.php';

        if (! is_file($path)) {
            throw new \RuntimeException("View [{$name}] tak dijumpai.");
        }

        extract($data, EXTR_SKIP);

        ob_start();

        require $path;

        return (string) ob_get_clean();
    }
}
