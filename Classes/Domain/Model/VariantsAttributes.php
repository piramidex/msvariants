<?php
namespace Piramidex\Msvariants\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Alfredo A. Villalba Castro <piramidex@gmail.com>, Viboo Technologies
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 * VariantsAttributes
 */
class VariantsAttributes extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * variantId
	 *
	 * @var integer
	 */
	protected $variantId = 0;

	/**
	 * productId
	 *
	 * @var integer
	 */
	protected $productId = 0;

	/**
	 * attributeId
	 *
	 * @var integer
	 */
	protected $attributeId = 0;

	/**
	 * optionId
	 *
	 * @var integer
	 */
	protected $optionId = 0;

	/**
	 * optionValueId
	 *
	 * @var integer
	 */
	protected $optionValueId = 0;

	/**
	 * Returns the productId
	 *
	 * @return integer $productId
	 */
	public function getProductId() {
		return $this->productId;
	}

	/**
	 * Sets the productId
	 *
	 * @param integer $productId
	 * @return void
	 */
	public function setProductId($productId) {
		$this->productId = $productId;
	}

	/**
	 * Returns the attributeId
	 *
	 * @return integer $attributeId
	 */
	public function getAttributeId() {
		return $this->attributeId;
	}

	/**
	 * Sets the attributeId
	 *
	 * @param integer $attributeId
	 * @return void
	 */
	public function setAttributeId($attributeId) {
		$this->attributeId = $attributeId;
	}

	/**
	 * Returns the optionId
	 *
	 * @return integer $optionId
	 */
	public function getOptionId() {
		return $this->optionId;
	}

	/**
	 * Sets the optionId
	 *
	 * @param integer $optionId
	 * @return void
	 */
	public function setOptionId($optionId) {
		$this->optionId = $optionId;
	}

	/**
	 * Returns the optionValueId
	 *
	 * @return integer $optionValueId
	 */
	public function getOptionValueId() {
		return $this->optionValueId;
	}

	/**
	 * Sets the optionValueId
	 *
	 * @param integer $optionValueId
	 * @return void
	 */
	public function setOptionValueId($optionValueId) {
		$this->optionValueId = $optionValueId;
	}

	/**
	 * __construct
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties
	 * Do not modify this method!
	 * It will be rewritten on each save in the extension builder
	 * You may modify the constructor of this class instead
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		
	}

	/**
	 * Returns the variantId
	 *
	 * @return integer $variantId
	 */
	public function getVariantId() {
		return $this->variantId;
	}

	/**
	 * Sets the variantId
	 *
	 * @param integer $variantId
	 * @return void
	 */
	public function setVariantId($variantId) {
		$this->variantId = $variantId;
	}

}