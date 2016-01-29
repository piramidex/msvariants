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
	 * variantId
	 *
	 * @var integer
	 * @validate NotEmpty
	 */
	protected $variantId = 0;

	/**
	 * productId
	 *
	 * @var integer
	 * @validate NotEmpty
	 */
	protected $productId = 0;

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