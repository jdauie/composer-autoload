<?php

namespace Jacere\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PostAutoloadDumpEvent;

class AutoloadPlugin implements PluginInterface, EventSubscriberInterface {

	protected $composer;
	protected $io;

	public function activate(Composer $composer, IOInterface $io) {
		$this->composer = $composer;
		$this->io = $io;
	}

	public static function getSubscribedEvents() {
		return [
			PluginEvents::POST_AUTOLOAD_DUMP => [
				['onPostAutoloadDump', 0],
			],
		];
	}

	public function onPostAutoloadDump(PostAutoloadDumpEvent $event)
	{
		//
	}
}