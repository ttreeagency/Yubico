<?php
namespace Ttree\Yubico\Service;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\AccountRepository;
use Neos\Neos\Domain\Service\UserService;
use Ttree\Yubico\Domain\Model\Key;
use Ttree\Yubico\Domain\Model\PublicId;
use Ttree\Yubico\Domain\Repository\KeyRepository;
use Ttree\Yubico\Exception;

/**
 * Attach key to local account
 *
 * @Flow\Scope("singleton")
 */
class KeyRegistrationService {

    /**
     * @var AccountRepository
     * @Flow\Inject
     */
    protected $accountRepository;

    /**
     * @var KeyRepository
     * @Flow\Inject
     */
    protected $keyRepository;

    /**
     * @param PublicId $id
     * @param string $username
     * @param string $provider
     * @return PublicId
     * @throws Exception
     */
	public function register(PublicId $id, string $username, string $provider): PublicId
    {
        $account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, $provider);
        if ($account === null) {
            throw new Exception('Account not found', 1491642634);
        }
        $key = $this->keyRepository->findOneByAccountAndPublicId($account, (string)$id);
        if ($key instanceof Key) {
            throw new Exception('Key with the current id exists', 1491643554);
        }

        $key = new Key((string)$id, $account);
        $this->keyRepository->add($key);

        return $id;
    }

    /**
     * @param PublicId $id
     * @param string $username
     * @param string $provider
     * @return PublicId
     * @throws Exception
     */
	public function unregister(PublicId $id, string $username, string $provider): PublicId
    {
        $account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, $provider);
        if ($account === null) {
            throw new Exception('Account not found', 1491642635);
        }
        $key = $this->keyRepository->findOneByAccountAndPublicId($account, (string)$id);
        if (!$key instanceof Key) {
            throw new Exception('Key with the current id does not exists', 1491643555);
        }

        $this->keyRepository->remove($key);

        return $id;
    }

}
