<div class="space-y-6">
    <!-- Header simple con información de contexto -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $exam->name }}</h3>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span>Código: {{ $exam->code }}</span>
                        <span>•</span>
                        <span>Período: {{ $exam->term->name ?? 'Sin período' }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button wire:click="toggleSelectForm" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Agregar Pregunta
                </button>
                <a href="{{ route('exams.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario de selección de preguntas -->
    @if($showSelectForm)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Seleccionar Preguntas
                    </h4>
                    <button wire:click="hideSelectForm" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <!-- Switch para modo de selección -->
                <div class="mb-6 flex items-center justify-center">
                    <div class="bg-gray-100 p-1 rounded-lg inline-flex">
                        <button
                            wire:click="$set('selectionMode', 'individual')"
                            class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 @if($selectionMode === 'individual') bg-white text-blue-600 shadow-sm @else text-gray-600 hover:text-gray-800 @endif">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pregunta Individual
                        </button>
                        <button
                            wire:click="$set('selectionMode', 'group')"
                            class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 @if($selectionMode === 'group') bg-white text-blue-600 shadow-sm @else text-gray-600 hover:text-gray-800 @endif">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Preguntas por Grupo
                        </button>
                    </div>
                </div>

                <!-- Formulario para Pregunta Individual -->
                @if($selectionMode === 'individual')
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Select de Asignaturas -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Asignatura <span class="text-red-500">*</span>
                            </label>
                            <select
                                wire:model.live="selectedSubjectId"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm"
                                id="subject">
                                <option value="">Seleccione una asignatura</option>
                                @foreach($this->subjectsWithQuestions as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select de Capítulos -->
                        <div>
                            <label for="chapter" class="block text-sm font-medium text-gray-700 mb-2">
                                Capítulo <span class="text-red-500">*</span>
                            </label>
                            <select
                                wire:model.live="selectedChapterId"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm @if(!$selectedSubjectId) bg-gray-100 @endif"
                                id="chapter"
                                @if(!$selectedSubjectId) disabled @endif>
                                <option value="">Seleccione un capítulo</option>
                                @if($selectedSubjectId)
                                    @foreach($this->chapters as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Select de Temas -->
                        <div>
                            <label for="topic" class="block text-sm font-medium text-gray-700 mb-2">
                                Tema <span class="text-red-500">*</span>
                            </label>
                            <select
                                wire:model.live="selectedTopicId"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm @if(!$selectedChapterId) bg-gray-100 @endif"
                                id="topic"
                                @if(!$selectedChapterId) disabled @endif>
                                <option value="">Seleccione un tema</option>
                                @if($selectedChapterId)
                                    @foreach($this->topics as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Select de Dificultad -->
                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                                Dificultad
                            </label>
                            <select
                                wire:model.live="selectedDifficulty"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm @if(!$selectedTopicId) bg-gray-100 @endif"
                                id="difficulty"
                                @if(!$selectedTopicId) disabled @endif>
                                <option value="">Todas las dificultades</option>
                                @if($selectedTopicId)
                                    @foreach($this->difficulties as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Información de preguntas disponibles para modo individual -->
                    @if($selectedTopicId)
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m-1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-blue-800">
                                    Preguntas disponibles:
                                    <span class="font-bold">{{ $this->availableQuestionsCount }}</span>
                                    @if($selectedDifficulty)
                                        ({{ $this->difficulties[$selectedDifficulty] }})
                                    @else
                                        (Todas las dificultades)
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Botón Sortear para modo individual -->
                    <div class="mt-6 flex justify-end">
                        <button
                            wire:click="sortearPregunta"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 @if(!$selectedTopicId || $this->availableQuestionsCount == 0) opacity-50 cursor-not-allowed @endif"
                            @if(!$selectedTopicId || $this->availableQuestionsCount == 0) disabled @endif>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h3a1 1 0 011 1v2h4a1 1 0 011 1v2a1 1 0 01-1 1H6a1 1 0 01-1-1V5a1 1 0 011-1h1zM6 10v8a2 2 0 002 2h8a2 2 0 002-2v-8H6z"></path>
                            </svg>
                            Sortear
                            @if($selectedTopicId && $this->availableQuestionsCount > 0)
                                ({{ $this->availableQuestionsCount }})
                            @endif
                        </button>
                    </div>
                @endif

                <!-- Formulario para Preguntas por Grupo -->
                @if($selectionMode === 'group')
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-6 border border-green-200">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="text-lg font-semibold text-gray-900">Selección por Grupo</h5>
                                <p class="text-sm text-gray-600">Selecciona múltiples preguntas de varios capítulos de una vez</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Select de Asignatura -->
                            <div>
                                <label for="group-subject" class="block text-sm font-medium text-gray-700 mb-2">
                                    Asignatura <span class="text-red-500">*</span>
                                </label>
                                <select
                                    wire:model.live="selectedSubjectId"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent sm:text-sm"
                                    id="group-subject">
                                    <option value="">Seleccione una asignatura</option>
                                    @foreach($this->subjectsWithQuestions as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Input de Capítulos -->
                            <div>
                                <label for="group-chapters" class="block text-sm font-medium text-gray-700 mb-2">
                                    Capítulos <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model.live="groupChapters"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent sm:text-sm @if(!$selectedSubjectId) bg-gray-100 @endif"
                                    id="group-chapters"
                                    placeholder="1,2,3 o 1-5 o *"
                                    @if(!$selectedSubjectId) disabled @endif>
                                <div class="mt-1 text-xs text-gray-500 space-y-1">
                                    <p class="flex items-start">
                                        <svg class="w-3 h-3 mt-0.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span><strong>Ejemplos:</strong></span>
                                    </p>
                                    <p class="ml-4 text-green-600">• <code class="bg-green-50 px-1 rounded">1,2,3</code> - Capítulos específicos</p>
                                    <p class="ml-4 text-blue-600">• <code class="bg-blue-50 px-1 rounded">1-5</code> - Rango del 1 al 5</p>
                                    <p class="ml-4 text-purple-600">• <code class="bg-purple-50 px-1 rounded">1-3,7,10-15</code> - Combinado</p>
                                    <p class="ml-4 text-orange-600">• <code class="bg-orange-50 px-1 rounded">*</code> - Todos los capítulos</p>
                                </div>
                            </div>

                            <!-- Select de Dificultad -->
                            <div>
                                <label for="group-difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                                    Dificultad
                                </label>
                                <select
                                    wire:model.live="selectedDifficulty"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent sm:text-sm @if(!$selectedSubjectId || !$groupChapters) bg-gray-100 @endif"
                                    id="group-difficulty"
                                    @if(!$selectedSubjectId || !$groupChapters) disabled @endif>
                                    <option value="">Todas las dificultades</option>
                                    @foreach($this->difficulties as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Input de Cantidad (último campo) -->
                            <div>
                                <label for="group-quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cantidad <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    wire:model="groupQuantity"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent sm:text-sm @if(!$selectedSubjectId || !$groupChapters) bg-gray-100 @endif"
                                    id="group-quantity"
                                    placeholder="10"
                                    min="1"
                                    max="100"
                                    @if(!$selectedSubjectId || !$groupChapters) disabled @endif>
                                <p class="mt-1 text-xs text-gray-500">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Total de preguntas a sortear
                                </p>
                            </div>


                        </div>

                        <!-- Información de preguntas disponibles para modo grupo -->
                        @if($selectedSubjectId && $groupChapters)
                            <div class="mt-4 p-4 bg-green-100 rounded-lg border border-green-300">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2v-8a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-green-800">
                                        <span class="font-bold text-lg">{{ $this->availableGroupQuestionsCount }}</span> preguntas disponibles
                                    </span>
                                </div>
                                @if($this->availableGroupQuestionsCount > 0 && $groupQuantity > $this->availableGroupQuestionsCount)
                                    <div class="mt-2 p-2 bg-yellow-100 border border-yellow-300 rounded-md">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <span class="text-sm text-yellow-800">
                                                ⚠️ La cantidad solicitada (<strong>{{ $groupQuantity }}</strong>) excede las preguntas disponibles (<strong>{{ $this->availableGroupQuestionsCount }}</strong>)
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Botón Sortear para modo grupo -->
                        <div class="mt-6 flex justify-end">
                            <button
                                wire:click="sortearGrupo"
                                class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 @if(!$selectedSubjectId || !$groupChapters || !$groupQuantity || $this->availableGroupQuestionsCount == 0) opacity-50 cursor-not-allowed @endif"
                                @if(!$selectedSubjectId || !$groupChapters || !$groupQuantity || $this->availableGroupQuestionsCount == 0) disabled @endif>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Sortear Grupo
                                @if($groupQuantity > 1)
                                    ({{ $groupQuantity }} preguntas)
                                @endif
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Detalles de la pregunta sorteada -->
    @if($showQuestionDetails && $selectedQuestion)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pregunta Sorteada
                    </h4>
                    <button wire:click="cancelarSeleccion" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <!-- Información de la pregunta en una sola tarjeta compacta -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="text-lg font-semibold text-gray-900">
                                    Código:
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 ml-2">
                                        {{ $selectedQuestion->code }}
                                    </span>
                                </h5>
                                <p class="text-sm text-gray-600 mt-1">Pregunta seleccionada aleatoriamente</p>
                            </div>
                        </div>
                        <!-- Estado y Dificultad en la esquina -->
                        <div class="flex flex-col items-end space-y-2">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'archived' => 'bg-red-100 text-red-800'
                                ];
                                $difficultyColors = [
                                    'easy' => 'bg-green-100 text-green-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'hard' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$selectedQuestion->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ \App\Enums\QuestionStatus::from($selectedQuestion->status)->label() }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $difficultyColors[$selectedQuestion->difficulty] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $this->difficulties[$selectedQuestion->difficulty] ?? $selectedQuestion->difficulty }}
                            </span>
                        </div>
                    </div>

                    <!-- Grid compacto con toda la información -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Información académica -->
                        <div class="space-y-3">
                            <h6 class="text-sm font-semibold text-gray-700 uppercase tracking-wide border-b border-gray-300 pb-1">
                                Información Académica
                            </h6>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Asignatura:</span>
                                    <span class="font-medium text-gray-900">{{ $selectedQuestion->subject->name }} ({{ $selectedQuestion->subject->code }})</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Capítulo:</span>
                                    <span class="font-medium text-gray-900">{{ $selectedQuestion->chapter->name }} ({{ $selectedQuestion->chapter->code }})</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tema:</span>
                                    <span class="font-medium text-gray-900">{{ $selectedQuestion->topic->name }} ({{ $selectedQuestion->topic->code }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Información técnica -->
                        <div class="space-y-3">
                            <h6 class="text-sm font-semibold text-gray-700 uppercase tracking-wide border-b border-gray-300 pb-1">
                                Información Técnica
                            </h6>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Banco:</span>
                                    <span class="font-medium text-gray-900">{{ $selectedQuestion->bank->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Estado Banco:</span>
                                    <span class="font-medium {{ $selectedQuestion->bank->active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $selectedQuestion->bank->active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Ruta:</span>
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded font-mono text-gray-800 max-w-xs truncate">
                                        {{ $selectedQuestion->path }}
                                    </code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button
                        wire:click="cancelarSeleccion"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancelar
                    </button>
                    <button
                        wire:click="sortearPregunta"
                        class="inline-flex items-center px-4 py-2 border border-orange-300 bg-orange-50 hover:bg-orange-100 text-orange-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Sortear Otra
                    </button>
                    <button
                        wire:click="agregarPregunta"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Elegir
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Vista de preguntas sorteadas en grupo -->
    @if($showGroupQuestions && !empty($selectedQuestions))
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Preguntas Sorteadas ({{ count($selectedQuestions) }})
                    </h4>
                    <div class="flex items-center space-x-3">
                        <button
                            wire:click="sortearGrupo"
                            class="inline-flex items-center px-3 py-1.5 border border-orange-300 bg-orange-50 hover:bg-orange-100 text-orange-700 text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Sortear Otro Grupo
                        </button>
                        <button wire:click="cancelarGrupo" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <!-- Botones de acción globales -->
                <div class="mb-6 flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Revisa las preguntas sorteadas y decide cuáles agregar al examen
                    </div>
                    <button
                        wire:click="guardarTodasLasPreguntas"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Guardar Todas ({{ count($selectedQuestions) }})
                    </button>
                </div>

                <!-- Grid de preguntas sorteadas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($selectedQuestions as $index => $question)
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200 relative">
                            <!-- Header de la tarjeta -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-xs font-bold">{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $question['code'] }}
                                        </span>
                                    </div>
                                </div>
                                <!-- Badges de estado y dificultad -->
                                <div class="flex flex-col items-end space-y-1">
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'archived' => 'bg-red-100 text-red-800'
                                        ];
                                        $difficultyColors = [
                                            'easy' => 'bg-green-100 text-green-800',
                                            'medium' => 'bg-yellow-100 text-yellow-800',
                                            'hard' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $difficultyColors[$question['difficulty']] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $this->difficulties[$question['difficulty']] ?? $question['difficulty'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Información compacta -->
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Asignatura:</span>
                                    <span class="font-medium text-gray-900 text-right">{{ $question['subject']['name'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Capítulo:</span>
                                    <span class="font-medium text-gray-900 text-right">{{ $question['chapter']['name'] }} ({{ $question['chapter']['code'] }})</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tema:</span>
                                    <span class="font-medium text-gray-900 text-right">{{ $question['topic']['name'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Banco:</span>
                                    <span class="font-medium text-gray-900 text-right">{{ $question['bank']['name'] }}</span>
                                </div>
                            </div>

                            <!-- Botón agregar individual -->
                            <div class="mt-4 flex justify-end">
                                <button
                                    wire:click="agregarPreguntaIndividual({{ $question['id'] }})"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Agregar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Contenido principal - Lista de preguntas del examen -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-medium text-gray-900">Lista de Preguntas del Examen</h4>
                    <p class="mt-1 text-sm text-gray-500">
                        Preguntas seleccionadas para este examen ({{ $this->examQuestions->count() }} preguntas)
                    </p>
                </div>
                @if($this->examQuestions->count() > 0)
                    <div class="flex items-center space-x-3">
                        <button
                            wire:click="exportarPreguntas"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Exportar Preguntas ({{ $this->examQuestions->count() }})
                        </button>
                        <button
                            wire:click="confirmCerrarSorteo"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Cerrar Sorteo ({{ $this->examQuestions->count() }})
                        </button>
                    </div>
                @endif
            </div>
        </div>

        @if($this->examQuestions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Código
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Asignatura
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Capítulo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tema
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dificultad
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Banco
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($this->examQuestions as $index => $examQuestion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $examQuestion->question->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <div class="font-medium">{{ $examQuestion->question->subject->name }}</div>
                                        <div class="text-gray-500 text-xs">({{ $examQuestion->question->subject->code }})</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <div class="font-medium">{{ $examQuestion->question->chapter->name }}</div>
                                        <div class="text-gray-500 text-xs">({{ $examQuestion->question->chapter->code }})</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <div class="font-medium">{{ $examQuestion->question->topic->name }}</div>
                                        <div class="text-gray-500 text-xs">({{ $examQuestion->question->topic->code }})</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $difficultyColors = [
                                            'easy' => 'bg-green-100 text-green-800',
                                            'medium' => 'bg-yellow-100 text-yellow-800',
                                            'hard' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $difficultyColors[$examQuestion->question->difficulty] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $this->difficulties[$examQuestion->question->difficulty] ?? $examQuestion->question->difficulty }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <div class="font-medium">{{ $examQuestion->question->bank->name }}</div>
                                        <div class="text-xs {{ $examQuestion->question->bank->is_active ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $examQuestion->question->bank->active ? 'Activo' : 'Inactivo' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button
                                        wire:click="confirmDeleteQuestion({{ $examQuestion->question->id }})"
                                        class="text-red-600 hover:text-red-900 transition-colors"
                                        title="Eliminar pregunta del examen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay preguntas en este examen</h3>
                    <p class="text-gray-500 mb-4">
                        Utiliza el botón "Agregar Pregunta" para comenzar a añadir preguntas a este examen.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

@script
<script>
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

    $wire.on('swal:info', (event) => {
        Swal.fire({
            title: event[0].title,
            text: event[0].text,
            icon: event[0].icon,
            confirmButtonText: 'OK'
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
