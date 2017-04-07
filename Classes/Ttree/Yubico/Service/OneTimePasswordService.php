<?php
namespace Ttree\Yubico\Service;

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
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Log\SystemLoggerInterface;
use Yubikey\Validate;

/**
 * One Time Password (OTP) Service
 *
 * @Flow\Scope("singleton")
 */
class OneTimePasswordService {

	/**
	 * @Flow\InjectConfiguration("api")
	 * @var array
	 */
	protected $apiSettings;

	/**
	 * @Flow\Inject
	 * @var SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * @param string $token
	 * @return boolean
	 */
	public function check($token) {
		$validate = new Validate($this->apiSettings['secrectKey'], $this->apiSettings['clientId']);
		$responses = $validate->check($token);
		return $responses->success();
	}

}