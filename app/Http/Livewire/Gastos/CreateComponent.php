<?php

namespace App\Http\Livewire\Gastos;

use Carbon\Carbon;
use App\Models\Gastos;
use Livewire\Component;
use App\Models\MetodoPago;
use App\Models\CategoryGastos;
use App\Models\GastosDetalles;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class CreateComponent extends Component
{
    use WithFileUploads;
    public $fecha, $descripcion, $user_id, $category_gastos_id, $metodo_pago_id, $picture;
    public   $descripcion_detalles, $subtotal, $selected_id, $photo;

    public $total = 0;




    public function render()
    {
        $hoy = Carbon::now();
        $hoy = $hoy->format('Y-m-d');
        $this->fecha = $hoy;
        $categorias = CategoryGastos::where('status', 'ACTIVE')->get();
        $medios = MetodoPago::all();

        return view('livewire.gastos.create-component', compact('categorias', 'medios'));
    }

    protected $rules = [
        'fecha'                 =>  'required',
        'descripcion'           =>  'required|min:4|max:254',
        'category_gastos_id'    =>  'required',
        'metodo_pago_id'        =>  'required',
    ];

    protected $messages = [
        'fecha.required'                => 'Este campo es requerido',
        'descripcion.required'          => 'Este campo es requerido',
        'category_gastos_id.required'   => 'Este campo es requerido',
        'metodo_pago_id.required'       => 'Este campo es requerido',
        'descripcion.min'               => 'Este campo requiere al menos 4 caracteres',
        'descripcion.max'               => 'Este campo no puede superar los 254 caracteres',

    ];


    public function save()
    {
        $validatedData = $this->validate();

        if($this->picture){
            $photo = $this->picture->store('livewire-tem');
        }else{
            $photo = null;
        }


        $gasto = Gastos::create([
            'descripcion'           => $this->descripcion,
            'fecha'                 => $this->fecha,
            'category_gastos_id'    => $this->category_gastos_id,
            'metodo_pago_id'        => $this->metodo_pago_id,
            'user_id'               => Auth::user()->id,
            'total'                 => $this->total,
            'status'                => 'PENDIENTE',
            'picture'               => $photo,
        ]);

        return redirect()->route('gastos.edit', $gasto->id);

    }


    public function cancel()
    {
        $this->reset();
        $this->resetErrorBag();
    }
}
