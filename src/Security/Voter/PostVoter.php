<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;





class PostVoter extends Voter
{
    public function __construct(
        private Security $security
    ){
    }




    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [Post::EDIT])&& $subject instanceof Post;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access

        $isAuth = $user instanceof UserInterface;

        if($this->security->isGranted('ROLE_ADMIN')){
            return true;
        }
        


        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case Post::EDIT:
                return $isAuth && (
                    $subject->getAuthor()->getId() === $user->getId()
                );
                // logic to determine if the user can EDIT
                // return true or false
        }

        return false;
    }
}
