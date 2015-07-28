<?php

namespace Jacere\Autoload;

class AutoloadGenerator {
	
	private $m_lookup;

	public function __construct() {
		$this->m_lookup = [];
	}

	public function register($class, $path) {
		// add to lookup tree
		$current = &$this->m_lookup;
		$parts = explode('\\', rtrim($class, '\\'));
		foreach ($parts as $part) {
			if (!isset($current['name'][$part])) {
				$current['name'][$part] = [];
			}
			$current = &$current['name'][$part];
		}
		$current['path'][] = str_replace('\\', '/', $path);
	}

	public static function _dump(array $lookup) {
		$parts = [];
		foreach ($lookup as $key => $value) {
			if ($key === 'name') {
				$v2 = [];
				foreach ($value as $vk => $vv) {
					$v2[] = "'$vk'=>".self::_dump($vv);
				}
				$value = $v2;
			}
			else {
				$value = array_map(function($a) {return "'$a'";}, $value);
			}
			$parts[] = "'$key'=>[".implode(',', $value).']';
		}
		return sprintf('[%s]', implode(',', $parts));
	}
	
	public function toString() {
		return sprintf('<?php \Jacere\Autoload\Autoloader::initialize(%s);', self::_dump($this->m_lookup));
	}
}
