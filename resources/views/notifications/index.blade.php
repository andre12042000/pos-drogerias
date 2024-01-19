@extends('adminlte::page')

@section('title', 'Notificaciones')

@include('popper::assets')

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap4.min.css">
@stop

@section('content_header')

@stop

@section('content')
@include('popper::assets')

@include('includes.alert')

<div class="card  card-info">
<div class="card-header"><h3> Mis notificaciones</h3></div>
     <a class="ml-3 mt-3 text-dark" href="{{ route('markAsRead') }}" id="mark-all"><strong><i class="bi bi-check2-all"></i> Marcar todas como leídas</strong></a>
    <div class="card-body">
        <table class="table table-striped" id="tabdispositivos">
            <thead>
                <tr>

                    <th>Fecha</th>
                    <th>Titulo</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                 @foreach ($notifications as $notification)
                <tr>
                    <td>{{ $notification['created_at']->diffForHumans() }}</td>
                     <td><i class='{{ $notification['data']['icon'] }}'></i></td>
                    <td>{{ $notification['data']['text'] }}</td>
                    <td>@if(is_null($notification['read_at'])) <strong class="text-danger">No leída</strong>  @else<strong class="text-success">Leída</strong> @endif</td>
                    <td class="text-center">
                    @if(is_null($notification['read_at']))
                        <button type="submit" class="mark-as-read btn btn-sm btn-dark" data-id="{{ $notification->id }}">Marcar como leída</button>
                    @else
                        <button  href="#" @popper(Ya fue leída) type="submit" class="ml-2 mark-as-read btn btn-sm btn-success "  data-id="{{ $notification['id'] }}" disabled> Leída <i class="bi bi-check2"></i> </button>   </button>
                    @endif
                </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop



@section('js')


    <script>
  function sendMarkRequest(id = null){
    return $.ajax("{{ route('markNotification') }}", {
      method: 'POST',
      data: {
        _token: "{{ csrf_token() }}",
        id
      }
    });
  }
  $(function(){
    $('.mark-as-read').click(function(){
      let request = sendMarkRequest($(this).data('id'));
      request.done(() => {
        $(this).parents('div.alert').remove();
      });
    });
    $('#mark-all').click(function(){
      let request = sendMarkRequest();
      request.done(() => {
        $('div.alert').remove();
      })
    });
  });
</script>

@stop
