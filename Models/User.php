<?php

namespace Models;

class User extends Model
{
    private string $username;
    private string $email;
    private string $password;
    protected $columns = ['username', 'email', 'password'];

    public function findFromEmail($email)
    {

        $table = $this->where('email', $email);
        if (count($table)) return $table[0];

        return [];
    }

    public function init($idOrEmail)
    {
        if (preg_match('/^[0-9]+$/', $idOrEmail)) {
            $user = $this->find($idOrEmail);
        } else {
            $user = $this->findFromEmail($idOrEmail);
        }

        if ($user) {
            $this->setId($user->id);
            $this->setUsername($user->username);
            $this->setEmail($user->email);
            $this->setPassword($user->password);
            $this->initialized = true;
        }
        return $this;
    }

    public function log()
    {
        $_SESSION['auth'] = [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
        ];
    }

    public static function hash_password($password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify_password($password): bool
    {
        return password_verify($password, $this->password);
        // return $this->getPassword() === $password;
    }

    /**
     * Get the value of username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of Id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}