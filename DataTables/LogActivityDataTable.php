<?php

namespace Modules\AuditLog\DataTables;

use Carbon\Carbon;
use App\DataTables\BaseDataTable;
use App\LogActivity;
use Yajra\DataTables\Html\Button;
use Illuminate\Support\Str;

class LogActivityDataTable extends BaseDataTable
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
            ->addIndexColumn()
            ->addColumn('user.name', function ($row) {
                return '<a target="_blank" href="' . route("admin.employees.show", $row->causer_id) . '">' . $row->user->name . '</a>';
            })
            ->addColumn('description', function ($row) {
                return  $row->description . ' ' . Str::afterLast($row->subject_type , '\\');;
            })
            ->editColumn('properties', function ($row) {
              if($row->properties)
              {
                return view('auditlog::properties')->with('properties',json_decode($row->properties,true))->with('id',$row->id);
              }
              else 
                return '-';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y - h:i a');
            })
            ->rawColumns(['user.name','properties']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $model = LogActivity::with('user');

        $date = Carbon::create((request()->year ?? date('Y')) . '-' . (request()->month ?? date('m')) . '-01');
        $startDate = $date->copy()->startOfMonth();
        $endDate = $date->copy()->endOfMonth();
        $model->whereBetween('created_at', [$startDate, $endDate]);

        if(request()->model_name)
          $model->where('subject_type',request()->model_name);
 
        return $model;
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
            __('app.id')       => ['data' => 'causer_id', 'name' => 'causer_id'],
            __('Model')        => ['data' => 'subject_type', 'name' => 'subject_type'],
            __('user')         => ['data' => 'user.name', 'name' => 'user.name'],
            __('activity')     => ['data' => 'description', 'name' => 'description'],
            __('properties')   => ['data' => 'properties', 'name' => 'properties'],
            __('IP')           => ['data' => 'ip', 'name' => 'ip'],
            __('app.date')     => ['data' => 'created_at', 'name' => 'created_at'],
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
