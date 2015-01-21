<?php
namespace Ttree\Yubico\Domain\Model;

/*                                                                        *
 * This script belongs to the Ttree.Yubico package.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Account;

/**
 * Key Account Mapping
 *
 * @Flow\ValueObject
 */
class Key {

	/**
	 * @var string
	 * @ORM\Id
	 * @ORM\Column(length=12)
	 */
	protected $publicId;

	/**
	 * @var Account
	 * @ORM\ManyToOne
	 */
	protected $account;

	/**
	 * @param string $publicId
	 * @param Account $account
	 */
	public function __construct($publicId, Account $account) {
		$this->publicId = $publicId;
		$this->account = $account;
	}

	/**
	 * @param string $publicId
	 * @return boolean
	 */
	public function matchPublicId($publicId) {
		return $publicId === $this->publicId;
	}

	/**
	 * @return string
	 */
	public function getPublicId() {
		return $this->publicId;
	}

	/**
	 * @return Account
	 */
	public function getAccount() {
		return $this->account;
	}

}