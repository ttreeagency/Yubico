<?php
namespace Ttree\Yubico\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Ttree\Yubico\Exception;

/**
 * PublicId
 */
class PublicId {

	protected $id;

    protected function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function createFromOtpToken(string $token): PublicId
    {
        $token = trim($token);
        $id = $token !== '' ? substr($token, 0, 12) : null;
        if ($id === null) {
            throw new Exception('Public ID can not be null', 1491642348);
        }
        return new PublicId($id);
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
