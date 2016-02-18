<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ChallengeLimit
 *
 * @ORM\Table(name="challenge_limit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChallengeLimitRepository")
 */
class ChallengeLimit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="begin", type="integer")
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champs"
     * )
     */
    private $begin;

    /**
     * @var int
     *
     * @ORM\Column(name="end", type="integer")
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champs"
     * )
     */
    private $end;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champs"
     * )
     */
    private $points;

    /**
     * @ORM\ManyToOne(targetEntity="Challenge", inversedBy="limits")
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     */
    private $challenge;

    /**
     * @ORM\ManyToMany(targetEntity="ChallengeAward", inversedBy="limits")
     * @ORM\JoinTable(name="limits_awards")
     */
    private $awards;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set begin
     *
     * @param integer $begin
     * @return ChallengeLimit
     */
    public function setBegin($begin)
    {
        $this->begin = $begin;

        return $this;
    }

    /**
     * Get begin
     *
     * @return integer 
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * Set end
     *
     * @param integer $end
     * @return ChallengeLimit
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return integer 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return ChallengeLimit
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set challenge
     *
     * @param \AppBundle\Entity\Challenge $challenge
     * @return ChallengeLimit
     */
    public function setChallenge(\AppBundle\Entity\Challenge $challenge = null)
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * Get challenge
     *
     * @return \AppBundle\Entity\Challenge 
     */
    public function getChallenge()
    {
        return $this->challenge;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->awards = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add awards
     *
     * @param \AppBundle\Entity\ChallengeAward $awards
     * @return ChallengeLimit
     */
    public function addAward(\AppBundle\Entity\ChallengeAward $awards)
    {
        $this->awards[] = $awards;

        return $this;
    }

    /**
     * Remove awards
     *
     * @param \AppBundle\Entity\ChallengeAward $awards
     */
    public function removeAward(\AppBundle\Entity\ChallengeAward $awards)
    {
        $this->awards->removeElement($awards);
    }

    /**
     * Get awards
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAwards()
    {
        return $this->awards;
    }
}
