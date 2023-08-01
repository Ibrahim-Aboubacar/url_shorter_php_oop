<?php

namespace Models;

class  Link extends Model
{
    private string $name;
    private string $original_link;
    private string $state;
    private int $visite = 0;
    private User $user;
    protected $columns = ['name', 'original_link', 'state', 'visite'];

    public function init($id)
    {
        /**
         *  @var Link $link
         */
        $link = $this->find($id);
        if ($link) {
            $this->setId($link->id);
            $this->setName($link->name);
            $this->setOriginal_link($link->original_link);
            $this->setState($link->state);
            $this->setVisite($link->visite);
            $this->setUser(new User($link->user));
            $this->initialized = true;
        }


        return $this;
    }

    public function belongsToUser(User $user)
    {
        return $this->user->getId() === $user->getId();
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of originalLink
     */
    public function getOriginal_link()
    {
        return $this->original_link;
    }

    /**
     * Set the value of originalLink
     *
     * @return  self
     */
    public function setOriginal_link($original_link)
    {
        $this->original_link = $original_link;

        return $this;
    }

    /**
     * Get the value of state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the value of state
     *
     * @return  self
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getUsersLinks($userId)
    {
        return $this->where('user', $userId);
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of visite
     */
    public function getVisite()
    {
        return $this->visite;
    }

    /**
     * Set the value of visite
     *
     * @return  self
     */
    public function setVisite($visite)
    {
        $this->visite = $visite;

        return $this;
    }

    public function incrementVisite()
    {
        $query = "UPDATE {$this->table} SET visite = (visite + 1) WHERE id = ?";

        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->execute([$this->getId()]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
}
