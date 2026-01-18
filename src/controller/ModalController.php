<?php

namespace controller;

use core\Controller;
use core\Database\Model;
use model\_Model;
use Scratchy\component\Modal\Modal;
use view\ModalController\DeleteRecordView;
use view\ModalController\EditRecordView;
use view\ModalController\ViewRecordView;

/** @noinspection PhpUnused */
class ModalController extends Controller
{
    private ?string $modelName;
    private ?int $id;
    private ?string $modalButtonAction;
    private Model $model;
    private ?Model $record;
    private string $modelDisplayName;

    public function __construct()
    {
        $this->webPageTemplate = Modal::class;
        parent::__construct();

        $this->modelName = $this->data->get('model');
        $this->id = $this->data->get('id');
        $this->modalButtonAction = $this->data->post('modalButtonAction');
        $this->model = _Model::get($this->modelName);
        $this->record = $this->model::fromId($this->id);
        $this->modelDisplayName = _Model::displayNameFromModelName($this->model::class);
    }

    /** @noinspection PhpUnused */
    public function editRecord(): EditRecordView
    {
        return new EditRecordView($this->record, $this->modelDisplayName, $this->modalButtonAction, $this->data->post('modalInputData'));
    }

    /** @noinspection PhpUnused */
    public function viewRecord(): ViewRecordView
    {
        return new ViewRecordView($this->record, $this->modelDisplayName);
    }

    /** @noinspection PhpUnused */
    public function deleteRecord(): DeleteRecordView
    {
        return new DeleteRecordView($this->record, $this->modelDisplayName, $this->modalButtonAction);
    }
}