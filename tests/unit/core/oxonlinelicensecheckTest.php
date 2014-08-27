<?php
/**
 * This file is part of OXID eShop Community Edition.
 *
 * OXID eShop Community Edition is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eShop Community Edition is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2014
 * @version   OXID eShop CE
 */

class Unit_Core_oxonlinelicensecheckTest extends OxidTestCase
{

    public function testRequestFormation()
    {
        $oRequest = new oxOnlineLicenseCheckRequest();
        $oRequest->edition = oxRegistry::getConfig()->getEdition();
        $oRequest->version = oxRegistry::getConfig()->getVersion();
        $oRequest->revision = oxRegistry::getConfig()->getRevision();
        $oRequest->shopUrl = oxRegistry::getConfig()->getShopUrl();
        $oRequest->pVersion = '1.0';
        $oRequest->productId = 'eShop';
        $oRequest->keys = new stdClass();
        $oRequest->keys->key = array('validSerial');
        $oRequest->servers = new stdClass();
        $oRequest->servers->server = oxRegistry::getConfig()->getConfigParam('aServersData');

        $oCaller = $this->getMock('oxOnlineLicenseCheckCaller', array('doRequest'), array(), '', false);
        $oCaller->expects($this->once())->method('doRequest')->with($oRequest, 'OLC');

        $oLicenseCheck = new oxOnlineLicenseCheck($oCaller);
        $oLicenseCheck->validate('validSerial');
    }

    /**
     * Test successful license key validation.
     */
    public function testValidationPassed()
    {
        $oResponse = new oxOnlineLicenseCheckResponse();
        $oResponse->code = 0;
        $oResponse->message = 'ACK';

        $oCaller = $this->getMock('oxOnlineLicenseCheckCaller', array('doRequest'), array(), '', false);
        $oCaller->expects($this->any())->method('doRequest')->will($this->returnValue($oResponse));

        $oLicenseCheck = new oxOnlineLicenseCheck($oCaller);

        $this->assertEquals(true, $oLicenseCheck->validate('validSerial'));

        return $oLicenseCheck;
    }

    /**
     * @depends testValidationPassed
     * @param oxOnlineLicenseCheck $oLicenseCheck
     */
    public function testValidationResultOnSuccess($oLicenseCheck)
    {
        $this->assertEquals(true, $oLicenseCheck->getValidationResult());
    }

    /**
     * @depends testValidationPassed
     * @param oxOnlineLicenseCheck $oLicenseCheck
     */
    public function testErrorMessageEmptyOnSuccess($oLicenseCheck)
    {
        $this->assertEquals('', $oLicenseCheck->getErrorMessage());
    }

    /**
     * Test failed license key validation.
     */
    public function testValidationFailed()
    {
        $oResponse = new oxOnlineLicenseCheckResponse();
        $oResponse->code = 1;
        $oResponse->message = 'NACK';

        $oCaller = $this->getMock('oxOnlineLicenseCheckCaller', array('doRequest'), array(), '', false);
        $oCaller->expects($this->any())->method('doRequest')->will($this->returnValue($oResponse));

        $oLicenseCheck = new oxOnlineLicenseCheck($oCaller);

        $this->assertEquals(false, $oLicenseCheck->validate('invalidSerial'));

        return $oLicenseCheck;
    }

    /**
     * @depends testValidationFailed
     * @param oxOnlineLicenseCheck $oLicenseCheck
     */
    public function testValidationResultOnFailure($oLicenseCheck)
    {
        $this->assertEquals(false, $oLicenseCheck->getValidationResult());
    }

    /**
     * @depends testValidationFailed
     * @param oxOnlineLicenseCheck $oLicenseCheck
     */
    public function testErrorMessageSetOnFailure($oLicenseCheck)
    {
        $this->assertEquals(oxRegistry::getLang()->translateString('OLC_ERROR_SERIAL_NOT_VALID'), $oLicenseCheck->getErrorMessage());
    }

    public function testSerialsAreTakenFromConfigWhenNotPassed()
    {
        $oRequest = new oxOnlineLicenseCheckRequest();
        $oRequest->edition = oxRegistry::getConfig()->getEdition();
        $oRequest->version = oxRegistry::getConfig()->getVersion();
        $oRequest->revision = oxRegistry::getConfig()->getRevision();
        $oRequest->shopUrl = oxRegistry::getConfig()->getShopUrl();
        $oRequest->pVersion = '1.0';
        $oRequest->productId = 'eShop';
        $oRequest->keys = new stdClass();
        $oRequest->keys->key = array('key1', 'key2');
        $oRequest->servers = new stdClass();
        $oRequest->servers->server = oxRegistry::getConfig()->getConfigParam('aServersData');

        $this->getConfig()->setConfigParam("aSerials", array('key1', 'key2'));

        $oCaller = $this->getMock('oxOnlineLicenseCheckCaller', array('doRequest'), array(), '', false);
        $oCaller->expects($this->once())->method('doRequest')->with($oRequest, 'OLC');

        $oLicenseCheck = new oxOnlineLicenseCheck($oCaller);
        $oLicenseCheck->validate();
    }

    public function testIsExceptionWhenExceptionWasThrown()
    {
        $oCaller = $this->getMock('oxOnlineLicenseCheckCaller', array('doRequest'), array(), '', false);
        $oCaller->expects($this->any())->method('doRequest')->will($this->throwException(new oxException()));

        $oLicenseCheck = new oxOnlineLicenseCheck($oCaller);
        $oLicenseCheck->validate('validSerial');

        $this->assertEquals(true, $oLicenseCheck->isException());
    }

    public function testLog()
    {
        $oResponse = new oxOnlineLicenseCheckResponse();
        $oResponse->code = 0;
        $oResponse->message = 'ACK';

        $oCaller = $this->getMock('oxOnlineLicenseCheckCaller', array('doRequest'), array(), '', false);
        $oCaller->expects($this->any())->method('doRequest')->will($this->returnValue($oResponse));

        $oOlc = new oxOnlineLicenseCheck($oCaller);

        $this->setTime(10);

        $oOlc->validate('validSerial');

        $this->assertEquals(10, oxRegistry::getConfig()->getConfigParam('iOlcSuccess'));
    }
}