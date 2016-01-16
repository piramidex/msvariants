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
 * Variants
 */
class Variants extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * productId
	 *
	 * @var integer
	 * @validate NotEmpty
	 */
	protected $productId = 0;

	/**
	 * productAttributeId
	 *
	 * @var integer
	 * @validate NotEmpty
	 */
	protected $productAttributeId = 0;

	/**
	 * optionId
	 *
	 * @var integer
	 * @validate NotEmpty
	 */
	protected $optionId = 0;

	/**
	 * optionValueId
	 *
	 * @var integer
	 * @validate NotEmpty
	 */
	protected $optionValueId = 0;

	/**
	 * variantPrice
	 *
	 * @var float
	 * @validate NotEmpty
	 */
	protected $variantPrice = 0.0;

	/**
	 * variantStock
	 *
	 * @var integer
	 * @validate NotEmpty
	 */
	protected $variantStock = 0;

	/**
	 * variantSku
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $variantSku = '';

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
	 * Returns the productAttributeId
	 *
	 * @return integer $productAttributeId
	 */
	public function getProductAttributeId() {
		return $this->productAttributeId;
	}

	/**
	 * Sets the productAttributeId
	 *
	 * @param integer $productAttributeId
	 * @return void
	 */
	public function setProductAttributeId($productAttributeId) {
		$this->productAttributeId = $productAttributeId;
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
	 * Returns the variantPrice
	 *
	 * @return float $variantPrice
	 */
	public function getVariantPrice() {
		return $this->variantPrice;
	}

	/**
	 * Sets the variantPrice
	 *
	 * @param float $variantPrice
	 * @return void
	 */
	public function setVariantPrice($variantPrice) {
		$this->variantPrice = $variantPrice;
	}

	/**
	 * Returns the variantStock
	 *
	 * @return integer $variantStock
	 */
	public function getVariantStock() {
		return $this->variantStock;
	}

	/**
	 * Sets the variantStock
	 *
	 * @param integer $variantStock
	 * @return void
	 */
	public function setVariantStock($variantStock) {
		$this->variantStock = $variantStock;
	}

	/**
	 * Returns the variantSku
	 *
	 * @return string $variantSku
	 */
	public function getVariantSku() {
		return $this->variantSku;
	}

	/**
	 * Sets the variantSku
	 *
	 * @param string $variantSku
	 * @return void
	 */
	public function setVariantSku($variantSku) {
		$this->variantSku = $variantSku;
	}

}