@extends('layouts.app')

@section('content')
    <div class="container">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1>Editar Tarea</h1>
        <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="task-form">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $task->nombre }}">
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion">{{ $task->descripcion }}</textarea>
            </div>
            <div class="mb-3">
                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" value="{{ $task->fecha_vencimiento }}">
            </div>

            @if(isset($tags))
            <div class="mb-3">
                <label>Etiquetas</label>
                @foreach($tags as $tag)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="etiquetas[]" value="{{ $tag->id }}" id="etiqueta_{{ $tag->id }}"
                               @if($task->tags->contains('id', $tag->id)) checked @endif>
                        <label class="form-check-label" for="etiqueta_{{ $tag->id }}">
                            {{ $tag->nombre }}
                        </label>
                    </div>
                @endforeach
            </div>
            @endif

            <button type="submit" class="btn btn-primary">Actualizar Tarea</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tagStack = {!! json_encode($task->tags->pluck('nombre')->toArray()) !!};

            function updateTagDisplay() {
                var tagContainer = document.getElementById('tagContainer');
                tagContainer.innerHTML = ''; 

                for (var i = 0; i < tagStack.length; i++) {
                    var tagBadge = document.createElement('div');
                    tagBadge.className = 'badge bg-secondary';
                    tagBadge.textContent = tagStack[i];
                    tagContainer.appendChild(tagBadge);
                }
            }

            document.getElementById('addTagBtn').addEventListener('click', function () {
                var newTag = document.getElementById('tagInput').value.trim();
                if (newTag !== '') {
                    tagStack.push(newTag);

                    if (tagStack.length > 5) {
                        tagStack.shift(); /
                    }

                    updateTagDisplay();
                }
            });

            document.getElementById('editTaskBtn').addEventListener('click', function () {
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'etiquetas';
                hiddenInput.value = tagStack.join(',');
                document.getElementById('editTaskForm').appendChild(hiddenInput);

                $.ajax({
                    url: '{{ route('tasks.update', $task->id) }}',
                    method: 'POST',
                    data: $('#editTaskForm').serialize(),
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            updateTagDisplay();
        });
    </script>
@endsection
