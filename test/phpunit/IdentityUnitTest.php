<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use sgoendoer\Sonic\Identity\GID;

class IdentityUnitTest extends PHPUnit_Framework_TestCase
{
	public function testGID()
	{
		$key = "-----BEGIN PUBLIC KEY-----MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAnDOhWIumc12Cf4O1AqAnnv/vCbsgoSqAhMwtvl+7Yjb+aPwuT+EoKN2mGNZ1GMrKZrqHNzkhzJCyLGCo/Zg4V95Xza4f8QxRUH/mOMp132VjvUKlnRNMEqE5hv85mtG5D4dTpkfu4wxxhCfND9bG3GmIoTMYjVGjm0947Zy+VWH1TI4dPVYTvDlwSEsbT5uXQySLBx2XkThynp+e+LGSsmga46LVkt5JUAjIgEstWXaMSJrofGenizw+Yf9tcjgYVDaYsWJVFO24pLkQkVNVt33zZREHECgVObWSJIRX2f/DMrC6pWbNgPdmwodI4dqezg+MvfZ+x+tLFtaquZBx0YH+45wpqy1txgxYUEPUJEsKy+0VxRZTu28j6mKpINQFw0OGMTtcQmZgvdf+JnJOKy0jFjO/CI3kod7SwW3cmTXSfp7KLbHN6BF4hrSpAhro3/2Pixa5LTIDhN1B1oNghbfQ6vXH9Ge8ZAS0G1q3jn/3zicoN+hCn3B8Bxx02Ere2laytnUCNz2HT+DlEtji4gFZEvou/TjjCrwKtTR0XTyUSiNkkG0xFSR0p+ghImz593t128r5I8iWRYCreQq2Z5a0PgcdC/BpdjPvpW2NGWuP4BRPJXO8ddsroi+kSjSqVZDKmtnP5HzpJxJVI/Clich40yaUM6nGVMSQAljGvB0CAwEAAQ==-----END PUBLIC KEY-----";
		
		$salt = "0b9357af0b74e6a4";
		
		$gid = '3Z51OUSP4UPXTEOOKSDXVZQLMYGNJQVY0SKYUKKGVT8QWD78T5';
		
		$this->assertEquals($gid, GID::createGID($key, $salt));
		$this->assertEquals(true, GID::isValid($key, $salt));
		$this->assertEquals(true, GID::verifyGID($key, $salt, $gid));
	}
}

?>