<?php

namespace Models;

use DateInterval;
use DateTime;
use Source\Constant;

class User extends Model
{
    public string $username = '';
    public string $email  = '';
    public string $password = '';
    public string|null $deleted_at  = null;
    public string|null $delete_comment  = '';
    protected $columns = ['id', 'username', 'email', 'password', 'deleted_at', 'delete_comment'];

    // public function __construct()
    // {
    // }

    public function findFromEmail($email)
    {
        return $this->where('email = :email')->setParam('email', $email)->fetch();
    }

    public function init($idOrEmail)
    {
        if (preg_match('/^[0-9]+$/', $idOrEmail)) {
            $user = $this->find($idOrEmail);
        } else {
            $user = $this->findFromEmail($idOrEmail);
        }

        if ($user) {
            /**
             * @var User $user 
             */
            $this->setId($user->id);
            $this->setUsername($user->username);
            $this->setEmail($user->email);
            $this->setPassword($user->password);
            $this->setDeleted_at($user->deleted_at);
            $this->setDelete_comment($user->delete_comment);
            $this->initialized = true;
        }
        return $this;
    }

    public function log()
    {
        $_SESSION['auth'] = [
            'id' => $this->getId(),
            'username' => ucfirst($this->getUsername()),
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
    }

    public function checkValidity(): bool
    {
        if ($this->deleted_at === null) {
            return true;
        }

        $date_delete = new DateTime($this->deleted_at);
        $date_delete->add(new DateInterval('P' . Constant::NBR_MOIS_DELETE_ACCOUNT * 30 . 'D'));
        return $date_delete <= new DateTime();
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
    protected function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of deleted_at
     */
    public function getDeleted_at()
    {
        return $this->deleted_at ?? null;
    }

    /**
     * Set the value of deleted_at
     *
     * @return  self
     */
    public function setDeleted_at($deleted_at)
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    /**
     * Get the value of delete_comment
     */
    public function getDelete_comment()
    {
        return $this->delete_comment ?? null;
    }

    /**
     * Set the value of delete_comment
     *
     * @return  self
     */
    public function setDelete_comment($delete_comment)
    {
        $this->delete_comment = $delete_comment;

        return $this;
    }
}
