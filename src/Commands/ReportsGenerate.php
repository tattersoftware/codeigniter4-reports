<?php namespace Tatter\Reports\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;
use Tatter\Handlers\Handlers;
use Tatter\Reports\Interfaces\ReportInterface;

class ReportsGenerate extends BaseCommand
{
    protected $group       = 'Reports';
    protected $name        = 'reports:generate';
    protected $description = 'Generate missing report contents for all detected reports';
	protected $usage       = 'reports:generate';

	public function run(array $params = [])
    {
		// Locate all Report handlers
		$classes = (new Handlers('Reports'))->all();

		// Get each handler and generate the report
		$flag = false;
		foreach ($classes as $class)
		{
			$handler = new $class();

			if (! $handler instanceof ReportInterface)
			{
				continue;
			}

			// Generate the data
			CLI::write("Checking {$handler->name} for new content..."); //@phpstan-ignore-line
			$result = $handler->generate();

			// Write out any messages
			foreach ($handler->getMessages() as $message)
			{
				CLI::write($message);
			}

			$flag = true;
		}

		if ($flag === false)
		{
			CLI::write('No reports found.', 'yellow');
			return;			
		}
	}
}
