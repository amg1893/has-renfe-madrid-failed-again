<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LatestRepository")
 */
class Latest extends AbstractEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $last_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date_tweet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastId(): ?int
    {
        return $this->last_id;
    }

    public function setLastId(int $last_id): self
    {
        $this->last_id = $last_id;

        return $this;
    }

    public function getDateTweet(): ?string
    {
        return $this->date_tweet;
    }

    public function setDateTweet(string $date_tweet): self
    {
        $this->date_tweet = $date_tweet;

        return $this;
    }
}
