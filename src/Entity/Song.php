<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SongRepository::class)
 */
class Song
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $artist;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $album;

    /**
     * @ORM\ManyToMany(targetEntity=Catalog::class, inversedBy="songs")
     */
    private $catalog;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $songFileName;

    public function __construct()
    {
        $this->catalog = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(?string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function setAlbum(?string $album): self
    {
        $this->album = $album;

        return $this;
    }

    /**
     * @return Collection|Catalog[]
     */
    public function getCatalog(): Collection
    {
        return $this->catalog;
    }

    public function addCatalog(Catalog $catalog): self
    {
        if (!$this->catalog->contains($catalog)) {
            $this->catalog[] = $catalog;
        }

        return $this;
    }

    public function removeCatalog(Catalog $catalog): self
    {
        $this->catalog->removeElement($catalog);

        return $this;
    }

    public function getSongFileName(): ?string
    {
        return $this->songFileName;
    }

    public function setSongFileName(string $songFileName): self
    {
        $this->songFileName = $songFileName;

        return $this;
    }
}
