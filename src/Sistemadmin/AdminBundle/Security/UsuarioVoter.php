<?php
/**
 * Created by PhpStorm.
 * User: sasuke
 * Date: 13/4/2018
 * Time: 11:35 AM
 */

namespace Sistemadmin\AdminBundle\Security;
use Sistemadmin\AdminBundle\Entity\Usuario;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class UsuarioVoter extends Voter
{
    private $decisionManager;
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }
        $user = $token->getUser();
        if (!$user instanceof Usuario) {
            return false;
        }
        $usuario = $subject;
        return $this->canEdit($usuario, $user);
    }
    protected function supports($attribute, $subject)
    {
        if (!$subject instanceof Usuario) {
            return false;
        }
        return true;
    }
    private function canEdit(Usuario $usuario, Usuario $user)
    {
      return $user->getId() === $usuario->getId();
    }

}