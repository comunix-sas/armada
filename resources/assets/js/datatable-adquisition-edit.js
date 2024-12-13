'use strict';

import Swal from 'sweetalert2';

function initializeSelect2() {
    if (typeof $.fn.select2 === 'undefined') {
        console.error('Select2 no está cargado');
        return false;
    }
    return true;
}

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`
        }
    });

    function initializeEditSelects() {
        if (!initializeSelect2()) return;

        $('#edit-modalidad-seleccion').select2({
            dropdownParent: $('#editModal'),
            placeholder: "Seleccione modalidad",
            allowClear: true,
            ajax: {
                url: '/api/modalidades-seleccion',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        }).prop('disabled', false);

        $('#edit-mes').select2({
            dropdownParent: $('#editModal'),
            placeholder: "Seleccione mes",
            allowClear: true,
            ajax: {
                url: '/api/meses',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        $('#edit-ubicacion').select2({
            dropdownParent: $('#editModal'),
            placeholder: "Seleccione ubicación",
            allowClear: true,
            ajax: {
                url: '/api/ubicaciones',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        // Códigos UNSPSC
        $('#edit-unspsc-code').select2({
            dropdownParent: $('#editModal'),
            placeholder: "Seleccione uno o más códigos",
            allowClear: true,
            multiple: true,
            ajax: {
                url: '/search-codigo',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results.map(function(item) {
                            return {
                                id: item.id,
                                text: item.codigo + ' - ' + item.descripcion
                            };
                        }),
                        pagination: {
                            more: data.pagination && data.pagination.more
                        }
                    };
                },
                cache: true
            },
            language: {
                noResults: function() {
                    return "No se encontraron resultados";
                }
            }
        });
    }

    $(document).on('click', '.edit-adquisition', function() {
        const id = $(this).data('id');

        $.ajax({
            url: `${baseUrl}api/adquisitions/${id}`,
            type: 'GET',
            headers: {
                Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
                Accept: 'application/json'
            },
            success: function(data) {
                initializeEditSelects();

                $('#edit-nombre-plan').val(data.nombrePlan);
                $('#edit-version').val(data.version);
                $('#edit-payment-mode').val(data.modalidadPago).trigger('change');
                $('#edit-budget').val(data.presupuesto);
                $('#edit-currency').val(data.currency).trigger('change');
                $('#edit-trm').val(data.trm);
                $('#edit-cdp').val(data.cdp);
                $('#edit-conversion').val(data.conversion);
                $('#edit-rp').val(data.rp);

                if (data.modalidad_seleccion_id) {
                    $.ajax({
                        url: `${baseUrl}api/modalidades-seleccion/${data.modalidad_seleccion_id}`,
                        type: 'GET',
                        success: function(modalidadData) {
                            const option = new Option(modalidadData.text, modalidadData.id, true, true);
                            $('#edit-modalidad-seleccion').empty().append(option).trigger('change');
                        }
                    });
                }

                if (data.codigosUnspsc && data.codigosUnspsc.length > 0) {
                    const codigosOptions = data.codigosUnspsc.map(codigo => {
                        return new Option(
                            `${codigo.codigo} - ${codigo.descripcion}`,
                            codigo.id,
                            true,
                            true
                        );
                    });

                    $('#edit-unspsc-code')
                        .empty()
                        .append(codigosOptions)
                        .trigger('change');
                }

                if (data.mes_id) {
                    $.ajax({
                        url: `${baseUrl}api/meses/${data.mes_id}`,
                        type: 'GET',
                        success: function(mesData) {
                            const option = new Option(mesData.text, mesData.id, true, true);
                            $('#edit-mes')
                                .empty()
                                .append(option)
                                .val(data.mes_id)
                                .trigger('change');
                        }
                    });
                }

                $('#edit-duracion-contrato').val(data.duracionContrato);
                $('#edit-tipo-duracion').val(data.tipoDuracion).trigger('change');

                $('#edit-fuente-recursos').val(data.fuenteRecursos);
                $('#edit-vigencia').prop('checked', data.vigencia);

                $('#edit-future-status')
                    .val(data.estado ? '1' : '0')
                    .trigger('change');
                $('#edit-contract-unit').val(data.unidadContratacion).trigger('change');

                if (data.ubicacion_id) {
                    $.ajax({
                        url: `${baseUrl}api/ubicaciones/${data.ubicacion_id}`,
                        type: 'GET',
                        success: function(ubicacionData) {
                            const option = new Option(ubicacionData.text, ubicacionData.id, true, true);
                            $('#edit-ubicacion')
                                .empty()
                                .append(option)
                                .trigger('change');
                        }
                    });
                }

                $('#edit-responsible-name').val(data.nombreResponsable);
                $('#edit-responsible-phone').val(data.telefonoResponsable);
                $('#edit-responsible-email').val(data.emailResponsable);

                $('#edit-additional-notes').val(data.notasAdicionales);

                $('#editForm').data('id', data.idPlan);

                $('#editModal').modal('show');

                updateEditConversion();
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de autenticación',
                        text: 'Su sesión ha expirado. Por favor, vuelva a iniciar sesión.'
                    }).then(() => {
                        window.location.href = '/login';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar los datos'
                    });
                }
            }
        });
    });

    $('#saveChanges').on('click', function() {
        const id = $('#editForm').data('id');
        const currentVersion = $('#edit-version').val();

        let formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            nombrePlan: $('#edit-nombre-plan').val(),
            version: currentVersion,
            modalidadPago: $('#edit-payment-mode').val() || null,
            presupuesto: $('#edit-budget').val(),
            currency: $('#edit-currency').val(),
            trm: $('#edit-trm').val(),
            cdp: $('#edit-cdp').val(),
            conversion: $('#edit-conversion').val(),
            rp: $('#edit-rp').val(),
            modalidad_seleccion_id: $('#edit-modalidad-seleccion').val(),
            mes_id: $('#edit-mes').val(),
            duracionContrato: $('#edit-duracion-contrato').val(),
            tipoDuracion: $('#edit-tipo-duracion').val(),
            fuenteRecursos: $('#edit-fuente-recursos').val(),
            vigencia: $('#edit-vigencia').is(':checked') ? 1 : 0,
            estado: $('#edit-future-status').val() || '0',
            ubicacion_id: $('#edit-ubicacion').val(),
            notasAdicionales: $('#edit-additional-notes').val() || '',
            codigo_unspsc_id: $('#edit-unspsc-code').val() || []
        };

        $.ajax({
            url: `${baseUrl}api/adquisitions/${id}`,
            type: 'PUT',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            headers: {
                Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
                Accept: 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: `El plan ha sido actualizado correctamente. Nueva versión: ${response.newVersion}`
                    }).then(() => {
                        $('#editModal').modal('hide');
                        if (typeof adquisitionTable !== 'undefined') {
                            adquisitionTable.ajax.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'No se pudo actualizar el plan'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'No se pudo actualizar el plan';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            }
        });
    });

    function updateEditConversion() {
        const budgetValue = parseFloat($('#edit-budget').val().replace(/\./g, '').replace(',', '.')) || 0;
        const trmValue = parseFloat($('#edit-trm').val()) || 0;
        const conversionValue = budgetValue * trmValue;
        $('#edit-conversion').val(conversionValue.toFixed(2));
    }

    $('#edit-budget, #edit-trm').on('input', updateEditConversion);

    $('#edit-currency').on('change', function() {
        const selectedCurrency = $(this).val();
        if (selectedCurrency !== 'COP') {
            fetch(`https://api.exchangerate-api.com/v4/latest/${selectedCurrency}`)
                .then(response => response.json())
                .then(data => {
                    let trmValue = data.rates.COP || 1;
                    $('#edit-trm').val(trmValue.toFixed(2));
                    updateEditConversion();
                })
                .catch(error => console.error('Error:', error));
        }
    });

    $(document).ready(function() {
        $('#editModal').on('shown.bs.modal', function() {
            initializeEditSelects();
        });

        $('#editModal').on('hidden.bs.modal', function() {
            ['#edit-modalidad-seleccion', '#edit-mes', '#edit-ubicacion', '#edit-unspsc-code'].forEach(selector => {
                if ($(selector).hasClass("select2-hidden-accessible")) {
                    $(selector).select2('destroy');
                }
                $(selector).empty();
            });
        });
    });
});
