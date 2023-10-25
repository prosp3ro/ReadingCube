<?php

declare(strict_types=1);

namespace Src;

use Src\Exceptions\ViewException;

class View
{
    public function __construct(
        protected string $view,
        protected array $params = []
    ) {
    }

    public function render()
    {
        if (! strpos($this->view, '.view.php')) {
            $this->view .= '.view.php';
        }

        $viewPath = $this->getPath(VIEW_PATH . '/' . $this->view);

        if (! file_exists($viewPath)) {
            throw ViewException::viewNotFound();
        }

        if (! empty($this->params)) {
            extract($this->params);
        }

        if (! isset($header)) {
            $header = APP_NAME;
        }

        include $viewPath;
    }

    public function pageNotFound(array $params = []): void
    {
        $view = "error/404.view.php";
        $this->render($view, $params);
    }

    public static function create(string $view, array $params = []): static
    {
        return new static($view, $params);
    }

    private function getPath(string $str): string
    {
        return str_replace('\\', '/', $str);
    }

    public function __toString()
    {
        return $this->render();
    }
}
