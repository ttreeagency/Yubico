<?php
namespace Ttree\Yubico\Service;

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
