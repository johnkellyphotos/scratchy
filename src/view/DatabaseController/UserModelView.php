<?php

namespace view\DatabaseController;

use model\UserModel;
use core\RowActionType;
use Scratchy\component\PageContent;
use Scratchy\component\SmartTable;
use Scratchy\elements\h1;
use Scratchy\elements\p;

class UserModelView extends PageContent
{
    public function __construct()
    {
        parent::__construct();

        $this->append(new h1(content: 'User model', classes: ['primary-color']));
        $this->append(new p(content: 'Rows currently stored in the users table.'));

        $users = UserModel::findAll();
        if (count($users) === 0) {
            $this->append(new p(content: 'No users found.'));
            return;
        }

        $this->append(new SmartTable($users, [RowActionType::VIEW, RowActionType::EDIT]));
    }
}
