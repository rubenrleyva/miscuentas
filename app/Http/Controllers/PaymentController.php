<?php

namespace App\Http\Controllers;

use App\Payments;
use Datatables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws Exception
     */
    public function index()
    {
        $payments = Payments::all();
        return Datatables::of($payments)
            ->addColumn('delete', function($payments){
                //$edition = '<button type="button" name="edit" id="'.$payments->id.'" class="edit btn btn-primary btn-sm">Editar</button>';
                $delete = '<a type="button" name="delete" href="'.route('delete', $payments->id).'" class="delete btn btn-danger btn-sm">Eliminar</a>';
                return $delete;
            })->rawColumns(['delete'])
            ->make(true);
    }

    /**
     * Función encargada de mostrar los datos del donut
     * @return mixed
     */
    public function donutGraphic()
    {
        ## recogemos todos los pagos
        $payments = Payments::all();

        ## recorremos los pagos
        foreach ($payments as $payment){

            ## dependiendo del concepto
            switch ($payment->concept){

                ## guardamos en un array su valor

                case 'Factura alarma': $data['alarma'] = floatval($payment->cost);
                    break;
                case 'Factura agua': $data['agua'] = floatval($payment->cost);
                    break;
                case 'Factura comida': $data['comida'] = floatval($payment->cost);
                    break;
                case 'Factura comunidad': $data['comunidad'] = floatval($payment->cost);
                    break;
                case 'Factura gas': $data['gas'] = floatval($payment->cost);
                    break;
                case 'Factura luz': $data['luz'] = floatval($payment->cost);
                    break;
                case 'Factura internet': $data['internet'] = floatval($payment->cost);
                    break;
                case 'Factura varios': $data['varios'] = floatval($payment->cost);
                    break;
                default:;
            }

        }

        ## retornamos los datos
        return $data;

    }

    public function barsGraphic()
    {
        ## recogemos todos los pagos
        $payments = Payments::all();

        ## array con los meses
        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', ' octubre', 'noviembre', 'diciembre'];

        ## iniciamos los arrays con los meses de cada usuario
        foreach ($meses as $mes){
            $data['ruben'][$mes] = 0;
            $data['cristina'][$mes] = 0;
        }

        ## recorremos los pagos
        foreach ($payments as $payment){

            ## se añade el nombre según el usuario
            if($payment->user_id == 1){
                $nombre = 'ruben';
            }elseif($payment->user_id == 2){
                $nombre = 'cristina';
            }

            ## dependiendo del concepto
            switch (date("m", strtotime($payment->created_at))){
                ## guardamos en un array su valor
                case 01: $data[$nombre]['enero'] += floatval($payment->cost);
                    break;
                case 02: $data[$nombre]['febrero'] +=  floatval($payment->cost);
                    break;
                case 03: $data[$nombre]['marzo'] += floatval($payment->cost);
                    break;
                case 04: $data[$nombre]['abril'] = floatval($payment->cost);
                    break;
                case 05: $data[$nombre]['mayo'] = floatval($payment->cost);
                    break;
                case 06: $data[$nombre]['junio'] = floatval($payment->cost);
                    break;
                case 07: $data[$nombre]['julio'] = floatval($payment->cost);
                    break;
                case 8: $data[$nombre]['agosto'] = floatval($payment->cost);
                    break;
                case 9: $data[$nombre]['septiembre'] +=  floatval($payment->cost);
                    break;
                case 10: $data[$nombre]['octubre'] += floatval($payment->cost);
                    break;
                case 11: $data[$nombre]['noviembre'] = floatval($payment->cost);
                    break;
                case 12: $data[$nombre]['diciembre'] = floatval($payment->cost);
                    break;
                default:;
            }
        }

        ## retornamos los datos
        return $data;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {

        ## validamos los datos
        $this->validate($request, [
            'cost' => ['required', 'string'],
            'concept' => ['required', 'string'],
            'description' => ['required', 'string']
        ]);

        ## creamos el pago
        Payments::create([
            'user_id' => auth()->user()->id,
            'photo_id' => 1,
            'concept' => $request['concept'],
            'description' => $request['description'],
            'cost' => floatval($request['cost']),
        ]);

        ## redireccionamos al home
        return redirect()->route('home');

    }

    /**
     * Display the specified resource.
     *
     * @param Payments $pago
     * @return Response
     */
    public function show(Payments $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Payments $pago
     * @return Response
     */
    public function edit(Payments $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Payments $pago
     * @return Response
     */
    public function update(Request $request, Payments $pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Payments $pago
     * @return Response
     * @throws Exception
     */
    public function destroy(Payments $pago)
    {

        ## comprobamos que existe el pago
        if($pago){

            ## lo eliminamos del sistema
            $pago->delete();

            ## avisamos con un mensaje
            $message = 'Pago eliminado';

        ## en caso contrario
        }else{

            ## avisamos con un mensaje
            $message = 'Pago no existe';
        }

        return redirect()->route('home')->with('message', $message);
    }
}
