<?php

namespace Modules\AuditLog\DataTables;

use Carbon\Carbon;
use App\DataTables\BaseDataTable;
use App\LogActivity;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class AttendanceLogActivityDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)

            ->editColumn('name', function ($row) {
                if (!$row->user_id)
                    return '--';

                return '<a target="_blank" href="' . route("admin.employees.show", $row->user_id) . '">' . $row->name . '</a>';
            })

            ->editColumn('whom_user_name', function ($row) {
                if (!$row->whom_user_id)
                    return '--';

                return '<a target="_blank" href="' . route("admin.employees.show", $row->whom_user_id) . '">' . $row->whom_user_name . '</a>';
            })

            ->editColumn('properties', function ($row) {
                if ($row->properties) {
                    return view('auditlog::properties')->with('properties', json_decode($row->properties, true))->with('id', $row->id)->with('global', $this->global);
                } else
                    return '-';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y - h:i a');
            })


            ->addColumn('action', function ($row) {
                if ($row->attendance_id)
                    $action = '<a href="' .  route('admin.attendances.info', $row->attendance_id) . '" class="btn btn-sm btn-info view-attendance"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                return $action ?? '--';
            })

            ->rawColumns(['name', 'whom_user_name', 'properties', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $logActivity = LogActivity::leftJoin('users', 'users.id', '=', 'log_activities.causer_id')
            ->leftJoin('attendances', 'attendances.id', '=', 'log_activities.subject_id')
            ->leftJoin('users as whom_user', function ($join) {
                $join->on('whom_user.id', '=', 'attendances.user_id');
            })
            ->select('log_activities.*', 'users.name as name', 'users.id as user_id', 'whom_user.name as whom_user_name', 'whom_user.id as whom_user_id', 'attendances.id as attendance_id')
            ->where('log_activities.subject_type', 'App\Attendance');

        if (request()->daterange) {
            $dates = explode(' - ', request()->daterange);
            $startDate = Carbon::create($dates[0] ?? date('Y-m-d'));
            $endDate = Carbon::create($dates[1] ?? date('Y-m-d'));

            $logActivity = $logActivity->whereBetween('log_activities.created_at', [$startDate->toDateString() . ' 00:00:00', $endDate->toDateString() . ' 23:59:59']);
        }

        return $logActivity;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('log_activity')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->buttons(
                Button::make()
            )
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["log_activity"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            __('auditlog::app._log_activity.user')         => ['data' => 'name', 'name' => 'users.name'],
            __('auditlog::app._log_activity.activity')     => ['data' => 'description', 'name' => 'log_activities.description'],
            __('auditlog::app._log_activity.for_whom')     => ['data' => 'whom_user_name', 'name' => 'whom_user.name'],
            __('auditlog::app._log_activity.properties')   => ['data' => 'properties', 'name' => 'log_activities.properties'],
            __('auditlog::app._log_activity.ip')           => ['data' => 'ip', 'name' => 'log_activities.ip'],
            __('auditlog::app._log_activity.date')         => ['data' => 'created_at', 'name' => 'log_activities.created_at'],
            Column::computed('action', __('auditlog::app._log_activity.view_mark'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'task_log_' . date('YmdHis');
    }

    public function pdf()
    {
        set_time_limit(0);
        if ('snappy' == config('datatables-buttons.pdf_generator', 'snappy')) {
            return $this->snappyPdf();
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('datatables::print', ['data' => $this->getDataForPrint()]);

        return $pdf->download($this->getFilename() . '.pdf');
    }
}
