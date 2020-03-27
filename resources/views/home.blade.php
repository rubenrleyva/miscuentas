@extends('layouts.app')
@push('estilos')

    <link rel="stylesheet" href="/css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/css/adminlte/css/adminlte.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-info">
                    <div class="bg-info card-header">
                        <button id="pago" class="btn btn-success" data-toggle="modal" data-target="#pagar">
                            Añadir pago
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="table_payments" class="display" style="width:100%; text-align: center">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Concepto</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Fecha</th>
                                <th>Eliminar</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-2">
        <div class="card border-info mb-2">
            <div class="text-center bg-info card-header">
                <h3 class="align-middle card-title">Gastos por concepto anuales</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card border-info mb-2">
            <div class="text-center bg-info card-header">
                <h3 class="card-title">Gatos totales mensuales por usuario</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('modal')
    <div class="modal fade" id="pagar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Añade un nuevo pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="create_pay" method="POST" action="{{ route('pay') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="cost">Precio pagado:</label>
                            <input type="text" class="form-control" id="cost" name="cost" required>
                            @error('cost')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="concept">Concepto:</label>
                            <select class="form-control" id="concept" name="concept" required>
                                <option>Factura alarma</option>
                                <option>Factura agua</option>
                                <option>Factura comida</option>
                                <option>Factura comunidad</option>
                                <option>Factura gas</option>
                                <option>Factura luz</option>
                                <option>Factura internet</option>
                                <option>Factura varios</option>
                            </select>
                            @error('concept')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción:</label>
                            <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="message_ok" tabindex="-1" role="dialog" aria-labelledby="message_o" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="message_o">Mensaje del sistema</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    {{ Session::get('message') }}
                    <p id="debes"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('scriptTables')
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" defer></script>
    <script type="text/javascript" charset="utf8" src="http://cdn.datatables.net/plug-ins/1.10.20/dataRender/datetime.js" defer></script>
    <script type="text/javascript" charset="utf8" src="/js/moment.js"></script>
    <script src="/js/chart.js/Chart.min.js"></script>
    <script src="/js/adminlte/adminlte.min.js"></script>
    <script>
        $(document).ready(function(){
            @if( Session::has('message'))
                $('#message_ok').modal();
            @endif
            tablas();
            statistics();

        });
    </script>
@endpush
