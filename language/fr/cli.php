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
	'NICOFUMA_DEBUGTOOLS_CLI_ARGUMENT_CACHE_KEY'	=>	'Clé de cache',
	'NICOFUMA_DEBUGTOOLS_CLI_CACHE_KEY_UNAVAILABLE'	=>	'Impossible de trouver la clé « %s » dans le cache.',

	'NICOFUMA_DEBUGTOOLS_CLI_ARGUMENT_SERVICE_NAME'			=>	'Nom du service (foo)',
	'NICOFUMA_DEBUGTOOLS_CLI_ARGUMENT_ROUTE_NAME'			=>	'Nom de la route',

	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_SHOW_PRIVATE'	=>	'Afficher les services publics *et* privés',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_TAG'			=>	'Voir tous les services avec un tag spécifique',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_TAGS'			=>	'Affiche les services marqués pour une application',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_PARAMETER'	=>	'Affiche les paramètres marqués pour une application',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_PARAMETERS'	=>	'Affiche les paramètres d’une application',

	'NICOFUMA_DEBUGTOOLS_CLI_DESCRIPTION_CONTAINER_DEBUG'	=>	'Affiche les services actuels pour une application',
	'NICOFUMA_DEBUGTOOLS_CLI_DESCRIPTION_ROUTER_DEBUG'		=>	'Affiche les routes actuelles pour une application',

	'NICOFUMA_DEBUGTOOLS_CLI_HELP_CONTAINER_DEBUG'			=>	<<<EOF
La commande <info>%command.name%</info> affiche tous les services <comment>publics</comment> :

  <info>php %command.full_name%</info>

Pour obtenir les informations spécifiques d’un service, indiquez son nom :

  <info>php %command.full_name% validator</info>

Par défaut, les services privés sont cachés. Vous pouvez afficher tous les services
en utilisant --show-private :

  <info>php %command.full_name% --show-private</info>

Utilisez l’option --tags pour afficher les services <commentaire>publics</comment> regroupés par tag :

  <info>php %command.full_name% --tags</info>

Trouver tous les services avec un tag spécifique en spécifiant le nom du tag avec l’option --tag :

  <info>php %command.full_name% --tag=form.type</info>

Utilisez l’option --parameters pour afficher tous les paramètres :

  <info>php %command.full_name% --parameters</info>

Afficher un paramètre spécifique en spécifiant son nom avec l’option --parameter :

  <info>php %command.full_name% --parameter=kernel.debug</info>
EOF
,

	'NICOFUMA_DEBUGTOOLS_CLI_HELP_ROUTER_DEBUG'				=>	<<<EOF
La commande <info>%command.name%</info> affiche les routes configurées :

  <info>php %command.full_name%</info>
EOF
,

	'NICOFUMA_DEBUGTOOLS_CLI_EXCEPTION_CONTAINER_DEBUG_INCOMPATIBLE_OPTIONS'			=>	'Les choix de tags, tag, les paramètres et réglages ne peuvent pas être combinés les uns avec les autres.',
	'NICOFUMA_DEBUGTOOLS_CLI_EXCEPTION_CONTAINER_DEBUG_INCOMPATIBLE_OPTIONS_ARGUMENTS'	=>	'Les choix de tags, tag, les paramètres et réglages ne peuvent pas être combinés avec le service de nom de l’argument.',
	'NICOFUMA_DEBUGTOOLS_CLI_EXCEPTION_ROUTER_DEBUG_ROUTE_NOT_EXIST'					=>	'La route « %s » n’existe pas.',

	'NICOFUME_DEBUGTOOLS_CLI_CONTAINER_PUBLIC_PRIVATE_SERVICES'	=>	'Services <comment>Publiques</comment> et <comment>privés</comment>',
	'NICOFUME_DEBUGTOOLS_CLI_CONTAINER_PUBLIC_SERVICES'			=>	'Services <comment>Publiques</comment>',
	'NICOFUME_DEBUGTOOLS_CLI_CONTAINER_LABEL_TAG'				=>	' avec le tag <info>%s</info>',
	'NICOFUMA_DEBUGTOOLS_CLI_CONTAINER_LABEL_INFO_SERVICE'		=>	'Informations pour le service <info>%s</info>',
	'NICOFUMA_DEBUGTOOLS_CLI_CONTAINER_SERVICE_ALIAS_FOR'		=>	'Ce service est un alias pour le service <info>%s</info>',

	'ALIAS_FOR'			=>	'alias pour',
	'ANY_MAJ'			=>	'TOUT',
	'ARGUMENTS'			=>	'Arguments',
	'CLASS'				=>	'Classe',
	'CLASS_NAME'		=>	'Nom de la classe',
	'CURRENT_ROUTES'	=>	'Routes actuelles',
	'DEFAULTS'			=>	'Par défaut',
	'HOST'				=>	'Host',
	'HOST_REGEX'		=>	'Host-Regex',
	'LIST_PARAMETERS'	=>	'Liste des paramètres',
	'METHOD'			=>	'Méthode',
	'NAME'				=>	'Nom',
	'NO_CUSTOM_MAJ'		=>	'NON PERSONNALIÉ',
	'PATH'				=>	'Chemin',
	'PATH_REGEX'		=>	'Chemin-Regex',
	'PARAMETER'			=>	'Paramètre',
	'PUBLIC'			=>	'Public',
	'REQUIRED_FILE'		=>	'Fichiers requis',
	'REQUIREMENTS'		=>	'Exigences',
	'ROUTE_NAME'		=>	'Route « %s »',
	'SCHEME'			=>	'Programme',
	'SCOPE'				=>	'Portée',
	'SERVICE_ID'		=>	'ID de service',
	'SYNTHETIC'			=>	'Synthétique',
	'TAGGED_SERVICES'	=>	'Services Taggés',
	'TAGS'				=>	'Tags',
));
