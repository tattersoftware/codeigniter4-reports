<?php namespace Tests\Support\Reports;

use Tatter\Reports\BaseReport;
use Tatter\Reports\Interfaces\ReportInterface;

class MoneyReport extends BaseReport implements ReportInterface
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, string>  Must include keys: name, table
	 */
	public $attributes = [
		'name'    => 'Money Report',
		'table'   => 'finances',
		'summary' => 'Daily reports on expenditures',
	];

	/**
	 * Tests all expected report criteria. Calls run() and saves results for any missing content.
	 */
	public function generate()
	{		
		// Days to process
		$start = date('Y-m-d', strtotime('-2 days'));
		$end   = date('Y-m-d');

		// Get current reports
		$reports = $this->get(null, ['day', 'amount']);

		// Check each day
		$current = $start;
		while (strtotime($current) <= strtotime($end))
		{
			if (! isset($reports[$current]))
			{
				// Create the report
				$content = $this->run($current);

				// Build the row
				$row = [
					'day'        => $current,
					'amount'     => $content,
					'created_at' => date('Y-m-d H:i:s'),
				];
				$this->builder->insert($row);
					
				// Add a message
				$this->messages[] = "New report saved: {$current}... {$content}";
			}

			$current = date('Y-m-01', strtotime('+1 day', strtotime($current)));
		}
	}

	/**
	 * Calculates content for a specific missing set of criteria.
	 *
	 * @param mixed ...$params
	 *
	 * @return int
	 */
	public function run(...$params)
	{
		$day = $params[0];

		return date('d', strtotime($day)) * 10;
	}
}
