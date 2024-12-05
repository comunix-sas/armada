    $(document).ready(function() {
        // Inicializa Select2 para todos los selects con clase .select2
        $('.select2').select2({
            placeholder: "Seleccione una opción",
            allowClear: true
        });

        // Configuración AJAX para el select de código UNSPSC
        $('#unspsc-code').select2({
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
                }
            },
            language: {
                noResults: function() {
                    return "No se encontraron resultados";
                }
            }
        });

        const fileList = $('#file-list');
        const uploadArea = $('.file-upload-area');
        const fileInput = $('#document-upload');

        // Manejar el arrastrar y soltar
        uploadArea.on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('border-primary');
        });

        uploadArea.on('dragleave', function(e) {
            e.preventDefault();
            $(this).removeClass('border-primary');
        });

        uploadArea.on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('border-primary');
            const files = e.originalEvent.dataTransfer.files;
            handleFiles(files);
        });

        // Manejar selección de archivo por clic
        fileInput.on('change', function() {
            handleFiles(this.files);
        });

        // Función para manejar los archivos
        function handleFiles(files) {
            Array.from(files).forEach(file => {
                // Validar tamaño del archivo (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    toastr.error(`${file.name} excede el tamaño máximo permitido`);
                    return;
                }

                const fileItem = `
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <i class="bx bx-file me-2"></i>
              ${file.name}
            </div>
            <button type="button" class="btn btn-danger btn-sm delete-file">
              <i class="bx bx-trash"></i>
            </button>
          </li>
        `;
                fileList.append(fileItem);
            });
        }

        // Eliminar archivo
        $(document).on('click', '.delete-file', function() {
            $(this).closest('li').remove();
        });

        $('.browse-files').on('click', function() {
            $('#document-upload').click();
        });

        function updateCDPDate() {
            const date = new Date();
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            $('#cdp-date').val(`${month}/${year}`);
        }

        updateCDPDate();

        $('#cdp').on('focus', function() {
            updateCDPDate();
        });

        $(document).ready(function() {
            function fetchTrm(currency) {
                let url = `https://api.exchangerate-api.com/v4/latest/${currency}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        let trmValue = 1;

                        if (currency === 'USD' && data.rates.COP) {
                            trmValue = data.rates.COP;
                        } else if (currency === 'EUR' && data.rates.COP && data.rates.EUR) {
                            trmValue = data.rates.COP / data.rates.EUR;
                        }

                        $('#trm').val(trmValue.toFixed(2));
                        updateConversion();
                    })
                    .catch(error => console.error('Error en la solicitud:', error));
            }

            fetchTrm('COP');
            $('#currency').on('change', function() {
                const selectedCurrency = $(this).val();
                fetchTrm(selectedCurrency);
            });

            $('#budget').on('input', updateConversion);

            function updateConversion() {
                const budgetValue = parseFloat($('#budget').val().replace(/\./g, '').replace(',', '.')) || 0;
                const trmValue = parseFloat($('#trm').val()) || 0;
                const conversionValue = budgetValue * trmValue;

                // Cambiado para enviar solo el valor numérico
                $('#conversion').val(conversionValue.toFixed(2));
            }

            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        });

        document.querySelector('.acquisition-form-validation').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        form.reset();
                        $('.select2').val(null).trigger('change');
                        $('#file-list').empty();
                    });
                } else {
                    throw new Error(data.message || 'Hubo un problema al crear el plan.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Hubo un problema al procesar la solicitud.',
                    showConfirmButton: true
                });
            });
        });
    });
