<?php

namespace AppVerk\Components\Model;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface, \Serializable
{
    /**
     * Default role for every user
     */
    const ROLE_DEFAULT = 'ROLE_USER';

    /**
     * Main admin role
     */
    const ROLE_MASTER = 'ROLE_MASTER';

    /**
     * Use that role to manage client hidden methods
     */
    const ROLE_DEVELOPER = 'ROLE_DEVELOPER';

    /**
     *  resetting token valid 2 days
     */
    const TOKEN_TTL = 172800;

    /**
     * @return mixed
     */
    public function getId();

    public function __construct();

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @param string $username
     */
    public function setUsername($username);

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName);

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @param string $lastName
     */
    public function setLastName($lastName);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @param string $password
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getSalt();

    /**
     * @param string $salt
     */
    public function setSalt($salt);

    /**
     * @return mixed
     */
    public function getRoles();

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled);

    /**
     * @return mixed
     */
    public function getPasswordRequestedAt();

    /**
     * @param mixed $passwordRequestedAt
     */
    public function setPasswordRequestedAt($passwordRequestedAt);

    /**
     * @return mixed
     */
    public function getPasswordRequestToken();

    /**
     * @param mixed $passwordRequestToken
     */
    public function setPasswordRequestToken($passwordRequestToken);

    /**
     * @return mixed
     */
    public function getPhone();

    /**
     * @param mixed $phone
     */
    public function setPhone($phone);

    public function eraseCredentials();

    public function isPasswordRequestNonExpired();

    public function hasRole($role);

    public function addRole($newRole = null);

    public function removeRole($role);

    public function __toString();
}
