<?php namespace sgoendoer\Sonic\examples;

use sgoendoer\Sonic\Identity\ISocialRecordCaching;

class SocialRecordCachingExample implements ISocialRecordCaching
{
	public function getSocialRecordFromCache($gid)
	{
		if($gid == '4802C8DE6UZZ5BICQI830A8P8BW3YB5EBPGXWNRH1EP7H838V7')
			return SocialRecordManager::importSocialRecord(file_get_contents(__DIR__ . 'data/SRAlice.json'))['socialRecord'];
		else
			return false;
	}
}

?>