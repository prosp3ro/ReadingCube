<?php

declare(strict_types=1);

namespace Src;

use Src\Exceptions\ViewException;

class View
{
    private function getPath(string $str): string
    {
        return str_replace('\\', '/', $str);
    }

    public function render(string $page, array $params = []): string
    {
        if (! strpos($page, '.view.php')) {
            $page .= '.view.php';
        }

        $viewPath = $this->getPath(VIEW_PATH . '/' . $page);

        if (! file_exists($viewPath)) {
            throw ViewException::viewNotFound();
        }

        if (! array_key_exists("header", $params)) {
            $params["header"] = APP_NAME;
        }

        if (! empty($params)) {
            extract($params);
        }

        ob_start();

        include $viewPath;

        return (string) ob_get_clean();
    }

    public function pageNotFound(array $params = []): void
    {
        $page = "404.view.php";
        $this->render($page, $params);
    }
}
