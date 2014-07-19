<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/
namespace nicofuma\debugtools\console\command\cache;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class show extends \phpbb\console\command\command
{
	/** @var \phpbb\cache\driver\driver_interface */
	protected $cache;

	/** @var \phpbb\user */
	protected $user;
	/**
	* Constructor
	*
	* @param \phpbb\user							$user	User instance
	* @param \phpbb\cache\driver\driver_interface	$cache	Cache instance
	*/
	public function __construct(\phpbb\user $user, \phpbb\cache\driver\driver_interface $cache)
	{
		$this->cache = $cache;
		$this->user = $user;

		$user->add_lang_ext('nicofuma/debugtools', 'cli');

		parent::__construct();
	}

	/**
	* {@inheritdoc}
	*/
	protected function configure()
	{
		$this
			->setName('cache:show')
			->setDescription('Get saved cache object.')
			->addArgument(
				'key',
				InputArgument::REQUIRED,
				$this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_ARGUMENT_CACHE_KEY')
			)
		;
	}

	/**
	* Executes the command cache:show.
	*
	* Display the information available about a cached value.
	*
	* @param InputInterface  $input  An InputInterface instance
	* @param OutputInterface $output An OutputInterface instance
	*
	* @return null
	*/
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$key = $input->getArgument('key');
		if ($this->cache->_exists($key))
		{
			$output->writeln('<info>' . $this->user->lang('VALUE') . $this->user->lang('COLON') .'</info>');
			$value = $this->cache->get($key);

			if (is_array($value))
			{
				$output->writeln(print_r($value, true), OutputInterface::OUTPUT_PLAIN);
			}
			else
			{
				$output->writeln($value, OutputInterface::OUTPUT_PLAIN);
			}
		}
		else
		{
			$output->writeln('<error>' . $this->user->lang('NICOFUMA_DEBUGTOOLS_CLI_CACHE_KEY_UNAVAILABLE', $key) .'</error>');
		}
	}
}
