<x-layouts.app title="Sortear preguntas del examen">
    <h1 class="title-page">
        <flux:icon.arrow-path-rounded-square />
        Sortear preguntas del examen
    </h1>
    <hr class="my-4">
    <livewire:exams.exam-questions-live :examId="$id" />

</x-layouts.app>
