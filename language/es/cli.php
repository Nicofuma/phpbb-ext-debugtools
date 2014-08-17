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
	'NICOFUMA_DEBUGTOOLS_CLI_ARGUMENT_CACHE_KEY'	=>	'Clave de caché',
	'NICOFUMA_DEBUGTOOLS_CLI_CACHE_KEY_UNAVAILABLE'	=>	'No se pudo encontrar la clave `%s` en el caché.',

	'NICOFUMA_DEBUGTOOLS_CLI_ARGUMENT_SERVICE_NAME'			=>	'Nombre del servicio',
	'NICOFUMA_DEBUGTOOLS_CLI_ARGUMENT_ROUTE_NAME'			=>	'Nombre de la ruta',

	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_SHOW_PRIVATE'	=>	'Usar para mostrar servicios públicos *y* privados',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_TAG'			=>	'Muestra todos los servicios con una etiqueta específica',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_TAGS'			=>	'Muestra servicios etiquetados para una aplicación',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_PARAMETER'	=>	'Muestra un parámetro específico para una aplicación',
	'NICOFUMA_DEBUGTOOLS_CLI_OPTION_CONTAINER_PARAMETERS'	=>	'Muestra los parámetros para una aplicación',

	'NICOFUMA_DEBUGTOOLS_CLI_DESCRIPTION_CONTAINER_DEBUG'	=>	'Muestra los servicios actuales para una aplicación',
	'NICOFUMA_DEBUGTOOLS_CLI_DESCRIPTION_ROUTER_DEBUG'		=>	'Muestra las rutas actuales para una aplicación',

	'NICOFUMA_DEBUGTOOLS_CLI_HELP_CONTAINER_DEBUG'			=>	<<<EOF
El comando <info>%command.name%</info> command muestra todos los servicios <comment>públicos</comment> configurados:

  <info>php %command.full_name%</info>

Para obtener información específica acerca de un servicio, debe especificar su nombre:

  <info>php %command.full_name% validator</info>

Por defecto, los servicios privados están ocultos. Puede mostrar todos los servicios mediante 
el uso de la bandera --show-private:

  <info>php %command.full_name% --show-private</info>

Use la opción --tags para mostrar servicios <comment>Públicos</comment> etiquetados agrupados por etiqueta:

  <info>php %command.full_name% --tags</info>

Encontrar todos los servicios con una etiqueta específica, indicando el nombre de la etiqueta con la opción --tag:

  <info>php %command.full_name% --tag=form.type</info>

Use la opción --parameters para mostrar todos los parámetros:

  <info>php %command.full_name% --parameters</info>

Mostrar un parámetro determinado especificando su nombre con la opción --parameter:

  <info>php %command.full_name% --parameter=kernel.debug</info>
EOF
,

	'NICOFUMA_DEBUGTOOLS_CLI_HELP_ROUTER_DEBUG'				=>	<<<EOF
El <info>%command.name%</info> muestra las rutas configuradas:

  <info>php %command.full_name%</info>
EOF
,

	'NICOFUMA_DEBUGTOOLS_CLI_EXCEPTION_CONTAINER_DEBUG_INCOMPATIBLE_OPTIONS'			=>	'Las opciones de etiquetas, etiqueta, parámetros y parámetros no se pueden combinar entre sí.',
	'NICOFUMA_DEBUGTOOLS_CLI_EXCEPTION_CONTAINER_DEBUG_INCOMPATIBLE_OPTIONS_ARGUMENTS'	=>	'Las opciones de etiquetas, etiqueta, parámetros y parámetros no se pueden combinar con el nombre del servicio argumento.',
	'NICOFUMA_DEBUGTOOLS_CLI_EXCEPTION_ROUTER_DEBUG_ROUTE_NOT_EXIST'					=>	'La ruta "%s" no existe.',

	'NICOFUME_DEBUGTOOLS_CLI_CONTAINER_PUBLIC_PRIVATE_SERVICES'	=>	'Servicios <comment>Públicos</comment> y <comment>Privados</comment>',
	'NICOFUME_DEBUGTOOLS_CLI_CONTAINER_PUBLIC_SERVICES'			=>	'Servicios <comment>Públicos</comment>',
	'NICOFUME_DEBUGTOOLS_CLI_CONTAINER_LABEL_TAG'				=>	' con la etiqueta <info>%s</info>',
	'NICOFUMA_DEBUGTOOLS_CLI_CONTAINER_LABEL_INFO_SERVICE'		=>	'Información para el servicio <info>%s</info>',
	'NICOFUMA_DEBUGTOOLS_CLI_CONTAINER_SERVICE_ALIAS_FOR'		=>	'Este servicio es un alias del servicio <info>%s</info>',

	'ALIAS_FOR'			=>	'alias para',
	'ANY_MAJ'			=>	'CUALQUIER',
	'ARGUMENTS'			=>	'Argumentos',
	'CLASS'				=>	'Clase',
	'CLASS_NAME'		=>	'Nombre de clase',
	'CURRENT_ROUTES'	=>	'Rutas actuales',
	'DEFAULTS'			=>	'Predeterminados',
	'HOST'				=>	'Host',
	'HOST_REGEX'		=>	'Host-Regex',
	'LIST_PARAMETERS'	=>	'Lista de parámetros',
	'METHOD'			=>	'Método',
	'NAME'				=>	'Nombre',
	'NO_CUSTOM_MAJ'		=>	'NO PERSONALIZADA',
	'PATH'				=>	'Ruta',
	'PATH_REGEX'		=>	'Ruta-Regex',
	'PARAMETER'			=>	'Parametro',
	'PUBLIC'			=>	'Público',
	'REQUIRED_FILE'		=>	'Archivo requerido',
	'REQUIREMENTS'		=>	'Requisitos',
	'ROUTE_NAME'		=>	'Ruta "%s"',
	'SCHEME'			=>	'Esquema',
	'SCOPE'				=>	'Alcance',
	'SERVICE_ID'		=>	'ID de servicio',
	'SYNTHETIC'			=>	'Sintético',
	'TAGGED_SERVICES'	=>	'Servicios etiquetados',
	'TAGS'				=>	'Etiquetas',
));
