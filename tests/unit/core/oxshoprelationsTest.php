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

require_once realpath(".") . '/unit/OxidTestCase.php';
require_once realpath(".") . '/unit/test_config.inc.php';

/**
 * Testing oxArticle class.
 */
class Unit_Core_oxShopRelationsTest extends OxidTestCase
{

    /**
     * Provides shop ID or list of shops.
     *
     * @return array
     */
    public function _dpTestListOfShops()
    {
        return array(
            array(45, 1),
            array(array(), 0),
            array(array(27), 1),
            array(array(3, 46, 5), 3),
        );
    }

    /**
     * Test set/get database gateway.
     */
    public function testSetGetDbGateway()
    {
        $oShopRelations = new oxShopRelations();

        // assert default gateway
        $this->isInstanceOf('oxShopRelationsDbGateway', $oShopRelations->getDbGateway());

        $oCustomDbGateway = new stdClass();

        $oShopRelations->setDbGateway($oCustomDbGateway);
        $this->assertSame($oCustomDbGateway, $oShopRelations->getDbGateway());
    }

    /**
     * Tests add object to shop or list of shops.
     *
     * @param int|array $aShopIds Shop ID or list of shop IDs.
     *
     * @dataProvider _dpTestListOfShops
     */
    public function testAddObjectToShops($aShopIds)
    {
        $iItemId   = 123;
        $sItemType = 'oxarticles';

        $oItem = new oxBase();
        $oItem->init($sItemType);
        $oItem->setId($iItemId);

        /** @var oxShopRelations|PHPUnit_Framework_MockObject_MockObject $oShopRelations */
        $oShopRelations = $this->getMock('oxShopRelations', array('addItemToShops'));
        $oShopRelations->expects($this->once())->method('addItemToShops')
            ->with($iItemId, $sItemType, $aShopIds)->will($this->returnValue(true));

        $this->assertTrue($oShopRelations->addObjectToShops($oItem, $aShopIds));
    }

    /**
     * Tests remove object from shop or list of shops.
     *
     * @param int|array $aShopIds Shop ID or list of shop IDs.
     *
     * @dataProvider _dpTestListOfShops
     */
    public function testRemoveObjectFromShops($aShopIds)
    {
        $iItemId   = 123;
        $sItemType = 'oxarticles';

        $oItem = new oxBase();
        $oItem->init($sItemType);
        $oItem->setId($iItemId);

        /** @var oxShopRelations|PHPUnit_Framework_MockObject_MockObject $oShopRelations */
        $oShopRelations = $this->getMock('oxShopRelations', array('removeItemFromShops'));
        $oShopRelations->expects($this->once())->method('removeItemFromShops')
            ->with($iItemId, $sItemType, $aShopIds)->will($this->returnValue(true));

        $this->assertTrue($oShopRelations->removeObjectFromShops($oItem, $aShopIds));
    }

    /**
     * Tests add item to shop or list of shops.
     *
     * @param int|array $aShopIds          Shop ID or list of shop IDs.
     * @param int       $iExpectsToProcess Number of shops expected to be processed.
     *
     * @dataProvider _dpTestListOfShops
     */
    public function testAddItemToShops($aShopIds, $iExpectsToProcess)
    {
        $iItemId   = 123;
        $sItemType = 'oxarticles';

        /** @var oxShopRelationsDbGateway|PHPUnit_Framework_MockObject_MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock('oxShopRelationsDbGateway', array('addItemToShop'));
        $oShopRelationsDbGateway->expects($this->exactly($iExpectsToProcess))->method('addItemToShop')
            ->will($this->returnValue(true));

        $oShopRelations = new oxShopRelations();
        $oShopRelations->setDbGateway($oShopRelationsDbGateway);

        $this->assertTrue($oShopRelations->addItemToShops($iItemId, $sItemType, $aShopIds));
    }

    /**
     * Tests remove item from shop or list of shops.
     *
     * @param int|array $aShopIds          Shop ID or list of shop IDs.
     * @param int       $iExpectsToProcess Number of shops expected to be processed.
     *
     * @dataProvider _dpTestListOfShops
     */
    public function testRemoveItemFromShops($aShopIds, $iExpectsToProcess)
    {
        $iItemId   = 123;
        $sItemType = 'oxarticles';

        /** @var oxShopRelationsDbGateway|PHPUnit_Framework_MockObject_MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock('oxShopRelationsDbGateway', array('removeItemFromShop'));
        $oShopRelationsDbGateway->expects($this->exactly($iExpectsToProcess))->method('removeItemFromShop')
            ->will($this->returnValue(true));

        $oShopRelations = new oxShopRelations();
        $oShopRelations->setDbGateway($oShopRelationsDbGateway);

        $this->assertTrue($oShopRelations->removeItemFromShops($iItemId, $sItemType, $aShopIds));
    }

    /**
     * Tests inherit items by type to sub shop(-s) from parent shop.
     *
     * @param int|array $aShopIds          Shop ID or list of shop IDs.
     * @param int       $iExpectsToProcess Number of shops expected to be processed.
     *
     * @dataProvider _dpTestListOfShops
     */
    public function testInheritItemsFromShops($aShopIds, $iExpectsToProcess)
    {
        $iParentShopId = 456;
        $sItemType     = 'oxarticles';

        /** @var oxShopRelationsDbGateway|PHPUnit_Framework_MockObject_MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock('oxShopRelationsDbGateway', array('inheritItemsFromShop'));
        $oShopRelationsDbGateway->expects($this->exactly($iExpectsToProcess))->method('inheritItemsFromShop')
            ->will($this->returnValue(true));

        $oShopRelations = new oxShopRelations();
        $oShopRelations->setDbGateway($oShopRelationsDbGateway);

        $this->assertTrue($oShopRelations->inheritItemsFromShops($iParentShopId, $aShopIds, $sItemType));
    }

    /**
     * Tests remove items by type from sub shop(-s) that were inherited from parent shop.
     *
     * @param int|array $aShopIds          Shop ID or list of shop IDs.
     * @param int       $iExpectsToProcess Number of shops expected to be processed.
     *
     * @dataProvider _dpTestListOfShops
     */
    public function testRemoveInheritedItemsFromShops($aShopIds, $iExpectsToProcess)
    {
        $iParentShopId = 456;
        $sItemType     = 'oxarticles';

        /** @var oxShopRelationsDbGateway|PHPUnit_Framework_MockObject_MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock('oxShopRelationsDbGateway', array('removeInheritedItemsFromShop'));
        $oShopRelationsDbGateway->expects($this->exactly($iExpectsToProcess))->method('removeInheritedItemsFromShop')
            ->will($this->returnValue(true));

        $oShopRelations = new oxShopRelations();
        $oShopRelations->setDbGateway($oShopRelationsDbGateway);

        $this->assertTrue($oShopRelations->removeInheritedItemsFromShops($iParentShopId, $aShopIds, $sItemType));
    }
}