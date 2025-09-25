<div class="px-4 py-5 sm:p-6">
    <div class="flex justify-end items-center mb-6">
        <button wire:click="showCreateForm" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Agregar
        </button>
    </div>

    <!-- Formulario de creación/edición -->
    @if($isCreate)
        <div class="mb-8">
            <div class="border border-blue-200 rounded-lg">
                <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg flex justify-between items-center">
                    <h5 class="font-medium flex items-center">
                        @if($isEdit)
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar Banco de Preguntas
                        @else
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Nuevo Banco de Preguntas
                        @endif
                    </h5>
                    <button wire:click="hideCreateForm" class="text-white hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 bg-gray-50">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="form.name"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('form.name') border-red-300 @enderror"
                                    id="name"
                                    placeholder="Ingrese el nombre del banco">
                                @error('form.name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="active" class="block text-sm font-medium text-gray-700 mb-2">
                                    Estado
                                </label>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-500 mr-3">Inactivo</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input
                                            type="checkbox"
                                            wire:model="form.active"
                                            class="sr-only peer"
                                            @if($form->active) checked @endif>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                    <span class="text-sm text-gray-500 ml-3">Activo</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Solo puede haber un banco activo a la vez. Los demás se desactivarán automáticamente.
                                </p>
                                @error('form.active')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción
                            </label>
                            <textarea
                                wire:model="form.description"
                                rows="3"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('form.description') border-red-300 @enderror"
                                id="description"
                                placeholder="Descripción opcional del banco de preguntas"></textarea>
                            @error('form.description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 flex space-x-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $isEdit ? 'Actualizar' : 'Guardar' }}
                            </button>
                            <button type="button" wire:click="hideCreateForm" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Tabla de bancos -->
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nombre
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Descripción
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Carpetas
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Estado
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Fecha
                </th>
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Acciones</span>
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($banks as $bank)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $bank->name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ Str::limit($bank->description, 50) ?: 'Sin descripción' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $bank->folders_count }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button
                            wire:click="toggleActive({{ $bank->id }})"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $bank->active ? 'bg-blue-600' : 'bg-gray-200' }}">
                            <span class="sr-only">Toggle bank status</span>
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $bank->active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $bank->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex space-x-2 justify-end">
                            <button
                                wire:click="edit({{ $bank->id }})"
                                class="text-indigo-600 hover:text-indigo-900"
                                title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button
                                wire:click="confirmDelete({{ $bank->id }})"
                                class="text-red-600 hover:text-red-900"
                                title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No hay bancos de preguntas</h3>
                            <p class="text-gray-500">
                                Comienza creando tu primer banco de preguntas.
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($banks->hasPages())
        <div class="flex justify-between items-center mt-6">
            <div class="flex justify-start">
                {{ $banks->links('pagination::custom') }}
            </div>
        </div>
    @endif
</div>

@script
<script>
    // Escuchar eventos de SweetAlert
    $wire.on('swal:success', (event) => {
        Swal.fire({
            title: event[0].title,
            text: event[0].text,
            icon: event[0].icon,
            timer: 3000,
            showConfirmButton: false
        });
    });

    $wire.on('swal:error', (event) => {
        Swal.fire({
            title: event[0].title,
            text: event[0].text,
            icon: event[0].icon,
            confirmButtonText: 'Entendido'
        });
    });

    $wire.on('swal:warning', (event) => {
        Swal.fire({
            title: event[0].title,
            text: event[0].text,
            icon: event[0].icon,
            confirmButtonText: 'Entendido',
            customClass: {
                popup: 'swal-wide'
            }
        });
    });

    $wire.on('swal:confirm', (event) => {
        Swal.fire({
            title: event[0].title,
            text: event[0].text,
            icon: event[0].icon,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: event[0].confirmButtonText,
            cancelButtonText: event[0].cancelButtonText
        }).then((result) => {
            if (result.isConfirmed) {
                $wire[event[0].method](event[0].params);
            }
        });
    });
</script>
@endscript
