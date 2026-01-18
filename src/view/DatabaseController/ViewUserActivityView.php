<?php

namespace view\DatabaseController;

use core\RowActionType;
use model\UserActivityModel;
use model\UserModel;
use Scratchy\component\PageContent;
use Scratchy\component\SmartTable;
use Scratchy\component\SubmitButton;
use Scratchy\elements\form;
use Scratchy\elements\p;

class ViewUserActivityView extends PageContent
{
    public function __construct(?bool $formHasBeenSubmitted = false)
    {
        parent::__construct();
        $p = new p();
        if ($formHasBeenSubmitted) {
            $randomUser = UserModel::findOne('1=1 ORDER BY RAND()');
            if (!$randomUser) {
                $p = new p(content: 'Can not create new user activity. Please first add at least one user.');
            } else {
                $UserActivityModel = new UserActivityModel();
                $UserActivityModel->userId = $randomUser->id;
                $UserActivityModel->title = 'Here is a new note!';
                $UserActivityModel->notes = str_repeat('Nonsense!', rand(10, 100));
                $UserActivityModel->create();
            }
        }

        $form = new form(attributes: [
            'method' => 'POST',
            'action' => '/Database/view-user-activity/'
        ]);
        $form->append(new SubmitButton(
            attributes: [
                'name' => 'has_been_submitted',
                'value' => 1
            ],
            content: 'Create new random user activity'
        ));

        /* @var UserModel[] $users */
        $users = UserActivityModel::findAll();
        foreach ($users as $user) {
            unset($user->title);
            unset($user->notes);
            unset($user->userId);
        }

        $table = new SmartTable($users, [
            RowActionType::VIEW,
            RowActionType::EDIT,
            RowActionType::DELETE,
        ]);

        $this->append(new PageContent($p, $form, $table));
    }
}