<?php

/**
 * @description
 * Simple autoloader for classes in the "Core" namespace.
 * When a class that starts with "Core\" is requested,
 * If the file exists, it is included.
 */
spl_autoload_register(function($className)
{
	static $namespace = 'Core\\';
	static $namespaceLength = null;

	if (!isset($namespaceLength))
	{
		$namespaceLength = strlen($namespace);
	}

	if (substr($className, 0, $namespaceLength) === $namespace)
	{
		$classNameRelative = substr($className, $namespaceLength);
		$classRelativePath = str_replace('\\', '/', $classNameRelative) . '.php';
		$classFullPath = __DIR__ . '/' . mb_strtolower($classRelativePath);

		if (file_exists($classFullPath))
		{
			require_once $classFullPath;
		}
	}
});