@extends('layouts.app')

@section('content')
<div style="max-width: 500px">
  <h1>Diák felvétele</h1>

  <!-- Iskola kiválasztása -->
  <select id="school-select"></select>

  <!-- Osztály kiválasztása -->
  <select id="class-select"></select>

  <!-- Diák kiválasztása (profilból) -->
  <select id="student-select"></select>

  <button onclick="addStudent()">Mentés</button>

  <p id="result"></p>
</div>

<script type="module">
  import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm'

  const supabase = createClient(
    import.meta.env.VITE_SUPABASE_URL,
    import.meta.env.VITE_SUPABASE_ANON_KEY
  )

  async function loadSchools() {
    const { data } = await supabase
      .from('schools')
      .select('id, name')
      .order('name')

    const select = document.getElementById('school-select')
    data.forEach(s => {
      const option = document.createElement('option')
      option.value = s.id
      option.text = s.name
      select.appendChild(option)
    })
    
    // betöltés után frissítjük az osztályokat
    loadClasses()
  }

  async function loadClasses() {
    const school_id = document.getElementById('school-select').value
    const { data } = await supabase
      .from('classes')
      .select('id, name')
      .eq('school_id', school_id)
      .order('name')

    const select = document.getElementById('class-select')
    select.innerHTML = ''
    data.forEach(c => {
      const option = document.createElement('option')
      option.value = c.id
      option.text = c.name
      select.appendChild(option)
    })
  }

  async function loadStudents() {
    const { data } = await supabase
      .from('profiles')
      .select('id, name')
      .eq('role', 'student')
      .order('name')

    const select = document.getElementById('student-select')
    data.forEach(s => {
      const option = document.createElement('option')
      option.value = s.id
      option.text = s.name
      select.appendChild(option)
    })
  }

  async function addStudent() {
    const school_id = document.getElementById('school-select').value
    const class_id = document.getElementById('class-select').value
    const student_id = document.getElementById('student-select').value

    const { error } = await supabase
      .from('students')
      .insert({ id: student_id, school_id, class_id })

    document.getElementById('result').innerText =
      error ? error.message : 'Diák felvéve az osztályhoz!'
  }

  document.getElementById('school-select').addEventListener('change', loadClasses)

  loadSchools()
  loadStudents()
</script>
@endsection
