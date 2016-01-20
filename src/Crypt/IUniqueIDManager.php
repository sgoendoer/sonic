<?php namespace sgoendoer\Sonic\Crypt;

/**
 * UniqueIDManager interface
 * version 20160104
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
interface IUniqueIDManager
{
	/**
	 * checks whether this ID is already registered
	 */
	public function isIDRegistered($id);
	
	/**
	 * register ID
	 */
	public function registerID($id);
}