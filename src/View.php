<?php

declare(strict_types = 1);

namespace Src;

use Throwable;

class View
{
    private string $viewsPath;

    public function __construct()
    {
        $this->viewsPath = ROOT . "/templates/views/";
    }

    private function getPath(string $str): string
    {
        return str_replace('\\', '/', $str);
    }

    public function render(string $page, array $args = []): void
    {
        try {
            if (!strpos($page, '.view.php')) {
                $page .= '.view.php';
            }

            $path = $this->getPath($this->viewsPath . $page);

            if (!empty($args)) {
                extract($args);
            }

            require_once($path);
        } catch (Throwable $exception) {
            dd($exception);
            die();
        }
    }
}
