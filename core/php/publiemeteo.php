<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";

if ( init('api') != config::byKey('api', 'publiemeteo') ) {
	connection::failed();
	echo __('{{Clef API non valide, vous n\'etes pas autorisé à effectuer cette action}}', __FILE__);
	log::add('publiemeteo','error',__('Clef API non valide, vous n\'etes pas autorisé à effectuer cette action', __FILE__));
	die();
}
log::add('publiemeteo','debug',__('Quelqu\'un demande la meteo format ', __FILE__).init('format'));
switch (init('format')) {
	case "awekas.at":
		header('Content-type: application/txt');
		print("\n");
		foreach (array("temp", "humidite", "pression", "pluie", "vent", "dirvent") as $indice) {
			$cmd_id = config::byKey($indice, 'publiemeteo');
			if ( $cmd_id != "" ) {
				$cmd = cmd::byId($cmd_id);
				if ( is_object($cmd) ) {
					$cmd->execCmd();
					if ( time() - strtotime($cmd->getCollectDate()) < 3600 )
					{
						if ( $indice == "pluie" ) {
							print(strtr($cmd->getStatistique(mktime(0,0,0), time())["avg"], ',', '.'));
						} else {
							log::add('publiemeteo','debug',__('Donnée ', __FILE__).$indice.' : '.$cmd->execCmd());
							print(strtr($cmd->execCmd(), ',', '.'));
						}
					}
					else
					{
						log::add('publiemeteo','debug',__('Donnée trop vieille pour ', __FILE__).$indice.' : '.$cmd->getCollectDate());
					}
				}
			}
			else
			{
				log::add('publiemeteo','debug',__('Aucune commande définie pour ', __FILE__).$indice);
			}
			print("\n");
		}
		print(date("H:i")."\n");
		print(date("d.m.Y")."\n");
		$cmd_id = config::byKey("pression", 'publiemeteo');
		if ( $cmd_id != "" ) {
			$cmd = cmd::byId($cmd_id);
			if ( is_object($cmd) ) {
				$cmd->execCmd();
				if ( time() - strtotime($cmd->getCollectDate()) < 300 )
				{
					# print(strtr($cmd_id->getValue() - $cmd_id->getValue(time() - 6 * 3600), ',', '.'));
				}
			}
		}
		print("\n");
		break;
	default:
		echo __('Format non supporté', __FILE__);
		log::add('publiemeteo','error',__('Format non supporté', __FILE__).' : '.init('format'));
		die();
		break;
}
?>
