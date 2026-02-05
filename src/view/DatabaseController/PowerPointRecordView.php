<?php

namespace view\DatabaseController;

use core\RowActionType;
use model\PowerPointControlsModel;
use Scratchy\component\PageContent;
use Scratchy\component\SmartTable;

class PowerPointRecordView extends PageContent
{
    public function __construct()
    {
        parent::__construct();

        /* @var PowerPointControlsModel[] $users */
        $users = PowerPointControlsModel::findAll();
        foreach ($users as $user) {
            unset($user->birthday);
            unset($user->lastSignIn);
            unset($user->username);
            unset($user->password);
        }

        $table = new SmartTable($users, [
            RowActionType::VIEW,
            RowActionType::EDIT,
            RowActionType::DELETE,
        ]);

        $this->append(new PageContent($table));
    }
}