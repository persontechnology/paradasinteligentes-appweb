<?php

namespace App\DataTables;

use App\Models\PosicionVehiculo;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PosicionVehiculoDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($pv){
                return view('posicion-vehiculos.action',['pv'=>$pv])->render();
            })
            ->editColumn('vehiculo.codigo',function($pv){
                return view('posicion-vehiculos.vehiculo',['pv'=>$pv])->render();
            })
            ->editColumn('tipoRuta.ruta.nombre',function($pv){
                return view('posicion-vehiculos.ruta',['pv'=>$pv])->render();
            })->rawColumns(['action','tipoRuta.ruta.nombre','vehiculo.codigo'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PosicionVehiculo $model): QueryBuilder
    {
        return $model->newQuery()->with(['vehiculo','tipoRuta.ruta']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('posicionvehiculo-table')
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
            Column::make('coordenadas'),
            Column::make('esta_ruta'),
            Column::make('tipoRuta.ruta.nombre')->title('Ruta'),
            Column::make('vehiculo.codigo')->title('Vehículo'),
            Column::make('velocidad'),
            Column::make('created_at')->title('Fecha'),
            Column::make('detalle'),
            Column::make('direccion')->title('Dirección'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PosicionVehiculo_' . date('YmdHis');
    }
}
