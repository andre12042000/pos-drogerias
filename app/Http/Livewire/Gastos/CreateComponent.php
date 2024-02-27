<?php

namespace App\Http\Livewire\Gastos;

use Carbon\Carbon;
use App\Models\Gastos;
use Livewire\Component;
use App\Models\MetodoPago;
use App\Models\CategoryGastos;
use App\Models\GastosDetalles;
use Illuminate\Support\Facades\Auth;

class CreateComponent extends Component
{
    public $fecha, $descripcion, $user_id, $category_gastos_id, $metodo_pago_id, $picture;
    public $precio_detalles, $cantidad_detalles, $descripcion_detalles, $subtotal, $selected_id;
    public $gastos_id;
    public $total = 0;

    protected $listeners = ['GatosEvent'];

    public function GatosEvent($gasto)
    {
        $this->selected_id          = $gasto['id'];
        $this->descripcion          = $gasto['descripcion'];
        $this->fecha                = $gasto['fecha'];
        $this->user_id              = $gasto['user_id'];
        $this->category_gastos_id   = $gasto['category_gastos_id'];
        $this->metodo_pago_id       = $gasto['metodo_pago_id'];
        $this->picture              = $gasto['picture'];
        $this->total                = $gasto['total'];


    }

    public function render()
    {
        $hoy = Carbon::now();
        $hoy = $hoy->format('Y-m-d');
        $this->fecha= $hoy;
        $categorias = CategoryGastos::where('status', 'ACTIVE')->get();
        $medios = MetodoPago::all();

        return view('livewire.gastos.create-component', compact('categorias', 'medios'));
    }

    protected $rules = [
        'fecha'             =>  'required',
        'descripcion'       =>  'required|min:4|max:254',
        'category_gastos_id'=>  'required',
        'metodo_pago_id'    =>  'required',


    ];

    protected $messages = [
        'fecha.required'                => 'Este campo es requerido',
        'descripcion.required'          => 'Este campo es requerido',
        'category_gastos_id.required'   => 'Este campo es requerido',
        'metodo_pago_id.required'       => 'Este campo es requerido',
        'descripcion.min'               => 'Este campo requiere al menos 4 caracteres',
        'descripcion.max'               => 'Este campo no puede superar los 254 caracteres',

    ];

    public function storeOrupdate()
    {
        if($this->selected_id > 0){
            $this->update();
        }else{
            $this->save();
        }
        $this->emit('reloadGastos');
    }

    public function save()
    {
        $validatedData = $this->validate();


        $gastos = Gastos::create([
            'descripcion'           => $this->descripcion,
            'fecha'                 => $this->fecha,
            'category_gastos_id'    => $this->category_gastos_id,
            'metodo_pago_id'        => $this->metodo_pago_id,
            'user_id'           => Auth::user()->id,
            'picture'               => $this->picture,
            'total'                 => $this->total,
        ]);
        $this->selected_id = $gastos->id;



        session()->flash('message', 'Gasto creado exitosamente');
    }

    public function update()
    {
        $validatedData = $this->validate();

        $gastos = Gastos::find($this->selected_id);

        $gastos->update([
            'descripcion'           => $this->descripcion,
            'fecha'                 => $this->fecha,
            'category_gastos_id'    => $this->category_gastos_id,
            'metodo_pago_id'        => $this->metodo_pago_id,
            'user_id'               => Auth::user()->id,
            'picture'               => $this->picture,
            'total'                 => $this->total,
        ]);
        $this->cancel();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('alert');
    }


    public function añadirddetalle(){

        $this->validate([
            'descripcion_detalles'  =>  'required',
            'cantidad_detalles'     =>  'required',
            'precio_detalles'       =>  'required',
            'subtotal'              =>  'required',

          ],[
            'descripcion_detalles.required'     => 'Este campo es requerido',
            'cantidad_detalles.required'        => 'Campo requerido',
            'precio_detalles.required'          => 'Campo requerido',
            'subtotal.required'                 => 'Campo requerido',

          ]);


        $detalles = GastosDetalles::create([
            'descripcion'    => $this->descripcion_detalles,
            'cantidad'       => $this->cantidad_detalles,
            'precio'         => $this->precio_detalles,
            'subtotal'       => $this->subtotal,
            'gastos_id'    => $this->selected_id

        ]);
        $this->descripcion_detalles = "";
        $this->cantidad_detalles = "";
        $this->precio_detalles = "";
        $this->subtotal = "";
        $total = 0;


        $gastos = Gastos::find($this->selected_id);
            $saldo = $gastos->total;

        $total = $saldo + $detalles->subtotal;
        $gastos->update([
            'total'                 => $total,
        ]);
        $this->emit('reloadGastos');
        $this->GatosEvent($gastos);
    }

    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }
}
