<?php

namespace Chamlee\BlogBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

//Les assert peuven etre résolus comme des Column avec un ORM

class Enquiry
{
    /**
    * @Assert\Length(min=2)
    */
    protected $name;

    /**
    * @Assert\Length(min=2)
    */
    protected $email;

    /**
    * @Assert\Length(min=2)
    */
    protected $subject;

    /**
    * @Assert\Length(min=2)
    */
    protected $body;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }
}