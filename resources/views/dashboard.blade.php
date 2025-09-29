<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <!-- Banner del banco activo -->
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900">
            <x-placeholder-pattern class="pointer-events-none absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10" />
            <div class="relative flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2">
                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-200 dark:ring-emerald-900">Banco activo</span>
                        <span class="text-sm text-neutral-500 dark:text-neutral-400">Contexto de elaboración</span>
                    </div>
                    <h2 class="mt-2 text-xl font-semibold text-neutral-900 dark:text-neutral-100">Banco Base 2025</h2>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('banks.index') }}" class="rounded-lg border border-neutral-200 px-3 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">Ver bancos</a>
                </div>
            </div>
        </div>

        <!-- KPIs principales -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-6">
            <livewire:statistics.statistics-card-one-live metric="total" title="Total preguntas" subtitle="Todas las preguntas del banco activo" />
            <livewire:statistics.statistics-card-one-live metric="approved" title="Aprobadas" subtitle="Listas para usar" />
            <livewire:statistics.statistics-card-one-live metric="review" title="En revisión" subtitle="Pendientes de aprobación" />
            <livewire:statistics.statistics-card-one-live metric="draft" title="Borradores" subtitle="En edición" />
            <livewire:statistics.statistics-card-one-live metric="archived" title="Archivadas" subtitle="No activas" />
            <livewire:statistics.statistics-card-one-live metric="drawn" title="Sorteadas" subtitle="Usadas en algún examen" />
        </div>

        <!-- Leyendas de estados y dificultades -->
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">Estados de pregunta</h3>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800">Borrador</span>
                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">Aprobada</span>
                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800">Revisión</span>
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">Sorteada</span>
                    <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">Archivada</span>
                </div>
            </div>
            <div class="rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">Dificultad</h3>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800">Fácil</span>
                    <span class="inline-flex items-center rounded-full bg-neutral-200 px-3 py-1 text-xs font-medium text-neutral-800 dark:bg-neutral-700 dark:text-neutral-100">Normal</span>
                    <span class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1 text-xs font-medium text-rose-800">Difícil</span>
                </div>
            </div>
        </div>

        <!-- Distribución de preguntas por asignatura (solo diseño) -->
        <livewire:statistics.distribution-by-subject-live />
        
        <!-- Gráficas (placeholders) -->
        <div class="grid gap-4 lg:grid-cols-3">
            <div class="relative h-64 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-900">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                <div class="relative h-full p-4">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">Distribución por estado</h3>
                    <div class="mt-3 h-[180px] w-full rounded-lg border border-dashed border-neutral-300 dark:border-neutral-700"></div>
                </div>
            </div>
            <div class="relative h-64 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-900">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                <div class="relative h-full p-4">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">Distribución por dificultad</h3>
                    <div class="mt-3 h-[180px] w-full rounded-lg border border-dashed border-neutral-300 dark:border-neutral-700"></div>
                </div>
            </div>
            <div class="relative h-64 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-900">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                <div class="relative h-full p-4">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">Preguntas por asignatura</h3>
                    <div class="mt-3 h-[180px] w-full rounded-lg border border-dashed border-neutral-300 dark:border-neutral-700"></div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">Acciones rápidas</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('questions.index') }}" class="rounded-lg border border-neutral-200 px-3 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">Crear pregunta</a>
                    <a href="{{ route('import.questions.index') }}" class="rounded-lg border border-neutral-200 px-3 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">Importar preguntas</a>
                    <a href="{{ route('banks.index') }}" class="rounded-lg border border-neutral-200 px-3 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">Bancos</a>
                    <a href="{{ route('subject.index') }}" class="rounded-lg border border-neutral-200 px-3 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">Asignaturas</a>
                    <a href="{{ route('exams.index') }}" class="rounded-lg border border-neutral-200 px-3 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">Exámenes</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
