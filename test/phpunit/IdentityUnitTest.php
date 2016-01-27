<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\UOID;

class IdentityUnitTest extends PHPUnit_Framework_TestCase
{
	public function testGID()
	{
		$key = "-----BEGIN PUBLIC KEY-----MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAnDOhWIumc12Cf4O1AqAnnv/vCbsgoSqAhMwtvl+7Yjb+aPwuT+EoKN2mGNZ1GMrKZrqHNzkhzJCyLGCo/Zg4V95Xza4f8QxRUH/mOMp132VjvUKlnRNMEqE5hv85mtG5D4dTpkfu4wxxhCfND9bG3GmIoTMYjVGjm0947Zy+VWH1TI4dPVYTvDlwSEsbT5uXQySLBx2XkThynp+e+LGSsmga46LVkt5JUAjIgEstWXaMSJrofGenizw+Yf9tcjgYVDaYsWJVFO24pLkQkVNVt33zZREHECgVObWSJIRX2f/DMrC6pWbNgPdmwodI4dqezg+MvfZ+x+tLFtaquZBx0YH+45wpqy1txgxYUEPUJEsKy+0VxRZTu28j6mKpINQFw0OGMTtcQmZgvdf+JnJOKy0jFjO/CI3kod7SwW3cmTXSfp7KLbHN6BF4hrSpAhro3/2Pixa5LTIDhN1B1oNghbfQ6vXH9Ge8ZAS0G1q3jn/3zicoN+hCn3B8Bxx02Ere2laytnUCNz2HT+DlEtji4gFZEvou/TjjCrwKtTR0XTyUSiNkkG0xFSR0p+ghImz593t128r5I8iWRYCreQq2Z5a0PgcdC/BpdjPvpW2NGWuP4BRPJXO8ddsroi+kSjSqVZDKmtnP5HzpJxJVI/Clich40yaUM6nGVMSQAljGvB0CAwEAAQ==-----END PUBLIC KEY-----";
		
		$salt = "0b9357af0b74e6a4";
		
		$gid1 = '3Z51OUSP4UPXTEOOKSDXVZQLMYGNJQVY0SKYUKKGVT8QWD78T5';
		$gid2 = '2Z51OUSP4UPXTEOOKSDXVZQLMYGNJQVY0SKYUKKGVT8QWD78T5';
		$gid3 = '2Z51OUSP4UPXTEOOKSDXVZQLMYGNJQVY0';
		$gid4 = '2-Z51OUSP4UPXTEOOKSDXVZQLMYGNJQVY0SKYUKKGVT8QWD78T';
		
		$this->assertEquals($gid1, GID::createGID($key, $salt));
		$this->assertEquals(true, GID::isValid($gid1));
		$this->assertEquals(true, GID::verifyGID($key, $salt, $gid1));
		
		$this->assertNotEquals($gid2, GID::createGID($key, $salt));
		$this->assertTrue(GID::isValid($gid2));
		$this->assertFalse(GID::verifyGID($key, $salt, $gid2));
		
		$this->assertFalse(GID::isValid($gid3));
		$this->assertFalse(GID::isValid($gid4));
	}
	
	public function testUOID()
	{
		$uoid1 = '3Z51OUSP4UPXTEOOKSDXVZQLMYGNJQVY0SKYUKKGVT8QWD78T5:0b9357af0b74e6a4'; // ok
		$uoid2 = '2Z51OUSP4UPXTEOOKSDXVZQLMYGNJQVY0:0b9357af0b74e6a4'; // invalid GID
		$uoid3 = '2-Z51OUSP4UPXTEOOKSDXVZQLMYGNJQVY0SKYUKKGVT8QWD78T:0b9357af0b74e6a4'; // invalid GID
		$uoid4 = '3Z51OUSP4UPXTEOOKSDXVZQLMYGNJQVY0SKYUKKGVT8QWD78T5:0b94357af0b74e6a4'; // invalid id
		$uoid5 = '3Z51OUSP4UPXTEOOKSDXVZQLMYGNJQVY0SKYUKKGVT8QWD78T5:0b94357_0b74e6a4'; // invalid id
		
		$this->assertTrue(UOID::isValid($uoid1));
		$this->assertFalse(UOID::isValid($uoid2));
		$this->assertFalse(UOID::isValid($uoid3));
		$this->assertFalse(UOID::isValid($uoid4));
		$this->assertFalse(UOID::isValid($uoid5));
	}
}

?>