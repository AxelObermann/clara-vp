<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getAllUsers(){
        $users = $this->em->getRepository(User::class)->findAll();
        return $users;
    }

    public function getChildUsers($id){
        $users = $this->em->getRepository(User::class)->findBy(array('parentID' => $id));
        return $users;
    }
}