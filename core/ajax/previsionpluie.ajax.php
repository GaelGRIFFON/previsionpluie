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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception('401 Unauthorized');
    }

    log::add('previsionpluie', 'debug', 'Ajax: Action = ' . init('action'));
                    
    if (init('action') == 'searchCity') {
    	$city = init('city'); 
    	log::add('previsionpluie', 'debug', 'Ajax: searchCity - city = ' . $city);

    	$res = file_get_contents("http://www.meteofrance.com/mf3-rpc-portlet/rest/lieu/facet/pluie/search/" . $city);

		if($res){
			ajax::success($res);
		}else{
			throw new Exception("Impossible d'obtenir le résultat de la recherche");
		}

    	
    }

    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));

} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}




?>
