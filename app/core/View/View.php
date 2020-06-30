<?php declare(strict_types=1);

namespace Core\View;

use Core\Exceptions\ViewException;

/**
 * Class View
 * @package Core\View
 */
class View
{
    // TODO: These are not look OK here. Some refactoring necessary tho.
    private const BASE_PATH = '/../../app/Views/';
    private const BASE_LAYOUTS_PATH = '/../../app/Views/layouts/base.php';

    public static function render(string $view, array $params = [])
    {
        $layout = __DIR__ . self::BASE_LAYOUTS_PATH;
        if (!file_exists($layout)) {
            throw new ViewException("$layout layout not found!", 500);
        }

        $file = __DIR__ . self::BASE_PATH . $view . '.php';
        if (file_exists($file)) {
            $body = self::requireToVar($file, $params);

            require_once $layout;
        } else {
            throw new ViewException("$file not found!", 500);
        }
    }

    public static function renderView(string $view, array $params = []): void
    {
        $file = __DIR__ . self::BASE_PATH . $view . '.php';

        if (file_exists($file)) {
            extract($params, EXTR_OVERWRITE);
            require_once $file;
        } else {
            throw new ViewException("$file not found!", 500);
        }
    }

    private static function requireToVar($file, $params): string
    {
        ob_start();
        extract($params, EXTR_OVERWRITE);
        require_once $file;

        return ob_get_clean();
    }
}