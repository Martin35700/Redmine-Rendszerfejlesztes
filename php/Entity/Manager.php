<?php

namespace Redmine\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('managers')]
class Manager
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[Column]
    private string $email;
    #[Column]
    private string $password;

     /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="user")
     */
    private $projects;
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function expose() {
        //return get_object_vars($this);
        $array=[
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'password'=>$this->password
        ];
        return $array;
    }
}
?>