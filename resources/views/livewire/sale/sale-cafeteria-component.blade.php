<div>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-7">
                <div class="card" id="cuentas_por_mesas">
                    @include('livewire.sale.subforms.productos_mesas')
                </div>
            </div>
            <div class="col-md-5">
                <div class="card" id="listado_productos" style="height: 500px;">
                    PAGOS Y TRANSACCIONES
                </div>
            </div>
        </div>
        <div class="row row-expand">
            <div class="col-md-12">
                <div class="card" id="mesas" style="height: 200px;">
                    @include('livewire.sale.subforms.mesas')
                    <span class="position-absolute top-0 end-0 m-2" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#cantidadMesasModal">
                        <i class="fas fa-cog"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.sale.editarcantidadmesas')

<style>
    .container-fluid {
      display: flex;
      flex-direction: column;
      height: 100vh; /* Altura total de la ventana */
    }

    .row-expand {
      flex-grow: 1; /* Hace que este div crezca y ocupe todo el espacio restante */
    }

    .bottom-section {
    display: flex;
    justify-content: space-around;
    align-items: center;
    background-color: #f0f0f0;
    padding: 20px;
}

.counter, .tables {
    text-align: center;
}

.counter {
    width: 200px; /* Ancho del mostrador */
    height: 150px; /* Altura del mostrador */
    background-color: #e0e0e0; /* Color de fondo del mostrador */
    border-radius: 15px; /* Radio de los bordes redondeados */
    text-align: center;
    padding: 20px;
}

.tables {
    flex: 3; /* Ocupa tres veces más espacio que el mostrador */
}

.counter h3, .tables h3 {
    margin-bottom: 10px;
}

/* Estilos para las mesas */
.tables .table {
    width: 100px;
    height: 100px;
    background-color: #ffffff;
    border: 1px solid #000000;
    border-radius: 50%;
    margin-bottom: 10px;
}

.mesa-box {
        width: 100px;
        height: 100px;
        background-color: #ffffff;
        border: 1px solid #000000;
        border-radius: 15px;
        margin: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
  </style>


<script>
    // Script para recuperar o establecer la cantidad de mesas en localStorage
    var cantidadMesas = localStorage.getItem('cantidadMesas');

    if (cantidadMesas === null) {
        cantidadMesas = 20; // Cantidad predeterminada de mesas
        localStorage.setItem('cantidadMesas', cantidadMesas);
    }
</script>

<script>
    // Obtener la cantidad de mesas del localStorage
    var cantidadMesas = localStorage.getItem('cantidadMesas');

    // Verificar si la cantidad de mesas ya está almacenada en el localStorage
    if (cantidadMesas !== null) {
        // Si la cantidad de mesas está almacenada, crear los cuadros de las mesas
        for (var i = 1; i <= cantidadMesas; i++) {
            var mesaBox = document.createElement('div');
            mesaBox.className = 'mesa-box';
            mesaBox.textContent = 'Mesa ' + i;
            document.getElementById('mesas-container').appendChild(mesaBox);
        }
    }
</script>
