<?php

namespace App\DataTables;

use App\Models\Parada;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ParadaDatableDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
           
            ->editColumn('latitud',function($parada){
                return view('paradas.modal-mapa',['parada'=>$parada])->render();
            })
            ->editColumn('estado',function($parada){
                return view('paradas.estado',['parada'=>$parada])->render();
            })
            ->editColumn('nombre',function($parada){
                return view('paradas.nombre',['parada'=>$parada])->render();
            })
            ->rawColumns(['estado','latitud','nombre'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Parada $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('paradadatable-table')
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
            // Column::computed('action')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->title('Acción')
            //       ->addClass('text-center'),
            Column::make('nombre'),
            Column::make('latitud')->searchable(false)->title('Ver en mapa'),
            Column::make('estado'),
            
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ParadaDatable_' . date('YmdHis');
    }
}
