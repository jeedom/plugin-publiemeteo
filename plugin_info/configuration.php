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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
	include_file('desktop', '404', 'php');
	die();
}
?>
<form class="form-horizontal">
    <fieldset>
    	<legend>Lien des données Météo pour des sites tiers</legend>
        <div class="form-group">
			<label class="col-lg-4 control-label"><a href="http://ma.station-meteo.com/import/new" target="_new"><img src="http://www.station-meteo.com/img/logo/station-meteo-120-60.gif" alt="Station-meteo" border="0" height="20"/></a> ma.station-meteo.com : Choisir format "awekas.txt"</label>
            <div class="col-lg-8 url_api">
            <?php
				echo network::getNetworkAccess('external') . "/plugins/publiemeteo/core/php/publiemeteo.php?api=" . config::byKey('api', 'publiemeteo') . "&format=awekas.at";
			?></div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label"><a href="http://www.awekas.at" target="_new"><img src="http://www.awekas.at/images/awekas-logo-kl.jpg" alt="Awekas" border="0" height="20"/></a> awekas.at : Choisir format "WSWIN"</label>
            <div class="col-lg-6 url_api">
            <?php
				echo network::getNetworkAccess('external') . "/plugins/publiemeteo/core/php/publiemeteo.php?api=" . config::byKey('api', 'publiemeteo') . "&format=awekas.at";
			?></div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label"><a href="http://www.wunderground.com" target="_new"><img src="https://icons.wxug.com/graphics/wu2/logo_130x80.png" alt="Wunderground" border="0" height="20"/></a> wunderground.com : </label>
            <div class="col-lg-1">
				Id : 
			</div>
			<div class="col-lg-2">
				<input class="configKey form-control" data-l1key="wunderground_id" placeholder="ID as registered by wunderground.com">
			</div>
            <div class="col-lg-1">
				Password : 
			</div>
			<div class="col-lg-2">
				<input type="password" class="configKey form-control" data-l1key="wunderground_password" placeholder="PASSWORD registered with this ID">
			</div>
        </div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Clef API}}</label>
			<div class="col-lg-5">
				<a class="btn btn-success" id="bt_resetkey"><i class="fa fa-refresh"></i> {{Reset}}</a> Il sera nécessaire de reconfigurer les URLs sur les sites Internet.
			</div>
		</div>
    </fieldset>
</form>
<div id='div_ResetKeyResult' style="display: none;"></div>
	<script>
		$('#bt_resetkey').on('click', function () {
			bootbox.confirm('{{Etes-vous sûr de vouloir forcer la génération d\'une nouvelle clef API ?}}', function (result) {
				if (result) {
					$.ajax({// fonction permettant de faire de l'ajax
						type: "POST", // methode de transmission des données au fichier php
						url: "plugins/publiemeteo/core/ajax/publiemeteo.ajax.php", // url du fichier php
						data: {
							action: "ResetKey",
						},
						dataType: 'json',
						error: function(request, status, error) {
							handleAjaxError(request, status, $('#div_ResetKeyResult'));
						},
						success: function(data) { // si l'appel a bien fonctionné
							if (data.state != 'ok') {
								$('#div_ResetKeyResult').showAlert({message: data.result, level: 'danger'});
							} else {
								$('.url_api').each(function(){
									$(this).value($(this).value().replace(/api=[a-z]*&/i, "api="+data.result+"&"));
								});
								$('#div_ResetKeyResult').showAlert({message: '{{Mise a jour de clef API OK}}', level: 'success'});
							}
						}
					});
				}
			});
		});
	</script>
