@extends('layouts.app')

@section('content')
<div style="max-width: 400px">
  <h1>Osztály létrehozása</h1>

  <select id="school-select"></select>
  <input type="text" id="class-name" placeholder="Osztály neve pl. 10.A" />
  <button onclick="createClass()">Mentés</button>

  <p id="result"></p>
</div>

<script type="module">
  import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm'

  const supabase = createClient(
    import.meta.env.VITE_SUPABASE_URL,
    import.meta.env.VITE_SUPABASE_ANON_KEY
  )

  // Betöltjük az iskolákat a select-be
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

  loadSchools()

  async function createClass() {
    const name = document.getElementById('class-name').value
    const school_id = document.getElementById('school-select').value

    const { error } = await supabase
      .from('classes')
      .insert({ name, school_id })

    document.getElementById('result').innerText =
      error ? error.message : 'Osztály létrehozva!'
  }
</script>
@endsection
