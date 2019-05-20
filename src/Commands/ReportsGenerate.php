<?php namespace Tatter\Reports\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

class ReportsGenerate extends BaseCommand
{
    protected $group       = 'Reports';
    protected $name        = 'reports:generate';
    protected $description = 'Generate missing report contents for all detected reports';
    
	protected $usage     = 'reports:generate';
	protected $arguments = [ ];

	public function run(array $params = [])
    {
		$locator = Services::locator(true);

		// get all namespaces from the autoloader
		$namespaces = Services::autoloader()->getNamespace();
		
		// scan each namespace for reports
		$flag = false;
		foreach ($namespaces as $namespace => $paths):

			// get any files in /Reports/ for this namespace
			$files = $locator->listNamespaceFiles($namespace, '/Reports/');
			foreach ($files as $file):
			
				// skip non-PHP files
				if (substr($file, -4) !== '.php'):
					continue;
				endif;
				
				// get namespaced class name
				$name = basename($file, '.php');
				$class = $namespace . '\Reports\\' . $name;
				
				include_once $file;

				// validate the class
				if (! class_exists($class, false)):
					throw new \RuntimeException("Could not locate {$class} in {$file}");
				endif;
				$instance = new $class();
				
				// validate necessary methods
				if (! is_callable([$instance, 'generate'])):
					throw new \RuntimeException("Missing 'generate' method for {$class} in {$file}");
				endif;
				if (! is_callable([$instance, 'getMessages'])):
					throw new \RuntimeException("Missing 'getMessages' method for {$class} in {$file}");
				endif;
				
				// run it
				$result = $instance->generate();
				
				// write out any messages
				foreach ($instance->getMessages() as $message):
					CLI::write("{$name}: {$message}");
				endforeach;
				
				$flag = true;
			endforeach;
		endforeach;
		
		if ($flag == false):
			CLI::write('No reports found in any namespace.', 'yellow');
			return;
		endif;
	}
}
