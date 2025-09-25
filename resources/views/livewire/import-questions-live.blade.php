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

    <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('warning'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">
                        {{ session('warning') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

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
                <form wire:submit.prevent="import">
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
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        Formato del archivo CSV
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>El archivo CSV debe contener las siguientes columnas:</p>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li><strong>codigo:</strong> Código de la pregunta (p1, p2, p3, etc.)</li>
                                            <li><strong>capitulo:</strong> Nombre del capítulo</li>
                                            <li><strong>tema:</strong> Nombre del tema</li>
                                            <li><strong>dificultad:</strong> easy, medium, hard</li>
                                            <li><strong>estado:</strong> draft, active, inactive</li>
                                            <li><strong>tiempo_estimado:</strong> Tiempo en segundos</li>
                                            <li><strong>comentarios:</strong> Comentarios opcionales</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <button
                            type="button"
                            wire:click="resetForm"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                            {{ $isImporting ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Limpiar
                        </button>

                        <button
                            type="submit"
                            class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ $isImporting ? 'disabled' : '' }}>

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
