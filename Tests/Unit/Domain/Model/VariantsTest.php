<?php

namespace Piramidex\Msvariants\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Alfredo A. Villalba Castro <piramidex@gmail.com>, Viboo Technologies
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class \Piramidex\Msvariants\Domain\Model\Variants.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Alfredo A. Villalba Castro <piramidex@gmail.com>
 */
class VariantsTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \Piramidex\Msvariants\Domain\Model\Variants
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \Piramidex\Msvariants\Domain\Model\Variants();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getProductIdReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getProductId()
		);
	}

	/**
	 * @test
	 */
	public function setProductIdForIntegerSetsProductId() {
		$this->subject->setProductId(12);

		$this->assertAttributeEquals(
			12,
			'productId',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getProductAttributeIdReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getProductAttributeId()
		);
	}

	/**
	 * @test
	 */
	public function setProductAttributeIdForIntegerSetsProductAttributeId() {
		$this->subject->setProductAttributeId(12);

		$this->assertAttributeEquals(
			12,
			'productAttributeId',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getOptionIdReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getOptionId()
		);
	}

	/**
	 * @test
	 */
	public function setOptionIdForIntegerSetsOptionId() {
		$this->subject->setOptionId(12);

		$this->assertAttributeEquals(
			12,
			'optionId',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getOptionValueIdReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getOptionValueId()
		);
	}

	/**
	 * @test
	 */
	public function setOptionValueIdForIntegerSetsOptionValueId() {
		$this->subject->setOptionValueId(12);

		$this->assertAttributeEquals(
			12,
			'optionValueId',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getVariantPriceReturnsInitialValueForFloat() {
		$this->assertSame(
			0.0,
			$this->subject->getVariantPrice()
		);
	}

	/**
	 * @test
	 */
	public function setVariantPriceForFloatSetsVariantPrice() {
		$this->subject->setVariantPrice(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'variantPrice',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getVariantStockReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getVariantStock()
		);
	}

	/**
	 * @test
	 */
	public function setVariantStockForIntegerSetsVariantStock() {
		$this->subject->setVariantStock(12);

		$this->assertAttributeEquals(
			12,
			'variantStock',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getVariantSkuReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getVariantSku()
		);
	}

	/**
	 * @test
	 */
	public function setVariantSkuForStringSetsVariantSku() {
		$this->subject->setVariantSku('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'variantSku',
			$this->subject
		);
	}
}
