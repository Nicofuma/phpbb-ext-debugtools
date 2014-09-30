<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @copyright (c) Fabien Potencier <fabien@symfony.com>
* @license       GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

namespace nicofuma\debugtools\console\command\router;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
* A console command for retrieving information about routes
*
* @author Fabien Potencier <fabien@symfony.com>
* @author Tobias Schultze <http://tobion.de>
*/
class debug extends \phpbb\console\command\command
{
	/**
	* @var \phpbb\controller\provider
	*/
	protected $controller_provider;

	/**
	* @var \phpbb\controller\provider
	*/
	protected $extension_manager;

	/**
	* @var \phpbb\controller\provider
	*/
	protected $phpbb_root_path;

	/**
	* Constructor
	*
	* @param \phpbb\user                $user                User instance
	* @param \phpbb\controller\provider $controller_provider User instance
	* @param \phpbb\extension\manager   $extension_manager   Extension manager object
	* @param string                     $phpbb_root_path     phpBB root path
	*/
	function __construct(\phpbb\user $user, \phpbb\controller\provider $controller_provider, \phpbb\extension\manager $extension_manager, $phpbb_root_path)
	{
		$this->controller_provider	= $controller_provider;
		$this->extension_manager	= $extension_manager;
		$this->phpbb_root_path		= $phpbb_root_path;

		$user->add_lang_ext('nicofuma/debugtools', 'cli');

		parent::__construct($user);
	}

	/**
	* {@inheritdoc}
	*/
	protected function configure()
	{
		$this
			->setName('debug:router')
			->setDefinition(array(
				new InputArgument('name', InputArgument::OPTIONAL, $this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_ARGUMENT_ROUTE_NAME')),
			))
			->setDescription($this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_DESCRIPTION_ROUTER_DEBUG'))
			->setHelp($this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_HELP_ROUTER_DEBUG'));
	}

	/**
	* {@inheritdoc}
	*
	* @throws \InvalidArgumentException When route does not exist
	*/
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$name = $input->getArgument('name');

		$this->controller_provider->find_routing_files($this->extension_manager->get_finder());
		$this->controller_provider->find($this->phpbb_root_path)->get_routes();

		if ($name)
		{
			$this->outputRoute($output, $name);
		}
		else
		{
			$this->outputRoutes($output);
		}
	}

	protected function outputRoutes(OutputInterface $output, $routes = null)
	{
		if (null === $routes)
		{
			$routes = $this->controller_provider->get_routes()->all();
		}

		$output->writeln($this->getHelper('formatter')->formatSection('router', $this->user->lang('CURRENT_ROUTES')));

		$maxName   = strlen('name');
		$maxMethod = strlen('method');
		$maxScheme = strlen('scheme');
		$maxHost   = strlen('host');

		foreach ($routes as $name => $route)
		{
			$method    = $route->getMethods() ? implode('|', $route->getMethods()) : $this->user->lang('ANY_MAJ_MAJ');
			$scheme    = $route->getSchemes() ? implode('|', $route->getSchemes()) : $this->user->lang('ANY_MAJ');
			$host      = '' !== $route->getHost() ? $route->getHost() : $this->user->lang('ANY_MAJ');
			$maxName   = max($maxName, strlen($name));
			$maxMethod = max($maxMethod, strlen($method));
			$maxScheme = max($maxScheme, strlen($scheme));
			$maxHost   = max($maxHost, strlen($host));
		}

		$format       = '%-' . $maxName . 's %-' . $maxMethod . 's %-' . $maxScheme . 's %-' . $maxHost . 's %s';
		$formatHeader = '%-' . ($maxName + 19) . 's %-' . ($maxMethod + 19) . 's %-' . ($maxScheme + 19) . 's %-' . ($maxHost + 19) . 's %s';
		$output->writeln(sprintf($formatHeader, '<comment>' . $this->user->lang('NAME') . '</comment>', '<comment>' . $this->user->lang('METHOD') . '</comment>', '<comment>' . $this->user->lang('SCHEME') . '</comment>', '<comment>' . $this->user->lang('HOST') . '</comment>', '<comment>' . $this->user->lang('PATH') . '</comment>'));

		foreach ($routes as $name => $route)
		{
			$method = $route->getMethods() ? implode('|', $route->getMethods()) : $this->user->lang('ANY_MAJ');
			$scheme = $route->getSchemes() ? implode('|', $route->getSchemes()) : $this->user->lang('ANY_MAJ');
			$host   = '' !== $route->getHost() ? $route->getHost() : $this->user->lang('ANY_MAJ');
			$output->writeln(sprintf($format, $name, $method, $scheme, $host, $route->getPath()), OutputInterface::OUTPUT_RAW);
		}
	}

	/**
	* @throws \InvalidArgumentException When route does not exist
	*/
	protected function outputRoute(OutputInterface $output, $name)
	{
		$route = $this->controller_provider->get_routes()->get($name);
		if (!$route)
		{
			throw new \InvalidArgumentException($this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_EXCEPTION_ROUTER_DEBUG_ROUTE_NOT_EXIST', $name));
		}

		$output->writeln($this->getHelper('formatter')->formatSection('router', $this->user->lang('ROUTE_NAME', $name)));

		$method = $route->getMethods() ? implode('|', $route->getMethods()) : $this->user->lang('ANY_MAJ');
		$scheme = $route->getSchemes() ? implode('|', $route->getSchemes()) : $this->user->lang('ANY_MAJ');
		$host   = '' !== $route->getHost() ? $route->getHost() : $this->user->lang('ANY_MAJ');

		$output->write('<comment>' . $this->user->lang('NAME') . '</comment> ');
		$output->writeln($name, OutputInterface::OUTPUT_RAW);

		$output->write('<comment>' . $this->user->lang('PATH') . '</comment> ');
		$output->writeln($route->getPath(), OutputInterface::OUTPUT_RAW);

		$output->write('<comment>' . $this->user->lang('HOST') . '</comment> ');
		$output->writeln($host, OutputInterface::OUTPUT_RAW);

		$output->write('<comment>' . $this->user->lang('SCHEME') . '</comment> ');
		$output->writeln($scheme, OutputInterface::OUTPUT_RAW);

		$output->write('<comment>' . $this->user->lang('METHOD') . '</comment> ');
		$output->writeln($method, OutputInterface::OUTPUT_RAW);

		$output->write('<comment>' . $this->user->lang('CLASS') . '</comment> ');
		$output->writeln(get_class($route), OutputInterface::OUTPUT_RAW);

		$output->write('<comment>' . $this->user->lang('DEFAULTS') . '</comment> ');
		$output->writeln($this->formatConfigs($route->getDefaults()), OutputInterface::OUTPUT_RAW);

		$output->write('<comment>' . $this->user->lang('REQUIREMENTS') . '</comment> ');
		// we do not want to show the schemes and methods again that are also in the requirements for BC
		$requirements = $route->getRequirements();
		unset($requirements['_scheme'], $requirements['_method']);
		$output->writeln($this->formatConfigs($requirements) ? : $this->user->lang('NO_CUSTOM_MAJ'), OutputInterface::OUTPUT_RAW);

		$output->write('<comment>' . $this->user->lang('OPTIONS') . '</comment> ');
		$output->writeln($this->formatConfigs($route->getOptions()), OutputInterface::OUTPUT_RAW);

		$output->write('<comment>' . $this->user->lang('PATH_REGEX') . '</comment> ');
		$output->writeln($route->compile()->getRegex(), OutputInterface::OUTPUT_RAW);

		if (null !== $route->compile()->getHostRegex())
		{
			$output->write('<comment>' . $this->user->lang('HOST_REGEX') . '</comment> ');
			$output->writeln($route->compile()->getHostRegex(), OutputInterface::OUTPUT_RAW);
		}
	}

	protected function formatValue($value)
	{
		if (is_object($value))
		{
			return sprintf('object(%s)', get_class($value));
		}

		if (is_string($value))
		{
			return $value;
		}

		return preg_replace("/\n\s*/s", '', var_export($value, true));
	}

	private function formatConfigs(array $array)
	{
		$string = '';
		ksort($array);
		foreach ($array as $name => $value)
		{
			$string .= ($string ? "\n" . str_repeat(' ', 13) : '') . $name . ': ' . $this->formatValue($value);
		}

		return $string;
	}
}
