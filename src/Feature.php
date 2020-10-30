<?php

namespace Humanik\WordPress;

use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Factory\Exceptions\CircularReferenceException;
use Yiisoft\Factory\Exceptions\InvalidConfigException;
use Yiisoft\Factory\Exceptions\NotFoundException;
use Yiisoft\Factory\Exceptions\NotInstantiableException;
use Yiisoft\Injector\Injector;

abstract class Feature {
	protected array $config;
	protected Plugin $plugin;

	public function __construct( Plugin $plugin, array $config = [] ) {
		$this->plugin = $plugin;
		$this->config = array_merge( $config, $this->default_config() );
	}

	abstract public function register();

	public function get_setting( string $name ) {
		return ArrayHelper::getValueByPath( $this->config, $name );
	}

	protected function default_config(): array {
		return [];
	}

	/**
	 * @param  string  $name
	 *
	 * @return object|Injector
	 * @throws CircularReferenceException
	 * @throws InvalidConfigException
	 * @throws NotFoundException
	 * @throws NotInstantiableException
	 */
	public function get( string $name ) {
		return $this->plugin->get( $name );
	}

	public function has( string $name ): bool {
		return $this->plugin->has( $name );
	}
}
