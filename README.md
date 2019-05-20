# Tatter\Reports
Report management framework for CodeIgniter 4

## Quick Start

1. Install with Composer: `> composer require tatter/reports`
2. Create your reports in `App/Reports/`
3. Generate contents from CLI: `> php spark reports:generate`
4. Access report content: `$reports = new \App\Reports\MyReport(); $results = $reports->get();`

## Features

Provides a concise, non-intrusive framework for writing database reports for CodeIgniter 4

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
* `> composer require tatter/reports`

Or, install manually by downloading the source files and adding the directory to
`app/Config/Autoload.php`.

## Create reports

Once the library is included all the resources are ready to go and you are ready to start
making your report classes. Reports are detected across any namespace so can come from
your `App\Reports` directory or any module or addin. See `ReportInterface` for requirements
when writing a report class.

## Generate results

Once all the reports are setup use the command-line interface to generate report results:

`> php spark reports:generate`

Each report class handles checking for missing report values so this command can be run
routinely (e.g. by regular cron).

## Access results

Load the report class of choice and then pull whatever contents you need using its `get()`
method. Called without parameters `get()` will return all contents straight from the
database. Optionally you may specify criteria to the database query, e.g.:
```
$criteria = [
	'user_id' => 56,
	'created_at >=' => '2019-03-01',
];
$results = $reports->get($criteria);
```

Other options for `get()` are recursive result grouping (e.g.
`$results[user_id][date] => contents`) and ordering.
