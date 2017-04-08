<?php
namespace Ttree\Yubico\Authentication\Provider;

use Ttree\Yubico\Authentication\Token\UsernamePassword;
use Ttree\Yubico\Domain\Model\Key;
use Ttree\Yubico\Domain\Repository\KeyRepository;
use Ttree\Yubico\Service\OneTimePasswordService;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Authentication\Provider;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\Authentication\TokenInterface;
use Neos\Flow\Security\Exception\UnsupportedAuthenticationTokenException;

/**
 * An authentication provider that authenticates
 * Neos\Flow\Security\Authentication\Token\UsernamePassword tokens.
 * The accounts are stored in the Content Repository.
 */
class PersistedUsernamePasswordProvider extends Provider\PersistedUsernamePasswordProvider {

	/**
	 * @Flow\Inject
	 * @var KeyRepository
	 */
	protected $keyRepository;

	/**
	 * @Flow\Inject
	 * @var OneTimePasswordService
	 */
	protected $oneTimePasswordService;

	/**
	 * @Flow\Inject
	 * @var PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * Returns the class names of the tokens this provider can authenticate.
	 *
	 * @return array
	 */
	public function getTokenClassNames() {
		return array(UsernamePassword::class);
	}

	/**
	 * Checks the given token for validity and sets the token authentication status
	 * accordingly (success, wrong credentials or no credentials given).
	 *
	 * @param \Neos\Flow\Security\Authentication\TokenInterface $authenticationToken The token to be authenticated
	 * @return void
	 * @throws \Neos\Flow\Security\Exception\UnsupportedAuthenticationTokenException
	 */
	public function authenticate(TokenInterface $authenticationToken) {
		if (!($authenticationToken instanceof UsernamePassword)) {
			throw new UnsupportedAuthenticationTokenException('This provider cannot authenticate the given token.', 1217339840);
		}

		/** @var $account \Neos\Flow\Security\Account */
		$account = null;
		$credentials = $authenticationToken->getCredentials();

		if (is_array($credentials) && isset($credentials['username'])) {
			$providerName = $this->name;
			$accountRepository = $this->accountRepository;
			$this->securityContext->withoutAuthorizationChecks(function() use ($credentials, $providerName, $accountRepository, &$account) {
				$account = $accountRepository->findActiveByAccountIdentifierAndAuthenticationProviderName($credentials['username'], $providerName);
			});
		}

		if (is_object($account)) {
			$keys = $this->keyRepository->findByAccount($account);
			$hasKey = $keys->count() > 0;

			$currentKey = null;
			$token = trim($credentials['token']);
			$publicId = $token !== '' ? substr($token, 0, 12) : null;
			if ($hasKey === false && $publicId !== null) {
				$currentKey = $this->createKey($publicId, $account);
			} elseif ($hasKey === true && $publicId !== null) {
				$currentKey = $this->keyRepository->findOneByAccountAndPublicId($account, $publicId);
				if ($currentKey === null || !$currentKey->matchPublicId($publicId)) {
					$authenticationToken->setAuthenticationStatus(TokenInterface::WRONG_CREDENTIALS);
					return;
				}
			} elseif ($hasKey === true && $publicId === null) {
				$authenticationToken->setAuthenticationStatus(TokenInterface::WRONG_CREDENTIALS);
				return;
			}

			if ($this->hashService->validatePassword($credentials['password'], $account->getCredentialsSource()) && $this->validateOneTimePassword($token, $currentKey)) {
				$authenticationToken->setAuthenticationStatus(TokenInterface::AUTHENTICATION_SUCCESSFUL);
				$authenticationToken->setAccount($account);
				if ($currentKey instanceof Key && $this->persistenceManager->isNewObject($currentKey)) {
					$this->keyRepository->add($currentKey);
				}
			} else {
				$authenticationToken->setAuthenticationStatus(TokenInterface::WRONG_CREDENTIALS);
			}
		} elseif ($authenticationToken->getAuthenticationStatus() !== TokenInterface::AUTHENTICATION_SUCCESSFUL) {
			$authenticationToken->setAuthenticationStatus(TokenInterface::NO_CREDENTIALS_GIVEN);
		}
	}

	/**
	 * Create the key for the given account
	 *
	 * @param string $publicId
	 * @param Account $account
	 * @return Key
	 */
	protected function createKey($publicId, Account $account) {
		return new Key($publicId, $account);
	}

	/**
	 * Validate One Time Password
	 *
	 * We can skip the validation is the current user doesn't have a valid Key
	 *
	 * @param string $token
	 * @param string $currentKey
	 * @return bool
	 */
	protected function validateOneTimePassword($token, $currentKey) {
		if ($currentKey === null) {
			return true;
		}
		return $this->oneTimePasswordService->check($token);
	}

}
