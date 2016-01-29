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
 * Test case for class \Piramidex\Msvariants\Domain\Model\VariantsAttributes.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Alfredo A. Villalba Castro <piramidex@gmail.com>
 */
class VariantsAttributesTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \Piramidex\Msvariants\Domain\Model\VariantsAttributes
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \Piramidex\Msvariants\Domain\Model\VariantsAttributes();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getVariantIdReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getVariantId()
		);
	}

	/**
	 * @test
	 */
	public function setVariantIdForIntegerSetsVariantId() {
		$this->subject->setVariantId(12);

		$this->assertAttributeEquals(
			12,
			'variantId',
			$this->subject
		);
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
	public function getAttributeIdReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getAttributeId()
		);
	}

	/**
	 * @test
	 */
	public function setAttributeIdForIntegerSetsAttributeId() {
		$this->subject->setAttributeId(12);

		$this->assertAttributeEquals(
			12,
			'attributeId',
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
}
