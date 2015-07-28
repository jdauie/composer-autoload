<?php

namespace Jacere\Autoload;

class Autoloader {
	
	private static $c_lookup;

	public static function initialize($lookup) {
		self::$c_lookup = $lookup;
		spl_autoload_register([self::class, 'autoload'], true, true);
	}
	
	public static function autoload($class) {
		$parts = explode('\\', $class);
		$count = count($parts);
		$current = self::$c_lookup;
		for ($i = 0; $i < $count; $i++) {
			$part = $parts[$i];
			if (!isset($current['name'][$part])) {
				break;
			}
			$current = $current['name'][$part];
		}
		if ($current && isset($current['path'])) {
			foreach ($current['path'] as $path) {
				if ($i !== $count) {
					array_splice($parts, 0, $i);
					$path = sprintf('%s/%s.php', $path, implode('/', $parts));
				}
				if (is_file($path)) {
					require($path);
				}
			}
		}
	}
}
