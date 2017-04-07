<?php
namespace Ttree\Yubico\Authentication\Token;

/*                                                                        *
 * This script belongs to the Ttree.Yubico package.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Utility\ObjectAccess;
use Neos\Flow\Security\Authentication\Token\AbstractToken;

/**
 * An authentication token used for simple username and password authentication.
 */
class UsernamePassword extends AbstractToken {

	/**
	 * The username/password credentials
	 * @var array
	 * @Flow\Transient
	 */
	protected $credentials = array('username' => '', 'password' => '', 'token' => '');

	/**
	 * Updates the username, password credentials and the yubikey token from the POST vars, if the POST parameters
	 * are available. Sets the authentication status to REAUTHENTICATION_NEEDED, if credentials have been sent.
	 *
	 * Note: You need to send the username and password in these two POST parameters:
	 *       __authentication[Ttree][Yubico][Authentication][Token][UsernamePassword][username]
	 *   and __authentication[Ttree][Yubico][Authentication][Token][UsernamePassword][password]
	 *   and __authentication[Ttree][Yubico][Authentication][Token][UsernamePassword][token]
	 *
	 * @param ActionRequest $actionRequest The current action request
	 * @return void
	 */
	public function updateCredentials(ActionRequest $actionRequest) {
		$httpRequest = $actionRequest->getHttpRequest();
		if ($httpRequest->getMethod() !== 'POST') {
			return;
		}

		$arguments = $actionRequest->getInternalArgument('__authentication');
		if (!is_array($arguments)) {
			return;
		}
		$username = ObjectAccess::getPropertyPath($arguments, 'Ttree.Yubico.Authentication.Token.UsernamePassword.username');
		$password = ObjectAccess::getPropertyPath($arguments, 'Ttree.Yubico.Authentication.Token.UsernamePassword.password');
		$token = ObjectAccess::getPropertyPath($arguments, 'Ttree.Yubico.Authentication.Token.UsernamePassword.token');

		if (!empty($username) && !empty($password)) {
			$this->credentials['username'] = $username;
			$this->credentials['password'] = $password;
			$this->credentials['token'] = $token;
			$this->setAuthenticationStatus(self::AUTHENTICATION_NEEDED);
		}
	}

	/**
	 * Returns a string representation of the token for logging purposes.
	 *
	 * @return string The username credential
	 */
	public function  __toString() {
		return vsprintf('Username: "%s" with OTP (%s)', array($this->credentials['username'], $this->credentials['token']));
	}

}