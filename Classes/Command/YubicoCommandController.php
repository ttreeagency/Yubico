<?php
namespace Ttree\Yubico\Command;

use Doctrine\ORM\Mapping as ORM;
use Ttree\Yubico\Domain\Model\PublicId;
use Ttree\Yubico\Exception;
use Ttree\Yubico\Service\KeyRegistrationService;
use Ttree\Yubico\Service\OneTimePasswordService;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;

/**
 * Yubico Command Controller
 */
class YubicoCommandController extends CommandController {

    /**
     * @var OneTimePasswordService
     * @Flow\Inject
     */
    protected $oneTimePasswordService;

    /**
     * @var KeyRegistrationService
     * @Flow\Inject
     */
	protected $keyRegistrationService;

    /**
     * @param string $account
     * @param string $id
     * @param string $provider
     */
	public function registerCommand(string $account, string $id, string $provider = 'Neos.Neos:Backend')
    {
        try {
            $publicId = $this->keyRegistrationService->register(PublicId::createFromOtpToken($id), $account, $provider);
            $this->outputLine('<info>%s (%s)</info>', ['Key registered with success', $publicId]);
        } catch (Exception $exception) {
            $this->outputLine('<error>%s</error>', [$exception->getMessage()]);
            $this->quit(1);
        }
    }

    /**
     * @param string $account
     * @param string $id
     * @param string $provider
     */
    public function unregisterCommand(string $account, string $id, string $provider = 'Neos.Neos:Backend')
    {
        try {
            $publicId = $this->keyRegistrationService->unregister(PublicId::createFromOtpToken($id), $account, $provider);
            $this->outputLine('<info>%s (%s)</info>', ['Key unregistered with success', $publicId]);
        } catch (Exception $exception) {
            $this->outputLine('<error>%s</error>', [$exception->getMessage()]);
            $this->quit(1);
        }
    }

	/**
     * Test OTP password validation
     *
	 * @param string $otp
	 */
	public function testCommand(string $otp) {
		$this->outputLine($this->oneTimePasswordService->check($otp) ? 'Success' : 'Error');
	}

}
