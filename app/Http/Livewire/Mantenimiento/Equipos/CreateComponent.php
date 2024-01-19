<?php

namespace App\Http\Livewire\Mantenimiento\Equipos;

use App\Models\Brand;
use App\Models\Client;
use Livewire\Component;
use Modules\Mantenimiento\Entities\Equipos;
use Modules\Mantenimiento\Entities\TipoEquipo;

class CreateComponent extends Component
{
    public $referencia, $color, $modelo, $marca, $placa, $tipoequipo, $nuevamarca, $nuevotipo, $brand, $tipos, $selected_id;
    protected $listeners = ['EquipoEventEdit', 'BrandEvent', 'TipoEvent'];
    public function EquipoEventEdit($equipo)
    {
        $this->selected_id     = $equipo['id'];
        $this->placa           = $equipo['serial_placa'];
        $this->referencia      = $equipo['referencia'];
        $this->marca           = $equipo['brand_id'];
        $this->color           = $equipo['color'];
        $this->modelo          = $equipo['modelo'];

        $this->tipoequipo      = $equipo['tipo_equipo_id'];
    }

    public function BrandEvent($brand)
    {
        $this->brand = Brand::all();
        $this->marca = $brand['id'];
    }

    public function TipoEvent($tipo)
    {
        $this->tipos = TipoEquipo::all();
        $this->tipoequipo = $tipo['id'];
    }
    public function render()
    {
        $this->brand = Brand::all();
        $clientes = Client::all();
        $this->tipos = TipoEquipo::all();
        return view('livewire.mantenimiento.equipos.create-component', compact('clientes'));
    }

    //Validaciones

    protected $rules = [
        'referencia'         => 'required',
        'marca'              => 'required',
        'color'              => 'nullable',
        'modelo'             => 'nullable',
        'tipoequipo'         => 'required',
    ];

    protected $messages = [
        'referencia.required'   => 'Este campo es requerido',
        'marca.required'        => 'Este campo es requerido',
        'tipoequipo.required'   => 'Este campo es requerido',

    ];

    public function storeOrupdate()
    {
        if ($this->selected_id > 0) {
            $this->update();
        } else {
            $this->save();
        }
        $this->emit('reloadEquipos');
    }


    public function save()
    {
        $this->validate();
        $equipos = Equipos::create([
            'serial_placa'         => $this->placa,
            'referencia'           => $this->referencia,
            'brand_id'             => $this->marca,
            'color'                => $this->color,
            'modelo'               => $this->modelo,

            'tipo_equipo_id'       => $this->tipoequipo,

        ]);
        session()->flash('message', 'Equipo crado exitosamente');
        $this->emit('EquipoEvent', $equipos);
    }
    public function update()
    {

        $this->validate();

        $equipo = Equipos::find($this->selected_id);

        $equipo->update([
            'serial_placa'         => $this->placa,
            'referencia'           => $this->referencia,
            'brand_id'             => $this->marca,
            'color'                => $this->color,
            'modelo'               => $this->modelo,

            'tipo_equipo_id'       => $this->tipoequipo,
        ]);

    session()->flash('message', 'Equipo actualizado exitosamente');


    }

    public function guardarMarca()
    {
        $this->validate([
            'nuevamarca' =>  'required|min:2|max:100|unique:brands,name',
        ],[
            'nuevamarca.required'          => 'Este campo es requerido',
            'nuevamarca.min'               => 'Este campo requiere al menos 2 carácteres',
            'nuevamarca.max'               => 'Este campo no puede superar los 100 carácteres',
            'nuevamarca.unique'            => 'Esta marca ya existe',
        ]);

        $marca = Brand::create([
            'name'     =>  $this->nuevamarca,
        ]);

        $this->nuevamarca = '';
        $this->emit('BrandEvent', $marca);
    }


    public function guardarTipos()
    {
        $this->validate([
            'nuevotipo' =>  'required|min:2|max:100|unique:tipo_equipos,descripcion',
        ],[
            'nuevotipo.required'          => 'Este campo es requerido',
            'nuevotipo.min'               => 'Este campo requiere al menos 2 carácteres',
            'nuevotipo.max'               => 'Este campo no puede superar los 100 carácteres',
            'nuevotipo.unique'            => 'Esta tipo ya existe',
        ]);

        $tipo = TipoEquipo::create([
            'descripcion'     =>  $this->nuevotipo,
        ]);

        $this->nuevotipo = '';
        $this->emit('TipoEvent', $tipo);
    }



    public function cancel()
    {
        $this->reset();
        $this->resetErrorBag();
    }
}
