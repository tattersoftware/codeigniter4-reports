<?php namespace Tatter\Reports\Interfaces;

use CodeIgniter\Database\ConnectionInterface;

interface ReportInterface
{
	// constructor with option DB connection injection
	public function __construct(ConnectionInterface &$db = null);
	
	// fetches and organizes report results based on supplied parameters
	public function get(array $criteria = null, array $groups = null, string $order = null): array;
	
	// tests all expected report criteria
	// calls run() and saves results for any missing content
	public function generate();
    
    // calculates content for a specific missing set of criteria
	public function run(...$params);
	
	// access messages stored during report generation
	public function getMessages(): array;
}
