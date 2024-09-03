<?php

namespace Redmine\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\Common\Collections\ArrayCollection;

#[Entity]
#[Table('projects')]
class Project
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[ManyToOne(targetEntity: Type::class)]
    #[JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private Type $type;
    #[Column]
    private string $description;

    #[OneToMany(targetEntity: ProjectDeveloper::class, mappedBy: 'project')]
    private $projectDevelopers;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setTypeId(Type $type): void
    {
        $this->type = $type;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getProjectDevelopers(): ArrayCollection
    {
        return $this->projectDevelopers;
    }

    public function addProjectDeveloper(ProjectDeveloper $projectDeveloper): void
    {
        $this->projectDevelopers->add($projectDeveloper);
    }

    public function removeProjectDeveloper(ProjectDeveloper $projectDeveloper): void
    {
        $this->projectDevelopers->removeElement($projectDeveloper);
    }

    public function __construct() {
        $this->projectDevelopers = new ArrayCollection();
    }

    public function expose() {
        //return get_object_vars($this);
        $array=[
            "id"=>$this->id,
            "name"=>$this->name,
            "type"=>$this->type->expose(),
            "description"=>$this->description
        ];
        return $array;
    }
}
?>