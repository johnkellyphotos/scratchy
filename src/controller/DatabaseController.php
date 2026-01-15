<?php

namespace controller;

use core\Controller;
use core\Schema;
use core\SchemaComparator;
use model\UserModel;
use Scratchy\component\PageContent;

use Scratchy\component\SmartTable;
use Scratchy\elements\button;
use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\elements\form;
use Scratchy\elements\input;
use Scratchy\elements\h1;

use Scratchy\elements\li;
use Scratchy\elements\ul;
use Throwable;
use view\DatabaseController\indexView;

/** @noinspection PhpUnused */
class DatabaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /** @noinspection PhpUnused */
    public function viewUsers(): ?Element
    {
        $users = UserModel::findAll();
        $table = new SmartTable($users);
        return new PageContent($table);
    }

    /**
     * @throws Throwable
     */
    /** @noinspection PhpUnused */
    public function index(): ?Element
    {
        return new indexView($this->data->post('execute_build'));
    }
}