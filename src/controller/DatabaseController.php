<?php

namespace controller;

use core\Controller;
use Scratchy\elements\Element;
use Throwable;
use view\DatabaseController\IndexView;
use view\DatabaseController\ViewUserActivityView;
use view\DatabaseController\PowerPointRecordView;

/** @noinspection PhpUnused */
class DatabaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /** @noinspection PhpUnused */
    public function viewPowerpointControlRecords(): PowerPointRecordView
    {
        return new PowerPointRecordView();
    }

    /**
     * @throws Throwable
     */
    /** @noinspection PhpUnused */
    public function index(): ?Element
    {
        return new IndexView($this->data->post('execute_build'));
    }
}