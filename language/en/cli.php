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

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'NICOFUMA_DEBUGTOOLS_CLI_ARGUMENT_CACHE_KEY'	=>	'Cache key',
	'NICOFUMA_DEBUGTOOLS_CLI_CACHE_KEY_UNAVAILABLE'	=>	'Could not find the key `%s` in the cache.',

	// container:debug
	'NICOFUMA_DEBUGTOOLS_CLI_ARGUMENT_SERVICE_NAME'			=>	'A service name (foo)',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_SHOW_PRIVATE'	=>	'Use to show public *and* private services',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_TAG'			=>	'Show all services with a specific tag',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_TAGS'			=>	'Displays tagged services for an application',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_PARAMETER'	=>	'Displays a specific parameter for an application',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_PARAMETERS'	=>	'Displays parameters for an application',

	'NICOFUMA_DEBUGTOOLS_CLI_DESCRIPTION_CONTAINER_DEBUG'	=>	'Displays current services for an application',
	'NICOFUMA_DEBUGTOOLS_CLI_HELP_CONTAINER_DEBUG'			=>	<<<EOF
The <info>%command.name%</info> command displays all configured <comment>public</comment> services:

  <info>php %command.full_name%</info>

To get specific information about a service, specify its name:

  <info>php %command.full_name% validator</info>

By default, private services are hidden. You can display all services by
using the --show-private flag:

  <info>php %command.full_name% --show-private</info>

Use the --tags option to display tagged <comment>public</comment> services grouped by tag:

  <info>php %command.full_name% --tags</info>

Find all services with a specific tag by specifying the tag name with the --tag option:

  <info>php %command.full_name% --tag=form.type</info>

Use the --parameters option to display all parameters:

  <info>php %command.full_name% --parameters</info>

Display a specific parameter by specifying his name with the --parameter option:

  <info>php %command.full_name% --parameter=kernel.debug</info>
EOF
,

	'NICOFUMA_DEBUGTOOLS_CLI_EXCEPTION_CONTAINER_DEBUG_INCOMPATIBLE_OPTIONS_ARGUMENTS'	=>	'The options tags, tag, parameters & parameter can not be combined with the service name argument.',
	'NICOFUMA_DEBUGTOOLS_CLI_EXCEPTION_CONTAINER_DEBUG_INCOMPATIBLE_OPTIONS'			=>	'The options tags, tag, parameters & parameter can not be combined together.',

	'NICOFUME_DEBUGTOOLS_CLI_CONTAINER_PUBLIC_PRIVATE_SERVICES'	=>	'<comment>Public</comment> and <comment>private</comment> services',
	'NICOFUME_DEBUGTOOLS_CLI_CONTAINER_PUBLIC_SERVICES'			=>	'<comment>Public</comment> services',
	'NICOFUME_DEBUGTOOLS_CLI_CONTAINER_LABEL_TAG'				=>	' with tag <info>%s</info>',
	'NICOFUMA_DEBUGTOOLS_CLI_CONTAINER_LABEL_INFO_SERVICE'		=>	'Information for service <info>%s</info>',
	'NICOFUMA_DEBUGTOOLS_CLI_CONTAINER_SERVICE_ALIAS_FOR'		=>	'This service is an alias for the service <info>%s</info>',

	'ALIAS_FOR'			=>	'alias for',
	'ARGUMENTS'			=>	'Arguments',
	'CLASS'				=>	'Class',
	'CLASS_NAME'		=>	'Class Name',
	'LIST_PARAMETERS'	=>	'List of parameters',
	'PARAMETER'			=>	'Parameter',
	'PUBLIC'			=>	'Public',
	'REQUIRED_FILE'		=>	'Required File',
	'SCOPE'				=>	'Scope',
	'SERVICE_ID'		=>	'Service Id',
	'SYNTHETIC'			=>	'Synthetic',
	'TAGGED_SERVICES'	=>	'Tagged services',
	'TAGS'				=>	'Tags',
));
