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
use DateTime;

#[Entity]
#[Table('tasks')]
class Task
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[Column]
    private string $description;

    #[ManyToOne(targetEntity: Project::class)]
    #[JoinColumn(name: 'project_id', referencedColumnName: 'id')]
    private Project $project;

    #[ManyToOne(targetEntity: Manager::class)]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private Manager $user;

    #[Column]
    private DateTime $deadline;
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    public function getUser(): Manager
    {
        return $this->user;
    }

    public function setUser(Manager $user): void
    {
        $this->user = $user;
    }

    public function getDeadline(): DateTime
    {
        return $this->deadline;
    }

    public function setDeadline(DateTime $deadline): void
    {
        $this->deadline = $deadline;
    }

    public function expose() {
        //return get_object_vars($this);
        $array=["id"=>$this->getId(),
        "name"=>$this->getName(),
        "description"=>$this->getDescription(),
        "pid"=>$this->getProject()->getId(),
        "pname"=>$this->getProject()->getName(),
        "userid"=>$this->getUser()->getId(),
        "deadline"=>$this->getDeadline()->format("Y-m-d H:i:s")];
        return $array;
    }
}
?>