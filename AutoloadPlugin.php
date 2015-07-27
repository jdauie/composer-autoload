<?php

namespace Jacere\Composer;

use Composer\Composer;
use Composer\Script\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PluginEvents;

class AutoloadPlugin implements PluginInterface, EventSubscriberInterface {

	protected $composer;
	protected $io;

	public function activate(Composer $composer, IOInterface $io) {
		$this->composer = $composer;
		$this->io = $io;
	}

	public static function getSubscribedEvents() {
		return [
			'post-autoload-dump' => [
				['onPostAutoloadDump', 0],
			],
		];
	}

	public function onPostAutoloadDump(Event $event) {
		return false;
	}
}