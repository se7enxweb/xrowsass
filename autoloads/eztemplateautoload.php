<?php
/**
 * Template autoload definition for xrowsass
 *
 * @copyright Copyright (C) 2013
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 'script' => 'extension/xrowsass/autoloads/xrowsasstemplateoperators.php',
'class' => 'xrowSassOperator',
'operator_names' => array( 'xrowsass',
                           'xrowsass_require',
                           'xrowsass_load',
                           'xrowsassfiles')
);

?>