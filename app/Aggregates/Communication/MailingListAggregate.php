<?php

namespace App\Aggregates\Communication;

use App\Exceptions\Communication\MailingListException;
use App\StorableEvents\Communication\MailingLists\NewMailingListCreated;
use App\StorableEvents\Communication\MailingLists\UserAddedToMailingList;
use App\StorableEvents\Communication\MailingLists\UserRemovedFromMailingList;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class MailingListAggregate extends AggregateRoot
{
    protected array $users = [];
    protected string|null $concentration = null;
    protected string|null $name = null;

    public function applyNewMailingListCreated(NewMailingListCreated $event)
    {
        $this->name = $event->name;
        $this->concentration = $event->concentration;
    }

    public function createMailingList(string $name, string $concentration) : self
    {
        if(!is_null($this->name))
        {
            throw MailingListException::mailingListAlreadyCreated();
        }

        $this->recordThat(new NewMailingListCreated($this->uuid(), $name, $concentration));

        return $this;
    }

    public function applyUserAddedToMailingList(UserAddedToMailingList $event)
    {
        $this->users[$event->user_id] = $event->email;
    }

    public function applyUserRemovedFromMailingList(UserRemovedFromMailingList $event)
    {
        unset($this->users[$event->user_id]);
    }

    public function addUserToMailingList(string $user_id, string $email) : self
    {
        if(array_key_exists($user_id, $this->users))
        {
            throw MailingListException::userAlreadyInMailingList($email);
        }

        $this->recordThat(new UserAddedToMailingList($this->uuid(), $user_id, $email));

        return $this;
    }

    public function removeUserToMailingList(string $user_id, string $email) : self
    {
        if(!array_key_exists($user_id, $this->users))
        {
            throw MailingListException::userNotInMailingList($email);
        }

        $this->recordThat(new UserRemovedFromMailingList($this->uuid(), $user_id, $email));

        return $this;
    }

    public function isUserInList(string $user_id) : bool
    {
        return array_key_exists($user_id, $this->users);
    }

    public function getUserList() : array
    {
        return $this->users;
    }
}
