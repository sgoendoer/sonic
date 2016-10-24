<?php namespace sgoendoer\Sonic\Identity;

/**
 * Interface for local SocialRecord Caching
 * version 20160104
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
interface ISocialRecordCaching
{
	/**
	 * retrieves a SocialRecord from the local Cache
	 */
	public function getSocialRecordFromCache($gid);
}

?>