<?php
namespace App\Security;

use App\Entity\User as AppUser;
use App\Exception\AccountDeletedException;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserChecker implements UserCheckerInterface
{
    private $session;

    public function __construct(SessionInterface $session){
        $this->session = $session;
    }

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if ($user->getEnabled() == false){
            throw new CustomUserMessageAccountStatusException('Ton compte utilisateur n\'a pas été validé par un administrateur.');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if ($user->getLinkedinUrl() == null){
            $this->session->getFlashBag()->add('warning', 'Aucun compte LinkedIn associé à ce compte. Va éditer ton profil pour en ajouter un.');
        }
    }
}
?>
