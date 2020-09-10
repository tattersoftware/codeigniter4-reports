<?php namespace Tests\Support\Factories;

class BadFactory
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, string>  Must include keys: name, table
	 */
	public $attributes = [
		'name'    => 'Bad Report',
		'table'   => 'failed_reports',
		'summary' => 'This report is missing its interface',
	];

	/**
	 * Tests all expected report criteria. Calls run() and saves results for any missing content.
	 *
	 * @return void
	 */
	public function generate()
	{
	}

	/**
	 * Calculates content for a specific missing set of criteria.
	 *
	 * @param mixed ...$params
	 *
	 * @return mixed
	 */
	public function run(...$params)
	{
	}
}
