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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class publiemeteo extends eqLogic {
	/*     * *************************Attributs****************************** */


	/*     * ***********************Methode static*************************** */

	public static function cron15() {
		log::add('publiemeteo', 'debug', 'Appel a cron15');
		if (config::byKey('wunderground_id', 'publiemeteo') != '') {
			log::add('publiemeteo', 'debug', 'Envoie des donnees vers wunderground');
			/*
			http://weatherstation.wunderground.com/weatherstation/updateweatherstation.php
			http://wiki.wunderground.com/index.php/PWS_-_Upload_Protocol
			*/
			log::add('publiemeteo', 'debug', 'Prepare les entêtes');
			$post['action'] = 'updateraw';
			$post['ID'] = config::byKey('wunderground_id', 'publiemeteo');
			$post['PASSWORD'] = config::byKey('wunderground_password', 'publiemeteo');
			$OldTZ = date_default_timezone_get();
			date_default_timezone_set('UTC');
			$post['dateutc'] = date('Y-m-d H:i:s');
			date_default_timezone_set($OldTZ);
			// Get Plugin info
			$data_to_send = false;
			$post['softwaretype'] = 'Jeedom publiemeteo';
			log::add('publiemeteo', 'debug', 'Prepare les données');
			foreach (array(
				"tempf temp 1.8 32",
				"baromin pression 0.000295299 0",
				"humidity humidite 1 0",
				"dailyrainin pluie 1 0",
				"rainin pluieheure 1 0",
				"dewptf pointrosee 1.8 32",
				"windspeedmph vent 0.6213711 0",
				"windgustmph rafalevent 0.6213711 0",
				"winddir dirvent 1 0"
			) as $element) {
				list($key, $indice, $coef, $add) = explode(' ', $element);
				log::add('publiemeteo', 'debug', 'Prepare information ' . $key);
				$cmd_id = config::byKey($indice, 'publiemeteo');
				if ($cmd_id != "") {
					$cmd = cmd::byId($cmd_id);
					if (is_object($cmd)) {
						$cmd->execCmd();
						if (time() - strtotime($cmd->getCollectDate()) < 900) {
							$data_to_send = true;
							if ($indice == "vent") {
								$data = $cmd->getStatistique(date('Y-m-d H:i:s', time() - 600), date('Y-m-d H:i:s'));
								$value = $data["avg"];
							} else {
								$value = $cmd->execCmd();
							}
							$post[$key] = $value * $coef + $add;
							log::add('publiemeteo', 'debug', $key . ' : ' . $value . ' => ' . $post[$key]);
						}
					}
				}
			}
			if ($data_to_send) {
				$url = 'http://weatherstation.wunderground.com/weatherstation/updateweatherstation.php?' . http_build_query($post);
				log::add('publiemeteo', 'debug', 'Envoie des donnees via ' . preg_replace("/PASSWORD=[^&]*&/", "PASSWORD=XXXXX&", $url));
				$content = @file_get_contents($url);
				if ($content != "success\n") {
					$content = @file_get_contents($url);
					if ($content != "success\n") {
						$content = @file_get_contents($url);
					}
				}
				if ($content != "success\n") {
					log::add('publiemeteo', 'error', __('Impossible d\'envoyer les données au serveur wunderground.com.', __FILE__) . " Message #" . $content . "#");
					throw new Exception(__('Impossible d\'envoyer les données au serveur wunderground.com.', __FILE__));
				}
			} else {
				log::add('publiemeteo', 'debug', 'Aucune donnée récente a envoyer');
			}
		}
	}

	public static function generatePassword($length = 8) {
		$possibleChars = "abcdefghijklmnopqrstuvwxyz";
		$password = '';

		for ($i = 0; $i < $length; $i++) {
			$rand = rand(0, strlen($possibleChars) - 1);
			$password .= substr($possibleChars, $rand, 1);
		}

		return $password;
	}

	public static function getIndicateurList() {
		return array(
			"temp" => "Température extérieur en °C",
			"pression" => "Pression atmosphérique en hPa",
			"humidite" => "Humidité extérieure en %",
			"pluie" => "Pluviométrie du jour en mm",
			"pluieheure" => "Pluviométrie de l'heure en mm",
			"vent" => "Vitesse du vent en km/h",
			"dirvent" => "Direction du vent en °C",
			"rafalevent" => "Rafales de vent en km/h",
			"pointrosee" => "point de rosée en °C"
		);
	}

	/*     * *********************Methode d'instance************************* */
}

class publiemeteoCmd extends cmd {

	/*     * **********************Getteur Setteur*************************** */
}
