<?php

namespace view\DatabaseController;

use core\RowActionType;
use lib\UserPresenter;
use model\UserModel;
use Scratchy\component\PageContent;
use Scratchy\component\SmartTable;
use Scratchy\elements\h1;
use Scratchy\elements\p;

class UserModelView extends PageContent
{
    public function __construct()
    {
        parent::__construct();

        $this->append(new h1(classes: ['primary-color'], content: 'User model'));
        $this->append(new p(content: 'Rows currently stored in the users table.'));

        $users = UserModel::findAll();
        if (count($users) === 0) {
            $this->append(new p(content: 'No users found.'));
            return;
        }

        $presenters = UserPresenter::list($users);
        $this->append(new SmartTable($presenters, [RowActionType::VIEW, RowActionType::EDIT], UserModel::class));
    }
}
