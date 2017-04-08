<?php
namespace Ttree\Yubico\Domain\Repository;

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
     * @todo use PublicId to transport the key id
	 */
	public function findOneByAccountAndPublicId(Account $account, $publicId) {
		$query = $this->createQuery();

		$query->matching($query->logicalAnd(
			$query->equals('account', $account),
			$query->equals('publicId', (string)$publicId)
		));

		return $query->execute()->getFirst();
	}

}
