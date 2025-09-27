<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Importar Preguntas</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Importa preguntas masivamente desde un archivo CSV con sus archivos correspondientes
                </p>
                @if($hasActiveBank)
                    <div class="mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Banco activo: {{ $this->getActiveBank()->name }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(!$hasActiveBank)
        <!-- Mensaje cuando no hay banco activo -->
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <h3 class="text-lg font-medium text-amber-800">
                                No hay banco activo
                            </h3>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-amber-700">
                            Para poder importar preguntas necesitas tener un banco activo. Ve a la sección de bancos y asegúrate de tener al menos uno activo.
                        </p>
                    </div>
                    <div class="mt-4">
                        <div class="flex space-x-3">
                            <a href="{{ route('banks.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-800 text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Ir a Bancos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Formulario de importación -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Datos de Importación
                </h3>
            </div>

            <div class="p-6">
                <form wire:submit.prevent="confirmImport">
                    <div class="grid grid-cols-1 gap-6">

                        <!-- Nombre de carpeta -->
                        <div>
                            <label for="folderName" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de Carpeta <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z"></path>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    wire:model="folderName"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent sm:text-sm @error('folderName') border-red-300 focus:ring-red-500 @enderror"
                                    id="folderName"
                                    placeholder="Ej: matematicas-2024, fisica-parcial1">
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Nombre de la carpeta que contiene los archivos de las preguntas a importar
                            </p>
                            @error('folderName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Seleccionar asignatura -->
                        <div>
                            <label for="selectedSubject" class="block text-sm font-medium text-gray-700 mb-2">
                                Asignatura <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select
                                    wire:model="selectedSubject"
                                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent sm:text-sm @error('selectedSubject') border-red-300 focus:ring-red-500 @enderror"
                                    id="selectedSubject">
                                    <option value="">Selecciona una asignatura...</option>
                                    @if(isset($subjects))
                                        @foreach($subjects as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Asignatura a la que pertenecen las preguntas que se van a importar
                            </p>
                            @error('selectedSubject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado de las preguntas -->
                        <div>
                            <label for="selectedStatus" class="block text-sm font-medium text-gray-700 mb-2">
                                Estado de las Preguntas <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <select
                                    wire:model="selectedStatus"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent sm:text-sm @error('selectedStatus') border-red-300 focus:ring-red-500 @enderror"
                                    id="selectedStatus">
                                    @if(isset($statusOptions))
                                        @foreach($statusOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Estado que se asignará a todas las preguntas importadas
                            </p>
                            @error('selectedStatus')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Archivo CSV -->
                        <div>
                            <label for="csvFile" class="block text-sm font-medium text-gray-700 mb-2">
                                Archivo CSV <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-green-400 transition-colors">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <div class="mt-4">
                                        <label for="csvFile" class="cursor-pointer">
                                            <span class="mt-2 block text-sm font-medium text-gray-900">
                                                Seleccionar archivo CSV
                                            </span>
                                            <input
                                                id="csvFile"
                                                name="csvFile"
                                                type="file"
                                                class="sr-only"
                                                wire:model="csvFile"
                                                accept=".csv,.txt">
                                        </label>
                                        <p class="mt-2 text-xs text-gray-500">
                                            CSV o TXT hasta 10MB
                                        </p>
                                    </div>
                                </div>

                                <!-- Preview del archivo -->
                                @if($csvFile)
                                    <div class="mt-4 border-t border-gray-200 pt-4">
                                        <div class="flex items-center bg-green-50 px-3 py-2 rounded-md">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700">{{ $csvFile->getClientOriginalName() }}</span>
                                            <span class="text-xs text-gray-500 ml-2">({{ number_format($csvFile->getSize() / 1024, 1) }} KB)</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @error('csvFile')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Información adicional -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        Estructura de Importación
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p><strong>Estructura de carpetas requerida:</strong></p>
                                        <div class="mt-2 bg-blue-100 p-3 rounded text-xs font-mono">
                                            storage/app/private/import/{nombre_carpeta}/<br>
                                            ├── data.csv<br>
                                            ├── p694/ (archivos de la pregunta)<br>
                                            ├── p699/ (archivos de la pregunta)<br>
                                            └── ...
                                        </div>

                                        <p class="mt-3"><strong>El archivo CSV debe contener las siguientes columnas:</strong></p>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li><strong>codigo:</strong> Código de la pregunta (p1, p2, p3, etc.)</li>
                                            <li><strong>capitulo:</strong> Código numérico del capítulo</li>
                                            <li><strong>tema:</strong> Código numérico del tema</li>
                                            <li><strong>dificultad:</strong> F (Fácil), N (Normal), D (Difícil)</li>
                                            <li><strong>tiempo_estimado:</strong> Tiempo en segundos (opcional)</li>
                                            <li><strong>comentarios:</strong> Comentarios opcionales</li>
                                        </ul>

                                        <p class="mt-3"><strong>Los archivos se copiarán a:</strong></p>
                                        <div class="mt-1 bg-blue-100 p-3 rounded text-xs font-mono">
                                            storage/app/private/{banco_activo}/{asignatura_slug}/{codigo_pregunta}/
                                        </div>

                                        <div class="mt-3 p-2 bg-yellow-100 border border-yellow-300 rounded">
                                            <p class="text-yellow-800 text-xs">
                                                <strong>Importante:</strong> Si ya existen archivos para una pregunta en el destino,
                                                la importación se detendrá para evitar sobrescribir archivos existentes.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-8 flex justify-end space-x-3 items-center">
                        {{-- Indicador de estado de validación --}}
                        <div class="flex items-center mr-4">
                            @if($isValidated)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                    Validado
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                    No validado
                                </span>
                            @endif
                        </div>

                        <button
                            type="button"
                            wire:click="resetForm"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                            {{ ($isImporting) ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Limpiar
                        </button>

                        <button
                            type="button"
                            wire:click="validateImport"
                            class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
                            {{ $isImporting ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                            </svg>
                            Validar
                        </button>

                        <button
                            type="submit"
                            class="inline-flex items-center px-6 py-2 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed @if(!$isValidated) bg-gray-400 text-gray-600 cursor-not-allowed @else bg-green-600 hover:bg-green-700 text-white @endif"
                            {{ ($isImporting || !$isValidated) ? 'disabled' : '' }}
                            @if(!$isValidated) title="Ejecute 'Validar' antes de importar" @endif>

                            @if($isImporting)
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Importando...
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                                Importar Preguntas
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

@script
<script>
    $wire.on('show-alert', (data) => {
        const alertData = Array.isArray(data) ? data[0] : data;

        let swalConfig = {
            title: alertData.title,
            text: alertData.message,
            icon: alertData.type,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#3b82f6'
        };

        // Si hay detalles (para errores múltiples), usar HTML
        if (alertData.details) {
            swalConfig.html = `
                <div class="text-left">
                    <p class="mb-3">${alertData.message}</p>
                    <div class="bg-gray-50 p-3 rounded-lg text-sm">
                        <strong>Detalles de errores:</strong><br>
                        ${alertData.details}
                    </div>
                </div>
            `;
            delete swalConfig.text;
        }

        // Ajustar colores según el tipo
        switch(alertData.type) {
            case 'success':
                swalConfig.confirmButtonColor = '#10b981';
                break;
            case 'error':
                swalConfig.confirmButtonColor = '#ef4444';
                break;
            case 'warning':
                swalConfig.confirmButtonColor = '#f59e0b';
                break;
        }

        Swal.fire(swalConfig);
    });

    // Listener para confirmaciones generadas desde Livewire (swal:confirm)
    $wire.on('swal:confirm', (data) => {
        const payload = Array.isArray(data) ? data[0] : data;
        Swal.fire({
            title: payload.title,
            html: payload.html ?? payload.text ?? '',
            icon: payload.icon || 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: payload.confirmButtonText || 'Sí',
            cancelButtonText: payload.cancelButtonText || 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                if (Array.isArray(payload.params)) {
                    $wire[payload.method](...payload.params);
                } else if (payload.params !== undefined && payload.params !== null) {
                    $wire[payload.method](payload.params);
                } else {
                    $wire[payload.method]();
                }
            }
        });
    });
</script>
@endscript
