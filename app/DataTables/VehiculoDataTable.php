<?php

namespace App\DataTables;

use App\Models\Vehiculo;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class VehiculoDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($vehiculo){
                return view('vehiculos.action',['vehiculo'=>$vehiculo])->render();
            })
            ->editColumn('ubicacion_actual',function($vehiculo){
                return view('vehiculos.mapa',['vehiculo'=>$vehiculo])->render();
            })
            ->editColumn('nombre_conductor', function ($vehiculo) {
                return $vehiculo->nombre_conductor;
            })
            ->editColumn('nombre_ayudante', function ($vehiculo) {
                return $vehiculo->nombre_ayudante;
            })
            ->filterColumn('nombre_conductor', function ($query, $keyword) {
                $query->whereHas('conductor', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('nombre_ayudante', function ($query, $keyword) {
                $query->whereHas('ayudante', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['action','ubicacion_actual'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Vehiculo $model): QueryBuilder
    {
        return $model->newQuery()->with(['conductor', 'ayudante']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('vehiculo-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->title('Acción')
                  ->addClass('text-center'),
            Column::make('foto')->searchable(false),
            Column::make('numero_linea')->title('# Línea'),
            Column::make('ubicacion_actual')->searchable(false),
            Column::make('codigo')->title('Código'),
            Column::make('placa'),
            Column::make('marca'),
            Column::make('modelo'),
            Column::make('nombre_cooperativa')->title('Cooperativa'),
            Column::make('descripcion')->searchable(false)->title('Descripción'),
            Column::make('estado'),
            Column::make('nombre_conductor')->title('Conductor'),
            Column::make('nombre_ayudante')->title('Ayudante'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Vehiculo_' . date('YmdHis');
    }
}
