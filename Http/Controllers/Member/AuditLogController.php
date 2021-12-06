<?php

namespace Modules\AuditLog\Http\Controllers\Member;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\AuditLog\DataTables\TaskLogDataTable;
use Modules\AuditLog\DataTables\UserLogDataTable;
use App\Http\Controllers\Member\MemberBaseController;
use App\LogModel;
use Modules\AuditLog\DataTables\ProjectLogDataTable;
use Modules\AuditLog\DataTables\LogActivityDataTable;
use Modules\AuditLog\DataTables\IncidentLogActivityDataTable;
use Modules\AuditLog\DataTables\AttendanceLogActivityDataTable;
use Modules\AuditLog\Exports\LogActivityExport;
use Modules\AuditLog\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class AuditLogController extends MemberBaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, LogActivityDataTable $dataTables)
    {
        user()->cans('edit_team') || abort(403, __('app.noPermission'));
        
        $this->pageTitle = __('All Log');
        $this->logModels = LogModel::get();
        dateRangeValidate();

        return $dataTables->render('auditlog::member.log-activities', $this->data);
    }

    public function export()
    {
        dateRangeValidate();
        
        return Excel::download(new LogActivityExport, 'log-activity.xlsx');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function incident (Request $request, IncidentLogActivityDataTable $dataTables)
    {
        $this->pageTitle = __('auditlog::app._log_activity.incident_logs');
        dateRangeValidate();

        return $dataTables->render('auditlog::member.incident.index', $this->data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function attendance(Request $request, AttendanceLogActivityDataTable $dataTables)
    {
        $this->pageTitle = __('auditlog::app._log_activity.attendanceLogs');
        dateRangeValidate();

        return $dataTables->render('auditlog::member.attendance.index', $this->data);
    }

    public function AttendanceExport()
    {
        dateRangeValidate();

        return Excel::download(new AttendanceExport, 'attendance-activity.xlsx');
    }


     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function user(Request $request, UserLogDataTable $dataTables)
    {
        $this->pageTitle = __('User Log');

        return $dataTables->render('auditlog::member.user', $this->data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function task(Request $request, TaskLogDataTable $dataTables)
    {
        $this->pageTitle = __('Task Log');

        return $dataTables->render('auditlog::member.task', $this->data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function project(Request $request, ProjectLogDataTable $dataTables)
    {
        $this->pageTitle = __('Project Log');

        return $dataTables->render('auditlog::member.project', $this->data);
    }
}
