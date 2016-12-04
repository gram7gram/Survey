<?php

namespace Gram\UserBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class Individual
{
    /**
     * @var integer
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     * @JMS\SerializedName("firstName")
     */
    private $firstName;

    /**
     * @var string
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     * @JMS\SerializedName("lastName")
     */
    private $lastName;

    /**
     * @var int
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    private $age;

    /**
     * @var User
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    private $user;

    /**
     * @var Address
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    private $address;

    /**
     * @var Contacts
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    private $contacts;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return Contacts
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param Contacts $contacts
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
    }

    public function getFullName()
    {
        return $this->lastName . ' ' . $this->firstName;
    }

}