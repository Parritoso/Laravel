@extends('layouts.app')

@section('title', __('auth/perfil/show.title'))

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center p-4 mb-4">
                <div class="mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="bi bi-person-fill text-primary display-4"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0">{{ Auth::user()->name }}</h4>
                <p class="text-muted small">{{ Auth::user()->email }}</p>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                    <i class="bi bi-translate text-primary me-1"></i> {{__('auth/perfil/show.language')}} {{ strtoupper(Auth::user()->language) }}
                </span>
                
                <hr class="my-4 text-muted opacity-25">
                
                <div class="d-grid gap-2 mt-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary fw-bold shadow-sm">
                        <i class="bi bi-pencil-square me-2"></i> {{__('auth/perfil/show.edit_profile')}}
                    </a>
                    <a href="{{ route('profile.password.edit') }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                        <i class="bi bi-shield-lock me-2"></i> {{__('auth/perfil/show.change_password')}}
                    </a>
                </div>
            </div>

            <div class="list-group shadow-sm border-0">
                <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action border-0 py-3">
                    <i class="bi bi-bag-check text-primary me-3"></i> {{__('auth/perfil/show.my_orders')}}
                </a>
                <a href="{{ route('favorites.index') }}" class="list-group-item list-group-item-action border-0 py-3">
                    <i class="bi bi-heart text-danger me-3"></i> {{__('auth/perfil/show.my_favorites')}}
                </a>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">{{__('auth/perfil/show.personal_info')}}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">{{__('auth/perfil/show.full_name')}}</div>
                        <div class="col-sm-8 fw-semibold">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">{{__('auth/perfil/show.email')}}</div>
                        <div class="col-sm-8 fw-semibold">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">{{__('auth/perfil/show.shipping_addresses')}}</h5>
                    @if(Auth::user()->direcciones() && Auth::user()->direcciones->count() > 0)
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalDireccion" onclick="prepareModalForCreate()">
                            <i class="bi bi-plus-lg me-1"></i> {{__('auth/perfil/show.add_another')}}
                        </a>
                    @endif
                    <i class="bi bi-truck text-primary fs-4"></i>
                </div>
                <div class="card-body">
                    @if(Auth::user()->direcciones() && Auth::user()->direcciones->count() > 0)
                        <div class="row g-3">
                            @foreach (Auth::user()->direcciones as $dir)
                                <div class="col-md-6">
                                    <div class="p-3 border rounded position-relative h-100 {{ $dir->es_predeterminada ? 'border-primary bg-primary bg-opacity-10' : 'bg-light' }}">
                                        @if($dir->es_predeterminada)
                                            <span class="badge bg-primary position-absolute top-0 end-0 m-2">{{__('auth/perfil/show.main')}}</span>
                                        @endif
                                        
                                        <div class="d-flex align-items-start mb-2">
                                            <i class="bi bi-geo-alt-fill text-primary me-2 mt-1"></i>
                                            <div>
                                                <p class="fw-bold mb-0">{{ $dir->calle }} {{ $dir->numero }}</p>
                                                <p class="text-muted small mb-0">{{ $dir->ciudad }}, {{ $dir->codigo_postal }}</p>
                                            </div>
                                        </div>

                                        <div class="mt-3 d-flex gap-2">
                                            <button type="button" class="btn btn-sm text-primary p-0" data-bs-toggle="modal" data-bs-target="#modalDireccion" onclick="prepareModalForEdit({{ json_encode($dir) }})">
                                                <i class="bi bi-pencil"></i> {{__('auth/perfil/show.edit')}}
                                            </button>
                                            <span class="text-muted small">|</span>
                                            <form action="{{ route('direcciones.destroy', $dir) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm text-danger p-0">
                                                    <i class="bi bi-trash"></i> {{__('auth/perfil/show.delete')}}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-light border-dashed text-center py-4">
                            <p class="text-muted mb-2">{{__('auth/perfil/show.no_addresses')}}</p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalDireccion" onclick="prepareModalForCreate()">{{__('auth/perfil/show.add_now')}}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalDireccion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <!-- ID para cambiar el título dinámicamente -->
                <h5 class="modal-title fw-bold" id="modalDireccionTitle">{{__('auth/perfil/show.new_addresses')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('auth/perfil/show.close') }}"></button>
            </div>
            <!-- ID para el formulario -->
            <form id="formDireccion" action="{{ route('direcciones.store') }}" method="POST">
                @csrf
                <!-- Campo oculto necesario para la actualización (PUT) -->
                <input type="hidden" name="_method" id="methodField" value="POST">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small text-muted">{{__('auth/perfil/show.street')}}</label>
                            <!-- IDs en todos los inputs -->
                            <input type="text" name="calle" id="in_calle" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">{{__('auth/perfil/show.number')}}</label>
                            <input type="text" name="numero" id="in_numero" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small text-muted">{{__('auth/perfil/show.city')}}</label>
                            <input type="text" name="ciudad" id="in_ciudad" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">{{__('auth/perfil/show.zip_code')}}</label>
                            <input type="text" name="codigo_postal" id="in_codigo_postal" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('auth/perfil/show.cancel')}}</button>
                    <!-- ID para cambiar el texto del botón -->
                    <button type="submit" id="btnDireccionSubmit" class="btn btn-primary px-4">{{__('auth/perfil/show.save_address')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Referencias a los elementos del modal
    const modalTitle = document.getElementById('modalDireccionTitle');
    const formDireccion = document.getElementById('formDireccion');
    const methodField = document.getElementById('methodField');
    const btnSubmit = document.getElementById('btnDireccionSubmit');
    const addressText = {
        createTitle: @json(__('auth/perfil/show.new_addresses')),
        createButton: @json(__('auth/perfil/show.save_address')),
        editTitle: @json(__('auth/perfil/show.edit_address')),
        editButton: @json(__('auth/perfil/show.update_changes')),
    };
    
    // Inputs
    const inCalle = document.getElementById('in_calle');
    const inNumero = document.getElementById('in_numero');
    const inCiudad = document.getElementById('in_ciudad');
    const inZip = document.getElementById('in_codigo_postal');

    // Función 1: Prepara el modal para CREAR una nueva dirección
    function prepareModalForCreate() {
        modalTitle.innerText = addressText.createTitle;
        btnSubmit.innerText = addressText.createButton;
        btnSubmit.className = "btn btn-primary px-4";
        
        // Configurar formulario para POST
        formDireccion.action = "{{ route('direcciones.store') }}";
        methodField.value = "POST";
        
        // Limpiar los campos
        inCalle.value = '';
        inNumero.value = '';
        inCiudad.value = '';
        inZip.value = '';
    }

    // Función 2: Prepara el modal para EDITAR rellenando los campos
    function prepareModalForEdit(direccion) {
        modalTitle.innerText = addressText.editTitle;
        btnSubmit.innerText = addressText.editButton;
        btnSubmit.className = "btn btn-success px-4"; // Cambiamos color a verde para distinguir
        
        // Configurar formulario para PUT (Actualizar)
        // La URL debe ser: /direcciones/{id}
        let updateUrl = "{{ route('direcciones.update', ':id') }}";
        formDireccion.action = updateUrl.replace(':id', direccion.id);
        methodField.value = "PUT";
        
        // Rellenar campos con los datos pasados
        inCalle.value = direccion.calle;
        inNumero.value = direccion.numero;
        inCiudad.value = direccion.ciudad;
        inZip.value = direccion.codigo_postal;
    }
</script>
@endsection
