<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CandidatureAcceptedEvent extends Event
{
    private string $candidateFirstname;
    private string $candidateLastname;
    private string $candidateCurriculumVitae;
    private string $recruiterEmail;


    public function __construct(string $candidateFirstname,
                                string $candidateLastname,
                                string $candidateCurriculumVitae,
                                string $recruiterEmail)
    {
        $this->candidateFirstname = $candidateFirstname;
        $this->candidateLastname = $candidateLastname;
        $this->candidateCurriculumVitae = $candidateCurriculumVitae;
        $this->recruiterEmail = $recruiterEmail;
    }

    /**
     * @return string
     */
    public function getCandidateFirstname(): string
    {
        return $this->candidateFirstname;
    }

    /**
     * @return string
     */
    public function getCandidateLastname(): string
    {
        return $this->candidateLastname;
    }

    /**
     * @return string
     */
    public function getCandidateCurriculumVitae(): string
    {
        return $this->candidateCurriculumVitae;
    }

    /**
     * @return string
     */
    public function getRecruiterEmail(): string
    {
        return $this->recruiterEmail;
    }

}