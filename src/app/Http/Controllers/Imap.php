<?php

namespace App\Http\Controllers;

use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\Folder;
use Webklex\PHPIMAP\Message;
use Webklex\PHPIMAP\Support\MessageCollection;

class Imap extends Controller
{

    /**
     * @var \Webklex\PHPIMAP\Client
     */
    private $client;

    /**
     * @var \Webklex\PHPIMAP\Query\WhereQuery
     */
    private $query;

    /**
     * @var MessageCollection $messages
     */
    private $messages;

    public function __construct()
    {
        $this->client = Client::account('default');
        $this->client->connect();
        $folder = $this->client->getFolderByName('INBOX');
        if (!is_null($folder)) {
            $this->query = $folder->query();
            $this->query->leaveUnread();
            $this->query->unseen();
        } else {
            throw new \Exception('Error get inbox folder');
        }
    }

    /** Установить лимит
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $limit): Imap
    {
        $this->query->setLimit($limit);
        return $this;
    }

    /** фильтровать письма по отправителю. Вызывается после get()
     * @param string $from
     * @return MessageCollection
     */
    public function filterEmailByFrom(string $from): MessageCollection
    {
        $from = mb_strtolower($from);
        $this->messages = $this->messages->filter(function (Message $message) use ($from) {
            $fromInMessage = mb_strtolower($message->getFrom()->toString());
            if (mb_strpos($fromInMessage, $from) !== false) {
                return $message;
            }
        });

        return $this->messages;
    }

    /** фильтровать письма по подстроке. Вызывается после get()
     * @param string $text
     * @return MessageCollection
     */
    public function filterEmailByTitle(string $text): MessageCollection
    {
        $text = mb_strtolower($text);
        $this->messages = $this->messages->filter(function (Message $message) use ($text) {
            $textInMessage = mb_strtolower($message->getSubject()->toString());
            if (mb_strpos($textInMessage, $text) !== false) {
                return $message;
            }
        });

        return $this->messages;
    }

    /** фильтровать по подстроке в теле письма. Вызывается после get()
     * @param string $text
     * @return MessageCollection
     */
    public function filterEmailByBody(string $text): MessageCollection
    {
        $text = mb_strtolower($text);
        $this->messages = $this->messages->filter(function (Message $message) use ($text) {
            $textInMessage = mb_strtolower($message->getTextBody());
            if (mb_strpos($textInMessage, $text) !== false) {
                return $message;
            }
        });

        return $this->messages;
    }

    public function filterEmailByRegex(string $regex)
    {
        $this->messages = $this->messages->filter(function (Message $message) use ($regex) {
            $textInMessage = mb_strtolower($message->getTextBody());
            if (mb_strpos($textInMessage, $text) !== false) {
                return $message;
            }
        });

        return $this->messages;
    }


    /** Получить письма
     * @return $this
     * @throws \Webklex\PHPIMAP\Exceptions\GetMessagesFailedException
     */
    public function get(): Imap
    {
        $this->messages = $this->query->get();
        return $this;
    }

    /** Получить сообщения
     * @return MessageCollection
     */
    public function getMessages(): MessageCollection
    {
        return $this->messages;
    }
}
