@extends('layouts.app')

@section('content')
<div style="max-width: 400px">
  <h1>Iskola létrehozása</h1>

  <input
    type="text"
    id="school-name"
    placeholder="Iskola neve"
  />

  <button onclick="createSchool()">Mentés</button>

  <p id="result"></p>
</div>

<script type="module">
  import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm'

  const supabase = createClient(
    import.meta.env.VITE_SUPABASE_URL,
    import.meta.env.VITE_SUPABASE_ANON_KEY
  )

  async function createSchool() {
    const name = document.getElementById('school-name').value

    const { error } = await supabase
      .from('schools')
      .insert({ name })

    document.getElementById('result').innerText =
      error ? error.message : 'Iskola létrehozva'
  }
</script>
@endsection
