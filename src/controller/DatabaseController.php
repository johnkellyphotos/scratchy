<?php

namespace controller;

use core\Controller;
use core\RowActionType;
use model\UserModel;
use Scratchy\component\PageContent;
use Scratchy\component\SmartTable;
use Scratchy\component\SubmitButton;
use Scratchy\elements\Element;
use Scratchy\elements\form;
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
        if ($this->data->post('has_been_submitted')) {
            $userModel = new UserModel();
            $userModel->username = 'user' . rand(10000, 99999);
            $userModel->password = hash('sha256', time());
            $userModel->created = date('Y-m-d H:i:s');
            $userModel->create();
        }

        $form = new form(attributes: ['method' => 'POST', 'action' => '/Database/view-users/']);
        $form->append(new SubmitButton(attributes: ['name' => 'has_been_submitted', 'value' => 1], content: 'Create new random user'));

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

        return new PageContent($form, $table);
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