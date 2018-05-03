
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

$('.listEquipement').on('click', function () {
    var el = $(this);
    jeedom.cmd.getSelectModal({}, function(result) {
		$('#cmdSupportId_' + el.data('input')).val(result.cmd.id);
		$('#cmdSupportHumanName_' + el.data('input')).val(result.human);
    });
});

$('.delEquipement').on('click', function () {
	$('#cmdSupportId_' + $(this).data('input')).val('');
	$('#cmdSupportHumanName_' + $(this).data('input')).val('');
});

$('.bt_savepubliemeteo').on('click',function(){
    var publiemeteo = {};
    $('tr.publiemeteocmd').each(function(){
        publiemeteo[$(this).attr('data-id')] = $('#cmdSupportId_'+$(this).attr('data-id')).value();
    });
     $('input.publiemeteocomplement').each(function(){
        publiemeteo[$(this).attr('id')] = $(this).value();
    });
    $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // méthode de transmission des données au fichier php
            url: "plugins/publiemeteo/core/ajax/publiemeteo.ajax.php", // url du fichier php
            data: {
                action: "savepubliemeteo",
                config: json_encode(publiemeteo),
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_alert').showAlert({message: '{{Sauvegarde réalisée avec succès}}', level: 'success'});
        }
    });
});

