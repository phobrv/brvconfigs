<?php

namespace Phobrv\BrvConfigs;

use Illuminate\Support\ServiceProvider;

class BrvConfigsServiceProvider extends ServiceProvider {
	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot(): void{
		// $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'phobrv');
		$this->loadViewsFrom(__DIR__ . '/../resources/views', 'phobrv');
		// $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
		$this->loadRoutesFrom(__DIR__ . '/routes.php');

		// Publishing is only necessary when using the CLI.
		if ($this->app->runningInConsole()) {
			$this->bootForConsole();
		}
	}

	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register(): void{
		$this->mergeConfigFrom(__DIR__ . '/../config/brvconfigs.php', 'brvconfigs');

		// Register the service the package provides.
		$this->app->singleton('brvconfigs', function ($app) {
			return new BrvConfigs;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return ['brvconfigs'];
	}

	/**
	 * Console-specific booting.
	 *
	 * @return void
	 */
	protected function bootForConsole(): void{
		// Publishing the configuration file.
		// $this->publishes([
		//     __DIR__.'/../config/brvconfigs.php' => config_path('brvconfigs.php'),
		// ], 'brvconfigs.config');

		// Publishing the views.
		$this->publishes([
			__DIR__ . '/../resources/views' => base_path('resources/views/vendor/phobrv'),
		], 'brvconfigs.views');

		// Publishing assets.
		/*$this->publishes([
		__DIR__.'/../resources/assets' => public_path('vendor/phobrv'),
		], 'brvconfigs.views');*/

		// Publishing the translation files.
		/*$this->publishes([
		__DIR__.'/../resources/lang' => resource_path('lang/vendor/phobrv'),
		], 'brvconfigs.views');*/

		// Registering package commands.
		// $this->commands([]);
	}
}
