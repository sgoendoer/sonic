<?php namespace sgoendoer\Sonic\Identity;

/**
 * Interface for local SocialRecord Caching
 * version 20160104
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
interface ISocialRecordCaching
{
	/**
	 * retrieves a SocialRecord from the local Cache
	 */
	public function getSocialRecordFromCache($gid);
}

?>