<?php

namespace Humanik\WordPress;

use Yiisoft\Di\Container;
use Yiisoft\Factory\Exceptions\CircularReferenceException;
use Yiisoft\Factory\Exceptions\InvalidConfigException;
use Yiisoft\Factory\Exceptions\NotFoundException;
use Yiisoft\Factory\Exceptions\NotInstantiableException;
use Yiisoft\Injector\Injector;

class Plugin {
	protected string $filepath;
	protected Container $container;

	public function __construct( string $filepath ) {
		$this->filepath = $filepath;
		$this->container = new Container( $this->dependencies() );
	}

	public function register(): void {
		$this->register_features();
	}

	public function register_features(): void {
		foreach ( $this->features() as $class ) {
			/** @var Feature $feature */
			$feature = $this->get( $class );
			$feature->register();
		}
	}

	protected function dependencies(): array {
		return [ Plugin::class => $this ];
	}

	protected function features(): array {
		return array_filter(
			$this->dependencies(),
			fn( $definition ) => is_string( $definition ) && is_a( $definition, Feature::class, true )
		);
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
		return $this->container->get( $name );
	}

	public function has( string $name ): bool {
		return $this->container->has( $name );
	}
}
