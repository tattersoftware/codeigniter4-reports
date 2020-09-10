<?php namespace Tests\Support\Reports;

use Tatter\Reports\BaseReport;
use Tatter\Reports\Interfaces\ReportInterface;

class WidgetReport extends BaseReport implements ReportInterface
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, string>  Must include keys: name, table
	 */
	public $attributes = [
		'name'    => 'Widget Report',
		'table'   => 'widget_reports',
		'summary' => 'Widget totals',
	];

	/**
	 * Tests all expected report criteria. Calls run() and saves results for any missing content.
	 *
	 * @return void
	 */
	public function generate()
	{
		for ($i = 0; $i < 3; $i++)
		{
			$result = $this->run($i);

			// Build the row
			$row = [
				'index'      => $i,
				'total'      => $result,
				'created_at' => date('Y-m-d H:i:s'),
			];
			$this->builder->insert($row);
		}
	}

	/**
	 * Calculates content for a specific missing set of criteria.
	 *
	 * @param mixed ...$params
	 *
	 * @return integer
	 */
	public function run(...$params)
	{
		return $params[0] * rand(0, 10);
	}
}
