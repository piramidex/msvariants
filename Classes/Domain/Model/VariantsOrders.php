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
 * VariantsOrders
 */
class VariantsOrders extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * orderId
	 *
	 * @var integer
	 */
	protected $orderId = 0;

	/**
	 * productId
	 *
	 * @var integer
	 */
	protected $productId = 0;

	/**
	 * variantId
	 *
	 * @var integer
	 */
	protected $variantId = 0;

	/**
	 * price
	 *
	 * @var float
	 */
	protected $price = 0.0;

	/**
	 * quantity
	 *
	 * @var integer
	 */
	protected $quantity = 0;

	/**
	 * sku
	 *
	 * @var string
	 */
	protected $sku = '';

	/**
	 * orderProductId
	 *
	 * @var integer
	 */
	protected $orderProductId = 0;

	/**
	 * Returns the orderId
	 *
	 * @return integer $orderId
	 */
	public function getOrderId() {
		return $this->orderId;
	}

	/**
	 * Sets the orderId
	 *
	 * @param integer $orderId
	 * @return void
	 */
	public function setOrderId($orderId) {
		$this->orderId = $orderId;
	}

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

	/**
	 * Returns the price
	 *
	 * @return float $price
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * Sets the price
	 *
	 * @param float $price
	 * @return void
	 */
	public function setPrice($price) {
		$this->price = $price;
	}

	/**
	 * Returns the quantity
	 *
	 * @return integer $quantity
	 */
	public function getQuantity() {
		return $this->quantity;
	}

	/**
	 * Sets the quantity
	 *
	 * @param integer $quantity
	 * @return void
	 */
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	/**
	 * Returns the sku
	 *
	 * @return string $sku
	 */
	public function getSku() {
		return $this->sku;
	}

	/**
	 * Sets the sku
	 *
	 * @param string $sku
	 * @return void
	 */
	public function setSku($sku) {
		$this->sku = $sku;
	}

	/**
	 * Returns the orderProductId
	 *
	 * @return integer $orderProductId
	 */
	public function getOrderProductId() {
		return $this->orderProductId;
	}

	/**
	 * Sets the orderProductId
	 *
	 * @param integer $orderProductId
	 * @return void
	 */
	public function setOrderProductId($orderProductId) {
		$this->orderProductId = $orderProductId;
	}

}