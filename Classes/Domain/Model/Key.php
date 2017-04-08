<?php
namespace Ttree\Yubico\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;

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
