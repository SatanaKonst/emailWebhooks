<?php

namespace App\Http\Controllers;

use Webklex\IMAP\Facades\Client;
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
     * @param MessageCollection|null $messagesForFiltered
     * @return MessageCollection
     */
    public function filterEmailByFrom(string $from, MessageCollection $messagesForFiltered = null): MessageCollection
    {
        $from = mb_strtolower($from);
        if (is_null($messagesForFiltered)) {
            $messagesForFiltered = $this->messages;
        }
        $messages = $messagesForFiltered->filter(function (Message $message) use ($from) {
            $fromInMessage = mb_strtolower($message->getFrom()->toString());
            if (mb_strpos($fromInMessage, $from) !== false) {
                return $message;
            }
        });

        return $messages;
    }

    /** фильтровать письма по подстроке. Вызывается после get()
     * @param string $text
     * @param MessageCollection|null $messagesForFiltered
     * @return MessageCollection
     */
    public function filterEmailByTitle(string $text, MessageCollection $messagesForFiltered = null): MessageCollection
    {
        $text = mb_strtolower($text);
        if (is_null($messagesForFiltered)) {
            $messagesForFiltered = $this->messages;
        }
        $messages = $messagesForFiltered->filter(function (Message $message) use ($text) {
            $textInMessage = mb_strtolower($message->getSubject()->toString());
            if (mb_strpos($textInMessage, $text) !== false) {
                return $message;
            }
        });

        return $messages;
    }

    /** фильтровать по подстроке в теле письма. Вызывается после get()
     * @param string $text
     * @param MessageCollection|null $messagesForFiltered
     * @return MessageCollection
     */
    public function filterEmailByBody(string $text, MessageCollection $messagesForFiltered = null): MessageCollection
    {
        $text = mb_strtolower($text);
        if (is_null($messagesForFiltered)) {
            $messagesForFiltered = $this->messages;
        }
        $messages = $messagesForFiltered->filter(function (Message $message) use ($text) {
            $textInMessage = mb_strtolower($message->getTextBody());
            if (mb_strpos($textInMessage, $text) !== false) {
                return $message;
            }
        });

        return $messages;
    }

    /** Поиск по регулярному выражению
     * @param string $regex
     * @param MessageCollection|null $messagesForFiltered
     * @return MessageCollection
     */
    public function filterEmailByRegex(string $regex, MessageCollection $messagesForFiltered = null): MessageCollection
    {
        if (is_null($messagesForFiltered)) {
            $messagesForFiltered = $this->messages;
        }
        $messages = $messagesForFiltered->filter(function (Message $message) use ($regex) {
            /**
             * Собираем все данные в 1 переменную и будем по ней искать
             */
            $searchData = [
                mb_strtolower($message->getSubject()->toString()),
                mb_strtolower($message->getFrom()->toString()),
                mb_strtolower($message->getTextBody()),
            ];
            $searchData = implode(' ', $searchData);

            preg_match_all($regex, $searchData, $matches, PREG_SET_ORDER, 0);
            if (is_array($matches) && !empty($matches)) {
                return $message;
            }
        });

        return $messages;
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
