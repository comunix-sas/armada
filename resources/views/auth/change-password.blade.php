@extends('layouts/contentNavbarLayout')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-center py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md me-2">
                            <span class="avatar-initial rounded-circle bg-primary">
                                <i class="ti ti-lock-access ti-md"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">Cambiar Contraseña</h4>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="ti ti-check-circle ti-sm me-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.change') }}" class="row g-3">
                        @csrf

                        <div class="col-12">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password" name="current_password"
                                       placeholder="············" required>
                                <span class="input-group-text cursor-pointer" id="toggleCurrentPassword">
                                    <i class="ti ti-eye-off" id="currentPasswordIcon"></i>
                                </span>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password"
                                       placeholder="············" required>
                                <span class="input-group-text cursor-pointer" id="toggleNewPassword">
                                    <i class="ti ti-eye-off" id="newPasswordIcon"></i>
                                </span>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                La contraseña debe tener al menos 8 caracteres
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control"
                                       id="password_confirmation" name="password_confirmation"
                                       placeholder="············" required>
                                <span class="input-group-text cursor-pointer" id="toggleConfirmPassword">
                                    <i class="ti ti-eye-off" id="confirmPasswordIcon"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">
                                <i class="ti ti-check me-1"></i>
                                Actualizar Contraseña
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function togglePasswordVisibility(inputId, toggleId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const toggle = document.getElementById(toggleId);

        toggle.addEventListener('click', function() {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);

            // Cambiar el icono con una animación suave
            icon.style.transition = 'transform 0.2s ease';
            if (type === 'password') {
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
                icon.style.transform = 'scale(1)';
            } else {
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
                icon.style.transform = 'scale(1.1)';
            }
        });

        // Efecto hover en el botón de mostrar/ocultar
        toggle.addEventListener('mouseenter', function() {
            icon.style.transition = 'transform 0.2s ease';
            icon.style.transform = 'scale(1.1)';
        });

        toggle.addEventListener('mouseleave', function() {
            icon.style.transition = 'transform 0.2s ease';
            icon.style.transform = 'scale(1)';
        });
    }

    // Configurar los tres campos de contraseña
    togglePasswordVisibility('current_password', 'toggleCurrentPassword', 'currentPasswordIcon');
    togglePasswordVisibility('password', 'toggleNewPassword', 'newPasswordIcon');
    togglePasswordVisibility('password_confirmation', 'toggleConfirmPassword', 'confirmPasswordIcon');
});
</script>

<style>
.input-group-text {
    transition: all 0.2s ease;
}

.input-group-text:hover {
    background-color: #f5f5f9;
    cursor: pointer;
}

.cursor-pointer {
    cursor: pointer;
}

.card {
    box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
    transition: all 0.2s ease-in-out;
}

.card:hover {
    box-shadow: 0 4px 12px 0 rgba(67, 89, 113, 0.16);
}

.avatar-md {
    width: 3.5rem;
    height: 3.5rem;
}

.avatar-initial {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
