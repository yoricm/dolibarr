<?php
/* Copyright (C) 2005-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2007 Regis Houssin        <regis@dolibarr.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * or see http://www.gnu.org/
 */

/**
 *	\file       htdocs/core/modules/import/modules_import.php
 *	\ingroup    export
 *	\brief      File of parent class for import file readers
 */
require_once(DOL_DOCUMENT_ROOT.'/core/lib/functions.lib.php');


/**
 *	Parent class for import file readers
 */
class ModeleImports
{
    var $db;
    var $datatoimport;

	var $error='';

	var $id;           // Id of driver
	var $label;        // Label of driver
	var $extension;    // Extension of files imported by driver
	var $version;      // Version of driver

	var $label_lib;    // Label of external lib used by driver
	var $version_lib;  // Version of external lib used by driver

	// Array of all drivers
	var $_driverlabel=array();
	var $_driverdesc=array();
	var $_driverversion=array();

	var $_liblabel=array();
	var $_libversion=array();


	/**
     *  Constructor
	 */
	function ModeleImports()
	{
	}

	/**
	 *   Charge en memoire et renvoie la liste des modeles actifs
	 *
	 *   @param	DoliDB	$db      Handler de base
	 */
	function liste_modeles($db)
	{
		dol_syslog("ModeleImport::liste_modeles");

		$dir=DOL_DOCUMENT_ROOT."/core/modules/import/";
		$handle=opendir($dir);

		// Recherche des fichiers drivers imports disponibles
		$var=True;
		$i=0;
        if (is_resource($handle))
        {
    		while (($file = readdir($handle))!==false)
    		{
    			if (preg_match("/^import_(.*)\.modules\.php/i",$file,$reg))
    			{
    				$moduleid=$reg[1];

    				// Chargement de la classe
    				$file = $dir."/import_".$moduleid.".modules.php";
    				$classname = "Import".ucfirst($moduleid);

    				require_once($file);
    				$module = new $classname($db,'');

    				// Picto
    				$this->picto[$module->id]=$module->picto;
    				// Driver properties
    				$this->_driverlabel[$module->id]=$module->getDriverLabel();
    				$this->_driverdesc[$module->id]=$module->getDriverDesc();
    				$this->_driverversion[$module->id]=$module->getDriverVersion();
    				// If use an external lib
    				$this->_liblabel[$module->id]=$module->getLibLabel();
    				$this->_libversion[$module->id]=$module->getLibVersion();

    				$i++;
    			}
    		}
        }

		return array_keys($this->_driverlabel);
	}


	/**
	 *  Return picto of import driver
	 *
	 *	@return	string
	 */
	function getPicto($key)
	{
		return $this->picto[$key];
	}

	/**
	 *  Renvoi libelle d'un driver import
	 *
	 *	@return	string
	 */
	function getDriverLabel($key)
	{
		return $this->_driverlabel[$key];
	}

	/**
	 *  Renvoi la description d'un driver import
	 *
	 *	@return	string
	 */
	function getDriverDesc($key)
	{
		return $this->_driverdesc[$key];
	}

	/**
	 *  Renvoi version d'un driver import
	 *
	 *	@return	string
	 */
	function getDriverVersion($key)
	{
		return $this->_driverversion[$key];
	}

	/**
	 *  Renvoi libelle de librairie externe du driver
	 *
	 *	@return	string
	 */
	function getLibLabel($key)
	{
		return $this->_liblabel[$key];
	}

	/**
	 *  Renvoi version de librairie externe du driver
	 *
	 *	@return	string
	 */
	function getLibVersion($key)
	{
		return $this->_libversion[$key];
	}

}


?>
