<?php

namespace App\Entity;

use App\Repository\ReleaseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ReleaseRepository::class)]
#[ORM\Table(name: '`release`')]
class Release
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $productionDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $qaDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $stageDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mainReleaseTicket = null;

    #[ORM\ManyToMany(targetEntity: Artifact::class, mappedBy: 'releases')]
    private Collection $artifacts;

    #[ORM\OneToMany(mappedBy: 'release', targetEntity: Deployment::class)]
    private Collection $deployments;

    public function __construct()
    {
        $this->artifacts = new ArrayCollection();
        $this->deployments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getProductionDate(): ?\DateTimeInterface
    {
        return $this->productionDate;
    }

    public function setProductionDate(?\DateTimeInterface $productionDate): static
    {
        $this->productionDate = $productionDate;

        return $this;
    }

    public function getQaDate(): ?\DateTimeInterface
    {
        return $this->qaDate;
    }

    public function setQaDate(?\DateTimeInterface $qaDate): static
    {
        $this->qaDate = $qaDate;

        return $this;
    }

    public function getStageDate(): ?\DateTimeInterface
    {
        return $this->stageDate;
    }

    public function setStageDate(?\DateTimeInterface $stageDate): static
    {
        $this->stageDate = $stageDate;

        return $this;
    }

    public function getMainReleaseTicket(): ?string
    {
        return $this->mainReleaseTicket;
    }

    public function setMainReleaseTicket(?string $mainReleaseTicket): static
    {
        $this->mainReleaseTicket = $mainReleaseTicket;

        return $this;
    }

    /**
     * @return Collection|Artifact[]
     */
    public function getArtifacts(): Collection
    {
        return $this->artifacts;
    }

    public function addArtifact(Artifact $artifact): self
    {
        if (!$this->artifacts->contains($artifact)) {
            $this->artifacts[] = $artifact;
            $artifact->addRelease($this);
        }

        return $this;
    }

    public function removeArtifact(Artifact $artifact): self
    {
        if ($this->artifacts->removeElement($artifact)) {
            $artifact->removeRelease($this);
        }

        return $this;
    }

      /**
     * @return Collection|Deployment[]
     */
    public function getDeployments(): Collection
    {
        return $this->deployments;
    }

    public function addDeployment(Deployment $deployment): self
    {
        if (!$this->deployments->contains($deployment)) {
            $this->deployments[] = $deployment;
            $deployment->setRelease($this);
        }

        return $this;
    }

    public function removeDeployment(Deployment $deployment): self
    {
        if ($this->deployments->removeElement($deployment)) {
            // set the owning side to null (unless already changed)
            if ($deployment->getRelease() === $this) {
                $deployment->setRelease(null);
            }
        }

        return $this;
    }
}
