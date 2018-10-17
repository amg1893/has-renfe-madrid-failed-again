<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HashtagStatusRepository")
 */
class HashtagStatus extends AbstractEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hashtag;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date_tweet;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $update_time;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true)
     */
    private $status;

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

    public function getHashtag(): ?string
    {
        return $this->hashtag;
    }

    public function setHashtag(string $hashtag): self
    {
        $this->hashtag = $hashtag;

        return $this;
    }

    public function getLastId(): ?string
    {
        return $this->last_id;
    }

    public function setLastId(?string $last_id): self
    {
        $this->last_id = $last_id;

        return $this;
    }

    public function getDateTweet(): ?string
    {
        return $this->date_tweet;
    }

    public function setDateTweet(?string $date_tweet): self
    {
        $this->date_tweet = $date_tweet;

        return $this;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->update_time;
    }

    public function setUpdateTime(?\DateTimeInterface $update_time): self
    {
        $this->update_time = $update_time;

        return $this;
    }

    public function updateTime(): void
    {
        $this->setUpdateTime(new \DateTime());
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
