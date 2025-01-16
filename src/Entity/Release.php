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

    #[ORM\OneToMany(mappedBy: 'release', targetEntity: Deployment::class)]
    private Collection $deployments;

    #[ORM\OneToMany(mappedBy: 'release', targetEntity: ArtifactRelease::class, cascade: ['persist', 'remove'])]
    private Collection $artifactReleases;

    public function __construct()
    {
        $this->artifactReleases = new ArrayCollection();
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
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
     * @return Collection|ArtifactRelease[]
     */
    public function getArtifactReleases(): Collection
    {
        return $this->artifactReleases;
    }

    public function addArtifactRelease(ArtifactRelease $artifactRelease): self
    {
        if (!$this->artifactReleases->contains($artifactRelease)) {
            $this->artifactReleases[] = $artifactRelease;
            $artifactRelease->setRelease($this);
        }

        return $this;
    }

    public function removeArtifactRelease(ArtifactRelease $artifactRelease): self
    {
        if ($this->artifactReleases->removeElement($artifactRelease)) {
            // set the owning side to null (unless already changed)
            if ($artifactRelease->getRelease() === $this) {
                $artifactRelease->setRelease(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
