<?php
namespace Ttree\Yubico\Command;

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
use Ttree\Yubico\Service\OneTimePasswordService;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;

/**
 * Yubico Command Controller
 */
class YubicoCommandController extends CommandController {

	/**
	 * @Flow\InjectConfiguration("api")
	 * @var array
	 */
	protected $apiSettings;

	/**
	 * @Flow\Inject
	 * @var OneTimePasswordService
	 */
	protected $oneTimePasswordService;

	/**
	 * @param string $otp
	 */
	public function testCommand($otp) {
		$this->outputLine($this->oneTimePasswordService->check($otp) ? 'Success' : 'Error');
	}

}