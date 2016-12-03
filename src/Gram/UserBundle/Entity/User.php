<?php

namespace Gram\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use JMS\Serializer\Annotation as JMS;

/**
 * NOTE See serialization in Resources/config/serializer/Entity.User.yml
 */
class User extends BaseUser
{

    /**
     * @var boolean
     */
    protected $canSendEmail;

    /**
     * @var Individual
     */
    private $individual;

    public function __construct()
    {
        parent::__construct();
        $this->canSendEmail = true;
    }

    /**
     * @return Individual
     */
    public function getIndividual()
    {
        return $this->individual;
    }

    /**
     * @param Individual $individual
     */
    public function setIndividual(Individual $individual)
    {
        $this->individual = $individual;
    }

    /**
     * @return boolean
     */
    public function canSendEmail()
    {
        return $this->canSendEmail;
    }

    /**
     * @param boolean $canSendEmail
     */
    public function setCanSendEmail($canSendEmail)
    {
        $this->canSendEmail = $canSendEmail;
    }


}