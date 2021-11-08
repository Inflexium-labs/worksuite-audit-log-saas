<?php

namespace Modules\AuditLog\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\AuditLog\DataTables\TaskLogDataTable;
use Modules\AuditLog\DataTables\UserLogDataTable;
use App\Http\Controllers\Admin\AdminBaseController;
use App\LogModel;
use Modules\AuditLog\DataTables\ProjectLogDataTable;
use Modules\AuditLog\DataTables\LogActivityDataTable;
use Modules\AuditLog\DataTables\AttendanceLogActivityDataTable;
use Modules\AuditLog\Exports\LogActivityExport;
use Maatwebsite\Excel\Facades\Excel;

class AuditLogController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, LogActivityDataTable $dataTables)
    {
        $this->pageTitle = __('All Log');
        $this->logModels = LogModel::get();
        return $dataTables->render('auditlog::log-activities', $this->data);
    }

    public function export()
    {
        return Excel::download(new LogActivityExport, 'log-activity.xlsx');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function attendance(Request $request, AttendanceLogActivityDataTable $dataTables)
    {
        $this->pageTitle = __('Attendance Log');
        return $dataTables->render('auditlog::attendance.index', $this->data);
    }


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
