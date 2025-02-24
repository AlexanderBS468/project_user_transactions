<?php
namespace Core\Tools;

class Debug
{
	private static string $styleBlock = 'font:normal 10pt/12pt monospace;background:#fff;color:#000;margin:10px;padding:10px;border:1px solid red;text-align:left;max-width:1400px;max-height:600px;overflow:scroll';
	private static string $style = 'font:normal 10pt/12pt monospace;color:#60d;text-decoration:underline';

	/**
	* @description
	* The function displays in a convenient form:
	* 1. Information about the script from which it was called
	* 2. The contents of the variable that was passed as the first parameter
	*
	* When running the script from the cli, the full path to the file and the print_r variable are displayed
	*
	* @param $obj mixed
	* @param bool $visibleForEveryone whether to show the block to all site visitors (by default, the block is visible only to site administrators)
	*
	* @return bool returns false if the message was not shown (if the user is not an administrator)
	* */

	public static function pr($obj, $visibleForEveryone = false) : bool
	{
		static $isAdmin = null;

		if (version_compare(PHP_VERSION, '5.4.0') >= 0)
		{
			$trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT & DEBUG_BACKTRACE_IGNORE_ARGS);
		}
		else
		{
			$trace = debug_backtrace();
		}

		if (PHP_SAPI === 'cli')
		{
			$first = $trace[1];

			echo $first['file'] . ':' . $first['line'] . PHP_EOL;
			print_r($obj);

			return true;
		}

		if ($isAdmin === null)
		{
			//@todo add is admin if have object user system
			$isAdmin = isset($_COOKIE['DEV_ALEX']) && $_COOKIE['DEV_ALEX'] === 'Y';
		}

		if (!$isAdmin && !$visibleForEveryone)
		{
			return false;
		}

		array_shift($trace); // shift self

		echo '<div style="' . self::$styleBlock . '">';
		echo static::traceOutput($trace);
		echo self::outputObject($obj);
		echo '</div>';

		return true;
	}

	public static function traceOutput(array $trace) : string
	{
		$first = array_shift($trace);
		$title = str_replace($_SERVER['DOCUMENT_ROOT'], '', $first['file']) . ':' . $first['line'];
		$style = self::$style;
		$body = implode('<br />', array_map(static function($row) use ($style) {
			$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $row['file']);
			$directory = dirname($path);
			$filename = pathinfo($path, PATHINFO_BASENAME);

			$nobrArgs = isset($row['class']) && $row['class'] ? $row['class'] : '';
			$nobrArgs .= isset($row['type']) && $row['type'] ? $row['type'] : '';
			$nobrArgs .= isset($row['function']) && $row['function'] ? $row['function'] : '';
			$output = '<nobr>' . $nobrArgs . '()' . '</nobr>';
			$output .= '&nbsp;';
			$output .= '<nobr>' . $directory . '</nobr>';
			$output .= '/';
			$output .= '<nobr>' . $filename . '</nobr>';
			$output .= ':' . $row['line'];

			return $output;
		}, $trace));

		return <<<HTML
			<details style="margin: 0 0 20px">
				<summary style="{$style}">{$title}</summary>
				<div style="padding: 20px 20px 0">{$body}</div>
			</details>
HTML;
	}

	private static function outputObject($obj) : string
	{
		$style = self::$style;

		$outputObj = '<pre>'
			. self::htmlspecialcharsEx(print_r($obj, true))
			. '</pre>';

		$strResult = <<<HTML
			<details style="margin: 0 0 20px" open>
				<summary style="{$style}">Object data</summary>
				<div style="padding: 20px 20px 0">{$outputObj}</div>
			</details>
HTML;

		$methods = '<pre>'
			. self::htmlspecialcharsEx(print_r(self::getMethodsObj($obj), true))
			. '</pre>';
		$strResultMethods = <<<HTML
			<details style="margin: 0 0 20px">
				<summary style="{$style}">Method name</summary>
				<div style="padding: 20px 20px 0">{$methods}</div>
			</details>
HTML;

		return $strResult . $strResultMethods;
	}

	public static function getMethodsObj($obj) : array|string
	{
		$result = null;

		if (is_object($obj) || is_string($obj))
		{
			try
			{
				$reflection = new \ReflectionClass($obj);
				$methods = $reflection->getMethods();
				foreach ($methods as $method)
				{
					$result[] =  $method->getName();
				}
			}
			catch (\ReflectionException $exception)
			{
				$result = $exception->getMessage();
			}
		}
		else
		{
			$result = 'NOTICE: @arg - "The entered variable is not an object type || not a class name"';
		}

		return $result;
	}

	protected static function htmlspecialcharsEx($str)
	{
		static $search = ["&amp;", "&lt;", "&gt;", "&quot;", "&#34;", "&#x22;", "&#39;", "&#x27;", "<", ">", "\""];
		static $replace = ["&amp;amp;", "&amp;lt;", "&amp;gt;", "&amp;quot;", "&amp;#34;", "&amp;#x22;", "&amp;#39;", "&amp;#x27;", "&lt;", "&gt;", "&quot;"];

		return self::str_replace($search, $replace, $str);
	}

	/**
	 * Compatible with php 8 for nested arrays. Only the first level of the array is processed.
	 *
	 * @param mixed $search
	 * @param mixed $replace
	 * @param mixed $str
	 * @return mixed
	 */
	public static function str_replace($search, $replace, $str)
	{
		if (is_array($str))
		{
			foreach ($str as $key => $value)
			{
				if (is_scalar($value))
				{
					$str[$key] = str_replace($search, $replace, $value);
				}
			}
		}
		elseif (is_scalar($str))
		{
			$str = str_replace($search, $replace, $str);
		}

		return $str;
	}
}
