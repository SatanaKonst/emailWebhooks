<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\Query\WhereQuery;
use Webklex\PHPIMAP\Support\FolderCollection;
use Webklex\PHPIMAP\Folder;

class Imap extends Controller
{
    public function getEmails()
    {
        $client = Client::account('default');

        //Connect to the IMAP Server
        $client->connect();

        /** Получим все не прочитанные сообщения из папки входящих
         * @var Folder $folder
         */
        $folder = $client->getFolderByName('INBOX');
        $query = $folder->query();
        $query->leaveUnread();
        $query->unseen();
        $query->setFetchOrderDesc();
        dump($query->get());

    }
}
