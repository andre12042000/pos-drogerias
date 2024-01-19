<div class="modal" @if ($showpay) style="display:block" @endif data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered md" role="document" >
        <div class="modal-content">
            <form wire:submit.prevent="save">

                    <div class="modal-header bg-primary">
                      <strong> <i class="bi bi-cart4"></i> Terminar venta</strong>
                      <button type="button" class="btn-close" wire:click="close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row">
                            <label for="abona" class="col-sm-4 col-form-label" placeholder="Cantidad abonado">Método de pago</label>
                            <div class="col-sm-8">
                                @include('includes.metodospago')
                            </div>
                          </div>


                        @if($tipo_transaccion == 'credito')
                            <div class="form-group row">
                                <label for="abona" class="col-sm-4 col-form-label" placeholder="Cantidad abonado">Abona</label>
                                <div class="col-sm-8">
                                  <input type="number" class="form-control" id="abona" wire:model.lazy = 'abona'>
                                </div>
                              </div>
                        @else
                        <div class="form-group row">
                            <label for="abona" class="col-sm-4 col-form-label" placeholder="Cantidad recibida">Cantidad recibida</label>
                            <div class="col-sm-8">
                              <input type="number" class="form-control" id="cantidad" wire:model.lazy = 'cantidad_recibida' wire:keydown.enter="updateCantidadRecibida">
                            </div>
                          </div>
                        @endif

                        <br><br>

                        <table id="detallesventa" class="table">

                            <tfoot>
                              <!--   <tr>
                                    <th colspan="2">
                                        <p align="right">DESCUENTO:</p>
                                    </th>
                                    <th>
                                        <p align="right"><span id="total">$ @if($total_descuento > 0) {{ number_format($total_descuento, 0);}} @else  0.00 @endif</span> </p>
                                    </th>
                                </tr> -->
                                <tr>
                                    <th colspan="2">
                                        <p align="right">TOTAL:</p>
                                    </th>
                                    <th>
                                        <p align="right"><span class="text-success" id="total_impuesto">$ @if($total_venta > 0) {{ number_format($total_venta, 0);}} @else  0.00 @endif</span></p>
                                    </th>
                                </tr>
                              <!--   <tr>
                                    <th colspan="2">
                                        <p align="right">PAGO:</p>
                                    </th>
                                    <th>
                                        <p align="right"><span id="total_impuesto">$ @if($pago > 0) {{ number_format($pago, 0);}} @else  0.00 @endif</span></p>
                                    </th>
                                </tr> -->

                                @if($tipo_transaccion == 'credito')

                                <tr>
                                    <th colspan="2">
                                        <p align="right">SALDO:</p>
                                    </th>
                                    <th>
                                        <p align="right"><span id="saldo">$ @if($saldo > 0) {{ number_format($saldo, 0);}} @else  0.00 @endif</span></p>
                                    </th>
                                </tr>


                                @else

                                <tr>
                                    <th colspan="2">
                                        <p align="right">CAMBIO:</p>
                                    </th>
                                    <th>
                                        <p align="right"><span class="text-warning" id="cambio">$ @if($cantidad_recibida > 0) {{number_format($cantidad_recibida - $total_venta)}} @else  0.00 @endif</span></p>
                                    </th>
                                </tr>

                                @endif


                            </tfoot>
                            <tbody>
                            </tbody>
                        </table>




                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary" wire:click="save" >TERMINAR VENTA
                            <div wire:loading wire:target="save">
                                <img src="{!! Config::get('app.URL') !!}/assets/img/loading.gif" width="20px"
                                    class="img-fluid" alt="">
                            </div>
                        </a>

                    </div>

            </form>
        </div>
    </div>
</div>
