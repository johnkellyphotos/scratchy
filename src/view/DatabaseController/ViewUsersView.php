<?php

namespace view\DatabaseController;

use core\RowActionType;
use model\UserModel;
use Scratchy\component\PageContent;
use Scratchy\component\SmartTable;
use Scratchy\component\SubmitButton;
use Scratchy\elements\form;

class ViewUsersView extends PageContent
{
    public function __construct(?bool $formHasBeenSubmitted = false)
    {
        parent::__construct();
        if ($formHasBeenSubmitted) {
            $userModel = new UserModel();
            $userModel->username = 'user' . rand(10000, 99999);
            $userModel->password = hash('sha256', time());
            $userModel->created = date('Y-m-d H:i:s');
            $userModel->create();
        }

        $form = new form(attributes: [
            'method' => 'POST',
            'action' => '/Database/view-users/'
        ]);
        $form->append(new SubmitButton(
            attributes: [
                'name' => 'has_been_submitted',
                'value' => 1
            ],
            content: 'Create new random user'
        ));

        /* @var UserModel[] $users */
        $users = UserModel::findAll();
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

        $this->append(new PageContent($form, $table));
    }
}