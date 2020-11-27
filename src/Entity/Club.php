<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClubRepository::class)
 */
class Club
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $REF;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Creation_Date;

    public function getREF(): ?int
    {
        return $this->REF;
    }

    public function getCreationDate(): ?string
    {
        return $this->Creation_Date;
    }

    public function setCreationDate(string $Creation_Date): self
    {
        $this->Creation_Date = $Creation_Date;

        return $this;
    }
}
