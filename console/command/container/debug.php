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

namespace nicofuma\debugtools\console\command\container;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
* A console command for retrieving information about services
*
* @author Ryan Weaver <ryan@thatsquality.com>
*/
class debug extends \phpbb\console\command\command
{
	/**
	* @var \Symfony\Component\DependencyInjection\ContainerBuilder|null
	*/
	protected $containerBuilder;

	/**
	* @var \phpbb\config_php_file
	*/
	protected $config_php_file;

	/**
	* @var string phpBB Root Path
	*/
	protected $phpbb_root_path;

	/**
	* @var string php file extension
	*/
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\user			$user				User instance
	* @param \phpbb\config_php_file	$config_php_file
	* @param string					$phpbb_root_path	Path to the phpbb includes directory.
	* @param string					$php_ext			php file extension
	*/
	function __construct(\phpbb\user $user, \phpbb\config_php_file $config_php_file, $phpbb_root_path, $php_ext)
	{
		$this->config_php_file	= $config_php_file;
		$this->phpbb_root_path	= $phpbb_root_path;
		$this->php_ext			= $php_ext;

		$user->add_lang_ext('nicofuma/debugtools', 'cli');

		parent::__construct($user);
	}

	/**
	* {@inheritdoc}
	*/
	protected function configure()
	{
		$this
			->setName('debug:container')
			->setDefinition(array(
				new InputArgument('name', InputArgument::OPTIONAL, $this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_ARGUMENT_SERVICE_NAME')),
				new InputOption('show-private', null, InputOption::VALUE_NONE, $this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_SHOW_PRIVATE')),
				new InputOption('tag', null, InputOption::VALUE_REQUIRED, $this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_TAG')),
				new InputOption('tags', null, InputOption::VALUE_NONE, $this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_TAGS')),
				new InputOption('parameter', null, InputOption::VALUE_REQUIRED, $this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_PARAMETER')),
				new InputOption('parameters', null, InputOption::VALUE_NONE, $this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_PARAMETERS'))
			))
			->setDescription($this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_DESCRIPTION_CONTAINER_DEBUG'))
			->setHelp($this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_HELP_CONTAINER_DEBUG'));
	}

	/**
	* {@inheritdoc}
	*
	* @throws \LogicException
	*/
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->validateInput($input);

		$this->containerBuilder = $this->getContainerBuilder();

		if ($input->getOption('parameters'))
		{
			$parameters = $this->getContainerBuilder()->getParameterBag()->all();

			// Sort parameters alphabetically
			ksort($parameters);

			$this->outputParameters($output, $parameters);

			return;
		}

		$parameter = $input->getOption('parameter');
		if (null !== $parameter)
		{
			$output->write($this->formatParameter($this->getContainerBuilder()->getParameter($parameter)));

			return;
		}

		if ($input->getOption('tags'))
		{
			$this->outputTags($output, $input->getOption('show-private'));

			return;
		}

		$tag = $input->getOption('tag');
		if (null !== $tag)
		{
			$serviceIds = array_keys($this->containerBuilder->findTaggedServiceIds($tag));
		}
		else
		{
			$serviceIds = $this->containerBuilder->getServiceIds();
		}

		// sort so that it reads like an index of services
		asort($serviceIds);

		$name = $input->getArgument('name');
		if ($name)
		{
			$this->outputService($output, $name);
		}
		else
		{
			$this->outputServices($output, $serviceIds, $input->getOption('show-private'), $tag);
		}
	}

	protected function validateInput(InputInterface $input)
	{
		$options = array('tags', 'tag', 'parameters', 'parameter');

		$optionsCount = 0;
		foreach ($options as $option)
		{
			if ($input->getOption($option))
			{
				$optionsCount++;
			}
		}

		$name = $input->getArgument('name');
		if ((null !== $name) && ($optionsCount > 0))
		{
			throw new \InvalidArgumentException($this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_EXCEPTION_CONTAINER_DEBUG_INCOMPATIBLE_OPTIONS_ARGUMENTS'));
		}
		else if ((null === $name) && $optionsCount > 1)
		{
			throw new \InvalidArgumentException($this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_EXCEPTION_CONTAINER_DEBUG_INCOMPATIBLE_OPTIONS'));
		}
	}

	protected function outputServices(OutputInterface $output, $serviceIds, $showPrivate = false, $showTagAttributes = null)
	{
		// set the label to specify public or public+private
		if ($showPrivate)
		{
			$label = $this->user->lang('NICOFUME_DEBUGTOOLS_CLI_CONTAINER_PUBLIC_PRIVATE_SERVICES');
		}
		else
		{
			$label = $this->user->lang('NICOFUME_DEBUGTOOLS_CLI_CONTAINER_PUBLIC_SERVICES');
		}
		if ($showTagAttributes)
		{
			$label .= $this->user->lang('NICOFUME_DEBUGTOOLS_CLI_CONTAINER_LABEL_TAG', $showTagAttributes);
		}

		$output->writeln($this->getHelper('formatter')->formatSection('container', $label));

		// loop through to get space needed and filter private services
		$maxName	= 4;
		$maxScope	= 6;
		$maxTags	= array();
		foreach ($serviceIds as $key => $serviceId)
		{
			$definition = $this->resolveServiceDefinition($serviceId);

			if ($definition instanceof Definition)
			{
				// filter out private services unless shown explicitly
				if (!$showPrivate && !$definition->isPublic())
				{
					unset($serviceIds[$key]);
					continue;
				}

				if (strlen($definition->getScope()) > $maxScope)
				{
					$maxScope = strlen($definition->getScope());
				}

				if (null !== $showTagAttributes)
				{
					$tags = $definition->getTag($showTagAttributes);
					foreach ($tags as $tag)
					{
						foreach ($tag as $key => $value)
						{
							if (!isset($maxTags[$key]))
							{
								$maxTags[$key] = strlen($key);
							}
							if (strlen($value) > $maxTags[$key])
							{
								$maxTags[$key] = strlen($value);
							}
						}
					}
				}
			}

			if (strlen($serviceId) > $maxName)
			{
				$maxName = strlen($serviceId);
			}
		}
		$format = '%-' . $maxName . 's ';
		$format .= implode("", array_map(function ($length)
		{
			return "%-{$length}s ";
		}, $maxTags));
		$format .= '%-' . $maxScope . 's %s';

		// the title field needs extra space to make up for comment tags
		$format1 = '%-' . ($maxName + 19) . 's ';
		$format1 .= implode("", array_map(function ($length)
		{
			return '%-' . ($length + 19) . 's ';
		}, $maxTags));
		$format1 .= '%-' . ($maxScope + 19) . 's %s';

		$tags = array();
		foreach ($maxTags as $tagName => $length)
		{
			$tags[] = '<comment>' . $tagName . '</comment>';
		}
		$output->writeln(vsprintf($format1, $this->buildArgumentsArray('<comment>' . $this->user->lang('SERVICE_ID') . '</comment>', '<comment>' . $this->user->lang('SCOPE') . '</comment>', '<comment>' . $this->user->lang('CLASS_NAME') . '</comment>', $tags)));

		foreach ($serviceIds as $serviceId)
		{
			$definition = $this->resolveServiceDefinition($serviceId);

			if ($definition instanceof Definition)
			{
				$lines = array();
				if (null !== $showTagAttributes)
				{
					foreach ($definition->getTag($showTagAttributes) as $key => $tag)
					{
						$tagValues = array();
						foreach (array_keys($maxTags) as $tagName)
						{
							$tagValues[] = isset($tag[$tagName]) ? $tag[$tagName] : "";
						}
						if (0 === $key)
						{
							$lines[] = $this->buildArgumentsArray($serviceId, $definition->getScope(), $definition->getClass(), $tagValues);
						}
						else
						{
							$lines[] = $this->buildArgumentsArray(' "', '', '', $tagValues);
						}
					}
				}
				else
				{
					$lines[] = $this->buildArgumentsArray($serviceId, $definition->getScope(), $definition->getClass());
				}

				foreach ($lines as $arguments)
				{
					$output->writeln(vsprintf($format, $arguments));
				}
			}
			else if ($definition instanceof Alias)
			{
				$alias = $definition;
				$output->writeln(vsprintf($format, $this->buildArgumentsArray($serviceId, 'n/a', sprintf('<comment>' . $this->user->lang('ALIAS_FOR') . '</comment> <info>%s</info>', (string) $alias), count($maxTags) ? array_fill(0, count($maxTags), "") : array())));
			}
			else
			{
				// we have no information (happens with "service_container")
				$service = $definition;
				$output->writeln(vsprintf($format, $this->buildArgumentsArray($serviceId, '', get_class($service), count($maxTags) ? array_fill(0, count($maxTags), "") : array())));
			}
		}
	}

	protected function buildArgumentsArray($serviceId, $scope, $className, array $tagAttributes = array())
	{
		$arguments = array($serviceId);
		foreach ($tagAttributes as $tagAttribute)
		{
			$arguments[] = $tagAttribute;
		}
		$arguments[] = $scope;
		$arguments[] = $className;

		return $arguments;
	}

	/**
	* Renders detailed service information about one service
	*/
	protected function outputService(OutputInterface $output, $serviceId)
	{
		$definition = $this->resolveServiceDefinition($serviceId);

		$label = $this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_CONTAINER_LABEL_INFO_SERVICE', $serviceId);
		$output->writeln($this->getHelper('formatter')->formatSection('container', $label));
		$output->writeln('');

		if ($definition instanceof Definition)
		{
			$output->writeln(sprintf('<comment>' . $this->user->lang('SERVICE_ID') . '</comment> %s', $serviceId));
			$output->writeln(sprintf('<comment>' . $this->user->lang('CLASS') . '</comment> %s', $definition->getClass() ? : "-"));

			$arguments = $definition->getArguments();
			if (count($arguments))
			{
				$output->writeln('<comment>' . $this->user->lang('ARGUMENTS') . '</comment>');
				foreach ($arguments as $argument)
				{
					if ($argument instanceof Reference)
					{
						$output->writeln(' - @' . $argument);
					}
					else
					{
						$parameter = $this->resolve_parameter($argument);
						if ($parameter !== false)
						{
							$output->writeln(sprintf(' - %-30s (%s)', '%' . $parameter . '%', $argument));
						}
						else
						{
							$output->writeln(' - ' . $argument);
						}
					}
				}
			}
			else
			{
				$output->writeln('<comment>' . $this->user->lang('ARGUMENTS') . '</comment> -');
			}

			$tags = $definition->getTags();
			if (count($tags))
			{
				$output->writeln('<comment>' . $this->user->lang('TAGS') . '</comment>');
				foreach ($tags as $tagName => $tagData)
				{
					foreach ($tagData as $singleTagData)
					{
						$output->writeln(sprintf(' - %-30s (%s)', $tagName, implode(', ', array_map(function ($key, $value)
						{
							return sprintf('<info>%s</info>: %s', $key, $value);
						}, array_keys($singleTagData), array_values($singleTagData)))));
					}
				}
			}
			else
			{
				$output->writeln('<comment>' . $this->user->lang('TAGS') . '</comment> -');
			}

			$output->writeln(sprintf('<comment>' . $this->user->lang('SCOPE') . '</comment> %s', $definition->getScope()));

			$public = $definition->isPublic() ? 'yes' : 'no';
			$output->writeln(sprintf('<comment>' . $this->user->lang('PUBLIC') . '</comment> %s', $public));

			$synthetic = $definition->isSynthetic() ? 'yes' : 'no';
			$output->writeln(sprintf('<comment>' . $this->user->lang('SYNTHETIC') . '</comment> %s', $synthetic));

			$file = $definition->getFile() ? $definition->getFile() : '-';
			$output->writeln(sprintf('<comment>' . $this->user->lang('REQUIRED_FILE') . '</comment> %s', $file));
		}
		else if ($definition instanceof Alias)
		{
			$alias = $definition;
			$output->writeln($this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_CONTAINER_SERVICE_ALIAS_FOR', (string) $alias));
		}
		else
		{
			// edge case (but true for "service_container", all we have is the service itself
			$service = $definition;
			$output->writeln(sprintf('<comment>' . $this->user->lang('SERVICE_ID') . '</comment> %s', $serviceId));
			$output->writeln(sprintf('<comment>' . $this->user->lang('CLASS') . '</comment> %s', get_class($service)));
		}
	}

	protected function outputParameters(OutputInterface $output, $parameters)
	{
		$output->writeln($this->getHelper('formatter')->formatSection('container', $this->user->lang('LIST_PARAMETERS')));

		$terminalDimensions	= $this->getApplication()->getTerminalDimensions();
		$maxTerminalWidth	= $terminalDimensions[0];
		$maxParameterWidth	= 0;
		$maxValueWidth		= 0;

		// Determine max parameter & value length
		foreach ($parameters as $parameter => $value)
		{
			$parameterWidth = strlen($parameter);
			if ($parameterWidth > $maxParameterWidth)
			{
				$maxParameterWidth = $parameterWidth;
			}

			$valueWith = strlen($this->formatParameter($value));
			if ($valueWith > $maxValueWidth)
			{
				$maxValueWidth = $valueWith;
			}
		}

		$maxValueWidth = min($maxValueWidth, $maxTerminalWidth - $maxParameterWidth - 1);

		$formatTitle	= '%-' . ($maxParameterWidth + 19) . 's %-' . ($maxValueWidth + 19) . 's';
		$format			= '%-' . $maxParameterWidth . 's %-' . $maxValueWidth . 's';

		$output->writeln(sprintf($formatTitle, '<comment>' . $this->user->lang('PARAMETER') . '</comment>', '<comment>' . $this->user->lang('VALUE') . '</comment>'));

		foreach ($parameters as $parameter => $value)
		{
			$splits = str_split($this->formatParameter($value), $maxValueWidth);

			foreach ($splits as $index => $split)
			{
				if (0 === $index)
				{
					$output->writeln(sprintf($format, $parameter, $split));
				}
				else
				{
					$output->writeln(sprintf($format, ' ', $split));
				}
			}
		}
	}

	/**
	* Loads the ContainerBuilder from the cache.
	*
	* @return \Symfony\Component\DependencyInjection\ContainerBuilder
	*
	* @throws \LogicException
	*/
	protected function getContainerBuilder()
	{
		$container_builder = new \phpbb\di\container_builder($this->config_php_file, $this->phpbb_root_path, $this->php_ext);
		$container_builder->set_compile_container(true);
		$container_builder->set_dump_container(false);
		$container = $container_builder->get_container();

		return $container;
	}

	/**
	* Given an array of service IDs, this returns the array of corresponding
	* Definition and Alias objects that those ids represent.
	*
	* @param string $serviceId The service id to resolve
	*
	* @return Definition|Alias
	*/
	protected function resolveServiceDefinition($serviceId)
	{
		if ($this->containerBuilder->hasDefinition($serviceId))
		{
			return $this->containerBuilder->getDefinition($serviceId);
		}

		// Some service IDs don't have a Definition, they're simply an Alias
		if ($this->containerBuilder->hasAlias($serviceId))
		{
			return $this->containerBuilder->getAlias($serviceId);
		}

		// the service has been injected in some special way, just return the service
		return $this->containerBuilder->get($serviceId);
	}

	/**
	* Given a parameter value, this returns the corresponding parameter if it exists, false otherwise.
	*
	* @param $parameter
	* @return bool|string
	*/
	protected function resolve_parameter($parameter)
	{
		foreach ($this->containerBuilder->getParameterBag()->all() as $key => $value)
		{
			if ($parameter === $value)
			{
				return $key;
			}
		}

		return false;
	}

	/**
	* Renders list of tagged services grouped by tag
	*
	* @param OutputInterface	$output
	* @param bool				$showPrivate
	*/
	protected function outputTags(OutputInterface $output, $showPrivate = false)
	{
		$tags = $this->containerBuilder->findTags();
		asort($tags);

		$label = $this->user->lang('TAGGED_SERVICES');
		$output->writeln($this->getHelper('formatter')->formatSection('container', $label));

		foreach ($tags as $tag)
		{
			$serviceIds = $this->containerBuilder->findTaggedServiceIds($tag);

			foreach ($serviceIds as $serviceId => $attributes)
			{
				$definition = $this->resolveServiceDefinition($serviceId);
				if ($definition instanceof Definition)
				{
					if (!$showPrivate && !$definition->isPublic())
					{
						unset($serviceIds[$serviceId]);
						continue;
					}
				}
			}

			if (count($serviceIds) === 0)
			{
				continue;
			}

			$output->writeln($this->getHelper('formatter')->formatSection('tag', $tag));

			foreach ($serviceIds as $serviceId => $attributes)
			{
				$output->writeln($serviceId);
			}

			$output->writeln('');
		}
	}

	protected function formatParameter($value)
	{
		if (is_bool($value) || is_array($value) || (null === $value))
		{
			return json_encode($value);
		}

		return $value;
	}
}
