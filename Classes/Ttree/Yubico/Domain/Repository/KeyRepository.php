<?php
namespace Ttree\Yubico\Domain\Repository;

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
use Ttree\Yubico\Domain\Model\Key;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Doctrine\Repository;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Flow\Security\Account;

/**
 * Key Repository
 *
 * @Flow\Scope("singleton")
 */
class KeyRepository extends Repository {

	/**
	 * @param Account $account
	 * @return QueryResultInterface
	 */
	public function findByAccount(Account $account) {
		$query = $this->createQuery();

		$query->matching($query->equals('account', $account));

		return $query->execute();
	}

	/**
	 * @param Account $account
	 * @param string $publicId
	 * @return Key
	 */
	public function findOneByAccountAndPublicId(Account $account, $publicId) {
		$query = $this->createQuery();

		$query->matching($query->logicalAnd(
			$query->equals('account', $account),
			$query->equals('publicId', $publicId)
		));

		return $query->execute()->getFirst();
	}

}