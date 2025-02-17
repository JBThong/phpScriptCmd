<?php


namespace App\DTO;

/**
 * Class UserDTO
 *
 * A Data Transfer Object (DTO) class that represents a user with name, surname, and email.
 * Provides methods to validate and format the data before storing in the database.
 */
class UserDTO {
    /**
     * @var string $name The user's first name.
     */
    private $name;

    /**
     * @var string $surname The user's surname.
     */
    private $surname;

    /**
     * @var string $email The user's email address.
     */
    private $email;

    /**
     * UserDTO constructor.
     *
     * @param string $name   The user's first name.
     * @param string $surname The user's surname.
     * @param string $email  The user's email address.
     */
    public function __construct($name, $surname, $email) {
        $this->setName($name);
        $this->setSurname($surname);
        $this->setEmail($email);
    }

    /**
     * Get the user's first name.
     *
     * @return string The user's first name.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the user's first name. The name will be capitalized.
     *
     * @param string $name The user's first name.
     */
    public function setName($name) {
        $this->name = ucfirst(strtolower($name));
    }

    /**
     * Get the user's surname.
     *
     * @return string The user's surname.
     */
    public function getSurname() {
        return $this->surname;
    }

    /**
     * Set the user's surname. The surname will be capitalized.
     *
     * @param string $surname The user's surname.
     */
    public function setSurname($surname) {
        $this->surname = ucfirst(strtolower($surname));
    }

    /**
     * Get the user's email.
     *
     * @return string The user's email.
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set the user's email. The email will be converted to lowercase.
     *
     * @param string $email The user's email.
     */
    public function setEmail($email) {
        $this->email = strtolower($email);
    }
}
