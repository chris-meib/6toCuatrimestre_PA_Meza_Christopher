
let tareas = [];
let filtro = 'todas';


window.onload = function() {
  cargarTareas();
};

async function cargarTareas() {
  let respuesta = await fetch('api.php?accion=listar');
  let datos = await respuesta.json();
  tareas = datos.tareas;
  mostrarTareas();
}

async function agregarTarea() {
  let input = document.getElementById('taskInput');
  let texto = input.value.trim();

  if (texto === '') return; // Si está vacío no hace nada

  let respuesta = await fetch('api.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ accion: 'agregar', descripcion: texto })
  });

  let datos = await respuesta.json();
  tareas.unshift(datos.tarea); // La agrega al inicio del arreglo
  input.value = '';
  mostrarTareas();
}

async function alternarTarea(id) {
  let tarea = tareas.find(t => t.id == id);
  let nuevoEstado = tarea.completada == 1 ? 0 : 1;

  await fetch('api.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ accion: 'actualizar', id: id, completada: nuevoEstado })
  });

  tarea.completada = nuevoEstado;
  mostrarTareas();
}

async function eliminarTarea(id) {
  await fetch('api.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ accion: 'eliminar', id: id })
  });

  tareas = tareas.filter(t => t.id != id);
  mostrarTareas();
}

function cambiarFiltro(nuevoFiltro) {
  filtro = nuevoFiltro;


  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.classList.remove('active');
    if (btn.dataset.filter === filtro) btn.classList.add('active');
  });

  mostrarTareas();
}


function mostrarTareas() {
  let lista = document.getElementById('taskList');
  lista.innerHTML = ''; // Limpia la lista


  let filtradas = tareas.filter(t => {
    if (filtro === 'pendientes')   return t.completada == 0;
    if (filtro === 'completadas')  return t.completada == 1;
    return true; // 'todas'
  });


  filtradas.forEach(tarea => {
    let li = document.createElement('li');
    if (tarea.completada == 1) li.classList.add('completada');

    li.innerHTML = `
      <input type="checkbox" ${tarea.completada == 1 ? 'checked' : ''}
             onchange="alternarTarea(${tarea.id})">
      <span onclick="alternarTarea(${tarea.id})">${tarea.descripcion}</span>
      <button onclick="eliminarTarea(${tarea.id})">Eliminar</button>
    `;

    lista.appendChild(li);
  });

  let pendientes = tareas.filter(t => t.completada == 0).length;
  document.getElementById('counter').textContent = pendientes;
}