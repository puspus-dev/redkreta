@extends('layouts.app')

@section('content')
<div style="max-width: 500px">
  <h1>Tanár felvétele</h1>

  <!-- Iskola kiválasztása -->
  <select id="school-select"></select>

  <!-- Tanár kiválasztása (már meglévő profil) -->
  <select id="teacher-select"></select>

  <button onclick="addTeacher()">Mentés</button>

  <p id="result"></p>
</div>

<script type="module">
  import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm'

  const supabase = createClient(
    import.meta.env.VITE_SUPABASE_URL,
    import.meta.env.VITE_SUPABASE_ANON_KEY
  )

  // Betöltjük az iskolákat
  async function loadSchools() {
    const { data, error } = await supabase
      .from('schools')
      .select('id, name')
      .order('name')

    if (error) return console.error(error)

    const select = document.getElementById('school-select')
    data.forEach(school => {
      const option = document.createElement('option')
      option.value = school.id
      option.text = school.name
      select.appendChild(option)
    })
  }

  // Betöltjük a tanárokat (profiles.role = 'teacher')
  async function loadTeachers() {
    const { data, error } = await supabase
      .from('profiles')
      .select('id, name')
      .eq('role', 'teacher')
      .order('name')

    if (error) return console.error(error)

    const select = document.getElementById('teacher-select')
    data.forEach(teacher => {
      const option = document.createElement('option')
      option.value = teacher.id
      option.text = teacher.name
      select.appendChild(option)
    })
  }

  async function addTeacher() {
    const school_id = document.getElementById('school-select').value
    const teacher_id = document.getElementById('teacher-select').value

    const { error } = await supabase
      .from('teachers')
      .insert({ id: teacher_id, school_id })

    document.getElementById('result').innerText =
      error ? error.message : 'Tanár felvéve az iskolához!'
  }

  loadSchools()
  loadTeachers()
</script>
@endsection
