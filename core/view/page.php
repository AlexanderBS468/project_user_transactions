<?php
namespace Core\View;

/**
 * @description
 * Class for working with a page. Output of header, content and footer.
 * Setting the meta tag title
 */
class Page
{
    public static string $title = '';
    protected static string $content = '';

    /**
     * @description
     * function set content
     */
    public static function contentBody($string = '') : void
	{
        self::$content = $string;
    }

    /**
     * @description
     * function render full page with replaced tag Title in header
     */
    public static function render() : void
	{
        ob_start();
        require __DIR__ . '/../view/templates/' . $GLOBALS['TEMPLATE_NAME'] . '/header.php';
        $header = ob_get_clean();

        if (trim(self::$title) !== '')
        {
            self::$title = '<title>' . self::$title . '</title>';
        }
        $header = str_replace(['{{title}}'], [self::$title], $header);

        ob_start();
        require __DIR__ . '/../view/templates/' . $GLOBALS['TEMPLATE_NAME'] . '/footer.php';
        $footer = ob_get_clean();

        echo $header . self::$content . $footer;
    }
}
