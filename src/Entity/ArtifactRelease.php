<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ArtifactRelease
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Artifact::class, inversedBy: 'artifactReleases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artifact $artifact = null;

    #[ORM\ManyToOne(targetEntity: Release::class, inversedBy: 'artifactReleases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Release $release = null;

    #[ORM\Column(length: 255)]
    private ?string $version = null;

    #[ORM\Column(length: 255)]
    private ?string $buildNum = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $buildDateTime = null;

    #[ORM\Column(length: 255)]
    private ?string $sourceRef = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtifact(): ?Artifact
    {
        return $this->artifact;
    }

    public function setArtifact(?Artifact $artifact): self
    {
        $this->artifact = $artifact;

        return $this;
    }

    public function getRelease(): ?Release
    {
        return $this->release;
    }

    public function setRelease(?Release $release): self
    {
        $this->release = $release;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getBuildNum(): ?string
    {
        return $this->buildNum;
    }

    public function setBuildNum(string $buildNum): self
    {
        $this->buildNum = $buildNum;

        return $this;
    }

    public function getBuildDateTime(): ?\DateTimeInterface
    {
        return $this->buildDateTime;
    }

    public function setBuildDateTime(\DateTimeInterface $buildDateTime): self
    {
        $this->buildDateTime = $buildDateTime;

        return $this;
    }

    public function getSourceRef(): ?string
    {
        return $this->sourceRef;
    }

    public function setSourceRef(string $sourceRef): self
    {
        $this->sourceRef = $sourceRef;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function __toString()
    {
        return $this->artifact->getName();
    }
}
