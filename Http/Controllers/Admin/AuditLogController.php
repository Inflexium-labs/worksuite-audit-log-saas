<?php

namespace Modules\AuditLog\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\AuditLog\DataTables\TaskLogDataTable;
use Modules\AuditLog\DataTables\UserLogDataTable;
use App\Http\Controllers\Admin\AdminBaseController;
use Modules\AuditLog\DataTables\ProjectLogDataTable;

class AuditLogController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function user(Request $request, UserLogDataTable $dataTables)
    {
        $this->pageTitle = __('User Log');

        return $dataTables->render('auditlog::user', $this->data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function task(Request $request, TaskLogDataTable $dataTables)
    {
        $this->pageTitle = __('Task Log');

        return $dataTables->render('auditlog::task', $this->data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function project(Request $request, ProjectLogDataTable $dataTables)
    {
        $this->pageTitle = __('Project Log');

        return $dataTables->render('auditlog::project', $this->data);
    }
}
