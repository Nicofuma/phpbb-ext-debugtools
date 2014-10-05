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

namespace nicofuma\debugtools\console\command\twig;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class transform extends \phpbb\console\command\command
{
	/**
	 * @var \Twig_Environment
	 */
	protected $twig;

	/**
	 * Constructor
	 *
	 * @param \phpbb\user       $user User instance
	 * @param \Twig_Environment $twig Twig instance
	 */
	function __construct(\phpbb\user $user, \Twig_Environment $twig)
	{
		$this->twig = $twig;

		$user->add_lang_ext('nicofuma/debugtools', 'cli');

		parent::__construct($user);
	}

	protected function configure()
	{
		$this
			->setName('twig:transform')
			->setDescription('Transforms phpBB template to a pure twig template')
			->addArgument('filename')
			;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$twig     = $this->twig;
		$template = null;
		$filename = $input->getArgument('filename');

		if (!$filename)
		{
			if (0 !== ftell(STDIN))
			{
				throw new \RuntimeException("Please provide a filename or pipe template content to stdin.");
			}

			while (!feof(STDIN))
			{
				$template .= fread(STDIN, 1024);
			}

			return $this->transform_template($twig, $output, $template);
		}

		if (!is_readable($filename))
		{
			throw new \RuntimeException(sprintf('File "%s" is not readable', $filename));
		}

		return $this->transform_template($twig, $output, file_get_contents($filename));
	}

	protected function transform_template(\Twig_Environment $twig, OutputInterface $output, $template, $file = null)
	{
		try
		{
			$twig->tokenize($template, $file ? (string) $file : null);
			$lexer = $twig->getLexer();

			$class = new \ReflectionClass('\Twig_Lexer');
			$property = $class->getProperty('code');
			$property->setAccessible(true);

			$code = $property->getValue($lexer);
			$output->write($code);
		}
		catch (\Twig_Error $e)
		{
			$this->renderException($output, $template, $e, $file);

			return 1;
		}

		return 0;
	}

	protected function renderException(OutputInterface $output, $template, \Twig_Error $exception, $file = null)
	{
		$line  = $exception->getTemplateLine();
		$lines = $this->getContext($template, $line);

		if ($file)
		{
			$output->writeln(sprintf("<error>KO</error> in %s (line %s)", $file, $line));
		}
		else
		{
			$output->writeln(sprintf("<error>KO</error> (line %s)", $line));
		}

		foreach ($lines as $no => $code)
		{
			$output->writeln(sprintf(
				"%s %-6s %s",
				$no == $line ? '<error>>></error>' : ' ',
				$no,
				$code
			));

			if ($no == $line)
			{
				$output->writeln(sprintf('<error>>> %s</error> ', $exception->getRawMessage()));
			}
		}
	}

	protected function getContext($template, $line, $context = 3)
	{
		$lines    = explode("\n", $template);
		$position = max(0, $line - $context);
		$max      = min(count($lines), $line - 1 + $context);
		$result   = array();

		while ($position < $max)
		{
			$result[$position + 1] = $lines[$position];
			$position++;
		}

		return $result;
	}
}
