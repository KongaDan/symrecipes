<?php 

namespace App\EntityListener;

use App\Entity\User;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener 
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function prePersist(User $user, PrePersistEventArgs $event): void
    {
        $this->encodePassword($user);
    }

    public function preUpdate(User $user, PreUpdateEventArgs $event): void
    {
        $this->encodePassword($user);
    }

    /**
     * Encode le mot de passe en fonction du plain password.
     */
    private function encodePassword(User $user): void
    {
        if ($user->getPlainPassword() === null) {
            return;
        }

        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                $user->getPlainPassword()
            )
        );
        
    }
    
}
