<?php namespace Tatter\Reports\Interfaces;

use CodeIgniter\Database\ConnectionInterface;

interface ReportInterface
{
	/**
	 * Initializes the database.
	 *
	 * @param ConnectionInterface|null $db
	 */
	public function __construct(ConnectionInterface &$db = null);

	/**
	 * Fetches and organizes report results based on supplied parameters.
	 *
	 * @param array<string, mixed>|null $criteria  Criteria to pass to the Builder
	 * @param array<string>|null $groups           Fields to group by
	 * @param string|null $order                   Order for results
	 *
	 * @return array
	 */
	public function get(array $criteria = null, array $groups = null, string $order = null): array;

	/**
	 * Tests all expected report criteria. Calls run() and saves results for any missing content.
	 */
	public function generate();

	/**
	 * Calculates content for a specific missing set of criteria.
	 *
	 * @param mixed ...$params
	 *
	 * @return mixed
	 */
	public function run(...$params);

	/**
	 * Returns any messages.
	 *
	 * @return array
	 */
	public function getMessages(): array;
}
