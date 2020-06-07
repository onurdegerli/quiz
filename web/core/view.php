<?php
namespace Core;

/**
 * View class
 */
class View
{
    const BASE_PATH = '/../app/views/';
    const BASE_LAYOUTS_PATH = '/../app/views/layouts/base.php';

    public function __construct($loyout = '')
    {
        if (!empty($layout)) {
            $this->layout = $layout;
        }
    }

    public static function render(string $view, array $params = [])
    {
        $layout = __DIR__ . self::BASE_LAYOUTS_PATH;
        if (!file_exists($layout)) {
            throw new \Exception("$this->layout layout not found!");
        }

        $file = __DIR__ . self::BASE_PATH . $view . '.php';

        if (file_exists($file)) {
            $body = self::requireToVar($file, $params);
            if (!empty($params['config'])) {
                extract($params['config'], EXTR_OVERWRITE);
            }
            require_once $layout;
        } else {
            throw new \Exception("$file not found!");
        }
    }

    public static function renderView(string $view, array $params = [])
    {
        $file = __DIR__ . self::BASE_PATH . $view . '.php';

        if (file_exists($file)) {
            extract($params, EXTR_OVERWRITE);
            require_once $file;
        } else {
            throw new \Exception("$file not found!");
        }
    }

    private static function requireToVar($file, $params)
    {
        ob_start();
        extract($params, EXTR_OVERWRITE);
        require_once $file;
        return ob_get_clean();
    }

    public static function renderJson(array $params = [])
    {
        header('Content-Type: application/json');
        echo json_encode($params);
    }
}