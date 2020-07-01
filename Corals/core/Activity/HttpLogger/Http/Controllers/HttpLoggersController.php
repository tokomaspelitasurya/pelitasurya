<?php

namespace Corals\Activity\HttpLogger\Http\Controllers;

use Corals\Activity\HttpLogger\DataTables\HttpLoggersDataTable;
use Corals\Activity\HttpLogger\Http\Requests\HttpLoggerRequest;
use Corals\Activity\HttpLogger\Models\HttpLog;
use Corals\Foundation\Http\Controllers\BaseController;

class HttpLoggersController extends BaseController
{

    public function __construct()
    {
        $this->resource_url = config('http_logger.models.http_log.resource_url');
        $this->title = 'Http Log';
        $this->title_singular = 'Http Log';

        parent::__construct();
    }

    public function show(HttpLoggerRequest $request, HttpLog $httpLog)
    {
        return view('HttpLogger::http_logs.show')->with(compact('httpLog'));
    }

    public function index(HttpLoggerRequest $request, HttpLoggersDataTable $dataTable)
    {
        return $dataTable->render('HttpLogger::http_logs.index');
    }
}