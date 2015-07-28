<?php

namespace Jacere\Composer;

use Composer\Composer;
use Composer\Script\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PluginEvents;

use Composer\Autoload\ClassMapGenerator;

use Jacere\Autoload\AutoloadGenerator;

class AutoloadPlugin implements PluginInterface, EventSubscriberInterface {

	private $m_composer;
	private $m_io;
	private $m_lookup;

	public function activate(Composer $composer, IOInterface $io) {
		$this->m_composer = $composer;
		$this->m_io = $io;
		$this->m_lookup = [];
	}

	public static function getSubscribedEvents() {
		return [
			'post-autoload-dump' => [
				['onPostAutoloadDump', 0],
			],
		];
	}

	public function onPostAutoloadDump(Event $event) {

		$vendorDir = dirname(dirname(dirname(__DIR__)));
		$composerDir = $vendorDir.'/composer';
		
		$generator = new AutoloadGenerator();

		$classmap = require($composerDir . '/autoload_classmap.php');
		$files = require($composerDir . '/autoload_files.php');
		foreach ($files as $file) {
			$classmap = array_merge($classmap, ClassMapGenerator::createMap($file, NULL, $this->m_io));
		}
		foreach ($classmap as $class => $path) {
			$generator->register($class, $path);
		}

		$psr4 = require($composerDir . '/autoload_psr4.php');
		foreach ($psr4 as $namespace => $paths) {
			foreach ($paths as $path) {
				$generator->register($namespace, $path);
			}
		}
		
		file_put_contents(dirname(__DIR__).'/autoload.php', (string)$generator);

		return true;
	}
}